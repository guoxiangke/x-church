<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Plank\Metable\Metable;

class Event extends Model
{
    use HasFactory;
    use Metable;
    use SoftDeletes;
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
}
