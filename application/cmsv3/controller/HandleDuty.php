<?php

namespace app\cmsv3\controller;

use think\Db;
use think\Controller;
use think\Request;
use app\cmsv3\model\Duty as DutyModel;

class HandleDuty
{
    //保存值班信息
    public function addDuty()
    {
        $db = db('duty');
        return $db->insert([
            'working' => input('working'),
            'zbsz' => input('zbsz'),
            'zbzg' =>  input('zbzg'),
            'zby' => input('zby'),
            'zry' => input('zry'),
            'time' => time() * 1000,
            'department_id' => input('department_id')
        ]);
    }
    //加载值班信息
    public function loadDuty()
    {
        $db = db('duty');
        return $db->where('department_id', input('department_id'))
            ->limit(1)
            ->order('id', 'desc')
            ->select();
    }
    //如果管理员登录，加载所有单位的值班信息
    public function loadAllDuty()
    {
        $sql = "SELECT a.*,b.label  FROM (
            SELECT * FROM duty  WHERE department_id !='' GROUP BY department_id, id DESC
            ) AS a 
            LEFT JOIN department AS b 
            ON a.department_id = b.id
            GROUP BY department_id";
        return Db::query($sql);
    }
}
