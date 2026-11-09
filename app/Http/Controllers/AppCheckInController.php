<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Event;
use App\Models\EventEnroll;
use App\Models\Service;
use App\Models\Social;
use App\Models\User;
use App\Services\CheckInService;
use App\Services\YbzxTicket;
use App\Services\YbzxUser;

// 永不止息 app 扫码签到：app 扫到 /e/{hashid} 或 /s/{hashid}，带着 worker 签发的票据来签到
class AppCheckInController extends Controller
{
    public function checkIn(Request $request)
    {
        $request->validate([
            'ticket' => 'required|string|max:4096',
            'url' => 'required|string|max:2048',
        ]);

        $claims = YbzxTicket::verify($request->input('ticket'));
        if (!$claims) {
            return response()->json(['error' => 'invalid_ticket', 'message' => '身份校验失败，请重试'], 401);
        }

        $event = $this->resolveEvent($request, $request->input('url'));
        if (!$event) {
            return response()->json(['error' => 'invalid_qrcode', 'message' => '不是有效的签到二维码'], 404);
        }

        $user = $this->resolveUser($claims);
        $result = CheckInService::check($user, $event);
        $eventEnroll = $result['eventEnroll'] ?? EventEnroll::firstWhere([
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);

        return response()->json([
            'success' => $result['success'],
            'status' => $result['status'],
            'title' => $result['title'],
            'message' => $result['message'],
            'event' => [
                'name' => $event->name,
                'begin_at' => $event->begin_at?->toIso8601String(),
                'duration_hours' => $event->duration_hours,
                'address' => $event->address,
            ],
            'organization' => [
                'name' => $event->organization?->name,
            ],
            'enroll' => $eventEnroll ? [
                'enrolled_at' => $eventEnroll->enrolled_at,
                'checked_in_at' => $eventEnroll->checked_in_at,
                'checked_out_at' => $eventEnroll->checked_out_at,
            ] : null,
            'page' => $this->page($event, $result),
        ]);
    }

    // app 结果页照 pages.check-in 显示：哪些行显示、文字怎么写都和网页一致，时间在这里格式化好
    protected function page(Event $event, array $result): array
    {
        $organization = $event->organization;
        $enrolls = null;
        $schedule = [];
        if (!in_array($result['status'], [3, 7, 0])) {
            $schedule = [
                ['label' => '活动时间', 'value' => $event->begin_at->format("M j H:i").' ～ '.$event->begin_at->copy()->addHours($event->duration_hours)->format("M j H:i")],
                ['label' => '活动时长', 'value' => $event->duration_hours.'小时'],
                ['label' => '签到时间', 'value' => '开始前'.$event->check_in_ahead.'分钟'],
                ['label' => '当前时间', 'value' => now()->format("M j H:i Y D")],
            ];
            if ($event->is_show_avatar && isset($result['eventEnrolls'])) {
                $list = $result['eventEnrolls'];
                $enrolls = [
                    'count' => $list->sum('count_adult') + $list->sum('count_child'),
                    'avatars' => $list->take(100)->map(fn ($eer) => $eer->user?->profile_photo_url)->filter()->values(),
                ];
            }
        }

        return [
            'title' => $event->name.'|'.$organization?->system_name,
            'summary' => $result['success'] ? [
                ['label' => '名称', 'value' => $event->name],
                ['label' => '简介', 'value' => (string) $event->description],
            ] : [],
            'schedule' => $schedule,
            'enrolls' => $enrolls,
            'map_url' => $schedule ? 'http://maps.google.com/maps?q='.urlencode((string) $event->address) : null,
            'footer_title' => ($organization?->system_name ?? $organization?->name_abbr).'活动签到管理系统',
            'footer_text' => 'Copyright © 2022 '.($organization?->website_url ?? 'yilindeli.com'),
        ];
    }

    // 只认本站的 /e/{hashid}、/s/{hashid}
    protected function resolveEvent(Request $request, string $url): ?Event
    {
        $parts = parse_url($url);
        $host = strtolower($parts['host'] ?? '');
        $hosts = array_filter([strtolower($request->getHost()), strtolower((string) parse_url(config('app.url'), PHP_URL_HOST))]);
        if (!in_array($host, $hosts, true)) return null;
        if (!preg_match('#^/(e|s)/([A-Za-z0-9]+)/?$#', $parts['path'] ?? '', $m)) return null;

        if ($m[1] === 'e') {
            return Event::findByHashid($m[2]);
        }
        $service = Service::findByHashid($m[2]);
        return $service ? CheckInService::currentEvent($service) : null;
    }

    // 按 app 的 uid 找本地用户，没有就建一个（和 /sso 共用，见 YbzxUser）
    protected function resolveUser(array $claims): User
    {
        return YbzxUser::resolve($claims);
    }
}
