<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Unpaid = 'unpaid';
    case Paid = 'paid';
    case Refunded = 'refunded';

     public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

