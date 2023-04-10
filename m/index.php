<?php
define('_MINDEX_', true);
include_once("./_common.php");

// 인트로를 사용중인지 검사
if(!$is_member && $config['shop_intro_yes']) {
	include_once(TB_MTHEME_PATH.'/intro.skin.php');
    return;
}

include_once(TB_MPATH."/_head2.php"); // 상단
include_once(TB_MPATH."/popup.inc.php"); // 팝업
include_once(TB_MYOLO_THEME.'/welcome.skin.php'); // 팝업레이어
// include_once(TB_MPATH."/_tail.php"); // 하단
?>
