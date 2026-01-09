@section('breadcrumb')
    <x-breadcrumb titulo="Asignar acceso">
        <x-breadcrumb.item titulo="Inicio"/>
        <x-breadcrumb.item titulo="/"/>
        <x-breadcrumb.item titulo="Seguridad"/>
        <x-breadcrumb.item titulo="/"/>
        <x-breadcrumb.item titulo="Rol"/>
        <x-breadcrumb.item titulo="/"/>
        <x-breadcrumb.item titulo="Asignar acceso"/>
    </x-breadcrumb>
@endsection
<div>
    <livewire:seguridad.rol.acceso.tabla :rol="$rol" />

    @include('livewire.seguridad.rol.acceso.modal-acciones-menu')

</div>
