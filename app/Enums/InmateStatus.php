<?php

namespace App\Enums;

enum InmateStatus: string
{
    case Active = 'active';
    case Released = 'released';
    case Transferred = 'transferred';
    case Deceased = 'deceased';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Aktif',
            self::Released => 'Bebas',
            self::Transferred => 'Dipindahkan',
            self::Deceased => 'Meninggal',
        };
    }

    public function badge(): string
    {
        return match ($this) {
            self::Active => 'success',
            self::Released => 'neutral',
            self::Transferred => 'primary',
            self::Deceased => 'danger',
        };
    }
}
