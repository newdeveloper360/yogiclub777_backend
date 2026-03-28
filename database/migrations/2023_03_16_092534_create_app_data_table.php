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
        Schema::create('app_data', function (Blueprint $table) {
            $table->id();


            $table->string('info_dialog_1_message')->nullable()->default("जय बाबा की दोस्तों");
            $table->string('info_dialog_1_bottom_text')->default("सभी भाई अपनी पेमेंट 11 बजे से पहले ले  लो। 11 बजे के बाद सर्वर अपडेट होगा फिर सुबह ही एप्लीकेशन चलेगी।");
            $table->string('info_dialog_1_url')->nullable()->default(NULL);

            //new data
            $table->string('info_dialog_message')->nullable()->default(NULL);
            $table->string('info_dialog_bottom_text')->default('🔥Jay Baba Ki🔥');


            $table->string('tm_no')->default('12070517');
            $table->string('arn_no')->default('RA725912517615G');
            $table->string('provisoinal_id')->default('NUMBER=72RAECS7019R6BG');
            $table->string('whatsapp_group_join_link')->default('https://google.com');
            //long datas
            $table->longText('custom_message_1_homepage_1st')->nullable()->default(NULL);
            $table->longText('custom_message_2_homepage_2nd_note')->nullable()->default(NULL);
            $table->longText('custom_message_3_help_page_1')->nullable()->default(NULL);
            $table->longText('custom_message_4_help_page_2nd')->nullable()->default(NULL);
            $table->longText('custom_message_5_terms')->nullable()->default(NULL);
            $table->string('facebook_url')->nullable()->default(NULL);
            $table->string('instagram_url')->nullable()->default(NULL);

            $table->boolean('maintain_mode')->default(false);
            $table->integer('version')->default(1);
            $table->string('home_message')->default('Welcome to ' . env('APP_NAME'));
            $table->string('support_number')->default('+91 1234567890');
            $table->string('support_time')->default('10:00 AM - 10:00 PM');
            $table->integer('min_bid_amount')->default(5);
            $table->integer('max_bid_amount')->default(5000);
            $table->integer('min_withdraw')->default(1000);
            $table->integer('min_deposit')->default(500);
            $table->float('self_recharge_bonus')->default(0);
            $table->integer('invite_bonus')->default(10);
            $table->boolean('invite_system_enable')->default(true);
            $table->integer('welcome_bonus')->default(0);
            $table->string('admin_upi')->default('upi@ybl');
            $table->boolean('telegram_enable')->default(true);
            $table->string('telegram_link')->nullable()->default('https://www.t.me/telegram_id');
            $table->boolean('whatsapp_enable')->default(false);
            $table->string('whatsapp_number')->nullable()->default(NULL);
            $table->time('withdraw_open_time')->default('00:01:00');
            $table->time('withdraw_close_time')->default('23:59:00');
            $table->enum('payment_method', ['auto', 'manual', 'direct_upi', 'ibr_pay', 'upi_money', 'i_online_pay', 'payment_karo', 'planet_c', 'sonic_pe', 'run_paisa', 'pay_from_upi', 'rudrax_pay', 'pay_o_matix'])->default('direct_upi');

            $table->string('payfromupi_api_key')->nullable()->default(NULL);

            $table->enum('withdrawal_method', ['manual', 'ibr_pay', 'upi_money', 'i_online_pay', 'cub_pay', 'planet_c', 'sonic_pe', 'run_paisa', 'click_pay', 'vagon_pay', 'rudrax_pay', 'payinfintech', 'universepay'])->default('manual');
            $table->string('auto_result_api')->nullable()->default(NULL);
            $table->string('sms_api_key')->nullable()->default(NULL);
            $table->string('app_update_link')->nullable()->default(env('APP_URL') . '/download');
            $table->boolean('bank_withdraw_enable')->default(true);
            $table->boolean('upi_withdraw_enable')->default(true);
            $table->boolean('enable_desawar')->default(true);
            $table->boolean('enable_desawar_only')->default(true);
            $table->string("fcm_key", 512)->nullable()->default(NULL);
            $table->string("homepage_image_url")->nullable()->default(NULL);
            $table->string("slider_url")->nullable()->default(NULL);
            $table->string("upi_image")->nullable()->default(NULL);
            $table->string("upi_gateway_key")->nullable()->default(NULL);
            $table->boolean('play_store')->default(false);
            $table->boolean('show_results_only')->default(false);
            $table->float('total_mannual_amount_added', 8, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_data');
    }
};
