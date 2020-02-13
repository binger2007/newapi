<?php
namespace app\index\controller;

class Index
{
    public function index($name='world23322')
    {
        return 'hello '.$name.'!';
    }
     public function hello()
    {
        return 'hello';
    }
    public function test()
    {
       return 'test';
    }
}

