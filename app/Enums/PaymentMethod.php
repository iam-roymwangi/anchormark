<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Mpesa = 'mpesa';
    case Paypal = 'paypal';
    case Cash = 'cash';

     public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
