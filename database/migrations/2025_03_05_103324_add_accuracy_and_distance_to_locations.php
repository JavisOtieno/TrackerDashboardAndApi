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
        Schema::table('locations', function (Blueprint $table) {
            //
            // $table->string('status')->after('phone'); // Adjust placement as needed
            $table->string('accuracy')->nullable()->after('long'); 
            $table->string('distance')->nullable()->after('accuracy'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            //

            $table->dropColumn(['accuracy','distance']);
        });
    }
};
