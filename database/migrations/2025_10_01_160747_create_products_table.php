<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\ProductStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock_quantity')->default(0);
            $table->integer('re-order_level')->default(0);
            $table->integer('shelf_life')->default(3650);
            $table->string('sku')->unique();
            $table->json('specs_json')->nullable(); // flexible storage
            /*For frontend, when adding a new product, we can suggest you view the 
            specs of an existing product in the same category as reference, for consistency*/
            $table->enum('status', ProductStatus::values())->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
