<?php
include_once("./_common.php");


  $user_hp = $_POST['user_hp'];
  $mb_password    = trim($_POST['mb_password']);
  $mb_password_re = trim($_POST['mb_password_re']);

  // if(!$mb_password)
  //     alert('비밀번호가 넘어오지 않았습니다.');
  // if($mb_password != $mb_password_re)
  //     alert('비밀번호가 일치하지 않습니다.');

  unset($value);
  $value['passwd']		= $mb_password;


  update("hi_member", $value, " where cellphone = '$user_hp' ");


  echo "<script>alert('비밀번호가 정상적으로 변경되었습니다.')</script>";


  goto_url(TB_MAPP_URL.'/login.php');
?>
