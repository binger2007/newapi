<?php

namespace app\cmsv3\model;

use think\Model;

class Duty extends Model
{
    protected $table = 'duty';
    // 定义关联方法 
    public function department()
    {
        // 用户HAS ONE档案关联 
        return $this->belongsTo('Department');
    }
    // 修改登录日期格式
    protected function getTimeAttr($time)
    {
        return date('Y-m-d', $time);
    }
}
