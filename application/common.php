<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
//获取所有子节点
function getChilds($p_id = array(46))
{
    static  $id_arr = array();
    $id_arr = db('department')->where('p_id', 'in', $p_id)->column('id');
    if (!empty($id_arr)) {
        $id_arr = array_merge($id_arr, getChilds($id_arr));
    }
    return $id_arr;
}
