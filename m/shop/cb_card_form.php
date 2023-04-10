<?php
include_once("./_common.php");

// if(!$is_member) {
// 	goto_url(TB_MBBS_URL.'/login.php?url='.$urlencode);
// }

$tb['title'] = "카드관리";
include_once("./_head3.php");

$sql = " select * from hi_member where id = '$member[id]'";
$member_row = sql_fetch($sql);



include_once(TB_MTHEME_PATH.'/cb_card_form.skin.php');

// include_once("./_tail.php");
?>
