<?php

use App\Http\Controllers\Api\AppDataController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Chat\DepositChatController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\MarketController;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\WithdrawDetailsController;
use App\Http\Controllers\Api\WithdrawHistoryController;
use App\Http\Controllers\Api\DepositHistoryController;
use App\Http\Controllers\Api\GroupPostingController;
use App\Http\Controllers\Api\Chat\WithdrawChatController;
use App\Http\Controllers\Api\DeleteUserDataHistoryController;
use Illuminate\Support\Facades\Route;

Route::any('/test', [TransactionController::class, 'RedirectUrlPayOMatix']);

//login signup
Route::post('/send-login-otp', [AuthController::class, 'sendLoginOtp'])->middleware('throttle:20,1');
Route::post('/verify-login-otp', [AuthController::class, 'verifyLoginOtp']);
// Route::post('/signup', [AuthController::class, 'create']);

// App data
Route::get('/get-app-data', [AppDataController::class, 'index']);
Route::get('/get-notifications', [AppDataController::class, 'getNotifications']);

// Market
Route::post('/get-markets', [MarketController::class, 'index']);

//forget password
// Route::post('/send-forget-password-otp', [AuthController::class, 'forgetPasswordOtp']);
// Route::post('/verify-forget-password-otp', [AuthController::class, 'forgetPasswordVerify']);
//hi
//transaction
Route::post('/submit-gateyway-payment', [TransactionController::class, 'SubmitGatewayPayment']);
Route::post('/submit-gateyway-payment-ibr-pay', [TransactionController::class, 'SubmitGatewayPaymentIBRPay']);
Route::any('/submit-gateyway-payment-rudrax-pay', [TransactionController::class, 'SubmitGatewayPaymentRudraxPay']);
Route::any('/submit-payout-rudrax-pay', [TransactionController::class, 'SubmitPayoutRudraxPay']);
Route::post('/submit-gateyway-payment-upi-money', [TransactionController::class, 'SubmitGatewayPaymentUPIMoney']);
Route::any('/submit-gateyway-payment-payment-karo', [TransactionController::class, 'SubmitGatewayPaymentPaymentKaro']);
Route::any('/submit-gateyway-payment-planet-c', [TransactionController::class, 'SubmitGatewayPaymentPlanetC']);
Route::any('/submit-gateyway-payment-run-paisa', [TransactionController::class, 'SubmitGatewayPaymentRunPaisa']);
Route::post('/submit-payfromupi-payment', [TransactionController::class, 'SubmitPayFromUpiPayment']);
Route::any('/submit-gateyway-payment-pay-o-matix', [TransactionController::class, 'SubmitPayOMatixPayment']);

// Otp
// Route::post('/send-signup-otp', [OtpController::class, 'send']);
// Route::post('/verify-signup-otp', [AuthController::class, 'confirm']);

// User Signal Subscription
Route::post('/one-signal-subscription-id', [UserController::class, 'oneSignalDubscriptionId']);

Route::middleware(['auth:sanctum', 'throttle:50,1'])->group(function () {

    Route::post('/submit-refer-code', [AuthController::class, 'submitReferCode']);

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/update-profile', [UserController::class, 'updateProfile']);
    Route::get('/get-user-balance', [UserController::class, 'getUserBalance']);

    // User
    // Route::post('/change-notification', [UserController::class, 'changeNotification']);
    Route::post('/withdraw-balance', [UserController::class, 'withdrawBalance']);
    Route::post('/transfer-balance', [TransactionController::class, 'transferBalance']);
    Route::get('/get-referral-details', [UserController::class, 'getReferralDetails']);

    // Games
    Route::post('/submit-game', [GameController::class, 'submitGame']);
    Route::post('/delete-single-play', [GameController::class, 'deletePlay']);
    Route::post('/get-game-history', [GameController::class, 'getGameHistory']);
    Route::post('/get-game-details', [GameController::class, 'getGameDetails']);
    // Route::get('/get-game-results', [GameController::class, 'getGameResults']);
    Route::get('/get-game-rates', [GameController::class, 'getGameRates']);

    // Transactions
    Route::post('/pay-from-upi-payment-url', [TransactionController::class, 'RedirectUrlPayFromUpi']);
    Route::post('/add-payment', [TransactionController::class, 'AddPayment']);
    // Route::post('/get-transactions', [TransactionController::class, 'getTransactions']);
    Route::post('/upi-payment-url', [TransactionController::class, 'RedirectUrl']);
    Route::post('/ibr-pay-upi-payment-url', [TransactionController::class, 'RedirectUrlIBRPay']);
    // Route::post('/upi-money-upi-payment-url', [TransactionController::class, 'RedirectUrlUPIMoney']);
    Route::post('/i-online-pay-upi-payment-url', [TransactionController::class, 'RedirectUrlIOnlinePay']);
    Route::post('/payment-karo-payment-url', [TransactionController::class, 'RedirectUrlPaymentKaro']);
    Route::post('/planet-c-payment-url', [TransactionController::class, 'RedirectUrlPlanetC']);
    Route::post('/sonic-pay-payment-url', [TransactionController::class, 'RedirectUrlSonicPay']);
    Route::post('/run-paisa-payment-url', [TransactionController::class, 'RedirectUrlRunPaisa']);
    Route::post('/rudrax-pay-payment-url', [TransactionController::class, 'RedirectUrlRudraxPay']);
    Route::post('/payotmatix-payment-url', [TransactionController::class, 'RedirectUrlPayOMatix']);

    // Save Withdraw Details
    // Route::post('/save-bank-details', [WithdrawDetailsController::class, 'saveBankDetails']);
    // Route::post('/save-upi-details', [WithdrawDetailsController::class, 'saveUpiDetails']);

    // Withdraw | Deposit History
    Route::get('/get-bonus-report', [TransactionController::class, 'getBonusReport']);
    Route::post('/get-deposit-history', [DepositHistoryController::class, 'getDepositHistory']);
    Route::post('/get-withdrawl-history', [WithdrawHistoryController::class, 'getWithdrawlHistory']);

    // Chats
    Route::get('group-posting/get', [GroupPostingController::class, 'get']);
    Route::post('group-posting/send-message', [GroupPostingController::class, 'sendMessage']);

    Route::get('deposit-chat/get', [DepositChatController::class, 'get']);
    Route::post('deposit-chat/send-message', [DepositChatController::class, 'sendMessage']);
    Route::get('deposit-chat/unread-messages', [DepositChatController::class, 'getUnreadMessagesCount']);

    Route::get('withdraw-chat/get', [WithdrawChatController::class, 'get']);
    Route::post('withdraw-chat/send-message', [WithdrawChatController::class, 'sendMessage']);
    Route::get('withdraw-chat/unread-messages', [WithdrawChatController::class, 'getUnreadMessagesCount']);

    // Delete User Data Histroy
    Route::get('delete-user-data-history', [DeleteUserDataHistoryController::class, 'deleteHistory']);
});
