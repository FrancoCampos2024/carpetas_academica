<?php

namespace App\Enums;

enum EstadoEnum: string
{
    // Estados generales
    case HABILITADO = 'HAB';
    case DESHABILITADO = 'DES';

    public function descripcion(): string
    {
        return match ($this) {
            self::HABILITADO => 'Habilitado',
            self::DESHABILITADO => 'Deshabilitado',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::HABILITADO => 'success',
            self::DESHABILITADO => 'danger',
        };
    }
}
