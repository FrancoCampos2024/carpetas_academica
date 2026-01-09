<?php

use Illuminate\Support\Collection;
use Carbon\Carbon;

if (!function_exists('formatoFecha')) {
    // Función para dar formato a la fecha
    function formatoFecha($fecha)
    {
        return date('d/m/Y', strtotime($fecha));
    }
}

if (!function_exists('formatoFechaHoras')) {
    // Función para dar formato a la fecha y hora
    function formatoFechaHoras($fecha)
    {
        return date('h:i A d/m/Y', strtotime($fecha));
    }
}

if (!function_exists('formatoFechaText')) {
    function formatoFechaText($fecha)
    {
        // Validar que la fecha sea válida
        if (!$fecha) {
            return null;
        }

        // Crear una instancia de Carbon con la fecha
        $date = Carbon::parse($fecha);

        // Configurar el idioma español
        $date->locale('es'); // Establecer el idioma español

        // Formatear la fecha de la manera que deseas
        return $date->translatedFormat('d M Y h:i a');
    }
}

if (!function_exists('formatoSoloFechaText')) {
    function formatoSoloFechaText($fecha)
    {
        // Validar que la fecha sea válida
        if (!$fecha) {
            return null;
        }

        // Crear una instancia de Carbon con la fecha
        $date = Carbon::parse($fecha);

        // Configurar el idioma español
        $date->locale('es'); // Establecer el idioma español

        // Formatear la fecha de la manera que deseas
        return $date->translatedFormat('d M Y');
    }
}

if (!function_exists('formatoHora')) {
    function formatoHora($hora)
    {
        // Validar que la hora sea válida
        if (!$hora) {
            return '00:00';
        }

        return date('g:i a', strtotime($hora));
    }
}

if (!function_exists('formatoHora24')) {
    function formatoHora24($hora)
    {
        // Validar que la hora sea válida
        if (!$hora) {
            return '00:00';
        }

        return date('H:i', strtotime($hora));
    }
}

if (!function_exists('mensajeToastr')) {
    // Función para mostrar mensajes con Toastr
    function mensajeToastr(
        $boton_cerrar,
        $progreso_avance,
        $duracion,
        $titulo,
        $tipo,
        $mensaje,
        $posicion_y,
        $posicion_x
    ): Collection {
        $mensaje = collect([
            'boton_cerrar' => $boton_cerrar,
            'progreso_avance' => $progreso_avance,
            'duracion' => $duracion,
            'titulo' => $titulo,
            'tipo' => $tipo,
            'mensaje' => $mensaje,
            'posicion_y' => $posicion_y,
            'posicion_x' => $posicion_x,
        ]);
        return $mensaje;
    }
}
