<?php
namespace app\admin\model;

use think\Model;

class BaseModel extends Model {
  /**
   * 把时间戳转换为 Date 格式
   * @param  bigint $value 时间戳
   * @return Date        Date
   */
  // public function getCreatetimeAttr ($value) {
  //   return date('Y-m-d h:i:s',$value);
  // }
}