<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\CartStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shopper_id')->nullable()->constrained()->onDelete('cascade');
            $table->uuid('session_id')->nullable(); // for guest carts
            $table->enum('cart_status', CartStatus::values())->default('active');
            /*For carts, when a user adds an item to a cart, a cart is created. 
            They can then modify the cart by adding or removing items. The cart status is active.

            When they proceed to order all items in the cart, the status changes to converted.
            At this point, a new order record is created and all the products are taken to order products
            The cart status changes to converted.

            When a user leaves items in a cart and logs out, or is logged out automatically, the status moves to abandoned.
            When they log back in and add an item to a cart, a new cart record will be created. */
            $table->integer('total_items')->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->timestamp('last_activity_at')->nullable(); //Could help when we want to set a cart as abndoned, after a specific amount of time after the last activity.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
