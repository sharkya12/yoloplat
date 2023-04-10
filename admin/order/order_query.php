<?php
if(!defined('_TUBEWEB_')) exit;

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

if(isset($sel_field))		 $qstr .= "&sel_field=$sel_field";
if(isset($od_settle_case))	 $qstr .= "&od_settle_case=".urlencode($od_settle_case);
if(isset($od_status))		 $qstr .= "&od_status=$od_status";
if(isset($od_status2))		 $qstr .= "&od_status2=$od_status2";
if(isset($od_final))		 $qstr .= "&od_final=$od_final";
if(isset($od_taxbill))		 $qstr .= "&od_taxbill=$od_taxbill";
if(isset($od_taxsave))		 $qstr .= "&od_taxsave=$od_taxsave";
if(isset($od_memo))			 $qstr .= "&od_memo=$od_memo";
if(isset($od_shop_memo))	 $qstr .= "&od_shop_memo=$od_shop_memo";
if(isset($od_receipt_point)) $qstr .= "&od_receipt_point=$od_receipt_point";
if(isset($od_coupon))		 $qstr .= "&od_coupon=$od_coupon";
if(isset($od_escrow))		 $qstr .= "&od_escrow=$od_escrow";

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";


$sql_common = " from hi_order ";

$where = array();

if($code == 'list') // 전체주문내역
	$where[] = " dan != 0 ";
else
	$where[] = " dan = '$code' ";

if($goods_ca)
	$where[] = " goods_ca = '$goods_ca' ";

if($seller_item)
 	$where[] = " seller_item = '$seller_item' ";

if($type)
	$where[] = "type = '$type' ";

if($sfl && $stx)
	$where[] = " $sfl like '%$stx%' ";

if($sfl=='b.company_name'){
	$sql_join = "  a left join (select mb_id as org_mb_id, company_name
																from v_company
															) b  on a.seller_id = b.org_mb_id";
}

if($od_settle_case)
	$where[] = " paymethod = '$od_settle_case' ";

if(is_numeric($od_status))
	$where[] = " dan = '$od_status' ";

if(is_numeric($od_status2))
	$where[] = " dan = '$od_status2' ";

if(is_numeric($od_final))
	$where[] = " user_ok = '$od_final' ";

if($od_taxbill)
	$where[] = " taxbill_yes = 'Y' ";

if($od_taxsave)
	$where[] = " taxsave_yes IN ('Y','S') ";

if($od_memo)
	$where[] = " memo <> '' ";

if($od_shop_memo)
	$where[] = " shop_memo <> '' ";

if($od_receipt_point)
	$where[] = " use_point != 0 ";

if($od_coupon)
	$where[] = " coupon_price != 0 ";

if($od_escrow)
	$where[] = " od_escrow = 1 ";

if($fr_date && $to_date)
  $where[] = " left({$sel_field},10) between '$fr_date' and '$to_date' ";
else if($fr_date && !$to_date)
	$where[] = " left({$sel_field},10) between '$fr_date' and '$fr_date' ";
else if(!$fr_date && $to_date)
	$where[] = " left({$sel_field},10) between '$to_date' and '$to_date' ";

if($where) {
    $sql_search = ' where '.implode(' and ', $where);
}

$sql_group = " group by od_id ";
$sql_order = " order by index_no desc ";

// 테이블의 전체 레코드수만 얻음
$sql = " select od_id {$sql_common} {$sql_join} {$sql_search} {$sql_group} ";
$result = sql_query($sql);
$total_count = sql_num_rows($result);

if($_SESSION['ss_page_rows'])
	$page_rows = $_SESSION['ss_page_rows'];
else
	$page_rows = 30;

$rows = $page_rows;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * {$sql_common} {$sql_join} {$sql_search} {$sql_group} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$tot_orderprice = 0; // 총주문액
$sql = " select od_id {$sql_common} {$sql_join} {$sql_search} {$sql_group} {$sql_order} ";
$res = sql_query($sql);
while($row=sql_fetch_array($res)) {
	$amount = get_order_spay($row['od_id']);
	$tot_orderprice += $amount['buyprice'];
}

include_once(TB_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>
