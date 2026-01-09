<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class AlumnoService
{

    public function getAlumno(int $id_alumno): ?array
    {
        $response = Http::withHeaders([
            'X-API-KEY' => config('services.unia.key'),
        ])->get(config('services.unia.url') . "obtener-alumno/{$id_alumno}");

        if (!$response->successful()) return null;

        $json = $response->json();

        $data = $json['data'] ?? $json;

        return is_array($data) ? $data : null;
    }



    public function getAlumnoCached(int $id_alumno, int $minutes = 60): ?array
    {
        return Cache::remember(
            "unia_alumno_{$id_alumno}",
            now()->addMinutes($minutes),
            fn () => $this->getAlumno($id_alumno)
        );
    }


    public function getAlumnos($searchTerm, $limit = 10): array
    {
        try {
            $response = Http::withHeaders([
                    'X-API-KEY' => config('services.unia.key'),
                ])
                ->connectTimeout(3) // tiempo para conectar
                ->timeout(8)        // tiempo total de request
                ->retry(1, 200)     // 1 reintento (opcional)
                ->get(config('services.unia.url') . 'listar-alumnos', [
                    'searchTerm' => $searchTerm,
                    'limit' => $limit,
                ]);

            if (!$response->successful()) {
                return [
                    'ok' => false,
                    'message' => 'No se pudo obtener alumnos (HTTP ' . $response->status() . ').',
                    'data' => [],
                ];
            }

            $json = $response->json();
            $data = $json['data'] ?? $json;

            return [
                'ok' => true,
                'message' => null,
                'data' => is_array($data) ? $data : [],
            ];

        } catch (\Throwable $e) {
            report($e); // lo manda a logs para que puedas ver el motivo real

            return [
                'ok' => false,
                'message' => 'No se pudo conectar con el servidor de alumnos. Intenta nuevamente.',
                'data' => [],
            ];
        }
    }

    public function findById(?int $id): ?array
    {
        if (!$id) return null;

        $cacheKey = "alumno_api_{$id}";
        $cached = cache()->get($cacheKey);

        if (is_array($cached)) {
            return $cached;
        }

        $data = $this->getAlumno($id);

        if (is_array($data)) {
            cache()->put($cacheKey, $data, 3600);
        }

        return $data;
    }
}
