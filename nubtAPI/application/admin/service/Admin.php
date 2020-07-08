<?php

namespace app\admin\service;

use app\admin\model\RoleToPermission;
use app\admin\model\AdminToRole;
use app\admin\model\Role;

/**
 * 管理员服务层
 */
class Admin extends BaseService
{

  public $thisModel; // 全局模型

  /**
   * 构造函数,用于给全局变量实例化
   */
  public function __construct()
  {
    $this->thisModel = model('Admin');
  }

  /**
   * 根据 token 获取权限数组
   * @param  string $token 
   * @return array Permissions
   */
  public function getPermissionsByToken($token)
  {
    $admin = $this->findByToken($token);
    $roleid = $this->getRoleidById($admin['id']);
    $permissions = $this->getPermissionsByRoleid($roleid);
    $ret['username'] = $admin['username'];
    foreach ($permissions as $key => $value) {
      $ret['permissions'][] = $value['permissionid'];
    }
    return $ret;
  }

  /**
   * 根据 token 获取 Admin
   */
  public function findByToken($token)
  {
    $res = $this->thisModel->where('token', $token)->find();
    return $res;
  }

  /**
   * 根据 Admin id 去查询对应的 Roleid
   */
  public function getRoleidById($adminid)
  {
    $Role = new AdminToRole();
    $res = $Role->field('roleid')->where('adminid', $adminid)->find();
    return $res['roleid'];
  }

  /**
   * 根据 Admin id 去查询对应的 Roleid
   */
  public function getRoleById($id)
  {
    $Role = new Role();
    $res = $Role->where('id', $id)->find();
    return $res;
  }

  /**
   * 根据 Role id 去查询对应的 Permissions
   */
  public function getPermissionsByRoleid($roleid)
  {
    $RoleToPermission = new RoleToPermission();
    $res = $RoleToPermission->field('permissionid')->where('roleid', $roleid)->select();
    return $res;
  }


  /**
   * 获取所有的角色数组
   */
  public function getAllRole()
  {
    return Role::all();
  }

  /**
   * 获取当前用户的角色
   */
  public function getCurrentRole($adminid)
  {
    $AdminToRole = new AdminToRole();
    return $AdminToRole->field('roleid')->where('adminid', $adminid)->find();
  }


  public function getWelcome($token)
  {
    $admin = $this->findByToken($token);
    $roleid = $this->getRoleidById($admin['id']);
    $role = $this->getRoleById($roleid);
    $role['username'] = $admin['username'];
    return $role;
  }

  /**
   * 添加权限 (暂时弃用)
   */
  public function addRole($data)
  {
    $AdminToRole = new AdminToRole();
    return $AdminToRole->data($data)->save();
  }

  /**
   * 更新权限 (暂时弃用)
   */
  public function updateRole($adminid, $roleid)
  {
    $AdminToRole = new AdminToRole();
    return $AdminToRole->where('adminid', $adminid)->update([
      'roleid' => $roleid
    ]);
  }
}
