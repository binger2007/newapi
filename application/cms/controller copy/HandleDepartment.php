<?php

namespace app\cms\controller;

use think\Db;
use think\Controller;
use think\Request;
use app\cms\model\Department;

class HandleDepartment
{

    //加载单位信息
    public function loadDepartment()
    {
        $db = db('department');
        $departmentId = input('departmentId');
        if ($departmentId) {
            $db->where('id', $departmentId)->whereOr('p_ids', 'LIKE', "%,$departmentId,%");
        }
        return $db->order('sort_num asc')->select();
    }
    //加载单位信息，并包含了单位的得分情况
    public function departmentDataForTable()
    {
        return Department::with('performance')
            ->where('p_id', input('departmentId'))
            ->order('p_ids asc, sort_num asc')
            //查询
            ->select();
    }
    //增加单位
    public function addDepartment()
    {
        $db = db('department');
        return $db->insert([
            'p_id' => input('pid'),
            'p_ids' => input('pids') . ',',
            'label' => input('cname'),
            'create_time' => time()
        ]);
    }
    //删除单位
    public function delDepartment()
    {
        $db = db('department');
        $id =  input('id');
        return $db->where('id', $id)->whereOr('p_ids', 'LIKE', "%,$id,%")->delete();
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
            $pids = input('pids'); //父节点路径
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
                'p_id' => $pid,
                'p_ids' => $pids
            ]);
        });
        return true;
    }
}
