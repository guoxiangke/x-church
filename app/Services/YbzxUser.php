<?php

namespace App\Services;

use App\Models\Social;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

// 永不止息 app 的用户 → 本站用户（socials 里 type=app、social_id=app 的 uid）。
// 扫码签到（AppCheckInController）和 App 内网页自动登录（SsoController）共用。
class YbzxUser
{
    // 按 app 的 uid 找本地用户，没有就建一个；资料以票据里的为准，空的不覆盖
    public static function resolve(array $claims): User
    {
        $uid = (string) $claims['sub'];
        $name = trim((string) ($claims['name'] ?? ''));
        $telephone = trim((string) ($claims['telephone'] ?? ''));
        $avatar = trim((string) ($claims['avatar_url'] ?? ''));

        $social = Social::where(['type' => Social::TYPE_APP, 'social_id' => $uid])->first();
        $user = $social?->user;
        if (!$user) {
            $user = User::create([
                'name' => $name ?: "app用户{$uid}",
                'email' => "{$uid}@ybzx.app",
                'password' => Hash::make(Str::random(16)),
                'profile_photo_path' => $avatar ?: null,
            ]);
            if ($social) {
                $social->user_id = $user->id;
                $social->save();
            } else {
                $social = Social::create([
                    'user_id' => $user->id,
                    'social_id' => $uid,
                    'type' => Social::TYPE_APP,
                ]);
            }
        }

        if ($name) {
            $user->name = $name;
            $social->name = $name;
        }
        if ($avatar) {
            $user->profile_photo_path = $avatar;
            $social->avatar = $avatar;
        }
        if ($telephone) $social->telephone = $telephone;
        if ($user->isDirty()) $user->save();
        if ($social->isDirty()) $social->save();

        return $user;
    }
}
