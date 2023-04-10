<?php
include_once("./_common.php");

$w = $_POST['w']; //저장 : '' , 업데이트.u,삭제,d
$mb_id = $_POST['mb_id'];
$seller_id = $_POST['seller_id']
$gs_id = $_POST['gs_id'];
$score = $_POST['review_score'];
$memo = $_POST['review_content'];

echo $mb_id;


// if ($w == '') {
//   $sql = "insert into hi_goods_review
//          set mb_id    = '$mb_id',
//            seller_id    = '$seller_id',
//            gs_id       = '$gs_id',
//            score  = '$score',
//            memo = '$memo',
//            reg_time    = '".TB_TIME_YMDHIS."'";
//   sql_query($sql);
// //echo($sql);
// }else if($w == 'd'){
//   $sql = "delete from hi_goods_review where mb_id = '$mb_id' and index_no = '$index_no'";
//   sql_query($sql);
// }

?>
