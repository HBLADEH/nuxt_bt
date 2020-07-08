<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
define('WXPAY_PATH', __DIR__ . '/../application/wxpay/');
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';

//防CC攻击
sheli_cc();

function sheli_cc()
{
    //代理IP直接退出
    empty($_SERVER['HTTP_VIA']) or exit('Access Denied');
    //防止快速刷新
    session_start();
    $seconds = '60'; //时间段[秒]
    $refresh = '40'; //刷新次数
    //设置监控变量
    $cur_time = time();
    if (isset($_SESSION['last_time'])) {
        $_SESSION['refresh_times'] += 1;
    } else {
        $_SESSION['refresh_times'] = 1;
        $_SESSION['last_time'] = $cur_time;
    }
    //处理监控结果
    if ($cur_time - $_SESSION['last_time'] < $seconds) {
        if ($_SESSION['refresh_times'] >= $refresh) {
            //跳转至攻击者服务器地址
            //header(sprintf('Location:%s', 'http://127.0.0.1'));  
            exit('请求频率太快，稍候' . $seconds . '秒后再访问！');
        }
    } else {
        $_SESSION['refresh_times'] = 0;
        $_SESSION['last_time'] = $cur_time;
    }
}
