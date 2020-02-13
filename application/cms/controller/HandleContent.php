<?php

namespace app\cms\controller;

use think\Db;
use think\Controller;
use think\Request;
use app\cms\model\Content as ContentModel;

class HandleContent
{
    //保存
    public function add()
    {
        $db = db('content');
        $time = input('time') ? input('time') : time() * 1000;
        return $db->insert([
            'title' => input('title'),
            'content' => input('content'),
            'time' =>  $time,
            'department_id' => input('department_id'),
            'type_id' => input('type_id')
        ]);
    }
    //加载
    public function load()
    {
        $situations = new ContentModel();
        return $situations
            ->where('department_id', input('department_id'))
            ->where('type_id', input('type_id'))
            ->order('time', 'desc')
            ->select();
    }
    //删除
    public function del()
    {
        $db = db('content');
        $id =  input('id');
        return $db->where('id', $id)->delete();
    }
    //编辑
    public function edit()
    {
        $db = db('content');
        return $db->where('id', input('id'))->update([
            'content' => input('content'),
            'title' => input('title'),
            'time' => input('time')
        ]);
    }
}
