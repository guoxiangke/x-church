<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Plank\Metable\Metable;

class Service extends Model
{
    use HasFactory;
    use Metable;
    use SoftDeletes;
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
