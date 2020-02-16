<?php

namespace app\cmsv3\model;

use think\Model;

class content extends Model
{
    protected $table = 'content';
    // 修改日期格式
    // protected function getUserTimeAttr($time, $data)
    // {
    //     return date('Y-m-d', $data['time']);
    // }
}
