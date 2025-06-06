<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Event;
use App\Models\EventEnroll;
use App\Models\Service;
use App\Models\Social;
use App\Services\Rrule;
use Cookie;

class CheckInController extends Controller
{
    //$service 一定是周期性的，rrule为必选项
    public function serviceRedirectToEvent(Request $request, Service $service){
        $event = $service->events()->orderBy('created_at', 'desc')->first();

        $isNeedCreate = false;
        // 如果周期性的event没有，则创建
        if(!$event) $isNeedCreate = true;
        // 如果活动已结束，且不是当天，则创建
        if($event && $event->isEnd() && !$event->isToday()){
            $isNeedCreate = true;
        }
        if($isNeedCreate){
            $data = $service->toArray();
            $service_id = $service->id;

            $rule = new Rrule($service->begin_at, $service->rrule, auth()->user()->timezone??config('app.timezone'));
            $begin_at = $rule->getNextEventDate(); // 从rrule计算下次活动开始时间和日期
            $name = $service->name . ' - '.$begin_at->format('ymd');
            $rrule = null;//周期性创建的1次性活动
            $event = Event::create(array_merge($data, compact('rrule','name','service_id','begin_at')));
        }
        // 做一次跳转，为了纪录新人从哪里event来的。
        // TODO 统计
        return redirect()->route('event.checkin', [$event]);
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
        $checkInPage = 'pages.check-in';
        $organization_id = $event->organization_id;

        // 随机生成6位数字，30s内过期，以便绑定 user1:social1
        $user = auth()->user();
        $user_id = $user->id;
        $social = Social::where('user_id', $user_id)->first();
        if($user_id == 1){
            // TODO 是否绑定
            $social_id = $social?$social->id:null;
            $isBind = 1;
        }else{
            $social = Social::where('user_id', $user_id)->firstOrFail();
            $social_id = $social->id;
            $isBind = $social->wxid;
        }
        $code6 = '123456';
        if(!$isBind) {
            $code6 = (int) (random_int(1, 3) . substr(now()->valueOf(), -5)) - $user_id%100;
            Cache::put($code6, compact('social_id','organization_id','user_id'), 180);
        }
        $organization = $event->organization;
        $isBind = true;
        $data = compact(
            'event',
            'organization',
            'code6',
            'isBind',
            'social',//?
        );

        $eventEnroll = EventEnroll::firstWhere([
            'user_id' => $user_id,
            'event_id' => $event->id,
        ]);
        // 取消报名的情况
        if($eventEnroll&&$eventEnroll->canceled_at){
            //您已取消报名，不可以再次报名！
            return view($checkInPage,array_merge($data,[
                'success' => false,
                'status' => 7,
                'title' => '失败',
                'eventEnroll' => $eventEnroll,
                'message' => '您已取消报名，不可以再次报名'
            ]));
        }

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
                return view($checkInPage,array_merge($data,[
                    'success' => true,
                    'status' => 0,
                    'title' => '签出成功',
                    'message' => '下次记得早点check-out哦！'
                ]));
        }
        // 结束后，不可以check in'
        if(now() > $event->begin_at->addHours($event->duration_hours)){
            return view($checkInPage,array_merge($data,[
                'success' => false,
                'status' => 1,
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
        
        $eventEnrolls = EventEnroll::with('user')
            ->where(['event_id' => $event->id])
            ->orderBy('updated_at','desc')->get();
        $data['eventEnrolls'] = $eventEnrolls;
        $diffMinutes = now()->diffInMinutes($event->begin_at,false);
        if($eventEnroll->wasRecentlyCreated){ //这里没有 check-out 签出的可能，因为是第一次扫码
            if($diffMinutes > $event->check_in_ahead ){
                // 早于3个小时，为报名阶段，而不是check-in
                return view($checkInPage, array_merge($data,[
                    'success' => true,
                    'title' => '报名成功',
                    'status' => 2,
                    'eventEnroll' => $eventEnroll,
                    'message' => "",
                    //感谢参与，我们将会在开始前{$event->check_in_ahead}分钟提醒您现场扫码签到?几个人TODO
                ]));
            }

            // 3个小时开始 到结束前，都可以check-in（提前几小时可以签到？）
            if(!$eventEnroll->checked_in_at && $diffMinutes <= $event->check_in_ahead && now() <= $event->begin_at->addHours($event->duration_hours)){
                $eventEnroll->checked_in_at = now();
                $eventEnroll->save();
                // 3个小时开始 到结束前，都可以check-in
                return view($checkInPage, array_merge($data,[
                    'eventEnroll' => $eventEnroll,
                    'success' => true,
                    'status' => 3,
                    'title' => '签到成功',
                    'message' => '',//TODO 是否show报名人数和最后/最新头像
                ]));
                // 必须是扫码（动态：防止作弊，需要每周打印？/静态，省事儿）
            }

            return view($checkInPage, array_merge($data,[
                'success' => false,
                'status' => 4,
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
                return view($checkInPage, array_merge($data,[
                    'success' => true,
                    'status' => 5,
                    'title' => '签到成功',
                    'eventEnroll' => $eventEnroll,
                    'message' => ''
                ]));
                // 必须是扫码（动态：防止作弊，需要每周打印？/静态，省事儿）
            }

            if($diffMinutes > $event->check_in_ahead){

                return view($checkInPage, array_merge($data,[
                    'success' => false,
                    'status' => 6,
                    'title' => '签到未开放',
                    'eventEnroll' => $eventEnroll,
                    'message' => ""//请于开始前{$event->check_in_ahead}分钟签到
                ]));
            }

            return view($checkInPage, array_merge($data,[
                'success' => true,
                'status' => 7,
                'eventEnroll' => $eventEnroll,
                'title' => '无需重复签到!',
                'message' => ''
            ]));
        }
    }
}
