<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('desawar_markets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('api_key_name');
            $table->boolean('auto_result')->default(0);
            $table->boolean('previous_day_check')->default(0);
            $table->boolean('disable_game')->default(0);
            $table->time('open_time');
            $table->time('close_time');
            $table->time('result_time');

            //criteria 1 (their timings & max bet amount)
            $table->time('c_time_start')->default(Carbon::now()->subHours(2)->format('H:i'));
            $table->time('c_time_end')->default(Carbon::now()->subHours(1)->format('H:i'));
            $table->integer('c_max_bet_amount')->default(10000);

            //criteria 2 (their timings & max bet amount)
            $table->time('c2_time_start')->default(Carbon::now()->subHours(1)->format('H:i'));
            $table->time('c2_time_end')->default(Carbon::now()->format('H:i'));
            $table->integer('c2_max_bet_amount')->default(10000);

            //criteria 3 (their timings & max bet amount)
            $table->time('c3_time_start')->default(Carbon::now()->addHours(1)->format('H:i'));
            $table->time('c3_time_end')->default(Carbon::now()->addHours(2)->format('H:i'));
            $table->integer('c3_max_bet_amount')->default(10000);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desawar_markets');
    }
};
