<?php

use App\Http\Controllers\StartLineChartController;
use App\Http\Controllers\MarketChartController;
use App\Http\Controllers\Dashboard\AppDataController;
use App\Http\Controllers\Dashboard\AuthController;
use App\Http\Controllers\Dashboard\ChatController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\DepositHistoryController;
use App\Http\Controllers\Dashboard\DesawarMarketController;
use App\Http\Controllers\Dashboard\DesawarRecordController;
use App\Http\Controllers\Dashboard\DesawarMarketLimitController;
use App\Http\Controllers\Dashboard\DesawarResultController;
use App\Http\Controllers\Dashboard\DesawarWinPredictionController;
use App\Http\Controllers\Dashboard\GameTypeController;
use App\Http\Controllers\Dashboard\MarketController;
use App\Http\Controllers\Dashboard\MarketRecordController;
use App\Http\Controllers\Dashboard\MarketResultController;
use App\Http\Controllers\Dashboard\MarketWinPredictionController;
use App\Http\Controllers\Dashboard\NotificationController;
use App\Http\Controllers\Dashboard\ProfitLossController;
use App\Http\Controllers\Dashboard\SliderImageController;
use App\Http\Controllers\Dashboard\StartLineMarketController;
use App\Http\Controllers\Dashboard\StartLineRecordController;
use App\Http\Controllers\Dashboard\StartLineResultController;
use App\Http\Controllers\Dashboard\StartLineWinPredictionController;
use App\Http\Controllers\Dashboard\SubAdminController;
use App\Http\Controllers\Dashboard\TransactionController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\WithdrawDetailController;
use App\Http\Controllers\Dashboard\WithdrawHistoryController;
use App\Http\Controllers\DesawarChartController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/test', [HomeController::class, 'test']);

Route::get('/', [HomeController::class, 'index']);
Route::get('/pay', [HomeController::class, 'pay']);
Route::get('/privacy-policy', [HomeController::class, 'privacy']);
Route::get('/charts', [HomeController::class, 'charts']);
Route::get('/login', [AuthController::class, 'index']);
Route::get('/payment/{user_id}/{amount}', [HomeController::class, 'payment']);
Route::get("/verify-utr/{id}/{utr}/{amount}", [HomeController::class, "verifyUTR"]);
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:20,1')->name('login');

// Webapp routes
Route::get('/download', [HomeController::class, 'download'])->name('download-apk');
Route::get('/start-line-market/chart/{market}', [StartLineChartController::class, 'index'])->name('start-line-markets.chart');
Route::get('/market/pana-chart/{market}/{app?}', [MarketChartController::class, 'index'])->name('markets.chart');
Route::get('/market/jodi-chart/{market}', [MarketChartController::class, 'jodiChart'])->name('markets.jodi-chart');
// Route::get('/desawar/chart/{market}/{app?}', [DesawarChartController::class, 'index']);
Route::get('/desawar/chart/{market}', [DesawarChartController::class, 'index'])->name('desawar-markets.chart_view');

// Download bid PDF
Route::get('/download/bid/{fileName}', function ($fileName) {
    $path = storage_path('app/public/bids/' . $fileName);
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->download($path, $fileName, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
    ]);
})->name('download.bid.pdf');

Route::middleware(['auth', 'throttle:20,1'])->group(function () {
    Route::middleware('can:dashboard-view')->prefix('/dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    });
    Route::post('/getBidsDetail', [DashboardController::class, 'getBidsDetail']);

    Route::middleware('can:clear-dashboard-data')->prefix('/delete-dashboard-data')->group(function () {
        Route::get('/', [DashboardController::class, 'deleteChetDepositWithdrawl'])->name('deleteChetDepositWithdrawl');
    });
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // change-password
    Route::middleware('can:change-password')->prefix('/change-password')->group(function () {
        Route::get('/', [AuthController::class, 'changePasswordIndex'])->name('change-password.index');
        Route::post('/', [AuthController::class, 'changePasswordStore'])->name('change-password.store');
    });
    // Users Routes
    Route::middleware('can:users')->prefix('/users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/detail/{id}', [UserController::class, 'getUserDetail'])->name('users.Detail');
        Route::get('/detail/{id}/all', [UserController::class, 'getUserAllDetail'])->name('users.AllDetail');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/store', [UserController::class, 'store'])->name('users.store');
        // Balance
        Route::get('/{user}/change-balance', [UserController::class, 'changeBalanceView'])
            ->name('users.change-balance.show')->middleware('can:add-deduct-balance-users');
        Route::post('/{user}/change-balance', [UserController::class, 'changeBalance'])
            ->name('users.change-balance.store')->middleware('can:add-deduct-balance-users');
        Route::post('/toogle-block', [UserController::class, 'toogleBlock'])
            ->name('users.toogle-blocked.change')->can('users.toogle-blocked.change');
        // withdraw details
        Route::get('/withdraw-details', [WithdrawDetailController::class, 'index'])->name('users.withdraw-details.index');
        Route::get('/withdraw-details/search', [WithdrawDetailController::class, 'search'])->name('users.withdraw-details.search');

        Route::get('/user-login/{id}', [UserController::class, 'adminToUserLogin'])->name('admin.to.user.login');
    });

    Route::middleware(['can:markets', 'access-desawar-only'])->group(function () {
        // Markets Routes
        Route::prefix('/markets')->group(function () {
            Route::get('/', [MarketController::class, 'index'])->name('markets.index');
            Route::get('/create', [MarketController::class, 'create'])->name('markets.create')->can('create-markets');
            Route::post('/store', [MarketController::class, 'store'])->name('markets.store')->can('create-markets');
            Route::get('/{market}/edit', [MarketController::class, 'edit'])->name('markets.edit')->can('edit-markets');
            Route::put('/{market}/update', [MarketController::class, 'update'])->name('markets.update')->can('update-markets');
            Route::get('/{market}/destroy', [MarketController::class, 'destroy'])->name('markets.destroy')->can('delete-markets');
            // Market Results
            Route::get('/results', [MarketResultController::class, 'index'])->name('markets.results');
            Route::post('/results', [MarketResultController::class, 'store'])->name('markets.results.store');
            Route::get('/results/{id}', [MarketResultController::class, 'revert'])->name('markets.results.revert');
            // Market Results Prediction
            Route::get('/prediction-results', [MarketWinPredictionController::class, 'index'])->name('markets.prediction-results.index');
            Route::get('/prediction-results/show', [MarketWinPredictionController::class, 'getPrediction'])
                ->name('markets.prediction-results.show');
            Route::post('/prediction-results/updateBid', [MarketWinPredictionController::class, 'updatePredictionBid'])
                ->name('markets.prediction-results.updateBid');
            // Market Records
            Route::get('/records', [MarketRecordController::class, 'index'])->name('markets.records');
            // Market Win History
            Route::get('/win-history', [MarketRecordController::class, 'winHistory'])->name('markets.win-history');
            Route::get('/data', [MarketRecordController::class, 'data'])->name('markets.data');
        });
    });

    Route::middleware(['can:startLine', 'access-desawar-only'])->group(function () {
        // Start Line Markets Routes
        Route::prefix('/start-line-markets')->group(function () {
            Route::get('/', [StartLineMarketController::class, 'index'])->name('start-line-markets.index');
            Route::get('/create', [StartLineMarketController::class, 'create'])->name('start-line-markets.create')->can('create-startLine');
            Route::post('/store', [StartLineMarketController::class, 'store'])->name('start-line-markets.store')->can('create-startLine');
            Route::get('/{market}/edit', [StartLineMarketController::class, 'edit'])->name('start-line-markets.edit')->can('edit-startLine');
            Route::put('/{market}/update', [StartLineMarketController::class, 'update'])->name('start-line-markets.update')->can('edit-startLine');
            Route::get('/{market}/destroy', [StartLineMarketController::class, 'destroy'])->name('start-line-markets.destroy')->can('delete-startLine');
            // Market Results
            Route::get('/results', [StartLineResultController::class, 'index'])->name('start-line-markets.results');
            Route::post('/results', [StartlineResultController::class, 'store'])->name('start-line-markets.results.store');
            Route::get('/results/{id}', [StartlineResultController::class, 'revert'])->name('start-line-markets.results.revert');
            // Market Results Prediction
            Route::get('/prediction-results', [StartLineWinPredictionController::class, 'index'])->name('start-line-markets.prediction-results.index');
            Route::get('/prediction-results/show', [StartLineWinPredictionController::class, 'getPrediction'])
                ->name('start-line-markets.prediction-results.show');
            // Market Records
            Route::get('/records', [StartLineRecordController::class, 'index'])->name('start-line-markets.records');
            // Market Win History
            Route::get('/win-history', [StartLineRecordController::class, 'winHistory'])->name('start-line-markets.win-history');
        });
    });

    // Desawar Markets Routes
    Route::middleware(['can:desawar', 'access-desawar'])->group(function () {
        Route::prefix('/desawar-markets')->group(function () {
            Route::get('/', [DesawarMarketController::class, 'index'])->name('desawar-markets.index');
            Route::get('/create', [DesawarMarketController::class, 'create'])->name('desawar-markets.create')->can('create-desawar');
            Route::post('/store', [DesawarMarketController::class, 'store'])->name('desawar-markets.store')->can('create-desawar');
            Route::get('/{market}/edit', [DesawarMarketController::class, 'edit'])->name('desawar-markets.edit')->can('edit-desawar');
            Route::put('/{market}/update', [DesawarMarketController::class, 'update'])->name('desawar-markets.update')->can('edit-desawar');
            Route::get('/{market}/destroy', [DesawarMarketController::class, 'destroy'])->name('desawar-markets.destroy')->can('delete-desawar');
            Route::get('/chart', [DesawarMarketController::class, 'chart'])->name('desawar-markets.chart');
            Route::get('/download-excel', [DesawarMarketController::class, 'downloadExcel'])->name('desawar-markets.download-excel');
            // Market Results
            Route::get('/results', [DesawarResultController::class, 'index'])->name('desawar-markets.results');
            Route::post('/results', [DesawarResultController::class, 'store'])->name('desawar-markets.results.store');
            Route::get('/results/{id}', [DesawarResultController::class, 'revert'])->name('desawar-markets.results.revert');
            // Market Results Prediction
            Route::get('/prediction-results', [DesawarWinPredictionController::class, 'index'])->name('desawar-markets.prediction-results.index');
            Route::get('/prediction-results/show', [DesawarWinPredictionController::class, 'getPrediction'])
                ->name('desawar-markets.prediction-results.show');
            // Market Records
            Route::get('/records', [DesawarRecordController::class, 'index'])->name('desawar-markets.records');
            // Market Win History
            Route::get('/win-history', [DesawarRecordController::class, 'winHistory'])->name('desawar-markets.win-history');

            // Chart Number Limit
            Route::resource('desawar-market-limit', DesawarMarketLimitController::class);
            // Market Chart Number Bise Records
            Route::get('/chart-no-records', [DesawarRecordController::class, 'chartNoRecords'])->name('desawar-markets.chartNoRecords');
            Route::get('/cancel-bet', [DesawarRecordController::class, 'cancelBet'])->name('cancelBet');
        });
    });

    // App Data
    Route::middleware('can:app-data')->prefix('/app-data')->group(function () {
        Route::get('/', [AppDataController::class, 'index'])->name('app-data.index');
        Route::post('/', [AppDataController::class, 'store'])->name('app-data.store');
        Route::get('/home-page-img-delete', [AppDataController::class, 'HomePageImgDelete'])->name('app-data.HomePageImgDelete');
    });

    // Payment Getway Setting
    Route::middleware('can:app-data')->prefix('payment-getway-setting')->group(function () {
        Route::get('/', [AppDataController::class, 'paymentGetwaySetting'])->name('payment-getway-setting.index');
        Route::post('/', [AppDataController::class, 'paymentGetwaySettingUpdate'])->name('payment-getway-setting.update');
    });

    // Game Types
    Route::middleware('can:game-types')->prefix('/game-types')->group(function () {
        Route::get('/', [GameTypeController::class, 'index'])->name('game-types.index');
        Route::post('/{id}', [GameTypeController::class, 'update'])->name('game-types.update')->can('update-game-types');
    });

    // Profit And Loss
    Route::middleware('can:profit-loss')->prefix('/profit-loss')->group(function () {
        Route::get('/', [ProfitLossController::class, 'index'])->name('profit-loss.index');
    });

    // Notifications
    Route::middleware('can:notifications')->prefix('/notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/store', [NotificationController::class, 'store'])->name('notifications.store');
    });

    // Transactions
    Route::middleware('can:transactions')->prefix('/transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('transactions.index');
    });

    // Withdraw History
    Route::middleware('can:withdraw-history')->prefix('/withdraw-history')->group(function () {
        Route::get('/', [WithdrawHistoryController::class, 'index'])->name('withdraw-history.index');

        Route::get('/accept-request/{id}', [WithdrawHistoryController::class, 'acceptRequest'])->name('withdraw-request.accept')->can('withdraw-request-accept');

        Route::get('/accept-request-api/{id}', [WithdrawHistoryController::class, 'acceptRequestApi'])->name('withdraw.accept-api')->can('withdraw-request-accept');


        Route::get('/reject-request/{id}', [WithdrawHistoryController::class, 'rejectRequest'])->name('withdraw-request.reject')->can('withdraw-request-reject');
    });

    // payinfintechToken
    Route::get('/payinfintech-token', [WithdrawHistoryController::class, 'payinfintechToken'])->name('payinfintechToken');

    // Deposit History
    Route::middleware('can:deposit-history')->prefix('/deposit-history')->group(function () {
        Route::get('/', [DepositHistoryController::class, 'index'])->name('deposit-history.index');
        Route::get('/accept-request/{id}', [DepositHistoryController::class, 'acceptRequest'])
            ->name('deposit-request.accept');
        Route::get('/reject-request/{id}', [DepositHistoryController::class, 'rejectRequest'])
            ->name('deposit-request.reject');
    });

    // Sub Admins
    Route::middleware('can:sub-admins')->prefix('/sub-admins')->group(function () {
        Route::get('/', [SubAdminController::class, 'index'])->name('sub-admins.index');
        Route::get('/create', [SubAdminController::class, 'create'])->name('sub-admins.create');
        Route::post('/store', [SubAdminController::class, 'store'])->name('sub-admins.store');
        Route::get('/edit/{id}', [SubAdminController::class, 'edit'])->name('sub-admins.edit');
        Route::post('/update/{id}', [SubAdminController::class, 'update'])->name('sub-admins.update');
        Route::get('/delete/{id}', [SubAdminController::class, 'delete'])->name('sub-admins.delete');
        Route::post('/toogle-block', [SubAdminController::class, 'toogleBlock'])
            ->name('sub-admins.toogle-blocked.change')->can('sub-admins.toogle-blocked.change');
        Route::get('/edit-permissions/{id}', [SubAdminController::class, 'editPermissions'])
            ->name('sub-admins.edit-permissions');
        Route::post('/update-permissions/{id}', [SubAdminController::class, 'updatePermissions'])
            ->name('sub-admins.update-permissions');
    });

    // Slider Images
    Route::middleware('can:slider-images')->prefix('/slider-images')->group(function () {
        Route::get('/', [SliderImageController::class, 'index']);
        Route::post('/', [SliderImageController::class, 'store'])->name('slider-images.store');
        Route::get('/{id}', [SliderImageController::class, 'destroy'])->name('slider-images.destroy');
    });

    // Chat
    Route::middleware('can:chats-view')->prefix('/chats')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('chats.index');
        Route::get('/get/{chat}', [ChatController::class, 'getChat']);
        Route::post('/read-message/{chat}', [ChatController::class, 'readMessage']);
        Route::get('/unread-chats-count', [ChatController::class, 'unreadChatsCount']);
    });
    Route::middleware('can:chats-send-message')->prefix('/chats')->group(function () {
        Route::post('/send-message/{chat}', [ChatController::class, 'sendMessage']);
    });
});
