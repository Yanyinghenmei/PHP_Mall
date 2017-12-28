<?php
if (!empty($_POST['username'])) {
  include_once './lib/fun.php';

  // 过滤两边的空格 或使用mysql_real_escape_string();
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);
  $repasword = trim($_POST['repassword']);

  if (!$username) {
    msg(2,'用户名不能为空');
  }
  if (!$password) {
    msg(2,'密码不能为空');
  }
  if ($repasword!==$password) {
    msg(2,'两次密码输入不一致');
  }

  // 数据库操作
  $connect = mysqlInit('localhost','root','root','imooc_mall');
  if (!$connect) {
    echo mysql_errno();exit;
  }

  // 判断用户是否在数据表中存在
  $sql = "SELECT COUNT(`id`) AS total FROM im_user
          WHERE username='{$username}'";
  $obj = mysql_query($sql);
  $res = mysql_fetch_assoc($obj);


  // 用户已存在数据库
  if (isset($res['total'])&&$res['total']>0) {
    msg(2,'用户名已存在,请重新输入');
  }

  unset($obj,$res);

  // 加密
  $password = createPassword($password);
  if (!$password) {
    msg(2,'密码加密失败');
  }
  $sql = "INSERT im_user(username,password,create_time)
          VALUES('{$username}','{$password}','{$_SERVER['REQUEST_TIME']}')";
  $obj = mysql_query($sql);
  if ($obj) {
    $userId = mysql_insert_id(); // 插入成功的主键id
    msg(1,sprintf('注册成功,用户名:%s,用户id:%s',$username,$userId),'login.php');
  } else
    msg(2,mysql_error());
  }
}

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>M-GALLARY|用户注册</title>
    <link type="text/css" rel="stylesheet" href="./static/css/common.css">
    <link type="text/css" rel="stylesheet" href="./static/css/add.css">
    <link rel="stylesheet" type="text/css" href="./static/css/login.css">
</head>
<body>
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
    <div class="auth fr">
        <ul>
            <li><a href="login.php">登录</a></li>
            <li><a href="register.php">注册</a></li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="center">
        <div class="center-login">
            <div class="login-banner">
                <a href="#"><img src="./static/image/login_banner.png" alt=""></a>
            </div>
            <div class="user-login">
                <div class="user-box">
                    <div class="user-title">
                        <p>用户注册</p>
                    </div>
                    <form class="login-table" name="register" id="register-form" action="register.php" method="post">
                        <div class="login-left">
                            <label class="username">用户名</label>
                            <input type="text" class="yhmiput" name="username" placeholder="Username" id="username">
                        </div>
                        <div class="login-right">
                            <label class="passwd">密码</label>
                            <input type="password" class="yhmiput" name="password" placeholder="Password" id="password">
                        </div>
                        <div class="login-right">
                            <label class="passwd">确认</label>
                            <input type="password" class="yhmiput" name="repassword" placeholder="Repassword"
                                   id="repassword">
                        </div>
                        <div class="login-btn">
                            <button type="submit">注册</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="footer">
    <p><span>M-GALLARY</span> ©2017 POWERED BY IMOOC.INC</p>
</div>

</body>
<script src="./static/js/jquery-1.10.2.min.js"></script>
<script src="./static/js/layer/layer.js"></script>
<script>
    $(function () {
        $('#register-form').submit(function () {
            var username = $('#username').val(),
                password = $('#password').val(),
                repassword = $('#repassword').val();
            if (username == '' || username.length <= 0) {
                layer.tips('用户名不能为空', '#username', {time: 2000, tips: 2});
                $('#username').focus();
                return false;
            }

            if (password == '' || password.length <= 0) {
                layer.tips('密码不能为空', '#password', {time: 2000, tips: 2});
                $('#password').focus();
                return false;
            }

            if (repassword == '' || repassword.length <= 0 || (password != repassword)) {
                layer.tips('两次密码输入不一致', '#repassword', {time: 2000, tips: 2});
                $('#repassword').focus();
                return false;
            }

            return true;
        })

    })
</script>
</html>
