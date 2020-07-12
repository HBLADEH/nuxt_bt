<?php

namespace app\util;

use app\admin\service\Admin as AdminService;
use think\Session;

/**
 * 
 * 权限验证类
 * 
 */
class Security
{
  /**
   * 根据传入的 permission 确认权限
   * @param int $permission 权限
   * @return bool 是否可以使用
   */
  public function checkPermission($permission)
  {
    // $headers  = get_headers();
    // foreach ($headers as $header => $value) {
    //   echo "$header: $value <br />\n";
    // }
    // exit();
    $token = Session::get("userToken");
    $AdminService = new AdminService();
    $permissions = $AdminService->getPermissionsByToken($token);

    /** 判断所需的权限是否在获取到的权限数组里 */
    return in_array($permission, $permissions['permissions']);
  }
}
