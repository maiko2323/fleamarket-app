<?php

use App\Http\Controllers\TopPageController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CommentController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


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
Route::get('/', [TopPageController::class, 'index'])->name('top');

Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', function () {
    Auth::logout();

    return redirect()->route('top');
})->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');

Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/mypage/profile', [UserController::class, 'edit'])->middleware('auth')->name('mypage.profile');

Route::post('/mypage/profile/update', [UserController::class, 'update'])
    ->middleware('auth')
    ->name('mypage.profile.update');

Route::get('/verify',  [AuthController::class, 'showVerify'])->name('verify');

Route::get('/mypage', [UserController::class, 'showMypage'])->middleware('auth')->name('mypage');

Route::get('/sell', function () {
    return view('sell.create');
});

Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])->name('purchase');

Route::post('/items/{item}/comments', [CommentController::class, 'store'])
    ->name('comment.store')
    ->middleware('auth');

Route::post('/items/{item}/like', [ItemController::class, 'like'])
    ->name('items.like')
    ->middleware('auth');

Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress'])
    ->name('purchase.address');

Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])
    ->name('purchase.address.update');

