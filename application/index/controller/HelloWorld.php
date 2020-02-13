<?php

namespace app\index\controller;

use think\Request;
//use think\Controller;22

class HelloWorld
{
    public function Index($name = 'world', $city = 'tangshan')
    {
        return 'hello2,' . $name . '! you are from ' . $city;
    }
    public function Test($name = 'binger')
    {
        echo 'url: ' . request()->url() . '<br/>';
        return $name;
    }
    public function Test2()
    {
        echo '请求参数：';
        dump(input('get.name'));
        echo 'name:' . input('get.name');
    }
    public function Hello(Request $request)
    {
        echo $request->module();
    }
    public function hello2()
    {
        $data = ['name' => 'thinkphp', 'status' => '1'];
        return $data;
    }
}
