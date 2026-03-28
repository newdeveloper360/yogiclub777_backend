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
        Schema::table('desawar_markets', function (Blueprint $table) {
            $table->boolean('is_bet_time_limit')->default(false)->after('result_time');
            $table->time('bet_time_limit')->nullable()->after('is_bet_time_limit');
            $table->decimal('choti_jodi_bet_amount_limit', 10, 2)->default(0)->after('bet_time_limit');
            $table->decimal('moti_jodi_bet_amount_limit', 10, 2)->default(0)->after('choti_jodi_bet_amount_limit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('desawar_markets', function (Blueprint $table) {
            $table->dropColumn('is_bet_time_limit');
            $table->dropColumn('bet_time_limit');
            $table->dropColumn('choti_jodi_bet_amount_limit');
            $table->dropColumn('moti_jodi_bet_amount_limit');
        });
    }
};
