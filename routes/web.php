<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OverviewController;

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
    return redirect()->route('job');
});

// Route::prefix('auth')->group(function(){
//     Route::get('/login', function () { return view('auth.login'); })->name('auth.login');
//     Route::post('/doLogin', [AuthController::class, 'doLogin'])->name('auth.submit.login');

//     Route::get('/logout', [AuthController::class, 'doLogout'])->name('auth.submit.logout');
// });

Route::get('/job', [DashboardController::class, 'index'])->name('job');
Route::get('/skill', [DashboardController::class, 'index'])->name('skill');
Route::get('/industry', [DashboardController::class, 'index'])->name('industry');
Route::get('/needs', [DashboardController::class, 'index'])->name('needs');

Route::prefix('overview')->group(function (){
    Route::get('/', [OverviewController::class, 'index'])->name('overview');
});

Route::get('/qq', function (){
    dd(session()->all());
})->name('session.debug');

Route::get('/flush', function (){
    // session()->flush();
    session()->invalidate();
    dd(session()->all());
})->name('session.flush');

