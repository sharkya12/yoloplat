<?php
include_once("./_common.php"); // PC+모바일 공통 라이브러리
/*
  신용카드 간편결제 디비 저장,삭제 관리
*/

$w = $_POST['w']; //저장 : '' , 업데이트.u,삭제,d
$mb_id = $_POST['mb_id'];
$card_no = $_POST['card_4'];
// $billkey = $_POST['billkey'];

$sql = "select count(*) as count from hi_card_info where mb_id = '$mb_id' ";

//등록된 카드 순서
$count = sql_fetch($sql);
$card_seq = $count['count']+1;


if ($w == '') {
  $sql = "insert into hi_card_info
         set card_seq = $card_seq,
           mb_id    = '$mb_id',
           card_no       = '$card_no',
           default_yn = 'Y',
           reg_date    = '".TB_TIME_YMDHIS."'";
  sql_query($sql);
//echo($sql);
}else if($w == 'd'){
  $sql = "delete from hi_card_info where mb_id = '$mb_id' and card_no = '$card_no'";
  sql_query($sql);
}

goto_url(TB_MSHOP_URL.'/cb_card.php');

?>
