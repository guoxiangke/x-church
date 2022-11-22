<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Plank\Metable\Metable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Event extends Model
{
    use HasFactory;
    use Metable;
    use SoftDeletes;

    use LogsActivity;
    protected static $recordEvents = ['updated'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at',
        'begin_at',
    ];


    public function eventEnrolls(){
        return $this->hasMany(EventEnroll::class);
    }



    public function user(){
        return $this->belongsTo(User::class);
    }

    public function service(){
        return $this->belongsTo(Service::class);
    }

    
    public function organization(){
        return $this->belongsTo(Organization::class);
    }

    // 如果活动已结束, 结束后1h可以check-out
    public function isEnd(){
        return now() > $this->begin_at->addHours($this->duration_hours+1);
    }
    // 是否在今天？
    public function isToday(){
        return now()->diffInDays($this->begin_at) === 0;
    }
    
}
