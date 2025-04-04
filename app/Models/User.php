<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
// use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\HasApiTokens;
// Both HasApiTokens 皆可，已测试
use Illuminate\Database\Eloquent\SoftDeletes;
use Plank\Metable\Metable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;
    use Metable;

    use LogsActivity;
    protected static $recordEvents = ['updated'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
    
    public function organizations(){
        return $this->hasMany(Organization::class);
    }
    public function isAdmin(){
        return  $this->id === 1;
    }
    
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
        'nickname',
        'avatar_url',
    ];

    public function getProfilePhotoUrlAttribute()
    {
        if($this->profile_photo_path && Str::startsWith($this->profile_photo_path, 'http')) return $this->profile_photo_path;
        return $this->profile_photo_path
                    ? Storage::disk($this->profilePhotoDisk())->url($this->profile_photo_path)
                    : $this->defaultProfilePhotoUrl();
    }


    public function getNickNameAttribute()
    {
        return $this->social?$this->social->name:$this->name;
    }
    
    public function getAvatarUrlAttribute()
    {
        return $this->social?$this->social->avatar:$this->profile_photo_url;
    }

    public function social(){
        return $this->hasOne(Social::Class);
    }
    
    // Add a routeNotificationForTwilio method or a phone_number attribute to your notifiable.
    public function routeNotificationForTwilio()
    {
        return $this->social->telephone??env('TWILIO_DEBUG_TO');
    }
}
