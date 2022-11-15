<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventEnroll;
use App\Models\Service;

class CheckInController extends Controller
{
    //
    public function serviceCheck(Request $request, Service $service){
        $event = $service->events()->orderBy('created_at', 'desc')->firstOrFail();
        // dd($event->toArray());
        return $this->check($event);
    }
    public function eventCheck(Request $request, Event $event){
        return $this->check($event);
    }
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
        
    // Workflow:1.报名->2.check-in,3.check-uout
    // 其中1 和 3 可以省略。
    // 还可以外加一个 double-check
    protected function check(Event $event){
        $eventEnroll = EventEnroll::firstWhere([
            'user_id' => auth()->id(),
            'event_id' => $event->id,
        ]);
        // 结束后，不可以check in
        // 但，会后1小时，还有机会check out(已经报名/check-in的，需要check-out的)
        if($eventEnroll //必须有（报名/check-in）
            && $event->is_need_check_out 
            && !$eventEnroll->checked_out_at 
            && now() > $event->begin_at->addHours($event->duration_hours) //结束后才可以check out？
            && now() <= $event->begin_at->addHours($event->duration_hours+1)//结束后才可以check out
        ){
                $eventEnroll->checked_out_at = now();
                $eventEnroll->save();
                return ['迁出成功，记得下次早点check-out哦！','活动已结束，会后1小时，还有机会check out(已经报名/check-in的，需要check-out的',$eventEnroll->toArray()];
        }
        if(now() > $event->begin_at->addHours($event->duration_hours)){
            return ['很抱歉，活动已结束，下次记得早点来哦！','结束后，不可以check in',$event->toArray()];
        }

        if(!$eventEnroll){
            $eventEnroll = EventEnroll::create([
                'user_id' => auth()->id(),
                'event_id' => $event->id,
                'service_id' => $event->service->id??null,
                'enrolled_at' => now(),
            ]);
        }
        
        $diffMinutes = now()->diffInMinutes($event->begin_at,false);
        if($eventEnroll->wasRecentlyCreated){ //这里没有 check-out 签出的可能，因为是第一次扫码
            if($diffMinutes > $event->check_in_ahead ){
                return ['感谢您登记报名参加活动，我们将会在开始前3小时提醒您现场扫码签到check-in。','早于3个小时，为报名阶段，而不是check-in'];
            }

            // 3个小时开始 到结束前，都可以check-in（提前几小时可以签到？）
            if(!$eventEnroll->checked_in_at && $diffMinutes <= $event->check_in_ahead && now() <= $event->begin_at->addHours($event->duration_hours)){
                $eventEnroll->checked_in_at = now();
                $eventEnroll->save();
                return ['1谢谢，签到成功！', '3个小时开始 到结束前，都可以check-in',$eventEnroll->toArray()];
                // 必须是扫码（动态：防止作弊，需要每周打印？/静态，省事儿）
            }

            return ['Unknown 活动1已结束，记得下次早点来哦！',$eventEnroll->toArray()];

        }else{
            // 说明已经报名过了！这次扫码是签到/签出

            // 3个小时开始 到结束前，都可以check-in（提前几小时可以签到？）
            if(!$eventEnroll->checked_in_at && $diffMinutes <= $event->check_in_ahead && now() <= $event->begin_at->addHours($event->duration_hours)){
                $eventEnroll->checked_in_at = now();
                $eventEnroll->save();
                return ['2谢谢，签到成功！', '3个小时开始 到结束前，都可以check-in'];
                // 必须是扫码（动态：防止作弊，需要每周打印？/静态，省事儿）
            }

            if($diffMinutes > $event->check_in_ahead){
                return ['4.还没到签到时间，请在耐心等待，并在规定时间内签到'];
            }

            return ['3.谢谢，无需重复签到扫码！签到已成功'];
        }
    }
}
