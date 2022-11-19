<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;


final class Xbot {
    public function send($content, $wxid)
    {
        return Http::withToken(config('services.xbot.token'))
            ->post(config('services.xbot.endpoint'), [
                'type' => 'text',
                'to' => $wxid,
                'data' => [
                    'content' => $content
                ],
            ]);
    }

}