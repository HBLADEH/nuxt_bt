<?php

namespace app\admin\controller;

use app\admin\controller\BaseController;
use app\admin\service\Admin as AdminService;
use think\Session;

/**
 * 首页模块
 */
class Index extends BaseController
{
  /**
   *  显示主页,在这里先进行权限查询
   */
  public function index()
  {
    /* 获取当前用户权限信息传入前台 */
    $token = Session::get('userToken');
    $AdminService = new AdminService();
    $permissions = $AdminService->getPermissionsByToken($token);
    $this->assign("permissions", $permissions['permissions']);
    $this->assign("username", $permissions['username']);

    return $this->fetch();
  }

  public function welcome()
  {
    $token = Session::get('userToken');
    $AdminService = new AdminService();
    $role = $AdminService->getWelcome($token);
    $this->assign("role", $role);
    return $this->fetch();
  }

  public function passwordChange()
  {
    return $this->fetch();
  }


  /**
   * index 的修改密码, 需要提供原密码才行嗷
   */
  public function doChangePassWord()
  {
    $result = $this->createResultJSON();
    $AdminService = new AdminService();
    $data = $_POST['data'];
    $token = Session::get('userToken');

    $admin = $AdminService->findByToken($token);

    /** @var sring 密码需要进行 md5 转换 */
    $old_password = $AdminService->strToMd5($data['old_password']);

    $new_password = $AdminService->strToMd5($data['new_password']);
    /** 先判断旧密码是否一致 */
    if ($admin['password'] != $old_password) {
      $result['success'] = false;
      $result['msg'] = '旧密码不和原来相同！';
    } else {
      $map['id'] = $admin['id'];
      $map['password'] = $new_password;

      /** 执行修改操作 */
      $state = $AdminService->update($map);

      /** 判断 State 状态 */
      $result = $this->checkState($result, $state);
    }
    return $result;
  }


  /**
   * 获取系统信息
   */
  public function getSysInfo()
  {
    // // 服务器IP地址
    // echo $_SERVER['SERVER_ADDR'];

    // // 服务器域名    
    // echo $_SERVER['SERVER_NAME'];

    // // 服务器端口    
    // echo $_SERVER['SERVER_PORT'];

    // // 服务器版本     
    // echo php_uname('s') . php_uname('r');

    // // 服务器操作系统  
    // echo php_uname();

    // // PHP版本         
    echo PHP_VERSION;

    // // 获取PHP安装路径：         
    // echo DEFAULT_INCLUDE_PATH;

    // // 获取当前文件绝对路径：    
    // echo __FILE__;

    // // 获取Http请求中Host值： 
    // echo $_SERVER["HTTP_HOST"];

    // // 获取Zend版本： 
    // echo Zend_Version();


    // // PHP运行方式 
    // echo php_sapi_name();

    // // 服务器当前时间 
    // echo date("Y-m-d H:i:s");

    // // 最大上传限制  
    // echo get_cfg_var("upload_max_filesize");

    // // 最大执行时间  
    // echo get_cfg_var("max_execution_time") . "秒 ";

    // // 脚本运行占用最大内存  
    // echo get_cfg_var("memory_limit") ? get_cfg_var("memory_limit") : "无";

    // // 获取服务器解译引擎 / 运行环境    
    // echo $_SERVER['SERVER_SOFTWARE'];

    // // 获取服务器CPU数量     
    // echo $_SERVER['PROCESSOR_IDENTIFIER'];

    // // 获取服务器系统目录
    // echo $_SERVER['SystemRoot'];

    // // 获取服务器域名（主机名）
    // echo $_SERVER["HTTP_HOST"];

    // // 获取用户域名
    // echo $_SERVER['USERDOMAIN'];

    // // 获取服务器语言       
    // echo $_SERVER['HTTP_ACCEPT_LANGUAGE'];

    // // 获取服务器Web端口    
    // echo $_SERVER['SERVER_PORT'];

    // // 获取请求页面时通信协议的名称和版本  
    // echo $_SERVER['SERVER_PROTOCOL'];
  }
}
