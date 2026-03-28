<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Api\TransactionController;
use App\Models\WithdrawHistory;
use App\Http\Controllers\Controller;
use App\Models\AppData;
use App\Notifications\WithdrawRequestAcceptNotification;
use App\Notifications\WithdrawRequestRejectNotification;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WithdrawHistoryController extends Controller
{
    public function index(Request $request)
    {
        $appData = AppData::first();
        if ($request->has('searchValue')) {
            $searchValue = $request->searchValue;
            $withdrawHistories = WithdrawHistory::with('user')
                ->whereHas('user', function ($query) use ($searchValue) {
                    $query->where('name', 'LIKE', '%' . $searchValue . '%')
                        ->orWhere('phone', 'LIKE', '%' . $searchValue . '%');
                })->latest()->paginate(250);
            return view('dashboard.withdraw-history.index', compact('withdrawHistories', 'searchValue', 'appData'));
        }
        $withdrawHistories  = WithdrawHistory::latest()->paginate(25);
        return view('dashboard.withdraw-history.index', compact('withdrawHistories', 'appData'));
    }

    function generateRandomString($length = 20)
    {
        // Ensure minimum length of 18 (5 letters + 13 numbers)
        if ($length < 18) {
            $length = 18;
        }

        // Define the prefix with sequential letters
        $prefix = 'abcde';

        // Calculate the remaining length for numbers
        $remainingLength = $length - strlen($prefix);

        // Generate the numeric part
        $numbers = '0123456789';
        $numbersLength = strlen($numbers);
        $randomNumbers = '';

        for ($i = 0; $i < $remainingLength; $i++) {
            $randomNumbers .= $numbers[rand(0, $numbersLength - 1)];
        }

        // Combine the prefix and numbers
        return $prefix . $randomNumbers;
    }

    public function acceptRequestClickPay($id)
    {
        $withdraw = WithdrawHistory::where('id', $id)
            ->with('user', 'user.withdrawDetails')
            ->first();
        $user = $withdraw->user;

        $client = new Client();
        $url = 'https://api.clickncash.in/app/api/dopayout';
        $agent_id = $this->generateRandomString();

        $beneName = $user->withdrawDetails->account_holder_name;
        if (strlen($beneName) < 5) {
            $beneName .= 'abcd';
        }
        $beneName = substr($beneName, 0, 20);

        $data = [
            'phone' => $user->phone,
            'name' => $beneName,
            'amount' => $withdraw->amount,
            'account' => $user->withdrawDetails->account_number,
            'ifsc' => $user->withdrawDetails->account_ifsc_code,
            'email' => $user->phone . '@gmail.com',
            'agent_id' => $agent_id,
            'key' => env('CLICK_PAY_USER_KEY'),
            'token' => env('CLICK_PAY_API_TOKEN'),
        ];

        try {
            $response = $client->post($url, [
                'form_params' => $data
            ]);

            $body = $response->getBody()->getContents();
            $parsedResponse = json_decode($body, true);

            // Log parsed response
            Log::info($parsedResponse);

            if (isset($parsedResponse['status'])) {
                if ($parsedResponse['status'] === 'PROCESSING') {
                    $withdraw->status = "success";
                    $withdraw->save();
                    $user->transactions()->create([
                        'previous_amount' => $user->balance,
                        'amount' => $withdraw->amount,
                        'current_amount' => $withdraw->amount + $user->balance,
                        "type" => "withdraw",
                        "details" => "Withdraw ($withdraw->amount) Accepted"
                    ]);
                    $user->notify(new WithdrawRequestAcceptNotification($withdraw->amount, $user->fcm, $user->one_signalsubscription_id));
                    $message = "Payment Request sent to API. Message from API: " . $parsedResponse['status'] ?? 'Message Not Found';
                    return $message;
                } else {
                    $message = "Payment Request sent to API. Message from API: " . $parsedResponse['status'] ?? 'Message Not Found';
                    return $message;
                }
            } else {
                $message = "API Error: Invalid response format";
                return $message;
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            $message = "API Error: " . $error;
            return $message;
        }
    }

    private function acceptRequestApiIBRPayNew($id)
    {
        Log::info('acceptRequestApiIBRPayNew');
        $withdraw = WithdrawHistory::where('id', $id)
            ->with('user', 'user.withdrawDetails')
            ->first();
        $user = $withdraw->user;

        $client = new Client();
        $url = 'https://ibrpay.com/api/PayoutLive.aspx';
        $data = [
            'APIID' => env('GATEWAY_IBR_PAY_APIID'),
            'Token' => env('GATEWAY_IBR_PAY_API_TOKEN'),
            'MethodName' => 'payout',
            'OrderID' => Str::random(12),
            'Name' => $user->withdrawDetails->account_holder_name,
            'Amount' => $withdraw->amount,
            'number' => $user->withdrawDetails->account_number,
            'ifsc' => $user->withdrawDetails->account_ifsc_code,
            'PaymentType' => 'Imps',
            'CustomerMobileNo' => $user->phone,
        ];

        Log::info($data);

        try {
            $response = $client->post($url, [
                'json' => $data
            ]);



            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            $parsedResponse = json_decode($body, true);

            Log::info($parsedResponse);

            if (isset($parsedResponse['data']['Message']))
                $api_message = $parsedResponse['data']['Message'];
            elseif (isset($parsedResponse['mess']))
                $api_message = $parsedResponse['mess'];
            else $api_message = "No Message Found";

            if (isset($parsedResponse['status'])) {
                $status = $parsedResponse['status'];
                if ($status == 'success' || $status == 'pending') {
                    $withdraw->status = "success";
                    $withdraw->save();
                    $user->transactions()->create([
                        'previous_amount' => $user->balance,
                        'amount' => $withdraw->amount,
                        'current_amount' => $withdraw->amount + $user->balance,
                        "type" => "withdraw",
                        "details" => "Withdraw ($withdraw->amount) Accepted"
                    ]);
                    $user->notify(new WithdrawRequestAcceptNotification($withdraw->amount, $user->fcm, $user->one_signalsubscription_id));
                    $message = "Payment Request sent to API. Message from API: " . $api_message;
                    return $message;
                } elseif ($status == 'failed') {
                    $message = "API Error, " . $api_message . ", Please Mannually Send Payment to user or Contact Support";
                    return $message;
                }
            } else {
                $message = "API Error, Invalid Status message, Status Code is: " . $parsedResponse['statusCode'];
                return $message;
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            Log::info($error);
            $message = "API Error: " . $error;
            return $message;
        }
    }


    private function acceptRequestApiCubPay($id)
    {
        $withdraw = WithdrawHistory::where('id', $id)
            ->with('user', 'user.withdrawDetails')
            ->first();
        $user = $withdraw->user;

        $client = new Client();
        $url = 'https://api.cubpay.in/Payout/DoPayout';
        $client_transaction_id = Str::random(12);
        $data = [
            'UserId' => env('CUB_PAY_USER_ID'),
            'apikey' => env('CUB_PAY_PAYOUT_API_KEY'),
            'Amount' => $withdraw->amount,
            'AccountNo' => $user->withdrawDetails->account_number,
            'IFSC' => $user->withdrawDetails->account_ifsc_code,
            'SenderMobile' => $user->phone,
            'SenderName' => $user->withdrawDetails->account_holder_name,
            'SenderEmail' => $user->phone . '@gmail.com',
            'BeneName' => $user->withdrawDetails->account_holder_name,
            'BeneMobile' => $user->phone,
            'OrderId' => $client_transaction_id,
            'SPKey' => 'IMPS',
        ];


        try {
            $response = $client->post($url, [
                'json' => $data
            ]);

            $body = $response->getBody()->getContents();
            $parsedResponse = json_decode($body, true);

            //log parsed response
            Log::info($parsedResponse);

            if (isset($parsedResponse[0]['status'])) {
                if ($parsedResponse[0]['status'] == '0') {
                    $message = "API Error: " . $parsedResponse[0]['message'] ?? 'Message Not Found';
                    return $message;
                } elseif ($parsedResponse[0]['status'] == '1' && $parsedResponse[0]['message'] == 'Success') {
                    $withdraw->status = "success";
                    $withdraw->save();
                    $user->transactions()->create([
                        'previous_amount' => $user->balance,
                        'amount' => $withdraw->amount,
                        'current_amount' => $withdraw->amount + $user->balance,
                        "type" => "withdraw",
                        "details" => "Withdraw ($withdraw->amount) Accepted"
                    ]);
                    $user->notify(new WithdrawRequestAcceptNotification($withdraw->amount, $user->fcm, $user->one_signalsubscription_id));
                    $message = "Payment Request sent to API. Message from API: " . $parsedResponse[0]['message'] ?? 'Message Not Found';
                    return $message;
                } else {
                    $message = "Payment Request sent to API. Message from API: " . $parsedResponse[0]['message'] ?? 'Message Not Found';
                    return $message;
                }
            } else {
                $message = "API Error: " . $parsedResponse[0]['message'] ?? 'Message Not Found';
                return $message;
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            $message = "API Error: " . $error;
            return $message;
        }
    }

    private function acceptRequestRunPaisa($id)
    {
        $withdraw = WithdrawHistory::where('id', $id)
            ->with('user', 'user.withdrawDetails')
            ->first();
        $user = $withdraw->user;

        // Prepare the request fields
        $fields = [
            "beneName" => $user->withdrawDetails->account_holder_name,
            "accountNo" => $user->withdrawDetails->account_number,
            "ifsc" => $user->withdrawDetails->account_ifsc_code,
            "bank" => $user->withdrawDetails->bank_name,
            "amount" => $withdraw->amount,
            "mode" => "IMPS",
            // "mode" => "NEFT",
            "RefId" => Str::random(12),
            "customer_name" => $user->name,
            "purpose" => "refund",
            "clientRefId" => Str::random(12),
        ];

        try {
            $bearer_token = base64_encode(env('RUNPAISA_PAYOUT_CLIENT_ID') . ":" . env('RUNPAISA_PAYOUT_CLIENT_SECRET'));
            // $bearer_token = base64_encode("SAFEE_f5fe7b7ab1b034b02729898184590515:a72e8d0a2f2723dada961b6194b105262729898184595242");
            $http_request = Http::withHeaders([
                "Content-Type" => "application/json",
                "Authorization" => "Basic " . $bearer_token,
            ])->post("https://dashboard.xettle.net/v1/service/payout/ordersInitiate", $fields);

            // Get the response
            $response = $http_request->json();
            Log::info('acceptRequestRunPaisa 2');
            Log::info($response);

            // Check if status is true and process the response
            if (isset($response['status']) && $response['status'] == "SUCCESS") {
                $withdraw->status = "success";
                $withdraw->save();
                $user->transactions()->create([
                    'previous_amount' => $user->balance,
                    'amount' => $withdraw->amount,
                    'current_amount' => $withdraw->amount + $user->balance,
                    "type" => "withdraw",
                    "details" => "Withdraw ($withdraw->amount) Accepted"
                ]);
                $user->notify(new WithdrawRequestAcceptNotification($withdraw->amount, $user->fcm, $user->one_signalsubscription_id));
                $message = "Payment Request sent to RunPaisa API successfully.";
                return $message;
            } else {
                // Handle error messages if any
                $error_message = 'API Error: ';

                if (isset($response['message']) && is_array($response['message'])) {
                    foreach ($response['message'] as $field => $errors) {
                        $error_message .= "$field: " . implode(', ', $errors) . "; ";
                    }
                } else {
                    $error_message .= $response['message'] ?? 'Message Not Found';
                }

                return rtrim($error_message, '; ');
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            Log::info('acceptRequestRunPaisa 3');
            Log::info($error);
            $message = "API Error: " . $error;
            return $message;
        }
    }

    private function acceptRequestApiSonicPe($id)
    {
        $withdraw = WithdrawHistory::where('id', $id)
            ->with('user', 'user.withdrawDetails')
            ->first();
        $user = $withdraw->user;

        $data = [
            'token' => env('GATEWAY_SONIC_PE_API_TOKEN'),
            'type' => 'bank',
            'name' => $user->withdrawDetails->account_holder_name,
            'bank' => $user->withdrawDetails->bank_name,
            'account' => $user->withdrawDetails->account_number,
            'ifsc' => $user->withdrawDetails->account_ifsc_code,
            'mode' => 'IMPS',
            'mobile' => $user->phone,
            'email' => $user->phone . '@gmail.com',
            'address' => 'delhi',
            'apitxnid' => Str::random(12),
            'amount' => $withdraw->amount
        ];

        try {

            $http_request = Http::asForm()->withHeaders([
                "Content-Type" => "application/x-www-form-urlencoded",
                "mid" => env('SONIC_PAYOUT_MERCHANT_ID'),
                "apikey" => env('SONIC_PAYOUT_API_KEY'),
            ])->post("http://payout.sonicpe.com/api/v1/payout", $data);

            // Get the response
            $response = $http_request->json();
            Log::info('acceptRequestApiSonicPe');
            Log::info($response);
        } catch (\Exception $e) {
            $error = $e->getMessage();
            $message = "API Error: " . $error;
            return $message;
        }
    }

    private function acceptRequestPlanetC($id)
    {
        Log::info('acceptRequestPlanetC 1');
        $withdraw = WithdrawHistory::where('id', $id)
            ->with('user', 'user.withdrawDetails')
            ->first();
        $user = $withdraw->user;

        $transController = new TransactionController();
        $planetCToken = $transController->getPlanetCToken();
        if ($planetCToken === NULL) {
            return response()->failed('Planet C Token Error');
        }

        // Prepare the request fields
        $fields = [
            "beneName" => $user->withdrawDetails->account_holder_name,
            "beneAccountNo" => $user->withdrawDetails->account_number,
            "beneifsc" => $user->withdrawDetails->account_ifsc_code,
            "benePhoneNo" => $user->phone,
            "beneBankName" => $user->withdrawDetails->bank_name,
            "clientReferenceNo" => Str::random(12),
            "amount" => $withdraw->amount,
            "fundTransferType" => "imps",
            "token_key" => env('PLANET_C_IP_TOKEN'),
            "lat" => "22.8031731",
            "long" => "22.8031731",
            "remarks" => "Payment",
        ];

        try {
            $http_request = Http::asForm()->withHeaders([
                "Content-Type" => "application/x-www-form-urlencoded",
                "Authorization" => $planetCToken,
            ])->post("https://planetctechnology.in/planetcapi/auth/payout/payoutApi", $fields);

            // Get the response
            $response = $http_request->json();
            Log::info('acceptRequestPlanetC 2');
            Log::info($response);

            // Check if status is true and extract the URL and message
            $url = '';
            $message = '';
            if (isset($response['status']) && $response['status'] === true) {
                $withdraw->status = "success";
                $withdraw->save();
                $user->transactions()->create([
                    'previous_amount' => $user->balance,
                    'amount' => $withdraw->amount,
                    'current_amount' => $withdraw->amount + $user->balance,
                    "type" => "withdraw",
                    "details" => "Withdraw ($withdraw->amount) Accepted"
                ]);
                $user->notify(new WithdrawRequestAcceptNotification($withdraw->amount, $user->fcm, $user->one_signalsubscription_id));
                $message = "Payment Request sent to API. Message from API: " . $message;
                return $message;
            } else {
                // Handle error messages if any
                $error_message = $response['data']['message'] ?? 'Message Not Found';
                return "API Error: " . $error_message;
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            Log::info('acceptRequestPlanetC 3');
            Log::info($error);
            $message = "API Error: " . $error;
            return $message;
        }
    }

    private function acceptRequestApiUPIMoney($id)
    {
        $withdraw = WithdrawHistory::where('id', $id)
            ->with('user', 'user.withdrawDetails')
            ->first();
        $user = $withdraw->user;

        $client = new Client();
        $url = 'https://upimoney.co.in/api/payout/transaction';
        $data = [
            'token' => env('GATEWAY_UPI_MONEY_API_TOKEN'),
            'type' => 'bank',
            'name' => $user->withdrawDetails->account_holder_name,
            'bank' => $user->withdrawDetails->bank_name,
            'account' => $user->withdrawDetails->account_number,
            'ifsc' => $user->withdrawDetails->account_ifsc_code,
            'mode' => 'IMPS',
            'mobile' => $user->phone,
            'email' => $user->phone . '@gmail.com',
            'address' => 'delhi',
            'apitxnid' => Str::random(12),
            'amount' => $withdraw->amount
        ];

        try {
            $response = $client->post($url, [
                'json' => $data
            ]);

            $body = $response->getBody()->getContents();
            $parsedResponse = json_decode($body, true);

            //log parsed response
            Log::info($parsedResponse);

            if (isset($parsedResponse['statuscode'])) {
                $statuscode = $parsedResponse['statuscode'];
                if (isset($parsedResponse['message']))
                    $api_message = $parsedResponse['message'];
                else $api_message = "No Message Found";

                if ($statuscode == 'ERR') {
                    $message = "API Error: " . $api_message;
                    return $message;
                } elseif ($statuscode == 'TXN') {
                    $status = $parsedResponse['status'];
                    if ($status == 'inprocess') {
                        $withdraw->status = "success";
                        $withdraw->save();
                        $user->transactions()->create([
                            'previous_amount' => $user->balance,
                            'amount' => $withdraw->amount,
                            'current_amount' => $withdraw->amount + $user->balance,
                            "type" => "withdraw",
                            "details" => "Withdraw ($withdraw->amount) Accepted"
                        ]);
                        $user->notify(new WithdrawRequestAcceptNotification($withdraw->amount, $user->fcm, $user->one_signalsubscription_id));
                        $message = "Payment Request sent to API. Message from API: " . $api_message;
                        return $message;
                    } else {
                        $message = "Unknown Status, Please Contact Support, Status Code is: " . $status;
                        return $message;
                    }
                } elseif ($statuscode == 'TXF') {
                    $message = "API Error, " . $api_message . ", Please Mannually Send Payment to user or Contact Support";
                    return $message;
                } else {
                    $message = "API Error, Invalid Status message, Status Code is: " . $statuscode;
                    return $message;
                }
            } else {
                $message = "API Error, Invalid Status message, Status Code is: ";
                return $message;
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            $message = "API Error: " . $error;
            return $message;
        }
    }

    private function getApiUniversePayToken(){
        $universepay_email = env('UNIVERSEPAY_EMAIL');
        $universepay_password = env('UNIVERSEPAY_PASSWORD');
        
        try {
            $http_request = Http::withHeaders(["Content-Type" => "application/json",])->timeout(60)
                ->post("https://universepay.in/api/auth/login", [
                    "email" => $universepay_email,
                    "password" => $universepay_password,
                ]);
            $response = $http_request->json();

            Log::info("Get Api UniversePay Token Response : " . json_encode($response));

            if (!$http_request->ok()) {
                $message = "Failed to get API token!";
                return $message;
            } else {

                if (!$response['status']) {
                    $message = $response["message"];
                    return $message;
                }

                return $response['access_token'];
            }
        } catch (\Exception $e) {
            Log::error('Get Api UniversePay Token Error: ' . $e->getMessage());
        }
    }

    private function acceptRequestApiUniversePay($id)
    {
        $withdraw = WithdrawHistory::where('id', $id)->with('user', 'user.withdrawDetails')->first();
        $user = $withdraw->user;

        $token = "Bearer " . $this->getApiUniversePayToken() ?? "";

        Log::info("UniversePay Token: " . $token);

        $payload = [
            "amount" => $withdraw->amount,
            "ifsc" => $user->withdrawDetails->account_ifsc_code,
            "accountno" => $user->withdrawDetails->account_number,
            "name" => $user->withdrawDetails->account_holder_name,
            "branch" => "Bank Branch Address",
            "paymode" => "IMPS", // IMPS/NEFT/RTGS
            "udf1" => $user->name,
            "udf2" => $user->phone,
            "udf3" => "",
            "remarks" => "Withdraw ($withdraw->amount) Accepted",
            "mode" => "bank"
        ];

        try {
            $http_request = Http::withHeaders([
                "Content-Type" => "application/json",
                "Authorization" => $token,
            ])->timeout(60)->post("https://universepay.in/api/transfer", $payload);
            $response = $http_request->json();

            Log::info("Payout UniversePay Payout Response : " . json_encode($response));

            if (!$response['status']) {
                $message = $response["message"];
                return $message;
            }

            $withdraw->status = "success";
            $withdraw->transaction_id = $response['data']['data']['orderId'];
            $withdraw->save();
            $user->transactions()->create([
                'previous_amount' => $user->balance,
                'amount' => $withdraw->amount,
                'current_amount' => $withdraw->amount + $user->balance,
                "type" => "withdraw",
                "details" => "Withdraw ($withdraw->amount) Accepted"
            ]);
            $user->notify(new WithdrawRequestAcceptNotification($withdraw->amount, $user->fcm, $user->one_signalsubscription_id));

            $message = $response["data"]["message"] ?? "Payout processed successfully.";
            return $message;
            
        } catch (\Exception $e) {
            Log::error('Payout UniversePay Payout Error: ' . $e->getMessage());
            $error = $e->getMessage();
            $message = "API Error: " . $error;
            return $message;
        }

    }

    public function acceptRequestApi($id)
    {
        $appData = AppData::first();
        $payout_name = $appData->withdrawal_method;
        // $payout_name = 'vagon_pay';

        //if already success then return error
        $withdraw = WithdrawHistory::findOrFail($id);
        if ($withdraw->status == "success") {
            return back()->with('error', 'Request has already been accepted');
        }

        if ($payout_name == 'manual') {
            return back()->with('success', 'Payout is disabled (set to Mannual), Please enable it from settings.');
        } elseif ($payout_name === 'ibr_pay') {
            $message = $this->acceptRequestApiIBRPayNew($id);
            return back()->with('success', $message);
        } elseif ($payout_name === 'upi_money') {
            $message = $this->acceptRequestApiUPIMoney($id);
            return back()->with('success', $message);
        } elseif ($payout_name === 'cub_pay') {
            $message = $this->acceptRequestApiCubPay($id);
            return back()->with('success', $message);
        } elseif ($payout_name === 'planet_c') {
            $message = $this->acceptRequestPlanetC($id);
            return back()->with('success', $message);
        } elseif ($payout_name === 'sonic_pe') {
            $message = $this->acceptRequestApiSonicPe($id);
            return back()->with('success', $message);
        } elseif ($payout_name === 'run_paisa') {
            $message = $this->acceptRequestRunPaisa($id);
            return back()->with('success', $message);
        } elseif ($payout_name === 'click_pay') {
            $message = $this->acceptRequestClickPay($id);
            return back()->with('success', $message);
        } elseif ($payout_name === 'vagon_pay') {
            $message = $this->acceptRequestVagonPay($id);
            return back()->with('success', $message);
        } elseif ($payout_name === 'rudrax_pay') {
            $message = $this->acceptRequestRudraxPay($id);
            return back()->with('success', $message);
        } elseif ($payout_name === 'payinfintech') {
            $message = $this->acceptRequestPayinfintech($id);
            return back()->with('success', $message);
        } elseif ($payout_name === 'universepay') {
            $message = $this->acceptRequestApiUniversePay($id);
            return back()->with('success', $message);
        } else {
            return back()->with('success', 'Payout API is not configured');
        }
    }

    // Payin Fintech Token Start
    private function acceptRequestPayinfintech($id) {
        Log::info('acceptRequestPayinfintech');

        // Fetch withdrawal request and user details
        $withdraw = WithdrawHistory::where('id', $id)->with('user', 'user.withdrawDetails')->first();

        if (!$withdraw) {
            return "Withdrawal request not found.";
        }

        $user = $withdraw->user;
        $appData = AppData::first();
        $token = $appData->payin_fintech_token;

        // Prepare API endpoint and request data
        $client = new Client();
        $url = 'https://api.payinfintech.com/partner/payout';
        $data = [
            'Amount' => $withdraw->amount,
            'AccountNumber' => $user->withdrawDetails->account_number,
            'Bank' => $user->withdrawDetails->bank_name,
            'IFSC' => $user->withdrawDetails->account_ifsc_code,
            'Mode' => "IMPS",
            'OrderId' => Str::random(12), // Unique order ID
            'Mobile' => $user->phone,
        ];

        Log::info("========== Payload Data =======");
        Log::info($data);

        try {
            // Send POST request
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
                'json' => $data
            ]);

            $body = $response->getBody()->getContents();
            $parsedResponse = json_decode($body, true);

            // Log parsed response
            Log::info($parsedResponse);

            if (isset($parsedResponse['status']) && $parsedResponse['status'] === true) {
                // Mark withdrawal as success
                $withdraw->status = "success";
                $withdraw->withdrawal_method = "payinfintech";
                $withdraw->save();

                // Update user transaction history
                $user->transactions()->create([
                    'previous_amount' => $user->balance,
                    'amount' => $withdraw->amount,
                    'current_amount' => $user->balance - $withdraw->amount,
                    "type" => "withdraw",
                    "details" => "Withdraw ($withdraw->amount) Accepted via payinfintech",
                ]);

                // Notify the user
                $user->notify(new WithdrawRequestAcceptNotification($withdraw->amount, $user->fcm, $user->one_signalsubscription_id));

                return "Payout request successful. API Response: " . $parsedResponse['message'];
            } else {
                $message = "Payout failed. API Response: " . ($parsedResponse['message'] ?? 'Unknown error');
                return $message;
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            Log::error($error);

            return "API Error: " . $error;
        }
    }

    public function payinfintechToken() {
        $appData = AppData::first();
        
        $client = new Client();
        $url = 'https://api.payinfintech.com/api-login-merchant';
        $data = [
            'email' => env('PAYIN_FINTECH_EMAIL'),
            'password' => env('PAYIN_FINTECH_PASSWROD'),
        ];

        try {
            $response = $client->post($url, [
                'json' => $data
            ]);

            $body = $response->getBody()->getContents();
            $parsedResponse = json_decode($body, true);

            if (isset($parsedResponse['message']) && $parsedResponse['message'] == 'success') {
                $appData->payin_fintech_token = $parsedResponse['access_token'];
                $appData->save();

                $message = "Token generate successfully: " . $parsedResponse['access_token'];
                return back()->with('success', $message);
            } else {
                $message = "payinfintechToken API Response: " . ($parsedResponse['message'] ?? 'Unknown error');
                return back()->with('error', $message);
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            $message = "payinfintechToken API Error: " . $error;
            return back()->with('error', $message);
        }
    }
    // Payin Fintech Token End

    private function acceptRequestRudraxPay($id)
    {
        Log::info('acceptRequestRudraxPay');

        // Fetch withdrawal request and user details
        $withdraw = WithdrawHistory::where('id', $id)
            ->with('user', 'user.withdrawDetails')
            ->first();

        if (!$withdraw) {
            return "Withdrawal request not found.";
        }

        $user = $withdraw->user;

        // Prepare API endpoint and request data
        $client = new Client();
        $url = 'https://merchant.rudraxpay.com/api/payout/initiate';
        $data = [
            'token' => env('RUDRAX_PAY_TOKEN'), // API token from .env
            'userid' => env('RUDRAX_PAY_USER_ID'),  // UserID from .env
            'amount' => $withdraw->amount,
            'mobile' => $user->phone,
            'name' => $user->withdrawDetails->account_holder_name,
            'number' => $user->withdrawDetails->account_number,
            'ifsc' => $user->withdrawDetails->account_ifsc_code,
            'orderid' => Str::random(12), // Unique order ID
        ];

        try {
            // Send POST request
            $response = $client->post($url, [
                'json' => $data
            ]);

            $body = $response->getBody()->getContents();
            $parsedResponse = json_decode($body, true);

            // Log parsed response
            Log::info($parsedResponse);

            if (isset($parsedResponse['status']) && $parsedResponse['status'] === true) {
                // Mark withdrawal as success
                $withdraw->status = "success";
                $withdraw->save();

                // Update user transaction history
                $user->transactions()->create([
                    'previous_amount' => $user->balance,
                    'amount' => $withdraw->amount,
                    'current_amount' => $user->balance - $withdraw->amount,
                    "type" => "withdraw",
                    "details" => "Withdraw ($withdraw->amount) Accepted via RudraxPay",
                ]);

                // Notify the user
                $user->notify(new WithdrawRequestAcceptNotification($withdraw->amount, $user->fcm, $user->one_signalsubscription_id));

                return "Payout request successful. API Response: " . $parsedResponse['message'];
            } else {
                $message = "Payout failed. API Response: " . ($parsedResponse['message'] ?? 'Unknown error');
                return $message;
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            Log::error($error);

            return "API Error: " . $error;
        }
    }


    private function acceptRequestVagonPay($id)
    {
        $withdraw = WithdrawHistory::where('id', $id)
            ->with('user', 'user.withdrawDetails')
            ->first();
        $user = $withdraw->user;

        $client = new Client();
        $url = 'https://api.vagon.in/walletout';
        $agent_id = $this->generateRandomString(20);
        $beneName = $user->withdrawDetails->account_holder_name;
        if (strlen($beneName) < 5) {
            $beneName .= 'abcd';
        }
        $beneName = substr($beneName, 0, 20);
        Log::info('acceptRequestVagonPay ' . $agent_id);

        $data = [
            'benename' => $beneName,
            'Txnamount' => $withdraw->amount,
            'bank_account' => $user->withdrawDetails->account_number,
            'bank_name' => $user->withdrawDetails->bank_name,
            'ifsc_code' => $user->withdrawDetails->account_ifsc_code,
            'order_id' => $agent_id,
            'user_key' => env('VAGON_PAY_USER_KEY'),
            'user_token' => env('VAGON_PAY_USER_TOKEN'),
            'mobile' => $user->phone,
            // 'agent_id' => $agent_id,
        ];


        Log::info($data);

        try {
            $response = $client->post($url, [
                'form_params' => $data
            ]);

            $body = $response->getBody()->getContents();
            $parsedResponse = json_decode($body, true);

            // Log parsed response
            Log::info($parsedResponse);

            if (isset($parsedResponse['status'])) {
                if ($parsedResponse['status'] === 'PROCESSING') {
                    $withdraw->status = "success";
                    $withdraw->save();
                    $user->transactions()->create([
                        'previous_amount' => $user->balance,
                        'amount' => $withdraw->amount,
                        'current_amount' => $withdraw->amount + $user->balance,
                        "type" => "withdraw",
                        "details" => "Withdraw ($withdraw->amount) Accepted"
                    ]);
                    $user->notify(new WithdrawRequestAcceptNotification($withdraw->amount, $user->fcm, $user->one_signalsubscription_id));
                    $message = "Payment Request sent to API. Message from API: " . $parsedResponse['status'] ?? 'Message Not Found';
                    return $message;
                } elseif ($parsedResponse['status'] === 'ERROR') {
                    $message = "API Erro. Message from API: " . $parsedResponse['message'] ?? 'Message Not Found';
                    return $message;
                } else {
                    $message = "Payment Request sent to API. Message from API: " . $parsedResponse['status'] ?? 'Message Not Found';
                    return $message;
                }
            } else {
                $message = "API Error: Invalid response format";
                return $message;
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            $message = "API Error: " . $error;
            return $message;
        }
    }



    public function acceptRequest($id)
    {
        $withdraw = WithdrawHistory::findOrFail($id);
        if ($withdraw->status == "success") {
            return back()->with('error', 'Request has already been accepted');
        }
        $withdraw->status = "success";
        $withdraw->save();
        $user = $withdraw->user;
        $user->transactions()->create([
            'previous_amount' => $user->balance,
            'amount' => $withdraw->amount,
            'current_amount' => $withdraw->amount + $user->balance,
            "type" => "withdraw",
            "details" => "Withdraw ($withdraw->amount) Accepted"
        ]);
        $user->notify(new WithdrawRequestAcceptNotification($withdraw->amount, $user->fcm, $user->one_signalsubscription_id));
        return back()->with('success', 'Request has been accepted');
    }

    public function rejectRequest($id)
    {
        $withdraw = WithdrawHistory::with("user")->findOrFail($id);
        if ($withdraw->status == "failed") {
            return back()->with('error', 'Request has already been rejected');
        }
        $withdraw->status = "failed";
        $withdraw->save();

        $previous_amount = $withdraw->user->balance;
        $current_amount = $previous_amount + $withdraw->amount;

        $user = $withdraw->user;
        $user->balance += $withdraw->amount;
        $user->withdrawal_balance += $withdraw->amount;
        $user->save();
        $user->transactions()->create([
            'previous_amount' => $previous_amount,
            'amount' => $withdraw->amount,
            'current_amount' => $current_amount,
            "type" => "withdraw",
            "details" => "Withdraw ($withdraw->amount) Rejected"
        ]);
        $user->notify(new WithdrawRequestRejectNotification($withdraw->amount, $user->fcm, $user->one_signalsubscription_id));
        return back()->with('success', 'Request has been rejected');
    }
}
