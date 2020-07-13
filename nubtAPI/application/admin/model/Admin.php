<?php

namespace app\admin\model;

use app\admin\model\Role;
use think\model\Merge;
use think\Db;

class Admin extends Merge
{

  // 定义关联模型列表
  protected $relationModel = ['AdminToRole'];
  // 定义关联外键
  protected $fk = 'adminid';
  protected $mapFields = [
    // 为混淆字段定义映射
    'id'        =>  'Admin.id',
    'atrid' =>  'AdminToRole.id',
  ];

  /**
   * 显示用户角色名称
   */
  public function getJustAttr($value, $data)
  {

    $res = Db::table('nt_admin_to_role')
      ->alias('ar')
      ->join('nt_role r', 'ar.roleid = r.id')
      ->where('ar.adminid', $data['id'])
      ->find();

    return $res['role_name'];
  }
  /**
   * 设置锁定字样
   * @param  bigint $value 锁定值
   * @return stirng 状态
   */
  public function getIsLockAttr($value)
  {
    if ($value >= 5) {
      $status = '禁用';
    } else {
      $status = '未禁用';
    }

    return $status;
  }
}
