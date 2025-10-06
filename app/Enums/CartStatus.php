<?php

namespace App\Enums;

enum CartStatus: string
{
    case Active = 'active';
    case Ordered = 'ordered';
    case Abandoned = 'abandoned';

     public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}


