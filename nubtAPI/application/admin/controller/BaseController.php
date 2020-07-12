<?php

namespace app\admin\controller;

use Exception;
use think\Session;
use think\Controller;
use app\util\TokenUtil;
use app\util\Security;

/**
 * 通用模块
 */
class BaseController extends Controller
{

  /**
   * 初始化函数,用于给全局变量实例化
   */
  public function __construct()
  {
    parent::__construct(); // 必须添加,需要执行父类的初始化函数,不管有没有
    $controllerName = $this->request->controller();
    $this->checkIsNeedLogin($controllerName);
  }
  /**
   * 创建通用返回 JSON 数组
   * @return [type] [description]
   */
  public function createResultJSON()
  {
    return array(
      'code' => 0,
      'success' => false,
      'msg' => '',
      'data' => []
    );
  }

  public function arrayToStr($array)
  {
    $str = '';

    /** 把获取到的权限数组变成字符串 */
    foreach ($array as $value) {
      $str .= ',' . $value;
    }

    return substr($str, 1); // 把字符串赋值回原变量,因为字符串第一位有个逗号，故去掉
  }

  /**
   * 当服务层使用 save() 操作时,可以使用该方法来统一返回
   * @param  [type] $result [description]
   * @param  [type] $state  [description]
   * @return [type]         [description]
   */
  public function checkState($result, $state)
  {
    if ($state == false) {
      $result['success'] = false;
      $result['msg'] = '执行操作失败！';
    } else if ($state == 0) {
      $result['success'] = false;
      $result['msg'] = '未改变任何数据！';
    } else {
      $result['success'] = true;
      $result['msg'] = '执行操作成功！';
    }
    return $result;
  }

  /**
   * 上传图片方法
   * @param  string $toURL 图片要上传到的路径
   * @return json      是否成功
   */
  public function uploadImg($toURL)
  {
    $result = $this->createResultJSON();
    $is_success = true;
    $files = request()->file('file');
    $imgSrcs = '';
    dump($files);
    $info = $files->rule('uniqid')->move(ROOT_PATH . 'public/' . $toURL);

    if ($info) {
      $imgSrcs = "/" . $toURL . "/" . $info->getFilename();
    } else {
      $result['msg'] = $files->getError();
      $is_success = false;
    }

    if ($is_success) {
      $result['success'] = true;
      $result['msg'] = '上传成功';
      $result['data'] = $imgSrcs;
    } else {
      $result['success'] = false;
    }
    return json($result);
  }

  /**
   * 上传多图片方法
   * @param  string $toURL 图片要上传到的路径
   * @return json      是否成功
   */
  public function uploadImgs($toURL)
  {
    $result = $this->createResultJSON();
    $is_success = true;
    $imgSrcs = array();
    $files = request()->file('file');

    /** 遍历每个图片,执行上传操作 */
    foreach ($files as $file) {
      // 移动到框架应用根目录/public/$toURL/ 目录下
      $info = $file->rule('uniqid')->move(ROOT_PATH . 'public/' . $toURL); // 上传操作
      /** 是否成功 */
      if ($info) {
        array_push($imgSrcs, "/" . $toURL . "/" . $info->getFilename()); // 记录下图片目录
      } else {
        // 上传失败获取错误信息
        $result['msg'] = $file->getError();
        $is_success = false;
        break;
      }
    }
    if ($is_success) {
      $result['success'] = true;
      $result['errno'] = 0;
      $result['msg'] = '上传成功';
      $result['data'] = $imgSrcs;
    } else {
      $result['success'] = false;
      $result['errno'] = 1;
    }
    return json($result);
  }

  /**
   * 根据传入的图片路径删除对应图片
   * @param  [string] $imgUrl [图片路径]
   * @return [type]         [description]
   */
  public function deleteImg($imgUrl)
  {
    try {
      $result = $this->createResultJSON();
      $deleteUrl = ROOT_PATH . 'public/' . $imgUrl;

      /** 执行删除操作 */
      if (!unlink($deleteUrl)) {
        $result['success'] = false;
        $result['msg'] = "删除失败,路径为: $imgUrl";
      } else {
        $result['msg'] = "删除成功,路径为: $imgUrl";
      }
    } catch (\Exception $e) {
      // $this->error('执行错误');
    }
    return $result;
  }

  /**
   * 根据传入的图片路径数组删除对应图片
   * @param  [array] $imgUrl [图片路径]
   * @return [type]         [description]
   */
  public function deleteImgs($imgUrl)
  {
    try {
      $result = $this->createResultJSON();

      $result['msg'] = "删除成功！"; // 默认是成功

      /** 执行删除操作 */
      foreach ($imgUrl as $value) {
        $deleteUrl = ROOT_PATH . 'public/' . $value;
        if (!unlink($deleteUrl)) {
          $result['success'] = false;
          $result['msg'] = "删除失败,路径为: $imgUrl";
          break;
        }
      }
    } catch (\Exception $e) {
      // $this->error('执行错误');
    }

    return $result;
  }

  /**
   * 检测是否非法访问
   * @param string $controllerName 控制器名
   * @return string
   */
  public function checkIsNeedLogin($controllerName)
  {
    $headers  = $_SERVER;
    foreach ($headers as $header => $value) {
      echo "$header: $value <br />\n";
    }
    exit();
    if ($controllerName != 'Login') {
      if (!(Session::has('userToken'))) {
        $this->error('检测到用户没有登录', '/admin/login');
      } else {
        $tokenUtil = new TokenUtil;
        $token = Session::get('userToken');
        $res_code = $tokenUtil->checkToken($token);
        switch ($res_code) {
          case 201:
            $this->error('用户长时间未使用，请重新登录', '/admin/login');
            break;
          case 202:
            $this->error('未找到该用户信息!', '/admin/login');
            break;
        }
      }
    }
  }


  /**
   * 随机字符
   * @param number $length 长度
   * @param string $type 类型
   * @param number $convert 转换大小写
   * @return string
   */
  function random($length = 6, $type = 'string', $convert = 0)
  {
    $config = array(
      'number' => '1234567890',
      'letter' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
      'string' => 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789',
      'all' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
    );

    if (!isset($config[$type])) $type = 'string';
    $string = $config[$type];

    $code = '';
    $strlen = strlen($string) - 1;
    for ($i = 0; $i < $length; $i++) {
      $code .= $string{
        mt_rand(0, $strlen)};
    }
    if (!empty($convert)) {
      $code = ($convert > 0) ? strtoupper($code) : strtolower($code);
    }
    return $code;
  }

  public function checkPermission($permission)
  {
    Security::checkPermission(1);
    // if (!Security::checkPermission(1)) {
    //   $this->error("检测到该用户没有权限!!!");
    // }
  }
}
