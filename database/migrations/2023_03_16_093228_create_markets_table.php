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
        Schema::create('markets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('api_key_name');
            $table->boolean('disable_game')->default(0);
            $table->boolean('saturday_open')->default(1);
            $table->boolean('sunday_open')->default(1);
            $table->boolean('auto_result')->default(0);
            $table->boolean('previous_day_check')->default(0);
            $table->time('open_time')->useCurrent();
            $table->time('close_time')->useCurrent();
            $table->time('open_result_time')->useCurrent();
            $table->time('close_result_time')->useCurrent();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('markets');
    }
};
