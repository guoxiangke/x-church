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
use App\Services\CheckInStatsService;
use App\Services\GlobalCheckInStatsService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Cookie;
use Carbon\Carbon;


class WeixinController extends Controller
{
    // 如果绑定过，取出userID, 自动登录，否则 执行绑定过程
    public function _to_delete_bindOrlogin($socialUser, $type)
    {
        $loginedId = Auth::id();
        // $avatar = Str::replaceFirst('http://', 'https://', $socialUser->avatar);
        $avatar = $socialUser->avatar;
        dd($loginedId, $socialUser);
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
        // 随意找一个来调用默认bot发送 6/1
        if(!$organization) $organization = Organization::find(1);

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
                return $organization->wxNotify($data);
            }
        }

        // 个人或群签到
        if($isRoom && in_array($keyword,['qd','Qd','qiandao','Qiandao','签到','簽到','dk','Dk','Daka','daka','打卡','已读','已看','已讀','已听','已聽','已完成','报名','報名','bm','Bm','baoming','Baoming'])){
            $wxRoom = $wxidOrCurrentRoom;
            $checkIn = CheckIn::updateOrCreate(
                [
                    'content' => $wxRoom,//在哪个群里打卡的？
                    'wxid' => $wxid,
                    'check_in_at' => now()->startOfDay()
                ],
                ['nickname' => $remark]
            );
            $service = new CheckInStatsService($wxid,$wxRoom);
            $stats = $service->getStats();

            $encourages = [
                "太棒了🌟",
                "做的好👏👏",
                "耶✌️✌️✌️",
                "给身边的人击掌一下吧🙌",
                "给自己一个微笑吧😊",
                "得意的笑一个吧✌️",
                "给自己一个赞吧👍",
                "庆祝🪅一下吧🤩",
                "大声对自己说：我赢了🥇",
                "给自己说一句鼓励的话吧🥳",
                "做一件对自己好的事情吧✅",
                "有没有感觉自己在发光[太阳]",
                "大声对自己说：_ _ _ _，今天又是美好的一天✌️",
                "哼一首你喜欢的乐观向上的歌曲吧🥳",
                "跳跳舞💃，拍拍手🙌，点点头，给自己点赞",
                "想象一群人在欢呼庆祝",
                "在心里对自己说，干得不错👍",
                "深呼吸，打响指✌️",
                "想象看见烟花在绽放，向上看，做出✌️手势",
                "得意的笑，告诉自己，我做到了✌️",
                "[庆祝][庆祝][庆祝]",
                "[爆竹][爆竹][爆竹]",
                "[烟花][烟花][烟花]",
            ];
            $randomEncourage = $encourages[array_rand($encourages)];

            // 'qd','Qd','签到','簽到','dk','Dk','打卡','已读','已看','已讀','已听','已聽','已完成','报名','報名','bm','Bm',
            switch ($keyword) {
                case '签到':
                case 'qd':
                case 'Qd':
                case 'Qiandao':
                case 'qiandao':
                case '簽到':
                    $first = "✅签到成功";
                    break;
                case '打卡':
                case 'daka':
                case 'Daka':
                case 'dk':
                case 'Dk':
                    $first = "✅打卡成功";
                    break;
                case '报名':
                case 'bm':
                case 'Bm':
                case 'baoming':
                case 'Baomming':
                case '報名':
                    $first = "✅报名成功";
                    break;
                default:
                    $first = "✅挑战成功";
                    break;
            }
            $content = "{$first}\n✊您已连续坚持了 {$stats['current_streak']} 天\n🏅您总共攒了 {$stats['total_days']} 枚🌟\n您是今天第 {$stats['rank']} 个签到的🥇\n给你一个大大的赞👍\n{$randomEncourage}";
            // $content = "✅挑战成功\n[强]我们一起祝贺 @{$remark}";
            $data = [
                'type' => 'text',
                'to' => $wxRoom,// 发到群里！
                'data' => [
                    'content' => $content
                ]
            ];
            
            if($checkIn->wasRecentlyCreated){
                // 先发给个人，再发到群里！
                $data['to'] = $wxid;
                $organization->wxNotify($data);

                $data['to'] = $wxRoom;
                $data['data']['content'] = "{$first}\n🥇今天您是第 {$stats['rank']} 位挑战者";
                $organization->wxNotify($data);
            }else{
                // 重复打卡时
                $data['data']['content'] = "✅再次祝贺你！今日您已经挑战过了！";
                $organization->wxNotify($data);
            }
        }

        if($isRoom && $keyword=='打卡排行'){
            $wxRoom = $wxidOrCurrentRoom;
            $service = new GlobalCheckInStatsService($wxRoom);
            // 获取总打卡天数排行榜
            $totalRanking = $service->getTotalDaysRanking(10);
            // 获取当前连续打卡天数排行榜  
            $streakRanking = $service->getCurrentStreakRanking(10);
            // 构建总打卡天数排行榜文本
            $textTotalRanking = "📊 总打卡天数排行榜 TOP10\n";
            $textTotalRanking .= "━━━━━━━━━━━━━━━━━━━━━━\n";
            
            if (empty($totalRanking)) {
                $textTotalRanking .= "暂无打卡记录\n";
            } else {
                foreach ($totalRanking as $user) {
                    $rankIcon = $this->getRankIcon($user['rank']);
                    $textTotalRanking .= sprintf(
                        "%s %s %s (%d天)\n", 
                        $rankIcon, 
                        $user['rank'], 
                        $user['nickname'], 
                        $user['total_days']
                    );
                }
            }
            
            // 构建连续打卡天数排行榜文本
            $textStreakRanking = "\n🔥 连续打卡天数排行榜 TOP10\n";
            $textStreakRanking .= "━━━━━━━━━━━━━━━━━━━━━━\n";
            
            if (empty($streakRanking)) {
                $textStreakRanking .= "暂无连续打卡记录\n";
            } else {
                foreach ($streakRanking as $user) {
                    $rankIcon = $this->getRankIcon($user['rank']);
                    $streakText = $user['current_streak'] == 1 ? "1天" : "{$user['current_streak']}天连击";
                    $textStreakRanking .= sprintf(
                        "%s %s %s (%s)\n", 
                        $rankIcon, 
                        $user['rank'], 
                        $user['nickname'], 
                        $streakText
                    );
                }
            }
            
            // 合并两个排行榜
            $finalText = $textTotalRanking . $textStreakRanking;
            
            // 添加底部提示
            $finalText .= "\n💡 发送「我的打卡」查看个人统计";
            
            // 发送排行榜消息
            $data = [
                'type' => 'text',
                'to' => $wxRoom,// 发到群里！
                'data' => [
                    'content' => $finalText
                ]
            ];
            $organization->wxNotify($data);

            // return $finalText; 或者直接发送消息
        }

        if($isRoom && $keyword=='我的打卡'){
            $wxRoom = $wxidOrCurrentRoom;
            $service = new CheckInStatsService($wxid, $wxRoom);
            $stats = $service->getStats();
            
            // 如果没有打卡记录
            if ($stats['total_days'] == 0) {
                $text = "📝 您的打卡统计\n";
                $text .= "━━━━━━━━━━━━━━━━━━━━━━\n";
                $text .= "还没有打卡记录哦～\n";
                $text .= "发送「打卡」开始您的第一次打卡吧！";
                // return $text;
            } else {
                // 构建个人统计文本
                $text = "📝 您的打卡统计\n";
                $text .= "━━━━━━━━━━━━━━━━━━━━━━\n";
                
                // 基本统计
                $text .= sprintf("📅 总打卡天数：%d天\n", $stats['total_days']);
                $text .= sprintf("🔥 当前连续：%d天\n", $stats['current_streak']);
                $text .= sprintf("🏆 最高连击：%d天\n", $stats['max_streak']);
                
                // 今日排名
                if ($stats['rank'] > 0) {
                    $text .= sprintf("⏰ 今日第%d个打卡\n", $stats['rank']);
                }
                
                // 缺勤统计
                if ($stats['missed_days'] > 0) {
                    $text .= sprintf("😴 缺勤天数：%d天 (%.1f%%)\n", 
                        $stats['missed_days'], 
                        floatval($stats['missed_percentage'])
                    );
                } else {
                    $text .= "😴 缺勤天数：0天 (全勤！)\n";
                }
                
                // 打卡状态评语
                $text .= "\n" . $this->getStatusComment($stats) . "\n";
                
                // 显示最近缺勤日期（如果有且不超过5个）
                if (!empty($stats['missed_dates']) && count($stats['missed_dates']) <= 5) {
                    $text .= "\n📋 缺勤日期：\n";
                    foreach ($stats['missed_dates'] as $missedDate) {
                        $text .= "• " . Carbon::parse($missedDate)->format('m月d日') . "\n";
                    }
                } elseif (count($stats['missed_dates']) > 5) {
                    $text .= sprintf("\n📋 共缺勤%d天（最近5次）：\n", count($stats['missed_dates']));
                    $recentMissed = array_slice($stats['missed_dates'], -5);
                    foreach ($recentMissed as $missedDate) {
                        $text .= "• " . Carbon::parse($missedDate)->format('m月d日') . "\n";
                    }
                }
                
                // 底部提示
                $text .= "\n💡 发送「打卡排行」查看群组排名";
            }
            
            // 发送个人统计消息
            $data = [
                'type' => 'text',
                'to' => $wxRoom,// 发到群里！
                'data' => [
                    'content' => '📅 统计已单独发您微信。'
                ]
            ];
            $organization->wxNotify($data);
            
            // 先发给个人，再发到群里！
            $data['to'] = $wxid;
            $data['data']['content'] = $text;
            $organization->wxNotify($data);
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
    
    // 辅助方法：根据统计数据生成状态评语
    private function getStatusComment($stats) {
        $currentStreak = $stats['current_streak'];
        $maxStreak = $stats['max_streak'];
        $missedPercentage = floatval($stats['missed_percentage']);
        
        // 连续天数评语
        if ($currentStreak >= 30) {
            $streakComment = "🌟 坚持王者！连续打卡超过30天！";
        } elseif ($currentStreak >= 14) {
            $streakComment = "🚀 习惯养成中！连续打卡超过2周！";
        } elseif ($currentStreak >= 7) {
            $streakComment = "📈 状态不错！连续打卡1周了！";
        } elseif ($currentStreak >= 3) {
            $streakComment = "💪 继续加油！保持连续打卡！";
        } elseif ($currentStreak >= 1) {
            $streakComment = "🌱 刚刚开始，加油坚持！";
        } else {
            $streakComment = "😴 今天还没打卡哦~";
        }
        
        // 出勤率评语
        if ($missedPercentage == 0) {
            $attendanceComment = "完美全勤！";
        } elseif ($missedPercentage <= 10) {
            $attendanceComment = "出勤率很棒！";
        } elseif ($missedPercentage <= 20) {
            $attendanceComment = "出勤率良好~";
        } elseif ($missedPercentage <= 30) {
            $attendanceComment = "还有提升空间哦~";
        } else {
            $attendanceComment = "要更加努力坚持打卡！";
        }
        
        return $streakComment . " " . $attendanceComment;
    }

    // 辅助方法：获取排名图标
    private function getRankIcon($rank) {
        switch ($rank) {
            case 1:
                return '🥇';
            case 2:
                return '🥈';
            case 3:
                return '🥉';
            default:
                return '🏅';
        }
    }
}
