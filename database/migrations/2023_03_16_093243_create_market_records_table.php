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
        Schema::create('market_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('market_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->string('number');
            $table->integer('amount');
            $table->string('game_string');
            $table->integer('win_amount')->nullable()->default(NULL);
            $table->foreignId('game_type_id')->constrained('game_types');
            $table->date('date')->default(Carbon::today());
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->enum('session', ['open', 'close', 'null'])->default('null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_records');
    }
};
