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
        Schema::create('desawar_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desawar_market_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('game_type_id')->constrained();
            $table->string('number');
            $table->integer('amount');
            $table->string('game_string');
            $table->integer('win_amount')->nullable()->default(NULL);
            $table->date('date')->default(Carbon::today());
            $table->enum('status', ['pending', 'success', 'failed', 'canceled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desawar_records');
    }
};
