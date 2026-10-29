<?php

namespace App\Http\Middleware;

use App\Services\CheckInService;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            // 签到码 /e/ 不在微信里打开：去 app 下载页（/s/ 见 CheckInController）
            if ($request->routeIs('event.checkin') && CheckInService::shouldGoAppDownload($request)) {
                return config('services.ybzx.download_url');
            }
            return route('login');
        }
    }
}
