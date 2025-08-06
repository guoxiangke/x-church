<?php

namespace Tests\Unit\Services;

use App\Models\CheckIn;
use App\Services\GlobalCheckInStatsService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GlobalCheckInStatsServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $service;
    protected $wxRoom = 'test_room_123';

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new GlobalCheckInStatsService($this->wxRoom);
    }

    /** @test */
    public function it_returns_empty_rankings_when_no_check_ins_exist()
    {
        $totalRanking = $this->service->getTotalDaysRanking();
        $streakRanking = $this->service->getCurrentStreakRanking();

        $this->assertEmpty($totalRanking);
        $this->assertEmpty($streakRanking);
    }

    /** @test */
    public function it_calculates_total_days_ranking_correctly()
    {
        // 创建测试数据
        // 用户A：3天打卡
        CheckIn::factory()->create([
            'wxid' => 'user_a',
            'nickname' => 'UserA',
            'content' => $this->wxRoom,
            'check_in_at' => '2024-01-01 09:00:00'
        ]);
        CheckIn::factory()->create([
            'wxid' => 'user_a',
            'nickname' => 'UserA',
            'content' => $this->wxRoom,
            'check_in_at' => '2024-01-02 09:00:00'
        ]);
        CheckIn::factory()->create([
            'wxid' => 'user_a',
            'nickname' => 'UserA',
            'content' => $this->wxRoom,
            'check_in_at' => '2024-01-03 09:00:00'
        ]);

        // 用户B：2天打卡
        CheckIn::factory()->create([
            'wxid' => 'user_b',
            'nickname' => 'UserB',
            'content' => $this->wxRoom,
            'check_in_at' => '2024-01-01 10:00:00'
        ]);
        CheckIn::factory()->create([
            'wxid' => 'user_b',
            'nickname' => 'UserB',
            'content' => $this->wxRoom,
            'check_in_at' => '2024-01-02 10:00:00'
        ]);

        // 用户C：1天打卡
        CheckIn::factory()->create([
            'wxid' => 'user_c',
            'nickname' => 'UserC',
            'content' => $this->wxRoom,
            'check_in_at' => '2024-01-01 11:00:00'
        ]);

        $ranking = $this->service->getTotalDaysRanking();

        $this->assertCount(3, $ranking);
        
        // 验证排名顺序
        $this->assertEquals(1, $ranking[0]['rank']);
        $this->assertEquals('user_a', $ranking[0]['wxid']);
        $this->assertEquals('UserA', $ranking[0]['nickname']);
        $this->assertEquals(3, $ranking[0]['total_days']);

        $this->assertEquals(2, $ranking[1]['rank']);
        $this->assertEquals('user_b', $ranking[1]['wxid']);
        $this->assertEquals(2, $ranking[1]['total_days']);

        $this->assertEquals(3, $ranking[2]['rank']);
        $this->assertEquals('user_c', $ranking[2]['wxid']);
        $this->assertEquals(1, $ranking[2]['total_days']);
    }

    /** @test */
    public function it_calculates_current_streak_ranking_correctly()
    {
        // 用户A：连续3天打卡（最新的连续）
        CheckIn::factory()->create([
            'wxid' => 'user_a',
            'nickname' => 'UserA',
            'content' => $this->wxRoom,
            'check_in_at' => '2024-01-01 09:00:00'
        ]);
        CheckIn::factory()->create([
            'wxid' => 'user_a',
            'nickname' => 'UserA',
            'content' => $this->wxRoom,
            'check_in_at' => '2024-01-02 09:00:00'
        ]);
        CheckIn::factory()->create([
            'wxid' => 'user_a',
            'nickname' => 'UserA',
            'content' => $this->wxRoom,
            'check_in_at' => '2024-01-03 09:00:00'
        ]);

        // 用户B：连续2天打卡
        CheckIn::factory()->create([
            'wxid' => 'user_b',
            'nickname' => 'UserB',
            'content' => $this->wxRoom,
            'check_in_at' => '2024-01-02 10:00:00'
        ]);
        CheckIn::factory()->create([
            'wxid' => 'user_b',
            'nickname' => 'UserB',
            'content' => $this->wxRoom,
            'check_in_at' => '2024-01-03 10:00:00'
        ]);

        // 用户C：中断后重新开始，当前连续1天
        CheckIn::factory()->create([
            'wxid' => 'user_c',
            'nickname' => 'UserC',
            'content' => $this->wxRoom,
            'check_in_at' => '2024-01-01 11:00:00'
        ]);
        // 1月2日中断
        CheckIn::factory()->create([
            'wxid' => 'user_c',
            'nickname' => 'UserC',
            'content' => $this->wxRoom,
            'check_in_at' => '2024-01-03 11:00:00'
        ]);

        $ranking = $this->service->getCurrentStreakRanking();

        $this->assertCount(3, $ranking);

        // 验证连续天数排名
        $this->assertEquals(1, $ranking[0]['rank']);
        $this->assertEquals('user_a', $ranking[0]['wxid']);
        $this->assertEquals(3, $ranking[0]['current_streak']);

        $this->assertEquals(2, $ranking[1]['rank']);
        $this->assertEquals('user_b', $ranking[1]['wxid']);
        $this->assertEquals(2, $ranking[1]['current_streak']);

        $this->assertEquals(3, $ranking[2]['rank']);
        $this->assertEquals('user_c', $ranking[2]['wxid']);
        $this->assertEquals(1, $ranking[2]['current_streak']);
    }

    /** @test */
    public function it_handles_same_day_multiple_check_ins_correctly()
    {
        // 同一天多次打卡，应该只算1天
        CheckIn::factory()->create([
            'wxid' => 'user_a',
            'nickname' => 'UserA',
            'content' => $this->wxRoom,
            'check_in_at' => '2024-01-01 09:00:00'
        ]);
        CheckIn::factory()->create([
            'wxid' => 'user_a',
            'nickname' => 'UserA',
            'content' => $this->wxRoom,
            'check_in_at' => '2024-01-01 15:00:00'
        ]);
        CheckIn::factory()->create([
            'wxid' => 'user_a',
            'nickname' => 'UserA',
            'content' => $this->wxRoom,
            'check_in_at' => '2024-01-02 09:00:00'
        ]);

        $totalRanking = $this->service->getTotalDaysRanking();
        $streakRanking = $this->service->getCurrentStreakRanking();

        $this->assertEquals(2, $totalRanking[0]['total_days']);
        $this->assertEquals(2, $streakRanking[0]['current_streak']);
    }

    /** @test */
    public function it_filters_by_wx_room_correctly()
    {
        // 在目标房间的打卡
        CheckIn::factory()->create([
            'wxid' => 'user_a',
            'nickname' => 'UserA',
            'content' => $this->wxRoom,
            'check_in_at' => '2024-01-01 09:00:00'
        ]);

        // 在其他房间的打卡，不应该被统计
        CheckIn::factory()->create([
            'wxid' => 'user_a',
            'nickname' => 'UserA',
            'content' => 'other_room',
            'check_in_at' => '2024-01-01 09:00:00'
        ]);

        CheckIn::factory()->create([
            'wxid' => 'user_b',
            'nickname' => 'UserB',
            'content' => 'other_room',
            'check_in_at' => '2024-01-01 10:00:00'
        ]);

        $totalRanking = $this->service->getTotalDaysRanking();
        $streakRanking = $this->service->getCurrentStreakRanking();

        $this->assertCount(1, $totalRanking);
        $this->assertCount(1, $streakRanking);
        $this->assertEquals('user_a', $totalRanking[0]['wxid']);
        $this->assertEquals('user_a', $streakRanking[0]['wxid']);
    }

    /** @test */
    public function it_respects_limit_parameter()
    {
        // 创建5个用户的打卡记录
        for ($i = 1; $i <= 5; $i++) {
            CheckIn::factory()->create([
                'wxid' => "user_{$i}",
                'nickname' => "User{$i}",
                'content' => $this->wxRoom,
                'check_in_at' => '2024-01-01 09:00:00'
            ]);
        }

        $totalRanking = $this->service->getTotalDaysRanking(3);
        $streakRanking = $this->service->getCurrentStreakRanking(3);

        $this->assertCount(3, $totalRanking);
        $this->assertCount(3, $streakRanking);
    }

    /** @test */
    public function it_excludes_users_with_zero_streak()
    {
        // 这个测试确保没有打卡记录或连续天数为0的用户不会出现在排名中
        // 由于我们的逻辑是基于实际的打卡记录，所以有记录的用户连续天数不会为0
        // 但我们可以测试空记录的情况

        $totalRanking = $this->service->getTotalDaysRanking();
        $streakRanking = $this->service->getCurrentStreakRanking();

        $this->assertEmpty($totalRanking);
        $this->assertEmpty($streakRanking);
    }

    /** @test */
    public function it_calculates_streak_correctly_with_gaps()
    {
        // 用户有打卡间隔，测试最后一段连续天数的计算
        CheckIn::factory()->create([
            'wxid' => 'user_a',
            'nickname' => 'UserA',
            'content' => $this->wxRoom,
            'check_in_at' => '2024-01-01 09:00:00'
        ]);
        CheckIn::factory()->create([
            'wxid' => 'user_a',
            'nickname' => 'UserA',
            'content' => $this->wxRoom,
            'check_in_at' => '2024-01-02 09:00:00'
        ]);
        // 1月3日中断
        CheckIn::factory()->create([
            'wxid' => 'user_a',
            'nickname' => 'UserA',
            'content' => $this->wxRoom,
            'check_in_at' => '2024-01-04 09:00:00'
        ]);
        CheckIn::factory()->create([
            'wxid' => 'user_a',
            'nickname' => 'UserA',
            'content' => $this->wxRoom,
            'check_in_at' => '2024-01-05 09:00:00'
        ]);
        CheckIn::factory()->create([
            'wxid' => 'user_a',
            'nickname' => 'UserA',
            'content' => $this->wxRoom,
            'check_in_at' => '2024-01-06 09:00:00'
        ]);

        $streakRanking = $this->service->getCurrentStreakRanking();

        // 当前连续天数应该是最后一段连续的天数：1月4-6日，共3天
        $this->assertEquals(3, $streakRanking[0]['current_streak']);
    }
}