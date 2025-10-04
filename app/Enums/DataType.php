<?php

namespace App\Enums;

enum DataType: string
{
    case String = 'string';
    case Number = 'number';
    case Boolean = 'boolean';
    case Date = 'date';

     public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

