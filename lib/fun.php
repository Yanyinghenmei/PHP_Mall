<?php

/**
 * 初始化数据库
 */
function mysqlInit($host,$user,$password,$db_name) {
  $connect = mysql_connect($host,$user,$password);
  if (!$connect) {
    return false;
  }

  mysql_select_db("{$db_name}");
  mysql_set_charset('utf8');
  return $connect;
}

/**
 * 密码加密
 */
function createPassword($password) {
  if (!$password) {
    return false;
  }
  return md5(md5($password).'Daniel');
}

/**
 * 消息提示
 * @param  int $type 1:成功 2:失败
 * @param  string $msg  消息
 * @param  string $url  跳转的url
 */
function msg($type,$msg=null,$url=null) {
  $toUrl = "Location: msg.php?type={$type}";
  $toUrl.= $msg?"&msg={$msg}":'';
  $toUrl.= $url?"&url={$url}":'';
  header($toUrl);
  exit;
}
