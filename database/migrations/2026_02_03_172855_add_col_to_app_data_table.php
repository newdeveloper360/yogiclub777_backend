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
        Schema::table('app_data', function (Blueprint $table) {
            $table->integer('max_deposit')->default(500)->after('min_deposit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_data', function (Blueprint $table) {
            $table->dropColumn('max_deposit');
        });
    }
};
