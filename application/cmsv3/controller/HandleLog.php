<?php

namespace app\cmsv3\controller;

use think\Request;

class HandleLog
{
    //添加日志22
    public function add()
    {
        $request = Request::instance();
        $ip = $request->ip();
        $db = db('log');
        return $db->insert([
            'title' => input('title'),
            'time' => input('time'),
            'ip' => $ip,
            'users_id' => input('users_id'),
            'type' => input('type'),
            'person_id' => input('person_id')
        ]);
    }
    //加载人员信息
    public function load()
    {
        $db = db('log');
        return  $db->where('person_id', input('id'))
            ->order('time', 'DESC')
            //查询
            ->select();
    }
}
