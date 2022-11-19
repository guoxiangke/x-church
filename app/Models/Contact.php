<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Plank\Metable\Metable;

class Contact extends Model
{
    use HasFactory;
    use Metable;
    use SoftDeletes;
    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at',
        'date_join',
        'birthday',
    ];

    public function organization(){
        return $this->belongsTo(Organization::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    // reference_id
    public function referenceUser(){
        return $this->hasOne(User::class,'reference_id');
    }

}
