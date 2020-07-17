<?php

namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\Exception;

/**
 * 电话号码
 */
class Relation extends BaseController
{
  /**
   * 初始化函数,用于给全局变量实例化
   */
  public function __construct()
  {
    parent::__construct(); // 必须添加,需要执行父类的初始化函数,不管有没有
    $this->thisService = model('Relation', 'service');
  }
  public function index()
  {
    return $this->fetch();
  }

  

  public function idcard($id)
  {
    // echo $id;
    $data = $this->thisService->findDataByIdOrList($id);

    $this->assign('data', $data); //打印数组
    return $this->fetch();
  }

  public function Findall()
  { 
    //找到全部加模糊查询
    // $ip = $_GET['ip']; //ip地址
    // $userphone = $_GET['userphone']; //电话号码
    // $createtime = $_GET['createtime']; //创建时间
    $limit = $_GET['limit'];
     $page = $_GET['page'];
   
    $order=$this->request->param('order');
    $map = [
      'order' =>  ['like', '%' . $order . '%'],
    ];

    $_SESSION['oldcount'] = $this->thisService->getAllCount(); //获取行数 提示音

    $_SESSION['times'] = 0; // 第一次前后台差异为0
    
    $data = $this->thisService->laytableSearch($map, $page, $limit);

    return json($data);
  }

  public function doadd()
  {
    $result = $this->createResultJSON();
    $data = $_POST;
    $data['purchase_time'] = date("Y-m-d h:i:s");
    $state = $this->thisService->insert($data);
    /** 判断 State 状态 */
    $result = $this->checkState($result, $state);

    return json($result);
  }
  public function send_workman()
  {

    $arr = array(
      'mas' => '成功',
      'succsu' => true
    );
    echo json_encode($arr);
  }


  public function phonelimit($data)
  {
    return $this->thisService->thisModel->where('userphone', $data['userphone'])->update(['limit' => '1']);
  }
  public function dolimit()
  {
    $result = $this->createResultJSON();
    $data['userphone'] = $_POST['userphone'];
    $state = $this->phonelimit($data);
    $result = $this->checkState($result, $state);


    return $result;
  }

  public function doPass()
  {
    $result = $this->createResultJSON();
    $data['id'] = $_POST['id'];
    $data['pass'] = 1;
    $state = $this->thisService->update($data);
    $result = $this->checkState($result, $state);
    return $result;
  }


  /**
   * 执行删除
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

  public function checkCount()
  {
    // $oldCount = $_GET['']
    $mag = $_POST['mag'];

    $result = $this->createResultJSON();
    $datacount = $this->thisService->getAllCount(); //获取行数

    $result['data']['datacount'] = $datacount;

    $newtimes = $datacount - $_SESSION['oldcount'];
    if ($_SESSION['times'] < $newtimes) {
      $_SESSION['times'] = $newtimes;
      $result["msg"] = 'true';
    } else {
      $result["msg"] = 'false';
    }

    return $result;
  }
}
