@props([
    'titulo' => null,
])
<h1 class="page-heading d-flex text-gray-900 fw-bold fs-1 flex-column justify-content-center my-0">{{ $titulo ?? '' }}</h1>
<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
    {{ $slot }}
</ul>
