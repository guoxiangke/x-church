<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeixinController;

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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // 1.必须是登陆的
    // 2.返回gh_xxx 给用户回复给AI机器人
    Route::get('/user/weixin/bind',  [WeixinController::class, 'bindAI'])->name('weixin.bind');
});

Route::get('/login/wechat', [WeixinController::class, 'weixin'])->name('login.weixin');
Route::get('/login/wechat/callback', [WeixinController::class, 'weixinlogin'])->name('login.weixin.callback');