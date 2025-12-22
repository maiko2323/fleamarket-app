<?php

use App\Http\Controllers\TopPageController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SellController;


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;


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

// マイページ表示（購入/出品タブ切り替え）
Route::get('/mypage', [UserController::class, 'showMypage'])
    ->middleware(['auth', 'verified'])
    ->name('mypage.show');

// プロフィール編集
Route::get('/mypage/profile', [UserController::class, 'edit'])
    ->middleware(['auth', 'verified'])
    ->name('mypage.profile.edit');

// プロフィール更新
Route::post('/mypage/profile/update', [UserController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('mypage.profile.update');

// 認証誘導画面
Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

// 認証完了
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect()->route('mypage.profile.edit');
})->middleware(['auth', 'signed'])->name('verification.verify');

// 認証メール再送
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', '認証メールを再送しました');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


Route::get('/sell', [SellController::class, 'create'])->name('sell.create');

Route::post('/item/store', [ItemController::class, 'store'])->middleware('auth')->name('item.store');

Route::get('/item/create', [ItemController::class, 'create'])->name('item.create');

Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])
->middleware('auth')
->name('purchase.show');

Route::post('/items/{item}/comments', [CommentController::class, 'store'])->middleware('auth')->name('comment.store');

Route::post('/items/{item}/like', [ItemController::class, 'like'])->middleware('auth')->name('items.like');

Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress'])->name('purchase.address');

Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])->name('purchase.address.update');

Route::post('/purchase/{item_id}/complete', [PurchaseController::class, 'complete'])->name('purchase.complete');
