<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Organization;
use App\Models\Social;
use App\Models\Contact;
use App\Models\Event;
use App\Models\CheckIn;
use App\Services\Xbot;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Cookie;


class WeixinController extends Controller
{
    // 如果绑定过，取出userID, 自动登录，否则 执行绑定过程
    public function bindOrlogin($socialUser, $type)
    {
        $loginedId = Auth::id();
        // $avatar = Str::replaceFirst('http://', 'https://', $socialUser->avatar);
        $avatar = $socialUser->avatar;
        if($loginedId){
            //用户已登录，执行绑定！
            $social = Social::firstOrCreate([
                'social_id' => $socialUser->id,
                'user_id'   => $loginedId,
                'type'      => $type,
                'name'      => $socialUser->nickname ?: $socialUser->name,
                'avatar'    => $avatar,
            ]);
        }else{ //未登录，执行登录！
            $social = Social::where('social_id', $socialUser->id)->first();
            if($social){ //已绑定，并定期更新头像
                if($social->updated_at->diffInDays(now()) > 1) {
                    // $social->name = $name;
                    $social->avatar = $avatar;
                    if($social->isDirty()){
                        $social->save();
                    }
                }
            }else { //微信首次授权登录
                $socialId = $socialUser->id;
                $email = $socialId.'@wx.com';
                $token = Str::random(10);
                $password = Hash::make(Str::random(8));
                $name = $socialUser->nickname ?: $socialUser->name;
                $user = User::where('email', $email)->first();
                if(!$user){
                    $user = User::create([
                        'name' => $name,
                        'email' => $email,
                        'email_verified_at' => now(),
                        'password' => $password,
                        'remember_token' => $token,
                    ]);
                }

                $social = Social::firstOrCreate([
                    'social_id' => $socialId,
                    'user_id'   => $user->id,
                    'type'      => $type,
                    'name'      => $name,
                    'avatar'    => $avatar,
                ]);
            }
            //执行登录！
            Auth::loginUsingId($social->user_id, true);//自动登入！
        }
        // 用户第一次 来源event
        if($social->wasRecentlyCreated){
            $intendedUrl = session('url.intended');
            if(Str::contains($intendedUrl,'/e/')){
                $hashid = basename($intendedUrl);
                $event = Event::findByHashid($hashid);
                $social->update(['event_id' => $event->id]);
            }
        }

        return Redirect::intended('dashboard');
    }


    public function xbotResponse(Request $request, Organization $organization)
    {
        // 验证消息
        if(!isset($request['msgid']) || $request['self'] == true)  return response()->json(null);

        $wxidOrCurrentRoom = $request['wxid'];
        $isRoom = Str::endsWith($wxidOrCurrentRoom, '@chatroom');
        // personal
        $wxid = $wxidOrCurrentRoom;
        $remark = Str::replace("\n", '', $request['remark']);
        if($isRoom){
             $wxid = $request['from'];
             $remark = $request['from_remark'];
        }
        // $cache = Cache::tags($wxid);

        $keyword = $request['content'];

        if(!$isRoom){
            if( strlen($keyword) === 6 //6位绑定验证码
                && preg_match("/\d{6}/", $keyword)){
                if( $caches = Cache::pull($keyword)){
                    Social::find($caches['social_id'])->update([
                        'wxid' => $wxid,
                        'nickname' => $remark,
                    ]);
                    // Log::error(__CLASS__, [$caches]);
                    unset($caches['social_id']);
                    $contact = Contact::firstOrCreate($caches);//compact('social_id','organization_id','user_id')
                    $content = "恭喜，绑定成功！";//点此更新联系人信息$contact->link
                    // $name = $organization->name;
                    // 欢迎加入xxx大家庭。
                    // 恭喜你绑定成功！TODO 点此更新联系人信息 $contact->link
                }else{
                    $content = "验证码已过期或无效，请重新获取！";//$contact->link
                }

                $data = [
                    'type' => 'text',
                    'to' => $wxid,
                    'data' => [
                        'content' => $content
                    ]
                ];
                // 随意找一个来调用默认bot发送 6/1
                if(!$organization) $organization = Organization::find(1);
                return $organization->wxNotify($data);
            }
        }

        // 个人或群签到

        
        // 

        if(in_array($keyword,['签到','打卡','已读','已看','已听','已完成'])){
            $checkIn = CheckIn::updateOrCreate(
                ['wxid'=>$wxid,'check_in_at'=>now()->startOfDay()],
                ['content'=>$keyword,'nickname'=>$remark]
            );
            $content = "🌟打卡成功\n👍@{$remark} 你太棒了\n👍卢牧师给你点赞👍👍👍\n✊本次微习惯挑战您已连续坚持了20天。\n您收获了99枚金币🏅\n成功率 99.91%";
            $data = [
                'type' => 'text',
                'to' => $wxid,
                'data' => [
                    'content' => $content
                ]
            ];
            // 随意找一个来调用默认bot发送 6/1
            if(!$organization) $organization = Organization::find(1);
            $organization->wxNotify($data);

            // 发到群里！
            if(!$isRoom){
                $content = "@{$remark} 今日已打卡，我们给他点个赞鼓励一下吧👍";
                 $data = [
                    'type' => 'text',
                    'to' => '38796149771@chatroom',
                    'data' => [
                        'content' => $content
                    ]
                ];
                // 随意找一个来调用默认bot发送 6/1
                if(!$organization) $organization = Organization::find(1);
                $organization->wxNotify($data);
            }
        }

        // // 查找或存储用户
        // $customer = Social::first(['wxid'=> $wxid]); // "wxid":"bluesky_still","remark":"AI天空蔚蓝"

        // // 更新用户的备注
        // if($customer->name !== $remark){
        //     $customer->name = $remark;
        //     // Saving A Single Model Without Events
        //     $customer->saveQuietly();
        // }


    }
    
}
