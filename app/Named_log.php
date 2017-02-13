<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Named_log extends Model
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'named_log';

    /**
     * 不可被批量赋值的属性。
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * 获取该记录所属的学生。
     */
    public function student()
    {
        return $this->belongsTo('App\Student', 'stuid', 'stuid')->select('stuid', 'username');
    }

    /**
     * 获取该记录所属的教师。
     */
    public function teacher()
    {
        return $this->belongsTo('App\Teacher', 'workid', 'workid')->select('workid', 'username');
    }
}
