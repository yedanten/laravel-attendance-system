<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
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
    protected $primaryKey = 'stuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'stuid', 'username', 'password', 'sex', 'department', 'major', 'class', 'email'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password'];

    /**
     * 获取Gravatat头像。
     */
    public function getGravatarAttribute()
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return 'http://www.gravatar.com/avatar/'.$hash;
    }
    
    /**
     * 获取这个学生下的所有考勤记录。
     */
    public function namedLog()
    {
        return $this->hasMany('App\Named_log');
    }
}
