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
        Schema::create('desawar_market_limits', function (Blueprint $table) {
            $table->id();
            $table->decimal('jodiAmount', 10, 2)->nullable();
            $table->decimal('andarAmount', 10, 2)->nullable();
            $table->decimal('baharAmount', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desawar_market_limits');
    }
};
