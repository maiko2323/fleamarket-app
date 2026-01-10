<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

use App\Http\Controllers\TopPageController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SellController;

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
// トップページ・商品詳細ページ
Route::get('/', [TopPageController::class, 'index'])->name('top');

Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');

// メール認証
Route::get('/email/verify', function () {
    return view('auth.verify');
})
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect()->route('mypage.profile.edit');
})
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', '認証メールを再送しました');
})
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

// マイページ
Route::get('/mypage', [UserController::class, 'showMypage'])
    ->middleware(['auth', 'verified'])
    ->name('mypage.show');

// プロフィール
Route::get('/mypage/profile', [UserController::class, 'edit'])
    ->middleware(['auth', 'verified'])
    ->name('mypage.profile.edit');

Route::post('/mypage/profile/update', [UserController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('mypage.profile.update');

// 出品ページ
Route::get('/sell', [SellController::class, 'create'])
    ->middleware('auth')
    ->name('sell.create');

Route::post('/item/store', [ItemController::class, 'store'])
    ->middleware('auth')
    ->name('item.store');

//いいね・コメント機能
Route::post('/items/{item}/like', [ItemController::class, 'like'])
    ->middleware('auth')
    ->name('items.like');

Route::post('/items/{item}/comments', [CommentController::class, 'store'])
    ->middleware('auth')
    ->name('comment.store');

// 購入フロー
Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])
    ->middleware('auth')
    ->name('purchase.show');

Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress'])
    ->middleware('auth')
    ->name('purchase.address');

Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])
    ->middleware('auth')
    ->name('purchase.address.update');

Route::post('/purchase/{item_id}/complete', [PurchaseController::class, 'complete'])
    ->middleware('auth')
    ->name('purchase.complete');