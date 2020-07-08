<?php
namespace app\admin\service;

/**
 * 用户电话服务层
 */
class UserPhone extends BaseService {

  public $thisModel; // 全局模型

  /**
   * 构造函数,用于给全局变量实例化
   */
  public function __construct() {
      $this->thisModel = model('UserPhone');
  }
}