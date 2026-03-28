<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Dashboard\DepositHistoryController;
use App\Models\AppData;
use App\Models\DepositHistory;
use App\Models\DesawarRecord;
use App\Models\Transaction;
use App\Models\UpiTransaction;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use sonicpe\paymentV2\sonicpePaymentsV2;
use stdClass;

include_once base_path('packages/sonicpe/src/SonicpePaymentsV2.php');

class TransactionController extends Controller
{

    public function RedirectUrlPayOMatix(Request $request)
    {
        Log::info('RedirectUrlPayOMatix');
        $request->validate([
            // 'amount' => 'required|numeric',
            'amount' => 'required|numeric|max:' . AppData::first()->max_deposit,
        ]);

        $customer_name = Auth::user()->name;
        $customer_email = Auth::user()->phone . '@gmail.com';
        $customer_mobile = Auth::user()->phone;

        $client_txn_id = $this->generateRandomString(9);

        $upi_trans = new UpiTransaction();
        $upi_trans->user_id = Auth::user()->id;
        $upi_trans->client_txn_id = $client_txn_id;
        $upi_trans->save();

        $apiKey = env('PAYOMATIX_SECRET_KEY_PRIVATE');
        $apiUrl = 'https://admin.payomatix.com/payment/merchant/transaction';

        $otherData = [
            'first_name'   => $customer_name,
            'last_name'    => $customer_name,
            'address'      => 'Lucknow, UP, India',
            'state'        => 'Uttar Pradesh',
            'city'         => 'Lucknow',
            'zip'          => '226001',
            'country'      => 'IN',
            'phone_no'     => $customer_mobile,
        ];

        $fields = [
            'email'        => $customer_email,
            'amount'       => $request->amount,
            'currency'     => 'INR',
            'return_url'   => "https://new.yogiclub777.com/wallet?tab=addPoints",
            'notify_url'   => "https://api.yogiclub777.com/api/submit-gateyway-payment-pay-o-matix",
            'merchant_ref' => $client_txn_id,
            'other_data'   => $otherData,
        ];

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => $apiKey,
        ])->post($apiUrl, $fields);

        $data = $response->json();

        Log::error($response);

        if (!$response->ok()) {
            $message = "Something went wrong!";
            return response()->failed($message);
        } else {

            //if $ressponse['status'] == 'success' get 'redirect_url' and set in payment_url
            if (isset($data['status']) && $data['status'] == 'redirect') {
                $payment_url = $data['redirect_url'];
                return response()->success("Data Sent!", compact('payment_url'));
            } else {
                $message = $data['message'];
                return response()->failed($message);
            }
        }
    }

    //
    public function SubmitPayoutRudraxPay(Request $request)
    {
        Log::info('SubmitPayoutRudraxPay');
        Log::info($request->all());
    }

    //SUBMIT PAYMENT CALLBACKS START


    public function SubmitGatewayPaymentPlanetC(Request $request)
    {
        Log::info('SubmitGatewayPaymentPlanetC');
        Log::info($request->all());

        //try 2
        Log::info('Try 2');
        $data2 = $request->all();
        $jsonString2 = array_key_first($data2);
        $jsonData2 = json_decode($jsonString2, true);

        $utrno = $jsonData2['utrno'];
        $customer_name = $jsonData2['customer_name'];
        $merchanttransid = $jsonData2['merchanttransid'];
        $clientid = $jsonData2['clientid'];
        $status = $jsonData2['status'];
        $amount2 = $jsonData2['amount'];
        $id = $jsonData2['id'];
        $customer_mobile = $jsonData2['customer_mobile'];
        $customer_vpa = $jsonData2['customer_vpa'];
        $remark = $jsonData2['remark'];

        $amountDouble = (float) str_replace('_', '.', $amount2);


        // Log::info('Payment Details2');
        // Log::info($jsonData2);
        // Log::info('amount2');
        // Log::info($amountDouble);



        $client_txn_id = $merchanttransid;
        $transactino_check = UpiTransaction::where('client_txn_id', $client_txn_id)
            ->first();
        if ($transactino_check === NULL) {
            return 'Transaction Not Found';
        }

        if ($status != 'success') {
            return 'Transaction Failed';
        }

        $user = $transactino_check->user;
        if ($user === NULL) {
            return response()->failed('User Not Found');
        }
        $user->balance = $user->balance + $amountDouble;
        $user->update();

        $user->transactions()->create([
            "previous_amount" => $user->balance,
            "amount" => $amountDouble,
            "current_amount" => $user->balance + $amountDouble,
            "type" => "recharge",
            "details" => "Gatway, Deposit ($amountDouble) Successful"
        ]);

        $submit_utr = new DepositHistory();
        $submit_utr->user_id = $user->id;
        $submit_utr->utr = 'ONLINE_PAYMENT_PLANET_C';
        $submit_utr->amount = $amountDouble;
        $submit_utr->status = "success";
        $submit_utr->transaction_id = Str::random(12);
        $submit_utr->payment_method = AppData::first()->payment_method;
        $submit_utr->save();

        $otherController = new DepositHistoryController();
        $otherController->giveBonusToSelf($user, $submit_utr);

        return 'Transaction Done';
    }

    public function SubmitGatewayPaymentPaymentKaro(Request $request)
    {
        // Log::info('SubmitGatewayPaymentPaymentKaro');
        // Log::info($request->all());

        // Assuming the data is in the first element of the array
        $data = $request->all()[0];

        $status = $data['status'];
        $client_txn_id  = $data['transaction_id'];

        $transactino_check = UpiTransaction::where('client_txn_id', $client_txn_id)
            ->first();
        if ($transactino_check === NULL) {
            return 'Transaction Not Found';
        }
        $amount = $transactino_check->amount;

        if ($status != 'success') {
            return 'Transaction Failed';
        }

        $user = $transactino_check->user;
        if ($user === NULL) {
            return response()->failed('User Not Found');
        }
        $user->balance = $user->balance + $amount;
        $user->update();

        $user->transactions()->create([
            "previous_amount" => $user->balance,
            "amount" => $amount,
            "current_amount" => $user->balance + $amount,
            "type" => "recharge",
            "details" => "Gatway, Deposit ($amount) Successful"
        ]);

        $submit_utr = new DepositHistory();
        $submit_utr->user_id = $user->id;
        $submit_utr->utr = 'ONLINE_PAYMENT_PAYMENT_KARO';
        $submit_utr->amount = $amount;
        $submit_utr->status = "success";
        $submit_utr->transaction_id = Str::random(12);
        $submit_utr->payment_method = AppData::first()->payment_method;
        $submit_utr->save();

        $otherController = new DepositHistoryController();
        $otherController->giveBonusToSelf($user, $submit_utr);

        return 'Transaction Done';
    }

    public function SubmitGatewayPaymentRudraxPay(Request $request)
    {
        Log::info('SubmitGatewayPaymentRudraxPay');
        Log::info($request->all());

        // Validate required fields
        if (!isset($request->client_txn_id)) {
            return response()->failed('Transaction ID is required');
        }

        $client_txn_id = $request->client_txn_id;
        $amount = $request->amount;
        $utr = $request->utr;

        // Check if the transaction exists
        $transaction_check = UpiTransaction::where('client_txn_id', $client_txn_id)->first();
        if ($transaction_check === null) {
            return response()->failed('Transaction Not Found');
        }

        // Check transaction status
        if ($request->status !== 'success') {
            return response()->failed('Transaction Failed');
        }

        // Find the user associated with the transaction
        $user = User::find($transaction_check->user_id);
        if ($user === null) {
            return response()->failed('User Not Found');
        }

        // Update user's balance
        $user->balance += $amount;
        $user->save();

        // Log the transaction in user's transaction history
        $user->transactions()->create([
            "previous_amount" => $user->balance - $amount,
            "amount" => $amount,
            "current_amount" => $user->balance,
            "type" => "recharge",
            "details" => "RudraxPay Gateway, Deposit ($amount) Successful"
        ]);

        // Save the deposit history
        $submit_utr = new DepositHistory();
        $submit_utr->user_id = $user->id;
        $submit_utr->utr = $utr ?? 'ONLINE_PAYMENT_RUDRAXPAY_GATEWAY';
        $submit_utr->amount = $amount;
        $submit_utr->status = "success";
        $submit_utr->transaction_id = Str::random(12);
        $submit_utr->payment_method = AppData::first()->payment_method;
        $submit_utr->save();

        $otherController = new DepositHistoryController();
        $otherController->giveBonusToSelf($user, $submit_utr);

        return 'Transaction Done';
    }


    public function SubmitGatewayPayment(Request $request)
    {
        Log::info('SubmitGatewayPayment');
        Log::info($request->all());
        if (!isset($request->client_txn_id)) {
            return response()->failed('Transaction ID is required');
        }

        $amount = $request->amount;
        $client_txn_id  = $request->client_txn_id;
        $customer_email = $request->customer_email;
        $customer_mobile = $request->customer_mobile;
        $customer_name = $request->customer_name;
        $customer_vpa = $request->customer_vpa;
        $upi_txn_id = $request->upi_txn_id;

        $transactino_check = UpiTransaction::where('client_txn_id', $client_txn_id)
            ->first();
        if ($transactino_check === NULL) {
            return 'Transaction Not Found';
        }

        if ($request->status != 'success') {
            return 'Transaction Failed';
        }

        $user = User::where('phone', $customer_mobile)
            ->first();
        if ($user === NULL) {
            return response()->failed('User Not Found');
        }
        $user->balance = $user->balance + $amount;
        $user->update();

        $user->transactions()->create([
            "previous_amount" => $user->balance,
            "amount" => $amount,
            "current_amount" => $user->balance + $amount,
            "type" => "recharge",
            "details" => "Gatway, Deposit ($amount) Successful"
        ]);

        $submit_utr = new DepositHistory();
        $submit_utr->user_id = $user->id;
        $submit_utr->utr = 'ONLINE_PAYMENT_UPI_GATEWAY';
        $submit_utr->amount = $amount;
        $submit_utr->status = "success";
        $submit_utr->transaction_id = Str::random(12);
        $submit_utr->payment_method = AppData::first()->payment_method;
        $submit_utr->save();

        $otherController = new DepositHistoryController();
        $otherController->giveBonusToSelf($user, $submit_utr);

        return 'Transaction Done';
    }

    public function SubmitGatewayPaymentIBRPay(Request $request)
    {
        Log::info('SubmitGatewayPaymentIBRPay');
        Log::info($request->all());

        if (!isset($request['data']['userRefNo'])) {
            return response()->failed('Transaction ID is required');
        }

        $amount = $request['data']['Amount'];
        $client_txn_id  = $request['data']['userRefNo'];

        $transactino_check = UpiTransaction::where('client_txn_id', $client_txn_id)
            ->with('user')
            ->first();
        if ($transactino_check === NULL) {
            return 'Transaction Not Found';
        }

        if ($request['data']['TxnStatus'] != 'success') {
            return 'Transaction Failed';
        }

        $user = $transactino_check->user;
        if ($user === NULL) {
            return response()->failed('User Not Found');
        }
        $user->balance = $user->balance + $amount;
        $user->update();

        $user->transactions()->create([
            "previous_amount" => $user->balance,
            "amount" => $amount,
            "current_amount" => $user->balance + $amount,
            "type" => "recharge",
            "details" => "Gatway, Deposit ($amount) Successful"
        ]);

        $submit_utr = new DepositHistory();
        $submit_utr->user_id = $user->id;
        $submit_utr->utr = 'ONLINE_PAYMENT_IRR_PAY';
        $submit_utr->amount = $amount;
        $submit_utr->status = "success";
        $submit_utr->transaction_id = Str::random(12);
        $submit_utr->payment_method = AppData::first()->payment_method;
        $submit_utr->save();

        $otherController = new DepositHistoryController();
        $otherController->giveBonusToSelf($user, $submit_utr);

        return 'Transaction Done';
    }


    public function SubmitGatewayPaymentUPIMoney(Request $request)
    {
        Log::info($request->all());
        if (!isset($request->apitxnid)) {
            return response()->failed('Transaction ID is required');
        }

        $amount = $request->amount;
        $client_txn_id  = $request->apitxnid;

        $transactino_check = UpiTransaction::where('client_txn_id', $client_txn_id)
            ->with('user')
            ->first();
        if ($transactino_check === NULL) {
            return 'Transaction Not Found';
        }

        if ($request->status != 'success') {
            return 'Transaction Failed';
        }

        $user = $transactino_check->user;
        if ($user === NULL) {
            return response()->failed('User Not Found');
        }
        $user->balance = $user->balance + $amount;
        $user->update();

        $user->transactions()->create([
            "previous_amount" => $user->balance,
            "amount" => $amount,
            "current_amount" => $user->balance + $amount,
            "type" => "recharge",
            "details" => "Gatway, Deposit ($amount) Successful"
        ]);

        $submit_utr = new DepositHistory();
        $submit_utr->user_id = $user->id;
        $submit_utr->utr = 'ONLINE_PAYMENT_IRR_PAY';
        $submit_utr->amount = $amount;
        $submit_utr->status = "success";
        $submit_utr->transaction_id = Str::random(12);
        $submit_utr->payment_method = AppData::first()->payment_method;
        $submit_utr->save();

        $otherController = new DepositHistoryController();
        $otherController->giveBonusToSelf($user, $submit_utr);

        return 'Transaction Done';
    }

    public function SubmitGatewayPaymentRunPaisa(Request $request)
    {
        Log::info('SubmitGatewayPaymentRunPaisa');
        Log::info($request->all());
    }

    public function SubmitPayOMatixPayment(Request $request)
    {
        Log::info('SubmitPayOMatixPayment');
        Log::info($request->all());

        // Check if transaction ID exists
        if (!isset($request->data['merchant_ref'])) {
            Log::info('Transaction ID is required');
            return response()->json(['error' => 'Transaction ID is required'], 400);
        }

        $txn_id = $request->data['merchant_ref'];
        $amount = $request->data['converted_amount'];
        $user_email = $request->data['email'];
        $user_mobile = $request->data['phone_no'];
        $status = $request->data['status'];

        // Check if transaction exists
        $transaction_check = UpiTransaction::where('client_txn_id', $txn_id)->first();
        if ($transaction_check === NULL) {
            Log::info('Transaction Not Found');
            return response()->json(['error' => 'Transaction Not Found'], 404);
        }

        // Check if payment is successful
        if ($status !== 'success') {
            Log::info('Transaction Failed');
            return response()->json(['error' => 'Transaction Failed'], 400);
        }

        // Find the user
        $user = User::where('phone', $user_mobile)->first();
        if ($user === NULL) {
            Log::info('User Not Found');
            return response()->json(['error' => 'User Not Found'], 404);
        }

        // Update user balance
        $user->balance += $amount;
        $user->save();

        // Create transaction record
        $user->transactions()->create([
            "previous_amount" => $user->balance - $amount,
            "amount" => $amount,
            "current_amount" => $user->balance,
            "type" => "recharge",
            "details" => "PayOMatix Deposit ($amount) Successful"
        ]);

        // Save to deposit history
        $depositHistory = new DepositHistory();
        $depositHistory->user_id = $user->id;
        $depositHistory->utr = 'ONLINE_PAYMENT_PAYOMATIX';
        $depositHistory->amount = $amount;
        $depositHistory->status = "success";
        $depositHistory->transaction_id = Str::random(12);
        $depositHistory->payment_method = AppData::first()->payment_method;
        $depositHistory->save();

        $otherController = new DepositHistoryController();
        $otherController->giveBonusToSelf($user, $depositHistory);

        return response()->json(['message' => 'Transaction Done'], 200);
    }

    public function SubmitPayFromUpiPayment(Request $request)
    {
        Log::info('SubmitPayFromUpiPayment');
        Log::info($request->all());
        if (!isset($request->txn_id)) {
            return response()->failed('Transaction ID is required');
        }

        $amount = $request->amount;
        $txn_id  = $request->txn_id;
        $user_name = $request->user_name;
        $user_mobile = $request->user_mobile;
        $user_email = $request->user_email;

        $transactino_check = UpiTransaction::where('client_txn_id', $txn_id)
            ->first();
        if ($transactino_check === NULL) {
            return 'Transaction Not Found';
        }

        if ($request->status != 'completed') {
            return 'Transaction Failed';
        }

        $user = User::where('phone', $user_mobile)
            ->first();
        if ($user === NULL) {
            return response()->failed('User Not Found');
        }
        $user->balance = $user->balance + $amount;
        $user->update();

        $user->transactions()->create([
            "previous_amount" => $user->balance,
            "amount" => $amount,
            "current_amount" => $user->balance + $amount,
            "type" => "recharge",
            "details" => "PayFromUPI, Deposit ($amount) Successful"
        ]);

        $submit_utr = new DepositHistory();
        $submit_utr->user_id = $user->id;
        $submit_utr->utr = 'ONLINE_PAYMENT_PAYFROMUPI';
        $submit_utr->amount = $amount;
        $submit_utr->status = "success";
        $submit_utr->transaction_id = Str::random(12);
        $submit_utr->payment_method = AppData::first()->payment_method;
        $submit_utr->save();

        $otherController = new DepositHistoryController();
        $otherController->giveBonusToSelf($user, $submit_utr);
        
        return 'Transaction Done';
    }
    //SUBMIT PAYMENT CALLBACKS END









    //GET PAYMENT URI/URL API START

    public function RedirectUrlPayFromUpi(Request $request)
    {
        Log::info('RedirectUrlPayFromUpi');
        $request->validate([
            'amount' => 'required|numeric|max:' . AppData::first()->max_deposit,
        ]);

        $customer_name = Auth::user()->name;
        $customer_email = Auth::user()->phone . '@gmail.com';
        $customer_mobile = Auth::user()->phone;

        $appData = AppData::find(1);
        if ($appData->payfromupi_api_key === NULL) {
            return response()->failed('API Key Not Found!');
        }

        $fields = [
            'type' => 'any',
            'user_mobile' => $customer_mobile,
            'user_name' => $customer_name,
            'user_email' => $customer_email,
            'redirect_url' => "https://new.yogiclub777.com/wallet?tab=addPoints",
            'amount' => $request->amount,
        ];


        $http_request = Http::withHeaders([
            "Content-Type" => "application/json",
        ])
            ->withToken($appData->payfromupi_api_key)
            ->timeout(60)
            ->post("https://payfromupi.com/api/transactions/create", $fields);
        $response = $http_request->json();

        Log::error($response);

        if (!$http_request->ok()) {
            $message = "Something went wrong!";
            return response()->failed($message);
        } else {

            if (!$response['success']) {
                Log::error('error');
                $message = $response["message"];
                return response()->failed($message);
            }

            $payment_url = $response["data"]["paymentLink"];
            $txnId = $response["data"]["txnId"];
            $qyeryUrl = $response["data"]["qyeryUrl"];

            $upi_trans = new UpiTransaction();
            $upi_trans->user_id = Auth::user()->id;
            $upi_trans->client_txn_id = $txnId;
            $upi_trans->save();

            return response()->success(
                "Data Sent!",
                compact(
                    'payment_url',
                    'txnId',
                    'qyeryUrl'
                )
            );
        }
    }


    private $deugRunPaisa = false;
    private function getRunPaisaToken()
    {
        // Define the base URL and API endpoint
        if ($this->deugRunPaisa) {
            $baseUrl = "https://api.runpaisa.com/token";
        } else {
            $baseUrl = "https://dev.api.runpaisa.com/token";
        }

        // API credentials
        $clientId = env('RUNPAISA_CLIENT_ID'); // Set in .env
        $username = env('RUNPAISA_USERNAME'); // Set in .env
        $password = env('RUNPAISA_PASSWORD'); // Set in .env

        // Prepare the request data
        $data = [
            'client_id' => $clientId,
            'username'  => $username,
            'password'  => $password,
        ];

        // Make the POST request to get the token
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($baseUrl, $data);

        Log::info('getRunPaisaToken');
        Log::info($response->json());

        // Check if the request was successful
        if ($response->successful()) {
            $responseData = $response->json();

            if (isset($responseData['data']['token'])) {
                $token = $responseData['data']['token'];
                $expiry = $responseData['data']['expiry'];

                // Return the token and expiry (or store them as needed)
                return [
                    'token'  => $token,
                    'expiry' => $expiry,
                ];
            } elseif (isset($responseData['message'])) {
                return [
                    'status'  => 'FAIL',
                    'message' => $responseData['message'],
                    'code'    => $responseData['code'],
                ];
            }
        }

        // If the request fails, handle the error response
        if ($response->failed()) {
            $error = $response->json();

            return [
                'status'  => $error['status'] ?? 'FAIL',
                'message' => $error['message'] ?? 'An error occurred',
                'code'    => $error['code'] ?? 'RP999',
            ];
        }
        return null;
    }

    public function RedirectUrlRudraxPay(Request $request)
    {
        $request->validate([
            // 'amount' => 'required|numeric|min:100',
            'amount' => 'required|numeric|min:100|max:' . AppData::first()->max_deposit,
        ]);

        $customer_name = Auth::user()->name;
        $customer_mobile = Auth::user()->phone;
        $client_txn_id = $this->generateRandomString(14);

        $upi_trans = new UpiTransaction();
        $upi_trans->user_id = Auth::user()->id;
        $upi_trans->client_txn_id = $client_txn_id;
        $upi_trans->amount = $request->amount;
        $upi_trans->save();

        // Define API endpoint and data
        $baseUrl = "https://merchant.rudraxpay.com/api/pg/phonepe/initiate";
        $data = [
            'token' => env('RUDRAX_PAY_TOKEN'), // Use token from your .env file
            'userid' => env('RUDRAX_PAY_USER_ID'), // Use UserID from .env
            'amount' => $request->amount,
            'mobile' => $customer_mobile,
            'orderid' => $client_txn_id,
            'callback_url' => "https://api.yogiclub777.com/api/submit-gateyway-payment-rudrax-pay", // https://yogiclub777.com/wallet Replace with your callback URL
        ];

        // Send the POST request
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($baseUrl, $data);

        Log::info('RedirectUrlRudraxPay');
        Log::info($response->json());

        // Handle the API response
        if ($response->successful()) {
            $responseData = $response->json();

            // Check if the status field indicates failure
            if (
                isset($responseData['status']) && $responseData['status'] === false
            ) {
                return response()->failed($responseData['message'] ?? 'Unknown error');
            }

            // Check if the status field indicates success and the URL is present
            if (
                isset($responseData['status']) && $responseData['status'] === true && isset($responseData['url'])
            ) {
                $payment_url = $responseData['url'];
                return response()->success("Data Sent!", compact('payment_url'));
            }
        }

        // Handle other failure cases
        $error = $response->json();
        return response()->failed($responseData['message'] ?? 'Unknown error');
    }


    public function RedirectUrlRunPaisa(Request $request)
    {
        $request->validate([
            // 'amount' => 'required|numeric',
            'amount' => 'required|numeric|max:' . AppData::first()->max_deposit,
        ]);


        $tokenRespnose = $this->getRunPaisaToken();
        if ($tokenRespnose === NULL) {
            return response()->failed('RunPaisa Token Error');
        }
        if (isset($tokenRespnose['status']) && $tokenRespnose['status'] == 'FAIL') {
            return response()->failed($tokenRespnose['message']);
        }

        $customer_name = Auth::user()->name;
        $customer_email = Auth::user()->phone . '@gmail.com';
        $customer_mobile = Auth::user()->phone;
        $client_txn_id = $this->generateRandomString(9);

        $upi_trans = new UpiTransaction();
        $upi_trans->user_id = Auth::user()->id;
        $upi_trans->client_txn_id = $client_txn_id;
        $upi_trans->amount = $request->amount;
        $upi_trans->save();

        $token = $tokenRespnose['token'];

        // Now proceed with the Create Order API
        if ($this->deugRunPaisa) {
            $baseUrl = "https://test.api.pg.runpaisa.com";
        } else {
            $baseUrl = "https:// api.pg.runpaisa.com/order";
        }
        // Define your request data
        $data = [
            'callbackurl'   => env('APP_URL') . "/api/submit-gateyway-payment-run-paisa", // Replace with your callback URL
            'order_id'      => $client_txn_id,  // Unique order ID
            'amount'        => $request->amount,     // Example order amount
            'merc_unq_ref'  => 'abc123',                // Optional merchant reference
        ];

        // Send the POST request to create the order
        $response = Http::withHeaders([
            'Content-Type' => 'multipart/form-data',
            'client_id'    => env('RUNPAISA_CLIENT_ID'), // From .env
            'token'        => $token,                   // The token received from the token API
        ])->post($baseUrl, $data);

        Log::info('RedirectUrlRunPaisa');
        Log::info($response->json());

        // Handle the API response
        if ($response->successful()) {
            $responseData = $response->json();

            if (isset($responseData['order_token']) && isset($responseData['status']) && $responseData['status'] == 'SUCCESS') {
                $payment_url = $responseData['paymentLink'];
                return response()->success("Data Sent!", compact('payment_url'));
            } elseif (isset($responseData['status']) && $responseData['status'] == 'FAIL') {
                return response()->failed($responseData['message']);
            }
        }

        // Handle failure response
        if ($response->failed()) {
            $error = $response->json();

            return response()->json([
                'status'  => $error['status'] ?? 'FAIL',
                'message' => $error['message'] ?? 'An error occurred',
                'code'    => $error['code'] ?? 'RP999',
            ]);
        }

        return response()->json(['status' => 'FAIL', 'message' => 'Unknown error occurred']);
    }


    public function RedirectUrlSonicPay(Request $request)
    {
        $request->validate([
            // 'amount' => 'required|numeric',
            'amount' => 'required|numeric|max:' . AppData::first()->max_deposit,
        ]);

        $customer_name = Auth::user()->name;
        $customer_email = Auth::user()->phone . '@gmail.com';
        $customer_mobile = Auth::user()->phone;
        $client_txn_id = "SEAMLESS_" . rand(1111111111, 9999999999);

        $upi_trans = new UpiTransaction();
        $upi_trans->user_id = Auth::user()->id;
        $upi_trans->client_txn_id = $client_txn_id;
        $upi_trans->amount = $request->amount;
        $upi_trans->save();

        $option = [
            'mode' => 'LIVE',
            'debug' => false
        ];

        $paymentV2 = new sonicpePaymentsV2(env('SONIC_PAY_MERCHANT_ID'), env('SONIC_PAY_ACCESS_TOKEN'), env('SONIC_PAY_API_SECRET'), $option);

        $paymentV2->addCustomerInfo($customer_name, $customer_email, $customer_mobile);
        $paymentV2->setResponseHandler('https://yogiclub777.com', 'https://yogiclub777.com', 'https://yogiclub777.com');
        $orderResponse = $paymentV2->TransactionInit($client_txn_id, 'physical', 'product', $request->amount, 'INR', 'A43');

        $jsonData = json_encode($orderResponse);
        $decodedData = json_decode($jsonData, true);

        $status = $decodedData['status'];

        Log::info('RedirectUrlSonicPay');
        Log::info($decodedData);

        if ($status) {
            $payment_url = $decodedData['message'];
            return response()->success("Data Sent!", compact('payment_url'));
        } else {
            $errorDetails = $decodedData['message'];
            return response()->failed($errorDetails);
        }
    }

    public function getPlanetCToken()
    {

        // Prepare the request fields
        $fields = [
            "user_name" => env('PLANET_C_USERNAME'),
            "password" => env('PLANET_C_PASSWORD'),
        ];

        // Make the HTTP request
        // Make the HTTP request
        $http_request = Http::asForm()->withHeaders([
            "Content-Type" => "application/x-www-form-urlencoded",
        ])->post("https://planetctechnology.in/planetcapi/auth/user/generateToken", $fields);

        // Get the response
        $response = $http_request->json();

        // Check if status is true and extract the URL and message
        $url = '';
        $message = '';
        if (isset($response['status']) && $response['status'] === true) {
            $token = $response['data']['token'] ?? '';
            return $token;
        } else {
            return NULL;
        }
    }

    public function RedirectUrlPlanetC(Request $request)
    {
        $request->validate([
            // 'amount' => 'required|numeric',
            'amount' => 'required|numeric|max:' . AppData::first()->max_deposit,
        ]);

        $client_txn_id = $this->generateRandomString(9);
        $customer_name = Auth::user()->name ?? 'Test User';
        $customer_email = Auth::user()->phone . '@gmail.com';
        $customer_mobile = Auth::user()->phone;

        $planetCToken = $this->getPlanetCToken();
        if ($planetCToken === NULL) {
            return response()->failed('Planet C Token Error');
        }

        // Prepare the request fields
        $fields = [
            "clientReferenceNo" => $client_txn_id,
            "customer_name" => $customer_name,
            "customer_email" => $customer_email,
            "customer_mobile" => $customer_mobile,
            "option" => "QR",
            "amount" => $request->amount,
            "token_key" => env('PLANET_C_IP_TOKEN')
        ];

        $http_request = Http::asForm()->withHeaders([
            "Content-Type" => "application/x-www-form-urlencoded",
            "Authorization" => $planetCToken,
        ])->post("https://planetctechnology.in/planetcapi/api/v1/Payin", $fields);

        // Get the response
        $response = $http_request->json();
        Log::info('RedirectUrlPlanetC');
        Log::info($response);

        // Check if status is true and extract the URL and message
        $url = '';
        $message = '';
        if (isset($response['status']) && $response['status'] === true) {
            $merchantTransactionId = $response['data']['data']['merchantTransactionId'] ?? '';
            $qrString = $response['data']['data']['url'] ?? '';
            $message = $response['data']['message'] ?? '';

            $upi_trans = new UpiTransaction();
            $upi_trans->user_id = Auth::user()->id;
            $upi_trans->client_txn_id = $merchantTransactionId;
            $upi_trans->amount = $request->amount;
            $upi_trans->save();

            return response()->success($message, compact('qrString', 'message'));
        } else {
            // Handle error messages if any
            $error_message = '';
            if (isset($response['error'])) {
                $errors = $response['error'];
                foreach ($errors as $field => $message) {
                    $error_message .= "$message ";
                }
            }
            $message = $error_message ?: ($response['message'] ?? 'An unknown error occurred.');
            return response()->failed($message);
        }
    }

    public function RedirectUrlPaymentKaro(Request $request)
    {
        $request->validate([
            // 'amount' => 'required|numeric',
            'amount' => 'required|numeric|max:' . AppData::first()->max_deposit,
        ]);

        // $payment_url = "https://google.com";
        // return response()->success("Data Sent!", compact('payment_url'));

        $customer_name = Auth::user()->name;
        $customer_email = Auth::user()->phone . '@gmail.com';
        $customer_mobile = Auth::user()->phone;
        $client_txn_id = $this->generateRandomString(9);
        $upi_gateway_api = AppData::find(1)->upi_gateway_key;

        $upi_trans = new UpiTransaction();
        $upi_trans->user_id = Auth::user()->id;
        $upi_trans->client_txn_id = $client_txn_id;
        $upi_trans->amount = $request->amount;
        $upi_trans->save();

        $fields = [
            "api_key" => env('PAYMENT_KARO_API_KEY'),
            "transaction_id" => $client_txn_id,
            "amount" => $request->amount,
            "p_info" => env('APP_NAME') . " App Payment",
            "customer_name" => isset($request->customer_name) ? $request->customer_name : 'Test User',
            "customer_email" => $customer_email,
            "customer_mobile" => $customer_mobile,
            "redirect_url" => "https://new.yogiclub777.com/wallet?tab=addPoints",
            "udf1" => "user defined field 1",
            "udf2" => "user defined field 2",
            "udf3" => "user defined field 3",
        ];

        // Log::info($fields);

        $http_request = Http::withHeaders([
            "Content-Type" => "application/json",
        ])->post("https://api.paymentkaro.com/Collections/CollectionInitiate", $fields);
        $response = $http_request->json();
        // Log::info('RedirectUrlPaymentKaro');
        // Log::info($response);

        if (!$http_request->ok()) {
            $message = "Something went wrong!";
            return response()->failed($message);
        } else {

            // Check if 'Success' key exists and is equal to '1'
            if (!isset($response[0]['Success']) || $response[0]['Success'] != '1') {
                if (!isset($response[0]['Message']) || !isset($response[0]['Message']['msg'])) {
                    $message = "Something went wrong!";
                } else {
                    $message = $response[0]['Message']['msg'];
                }
                return response()->failed($message);
            }

            // Check if 'Message', 'data', and 'payment_url' keys exist
            if (!isset($response[0]['Message']) || !isset($response[0]['Message']['data']) || !isset($response[0]['Message']['data']['payment_url'])) {
                $message = "Not Received Payment URL!";
                return response()->failed($message);
            }

            // Retrieve the payment URL
            $payment_url = $response[0]['Message']['data']['payment_url'];
            return response()->success("Data Sent!", compact('payment_url'));
        }
    }

    public function RedirectUrlIOnlinePay(Request $request)
    {
        $request->validate([
            // 'amount' => 'required|numeric',
            'amount' => 'required|numeric|max:' . AppData::first()->max_deposit,
        ]);

        // $upiString = "upi://pay?pa=paytonigam@axl&pn=PritamKumar&mc=0000&tid=1234567890123456&tr=123456789012&tn=Payment for goods&am=100.00&cu=INR&url=https://www.example.com";
        // return response()->success("Data Sent!", compact('upiString'));
        // return response()->failed("API is Not Completed!");

        $customer_name = Auth::user()->name;
        $customer_email = Auth::user()->phone . '@gmail.com';
        $customer_mobile = Auth::user()->phone;
        $client_txn_id = $this->generateRandomString(9);

        $upi_trans = new UpiTransaction();
        $upi_trans->user_id = Auth::user()->id;
        $upi_trans->client_txn_id = $client_txn_id;
        $upi_trans->save();

        $call_back_url = env('APP_URL') . '/api/submit-gateyway-payment-upi-money';
        $fields = [
            'mId' => 'FFFFEJ9G7KFUSEEN',
            'amount' => $request->amount,
            'invno' => $client_txn_id,
            'fName' => $customer_name,
            'lName' => $customer_name,
            'mNo' => $customer_mobile,
            'uType' => 'INTENT',
            'email' => $customer_email,
        ];
        Log::info($fields);

        try {
            $http_request = Http::withHeaders([
                "Content-Type" => "application/json",
            ])->timeout(60)
                ->get("https://indiaonlinepay.com/api/iopregisterupiintent", $fields);
            $response = $http_request->json();

            Log::info($response);
        } catch (Exception $e) {
        }
    }


    public function RedirectUrlUPIMoney(Request $request)
    {
        $request->validate([
            // 'amount' => 'required|numeric',
            'amount' => 'required|numeric|max:' . AppData::first()->max_deposit,
        ]);

        $customer_name = Auth::user()->name;
        $customer_email = Auth::user()->phone . '@gmail.com';
        $customer_mobile = Auth::user()->phone;
        $client_txn_id = $this->generateRandomString(9);

        $upi_trans = new UpiTransaction();
        $upi_trans->user_id = Auth::user()->id;
        $upi_trans->client_txn_id = $client_txn_id;
        $upi_trans->save();

        $call_back_url = env('APP_URL') . '/api/submit-gateyway-payment-upi-money';
        $fields = [
            'token' => env('GATEWAY_UPI_MONEY_API_TOKEN'),
            'type' => 'upi',
            'mobile' => $customer_mobile,
            'name' => $customer_name,
            'email' => $customer_email,
            'callback' => $call_back_url,
            'apitxnid' => $client_txn_id,
            'amount' => $request->amount,
        ];

        try {
            $http_request = Http::withHeaders([
                "Content-Type" => "application/json",
            ])->timeout(60)
                ->get("https://upimoney.co.in/api/payin/transaction", $fields);
            $response = $http_request->json();

            Log::info($response);


            if (!$http_request->ok()) {
                $message = "Something went wrong!";
                return response()->failed($message);
            } else {
                if (isset($response['status']) && ($response['status'] == 'ERR' || $response['status'])) {
                    if (isset($response['message'])) $message = $response['message'];
                    elseif (isset($response['msg'])) $message = $response['msg'];
                    else $message = "Something went wrong!";
                    return response()->failed($message);
                } elseif (
                    isset($response['statuscode']) && ($response['statuscode'] == 'ERR')
                ) {
                    if (isset($response['message'])) $message = $response['message'];
                    elseif (isset($response['msg'])) $message = $response['msg'];
                    else $message = "Something went wrong!";
                    return response()->failed($message);
                }

                $payment_link = $response['upiString']['payment_url'];
                return response()->success("Data Sent!", compact('payment_link'));
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            // Extract the main cURL error message
            $errorParts = explode(':', $e->getMessage());
            $mainErrorMessage = trim($errorParts[0]);  // Remove leading/trailing whitespace

            // Return a user-friendly response with just the main error
            return response()->failed("API Client Error: " . $mainErrorMessage);
        }
    }

    public function RedirectUrlIBRPayApi2(Request $request)
    {
        $request->validate([
            // 'amount' => 'required|numeric',
            'amount' => 'required|numeric|max:' . AppData::first()->max_deposit,
        ]);
        //if amount is less than 20 then return error
        if ($request->amount < 20) {
            $message = "Minimum Amount 20, is Supported by API!";
            return response()->failed($message);
        }

        $customer_name = Auth::user()->name;
        $customer_email = Auth::user()->email;
        $customer_mobile = Auth::user()->phone;
        $client_txn_id = $this->generateRandomString(15);

        $upi_trans = new UpiTransaction();
        $upi_trans->user_id = Auth::user()->id;
        $upi_trans->client_txn_id = $client_txn_id;
        $upi_trans->save();

        $fields = [
            'APIID' => env('GATEWAY_IBR_PAY_APIID'),
            'Token' => env('GATEWAY_IBR_PAY_API_TOKEN'),
            'MethodName' => 'collectionrequest',
            'client_txn_id' => $client_txn_id,
            'amount' => $request->amount,
            'customer_name' => isset($customer_name) ? $customer_name : 'Test User',
            'customer_email' => isset($customer_email) ? $customer_email : $customer_mobile . '@gmail.com',
            'customer_mobile' => "9351396226",
            'redirect_url' => "https://new.yogiclub777.com/wallet?tab=addPoints",
        ];

        // Log::info($fields);

        try {
            $http_request = Http::withHeaders([
                "Content-Type" => "application/json",
            ])->timeout(60)
                ->post("https://ibrpay.com/api/UPICollection.aspx", $fields);
            $response = $http_request->json();

            Log::info('RedirectUrlIBRPayApi2');
            Log::info($response);

            if (!$http_request->ok()) {
                $message = "Something went wrong!";
                return response()->failed($message);
            } else {

                //
                if (isset($response['code']) && $response['code'] == 'ERR') {
                    if (isset($response['mess']))
                        $message = $response['mess'];
                    elseif (isset($response['msg']))
                        $message = $response['msg'];
                    else
                        $message = "Something went wrong!";
                    return response()->failed($message);
                }

                //
                if (isset($response['data']) && Str::startsWith(trim($response['data']), 'upi')) {
                    $upiIntent = trim($response['data']);
                    return response()->success("Data Sent!", compact('upiIntent'));
                }

                //
                if (!isset($response['data']) || !isset($response['data']['payment_url']) || !isset($response['data']['payment_url'])) {
                    $message = "Not Received QR String!";
                    return response()->failed($message);
                } else {
                    $upiIntent = $response['data']['payment_url'];
                    return response()->success("Data Sent!", compact('upiIntent'));
                }

                //
                $upiIntent = $response['data']['payment_url'];
                return response()->success("Data Sent!", compact('upiIntent'));
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            // Extract the main cURL error message
            $errorParts = explode(':', $e->getMessage());
            $mainErrorMessage = trim($errorParts[0]);  // Remove leading/trailing whitespace

            // Return a user-friendly response with just the main error
            return response()->failed("API Client Error: " . $mainErrorMessage);
        }
    }

    public function RedirectUrlIBRPayApi1(Request $request)
    {
        Log::info('method old');
        $request->validate([
            // 'amount' => 'required|numeric',
            'amount' => 'required|numeric|max:' . AppData::first()->max_deposit,
        ]);

        $customer_name = Auth::user()->name;
        $customer_email = Auth::user()->phone . '@gmail.com';
        $customer_mobile = Auth::user()->phone;
        $client_txn_id = $this->generateRandomString(9);

        $upi_trans = new UpiTransaction();
        $upi_trans->user_id = Auth::user()->id;
        $upi_trans->client_txn_id = $client_txn_id;
        $upi_trans->save();

        $fields = [
            'APIID' => env('GATEWAY_IBR_PAY_APIID'),
            'Token' => env('GATEWAY_IBR_PAY_API_TOKEN'),
            'MethodName' => 'createqr',
            'AgentName' => env('GATEWAY_IBR_PAY_APIID'),
            'Mobile' => "9351396226",
            'QRName' => "Prakash Khaiwal",
            'OrderID' => $client_txn_id,
            'Amount' => $request->amount,
        ];

        try {
            $http_request = Http::withHeaders([
                "Content-Type" => "application/json",
            ])->timeout(60)
                ->post("https://ibrpay.com/api/upiintent.aspx", $fields);
            $response = $http_request->json();

            if (!$http_request->ok()) {
                $message = "Something went wrong!";
                return response()->failed($message);
            } else {
                if (isset($response['code']) && $response['code'] == 'ERR') {
                    if (isset($response['mess']))
                        $message = $response['mess'];
                    elseif (isset($response['msg']))
                        $message = $response['msg'];
                    else
                        $message = "Something went wrong!";
                    return response()->failed($message);
                }
                if (!isset($response['data']) || !isset($response['data']['qrString'])) {
                    $message = "Not Received QR String!";
                    return response()->failed($message);
                }
                $upiIntent = $response['data']['qrString'];
                return response()->success("Data Sent!", compact('upiIntent'));
            }
        } catch (Exception $e) {
            // Extract the main cURL error message
            $errorParts = explode(':', $e->getMessage());
            $mainErrorMessage = trim($errorParts[0]);  // Remove leading/trailing whitespace

            // Return a user-friendly response with just the main error
            return response()->failed("API Client Error: " . $mainErrorMessage);
        }
    }

    public function RedirectUrlIBRPay(Request $request)
    {
        return $this->RedirectUrlIBRPayApi2($request);
        $method1 = false;
        if ($method1)
            return $this->RedirectUrlIBRPayApi1($request);
        else
            return $this->RedirectUrlIBRPayApi2($request);
    }

    public function RedirectUrl(Request $request)
    {
        $request->validate([
            // 'amount' => 'required|numeric',
            'amount' => 'required|numeric|max:' . AppData::first()->max_deposit,
        ]);

        Log::info('============= RedirectUrl ========= ');
        Log::info($request->all());

        $customer_name = Auth::user()->name;
        $customer_email = Auth::user()->phone . '@gmail.com';
        $customer_mobile = Auth::user()->phone;
        $client_txn_id = $this->generateRandomString(9);
        // if ($request->amount <= 100)
        //     $upi_gateway_api = AppData::find(1)->upi_gateway_key;
        // else $upi_gateway_api = "cc0de24a-5178-4303-91e3-ed947b5c1e7b";

        $upi_gateway_api = AppData::find(1)->upi_gateway_key;

        $upi_trans = new UpiTransaction();
        $upi_trans->user_id = Auth::user()->id;
        $upi_trans->client_txn_id = $client_txn_id;
        $upi_trans->save();

        $fields = [
            "key" => $upi_gateway_api,
            "client_txn_id" => $client_txn_id,
            "amount" => $request->amount,
            "p_info" => env('APP_NAME') . " App Payment",
            "customer_name" => isset($request->customer_name) ? $request->customer_name : 'Test User',
            "customer_email" => $customer_email,
            "customer_mobile" => $customer_mobile,
            "redirect_url" => "https://new.yogiclub777.com/wallet?tab=addPoints",
            "udf1" => "user defined field 1",
            "udf2" => "user defined field 2",
            "udf3" => "user defined field 3",
        ];
        $http_request = Http::withHeaders([
            "Content-Type" => "application/json",
        ])->post("https://merchant.upigateway.com/api/create_order", $fields);
        $response = $http_request->json();

        if (!$http_request->ok()) {
            $message = "Something went wrong!";
            return response()->failed($message);
        } else {

            if (!$response['status']) {
                $message = $response['msg'];
                return response()->failed($message);
            }

            $payment_url = $response["data"]["payment_url"];
            return response()->success("Data Sent!", compact('payment_url'));
        }
    }
    //GET PAYMENT URI/URL API END

























    public function getTransactions(Request $request)
    {
        $request->validate([
            'page' => 'required|numeric',
        ]);
        $user = Auth::user();
        $transactions = Transaction::where('user_id', $user->id)->latest()->paginate(50, ['*'], 'transactions', $request->page);
        return response()->success("Data Sent!", compact('transactions'));
    }

    //bonus report api: total play amount, total commision as 0, remaining commision as 0, & date wise total played amount as well as commision (0)
    public function getBonusReport(Request $request)
    {
        $request->validate([
            'page' => 'required|numeric',
        ]);
        $user = Auth::user();


        $total_commission = 0;
        $remaining_commission = 0;

        $total_play_amount = DesawarRecord::where('user_id', $user->id)
            ->sum('amount');

        //date wise rows which contains date and total play amount from DesawarRecord table
        $date_wise_play_amount = DesawarRecord::where('user_id', $user->id)
            ->selectRaw('DATE(created_at) as date, sum(amount) as play_amount')
            //total_commission as 0
            ->selectRaw('0 as commission')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        $data = compact('total_play_amount', 'total_commission', 'remaining_commission', 'date_wise_play_amount');
        return response()->success("Data Sent!", $data);
    }

    //transfer balance to another user
    public function transferBalance(Request $request)
    {
        //return api disabled error
        return response()->failed("API is disabled!");
        $request->validate([
            // 'amount' => 'required|numeric',
            'amount' => 'required|numeric|max:' . AppData::first()->max_deposit,
            'phone' => 'required|numeric',
        ]);
        /** @var User $userSender */
        $userSender = Auth::user();

        //if both sender and receiver are same then return error
        if ($userSender->phone == $request->phone) {
            return response()->failed("You can't transfer to yourself!");
        }

        $phone = $request->phone;
        $amount = $request->amount;
        $message = "Balance Transfered! Congratulations!";
        $balance_left = $userSender->balance - $amount;

        if ($amount < env('MIN_TRANSFER')) {
            $message = "Minimum Transfer Amount is " . env('MIN_TRANSFER');
            return response()->failed($message);
        }

        if ($balance_left < 0) {
            $message = "Insufficient Balance!";
            return response()->failed($message);
        }

        $userReceiver = User::where('phone', $phone)->first();
        if ($userReceiver === NULL) {
            $message = "User Not Found!";
            return response()->failed($message);
        }

        $userSender->balance = $balance_left;
        $userSender->update();

        $userSender->transactions()->create([
            "previous_amount" => $userSender->balance,
            "amount" => $amount,
            "current_amount" => $userSender->balance - $amount,
            "type" => "transfer",
            "details" => "Transfered ($amount) to $phone"
        ]);


        $userReceiver->balance = $userReceiver->balance + $amount;
        $userReceiver->update();

        $userReceiver->transactions()->create([
            "previous_amount" => $userReceiver->balance,
            "amount" => $amount,
            "current_amount" => $userReceiver->balance + $amount,
            "type" => "transfer",
            "details" => "Received ($amount) from $phone"
        ]);

        return response()->success($message, compact('balance_left'));
    }

    public function AddPayment(Request $request)
    {
        $request->validate([
            // 'amount' => 'required|numeric',
            'amount' => 'required|numeric|max:' . AppData::first()->max_deposit,
        ]);
        //add amount in user wallet balance and insert in transctio historuy
        /** @var User $user  */
        $user = Auth::user();

        $auto_approval = env('AUTOMATIC_PAYMENT_APPROVAL', false);
        if (isset($request->pay_status) && $auto_approval) {
            $pay_status = strtolower($request->pay_status);
            if (Str::contains($pay_status, 'success')) {
                $prev_balance = $user->balance;
                $user->balance = $user->balance + $request->amount;
                $user->update();

                $user->transactions()->create([
                    "previous_amount" => $prev_balance,
                    "amount" => $request->amount,
                    "current_amount" => $user->balance + $request->amount,
                    "type" => "recharge",
                    "details" => "Direct UPI, Deposit ($request->amount) Successful"
                ]);

                $user->refresh();
            }
        }

        $submit_utr = new DepositHistory();
        $submit_utr->user_id = $user->id;
        $submit_utr->utr = 'DIRECT_UPI';
        $submit_utr->amount = $request->amount;
        $submit_utr->status = isset($request->pay_status) ? $request->pay_status : "pending";
        $submit_utr->transaction_id = Str::random(12);
        $submit_utr->payment_method = AppData::first()->payment_method;
        $submit_utr->save();

        $balance_left = $user->balance;

        if (isset($request->pay_status) && Str::contains($pay_status, 'success'))
            $message = "Payment Successfull, Balance Has Been Added!";
        else if (isset($request->pay_status) && !Str::contains($pay_status, 'success'))
            $message = "Payment Failed!, No Success Response Found.";
        else $message = "Please Wait for Approval!";
        return response()->success($message, compact('balance_left'));
    }

    function generateRandomString($length = 10)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
