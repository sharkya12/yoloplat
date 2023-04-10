<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$count = count($_POST['chk']);
if(!$count) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

// if($_POST['act_button'] == "입금완료")
// {
// 	for($i=0; $i<$count; $i++)
// 	{
// 		// 실제 번호를 넘김
// 		$k     = $_POST['chk'][$i];
// 		$od_id = $_POST['od_id'][$k];
//
// 		$od = get_order($od_id);
// 		if($od['dan'] != 1) continue;
// 		if(!in_array($od['paymethod'], array('무통장','가상계좌'))) continue;
//
// 		change_order_status_ipgum($od_id);
//
// 		icode_order_sms_send($od['pt_id'], $od['cellphone'], $od_id, 3);
// 	}
// }
// else if($_POST['act_button'] == "주문취소")
// {
// 	for($i=0; $i<$count; $i++)
// 	{
// 		// 실제 번호를 넘김
// 		$k     = $_POST['chk'][$i];
// 		$od_id = $_POST['od_id'][$k];
//
// 		$od = get_order($od_id);
// 		if($od['dan'] != 1) continue;
// 		if(!in_array($od['paymethod'], array('무통장','가상계좌'))) continue;
//
// 		$sql = " select od_no from hi_order where od_id = '$od_id' order by index_no ";
// 		$res = sql_query($sql);
// 		while($row=sql_fetch_array($res)) {
// 			change_order_status_6($row['od_no']);
// 		}
//
// 		icode_order_sms_send($od['pt_id'], $od['cellphone'], $od_id, 5);
// 	}
// }
// else if($_POST['act_button'] == "예약대기")
// {
// 	for($i=0; $i<$count; $i++)
// 	{
// 		// 실제 번호를 넘김
// 		$k     = $_POST['chk'][$i];
// 		$od_id = $_POST['od_id'][$k];
//
// 		$od = get_order($od_id);
// 		if($od['dan'] != 2) continue;
//
// 		change_order_status_3($od_id);
// 	}
if($_POST['act_button'] == "예약완료")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k			 = $_POST['chk'][$i];
		$od_id		 = $_POST['od_id'][$k];
		// $delivery	 = $_POST['delivery'][$k];
		// $delivery_no = $_POST['delivery_no'][$k];

		$od = get_order($od_id);
		if($od['dan'] != 3) continue;

		change_order_status_4($od_id, $delivery, $delivery_no);

		$od_sms_baesong[$od['od_id']] = $od['cellphone'];
	}

	foreach($od_sms_baesong as $key=>$recv) {
		$q = get_order($key, 'pt_id');
		icode_order_sms_send($q['pt_id'], $recv, $key, 4);
	}
}
else if($_POST['act_button'] == "예약취소 - 전액환불")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k     = $_POST['chk'][$i];
		$od_id = $_POST['od_id'][$k];

		$od = get_order($od_id);
		if($od['dan'] != 3) continue;

		change_order_status_6($od_id);
	}
}
// else if($_POST['act_button'] == "예약대기 부분환불")
// {
// 	$od_id_arr = explode(',', $_POST['od_id']);
//
// 	$update_state = '0';
//
// 	for($i=0; $i<$count; $i++) {
// 		// 실제 번호를 넘김
// 		// $k     = $_POST['chk'][$i];
// 		$od_id = $od_id_arr[$i];
// 		$od_no = $_POST['od_no'][$i]; //오더넘버
// 		$cancel_per = $_POST['cancel_per'][$i]; //환불퍼센트
// 		$cancel_price = $_POST['cancel_price'][$i]; //환불금액
// 		$cancel_reason = $_POST['cancel_reason'][$i]; //환불사유
//
// 		$od = get_order($od_id);
// 		if($od['dan'] != 3) continue;
//
// 		//innopay 처리하기
// 		if(in_array($od['PayMethod'], array('간편신용카드','계좌이체','신용카드'))) {
//
// 			require_once(TB_ADMIN_PATH.'/settle_innopay.inc.php');
//
// 			$stotal = get_order_spay($od_id); // 총계
//
// 			echo "<script>alert('PayMethod:".$PAY_METHOD[$od['PayMethod']]."');</script>";
//
// 			$innopay_data = array();
// 			$innopay_data['mid'] = $default['de_innopay_mid'];
// 			$innopay_data['tid'] = $od['od_tno'];
// 			$innopay_data['svcCd'] = $PAY_METHOD[$od['PayMethod']];
// 			$innopay_data['partialCancelCode'] = '0';
// 			$innopay_data['cancelAmt'] = $stotal['useprice'];
// 			$innopay_data['cancelMsg'] = '주문자 본인 취소-'.$cancel_memo;
// 			$innopay_data['cancelPwd'] = $default['de_innopay_cancelpwd'];
//
// 			// $innopay = ERP_curl($innopay_data);
//
// 			$res_cd  = $innopay->resultCode;
// 			$res_msg = $innopay->resultMsg;
//
// 			if($res_cd != '2001') {
// 				alert($res_msg.' 코드 : '.$res_cd);
// 			}
// 		}
//
// 		change_order_status_10($od_no, $cancel_per, $cancel_price, $cancel_reason);
// 		$update_state = '1';
// 	}
// 	echo "<script>opener.document.getElementById('order_update_status').value = ".$update_state."</script>";
// 	echo "<script>window.close();</script>";
// }
else if($_POST['act_button'] == "사용완료")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k			 = $_POST['chk'][$i];
		$od_id		 = $_POST['od_id'][$k];
		$delivery	 = $_POST['delivery'][$k];
		$delivery_no = $_POST['delivery_no'][$k];

		$od = get_order($od_id);
		if($od['dan'] != 4) continue;

		change_order_status_5($od_id, $delivery, $delivery_no);

		//partner_pay 수수료 pp_yn Y 로 바꾸기
		change_ppyn_status($od_id, 'Y');

		//pay_current 최종 수수료 업데이트 하기
		$sql = " select * from hi_partner_pay where pp_rel_id = ".$od_id;
		$result = sql_query($sql);
		for($j=0; $row=sql_fetch_array($result); $j++) {
			update_pay_current($row['mb_id']);
		}

		$od_sms_delivered[$od['od_id']] = $od['cellphone'];
	}

	foreach($od_sms_delivered as $key=>$recv) {
		$q = get_order($key, 'pt_id');
		icode_order_sms_send($q['pt_id'], $recv, $key, 6);
	}
}
else if($_POST['act_button'] == "예약완료 취소 - 전액환불")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k     = $_POST['chk'][$i];
		$od_id = $_POST['od_id'][$k];

		$od = get_order($od_id);
		if($od['dan'] != 4) continue;

		change_order_status_9($od_id);
	}
}
else if($_POST['act_button'] == "예약완료 부분환불")
{
	$od_id_arr = explode(',', $_POST['od_id']);

	$update_state = '0';

	for($i=0; $i<$count; $i++) {
		// 실제 번호를 넘김
		// $k     = $_POST['chk'][$i];
		$od_id = $od_id_arr[$i];
		$od_no = $_POST['od_no'][$i]; //오더넘버
		$cancel_per = $_POST['cancel_per'][$i]; //환불퍼센트
		$cancel_price = $_POST['cancel_price'][$i]; //환불금액
		$cancel_reason = $_POST['cancel_reason'][$i]; //환불사유

		$od = get_order($od_id);
		if($od['dan'] != 4) continue;

		change_order_status_11($od_no, $cancel_per, $cancel_price, $cancel_reason);
		$update_state = '1';
	}
	echo "<script>opener.document.getElementById('order_update_status').value = ".$update_state."</script>";
	echo "<script>window.close();</script>";
}
// else if($_POST['act_button'] == "구매확정")
// {
// 	for($i=0; $i<$count; $i++)
// 	{
// 		// 실제 번호를 넘김
// 		$k     = $_POST['chk'][$i];
// 		$od_id = $_POST['od_id'][$k];
//
// 		change_status_final($od_id);
// 	}
// }
// else if($_POST['act_button'] == "구매확정취소")
// {
// 	for($i=0; $i<$count; $i++)
// 	{
// 		// 실제 번호를 넘김
// 		$k     = $_POST['chk'][$i];
// 		$od_id = $_POST['od_id'][$k];
//
// 		change_status_final_cancel($od_id);
// 	}
// }
else if($_POST['act_button'] == "선택삭제")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k     = $_POST['chk'][$i];
		$od_id = $_POST['od_id'][$k];

		$od = get_order($od_id);
		if(!in_array($od['dan'], array(1,9)))
			alert('입금대기, 주문취소 상태의 상품만 삭제 가능합니다.');

		$sql = " select od_id from hi_order where od_id = '$od_id' order by index_no ";
		$res = sql_query($sql);
		while($row=sql_fetch_array($res)) {
			order_delete($row['od_id'], $od_id); // 주문서 삭제
		}
	}
}
// else if($_POST['act_button'] == "운송장번호수정")
// {
// 	for($i=0; $i<$count; $i++)
// 	{
// 		// 실제 번호를 넘김
// 		$k = $_POST['chk'][$i];
//
// 		$sql = " update hi_order
// 					set delivery	= '{$_POST['delivery'][$k]}'
// 					  , delivery_no = '{$_POST['delivery_no'][$k]}'
// 				  where od_no = '{$_POST['od_no'][$k]}' ";
// 		sql_query($sql);
// 	}
// } else {
// 	alert();
// }


goto_url(TB_ADMIN_URL."/order.php?$q1&page=$page");
?>
