<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IntroController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\DurationController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\UserRegisteredController;
use App\Http\Controllers\PaymentInstructionController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UnitStatusController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\NotificationController;

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

// Route::prefix('unit-status')->group(function (){
//     Route::get('/', [UnitStatusController::class, 'index'])->name('unit_status');
    // Route::get('/add', [UnitStatusController::class, 'add'])->name('admin.add');
    // Route::get('/edit/{user_id}', [UnitStatusController::class, 'edit'])->name('admin.edit');

    // Route::post('/create', [UnitStatusController::class, 'create'])->name('admin.create');
    // Route::post('/update', [UnitStatusController::class, 'update'])->name('admin.update');
    // Route::post('/delete', [UnitStatusController::class, 'delete'])->name('admin.delete');
// });

// Route::prefix('admin')->group(function (){
//     Route::get('/', [AdminController::class, 'index'])->name('admin');
//     Route::get('/add', [AdminController::class, 'add'])->name('admin.add');
//     Route::get('/edit/{user_id}', [AdminController::class, 'edit'])->name('admin.edit');

//     Route::post('/create', [AdminController::class, 'create'])->name('admin.create');
//     Route::post('/update', [AdminController::class, 'update'])->name('admin.update');
//     Route::post('/delete', [AdminController::class, 'delete'])->name('admin.delete');
// });

// Route::prefix('role')->group(function (){
//     Route::get('/', [RoleController::class, 'index'])->name('role');
//     Route::get('/add', [RoleController::class, 'add'])->name('role.add');
//     Route::get('/edit/{role_id}', [RoleController::class, 'edit'])->name('role.edit');

//     Route::post('/create', [RoleController::class, 'create'])->name('role.create');
//     Route::post('/update', [RoleController::class, 'update'])->name('role.update');
//     Route::post('/delete', [RoleController::class, 'delete'])->name('role.delete');
// });

// Route::prefix('location')->group(function (){
//     Route::get('/', [LocationController::class, 'index'])->name('location');
//     Route::get('/add', [LocationController::class, 'add'])->name('location.add');
//     Route::get('/edit/{place_id}', [LocationController::class, 'edit'])->name('location.edit');

//     Route::post('/create', [LocationController::class, 'create'])->name('location.create');
//     Route::post('/update', [LocationController::class, 'update'])->name('location.update');
//     Route::post('/delete', [LocationController::class, 'delete'])->name('location.delete');
// });

// Route::prefix('profile')->group(function (){
//     Route::get('/', [ProfileController::class, 'profile'])->name('profile');
// });

// Route::prefix('intro')->group(function (){
//     Route::get('/', [IntroController::class, 'index'])->name('intro');
//     // Route::get('/get', [IntroController::class, 'get'])->name('intro.get');
//     Route::get('/add', [IntroController::class, 'add'])->name('intro.add');
//     Route::get('/edit/{place_id}', [IntroController::class, 'edit'])->name('intro.edit');

//     Route::post('/create', [IntroController::class, 'create'])->name('intro.create');
//     Route::post('/update', [IntroController::class, 'update'])->name('intro.update');
//     Route::post('/delete', [IntroController::class, 'delete'])->name('intro.delete');
// });

// Route::prefix('product')->group(function (){
//     Route::get('/', [ProductController::class, 'index'])->name('product');
//     Route::get('/add', [ProductController::class, 'add'])->name('product.add');
//     Route::get('/edit/{product_id}', [ProductController::class, 'edit'])->name('product.edit');

//     Route::post('/create', [ProductController::class, 'create'])->name('product.create');
//     Route::post('/update', [ProductController::class, 'update'])->name('product.update');
//     Route::post('/delete', [ProductController::class, 'delete'])->name('product.delete');
// });

// Route::prefix('unit')->group(function (){
//     Route::get('/{place_id}/{room_id}', [UnitController::class, 'index'])->name('unit');
//     Route::get('/add/{place_id}/{room_id}', [UnitController::class, 'add'])->name('unit.add');
//     Route::get('/edit/{place_id}/{unit_id}', [UnitController::class, 'edit'])->name('unit.edit');

//     Route::post('/create', [UnitController::class, 'create'])->name('unit.create');
//     Route::post('/update', [UnitController::class, 'update'])->name('unit.update');
//     Route::post('/delete', [UnitController::class, 'delete'])->name('unit.delete');
// });

// Route::prefix('roomtype')->group(function (){
//     Route::get('/', [RoomTypeController::class, 'index'])->name('room_type');
//     Route::get('/add', [RoomTypeController::class, 'add'])->name('room_type.add');
//     Route::get('/edit/{room_type_id}', [RoomTypeController::class, 'edit'])->name('room_type.edit');

//     Route::post('/create', [RoomTypeController::class, 'create'])->name('room_type.create');
//     Route::post('/update', [RoomTypeController::class, 'update'])->name('room_type.update');
//     Route::post('/delete', [RoomTypeController::class, 'delete'])->name('room_type.delete');
// });

// Route::prefix('duration')->group(function (){
//     Route::get('/', [DurationController::class, 'index'])->name('duration');
//     Route::get('/add', [DurationController::class, 'add'])->name('duration.add');
//     Route::get('/edit/{duration_id}', [DurationController::class, 'edit'])->name('duration.edit');

//     Route::post('/create', [DurationController::class, 'create'])->name('duration.create');
//     Route::post('/update', [DurationController::class, 'update'])->name('duration.update');
//     Route::post('/delete', [DurationController::class, 'delete'])->name('duration.delete');
// });

// Route::prefix('facility')->group(function (){
//     Route::get('/', [FacilityController::class, 'index'])->name('facility');
//     Route::get('/add', [FacilityController::class, 'add'])->name('facility.add');
//     Route::get('/edit/{facility_id}', [FacilityController::class, 'edit'])->name('facility.edit');

//     Route::post('/create', [FacilityController::class, 'create'])->name('facility.create');
//     Route::post('/update', [FacilityController::class, 'update'])->name('facility.update');
//     Route::post('/delete', [FacilityController::class, 'delete'])->name('facility.delete');
// });

// Route::prefix('promo')->group(function (){
//     Route::get('/', [PromoController::class, 'index'])->name('promo');
//     Route::get('/add', [PromoController::class, 'add'])->name('promo.add');
//     Route::get('/edit/{promo_id}', [PromoController::class, 'edit'])->name('promo.edit');

//     Route::post('/create', [PromoController::class, 'create'])->name('promo.create');
//     Route::post('/update', [PromoController::class, 'update'])->name('promo.update');
//     Route::post('/delete', [PromoController::class, 'delete'])->name('promo.delete');
// });

// Route::prefix('information')->group(function (){
//     Route::get('/', [InformationController::class, 'index'])->name('information');
//     Route::get('/add', [InformationController::class, 'add'])->name('information.add');
//     Route::get('/edit/{blog_id}', [InformationController::class, 'edit'])->name('information.edit');

//     Route::post('/create', [InformationController::class, 'create'])->name('information.create');
//     Route::post('/update', [InformationController::class, 'update'])->name('information.update');
//     Route::post('/delete', [InformationController::class, 'delete'])->name('information.delete');
// });

// Route::prefix('booking')->group(function (){
//     Route::get('/', [OrderController::class, 'index'])->name('booking');
//     Route::post('/delete', [OrderController::class, 'delete'])->name('booking.delete');
//     Route::post('/export-pdf-and-send-mail-to-front-office', [OrderController::class, 'pdfReviewAndSendEmailToFrontOffice'])->name('booking.pdf_n_send_email');
// });

// Route::prefix('notification')->group(function (){
//     Route::get('/', [NotificationController::class, 'index'])->name('notification');
//     Route::get('/add', [NotificationController::class, 'add'])->name('notification.add');
//     Route::get('/edit/{blog_id}', [NotificationController::class, 'edit'])->name('notification.edit');
    
//     Route::post('/create', [NotificationController::class, 'create'])->name('notification.create');
//     Route::post('/update', [NotificationController::class, 'update'])->name('notification.update');
//     Route::post('/delete', [NotificationController::class, 'delete'])->name('notification.delete');
//     Route::post('/send', [NotificationController::class, 'send'])->name('notification.send');
// });

// Route::prefix('review')->group(function (){
//     Route::get('/', [ReviewController::class, 'index'])->name('review');
//     // Route::post('/print', [ReviewController::class, 'print'])->name('review.print');
//     Route::post('/export', [ReviewController::class, 'export'])->name('review.export');
//     Route::post('/delete', [ReviewController::class, 'delete'])->name('review.delete');
// });

// Route::prefix('history')->group(function (){
//     Route::get('/', [HistoryController::class, 'index'])->name('history');
//     Route::post('/delete', [HistoryController::class, 'delete'])->name('history.delete');
// });

// Route::prefix('user')->group(function (){
//     Route::get('/', [UserRegisteredController::class, 'index'])->name('user');

//     Route::post('/delete', [UserRegisteredController::class, 'delete'])->name('user.delete');
// });

Route::get('/qq', function (){
    dd(session()->all());
})->name('session.debug');

Route::get('/flush', function (){
    // session()->flush();
    session()->invalidate();
    dd(session()->all());
})->name('session.flush');

