<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

// 永不止息 app 的签到票据：worker 认出设备用户后签发的 HS256 JWT，
// 有效期 2 分钟、只能用一次。验过签才信任里面的 uid，客户端自报的 uid 不认。
class YbzxTicket
{
    // 验证通过返回 payload（sub 为 app 的 uid），否则返回 null
    public static function verify(string $ticket): ?array
    {
        $secret = config('services.ybzx.ticket_secret');
        if (!$secret) return null;

        $parts = explode('.', $ticket);
        if (count($parts) !== 3) return null;
        [$header64, $payload64, $signature64] = $parts;

        $header = json_decode(self::base64UrlDecode($header64), true);
        if (($header['alg'] ?? null) !== 'HS256') return null;

        $expected = self::base64UrlEncode(hash_hmac('sha256', "$header64.$payload64", $secret, true));
        if (!hash_equals($expected, $signature64)) return null;

        $payload = json_decode(self::base64UrlDecode($payload64), true);
        if (!is_array($payload)) return null;
        if (($payload['iss'] ?? null) !== 'ybzx' || ($payload['aud'] ?? null) !== 'x-church') return null;

        $now = time();
        $exp = (int) ($payload['exp'] ?? 0);
        if ($exp < $now || (int) ($payload['iat'] ?? 0) > $now + 60) return null;
        if (empty($payload['sub']) || empty($payload['jti'])) return null;

        // 防重放：同一张票只能用一次
        if (!Cache::add('ybzx-ticket:' . $payload['jti'], 1, $exp - $now + 60)) return null;

        return $payload;
    }

    public static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public static function base64UrlDecode(string $data): string
    {
        return (string) base64_decode(strtr($data, '-_', '+/'));
    }
}
