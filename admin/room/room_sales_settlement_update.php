<?php
include_once("./_common.php");



$count = count($_POST['chk']);
if(!$count) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}


if($_POST['act_button'] == "선택정산")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$seller_id = trim($_POST['seller_id'][$k]);
		$seller_code = trim($_POST['seller_code'][$k]);
    $fr_date = $_POST['fr_date'];
    $to_date = $_POST['to_date'];

    if($fr_date && $to_date)
      $sql_search .= " and invoice_date between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
    else if($fr_date && !$to_date)
    	$sql_search .= " and invoice_date between '$fr_date 00:00:00' and '$fr_date 23:59:59' ";
    else if(!$fr_date && $to_date)
    	$sql_search .= " and invoice_date between '$to_date 00:00:00' and '$to_date 23:59:59' ";

    $sql = " select * from hi_order
              where seller_id = '$seller_id' and dan = 5 and od_id NOT IN (select order_idx from hi_seller_cal ) $sql_search ";
    $result = sql_query($sql);


    for($i=0; $row=sql_fetch_array($result); $i++) {
      $company_fee = (int)$row['goods_price'] * 0.077;
      $real_value = (int)$row['goods_price'] - (int)($row['goods_price'] * 0.077) - (int)$row['coupon_price'];
      $manager_fee = (int)$row['use_price'] * 0.0195;
      $pg_fee = (int)$row['use_price'] * 0.033;
      $headquarter_margin = $company_fee - $manager_fee - $pg_fee;
      $od_id = $row['od_id'];
      $tot_price = $row['goods_price'];
      $tot_point = $row['use_point'];
      $tot_coupon = $row['coupon_price'];
      $tot_supply = $row['use_price'];

      unset($value);
      $value['mb_id']    = $seller_id;
      $value['order_idx'] = $od_id;
      $value['tot_price'] = $tot_price;
      $value['tot_point']  = $tot_point;
      $value['tot_coupon']  = $tot_coupon;
      $value['tot_supply']  = $tot_supply;
      $value['tot_seller']  = $real_value;
      $value['reg_time']    = TB_TIME_YMDHIS;

      insert("hi_seller_cal", $value);


			unset($value);
			$value['sellerpay_yes']		  = 1;

			update("hi_order", $value," where od_id='{$od_id}'");

    }
	}

  goto_url(TB_ADMIN_URL.'/room.php?code=sales_settlement');

}










?>
