<?php

/**
 * 获取微信用户信息
 * @author: Lucky hypo
 * 第一步：判断有没有code，有code去第三步，没有code去第二步
 * 第二步：用户同意授权，获取code
 * 第三步：通过code换取网页授权access_token
 * 第四步：使用access_token获取用户信息
 */

namespace app\wx\controller;

class Index
{
    private $appid = 'wx2c8f6205e0511a3f';
    private $appsecret = '0cd4178fdc326623aae1de8941d3738c';

    // 后台接入验证
    public function check_access()
    {
        $arr = input();
        db('access_token')
            ->insert(['token' =>  json_encode($arr)]);
        //微信定义的token
        $token = 'xxzxB105_2012';
        //微信公众号后台请求传过来的数据
        $timestamp = $_GET['timestamp'];
        $signature = $_GET['signature'];
        $nonce = $_GET['nonce'];
        $echostr = $_GET['echostr'];
        //$token $timestamp $nonce 拼接数组然后排序 然后在转换成字符串 在sha1加密 获取签名
        //拼接到数组
        $arr = [$token, $timestamp, $nonce];
        //进行排序
        sort($arr);
        //加密
        $arr = sha1(implode($arr));
        //判断数据是否匹配
        if ($arr == $signature) {
            echo $echostr;
            exit;
        }
    }

    //获取微信客户端事件数据
    public function get_custom_data()
    {
        $arr = input();
        return db('access_token')
            ->insert(['token' =>  json_encode($arr)]);
    }


    //获取用户access_token，并保存到数据库，已设置好定时刷新
    public function get_token()
    {

        $appid = $this->appid;
        $appsecret = $this->appsecret;
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
        $access_token = json_decode(file_get_contents($url), true);


        return db('access_token')->where('id', 1)->update(['token' =>  $access_token['access_token']]);
    }

    public function get_user_all($next_openid = null)
    {
        $access_token = db('access_token')->where('id', 1)->value('token');
        $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=$access_token";
        if ($next_openid) {
            $url .= "&next_openid=$next_openid";
        }
        return file_get_contents($url);
    }

    /**
     * 4、使用access_token获取用户信息
     * @param string access_token
     * @param string 用户的openid
     * @return array 用户信息数组
     */
    public function get_user_info($openid)
    {
        $access_token = db('access_token')->where('id', 1)->value('token');
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $access_token . '&openid=' . $openid . '&lang=zh_CN';
        return file_get_contents($url);
    }



    // /**
    //  * 1、获取微信用户信息，判断有没有code，有使用code换取access_token，没有去获取code。
    //  * @return array 微信用户信息数组
    //  */
    // public function get_user_all()
    // {
    //     // return 111;
    //     // die;
    //     if (!isset($_GET['code'])) { //没有code，去微信接口获取code码
    //         $callback = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']; //微信服务器回调url，这里是本页url;注意：：这里回调的url是http://www.klda.com/index.php。如果想要获取完整的路径用 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']

    //         $this->get_code($callback);
    //     } else { //获取code后跳转回来到这里了
    //         $code = $_GET['code'];
    //         $data = $this->get_access_token($code); //获取网页授权access_token和用户openid
    //         $data_all = $this->get_user_info($data['access_token'], $data['openid']); //获取微信用户信息
    //         echo '<pre>';
    //         print_r($data_all);
    //         die;
    //         return json($data_all);
    //     }
    // }
    /**
     * 2、用户授权并获取code
     * @param string $callback 微信服务器回调链接url
     */
    private function get_code($callback)
    {
        $appid = $this->appid;
        $scope = 'snsapi_userinfo';
        $state = md5(uniqid(rand(), TRUE)); //唯一ID标识符绝对不会重复
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $appid . '&redirect_uri=' . urlencode($callback) . '&response_type=code&scope=' . $scope . '&state=' . $state . '#wechat_redirect';
        echo $url;
        header("Location:$url");
    }

    /**
     * 3、使用code换取access_token
     * @param string 用于换取access_token的code，微信提供
     * @return array access_token和用户openid数组
     */
    private function get_access_token($code)
    {
        $appid = $this->appid;
        $appsecret = $this->appsecret;
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appid . '&secret=' . $appsecret . '&code=' . $code . '&grant_type=authorization_code';
        $user = json_decode(file_get_contents($url));
        if (isset($user->errcode)) {
            echo 'error:' . $user->errcode . '<hr>msg  :' . $user->errmsg;
            exit;
        }
        $data = json_decode(json_encode($user), true); //返回的json数组转换成array数组
        return $data;
    }
}
