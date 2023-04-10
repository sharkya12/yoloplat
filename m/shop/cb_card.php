<?php
include_once("./_common.php");

// if(!$is_member) {
// 	goto_url(TB_MBBS_URL.'/login.php?url='.$urlencode);
// }

$tb['title'] = "카드관리";
include_once("./_head4.php");


  $sql = " select * from hi_card_info where mb_id = '$member[id]'";
  $result = sql_query($sql);
  $total_count = sql_num_rows($result);

include_once(TB_MTHEME_PATH.'/cb_card.skin.php');

// include_once("./_tail.php");
?>
