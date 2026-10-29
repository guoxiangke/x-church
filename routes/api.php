<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeixinController;
use App\Http\Controllers\AppCheckInController;

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
// auth:sanctum
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/webhook/xbot/{organization?}', [WeixinController::class, 'xbotResponse']);
// 永不止息 app 扫码签到（票据鉴权，见 App\Services\YbzxTicket）
Route::post('/app/check-in', [AppCheckInController::class, 'checkIn'])->middleware('throttle:30,1');
