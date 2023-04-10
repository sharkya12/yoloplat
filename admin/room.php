<?php
include_once("./_common.php");
include_once(TB_ADMIN_PATH."/admin_access.php");
include_once(TB_ADMIN_PATH."/admin_head.php");

$pg_title = ADMIN_MENU3;
$pg_num = 3;
$snb_icon = "<i class=\"fa fa-truck\"></i>";

if($member['id'] != 'admin' && !$member['auth_'.$pg_num]) {
	alert("접근권한이 없습니다.");
}

//숙박업소관리
if($code == "list")					$pg_title2 = ADMIN_MENU3_01;
if($code == "register")	 $pg_title2 = ($w=="u") ? '숙박업소 정보수정' : ADMIN_MENU3_02;
if($code == "facilities")					 $pg_title2 = ADMIN_MENU3_03;
if($code == "xls")					$pg_title2 = ADMIN_MENU3_04;
if($code == "xls_update")			$pg_title2 = ADMIN_MENU3_04;
// if($code == "goods_total")					$pg_title2 = ADMIN_MENU3_05;
// if($code == "goods_total_list")			$pg_title2 = ADMIN_MENU3_06;
if($code == "mail_select_form")		$pg_title2 = ADMIN_MENU3_07;
if($code == "mail_select_list")		$pg_title2 = ADMIN_MENU3_07;
if($code == "mail_select_update")	$pg_title2 = ADMIN_MENU3_07;
if($code == "sales_settlement")	$pg_title2 = ADMIN_MENU3_05;
if($code == "sales_settlement_detail")	$pg_title2 = '숙박업소 정산관리 상세페이지';
if($code == "sales_settlement_list")	$pg_title2 = ADMIN_MENU3_06;
if($code == "sales_settlement_list_detail")	$pg_title2 = '숙박업소 정산내역 상세페이지';

//객실관리
if($code == "goods_form")	$pg_title2 = ($w=="u")?'상품 정보수정':'신규 객실등록';
if($code == "goods_list")	$pg_title2 = ADMIN_MENU3_08;
if($code == "goods_facilities")	$pg_title2 = ADMIN_MENU3_09;
//if($code == "goods_type")	$pg_title2 = ADMIN_MENU3_10;
//if($code == "goods_brand_list")	$pg_title2 = ADMIN_MENU3_11;
if($code == "goods_event")	$pg_title2 = ADMIN_MENU3_12;
if($code == "goods_qa")	$pg_title2 = ADMIN_MENU3_13;
if($code == "goods_review")	$pg_title2 = ADMIN_MENU3_14;
if($code == "goods_gift")	$pg_title2 = ADMIN_MENU3_15;
if($code == "goods_coupon")	$pg_title2 = ADMIN_MENU3_16;
if($code == "goods_coupon_form")	$pg_title2 = ADMIN_MENU3_17;

include_once(TB_ADMIN_PATH."/admin_topmenu.php");
?>

<div class="s_wrap">
	<h1><?php echo $pg_title2; ?></h1>
	<?php
	include_once(TB_ADMIN_PATH."/room/room_{$code}.php");
	?>
</div>

<?php
include_once(TB_ADMIN_PATH."/admin_tail.php");
?>
