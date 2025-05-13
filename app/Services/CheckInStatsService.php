<?php

namespace App\Services;

use App\Models\CheckIn;
use Carbon\Carbon;

// php artisan test --filter=CheckInStatsServiceTest
class CheckInStatsService
{
    protected $wxid;
    protected $wxRoom;

    public function __construct(string $wxid, string $wxRoom)
    {
        $this->wxid = $wxid;
        $this->wxRoom = $wxRoom;
    }

    // 计算最近一次中断后的连续打卡天数
    public function getStats(): array
    {
        $dates = CheckIn::where('wxid', $this->wxid)
            ->where('content', $this->wxRoom)
            ->orderBy('check_in_at')
            ->pluck('check_in_at')
            ->map(fn($dt) => Carbon::parse($dt)->toDateString())
            ->unique()
            ->values();

        if ($dates->isEmpty()) {
            return [
                'total_days' => 0,
                'current_streak' => 0,
                'missed_days' => 0,
                'missed_percentage' => '0.00',
                'max_streak' => 0,
                'missed_dates' => [],
                'rank' => 0,
            ];
        }

        $firstDate = Carbon::parse($dates->first());
        $lastDate = Carbon::parse($dates->last());
        $totalRangeDays = $firstDate->diffInDays($lastDate) + 1;
        $totalDays = $dates->count();

        $missedDays = $totalRangeDays - $totalDays;
        $missedPercentage = number_format(($missedDays / $totalRangeDays) * 100, 2);

        // 计算最近一次中断后的连续打卡天数
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

        return [
            'total_days' => $totalDays,
            'current_streak' => $currentStreak,
            'missed_days' => $missedDays,
            'missed_percentage' => $missedPercentage,
            'max_streak' => $this->getMaxStreak(),
            'missed_dates' => $this->getMissedDates(),
            'rank' => $this->getTodayRank(),
        ];
    }

    // 你是今天第51个签到的！
    public function getTodayRank(): int
    {
        $today = now()->startOfDay();
        return CheckIn::whereDate('check_in_at', $today)->count();
    }

    // 最大连续打卡天数
    public function getMaxStreak(): int
    {
        $dates = CheckIn::where('wxid', $this->wxid)
            ->where('content', $this->wxRoom)
            ->orderBy('check_in_at')
            ->pluck('check_in_at')
            ->map(fn($dt) => Carbon::parse($dt)->toDateString())
            ->unique()
            ->values();

        if ($dates->isEmpty()) return 0;

        $maxStreak = 0;
        $currentStreak = 0;
        $prevDate = null;

        foreach ($dates as $date) {
            $dateObj = Carbon::parse($date);
            if ($prevDate && $prevDate->copy()->addDay()->isSameDay($dateObj)) {
                $currentStreak++;
            } else {
                $currentStreak = 1;
            }
            if ($currentStreak > $maxStreak) {
                $maxStreak = $currentStreak;
            }
            $prevDate = $dateObj;
        }

        return $maxStreak;
    }

    // 中断的日期: 断档天数的详细报告
    public function getMissedDates(): array
    {
        $dates = CheckIn::where('wxid', $this->wxid)
            ->where('content', $this->wxRoom)
            ->orderBy('check_in_at')
            ->pluck('check_in_at')
            ->map(fn($dt) => Carbon::parse($dt)->toDateString())
            ->unique()
            ->values();

        if ($dates->isEmpty()) return [];

        $firstDate = Carbon::parse($dates->first());
        $lastDate = Carbon::parse($dates->last());
        $allDates = collect();

        for ($date = $firstDate->copy(); $date->lte($lastDate); $date->addDay()) {
            $allDates->push($date->toDateString());
        }

        $missedDates = $allDates->diff($dates)->values();

        return $missedDates->all();
    }

    // 用户总排名（按打卡总数排序）
    public static function getTopRankings($limit = 10): array
    {
        return CheckIn::select('wxid')
            ->where('content', $this->wxRoom)
            ->selectRaw('COUNT(DISTINCT DATE(check_in_at)) as total_days')
            ->groupBy('wxid')
            ->orderByDesc('total_days')
            ->limit($limit)
            ->get()
            ->toArray();
    }
}