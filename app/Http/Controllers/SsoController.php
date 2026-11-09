<?php

namespace App\Http\Controllers;

use App\Services\YbzxTicket;
use App\Services\YbzxUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// App 内网页自动登录（2026-11-09）：永不止息 App 在 App 内网页打开本站前，找 worker 换一张
// 票据（HS256，aud=本站域名，60 秒、只能用一次），POST 到这里（表单 ticket、next）。
// 验过签按 app 的 uid 找到或新建用户并登录，再跳到 next（只认站内路径）。
// 说明见 ybzx/docs/sso-laravel.md。
class SsoController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $claims = YbzxTicket::verifySso((string) $request->input('ticket', ''), $request->getHost());
        if (!$claims) {
            abort(403, '登录票据无效或已过期，请回到 App 重新打开');
        }

        $user = YbzxUser::resolve($claims);

        // 这台手机上原来登着别人：先登出，再登录这个人
        if (Auth::check() && Auth::id() !== $user->id) {
            Auth::logout();
            $request->session()->invalidate();
        }
        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->to($this->safeNext((string) $request->input('next', '/')));
    }

    // 只跳站内路径，不让人拿它跳去别的网站
    protected function safeNext(string $next): string
    {
        if ($next === '' || $next[0] !== '/' || str_starts_with($next, '//') || str_contains($next, '\\')) {
            return '/';
        }
        return $next;
    }
}
