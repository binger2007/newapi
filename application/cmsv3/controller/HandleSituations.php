<?php

namespace app\cmsv3\controller;

use think\Db;
use think\Controller;
use think\Request;
use app\cmsv3\model\Situations as SituationsModel;

class HandleSituations
{
    //保存检查情况
    public function add()
    {
        $db = db('situations');
        $jcrq = input('jcrq') ? input('jcrq')  : time() * 1000;
        return $db->insert([
            'jcr' => input('jcr'),
            'jcqk' => input('jcqk'),
            'jcrq' =>  $jcrq,
            'department_id' => input('department_id')
        ]);
    }
    //加载检查情况
    public function load()
    {
        $situations = new SituationsModel();
        return $situations
            ->where('department_id', input('department_id'))
            ->order('jcrq', 'desc')
            ->select();
    }
}
