<?php

namespace Database\Seeders;

use App\Models\AppData;
use App\Models\GameType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use function PHPSTORM_META\type;

class AppDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        AppData::create([
            'id' => 1,
            'support_number' => '+91 1234567890',
            'admin_upi' => 'upi@ybl',
            'telegram_link' => 'https://www.t.me/telegram_id',
            'whatsapp_number' => '1234567890',
            'auto_result_api' => NULL,
            'sms_api_key' => '2qmpZu7tkfNRF1UnHhE5YQ36S4jITxwg8dzKo0AsVPcyaGbMivoE2NbDKepaAWGs1Tg6jZdwVSXPBOJ9',
            'fcm_key' => 'fcm_key',
            'info_dialog_message' => 'अगर आपको पैसा ऐड करने में, पैसा निकालने मे और कोई गेम खेलने में समस्या होती है तो आप HELP में जाके विडिओ देख सकते हो और हमसे बात भी कर सकते हो',

            'custom_message_1_homepage_1st' => '<p><span style="color: rgb(33, 37, 41);">🔥</span>भरोसे का एक ही नाम&nbsp;🔥</p><p>🙏बाबा जी खाईवाल<span style="color: rgb(33, 37, 41);">🙏</span></p>',

            'custom_message_2_homepage_2nd_note' => '<p>👉<span style="color: rgb(33, 37, 41);">👉</span>बिंदास होके खेलो भाइयो</p><p><span style="color: rgb(33, 37, 41);">👉</span>सबसे फास्ट पेमेंट मिलती है</p><p><span style="color: rgb(33, 37, 41);">👉</span>कोई भी गेम कभी भी लगाओ</p><p>आटोमेटिक सिस्टम है&nbsp;</p><p><span style="color: rgb(33, 37, 41);">👻</span>रेट 10 के 980 मिलते हैं&nbsp;👻</p><p><span style="color: rgb(33, 37, 41);">🙏</span>जय बाबा सागरनाथ अघोरी की 🙏</p>',

            'custom_message_3_help_page_1' => '<p><strong style="color: rgb(0, 138, 0);">💣BABA JI MATKA HELP &amp; SUPPORT💣</strong></p><p>&nbsp;</p><p><strong>🔥Min Deposit: Rs. 100🔥</strong></p><p><strong>🔥Min Withdraw: Rs. 475🔥</strong></p><p><strong>🔥रेट 10 के 980🔥</strong></p><p><strong>🔥भरोसे का एक ही नाम🔥</strong></p><p><strong>🙏बाबा जी खाइवल🙏</strong></p><p>&nbsp;</p><p><span style="color: rgb(0, 138, 0);">NOTE: अगर आपको गेम खेलने में या अगर कोई भी समसाय होती है तो आप हमारे हेल्पलाइन नंबर पर कल कर सकते हैं </span></p><p>&nbsp;</p><p class="ql-align-center"><strong class="ql-size-large">👻गेम कैसे खेलनी है जानिए👻</strong></p>',

            'custom_message_4_help_page_2nd' => '<p><strong style="color: rgb(230, 0, 0);">🔥सभी गेम मे 1 से 100 में से कोई एक नंबर आता है अगर आपने वही लगाया हुआ होगा तो आपको 98 गुणा पैसे मिलेंगे </strong></p><p><strong style="color: rgb(230, 0, 0);">🔥जैसे आपने 15 पर 10 रुपए लगाए हैं किसी गेम</strong></p><p><strong style="color: rgb(230, 0, 0);">में और उसमे 15 रिजल्ट आता है तो आपको 980 रुपए मिलेंगे</strong></p><p><strong style="color: rgb(230, 0, 0);">🔥आप कितने भी नंबर लगा सकते हो बस</strong></p><p><strong style="color: rgb(230, 0, 0);">आपका पास होगा चाहिए और पास होते ही आपका पैसा वॉलेट मे या जाएगा</strong></p><p>&nbsp;</p><p class="ql-align-center"><strong class="ql-size-large">👇👻गेम खेलना सीखने के लि Video आइकान पे क्लिक करें 👻</strong></p>',

            'custom_message_5_terms' => '<p class="ql-align-center"><strong style="color: rgb(0, 138, 0);">💥BABAJICLUB TERMS AND CONDITION💥</strong></p><p class="ql-align-center"><strong style="color: rgb(0, 138, 0);">♻️रेट 10 के 980♻️</strong></p><p><br></p><p><strong>1. 🔥DARK MODE🔥मे ऐप्लकैशन को उसे न करें </strong></p><p><strong>2. 10000 इंटू की जोड़ी लगेगी मैक्समम 3. 5000 इंटू का हरुफ़ लगेगा मैक्समम 5. 20000 इंटू की क्रॉसिंग लगेगी मैक्समम</strong></p><p><strong>5. रिजल्ट ऐप्लकैशन मे अपडेट होते ही आपका पैसा अकाउंट मे अपडेट हो जाएगा </strong></p><p><strong>6. कभी भी पैसा ऐड कर सकते हैं ऐप्लकैशन में</strong></p><p><strong>7. पैसे निकालने का समय सुबह 8 बजे से रात के 2 बजे तक हैं </strong></p><p><strong>8. WITHDRAW REQUEST डालते ही 5 से 10 मिनट के अंदर पैसा आपके अकाउंट मे या जाएगा</strong></p><p><strong>9. अगर आपको किसी भी प्रकार की समस्या होती है तो आप बाबा जी को WHATSAPP कर सकते हैं</strong></p><p><strong>10. बाबा जी हेल्पलाइन नंबर 6367529290 🔥</strong></p>'

        ]);
    }
}
