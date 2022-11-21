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
        'canceled_at',
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }
    public function event(){
        return $this->belongsTo(Event::class);
    }
    // 不一定都有，可能是单独的event而不属于某个service
    public function service(){
        return $this->belongsTo(Service::class);
    }
    public function cancel(){
        $this->canceled_at = now();
        return $this->save();
    }

    // 默认统计 without cancel，但显示时，要显示 canceled
    // ： https://laravel.com/docs/9.x/eloquent#anonymous-global-scopes
    /**
     * Scope a query to only include active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeActive($query)
    {
        $query->whereNull('canceled_at');
    }
}
