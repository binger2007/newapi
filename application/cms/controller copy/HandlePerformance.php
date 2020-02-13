<?php

namespace app\cms\controller;

class HandlePerformance
{
    //添加日志
    public function add()
    {
        $db = db('performance');
        $uniq = $db
            ->where('belong_id', input('belong_id'))
            ->where('pubdate', input('pubdate'))
            ->find();
        $arr = input();
        if ($arr['am'] == '早') {
            $arr['temp_am'] =  $arr['temp'];
            $arr['cough_am'] =  $arr['cough'];
            $arr['qicu_am'] =  $arr['qicu'];
        } else {
            $arr['temp_pm'] =  $arr['temp'];
            $arr['cough_pm'] =  $arr['cough'];
            $arr['qicu_pm'] =  $arr['qicu'];
        }
        unset($arr['temp']);
        unset($arr['cough']);
        unset($arr['qicu']);
        unset($arr['am']);
        //如果不存在值，插入，否则更新
        if (!$uniq) {
            return $db->insert($arr);
        } else {
            $res = $db->where('belong_id', $arr['belong_id'])
                ->where('pubdate', $arr['pubdate']);
            if (input('am') == '早') {
                return $res->update([
                    'temp_am'  => $arr['temp_am'],
                    'cough_am'  => $arr['cough_am'],
                    'qicu_am'  => $arr['qicu_am'],
                    'address'  => $arr['address'],
                    'remark'  => $arr['remark']
                ]);
            } else {
                return  $res->update([
                    'temp_pm'  => $arr['temp_pm'],
                    'cough_pm'  => $arr['cough_pm'],
                    'qicu_pm'  => $arr['qicu_pm'],
                    'address'  => $arr['address'],
                    'remark'  => $arr['remark']
                ]);
            }
        }
    }
    //加载人员信息
    public function load()
    {
        $db = db('performance');
        return  $db->where('person_id', input('id'))
            //查询
            ->select();
    }
}
