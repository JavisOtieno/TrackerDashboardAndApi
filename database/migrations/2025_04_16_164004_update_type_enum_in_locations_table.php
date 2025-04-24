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
        DB::statement("ALTER TABLE `locations` MODIFY `type` ENUM('start', 'movement', 'stopover', 'end') DEFAULT 'movement'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('locations')
        ->whereIn('type', ['start', 'end'])
        ->update(['type' => 'movement']);

        // Now safely change the ENUM definition
        DB::statement("ALTER TABLE `locations` MODIFY `type` ENUM('movement', 'stopover') DEFAULT 'movement'");
    }
};
