<?php
include_once("./_common.php");

$tb['title'] = "로그인";
include_once(TB_MAPP_PATH."/_yolo_head.php");

$referer_url = $_SERVER["HTTP_REFERER"];

$login_url        = login_url($url);
$login_action_url = TB_HTTPS_MBBS_URL."/login_check.php?referer_url=".$referer_url;


include_once(TB_MYOLO_THEME.'/login.skin.php');

?>
