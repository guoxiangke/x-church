<?php

namespace Tests\Feature;

use App\Models\Social;
use App\Models\User;
use App\Services\YbzxTicket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

// App 内网页自动登录（/sso，2026-11-09）
class SsoTest extends TestCase
{
    use RefreshDatabase;

    const SECRET = 'test-sso-secret';
    const HOST = 'check-in-out.online';

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.ybzx.sso_secret' => self::SECRET, 'services.ybzx.ticket_secret' => 'other-secret']);
    }

    protected function ticket(array $claims = [], string $secret = self::SECRET): string
    {
        $header = YbzxTicket::base64UrlEncode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload = YbzxTicket::base64UrlEncode(json_encode(array_merge([
            'iss' => 'ybzx',
            'aud' => self::HOST,
            'sub' => 4242,
            'name' => '李四',
            'avatar_url' => 'https://app.51chat.net/avatars/1.jpg',
            'telephone' => '',
            'telephone_verified' => false,
            'iat' => time(),
            'exp' => time() + 60,
            'jti' => (string) Str::uuid(),
        ], $claims)));
        $signature = YbzxTicket::base64UrlEncode(hash_hmac('sha256', "$header.$payload", $secret, true));
        return "$header.$payload.$signature";
    }

    protected function sso(array $form)
    {
        return $this->post('https://' . self::HOST . '/sso', $form);
    }

    public function test_有效票据_建用户_登录_跳到站内路径()
    {
        $res = $this->sso(['ticket' => $this->ticket(), 'next' => '/dashboard?x=1']);
        $res->assertRedirect('https://' . self::HOST . '/dashboard?x=1');
        $social = Social::where(['type' => Social::TYPE_APP, 'social_id' => '4242'])->first();
        $this->assertNotNull($social);
        $this->assertAuthenticatedAs($social->user);
        $this->assertSame('李四', $social->user->name);
    }

    public function test_同一个_app_用户再来_不重复建号()
    {
        $this->sso(['ticket' => $this->ticket()]);
        $this->post('/logout');
        $this->sso(['ticket' => $this->ticket(['name' => '李四改名'])]);
        $this->assertSame(1, Social::where(['type' => Social::TYPE_APP, 'social_id' => '4242'])->count());
        $this->assertSame('李四改名', Social::where('social_id', '4242')->first()->user->name);
    }

    public function test_换了人_先登出原来的()
    {
        $this->sso(['ticket' => $this->ticket(['sub' => 1])]);
        $first = auth()->id();
        $this->sso(['ticket' => $this->ticket(['sub' => 2])]);
        $this->assertNotSame($first, auth()->id());
        $this->assertSame('2', Social::where('user_id', auth()->id())->first()->social_id);
    }

    public function test_next_只认站内路径()
    {
        foreach (['https://evil.com/', '//evil.com/x', '/\\evil.com', 'evil'] as $next) {
            $this->sso(['ticket' => $this->ticket(), 'next' => $next])
                ->assertRedirect('https://' . self::HOST);
        }
    }

    public function test_票据不对_一律_403_不登录()
    {
        $bad = [
            $this->ticket([], 'wrong-secret'),
            $this->ticket(['aud' => 'chat.yongbuzhixi.com']),
            $this->ticket(['aud' => 'x-church']),
            $this->ticket(['exp' => time() - 10]),
            $this->ticket(['iss' => 'other']),
            'not-a-ticket',
            '',
        ];
        foreach ($bad as $ticket) {
            $this->sso(['ticket' => $ticket])->assertForbidden();
        }
        $this->assertGuest();
    }

    public function test_同一张票只能用一次()
    {
        $ticket = $this->ticket();
        $this->sso(['ticket' => $ticket])->assertRedirect();
        $this->post('/logout');
        $this->sso(['ticket' => $ticket])->assertForbidden();
    }

    public function test_签到票据不能拿来登录_sso_票据也不能拿来签到()
    {
        // 签到用的另一把密钥、aud=x-church：/sso 不认
        $checkInTicket = $this->ticket(['aud' => 'x-church'], 'other-secret');
        $this->sso(['ticket' => $checkInTicket])->assertForbidden();
        $this->assertNull(YbzxTicket::verify($this->ticket()));
    }

    public function test_没配密钥_403()
    {
        config(['services.ybzx.sso_secret' => null]);
        $this->sso(['ticket' => $this->ticket()])->assertForbidden();
    }
}
