<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::group(['middleware'=>'auth'], function(){
    Route::get('/email/verify',[AuthController::class,'verifyEmailMessage'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}',[AuthController::class,'verifyEmail'])->name('verification.verify');
    Route::get('logout', [AuthController::class,'logout'])->middleware('verified')->name('logout');
    Route::get('/home', [AuthController::class,'home'])->middleware(['verified'])->name('home');

    Route::resource('products',ProductController::class);

    
});


Route::group(['middleware'=>'guest'], function(){
    Route::get('/register', [AuthController::class,'register'])->name('register');
    Route::post('/register', [AuthController::class,'storeUser']);

    Route::get('/login', [AuthController::class,'login'])->name('login');
    Route::post('/login', [AuthController::class,'authenticate']);

});

