<?php
include_once("./_common.php");

// if(!$is_member) {
// 	goto_url(TB_MBBS_URL.'/login.php?url='.$urlencode);
// }

$tb['title'] = "결제";
include_once("../shop/_head3.php");

// 새로운 주문번호 생성
$od_id = get_uniqid();
set_session('ss_order_id', $od_id);

$order_action_url = TB_HTTPS_MAPP_URL.'/reservationupdate.php';

//세션에 저장된 인원수 불러오기
$total_num = get_session('total_num');

//달력에서 저장한 체크인,체크아웃 정보 가져오기
$check_in = get_session('start_time');
$check_out = get_session('end_time');

//달력에서 공휴일 정보가져오기
$in_holiday = get_session('in_holiday');
$out_holiday = get_session('out_holiday');

//숙박업소 정보 가져오기
$room = get_room($room_id);

//사용가능한 쿠폰
$cp_avl_chk = get_cp_precompose($mb_id);

//날짜 계산하기
if($check_in == "" && $check_out == ""){
  $check_in = TB_TIME_YMD;
  $check_out = TB_TIME_YMD;
}
// 체크인 날짜와 체크아웃 날짜가 같으면 체크인날짜의 -1 함.
if ($check_in == $check_out) {
  $check_out = date("Y-m-d", strtotime($check_in." +1 days")); //현재 날짜에 1일을 뺌
}
$interval = get_date_interval($check_in, $check_out); //시작일과 종료일의 차이계산

//체크인 요일 계산
$in_yoil =  get_yoil($check_in);
$out_yoil =  get_yoil($check_out);

//체크인 월계산
$in_month = substr($check_in, 5, 2);
if ($in_month < 10) {
  $in_month = str_replace('0','',$in_month);
}
//체크아웃 월계산
$out_month = substr($check_out, 5, 2);
if ($out_month < 10) {
  $out_month = str_replace('0','',$out_month);
}

$str_in_date = $in_month."월 ".substr($check_in, 8, 2)."일 (".$in_yoil.") ";
$str_out_date = $out_month."월 ".substr($check_out, 8, 2)."일 (".$out_yoil.") ";

// 요일별 체크인, 체크아웃 변수 값 자르기
if($in_yoil == '월') { $mon = json_decode($room['use_mon'], true); $check_in_str = $mon["data"][0]["in_time"]; }
if($in_yoil == '화') { $tue = json_decode($room['use_tue'], true); $check_in_str = $tue["data"][0]["in_time"]; }
if($in_yoil == '수') { $wed = json_decode($room['use_wed'], true); $check_in_str = $wed["data"][0]["in_time"]; }
if($in_yoil == '목') { $thu = json_decode($room['use_thu'], true); $check_in_str = $thu["data"][0]["in_time"]; }
if($in_yoil == '금') { $fri = json_decode($room['use_fri'], true); $check_in_str = $fri["data"][0]["in_time"]; }
if($in_yoil == '토') { $sat = json_decode($room['use_sat'], true); $check_in_str = $sat["data"][0]["in_time"]; }
if($in_yoil == '일') { $sun = json_decode($room['use_sun'], true); $check_in_str = $sun["data"][0]["in_time"]; }
if($in_holiday == 'Y') { $hol = json_decode($room['use_hol'], true); $check_in_str = $hol["data"][0]["in_time"]; }

if($out_yoil == '월') { $mon = json_decode($room['use_mon'], true); $check_out_str = $mon["data"][0]["out_time"]; }
if($out_yoil == '화') { $tue = json_decode($room['use_tue'], true); $check_out_str = $tue["data"][0]["out_time"]; }
if($out_yoil == '수') { $wed = json_decode($room['use_wed'], true); $check_out_str = $wed["data"][0]["out_time"]; }
if($out_yoil == '목') { $thu = json_decode($room['use_thu'], true); $check_out_str = $thu["data"][0]["out_time"]; }
if($out_yoil == '금') { $fri = json_decode($room['use_fri'], true); $check_out_str = $fri["data"][0]["out_time"]; }
if($out_yoil == '토') { $sat = json_decode($room['use_sat'], true); $check_out_str = $sat["data"][0]["out_time"]; }
if($out_yoil == '일') { $sun = json_decode($room['use_sun'], true); $check_out_str = $sun["data"][0]["out_time"]; }
if($out_holiday == 'Y') { $hol = json_decode($room['use_hol'], true); $check_out_str = $hol["data"][0]["out_time"]; }


//접속한 회원아이디
$mb_id = $member['id'];

//숙박상품 정보 가져오기
$room = get_room($room_id);

$sql = " select *, g.index_no AS gs_index, g.normal_price AS normal_price, s.off_percent as off_percent, s.gs_price AS gs_price, s.special_percent as special_percent, s.special_price AS special_price,

          sum(case
              when s.special_price = 0 AND s.gs_price = 0
              then g.normal_price
              when s.special_price = 0 AND s.gs_price > 0
              then s.gs_price
              ELSE s.special_price
           END) AS final_price

          FROM hi_goods AS g
          LEFT JOIN hi_sales AS s ON s.gs_id = g.index_no
          where s.gs_id = '".$gs_id."'
            and g.mb_id = '".$room_id."'
            and s.use_yn = '판매중'
            and (s.sales_date >= '".$check_in."' and s.sales_date < '".$check_out."')
            and g.goods_ca = 'R' and g.max_pplNum >= '".$total_num."' ";

$room_row = sql_fetch($sql);


//상품가격
$ori_price = intval($room_row['final_price']);

//렌트카정보
$row_rent = get_rent($rent_id);

//사용가능한 쿠폰
$cp_avl_chk = get_cp_precompose($mb_id);

// 고객 간편카드 정보 불러오기
$sql = " select * from hi_card_info where mb_id = '$member[id]'";
$card_info = sql_query($sql);
$total_count = sql_num_rows($card_info);


include_once(TB_MTHEME_PATH.'/cb_reservation.skin.php');

// include_once("./_tail.php");
?>
