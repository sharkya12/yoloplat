<?php
include_once("./_common.php");
include_once(TB_LIB_PATH.'/mailer.lib.php');

$business_type = $_POST['business_type'];

// 주문번호를 얻는다.
$od_id = get_session('ss_order_id');
//alert( get_session('ss_order_id'));

$od_no = $od_id;

echo($_PSOT['pay_method']);
if( !$od_id ){
    alert("주문번호가 없습니다.", TB_URL);
}

// '신용카드,계좌이체,가상계좌'등으로 결제시도 후 주문서로 리턴해 다시 재주문하는 경우에는 주문서가 2번 등록되므로 기존에 주문이 발생되지 않은 주문건은 먼저 삭제함.
sql_query(" delete from hi_order where od_id = '$od_id' ");

$cellphone = hyphen_hp_number($_POST['cellphone']);
$b_cellphone = hyphen_hp_number($_POST['b_cellphone']);

$dan = $_POST['dan'];

if((int)$_POST['tot_price'] == 0) { // 총 결제금액이 0 이면
	$dan = 3; // 예약대기 단계로 적용

	// 포인트로 전액 결제시는 포인트결제로 값을 바꾼다.

	if($_POST['pay_method'] != '포인트' && (int)$_POST['org_price'] == (int)$_POST['use_point']) {
		$_POST['pay_method'] = '포인트';
	}


}
// 콤마제거\
$ori_price = preg_replace("/[^0-9]/", "",$_POST['ori_price']);
$tot_price = preg_replace("/[^0-9]/", "",$_POST['tot_price']);
$coupon_price	= preg_replace("/[^0-9]/", "",$_POST['coupon_price']); // 쿠폰 할인가


// set_session('tot_price', (int)$tot_price);
// set_session('tot_price', (int)$_POST['tot_price']);
// set_session('use_point', (int)$_POST['use_point']);

$baesong_price	= explode("|",$_POST['baesong_price']); // 상품별 배송비
// $coupon_price	= explode("|",$_POST['coupon_price']); // 상품별 할인가
// $coupon_lo_id	= explode("|",$_POST['coupon_lo_id']); // 상품별 쿠폰 shop_coupon_log (필드:lo_id)
// $coupon_cp_id	= explode("|",$_POST['coupon_cp_id']); // 상품별 쿠폰 shop_coupon_log (필드:cp_id)
$ss_cart_id		= explode(",",$_POST['ss_cart_id']); // 장바구니 idx

$coupon_lo_id	= $_POST['coupon_lo_id']; // 상품별 쿠폰 shop_coupon_log (필드:lo_id)
$coupon_cp_id	= $_POST['coupon_cp_id']; // 상품별 쿠폰 shop_coupon_log (필드:cp_id)

$use_point = (int)preg_replace("/[^0-9]/", "",$_POST['use_point']); // 포인트결제
$use_money = (int)preg_replace("/[^0-9]/", "",$_POST['use_money']); // 머니결제

$baesong_price2 = (int)$_POST['baesong_price2']; // 추가배송비

if($is_member)
    $od_pwd = $member['passwd'];
else
    $od_pwd = get_encrypt_string($_POST['od_pwd']);

//for($i=0; $i<count($gs_id); $i++) {

$gs_id = $_POST['gs_id'];

for($i=0; $i<1; $i++) {

	$sql = "insert into hi_order
			   set od_id				= '{$od_id}'
			     , od_no				= '{$od_no}'
				 , mb_id				= '{$member['id']}'
				 , name					= '{$_POST['name']}'
				 , cellphone			= '{$cellphone}'
				 , telephone			= '{$_POST['telephone']}'
				 , email				= '{$_POST['email']}'
				 , zip					= '{$_POST['zip']}'
				 , addr1				= '{$_POST['addr1']}'
				 , addr2				= '{$_POST['addr2']}'
				 , addr3				= '{$_POST['addr3']}'
				 , addr_jibeon			= '{$_POST['addr_jibeon']}'
				 , b_name				= '{$_POST['b_name']}'
				 , b_cellphone			= '$b_cellphone'
				 , b_telephone			= '{$_POST['b_telephone']}'
				 , b_zip				= '{$_POST['zip']}'
				 , b_addr1				= '{$_POST['addr1']}'
				 , b_addr2				= '{$_POST['addr2']}'
				 , b_addr3				= '{$_POST['addr3']}'
				 , b_addr_jibeon		= '{$_POST['addr_jibeon']}'
         , gname        = '{$_POST['gname']}'
				 , gs_id				= '{$gs_id}'
				 , gs_notax				= '{$gs_notax}'
				 , seller_id			= '{$seller_id}'
         , type			= '{$_POST['type']}'
         , goods_ca			= '{$_POST['goods_ca']}'
				 , goods_price			= '{$ori_price}'
				 , supply_price			= '{$supply_price}'
				 , sum_point			= '{$i_sum_point}'
				 , sum_qty				= '1'
				 , coupon_price			= '{$coupon_price}'
				 , coupon_lo_id			= '{$coupon_lo_id}'
				 , coupon_cp_id			= '{$coupon_cp_id}'
				 , use_price			= '{$tot_price}'
				 , use_point			= '{$use_point}'
         , use_money			= '{$i_use_money}'
				 , baesong_price		= '{$baesong_price}'
				 , baesong_price2		= '{$baesong_price2}'
				 , PayMethod			= '{$_POST['pay_method']}'
				 , bank					= '{$_POST['bank']}'
				 , deposit_name			= '{$_POST['deposit_name']}'
				 , dan					= '{$dan}'
				 , memo					= '{$_POST['memo']}'
				 , taxsave_yes			= '{$_POST['taxsave_yes']}'
				 , taxbill_yes			= '{$_POST['taxbill_yes']}'
				 , company_saupja_no	= '{$_POST['company_saupja_no']}'
				 , company_name			= '{$_POST['company_name']}'
				 , company_owner		= '{$_POST['company_owner']}'
				 , company_addr			= '{$_POST['company_addr']}'
				 , company_item			= '{$_POST['company_item']}'
				 , company_service		= '{$_POST['company_service']}'
				 , tax_hp				= '{$_POST['tax_hp']}'
				 , tax_saupja_no		= '{$_POST['tax_saupja_no']}'
				 , od_time				= '".TB_TIME_YMDHIS."'
				 , od_pwd				= '{$od_pwd}'
				 , od_ip				= '{$_SERVER['REMOTE_ADDR']}'
				 , od_test				= '{$default['de_card_test']}'
				 , od_tax_flag			= '{$default['de_tax_flag_use']}'
				 , od_settle_pid		= '{$pt_settle_pid}'
				 , pt_id				= '{$_POST['pt_id']}'
         , in_date				= '{$_POST['in_date']}'
         , out_date				= '{$_POST['out_date']}'
				 , shop_id				= '{$_POST['shop_id']}' ";
	sql_query($sql, FALSE);
	$insert_id = sql_insert_id();


  //==================================================================
  //수수료적립 테이블에 넘겨주기
  $sql = "select pt_id from hi_member where id = '$seller_id' ";
  $result_pt_id = sql_fetch($sql);
  $pt_id = $result_pt_id['pt_id'];

  //적립금 합계
  $sql = " select ifnull(sum(pp_pay), 0) as pay
          from hi_partner_pay
          where mb_id = '$pt_id' and pp_yn = 'Y' ";
  $total_pay = sql_fetch($sql);

  // 수수료정산(출금)에 관련된 금액
  $sql = " select ifnull(sum(balance), 0)
          from hi_partner_payrun
          where mb_id = '$pt_id' and state = '1' ";
  $res = sql_fetch($sql);

  //현재 총적립금
  $member_pay = (int)$total_pay['pay'] - (int)$res['withdraw'];
  //=================================================================== 21.12.20일 변경내용

  //오더 정보 가져오기
  $sql_order = " sELECT o.*, m.pt_id AS m_pt_id from hi_order AS o LEFT JOIN hi_member AS m ON m.id = o.seller_id WHERE o.od_id = '".$od_no."' ";
  $row_order = sql_fetch($sql_order);

  unset($value);
  $value['mb_id']			= $pt_id; // 추천받은 프리미엄 회원이상 등급의 회원
  $value['pp_datetime'] = TB_TIME_YMDHIS;
  $value['pp_content'] = '주문번호 '.$od_id.' 적립예정';
  $value['pp_pay'] = $row_order['goods_price'];
  // $value['pp_balance'] = $member_pay;
  $value['pp_balance'] = '';
  $value['pp_rel_table'] = 'sale';
  $value['pp_rel_id'] = $seller_id;
  $value['pp_rel_action'] = $od_id;
  $value['pp_yn'] = 'N';

  $admin_pay = (int) floor($row_order['goods_price']*(0.077));

  pay_fee_recursive($seller_id, 1, $od_no, $od_id, $pt_id, $row_order['goods_price'], 'Y', 'N', $od_no, $admin_pay);
  // insert("hi_partner_pay", $value);

  //결제수단 저장하기
  unset($value);
  $next_paymethod = $_POST['next_paymethod'];
  $test = $_POST['select_card'];
    if($next_paymethod) {
        $value['pay_method']			= $_POST['pay_method'];
      if($_POST['pay_method'] == '간편신용카드') {
        $value['select_card']		= $_POST['select_card'];
      } else {
        $value['select_card']		= '';
      }
      update("hi_member",$value,"where id='{$member['id']}'");
  }

  //예약한 방 갯수 업데이트
  $start_date = substr($_POST['in_date'], 0, 10); //숙박 시작일
  $end_date = date("Y-m-d", strtotime($_POST['out_date']." -1 day")); //숙박 종료일 전일
  sql_query("update hi_sales set gs_use_qty = gs_use_qty + 1 where gs_id = '{$gs_id}' and sales_date between '{$start_date}' and '{$end_date}' ");


	// 고객이 주문/배송조회를 위해 보관해 둔다.
	save_goods_data($gs_id, $insert_id, $od_id);


	// 쿠폰 사용함으로 변경 (무통장, 포인트결제일 경우만)
	if($coupon_lo_id && $is_member) {
		sql_query("update hi_coupon_log set mb_use='1',od_no='$od_no',cp_udate='".TB_TIME_YMDHIS."' where lo_id='$coupon_lo_id'");
	}

	// 쿠폰 주문건수 증가
	if($coupon_cp_id && $is_member) {
		sql_query("update hi_coupon set cp_odr_cnt=(cp_odr_cnt + 1) where cp_id='$coupon_cp_id'");
	}

	// // 주문완료 후 쿠폰발행
	// $gs = get_goods($gs_id[$i], 'use_aff');
	// if(!$gs['use_aff'] && $config['coupon_yes'] && $is_member) {
	// 	$cp_used = is_used_coupon('1', $gs_id[$i], $member['id']);
	// 	if($cp_used) {
	// 		$cp_id = explode(",", $cp_used);
	// 		for($g=0; $g<count($cp_id); $g++) {
	// 			if($cp_id[$g]) {
	// 				$cp = sql_fetch("select * from shop_coupon where cp_id='$cp_id[$g]'");
	// 				insert_used_coupon($member['id'], $member['name'], $cp);
	// 			}
	// 		}
	// 	}
	// }
  if($is_member && $use_point) {
    insert_point($member['id'], (-1) * $use_point, "주문번호 $od_id 결제", "@order", $member['id'], $od_id );
  }
}

// $od_pg = $default['de_pg_service'];
// if($_POST['PayMethod'] == 'KAKAOPAY')
//     $od_pg = 'KAKAOPAY';
//
// // 복합과세 금액
// if($default['de_tax_flag_use']) {
// 	$info = comm_tax_flag($od_id);
// 	$od_tax_mny  = $info['comm_tax_mny'];
// 	$od_vat_mny  = $info['comm_vat_mny'];
// 	$od_free_mny = $info['comm_free_mny'];
// } else {
// 	$od_tax_mny  = round($_POST['tot_price'] / 1.1);
// 	$od_vat_mny  = $_POST['tot_price'] - $od_tax_mny;
// 	$od_free_mny = 0;
// }

// // 주문서에 UPDATE
// $sql = " update hi_order
//             set od_pg		 = '$od_pg'
// 			  , od_tax_mny	 = '$od_tax_mny'
// 			  , od_vat_mny	 = '$od_vat_mny'
// 			  , od_free_mny	 = '$od_free_mny'
// 		  where od_id = '$od_id'";
// sql_query($sql, false);

// if(in_array($_POST['PayMethod'],array('무통장','포인트'))) {
// 	$cart_select = " , ct_select = '1' ";
// }


if(in_array($_POST['PayMethod'],array('무통장','포인트','머니'))) {
	// 회원이면서 포인트를 사용했다면 테이블에 사용을 추가
	if($is_member && $use_point) {
		insert_point($member['id'], (-1) * $use_point, "주문번호 $od_id 결제", "@order", $member['id'], $od_id);
	}

  // // 회원이면서 머니를 사용했다면 테이블에 사용을 추가
	// if($is_member && $use_money) {
	// 	insert_money($member['id'], (-1) * $use_money, "주문번호 $od_id 결제");
	// }

	// 쿠폰사용내역기록
	if($is_member) {
		$sql = "select * from hi_order where od_id='$od_id'";
		$res = sql_query($sql);
		for($i=0; $row=sql_fetch_array($res); $i++) {
			if($row['coupon_price']) {
				$sql = "update hi_coupon_log
						   set mb_use = '1',
							   od_no = '$row[od_no]',
							   cp_udate	= '".TB_TIME_YMDHIS."'
						 where lo_id = '$row[coupon_lo_id]' ";
				sql_query($sql);
			}
		}
	}

	$od = sql_fetch("select * from hi_order where od_id='$od_id'");

	// 주문완료 문자전송
	// icode_order_sms_send($od['pt_id'], $od['cellphone'], $od_id, 2);

	// 무통장 입금 때 고객에게 계좌정보 보냄
	// if($_POST['PayMethod'] == '무통장' && (int)$_POST['tot_price'] > 0) {
	// 	$sms_content = $od['name']."님의 입금계좌입니다.\n금액:".number_format($_POST['tot_price'])."원\n계좌:".$od['bank']."\n".$config['company_name'];
	// 	icode_direct_sms_send($od['pt_id'], $od['cellphone'], $sms_content);
	// }

	// 메일발송
	if($od['email']) {
		$subject1 = get_text($od['name'])."님 예약이 정상적으로 처리되었습니다.";
		$subject2 = get_text($od['name'])." 고객님께서 신규예약을 신청하셨습니다.";

		ob_start();
		include_once(TB_SHOP_PATH.'/orderformupdate_mail.php');
		$content = ob_get_contents();
		ob_end_clean();

		// 예약자에게 메일발송
		mailer($config['company_name'], $super['email'], $od['email'], $subject1, $content, 1);

		// 관리자에게 메일발송
		if($super['email'] != $od['email']) {
			mailer($od['name'], $od['email'], $super['email'], $subject2, $content, 1);
		}
	}
}


// 주문번호제거
set_session('ss_order_id', '');

// 장바구니 session 삭제
set_session('ss_cart_id', '');

// orderinquiryview 에서 사용하기 위해 session에 넣고
$uid = md5($od_id.TB_TIME_YMDHIS.$_SERVER['REMOTE_ADDR']);
set_session('ss_orderview_uid', $uid);

 goto_url(TB_MAPP_URL.'/reservation_success.php?od_id='.$od_id.'&uid='.$uid.'&type='.$business_type);

?>

<html>
    <head>
        <title>주문정보 기록</title>
        <script>
            // 결제 중 새로고침 방지 샘플 스크립트 (중복결제 방지)
            function noRefresh()
            {
                /* CTRL + N키 막음. */
                if ((event.keyCode == 78) && (event.ctrlKey == true))
                {
                    event.keyCode = 0;
                    return false;
                }
                /* F5 번키 막음. */
                if(event.keyCode == 116)
                {
                    event.keyCode = 0;
                    return false;
                }
            }

            document.onkeydown = noRefresh ;
        </script>
    </head>
</html>
