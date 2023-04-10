<?php
include_once("./_common.php");

// if(!$is_member) {
// 	goto_url(TB_MBBS_URL.'/login.php?url='.$urlencode);
// }

$tb['title'] = "예약내역";
include_once("./_head4.php");

include_once(TB_MTHEME_PATH.'/cb_reservation_list.skin.php');

// include_once("./_tail.php");
?>
