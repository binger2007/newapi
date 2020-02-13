<?php

namespace app\cms\model;

use think\Model;

class Person extends Model
{
    protected $table = 'person';
    // 定义关联方法 
    // public function department()
    // {
    //     // 用户HAS ONE档案关联 
    //     return $this->belongsTo('Department')
    //         ->bind([
    //             'departmentName' => 'label', // '别名' => '字段名'
    //             'p_ids' => 'p_ids'
    //         ]);;
    // }
    public function performance()
    {
        return $this->hasMany('Performance', 'belong_id', 'openid')
            ->order('pubdate DESC');
    }
}
