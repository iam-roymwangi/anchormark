<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change session_id from uuid() to VARCHAR(50) to accommodate longer session IDs
        // Laravel's session()->getId() can return 40-character strings
        DB::statement('ALTER TABLE `carts` MODIFY COLUMN `session_id` VARCHAR(50) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to CHAR(36) for UUID format
        // Note: This might fail if there are session_ids longer than 36 chars
        DB::statement('ALTER TABLE `carts` MODIFY COLUMN `session_id` CHAR(36) NULL');
    }
};
