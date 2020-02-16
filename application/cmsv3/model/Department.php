<?php

namespace app\cmsv3\model;

use think\Model;

class Department extends Model
{
    protected $table = 'department';
    public function performance()
    {
        return $this->hasMany('Performance', 'belong_id')
            ->where('type', '2')
            ->order('pubdate ASC');
    }
    // 修改登录日期格式
    // protected function getLastLoginTimeAttr($time)
    // {
    //     return date('Y-m-d', $time);
    // }
    // // 修改最后登录ip
    // protected function getLastLoginIpAttr($ip)
    // {
    //     if (!$ip) {
    //         return '未登录';
    //     }else{
    //         return $ip;
    //     }
    // }
    // // 修改用户类型
    // protected function getUtypeAttr($utype)
    // {
    //     if ($utype) {
    //         return '普通用户';
    //     }else{
    //         return '管理员';
    //     }
    // }
}
