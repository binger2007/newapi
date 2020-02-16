<?php

namespace app\cmsv3\model;

use think\Model;

class User extends Model
{
    protected $table = 'users';
    // 定义关联方法 
    public function department()
    {
        // 用户HAS ONE档案关联 
        return $this->belongsTo('Department');
    }
    // 修改登录日期格式
    // protected function getLastLoginTimeAttr($time)
    // {
    //     return date('Y-m-d', $time);
    // }
    // 修改最后登录ip
    protected function getLastLoginIpAttr($ip)
    {
        if (!$ip) {
            return '未登录';
        } else {
            return $ip;
        }
    }
    // 修改用户类型
    protected function getUtypeAttr($utype)
    {
        if ($utype) {
            return '单位管理员';
        } else {
            return '超级管理员';
        }
    }
}
