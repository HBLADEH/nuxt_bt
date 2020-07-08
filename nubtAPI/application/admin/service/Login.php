<?php
namespace app\admin\service;

/**
 * 管理员服务层
 */
class Login extends BaseService {

  public $thisModel; // 全局模型

  /**
   * 构造函数,用于给全局变量实例化
   */
  public function __construct() {
      $this->thisModel = model('Admin');
  }
}