<?php

namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\Session;
use think\Exception;
use app\util\TokenUtil;

/**
 * 登录模块
 */
class Login extends BaseController
{

  public $thisService; // 全局服务

  /**
   * 初始化函数,用于给全局变量实例化
   */
  public function __construct()
  {
    parent::__construct(); // 必须添加,需要执行父类的初始化函数,不管有没有
    $this->thisService = model('Login', 'service');
  }
  /**
   * 验证登录
   * @return json 是否成功
   */
  public function checkLogin()
  {
    $result = $this->createResultJSON();

    $data = $_POST;

    $username = $data['username'];

    $password = $this->thisService->strToMd5($data['password']);

    $check_list['username'] = $username;

    $adminData = $this->thisService->findDataByIdOrList($check_list);
    if ($adminData) {
      $is_lock = $adminData['is_lock'];
      if ($is_lock >= 5) {
        $result['success'] = false;
        $result['msg'] = '账号已被锁定,请联系管理员解锁！';
        return $result;
      }
      if ($adminData['password'] == $password) {
        $tokenUtil = new TokenUtil;
        $token = $tokenUtil->createToken($username);

        $adminData['token'] = $token;
        $adminData['is_lock'] = 0;
        $adminData['token_timeout'] = strtotime("+7 days");
        $this->thisService->update($adminData);

        // Session::set('userToken', $token); // 设置 token
        $result['data'] = [
          'token' => $token,
          'username' => $username,
          'id' => $adminData['id'],
        ];
        $result['success'] = true;
        $result['msg'] = '登录成功！';
      } else {
        $adminData['is_lock']++;
        $this->thisService->update($adminData);
        $result['success'] = false;
        $result['msg'] = '密码错误,已经输入错误' . $adminData['is_lock'] . '次,输入错误5次将锁定账户!';
      }
    } else {
      $result['success'] = false;
      $result['msg'] = '用户不存在';
    }

    return json($result);
  }

  public function loginOut()
  {
    $result = $this->createResultJSON();

    Session::delete('userToken'); // 销毁 token
    $result['success'] = true;
    $result['msg'] = '下线成功！';

    return $result;
  }
}
