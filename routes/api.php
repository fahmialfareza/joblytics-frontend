<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TopicAPIController;
use App\Http\Controllers\Api\TrendsAPIController;
use App\Http\Controllers\Api\ComparisonAPIController;
use App\Http\Controllers\Api\FutureJobAPIController;
use App\Http\Controllers\Api\JobDetailAPIController;

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

Route::prefix('topic')->group(function (){
    Route::get('/{topic}', [TopicAPIController::class, 'list'])->name('topic.list');
});

Route::prefix('trend')->group(function (){
    Route::get('/job', [TrendsAPIController::class, 'job'])->name('trend.job');
    Route::get('/skill', [TrendsAPIController::class, 'skill'])->name('trend.skill');
    Route::get('/industry', [TrendsAPIController::class, 'industry'])->name('trend.industry');
    Route::get('/bootcamp', [TrendsAPIController::class, 'bootcamp'])->name('trend.bootcamp');
});

Route::prefix('comparison')->group(function (){
    Route::get('/graduate', [ComparisonAPIController::class, 'graduate'])->name('comparison.graduate');
    Route::get('/bootcamp', [ComparisonAPIController::class, 'bootcamp'])->name('comparison.bootcamp');
});

Route::prefix('job-detail')->group(function (){
    Route::get('/city', [JobDetailAPIController::class, 'city'])->name('job-detail.city');
    Route::get('/job-posting', [JobDetailAPIController::class, 'jobPosting'])->name('job-detail.job-posting');
    Route::get('/experience', [JobDetailAPIController::class, 'experience'])->name('job-detail.experience');
    Route::get('/role', [JobDetailAPIController::class, 'role'])->name('job-detail.role');
    Route::get('/top-skill', [JobDetailAPIController::class, 'topSkill'])->name('job-detail.top-skill');
});

Route::prefix('future-job')->group(function (){
    Route::get('/list', [FutureJobAPIController::class, 'list'])->name('future-job.list');
});