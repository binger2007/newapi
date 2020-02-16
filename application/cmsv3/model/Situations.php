<?php

namespace app\cmsv3\model;

use think\Model;

class Situations extends Model
{
    protected $table = 'situations';
    // 修改日期格式
    // protected function getJcrqAttr($time)
    // {
    //     return date('Y-m-d', $time);
    // }
    // 修改检查人
    protected function getJcrAttr($jcr)
    {
        if ($jcr) {
            return $jcr;
        } else {
            return "未知";
        }
    }
}
