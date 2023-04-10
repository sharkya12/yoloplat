<?php
include_once("./_common.php");
include_once(TB_LIB_PATH.'/register.lib.php');

$sql = "select * from hi_order where mb_id= '{$member['id']}'";
$order = sql_fetch($sql);

  $value['dan']				  = 6;
  $value['shop_memo']				  = "주문자 본인 직접 취소 - ".TB_TIME_YMDHIS."취소이유 : ".$_POST['cancel_check'];
  $value['cancel_date']		  = TB_TIME_YMDHIS;

  update("hi_order", $value," where mb_id='{$member['id']}' and index_no= {$_POST['index_no']}");

  // goto_url("./cb_reservation_info.php?index_no={$_POST['index_no']}");
?>
