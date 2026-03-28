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
        Schema::create('start_line_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('startline_market_id')->constrained('start_line_markets');
            $table->string('open_pana')->nullable()->default(NULL);
            $table->string('open_digit')->nullable()->default(NULL);
            $table->date('result_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('start_line_market_results');
    }
};
