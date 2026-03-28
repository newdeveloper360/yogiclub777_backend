<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppData extends Model
{
    use HasFactory;
    protected $fillable = [
        //new data added
        'info_dialog_1_message',
        'info_dialog_1_bottom_text',
        'info_dialog_1_url',
        'info_dialog_1_message_show_hide',
        'withdrawal_method',

        //new data added
        'info_dialog_bottom_text',
        'tm_no',
        'arn_no',
        'provisoinal_id',
        'whatsapp_group_join_link',
        'custom_message_1_homepage_1st',
        'custom_message_2_homepage_2nd_note',
        'custom_message_3_help_page_1',
        'custom_message_4_help_page_2nd',
        'custom_message_5_terms',
        'info_dialog_message',
        'facebook_url',
        'instagram_url',

        'version',
        'home_message',
        'support_number',
        'support_time',
        'min_withdraw',
        'min_deposit',
        'max_deposit',
        'self_recharge_bonus',
        'invite_bonus',
        'invite_system_enable',
        'welcome_bonus',
        'admin_upi',
        'telegram_enable',
        'telegram_link',
        'whatsapp_enable',
        'whatsapp_number',
        'withdraw_open_time',
        'withdraw_close_time',
        'payment_method',
        'auto_result_api',
        'sms_api_key',
        'fcm_key',
        'bank_withdraw_enable',
        'upi_withdraw_enable',
        'app_update_link',
        'enable_desawar',
        'enable_desawar_only',
        'homepage_image_url',
        'upi_image',
        'slider_url',
        'maintain_mode',
        'min_bid_amount',
        'max_bid_amount',
        'upi_gateway_key',
        'play_store',
        'show_results_only',
        'payfromupi_api_key',
        'holiday',
        
        'payin_fintech_token',
    ];
}
