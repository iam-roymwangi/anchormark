<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Shopper = 'shopper';
    case Courier = 'courier';
    case Staff = 'staff';
    case Supplier = 'supplier';

     public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

