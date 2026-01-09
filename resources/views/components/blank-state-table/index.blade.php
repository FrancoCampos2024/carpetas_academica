@props(['mensaje'])

<div class="col-12 text-center text-muted d-flex flex-column align-items-center py-2">
    <img src="{{ asset('/assets/media/illustrations/sigma-1/20.png') }}" alt="Sin contenido" class="theme-light-show mw-100 mh-200px mb-0 opacity-75">
    <img src="{{ asset('/assets/media/illustrations/sigma-1/20-dark.png') }}" alt="Sin contenido" class="theme-dark-show mw-100 mh-200px mb-0 opacity-75">
    <div class="fs-6 fw-semibold">{{ $mensaje }}</div>
</div>
