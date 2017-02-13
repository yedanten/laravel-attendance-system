<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Teacher extends Authenticatable
{
    use Notifiable;

    /**
     * 主键非自动增长
     *
     * @var string
     */
    public $incrementing = false;

    /**
     * 主键
     *
     * @var string
     */
    protected $primaryKey = 'workid';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'workid', 'username', 'email', 'password', 'lesson',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 获取Gravatat头像。
     */
    public function getGravatarAttribute()
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return 'http://www.gravatar.com/avatar/'.$hash;
    }

    /**
     * 获取这个教师下的所有考勤记录。
     */
    public function namedLog()
    {
        return $this->hasMany('App\Named_log');
    }
}
