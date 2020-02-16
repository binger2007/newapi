<?php

namespace app\cmsv3\controller;

use think\Db;
use think\Controller;
use think\Request;
use app\cmsv3\model\Department;
use think\console\output\descriptor\Console;

class HandleDepartment
{
    //加载单位信息
    public function loadDepartment()
    {
        $db = db('department');
        $departmentId = input('departmentId');
        if ($departmentId) {
            $arr = getChilds(array($departmentId));
            array_push($arr, (int) $departmentId);
            $db->whereOr('id', 'IN', $arr);
        }
        return $db->order('sort_num asc')->select();
    }

    //加载单位信息，并包含了单位的得分情况
    public function departmentDataForTable()
    {
        return Department::with('performance')
            ->where('p_id', input('departmentId'))
            ->order('sort_num asc')
            //查询
            ->select();
    }
    //增加单位
    public function addDepartment()
    {
        $db = db('department');
        return $db->insert([
            'p_id' => input('pid'),
            'label' => input('cname'),
            'create_time' => time()
        ]);
    }
    //删除单位
    public function delDepartment()
    {
        $db = db('department');
        $id =  input('id');
        // 删除所有子节点，并且子节点下所有人和管理员，人的话department_id置空，用户的话直接删除
        $ids = getChilds(array($id));
        array_push($ids, (int) $id);
        // 删除单位
        $db->where('id', 'IN', $ids)->delete();
        //删除单位管理员
        db('users')->where('department_id', 'IN', $ids)->delete();
        //将单位下属人员和人员状态置为初始化
        db('person')->where('department_id', 'IN', $ids)->update([
            'department_id' => null,
            'authed' => null
        ]);
        return true;
    }
    //编辑单位
    public function editDepartment()
    {
        $db = db('department');
        return $db->where('id', input('id'))->update([
            'label' => input('cname')
        ]);
    }
    //处理单位排序
    public function sortDepartment()
    {
        Db::transaction(function () {
            $db = db('department');
            $draggingNodeId = input('draggingNodeId'); //拖拽的节点 
            $pid = input('pid'); //父节点路径
            $brotherIds = json_decode(input('brotherIds')); //兄弟节点，需要处理兄弟间的排序 
            //首先处理兄弟间的排序
            for ($i = 0, $len = count($brotherIds); $i < $len; $i++) {
                $db->where('id', $brotherIds[$i])->update([
                    'sort_num' => $i
                ]);
            }
            //处理被拖动节点
            $db->where('id', $draggingNodeId)->update([
                'p_id' => $pid
            ]);
        });
        return true;
    }
}
