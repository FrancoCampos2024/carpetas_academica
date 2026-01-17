@php

    $nombre_institucion    = $nombre_institucion    ?? 'UNIVERSIDAD NACIONAL INTERCULTURAL DE LA AMAZONIA';
    $direccion_institucion = $direccion_institucion ?? 'CAR. SAN JOSE KM. 0.63 CAS. SAN JOSE (COSTADO INSTITUTO BILINGUE)';
    $ruc_institucion       = $ruc_institucion       ?? '20393146657';

    $items = $items ?? collect();
    $generado = $generado ?? now();

    $filtros_limpios = $filtros_limpios ?? [];
    $tipo  = $filtros_limpios['tipo']  ?? 'TODOS';
    $desde = $filtros_limpios['desde'] ?? 'TODOS';
    $hasta = $filtros_limpios['hasta'] ?? 'TODOS';

    $logoPath = public_path('media/logo-unia.webp');
    if (!file_exists($logoPath)) $logoPath = public_path('media/logo-unia.png');

    $logoSrc = null;
    if (file_exists($logoPath)) {
        $mime = @mime_content_type($logoPath) ?: 'image/png';
        $logoSrc = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($logoPath));
    }

    $usuario_nombre = trim(
        (auth()->user()?->persona?->nombre_persona ?? '') . ' ' .
        (auth()->user()?->persona?->apellidopaterno_persona ?? '') . ' ' .
        (auth()->user()?->persona?->apellidomaterno_persona ?? '')
    );
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Documentos</title>
    <style>
        @page { margin: 22px 20px; }
        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size:12px; color:#222; margin:0; padding:0;
        }

        table { width:100%; border-collapse: collapse; }
        thead { display: table-header-group; }
        tfoot { display: table-row-group; }
        tr, img { page-break-inside: avoid; }

        .wrap { width:100%; margin:0 auto; }
        .row { display: table; width:100%; }
        .col { display: table-cell; vertical-align: top; }
        .col-left { width:64%; padding-right:10px; }
        .col-right { width:36%; padding-left:10px; }

        .card { border:1px solid #c9ced6; border-radius:6px; background:#fff; }
        .p10{ padding:10px; } .p14{ padding:14px; }
        .mb18{ margin-bottom:18px; } .header-min{ min-height:88px; }

        .head{text-align:center;}
        .head .logo{width:60px; height:auto; margin:0 auto 6px; display:block;}
        .head .uni{font-weight:700; font-size:16px;}
        .head .dir{color:#6b7280; font-size:12px;}
        .head .ruc{color:#6b7280; font-size:11px;}

        .panel-title{
            font-weight:700; text-transform:uppercase; text-align:center;
            border:1px dashed #c9ced6; padding:8px; border-radius:6px; margin-bottom:10px;
        }

        .info-row{
            display:flex; justify-content:space-between; margin-bottom:6px;
        }
        .label{font-weight:700;}
        .muted{color:#6b7280;}

        .info-grid{display:table; width:100%; table-layout:fixed;}
        .info-cell{display:table-cell; vertical-align:top; padding:0 6px;}
        .info-cell.left{width:100%; padding-left:0;}

        th, td { padding:9px 10px; font-size:12px; }
        thead th {
            background:#eef2f7; color:#111827;
            border-bottom:1px solid #d7dbe3; text-align:left;
        }
        tbody td { border-bottom:1px dashed #dfe3ea; vertical-align:top; }

        .text-center{text-align:center;}
        .text-right{text-align:right;}
        .small{ font-size:10px; color:#6b7280; }
        .truncate{
            max-width: 260px;
            overflow:hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display:block;
            margin: 0 auto;
        }
        .file-name{ font-weight:700; color:#111827; }
        .badge{
            display:inline-block;
            padding:4px 10px;
            border-radius:999px;
            border:1px solid #c7d2fe;
            background:#eef2ff;
            color:#1d4ed8;
            font-size:11px;
            font-weight:700;
        }

        .footer{
            position:fixed; left:0; right:0; bottom:10px;
            font-size:10px; color:#6b7280; padding:0 12px;
        }
        .footer .bold{font-weight:700; color:#111827;}

        table {
            width: 100%;
            table-layout: fixed; /* Esto es vital para que respete los anchos de columna */
            border-collapse: collapse;
        }

        td {
            word-wrap: break-word; /* Fuerza el salto de línea en palabras largas */
            overflow-wrap: break-word;
            vertical-align: top;
            padding: 5px;
        }
    </style>
</head>
<body>

<div class="wrap">

    {{-- CABECERA --}}
    <div class="row mb18">
        <div class="col col-left">
            <div class="p14 header-min">
                <div class="head">
                    @if(isset($logoSrc) && $logoSrc)
                        <img src="{{ $logoSrc }}" class="logo" alt="Logo UNIA">
                    @endif
                    <div class="uni">{{ strtoupper($nombre_institucion) }}</div>
                    <div class="dir">{{ $direccion_institucion }}</div>
                    <div class="ruc">RUC: {{ $ruc_institucion }}</div>
                </div>
            </div>
        </div>

        <div class="col col-right">
            {{-- Título dinámico según el estado --}}
            <div class="panel-title" style="{{ ($es_eliminados ?? false) ? 'color: #e53e3e;' : '' }}">
                REPORTE: DOCUMENTOS {{ ($es_eliminados ?? false) ? 'ELIMINADOS' : 'CREADOS' }}
            </div>
            <div class="card p10 header-min">
                <div class="info-grid">
                    <div class="info-cell left">
                        <div class="info-row">
                            <span class="label">Fecha reporte:</span>
                            <span>{{ \Carbon\Carbon::parse($generado)->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Registros:</span>
                            <span>{{ count($items) }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Tipo Doc.:</span>
                            <span style="text-transform: uppercase;">{{ $filtros_limpios['tipo'] ?? 'TODOS' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Rango:</span>
                            <span style="text-transform: uppercase;">{{ $filtros_limpios['desde'] }} al {{ $filtros_limpios['hasta'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLA --}}
    <div class="card p14">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th class="text-center" style="width:30px;">N°</th>
                    <th class="text-center" style="width:230px;">ALUMNO</th>
                    <th class="text-center" style="width:110px;">TIPO</th>
                    <th class="text-center" style="width:250px;">ARCHIVO</th>
                    <th class="text-center" style="width:130px;">
                        {{ ($es_eliminados ?? false) ? 'FECHA ELIMINACIÓN' : 'FECHA CREACIÓN' }}
                    </th>
                    <th class="text-center" style="width:100px;">
                        {{ ($es_eliminados ?? false) ? 'ELIMINADO POR' : 'USUARIO' }}
                    </th>
                </tr>
            </thead>

            <tbody>
            @php $i=0; @endphp
            @foreach($items as $doc)
                @php
                    $isEliminado = ($es_eliminados ?? false);

                    $alumnoNombre = $doc['alumno_nombre'] ?? 'No disponible';
                    $alumnoCE     = $doc['alumno_codigo'] ?? '-';
                    $alumnoDNI    = $doc['alumno_dni'] ?? '-';
                    $alumnoEsc    = $doc['alumno_escuela'] ?? 'No disponible';

                    $tipoNombre = $doc['tipo_nombre'] ?? 'General';
                    $nombreArchivo = $doc['nombre_documento'] ?? 'Sin nombre';
                    $rutaCorta = \Illuminate\Support\Str::after($doc['ruta_documento'] ?? '', 'Documentos/');

                    // Lógica dinámica de fecha según el modo
                    $fechaRaw = $isEliminado ? ($doc['au_fechael'] ?? null) : ($doc['au_fechacr'] ?? null);
                    $fechaFormateada = !empty($fechaRaw)
                        ? \Carbon\Carbon::parse($fechaRaw)->format('d/m/Y H:i')
                        : '-';

                    $usuario = $doc['usuario_nombre'] ?? '-';
                @endphp

                <tr>
                    <td class="text-center" style="vertical-align: middle;">{{ ++$i }}</td>

                    <td class="text-left">
                        <div style="font-weight:700; color:#111827; font-size: 10px;">{{ strtoupper($alumnoNombre) }}</div>
                        <div class="small" style="font-size: 9px; color: #4b5563;">
                            CE: {{ $alumnoCE }} | DNI: {{ $alumnoDNI }}
                        </div>
                        <div class="small" style="font-size: 8px; color: #6b7280;">{{ $alumnoEsc }}</div>
                    </td>

                    <td class="text-center">
                        <span class="badge" style="font-size: 8px; border: 1px solid #e5e7eb; padding: 2px 5px;">
                            {{ strtoupper($tipoNombre) }}
                        </span>
                    </td>

                    <td class="text-left" style="width: 250px;">
                        <div style="width: 100%; font-weight: bold; font-size: 9px; color: #111827; word-wrap: break-word; line-height: 1.2;">
                            {{ $nombreArchivo }}
                        </div>
                        <div style="width: 100%; font-size: 8px; color: #6b7280; font-style: italic; word-wrap: break-word; margin-top: 4px; line-height: 1.1;">
                            <span style="color: #9ca3af;">Ruta:</span> {{ $rutaCorta ?: 'Ruta no disponible' }}
                        </div>
                    </td>

                    <td class="text-center" style="font-size: 10px; {{ $isEliminado ? 'color: #dc2626;' : '' }}">
                        {{ $fechaFormateada }}
                    </td>

                    <td class="text-center" style="font-size: 9px;">
                        <div class="fw-bold">{{ $usuario }}</div>
                        <div class="small" style="font-size: 8px; color: #9ca3af;">{{ $doc['usuario_login'] ?? '' }}</div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

</div>

{{-- FOOTER + PAGINACIÓN --}}
<script type="text/php">
if (isset($pdf)) {
    $w = $pdf->get_width();
    $h = $pdf->get_height();

    $fontNormal = $fontMetrics->getFont("DejaVu Sans", "normal");
    $fontBold   = $fontMetrics->getFont("DejaVu Sans", "bold");
    $size = 9;
    $color = [0.35, 0.35, 0.35];

    $usuario = '{{ $usuario_nombre ?: "-" }}';
    $label = "Generado por:";
    $page_text = "{PAGE_NUM}/{PAGE_COUNT}";

    $margin_bottom = 22;
    $margin_left = 25;
    $margin_right = 25;

    // Label bold
    $pdf->page_text($margin_left, $h - $margin_bottom, $label, $fontBold, $size, $color);

    // Usuario a la derecha del label
    $label_width = $fontMetrics->getTextWidth($label, $fontBold, $size);
    $pdf->page_text($margin_left + $label_width + 4, $h - $margin_bottom, $usuario, $fontNormal, $size, $color);

    // Paginación derecha
    $text_width = $fontMetrics->getTextWidth($page_text, $fontNormal, $size);
    $pdf->page_text($w - $text_width - $margin_right, $h - $margin_bottom, $page_text, $fontNormal, $size, $color);
}
</script>

</body>
</html>
