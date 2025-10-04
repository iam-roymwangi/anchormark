<?php

namespace App\Enums;

enum VehicleType: string
{
    case Bike = 'bike';
    case Car = 'car';
    case Van = 'van';
    case Truck = 'truck';
    case Foot = 'foot';

     public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

