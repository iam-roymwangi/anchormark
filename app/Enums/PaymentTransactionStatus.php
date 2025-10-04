<?php

namespace App\Enums;

enum PaymentTransactionStatus: string
{
    case Pending = 'pending';
    case Successful = 'successful';
    case Failed = 'failed';

     public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

//This is used in the actual payments table
//Like order payment. It'll track the transaction lifecycle
//Payment status enums on the other hand show the final state of the entire order in terms of its payment.
//So if a payment transaction becomes successful for an order, then the order gets marked as paid.


