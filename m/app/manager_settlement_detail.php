<?php
include_once("./_common.php");

$tb['title'] = "매니저 정산현황 상세내역";
include_once(TB_MAPP_PATH."/_yolo_head.php");

$mb_id = get_session('sbok_mb_id');

$mb_id = 'dlscjf';


if ($pp_yn == 'Y') {
  $sql_pp_yn = " and a.pp_yn = 'Y' ";
} else if ($pp_yn == 'N') {
  $sql_pp_yn = " and a.pp_yn = 'N' ";
}


$sql = " select a.*, b.company_name as company_name, left(c.out_date, 10) as out_date
        from hi_partner_pay as a

        left join hi_room as b ON a.pp_rel_id = b.mb_id
        left join hi_order as c ON a.pp_rel_action = c.od_id

        where a.mb_id = '$mb_id' and left(a.pp_datetime,10) = '$datetime' $sql_pp_yn ";
$result = sql_query($sql);




include_once(TB_MYOLO_THEME.'/manager_settlement_detail.skin.php');

?>
