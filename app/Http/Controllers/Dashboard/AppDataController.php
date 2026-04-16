<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\AppData;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DepositHistory;
use App\Models\WithdrawHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class AppDataController extends Controller
{
    public function index()
    {
        $appData = AppData::first();
        return view('dashboard.app-data.index', compact('appData'));
    }

    public function store(Request $request)
    {
        if (!env('ALLOW_EDITING')) {
            return redirect()->back()->with('success', 'Please Don"t Change anything while development.');
        }

        $request->validate([
            //new data
            'info_dialog_1_message' => 'required|string',
            'info_dialog_1_bottom_text' => 'required|string',
            'info_dialog_1_url' => 'nullable|url|max:255',
            'info_dialog_1_message_show_hide' => 'required|boolean',
            'withdrawal_method' => ['required', Rule::in(['manual', 'ibr_pay', 'upi_money', 'i_online_pay', 'cub_pay', 'planet_c', 'sonic_pe', 'run_paisa', 'click_pay', 'vagon_pay', 'rudrax_pay', 'payinfintech', 'universepay'])],

            //new data
            'info_dialog_bottom_text' => 'required|string',
            'tm_no' => 'required|string',
            'arn_no' => 'required|string',
            'provisoinal_id' => 'required|string',
            'whatsapp_group_join_link' => 'required|string',

            'custom_message_1_homepage_1st' => 'required|string',
            'custom_message_2_homepage_2nd_note' => 'required|string',
            'custom_message_3_help_page_1' => 'required|string',
            'custom_message_4_help_page_2nd' => 'required|string',
            'custom_message_5_terms' => 'required|string',
            'info_dialog_message' => 'required|string',
            'facebook_url' => 'required|string',
            'instagram_url' => 'required|string',

            'version' => 'required|integer',
            'home_message' => 'required|string|max:255',
            'support_number' => 'required|string|max:255',
            'support_time' => 'required|string|max:255',
            'min_bid_amount' => 'required|integer|min:1',
            'max_bid_amount' => 'required|integer|min:1',
            'min_withdraw' => 'required|integer|min:1',
            'min_deposit' => 'required|integer|min:1',
            'max_deposit' => 'required|integer|min:1',
            'invite_bonus' => 'required|integer',
            'invite_system_enable' => 'required|boolean',
            'welcome_bonus' => 'required|integer',
            'admin_upi' => 'required|string|max:255',
            'telegram_enable' => 'required|boolean',
            'telegram_link' => 'nullable|url|max:255',
            'whatsapp_enable' => 'required|boolean',
            'whatsapp_number' => 'nullable|string|max:255',
            'withdraw_open_time' => 'required',
            'withdraw_close_time' => 'required',

            'payment_method' => ['required', Rule::in(['auto', 'manual', 'direct_upi', 'ibr_pay', 'upi_money', 'i_online_pay', 'payment_karo', 'planet_c', 'sonic_pe', 'run_paisa', 'pay_from_upi', 'rudrax_pay', 'pay_o_matix'])],

            'auto_result_api' => 'nullable|url|max:255',
            'sms_api_key' => 'nullable|string|max:255',
            'fcm_key' => 'nullable|string|max:255',
            'bank_withdraw_enable' => 'required|boolean',
            'upi_withdraw_enable' => 'required|boolean',
            'enable_desawar' => 'required|boolean',
            'enable_desawar_only' => 'required|boolean',
            'maintain_mode' => 'required|boolean',
            'homepage_image_url'  => 'image|sometimes|nullable',
            'slider_url'  => 'string|sometimes|nullable|url',
            'upi_image'  => 'image|sometimes|nullable',
            'upi_gateway_key'  => 'string|sometimes|nullable',
            'play_store' => 'required|boolean',
            'show_results_only' => 'required|boolean',
            'payfromupi_api_key' => 'sometimes|nullable|string',
            'holiday' => 'required|boolean',

            'self_recharge_bonus' => 'sometimes|nullable|numeric',
        ]);

        $requestData = $request->all();
        if ($request->hasFile('upi_image')) {
            $path = Storage::disk('public')->put('', $request->upi_image);
            $requestData['upi_image'] = Storage::url($path);
        }

        if ($request->hasFile('homepage_image_url')) {
            $path = Storage::disk('public')->put('', $request->homepage_image_url);
            $requestData['homepage_image_url'] = Storage::url($path);
        }

        AppData::updateOrCreate([], $requestData);
        return redirect()->back()->with('success', ' App Data updated successfully');
    }

    public function paymentGetwaySetting() {
        $appData = AppData::first(); 

        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $month = Carbon::now()->month;
        $searchValue = request()->query('searchValue');

        // Deposit Queries
        $baseDepositQuery = DepositHistory::with('user')->where('status', 'success')
            ->when($searchValue, function($query) use ($searchValue) {
                return $query->whereHas('user', function($q) use ($searchValue) {
                    $q->where('name', 'like', '%' . $searchValue . '%');
                    $q->orWhere('phone', 'like', '%' . $searchValue . '%');
                });
            });
        $methodDepositQuery = (clone $baseDepositQuery)->where('payment_method', $appData->payment_method);

        $depositAmount = [
            'todayAll'     => (clone $baseDepositQuery)->whereDate('created_at', $today)->sum('amount'),
            'yesterdayAll' => (clone $baseDepositQuery)->whereDate('created_at', $yesterday)->sum('amount'),
            'thisMonthAll' => (clone $baseDepositQuery)->whereMonth('created_at', $month)->sum('amount'),
            'totalAll'     => (clone $baseDepositQuery)->sum('amount'),

            'today'        => (clone $methodDepositQuery)->whereDate('created_at', $today)->sum('amount'),
            'yesterday'    => (clone $methodDepositQuery)->whereDate('created_at', $yesterday)->sum('amount'),
            'thisMonth'    => (clone $methodDepositQuery)->whereMonth('created_at', $month)->sum('amount'),
            'total'        => (clone $methodDepositQuery)->sum('amount'),
        ];
        $depositHistoryAll = (clone $baseDepositQuery)->latest()->paginate(50);
        $depositHistories  = (clone $methodDepositQuery)->whereDate('created_at', $today)->latest()->paginate(25);


        // Withdraw Queries
        $baseWithdrawQuery = WithdrawHistory::where('status', 'success')
            ->when($searchValue, function ($query) use ($searchValue) {
                return $query->whereHas('user', function ($q) use ($searchValue) {
                    $q->where('name', 'like', '%' . $searchValue . '%');
                    $q->orWhere('phone', 'like', '%' . $searchValue . '%');
                });
            });
        $methodWithdrawQuery = (clone $baseWithdrawQuery)->where('withdrawal_method', $appData->withdrawal_method);

        $withdrawAmount = [
            'todayAll'     => (clone $baseWithdrawQuery)->whereDate('created_at', $today)->sum('amount'),
            'yesterdayAll' => (clone $baseWithdrawQuery)->whereDate('created_at', $yesterday)->sum('amount'),
            'thisMonthAll' => (clone $baseWithdrawQuery)->whereMonth('created_at', $month)->sum('amount'),
            'totalAll'     => (clone $baseWithdrawQuery)->sum('amount'),

            'today'        => (clone $methodWithdrawQuery)->whereDate('created_at', $today)->sum('amount'),
            'yesterday'    => (clone $methodWithdrawQuery)->whereDate('created_at', $yesterday)->sum('amount'),
            'thisMonth'    => (clone $methodWithdrawQuery)->whereMonth('created_at', $month)->sum('amount'),
            'total'        => (clone $methodWithdrawQuery)->sum('amount'),
        ];

        $withdrawHistoryAll = (clone $baseWithdrawQuery)->latest()->paginate(50);
        $withdrawHistories  = (clone $methodWithdrawQuery)->whereDate('created_at', $today)->latest()->paginate(25);
                                            
        return view('dashboard.app-data.payment-getway-setting', compact(
            'appData',
            'depositHistories', 
            'depositAmount', 
            'withdrawHistories', 
            'withdrawAmount', 
            'depositHistoryAll', 
            'withdrawHistoryAll',
            'searchValue'
        ));
    }

    public function paymentGetwaySettingUpdate(Request $request) {        
        if (!env('ALLOW_EDITING')) {
            return redirect()->back()->with('success', 'Please Don"t Change anything while development.');
        }

        $request->validate([
            'payment_method' => ['required', Rule::in(['auto', 'manual', 'direct_upi', 'ibr_pay', 'upi_money', 'i_online_pay', 'payment_karo', 'planet_c', 'sonic_pe', 'run_paisa', 'pay_from_upi', 'rudrax_pay', 'pay_o_matix'])],
            'withdrawal_method' => ['required', Rule::in(['manual', 'ibr_pay', 'upi_money', 'i_online_pay', 'cub_pay', 'planet_c', 'sonic_pe', 'run_paisa', 'click_pay', 'vagon_pay', 'rudrax_pay', 'payinfintech', 'universepay'])],
            'upi_image'  => 'image|sometimes|nullable',
            'payfromupi_api_key' => 'sometimes|nullable|string',
        ]);
        
        $appData = AppData::first();
        $appData->payment_method = $request->payment_method;
        $appData->withdrawal_method = $request->withdrawal_method;
        $appData->payfromupi_api_key = $request->payfromupi_api_key;

        if ($request->hasFile('upi_image')) {
            $path = Storage::disk('public')->put('', $request->upi_image);
            $appData['upi_image'] = Storage::url($path);
        }
        
        $appData->save();

        return redirect()->back()->with('success', ' Payment getway update successfully.');
    }

    public function HomePageImgDelete () {
        $appData = AppData::first();

        if (!$appData) {
            return redirect()->back()->with('failed', ' Slider Image is required.');
        }

        $appData['homepage_image_url'] = null;
        $appData->save();

        return redirect()->back()->with('success', ' App Data updated successfully');
    }
}
