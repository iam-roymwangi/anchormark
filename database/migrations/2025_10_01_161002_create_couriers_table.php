<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\VehicleType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('couriers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('courier_number')->unique();
            $table->string('profile_photo')->nullable();
            $table->enum('vehicle_type', VehicleType::values())->default('bike');
            $table->boolean('is_available')->default(true);
            $table->string('vehicle_registration')->nullable();
            $table->string('driver_license_number')->nullable();
            $table->string('national_id_number')->nullable();
            $table->integer('assigned_orders_count')->default(0);
            $table->integer('completed_orders_count')->default(0);
            $table->decimal('rating', 3, 2)->default(0);
            $table->timestamps();

            /*OTHER FIELDS TO CONSIDER
            CURRENT LOCATION
            WORKING HOURS
            PREFERRED DELIVERY ZONES*/
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('couriers');
    }
};
