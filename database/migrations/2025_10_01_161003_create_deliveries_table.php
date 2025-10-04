<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\DeliveryStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('set null');
            $table->foreignId('courier_id')->constrained()->onDelete('set null');
            $table->string('tracking_number')->nullable();
            $table->datetime('estimated_delivery_date_time')->nullable();
            $table->datetime('actual_delivery_date_time')->nullable();
            $table->enum('vehicle_type', DeliveryStatus::values())->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
