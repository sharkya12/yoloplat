<?php
include_once("./_common.php");

// if(TB_IS_MOBILE) {
// 	goto_url(TB_MBBS_URL.'/register.php');
// }

// if($is_member) {
// 	goto_url(TB_URL);
// }

// 본사쇼핑몰에서 회원가입을 받지 않을때
$config['admin_reg_msg'] = str_replace("\r\n", "\\r\\n", $config['admin_reg_msg']);
if($config['admin_reg_yes'] && $pt_id == 'admin') {
	alert($config['admin_reg_msg'], TB_URL);
}

// 세션을 지웁니다.
set_session("ss_mb_reg", "");

$tb['title'] = '체험 영업점 약관동의';
include_once("./_head.php");

$register_action_url = TB_BBS_URL.'/exp_manager_register_form.php';
include_once(TB_THEME_PATH.'/exp_manager_register.skin.php');

include_once("./_tail.php");
?>
