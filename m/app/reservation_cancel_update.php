<?php
include_once("./_common.php");
include_once(TB_LIB_PATH.'/register.lib.php');

$sql = "select * from hi_order where mb_id= '{$member['id']}'";
$order = sql_fetch($sql);

  $value['dan']				  = 6;
  $value['shop_memo']				  = "주문자 본인 직접 취소 - ".TB_TIME_YMDHIS." (취소이유 : {$_POST['cancel_check']}";
  $value['cancel_date']		  = TB_TIME_YMDHIS;

  update("hi_order", $value," where mb_id='{$member['id']}' and index_no= {$_POST['index_no']} and dan = '3'");

  unset($value);

//사용한 포인트 다시 되돌리기
$od_id = $_POST['od_id'];
$sql = "select * from hi_point where mb_id='{$member['id']}' and po_rel_action = '{$od_id}' ";
$point = sql_fetch($sql);

if($point['po_point']) {
insert_point($member['id'], (-1) * $point['po_point'], "주문번호 $od_id 예약취소", "@order_cancel", $member['id'], $od_id );
}
//적립예정금액 삭제
$sql = "delete from hi_partner_pay where pp_rel_action = '$od_id'";
sql_query($sql);
  // goto_url("./cb_reservation_info.php?index_no={$_POST['index_no']}");
?>
