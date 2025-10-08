<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'data_type'];

    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }
}
