<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2); /* snapshot of price when added to cart
           If the item's product is updated before a user can place an order, then 
           they will be notified of any price change. Current prices are stored in the products table.
           
           Notifications of price changes should happen at refresh, when proceeding to order, or when 
           removing a product from cart...
           
           Later to allow promotional purposes, we can keep a separate fixed unit price for items that have a fixed discount
           when added to cart before a specified time...*/
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_products');
    }
};
