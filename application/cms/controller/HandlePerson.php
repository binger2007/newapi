<?php

namespace app\cms\controller;

use app\cms\model\Person;
use think\Request;

class HandlePerson
{

    //加载人员
    public function loadPerson()
    {
        $departmentId = input('departmentId');
        $map = "(a.department_id = $departmentId OR b.p_ids LIKE '%," . $departmentId . ",%')";
        if (input('authed')) {
            $authed = input('authed');
            $map .= " AND a.authed = $authed";
        }
        $res = Person::with('performance')
            ->alias("a") //取一个别名
            //与department表进行关联，取名b，并且a表的department_id字段等于department表的id字段
            ->join('department b', 'a.department_id = b.id')
            //想要的字段
            ->field('a.*,b.label,b.p_id,b.p_ids')
            ->where($map)
            ->order('b.p_ids asc, b.sort_num asc')
            //查询
            ->select();
        return $res;
    }
    //加载人员,微信小程序加载,后面都要改成这个接口
    public function loadPersonData()
    {
        $openid = input('openid');
        $res = Person::with('performance')
            ->where('openid', $openid)
            //查询
            ->find();
        if ($res) {
            return $res;
        } else {
            return false;
        }
    }
    //添加人员
    public function addPerson()
    {
        $db = db('person');
        //一个单位里面不允许出现重名，不同单位允许有重名 
        $uniq = $db->where('name', input('name'))
            ->where('department_id', input('department_id'))
            ->find();
        if (!$uniq) {
            $arr = input();
            $arr['pwd'] = md5(123456);
            return $db->insert($arr);
        } else {
            return false;
        }
    }

    //通过微信添加用户
    public function addPersonByWx()
    {
        $db = db('person');
        //一个单位里面不允许出现重名，不同单位允许有重名 
        $uniq = $db->where('openid', input('openid'))
            ->find();
        if (!$uniq) {
            $arr = input();
            $arr['pwd'] = md5(123456);
            return $db->insert($arr);
        } else {
            return false;
        }
    }
    //审核人员

    public function changeAuditStatus()
    {
        return db('person')->where('openid', input('openid'))->update(['authed' => input('type')]);
    }
    //删除人员
    public function delPerson()
    {
        //还要删除表现数据
        return  db('person')->delete(input('id')) && db('performance')->where('belong_id', input('id'))->delete();;
    }
    //编辑人员
    public function editPerson()
    {
        $db = db('person');
        //需要验证重名问题，用uname和department_id确定唯一
        $uniq = $db->where('name', input('name'))
            ->where('department_id', input('department_id'))
            ->where('Id', '<>', input('Id'))
            ->find();
        $arr = input();

        unset($arr['dishao']);
        unset($arr['cough']);
        unset($arr['qicu']);
        unset($arr['gaoshao']);
        if (!$uniq) {
            return $db->update($arr);
        } else {
            return false;
        }
    }
    //编辑人员状态
    public function editStatus()
    {
        $db = db('person');
        return $db->where('id', input('id'))->update([
            'status' => input('status')
        ]);
    }
    //编辑人员状态
    public function editStatusByWx()
    {
        $db = db('person');
        $arr = input();
        unset($arr['performance']);
        return  $db->where('openid', input('openid'))->update($arr);
    }
}
