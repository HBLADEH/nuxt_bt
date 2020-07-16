<?php

namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\Exception;

use app\util\Security;

/**
 * 管理员模块
 */
class Admin extends BaseController
{

  public $thisService; // 全局服务

  /**
   * 初始化函数,用于给全局变量实例化
   */
  public function __construct()
  {
    parent::__construct(); // 必须添加,需要执行父类的初始化函数,不管有没有
    $this->checkPermission(1);
    $this->thisService = model('Admin', 'service');
  }

  public function edit($id)
  {
    /** 获取数据 */
    $data = $this->thisService->findDataByIdOrList($id);
    return json($data);
    // $this->assign('data', $data);
    // return $this->fetch();
  }

  public function passwordChange($id)
  {
    $this->assign('id', $id);
    return $this->fetch();
  }

  /**
   * 寻找所有数据
   */
  public function FindAll()
  {
    $page = $_GET['page'];
    $limit = $_GET['limit'];
    $username = $_GET['username'];


    /** @var array 搜索规则 */
    $map = [
      'username'  =>  ['like', '%' . $username . '%'],
    ];

    /** @var json 调用 laytable 搜索函数 */
    $data = $this->thisService->laytableSearch($map, $page, $limit, "id");
    collection($data['data'])->append(['just'])->toArray(); // 为了显示不存在字段
    return json($data);
  }

  /**
   * 添加数据
   * @return [type] [description]
   */
  public function doAdd()
  {
    $result = $this->createResultJSON();
    $data = $_POST; // 获取数据
    $data['registered_time'] = date("Y-m-d h:i:s");
    $checkList = array(
      'username' => $data['username']
    );

    if ($this->thisService->findDataByIdOrList($checkList) != null) {
      $result['success'] = false;
      $result['msg'] = '添加失败，管理员账号重复！';
    } else {

      $data['password'] = $this->thisService->strToMd5($data['password']); // 密码转换

      $state = $this->thisService->insert($data);

      /** 判断 State 状态 */
      $result = $this->checkState($result, $state);
    }
    return json($result);
  }

  /**
   * 修改数据
   * @return json 修改情况
   */
  public function doEdit()
  {
    $result = $this->createResultJSON();
    $data = $_POST;

    $state = $this->thisService->update($data);

    /** 判断 State 状态 */
    $result = $this->checkState($result, $state);

    return json($result);
  }

  /**
   * 执行删除管理员
   * @return json 是否删除成功
   */
  public function doDelete()
  {
    $result = $this->createResultJSON();
    $id = $_POST['id']; // 获取 ID

    if ($this->thisService->delete($id)) {
      $result['success'] = true;
      $result['msg'] = '删除成功！';
    } else {
      $result['success'] = false;
      $result['msg'] = '删除失败！';
    }

    return json($result);
  }

  /**
   * 执行修改密码操作
   * @return json 是否修改成功
   */
  public function doChangePassWord()
  {
    $result = $this->createResultJSON();
    $id = $_POST['id'];
    /** @var sring 密码需要进行 md5 转换 */
    $old_password = $this->thisService->strToMd5($_POST['old_password']);
    $new_password = $this->thisService->strToMd5($_POST['new_password']);
    $map = [
      'adminid' => $id,
      'password' => $old_password
    ];

    /** 先判断旧密码是否一致 */
    if ($this->thisService->findDataByIdOrList($map)) {
      unset($map['adminid']);
      $map['id'] = $id;
      $map['password'] = $new_password;

      /** 执行修改操作 */
      $state = $this->thisService->update($map);

      /** 判断 State 状态 */
      $result = $this->checkState($result, $state);
    } else {
      $result['success'] = false;
      $result['msg'] = '旧密码不和原来相同！';
    }
    return json($result);
  }
}
