<?php

namespace app\util;

class TokenUtil
{
  /**
   * token 验证
   * @param  string $token  
   * @return json  
   */
  public function checkToken($token)
  {
    $admin = new \app\admin\model\Admin();
    $res = $admin->field('token_timeout')->where('token', $token)->select();
    if (!empty($res)) {
      $token_timeout = $res[0]['token_timeout'];
      // dump($res);
      /* 如果 当前时间超过了过期时间 */
      if (time() - $token_timeout > 0) {
        return 201; // token 已过期
      }
      $new_timeout = time() + 604800; // 新过期时间 = 当前时间 + 7 天
      $res = $admin->isUpdate(true)->where('token', $token)->update(['token_timeout' => $new_timeout]); // 刷新 token 过期时间

      if ($res) {
        return 200; // 200 成功
      }
    } else {
      return 202; // 202 token 不存在
    }
  }

  /**
   * 生成 token
   * @param  string $seed 种子,可不填
   * @return string token
   */
  public function createToken($seed = '')
  {
    $str = md5(uniqid(md5($seed . microtime(true)), true)); // 生成一个不会重复的字符串
    $str = sha1($str); // 加密
    return $str;
  }

}
