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


  public function getWelcomeInfo() {
    $result = $this->createResultJSON();
    $infoList = array(
      'SERVER_ADDR' => $_SERVER['SERVER_ADDR'],
      'SERVER_NAME' => $_SERVER['SERVER_NAME'],
      'SERVER_PORT' => $_SERVER['SERVER_PORT'],
      'SERVER_VERSION' => php_uname('s').php_uname('r'),
      'SERVER_SYSTEM' => php_uname(),
      'PHP_VERSION' => PHP_VERSION,
      'TP_VERSION' => THINK_VERSION,
      'TP_LANGUAGE' => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
      'TP_RUNNINGWAY' => php_sapi_name(),
    );
    $result['success'] = true;
    $result['code'] = 200;
    $result['data'] = $infoList;
    return json($result);
  }
}
