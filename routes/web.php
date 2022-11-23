<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeixinController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\EventEnrollController;

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
Route::get('/weui/success', function () {
    return view('weui.success');
});
Route::get('/weui/error', function () {
    return view('weui.warn');
});


// 'login.weixin' => name('login') 覆盖403登陆跳转。登陆成功，再跳转之前请求的页面
$isLocal = app()->environment('local');
$loginNameByEnv = $isLocal?'login.weixin':'login';
Route::get('/login/wechat', [WeixinController::class, 'weixin'])->name($loginNameByEnv);
Route::get('/login/wechat/callback', [WeixinController::class, 'weixinlogin'])->name('login.weixin.callback');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Route::resources([
    //     'organization'      => OrganizationController::class,
    //     'services'     => ServiceController::class,
    //     'events'        => EventController::class,
    //     // 'rrules' => 'RruleController', //except create!!! create from order
    // ]);


    // /services/{service}/check-in-out
    // /events/{event}/check-in-out
    // https://github.com/mtvs/eloquent-hashids
    Route::get('/s/{service:hashid}',  [CheckInController::class, 'serviceCheck'])->name('service.checkin');
    Route::get('/e/{event:hashid}',  [CheckInController::class, 'eventCheck'])->name('event.checkin');


    // 报名人数更新 /event_enrolls/{{$enrollId}}/update
    Route::get('/event_enrolls/{eventEnroll}/counts', [EventEnrollController::class, 'counts'])->name('event_enrolls.counts');
    // 取消报名？ /event_enrolls/{{$enrollId}}/cancel
    Route::get('/event_enrolls/{eventEnroll}/cancel', [EventEnrollController::class, 'cancel'])->name('event_enrolls.cancel');



});