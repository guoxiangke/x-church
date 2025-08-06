<?php

namespace App\Services;

use App\Models\CheckIn;
use Carbon\Carbon;

// php artisan test --filter=GlobalCheckInStatsServiceTest
class GlobalCheckInStatsService
{
    protected $wxRoom;

    public function __construct(string $wxRoom)
    {
        $this->wxRoom = $wxRoom;
    }

    /**
     * 查询某个wxRoom里的用户总排名（按打卡总数排序）
     */
    public function getTotalDaysRanking($limit = 10): array
    {
        return CheckIn::select('wxid', 'nickname')
            ->where('content', $this->wxRoom)
            ->selectRaw('COUNT(DISTINCT DATE(check_in_at)) as total_days')
            ->groupBy('wxid', 'nickname')
            ->orderByDesc('total_days')
            ->limit($limit)
            ->get()
            ->map(function ($item, $index) {
                return [
                    'rank' => $index + 1,
                    'wxid' => $item->wxid,
                    'nickname' => $item->nickname,
                    'total_days' => $item->total_days,
                ];
            })
            ->toArray();
    }

    /**
     * 查询连续打卡天数的用户总排名
     */
    public function getCurrentStreakRanking($limit = 10): array
    {
        $allUsers = CheckIn::select('wxid', 'nickname')
            ->where('content', $this->wxRoom)
            ->groupBy('wxid', 'nickname')
            ->get();

        $userStreaks = [];

        foreach ($allUsers as $user) {
            $currentStreak = $this->calculateCurrentStreak($user->wxid);
            if ($currentStreak > 0) {
                $userStreaks[] = [
                    'wxid' => $user->wxid,
                    'nickname' => $user->nickname,
                    'current_streak' => $currentStreak,
                ];
            }
        }

        // 按连续天数降序排序
        usort($userStreaks, function ($a, $b) {
            return $b['current_streak'] <=> $a['current_streak'];
        });

        // 添加排名并限制数量
        $ranking = array_slice($userStreaks, 0, $limit);
        foreach ($ranking as $index => &$item) {
            $item['rank'] = $index + 1;
        }

        return $ranking;
    }

    /**
     * 计算指定用户的当前连续打卡天数
     */
    protected function calculateCurrentStreak(string $wxid): int
    {
        $dates = CheckIn::where('wxid', $wxid)
            ->where('content', $this->wxRoom)
            ->orderBy('check_in_at')
            ->pluck('check_in_at')
            ->map(fn($dt) => Carbon::parse($dt)->toDateString())
            ->unique()
            ->values();

        if ($dates->isEmpty()) return 0;

        $currentStreak = 0;
        $tempStreak = 0;
        $prevDate = null;

        foreach ($dates as $date) {
            $dateObj = Carbon::parse($date);
            if ($prevDate && $prevDate->copy()->addDay()->isSameDay($dateObj)) {
                $tempStreak++;
            } else {
                $tempStreak = 1;
            }
            $currentStreak = $tempStreak;
            $prevDate = $dateObj;
        }

        return $currentStreak;
    }
}