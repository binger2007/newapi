<?php

namespace app\cms\controller;

use think\Db;
use think\Controller;
use think\Request;
use app\cms\model\User as UserModel;
use app\cms\model\Person;

class HandleUser
{

    public function index($name = 'cms')
    {
        return 'hello ' . $name . '!';
    }
    //获取微信用户信息
    public function getWxInfo()
    {
        return file_get_contents(input('url'));
    }
    //加载用户
    public function loadUser()
    {
        $db = db('users');
        $did =  input('did');
        return $db
            ->alias("a") //取一个别名
            //与department表进行关联，取名b，并且a表的department_id字段等于department表的id字段
            ->join('department b', 'a.department_id = b.id')
            //想要的字段
            ->field('a.*,b.label,b.p_id,b.p_ids')
            ->where('a.department_id', $did)
            ->whereOr('b.p_ids', 'LIKE', "%,$did,%")
            ->order('a.id desc')
            ->select();
    }
    // 验证登录
    public function checkLogin()
    {
        $db = db('users');
        $userName = input('userName');
        $pass = input('pass');
        $department = input('department');
        $result = UserModel::get([
            'uname' => $userName,
            'pwd' => $pass
        ], 'department');
        //更新登录时间
        if ($result) {
            $request = Request::instance();
            $ip = $request->ip();
            $db->where('uname', $userName)->update(['last_login_time' => time() * 1000, 'last_login_ip' => $ip]);
        }
        return json($result);
    }
    // 验证会员登录
    public function checkMemberLogin()
    {
        $db = db('person');
        $userName = input('userName');
        $pass = input('pass');
        $department = input('department');
        //处理超级管理员无法登录的bug
        return $db
            ->alias('a')
            ->join('department b', 'a.department_id = b.id')
            //想要的字段
            ->field('a.*,b.label,b.p_id,b.p_ids')
            ->where('a.department_id', $department)
            ->where('a.name', $userName)
            ->where('pwd', $pass)
            ->find();
    }
    //修改密码    
    public function changePass()
    {
        $db = db('users');
        $userName = input('uname');
        $pass = input('pass');
        //更新密码
        return $db->where('uname', $userName)->update(['pwd' => $pass]);
    }
    //添加用户
    public function addUser()
    {
        $db = db('users');
        //需要验证重名问题，用uname和department_id确定唯一
        $uniq = $db->where('uname', input('uname'))
            ->find();
        if (!$uniq) {
            $department = input('utype') == 0 ? 0 : input('department');
            return $db->insert([
                'uname' => input('uname'),
                'pwd' => md5(123456),
                'last_login_time' =>  time(),
                'utype' => input('utype'),
                'department_id' => $department
            ]);
        } else {
            return false;
        }
    }
    //删除用户
    public function delUser()
    {
        $db = db('users');
        return $db->delete(input('id'));
    }
    //重置密码
    public function resetPwd()
    {
        $db = db('users');
        return $db->where('id', input('id'))->update([
            'pwd' => md5(123456)
        ]);
    }
    //更改用户类别
    public function changeType()
    {
        $db = db('users');
        //需要验证重名问题，用uname和department_id确定唯一
        $uniq = $db->where('uname', input('uname'))
            ->where('department_id', input('department'))
            ->find();
        if (!$uniq) {
            $department = input('utype') == 0 ? null : input('department');
            return $db->where('id', input('id'))->update([
                'utype' => input('utype'),
                'department_id' => $department
            ]);
        } else {
            return false;
        }
    }
}
