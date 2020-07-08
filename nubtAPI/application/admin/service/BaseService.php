<?php
namespace app\admin\service;


class BaseService {
  /**
   * 把收到的数据转换成 LayTable 所需要的格式
   * @param  array $data 获取到的数据
   * @return json LayTable 所需要的json 
   */
  public function dataToJSON($data) {
    $result = array(
        'code'=> 0,
        'msg'=> '',
        'count'=> count($data),
        'data'=> $data,
    );
    return $result;
  }

  /**
   * 把指定字符串进行 md5 加密
   * @param  string $str 指定字符串
   * @return string      加密后字符串
   */
  public function strToMd5($str) {
    return md5($str.'bdvC');
  }

  /**
   * 跟据传入的 id 或者 list 来查找数据
   * @param  int $data 传入的 id 或者 list
   * @return data     返回的数据
   */
  public function findDataByIdOrList($data, $getData=true) {
    $result = $this->thisModel->get($data);
    if ($result) {
      if ($getData) {
        $result = $result->getData();
      }
    } else {
      $result = null;
    }
    return $result; // 返回原始数据
  }

  /**
   * 跟据传入的 id 或者 list 来查找数据
   * @param  int $data 传入的 id 或者 list
   * @return data     返回的数据
   */
  public function allDataByIdOrList($data) {
    return $this->thisModel->all($data);
  }

  /**
   * 跟据一个列名查找所有数据 (返回 id,列名)
   * @param  [type] $column [description]
   * @return [type]         [description]
   */
  public function findBySigleColumn($column) {
    $id = "id";
    if ($column) {
      $id .= ",$column";
    }
    $list = $this->thisModel->field($id)->select();
    return $list;
  }

  /**
   * 新增数据
   * @param  array $data 数据数组
   * @return json       是否成功
   */
  public function insert($data) {
    return $this->thisModel->data($data)->save(); // 保存数据
  }

  /**
   * 新增数据 返回 插入数据的 id
   * @param  array $data 数据数组
   * @return json       是否成功
   */
  public function insertReturnId($data) {
    return $this->thisModel->insertGetId($data); // 保存数据
  }

  /**
   * 根据传入 data 修改数据
   * @param  array $data 数据(需要有 id )
   * @return bool       是否修改
   */
  public function update($data) {
    $getData = $this->thisModel->get($data['id']); // 首先跟据 id 获取到数据
    unset($data['id']); // 去除数组中的 id 可少一次循环
    /** @var array 遍历要修改的数据,把每个新的数据赋值到之前搜索到的数据中 */
    foreach ($data as $key=>$value) {
      $getData[$key] = $value;
    }
    return $getData->save(); // 保存更改
  }

  /**
   * 删除
   * @param  string $id id
   * @return bool     是否成功
   */
  public function delete($id) {
    return $this->thisModel->destroy($id);
  }

  /**
   * laytable 用的查询函数,用于查询后台表格信息
   * @param  arrayo $map   查询条件
   * @param  string $page  当前页码
   * @param  string $limit 一页显示条数
   * @return array        处理好的数据,需要以数组形式返回给 laytable
   */
  public function laytableSearch($map, $page, $limit,$order="id") {
    $list = $this->dataToJSON($this->thisModel->where($map)->page($page,$limit)->order($order, 'desc')->select()); // 查询所有数据，并转换成指定的 Json 格式
    $list['count'] = $this->thisModel->where($map)->count(); // 数量要和上面的查询条件一致
    return $list;
  }

  /**
   * laytable 用的查询函数,用于查询后台表格信息
   * @param  arrayo $has   关联的函数
   * @param  arrayo $map   查询条件
   * @param  string $page  当前页码
   * @param  string $limit 一页显示条数
   * @return array        处理好的数据,需要以数组形式返回给 laytable
   */
  public function laytableHasSearch($has, $map, $page, $limit) {
    $list = $this->dataToJSON($this->thisModel->hasWhere($has, $map)->page($page, $limit)->order('id', 'desc')->select()); // 查询所有数据，并转换成指定的 Json 格式
    $list['count'] = $this->thisModel->hasWhere($has, $map)->count(); // 数量要和上面的查询条件一致
    return $list;
  }

  public function getAllCount() {
    $count = $this->thisModel->count();
    return $count;
  }
}