<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventEnroll extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at',
        'enrolled_at',
        'double_checked_at',
        'checked_in_at',
        'checked_out_at',
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }
    public function event(){
        return $this->belongsTo(Event::class);
    }
    // reference_id
    public function service(){
        return $this->belongsTo(Service::class);
    }
}
