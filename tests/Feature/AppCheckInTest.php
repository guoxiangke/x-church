<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use App\Models\Event;
use App\Models\EventEnroll;
use App\Models\Organization;
use App\Models\Service;
use App\Models\Social;
use App\Models\User;
use App\Services\YbzxTicket;

class AppCheckInTest extends TestCase
{
    use RefreshDatabase;

    const SECRET = 'test-ticket-secret';

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.ybzx.ticket_secret' => self::SECRET]);
    }

    // 不触发观察者：创建 event 时会去下载 logo 生成二维码。
    // 不用现有 factory：EventFactory 会额外建 organization，其 email 域名 unique 很快耗尽
    protected function makeEvent(array $attributes = []): Event
    {
        return Model::withoutEvents(function () use ($attributes) {
            $organization = Organization::create([
                'user_id' => User::factory()->create()->id,
                'system_name' => 'sys-' . Str::random(6),
                'name' => '测试教会',
                'name_abbr' => '测试',
                'name_en' => 'Test Church',
                'name_en_abbr' => 'TC',
                'telephone' => '+10000000000',
                'email' => Str::random(8) . '@example.com',
                'birthday' => now(),
            ]);
            $service = Service::create([
                'organization_id' => $organization->id,
                'name' => '主日崇拜',
                'begin_at' => now()->addMinutes(30),
                'duration_hours' => 2,
                'rrule' => 'FREQ=WEEKLY;BYDAY=SU',
            ]);
            return Event::create(array_merge([
                'organization_id' => $organization->id,
                'service_id' => $service->id,
                'name' => '主日崇拜 - ' . now()->format('ymd'),
                'begin_at' => now()->addMinutes(30),
                'duration_hours' => 2,
                'check_in_ahead' => 180,
            ], $attributes));
        });
    }

    protected function ticket(array $claims = [], string $secret = self::SECRET): string
    {
        $header = YbzxTicket::base64UrlEncode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload = YbzxTicket::base64UrlEncode(json_encode(array_merge([
            'iss' => 'ybzx',
            'aud' => 'x-church',
            'sub' => 42,
            'name' => '张三',
            'telephone' => '+8613800000000',
            'iat' => time(),
            'exp' => time() + 120,
            'jti' => (string) Str::uuid(),
        ], $claims)));
        $signature = YbzxTicket::base64UrlEncode(hash_hmac('sha256', "$header.$payload", $secret, true));
        return "$header.$payload.$signature";
    }

    protected function checkIn(string $url, ?string $ticket = null)
    {
        return $this->postJson('/api/app/check-in', ['ticket' => $ticket ?? $this->ticket(), 'url' => $url]);
    }

    public function test_app_user_checks_in_and_is_created()
    {
        $event = $this->makeEvent();

        $this->checkIn(route('event.checkin', $event->hashid))
            ->assertOk()
            ->assertJson(['success' => true, 'status' => 3, 'title' => '签到成功', 'event' => ['name' => $event->name]]);

        $social = Social::where(['type' => Social::TYPE_APP, 'social_id' => '42'])->firstOrFail();
        $this->assertSame('张三', $social->user->name);
        $this->assertSame('+8613800000000', $social->telephone);
        $this->assertNotNull(EventEnroll::firstWhere(['user_id' => $social->user_id, 'event_id' => $event->id])->checked_in_at);
    }

    public function test_second_scan_does_not_check_in_twice()
    {
        $event = $this->makeEvent();
        $url = route('event.checkin', $event->hashid);

        $this->checkIn($url)->assertJson(['status' => 3]);
        $this->checkIn($url)->assertJson(['status' => 7, 'title' => '无需重复签到!']);
        $this->assertSame(1, Social::where('type', Social::TYPE_APP)->count());
        $this->assertSame(1, EventEnroll::where('event_id', $event->id)->count());
    }

    public function test_enroll_before_check_in_window()
    {
        $event = $this->makeEvent(['begin_at' => now()->addHours(5), 'description' => '週日崇拜聚會', 'is_show_avatar' => true, 'address' => '1 Main St']);

        $ticket = $this->ticket(['avatar_url' => 'https://app.51chat.net/avatar/42.jpg']);
        $response = $this->checkIn(route('event.checkin', $event->hashid), $ticket)
            ->assertJson(['success' => true, 'status' => 2, 'title' => '报名成功']);
        // 结果页的行和网页一致
        $response->assertJsonPath('page.summary', [
            ['label' => '名称', 'value' => $event->name],
            ['label' => '简介', 'value' => '週日崇拜聚會'],
        ]);
        $this->assertSame(['活动时间', '活动时长', '签到时间', '当前时间'], array_column($response->json('page.schedule'), 'label'));
        $response->assertJsonPath('page.schedule.1.value', '2小时')
            ->assertJsonPath('page.schedule.2.value', '开始前180分钟')
            ->assertJsonPath('page.enrolls.count', 1)
            ->assertJsonPath('page.map_url', 'http://maps.google.com/maps?q=1+Main+St')
            ->assertJsonPath('page.footer_title', $event->organization->system_name.'活动签到管理系统')
            ->assertJsonPath('page.title', $event->name.'|'.$event->organization->system_name);
        $response->assertJsonPath('page.enrolls.avatars', ['https://app.51chat.net/avatar/42.jpg']);
    }

    public function test_checked_in_page_hides_schedule()
    {
        $event = $this->makeEvent();

        $this->checkIn(route('event.checkin', $event->hashid))
            ->assertJson(['status' => 3])
            ->assertJsonPath('page.schedule', [])
            ->assertJsonPath('page.enrolls', null)
            ->assertJsonPath('page.map_url', null)
            ->assertJsonPath('page.summary.0.label', '名称');
    }

    public function test_event_ended()
    {
        $event = $this->makeEvent(['begin_at' => now()->subHours(3)]);

        $this->checkIn(route('event.checkin', $event->hashid))->assertJson(['success' => false, 'status' => 1]);
    }

    public function test_service_qrcode_uses_current_event()
    {
        $event = $this->makeEvent();

        $this->checkIn(route('service.checkin', $event->service->hashid))
            ->assertOk()
            ->assertJson(['status' => 3, 'event' => ['name' => $event->name]]);
    }

    public function test_empty_name_gets_placeholder()
    {
        $event = $this->makeEvent();

        $this->checkIn(route('event.checkin', $event->hashid), $this->ticket(['sub' => 7, 'name' => '', 'telephone' => '']))->assertOk();

        $this->assertSame('app用户7', Social::where(['type' => Social::TYPE_APP, 'social_id' => '7'])->first()->user->name);
    }

    public function test_rejects_bad_tickets()
    {
        $url = route('event.checkin', $this->makeEvent()->hashid);

        $this->checkIn($url, $this->ticket([], 'wrong-secret'))->assertStatus(401);
        $this->checkIn($url, $this->ticket(['exp' => time() - 1]))->assertStatus(401);
        $this->checkIn($url, $this->ticket(['aud' => 'other']))->assertStatus(401);
        $this->checkIn($url, 'not-a-jwt')->assertStatus(401);

        $ticket = $this->ticket();
        $this->checkIn($url, $ticket)->assertOk();
        $this->checkIn($url, $ticket)->assertStatus(401); // 重放

        config(['services.ybzx.ticket_secret' => null]);
        $this->checkIn($url)->assertStatus(401);
        $this->assertSame(1, Social::where('type', Social::TYPE_APP)->count());
    }

    public function test_rejects_foreign_or_unknown_qrcode()
    {
        $event = $this->makeEvent();

        $this->checkIn('https://evil.example.com/e/' . $event->hashid)->assertStatus(404);
        $this->checkIn(url('/e/zzzzzzzz'))->assertStatus(404);
        $this->checkIn(url('/dashboard'))->assertStatus(404);
    }

    // 旧的微信网页扫码流程不受影响
    public function test_wechat_web_check_in_still_works()
    {
        $event = $this->makeEvent();
        $user = User::factory()->create();
        Social::create(['user_id' => $user->id, 'social_id' => 'openid-1', 'type' => Social::TYPE_WECHAT, 'wxid' => 'wxid_1']);

        $this->actingAs($user)->get(route('event.checkin', $event->hashid))
            ->assertOk()
            ->assertViewIs('pages.check-in')
            ->assertViewHas('status', 3)
            ->assertSee('签到成功');

        $this->assertNotNull(EventEnroll::firstWhere(['user_id' => $user->id, 'event_id' => $event->id])->checked_in_at);
    }

    public function test_well_known_files_for_app_links()
    {
        $this->get('/.well-known/apple-app-site-association')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJsonPath('applinks.details.0.appIDs.0', 'L833M8JQWM.app.yongbuzhixi.com');
        $this->get('/.well-known/assetlinks.json')
            ->assertOk()
            ->assertJsonPath('0.target.package_name', 'app.yongbuzhixi.com');
    }
}
