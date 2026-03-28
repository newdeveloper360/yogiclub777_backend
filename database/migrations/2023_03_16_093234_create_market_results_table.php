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
        Schema::create('market_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('market_id')->constrained();
            $table->date('result_date');
            $table->string('open_pana')->nullable()->default(NULL);
            $table->string('open_digit')->nullable()->default(NULL);
            $table->string('close_digit')->nullable()->default(NULL);
            $table->string('close_pana')->nullable()->default(NULL);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_results');
    }
};
