<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Service;
use App\Services\CheckInService;

class CheckInController extends Controller
{
    //$service 一定是周期性的，rrule为必选项
    public function serviceRedirectToEvent(Request $request, Service $service){
        // 不在微信里打开：直接去 app 下载页，不生成这一场 event
        if (CheckInService::shouldGoAppDownload($request)) {
            return redirect()->away(config('services.ybzx.download_url'));
        }
        $event = CheckInService::currentEvent($service);
        // 做一次跳转，为了纪录新人从哪里event来的。
        // TODO 统计
        return redirect()->route('event.checkin', [$event]);
    }
    public function eventCheck(Request $request, Event $event){
        return view('pages.check-in', CheckInService::check(auth()->user(), $event));
    }
}
