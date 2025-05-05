<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\CheckIn;
use App\Services\CheckInStatsService;
use Carbon\Carbon;

class CheckInStatsServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_stats_with_no_checkins()
    {
        $service = new CheckInStatsService('test_user');

        $stats = $service->getStats();

        $this->assertEquals(0, $stats['total_days']);
        $this->assertEquals(0, $stats['current_streak']);
        $this->assertEquals(0, $stats['missed_days']);
        $this->assertEquals('0.00', $stats['missed_percentage']);
        $this->assertEquals(0, $stats['max_streak']);
        $this->assertEquals([], $stats['missed_dates']);
    }

    public function test_get_stats_with_checkins()
    {
        $wxid = 'test_user';
        $today = Carbon::today();

        // 连续打卡三天：5/1, 5/2, 5/3
        CheckIn::factory()->create(['wxid' => $wxid, 'check_in_at' => $today->copy()->subDays(2)]);
        CheckIn::factory()->create(['wxid' => $wxid, 'check_in_at' => $today->copy()->subDays(1)]);
        CheckIn::factory()->create(['wxid' => $wxid, 'check_in_at' => $today]);

        $service = new CheckInStatsService($wxid);
        $stats = $service->getStats();

        $this->assertEquals(3, $stats['total_days']);
        $this->assertEquals(3, $stats['current_streak']);
        $this->assertEquals(0, $stats['missed_days']);
        $this->assertEquals('0.00', $stats['missed_percentage']);
        $this->assertEquals(3, $stats['max_streak']);
        $this->assertEquals([], $stats['missed_dates']);
    }

    public function test_get_stats_with_missed_days()
    {
        $wxid = 'test_user';
        $today = Carbon::today();

        // 打卡 5/1, 5/3（漏掉 5/2）
        CheckIn::factory()->create(['wxid' => $wxid, 'check_in_at' => $today->copy()->subDays(2)]);
        CheckIn::factory()->create(['wxid' => $wxid, 'check_in_at' => $today]);

        $service = new CheckInStatsService($wxid);
        $stats = $service->getStats();

        $this->assertEquals(2, $stats['total_days']);
        $this->assertEquals(1, $stats['current_streak']);
        $this->assertEquals(1, $stats['missed_days']);
        $this->assertEquals('33.33', $stats['missed_percentage']);
        $this->assertEquals(1, $stats['max_streak']);
        $this->assertContains($today->copy()->subDay()->toDateString(), $stats['missed_dates']);
    }

    public function test_get_top_rankings()
    {
        // user1 打卡 3 天
        CheckIn::factory()->count(3)->create(['wxid' => 'user1']);
        // user2 打卡 5 天
        CheckIn::factory()->count(5)->create(['wxid' => 'user2']);

        $topUsers = CheckInStatsService::getTopRankings(2);

        $this->assertEquals('user2', $topUsers[0]['wxid']);
        $this->assertEquals('user1', $topUsers[1]['wxid']);
    }
}