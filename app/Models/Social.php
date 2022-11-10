<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    const TYPE_WECHAT = 0;
    const TYPE_FACEBOOK = 1;
    const TYPE_GITHUB = 2;
    // A person is assigned a unique page-scoped ID (PSID) for each Facebook Page they start a conversation with. The PSID is used by your Messenger bot to identify a person when sending messages.
    const TYPE_FB_PSID = 3;

    const TYPES = ['微信', 'Github', 'Facebook', 'PSID'];

    public function user(){
        return $this->belongsTo(User::Class);
    }
}
