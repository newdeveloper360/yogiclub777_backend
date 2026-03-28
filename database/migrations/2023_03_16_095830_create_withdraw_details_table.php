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
        Schema::create('withdraw_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('bank_name')->nullable()->default(NULL);
            $table->string('account_holder_name')->nullable()->default(NULL);
            $table->string('upi_name')->nullable()->default(NULL);
            $table->string('account_number')->nullable()->default(NULL);
            $table->string('account_ifsc_code')->nullable()->default(NULL);
            $table->string('upi_id')->nullable()->default(NULL);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdraw_details');
    }
};
