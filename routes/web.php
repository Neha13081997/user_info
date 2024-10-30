<?php

use App\Http\Controllers\Authenticate\ForgotPasswordController;
use App\Http\Controllers\Authenticate\LoginController;
use App\Http\Controllers\Authenticate\RegisterController;
use App\Http\Controllers\Backend\ProfileController;
// use App\Http\Controllers\Backend\DashboardController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//      return view('auth.login');
//     dd(auth()->user());
// });

// Auth::routes();

Route::get('/', function(){
    dd('User Home Page');
})->name('home');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('user.login');
Route::post('/login',[LoginController::class,'login'])->name('login');

Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');

// ForgotPasswordController
Route::get('/forgot-password',[ForgotPasswordController::class,'showForgotPasswordForm'])->name('password.request'); 
Route::post('forgot-password',[ForgotPasswordController::class,'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}',[ForgotPasswordController::class,'showResetPasswordForm'])->name('resetPassword');
Route::post('/reset-password',[ForgotPasswordController::class,'resetPassword'])->name('password.update');

// RegisterController
Route::get('/register',[RegisterController::class,'showRegisterForm'])->name('user.register'); 
Route::post('/register',[RegisterController::class,'registerUser'])->name('register');

Route::middleware(['auth', 'preventBackHistory', 'userinactive', 'role:' . implode(',', [config('constant.roles.admin'), config('constant.roles.staff')])])->group(function () {
    Route::group(['as' => 'admin.', 'prefix' => 'admin','namespace' => 'App\Http\Controllers\Backend'], function () {
        Route::resource('/dashboard',DashboardController::class);
        Route::post('/logout',[LoginController::class,'logout'])->name('logout');

        Route::get('/profile', [ProfileController::class,'showProfile'])->name('show.profile');
        Route::post('/profile', [ProfileController::class,'updateProfile'])->name('update.profile');

        Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('change.password');
    });
});