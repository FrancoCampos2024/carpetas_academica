@props([
    'titulo' => null,
    'route' => null,
    'parametros' => [],
    'separator' => false,
])
@if ($route && $titulo)
    <li class="breadcrumb-item text-muted">
        @if (!empty($parametros))
            <a href="{{ route($route, $parametros) }}" class="text-muted text-hover-primary">{{ $titulo }}</a>
        @else
            <a href="{{ route($route) }}" class="text-muted text-hover-primary">{{ $titulo }}</a>
        @endif
    </li>
@elseif ($titulo != '')
    <li class="breadcrumb-item text-muted">{{ $titulo }}</li>
@endif
@if ($separator)
    <li class="breadcrumb-item">
        <span class="bullet bg-gray-500 w-5px h-2px"></span>
    </li>
@endif
