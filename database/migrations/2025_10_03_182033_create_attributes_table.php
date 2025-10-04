<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\DataType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attributes', function (Blueprint $table) {
             $table->id();
             $table->string('name'); // e.g. color, size, weight, brand
             //WARRANTY SHOULD BE AMONG THE OPTIONS
             /*This table will be used to store 
             widely known product specs that can be easily queried 
             like size, weight and colour. More subtle specs are stored as a json within each
             product record */
             $table->enum('data_type', DataType::values());
             $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};
