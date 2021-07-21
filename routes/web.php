<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\TrendsController;
use App\Http\Controllers\ComparisonController;
use App\Http\Controllers\FutureJobController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return redirect()->route('overview');
});

Route::prefix('overview')->group(function (){
    Route::get('/', [OverviewController::class, 'index'])->name('overview');
});

Route::prefix('trends')->group(function (){
    Route::get('/', [TrendsController::class, 'index'])->name('trend');
});

Route::prefix('comparison')->group(function (){
    Route::get('/', [ComparisonController::class, 'index'])->name('trend');
});

Route::prefix('job-detail')->group(function (){
    Route::get('/', [JobDetailController::class, 'index'])->name('job-detail');
});

Route::prefix('comparison')->group(function (){
    Route::get('/', [ComparisonController::class, 'index'])->name('comparison');
});

Route::prefix('future-job')->group(function (){
    Route::get('/', [FutureJobController::class, 'index'])->name('future-job');
});

Route::get('/qq', function (){
    dd(session()->all());
})->name('session.debug');

Route::get('/flush', function (){
    // session()->flush();
    session()->invalidate();
    dd(session()->all());
})->name('session.flush');

