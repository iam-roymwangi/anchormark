<?php

namespace App\Enums;

enum ProductStatus: string
{
    case Active = 'active'; /*Products we offer, whether currently in stock or out
    Default status for newly created items, before they can be stocked or sold.*/

    case Inactive = 'inactive'; //Products we have  hidden from customers

    case InStock = 'instock'; /* Once a product has been supplied to us 
    for the first time,status changes from active to in stock,
     when sold out it moves to out of stock, when bought again moves back to in stock.*/
    case OutofStock = 'outofStock';
    
    case Suspended = 'discontinued'; //Excommunicated products 'Deleted'

     public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
