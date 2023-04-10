<?php
include_once("./_common.php");

// if(!$is_member) {
// 	goto_url(TB_MBBS_URL.'/login.php?url='.$urlencode);
// }

$tb['title'] = "내 정보";
include_once("./_head3.php");

include_once(TB_MTHEME_PATH.'/cb_mypage_set.skin.php');

// include_once("./_tail.php");
?>
