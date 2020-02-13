<?php

namespace app\cms\controller;

use think\Controller;
use think\Request;
use app\cms\model\File;

class HandleFile  extends Controller
{
    //保存
    public function upload()
    {
        // return $_FILES;
        // die;
        $image = $_FILES["file"]["tmp_name"];
        $fp = fopen($image, "r");
        $file = fread($fp, $_FILES["file"]["size"]); //二进制数据流
        //保存地址
        $imgDir =    'uploads' . DS . date("Ym") . DS;
        if (!file_exists(ROOT_PATH . $imgDir)) {
            mkdir(ROOT_PATH . $imgDir, 0777, true);
        }
        //要生成的图片名字
        $filename =  md5(time() . mt_rand(10, 99)) . "." . substr($_FILES["file"]["name"], -3); //新图片名称
        $newFilePath =   $imgDir . $filename;
        $data = $file;
        $newFile = fopen(ROOT_PATH . $newFilePath, "w"); //打开文件准备写入
        fwrite($newFile, $data); //写入二进制流到文件
        fclose($newFile); //关闭文件
        //如果是视频的话,获取视频缩略图,并将缩略图写入,windows环境无用
        // $vedio = ROOT_PATH . $newFilePath;
        // $pic = $vedio . ".jpg";
        // $command = "ffmpeg -v 0 -y -i $vedio -vframes 1 -ss 5 -vcodec mjpeg -f rawvideo -s 286x160 -aspect 16:9 $pic ";
        // shell_exec($command);
        //将文件地址写入数据库
        $request = Request::instance();
        $ip = $request->ip();
        $data = [
            'title' =>  $_FILES["file"]["name"],
            'time' => time(),
            'ip' => $ip,
            'did' => input('did'),
            'path' => $newFilePath
        ];
        $file = new File;
        $file->data($data);
        $file->save();
        return $file;
    }



    // 加载图片
    public function load()
    {
        $file = new File;
        return $file->where('did', input('did'))->order('id desc')->select();
    }
    //删除图片
    //删除人员
    public function del()
    {
        //先删除数据库
        $db = db('file');
        $res = $db->delete(input('id'));
        //再删除文件
        $file = ROOT_PATH . input('path');
        if (file_exists($file)) {
            $res2 = unlink($file);
        }
        return $res && $res2;
    }
}
