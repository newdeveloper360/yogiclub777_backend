<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppData;
use App\Models\Otp;
use App\Models\User;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OtpController extends Controller
{
    public static $meraOtp = true;

    public static function sendOtpMeraOtp($otp_to_send, $phoneNumber)
    {
        Log::info('Sending OTP SMS otp is ' . $otp_to_send);
        $sms_api_key = AppData::pluck('sms_api_key')->first();
        if (filled($sms_api_key)) {

            //insert into otps table
            $otp = Otp::create([
                'otp' => $otp_to_send,
                'phone' => $phoneNumber,
                'created_at' => now(),
            ]);

            $client = new Client();
            $url = "https://meraotp.com/api/sms";
            $fields = [
                "api_key" => $sms_api_key,
                "sms_type" => "otp",
                "mobile_number" => $phoneNumber,
                "message" => $otp_to_send,
            ];

            try {
                $response = $client->post($url, [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        "Authorization" => $sms_api_key,
                    ],
                    'json' => $fields
                ]);

                Log::info("Response : ");
                Log::info($response->getBody()->getContents());
            } catch (\Throwable $th) {
                Log::error('Error sending OTP SMS ' . $th->getMessage());
            }
        }
    }

    public static function verifyMeraOtp($otp, $phoneNumber)
    {
        if (filled($otp) && filled($phoneNumber)) {
            $client = new Client();
            $url = "https://meraotp.com/api/sms-validate";
            $fields = [
                "mobile_number" => $phoneNumber,
                "otp" => $otp,
            ];

            try {
                $response = $client->post($url, [
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'json' => $fields
                ]);

                // Convert response to string and decode JSON
                $responseBody = (string) $response->getBody();
                $responseData = json_decode($responseBody, true); // Convert to array

                // Extract status and log it
                Log::info('OTP SMS Status: ' . $responseData['success']);
                return $responseData['success'] ?? false;
            } catch (\Throwable $th) {
                return false;
                Log::error('Error sending OTP SMS ' . $th->getMessage());
            }
        }
    }

    public static function sendOtpA2TechNo($otp_to_send, $phoneNumber)
    {

        $client = new Client();
        $url = "https://a2technosoft.services/api/v1/sms-secure-push";

        $fields = [
            'key' => env('A2TECHNOSOFT_API_KEY'),
            // 'sms_type' => 'otp',
            'mobile' => $phoneNumber,
            'otp' => $otp_to_send,
        ];

        try {
            $response = $client->post($url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => $fields
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);
            $requestId = $responseBody['requestId'] ?? null; // Extract requestId


            if ($requestId) {
                Otp::create([
                    'otp' => $otp_to_send,
                    'phone' => $phoneNumber,
                    'created_at' => now(),
                    'requestId' => $requestId
                ]);
            } else {
                Log::warning("OTP sent but requestId is missing for phone: $phoneNumber");
                Log::warning('Response from SMS API: ' . json_encode($responseBody));
            }

            Log::info('Response from SMS API: ' . $response->getBody()->getContents());
        } catch (\Throwable $th) {
            Log::error('Error sending OTP SMS: ' . $th->getMessage());
        }
    }

    public static function verifyOtpA2TechNo($otp, $phoneNumber)
    {
        Log::info('Verifying OTP: ' . $otp . ' for phone: ' . $phoneNumber);
        $otpEntry = Otp::where('phone', $phoneNumber)->latest()->first();
        if (!$otpEntry) {
            Log::warning("OTP verification failed, OTP not found for phone: $phoneNumber");
            return false;
        }

        $requestId = $otpEntry->requestId;
        $client = new Client();
        $url = "https://a2technosoft.services/api/v1/sms-check-otp";

        $fields = [
            'key' => "P89tFbgWTvmLxK3QjMYIdrmVf93U4brGiQ4fekhYeYturMqzIHNA",
            'otp' => $otp,
            'requestId' => $requestId
        ];
        try {
            $response = $client->post($url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'query' => $fields
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);
            Log::info('Response from OTP verification API: ' . json_encode($responseBody));

            if ($responseBody['status'] === true && $responseBody['Status_code'] == 106) {
                return true;
            } else {
                Log::warning("OTP verification failed, Status False for phone: $phoneNumber");
                return false;
            }
        } catch (\Throwable $th) {
            Log::error('Error verifying OTP: ' . $th->getMessage());
            return false;
        }
    }

    public function sendOtpSms($otp_to_send, $phoneNumber)
    {
        $sms_api_key = AppData::pluck('sms_api_key')->first();

        if (filled($sms_api_key)) {
            $otp = Otp::create([
                'otp' => $otp_to_send,
                'phone' => $phoneNumber,
                'created_at' => now(),
            ]);

            $client = new Client();
            $url = "https://a2technosoft.services/api/v1/sms-secure-push";

            $fields = [
                'key' => $sms_api_key,
                // 'sms_type' => 'otp',
                'mobile' => $phoneNumber,
                'otp' => $otp_to_send,
            ];

            try {
                $response = $client->post($url, [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ],
                    'json' => $fields
                ]);

                Log::info('Response from SMS API: ' . $response->getBody()->getContents());
            } catch (\Throwable $th) {
                Log::error('Error sending OTP SMS: ' . $th->getMessage());
            }
        }
    }

    public function sendOtpSms2($otp_to_send, $phoneNumber)
    {
        Log::info('Sending OTP SMS otp is ' . $otp_to_send);
        $sms_api_key = AppData::pluck('sms_api_key')->first();
        if (filled($sms_api_key)) {

            //insert into otps table
            $otp = Otp::create([
                'otp' => $otp_to_send,
                'phone' => $phoneNumber,
                'created_at' => now(),
            ]);

            $client = new Client();
            $url = "https://www.fast2sms.com/dev/bulkV2";
            $fields = [
                // "route" => "p",
                "route" => "otp",
                // "message" => $message,
                "variables_values" => $otp_to_send,
                "language" => "english",
                "flash" => 0,
                "numbers" => $phoneNumber,
            ];
            try {
                $client->post($url, [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        "Authorization" => $sms_api_key,
                    ],
                    'json' => $fields
                ]);
            } catch (\Throwable $th) {
                Log::error('Error sending OTP SMS ' . $th->getMessage());
            }
        }
    }
}
