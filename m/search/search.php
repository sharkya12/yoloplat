<?php
include_once("./_common.php");

// if(!$is_member) {
// 	goto_url(TB_MBBS_URL.'/login.php?url='.$urlencode);
// }

$tb['title'] = "통합검색";
include_once(TB_MAPP_PATH."/_yolo_head.php");




//인원수 페이지 설정값
$integrated_search = "search";
//인원수
$adult_num = get_session('adult_num');
$children_num = get_session('kid_num');


//달력에서 저장한 체크인,체크아웃 정보 가져오기
$check_in = get_session('start_time');
$check_out = get_session('end_time');

//세션에 저장된 인원수 불러오기
$total_num = get_session('total_num');

//달력에서 공휴일 정보가져오기
$in_holiday = get_session('in_holiday');
$out_holiday = get_session('out_holiday');

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

$str_in_date = $in_month."월 ".substr($check_in, 8, 2)."일(".$in_yoil.")";
$str_out_date = $out_month."월 ".substr($check_out, 8, 2)."일(".$out_yoil.")";


//달력불러오기
include_once(TB_MROOM_THEME."/room_calendar.skin.php");

include_once(TB_MSEARCH_THEME.'/search.skin.php');

//인원수 팝업
include_once(TB_MROOM_THEME."/number_people.skin.php");


// include_once("./_tail.php");
?>
