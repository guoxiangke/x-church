<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Plank\Metable\Metable;
use Illuminate\Support\Facades\Http;

class Organization extends Model
{
    use HasFactory;
    use Metable;
    use SoftDeletes;
    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at',
        'birthday',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function send($content, $wxid)
    {
        return Http::withToken($this->wechat_ai_token??config('services.xbot.token'))
            ->post(config('services.xbot.endpoint'), [
                'type' => 'text',
                'to' => $wxid,
                'data' => [
                    'content' => $content
                ],
            ]);
    }
}
