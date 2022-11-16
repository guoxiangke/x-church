<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventEnroll;
use App\Models\Service;
use App\Models\Social;
use Cookie;

class CheckInController extends Controller
{
    //
    public function serviceCheck(Request $request, Service $service){
        $event = $service->events()->orderBy('created_at', 'desc')->firstOrFail();
        Cookie::queue('eventId', $event->id, 1); // 为了微信跳转
        return $this->check($event);
    }
    public function eventCheck(Request $request, Event $event){
        Cookie::queue('eventId', $event->id, 1); // 为了微信跳转
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
        // cookie 设置 所属组织id，用来在绑定页面找到organization
        // @see WeixinController->bindAI()
        $organizationId = $event->organization_id;
        Cookie::queue('organizationId', $organizationId, 30);

        
        $user = auth()->user();
        $userId = $user->id;
        $social = Social::where('user_id', $userId)->first();
        $socialId = $social?$social->id:'None';
        $organization = $event->organization->name_cn;
        $isBind = false; //TODO 是否绑定
        $data = compact('socialId','organization','user','isBind');


        $eventEnroll = EventEnroll::firstWhere([
            'user_id' => $userId,
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
                // 活动已结束，会后？小时，还有机会check out(已经报名/check-in的，需要check-out的
                $eventEnroll->checked_out_at = now();
                $eventEnroll->save();
                return view('check-in',array_merge($data,[
                    'success' => true,
                    'title' => '签出成功',
                    'message' => '记得下次早点check-out哦！'
                ]));
        }
        // 结束后，不可以check in'
        if(now() > $event->begin_at->addHours($event->duration_hours)){
            return view('check-in',array_merge($data,[
                'success' => false,
                'socialId' =>$socialId,
                'title' => '签到失败',
                'message' => '很抱歉，活动已结束，下次记得早点来哦！'
            ]));
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
                // 早于3个小时，为报名阶段，而不是check-in
                return view('check-in', array_merge($data,[
                    'success' => true,
                    'title' => '登记成功',
                    'message' => '感谢您报名参与，我们将会在开始前？小时提醒您现场扫码签到'
                ]));
            }

            // 3个小时开始 到结束前，都可以check-in（提前几小时可以签到？）
            if(!$eventEnroll->checked_in_at && $diffMinutes <= $event->check_in_ahead && now() <= $event->begin_at->addHours($event->duration_hours)){
                $eventEnroll->checked_in_at = now();
                $eventEnroll->save();
                // 3个小时开始 到结束前，都可以check-in
                return view('check-in', array_merge($data,[
                    'success' => true,
                    'title' => '签到成功1',
                    'message' => '很抱歉，活动已结束，下次记得早点来哦！'
                ]));
                // 必须是扫码（动态：防止作弊，需要每周打印？/静态，省事儿）
            }

            return view('check-in', array_merge($data,[
                'success' => false,
                'socialId' =>$socialId,
                'title' => 'Unknown Error',
                'message' => 'xxx?'
            ]));

        }else{
            // 说明已经报名过了！这次扫码是签到/签出

            // 3个小时开始 到结束前，都可以check-in（提前几小时可以签到？）
            if(!$eventEnroll->checked_in_at && $diffMinutes <= $event->check_in_ahead && now() <= $event->begin_at->addHours($event->duration_hours)){
                $eventEnroll->checked_in_at = now();
                $eventEnroll->save();
                // 3个小时开始 到结束前，都可以check-in
                return view('check-in', array_merge($data,[
                    'success' => true,
                    'title' => '签到成功2',
                    'message' => ''
                ]));
                // 必须是扫码（动态：防止作弊，需要每周打印？/静态，省事儿）
            }

            if($diffMinutes > $event->check_in_ahead){

                return view('check-in', array_merge($data,[
                    'success' => false,
                    'title' => '签到失败2',
                    'message' => '还没到签到时间，请在耐心等待，并在规定时间内签到'
                ]));
            }

            return view('check-in', array_merge($data,[
                'success' => true,
                'title' => '签到已成功',
                'message' => '无需重复签到扫码！'
            ]));
        }
    }
}
