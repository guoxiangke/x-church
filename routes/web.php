<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeixinController;
use App\Http\Controllers\ChurchController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CheckInController;

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

    Route::resources([
        'churches'      => ChurchController::class,
        'services'     => ServiceController::class,
        'events'        => EventController::class,
        // 'rrules' => 'RruleController', //except create!!! create from order
    ]);


    // 1.报名 
        // 感谢您登记报名参加xx活动，我们将会在开始前2小时提醒您。
    // 2.check-in 提前2小时，
        // 您报名参加的活动，还有2个小时即将开始，请到场后再次扫码check-in
    // 3.after begin-time checkout
        // no need checkout this event，谢谢！
    // 新人登记
        //1.新人扫码,set cookie churchId & this url，跳转登陆
        //2.扫码成功，跳转到 this url 继续创建一个 event_enrolls
        //3.如果没bind AI微信，则
    // && Sunday check-in/out
        //2.扫码成功，跳转到 this url 继续创建一个 event_enrolls
        //3.如果是第三次扫码，则 checkout
    Route::get('/services/{service}/check-in-out',  [CheckInController::class, 'serviceCheck'])->name('service.checkin');
    Route::get('/events/{event}/check-in-out',  [CheckInController::class, 'eventCheck'])->name('event.checkin');
});

// 'login.weixin' => name('login') 覆盖403登陆跳转。登陆成功，再跳转之前请求的页面
$isLocal = app()->environment('local');
$loginNameByEnv = $isLocal?'login.weixin':'login';
Route::get('/login/wechat', [WeixinController::class, 'weixin'])->name($loginNameByEnv);
Route::get('/login/wechat/callback', [WeixinController::class, 'weixinlogin'])->name('login.weixin.callback');