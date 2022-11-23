<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Plank\Metable\Metable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class Service extends Model
{
    use HasHashid, HashidRouting;
    protected $appends = ['hashid','qrpath'];
    public function getQrpathAttribute()
    {
        return "public/s-{$this->organization->id}-{$this->hashid}.png";
    }
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


    public function events(){
        return $this->hasMany(Event::class);
    }

    public function organization(){
        return $this->belongsTo(Organization::class);
    }
}
