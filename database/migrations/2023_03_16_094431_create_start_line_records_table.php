<?php

use Carbon\Carbon;
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
        Schema::create('start_line_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('startline_market_id')->constrained('start_line_markets');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('game_type_id')->unsigned('game_types');
            $table->string('game_string');
            $table->string('number');
            $table->integer('amount');
            $table->integer('win_amount')->nullable()->default(NULL);
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->date('date')->default(Carbon::today());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('start_line_records');
    }
};
