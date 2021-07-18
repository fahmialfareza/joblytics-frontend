<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\IntroAPIController;
use App\Http\Controllers\Api\ProductAPIController;
use App\Http\Controllers\Api\FacilityAPIController;
use App\Http\Controllers\Api\PromoAPIController;
use App\Http\Controllers\Api\LocationAPIController;
use App\Http\Controllers\Api\InformationAPIController;
use App\Http\Controllers\Api\UserRegisteredAPIController;
use App\Http\Controllers\Api\OrderAPIController;
use App\Http\Controllers\Api\ReviewAPIController;
use App\Http\Controllers\Api\UnitAPIController;
use App\Http\Controllers\Api\UnitStatusAPIController;
use App\Http\Controllers\Api\DashboardAPIController;
use App\Http\Controllers\Api\HistoryAPIController;
use App\Http\Controllers\Api\NotificationAPIController;
use App\Http\Controllers\Api\TokenAPIController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('dashboard')->group(function (){
    Route::get('/statistik', [DashboardAPIController::class, 'statistik'])->name('dashboard.statistik');
    Route::get('/booking', [DashboardAPIController::class, 'booking'])->name('dashboard.booking');
    Route::get('/booked', [DashboardAPIController::class, 'booked'])->name('dashboard.booked');
    Route::get('/revenue', [DashboardAPIController::class, 'revenue'])->name('dashboard.revenue');
    Route::get('/room_sold', [DashboardAPIController::class, 'room_sold'])->name('dashboard.room_sold');
});

Route::prefix('intro')->group(function (){
    Route::get('/get', [IntroAPIController::class, 'get'])->name('intro.get');
    Route::get('/switch/{introId}/{status}', [IntroAPIController::class, 'switch'])->name('intro.switch');
});

Route::prefix('facility')->group(function (){
    Route::get('/get', [FacilityAPIController::class, 'get'])->name('facility.get');
    Route::get('/switch/{facilityId}/{status}', [FacilityAPIController::class, 'switch'])->name('intro.switch');
});

Route::prefix('product')->group(function (){
    Route::get('/get', [ProductAPIController::class, 'get'])->name('product.get');
    Route::get('/switch/{roomId}/{status}', [ProductAPIController::class, 'switch'])->name('product.switch');
});

Route::prefix('location')->group(function (){
    Route::get('/get', [LocationAPIController::class, 'get'])->name('location.get');
    Route::get('/switch/{roomId}/{status}', [LocationAPIController::class, 'switch'])->name('location.switch');
});

Route::prefix('promo')->group(function (){
    Route::get('/get', [PromoAPIController::class, 'get'])->name('promo.get');
    Route::get('/switch/{voucherNo}/{status}', [PromoAPIController::class, 'switch'])->name('promo.switch');
});

Route::prefix('information')->group(function (){
    Route::get('/get', [InformationAPIController::class, 'get'])->name('information.get');
    Route::get('/switch/{blogId}/{status}', [InformationAPIController::class, 'switch'])->name('information.switch');
});

Route::prefix('unit')->group(function (){
    Route::get('/get/{roomId}', [UnitAPIController::class, 'get'])->name('unit.get');
    Route::get('/switch/{unitId}/{status}', [UnitAPIController::class, 'switch'])->name('unit.switch');
    Route::get('/switch/status/{unitId}/{statusId}', [UnitAPIController::class, 'switchStatus'])->name('unit.status');
    Route::get('/update/status/{unitId}', [UnitAPIController::class, 'updateUnit'])->name('unit.update');
});

Route::prefix('unit_status')->group(function (){
    Route::get('/get', [UnitStatusAPIController::class, 'get'])->name('unit_status.get');
    // Route::get('/switch/{unitId}/{status}', [UnitStatusAPIController::class, 'switch'])->name('unit_status.switch');
    Route::get('/switch/status/{unitId}/{status}', [UnitStatusAPIController::class, 'switchStatus'])->name('unit_status.status');
    // Route::get('/update/status/{unitId}', [UnitStatusAPIController::class, 'updateUnit'])->name('unit_status.update');
});

Route::prefix('booking')->group(function (){
    Route::get('/get', [OrderAPIController::class, 'get'])->name('booking.get');
    // Route::get('/switch/{roomId}/{status}', [OrderAPIController::class, 'switch'])->name('booking.switch');
});

Route::prefix('notification')->group(function (){
    Route::get('/get', [NotificationAPIController::class, 'get'])->name('notification.get');
});

Route::prefix('review')->group(function (){
    Route::get('/get', [ReviewAPIController::class, 'get'])->name('review.get');
    // Route::get('/print', [ReviewAPIController::class, 'print'])->name('review.print');
});

Route::prefix('history')->group(function (){
    Route::get('/get', [HistoryAPIController::class, 'get'])->name('history.get');
});

Route::prefix('token')->group(function (){
    Route::get('/login-api', [TokenAPIController::class, 'loginAPI'])->name('token.login');
});

Route::prefix('user')->group(function (){
    Route::get('/get', [UserRegisteredAPIController::class, 'get'])->name('user.get');
    Route::get('/detail/{userId}', [UserRegisteredAPIController::class, 'detail'])->name('user.detail');
    Route::get('/switch/{blogId}/{status}', [UserRegisteredAPIController::class, 'switch'])->name('user.switch');
    Route::get('/reject-ktp/{userId}/{ktpId}', [UserRegisteredAPIController::class, 'rejectKTP'])->name('user.reject_ktp');
});
