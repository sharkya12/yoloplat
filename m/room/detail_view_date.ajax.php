<?php
include_once("./_common.php");

$room_id = $_POST['room_id'];
$gs_id = $_POST['gs_id'];
$type = $_POST['type'];
$check_in = $_POST['check_in'];
$check_out = $_POST['check_out'];
$total_num = $_POST['total_num'];
$in_holiday = $_POST['in_holiday'];
$out_holiday = $_POST['out_holiday'];

//세션에 체크인, 체크아웃 정보 저장하기
set_session('start_time', $check_in);
set_session('end_time', $check_out);
set_session('in_holiday', $in_holiday);
set_session('out_holiday', $out_holiday);

//숙박업소 정보 가져오기
$room = get_room($room_id);

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


$sql =    " select * , g.index_no AS gs_index, g.mb_id AS g_mb_id, g.normal_price AS normal_price, min(s.gs_tot_qty - s.gs_use_qty) as room_gty,

              avg(case
                when s.special_percent = 0 and s.off_percent = 0
                then 0
                when s.special_percent = 0 AND s.off_percent > 0
                then s.off_percent
                ELSE s.special_percent
              END) AS final_percent,

              round(avg(case
                  when s.special_price = 0 AND s.gs_price = 0
                  then g.normal_price
                  when s.special_price = 0 AND s.gs_price > 0
                  then s.gs_price
                  ELSE s.special_price
               END),-2)  AS final_price

            FROM hi_goods AS g
            LEFT JOIN hi_sales AS s ON s.gs_id = g.index_no
            where s.gs_id = '".$gs_id."' and g.mb_id = '".$room_id."' and s.use_yn = '판매중' and (s.sales_date >= '".$check_in."' and s.sales_date < '".$check_out."') and g.goods_ca = 'R' and g.max_pplNum >= '".$total_num."'
            having ABS(DATEDIFF('".$check_in."', '".$check_out."')) = COUNT(*) ";

$result = sql_fetch($sql);

//쿼리 row세기
$sql = " select count(*) as room_row
          from hi_goods AS g
          LEFT JOIN hi_sales AS s ON s.gs_id = g.index_no
          where s.gs_id = '".$gs_id."' and g.mb_id = '".$room_id."' and s.use_yn = '판매중' and (s.sales_date >= '".$check_in."' and s.sales_date < '".$check_out."') and g.goods_ca = 'R' and g.max_pplNum >= '".$total_num."'
          having ABS(DATEDIFF('".$check_in."', '".$check_out."')) = COUNT(*) " ;

$num_row = sql_fetch($sql);

$count = $num_row['room_row'];

if($count != 0) {

$nor_price = number_format($result['normal_price']);
$discount = number_format($result['final_percent']);
$discount_price = number_format($result['final_price']);

//객실명 가져오기
$gname = $result['gname'];

//남은객실 수 구하기
$remain_room = $result['room_gty'];

//다음페이지에 get정보 넘기기
$room_info_str = "gcode=".$result['gcode']."&gs_id=".$result['gs_index']."&room_id=".$room_id."&type=".$type;
// $time_info_str = "&in_date=".$str_in_date."&out_date=".$str_out_date."&interval=".$interval."&chk_in_time=".$check_in_str."&chk_out_time=".$check_out_str;


    $return_arr[] = array(
        "gname" => $gname,
        "room_info_str" => $room_info_str,
        "day_interval" => $interval,
        "nor_price" => $nor_price,
        "discount" => $discount,
        "discount_price" => $discount_price,
        "remain_room" => $remain_room
    );

  // code : 200 설공
  // code : 999 실패
  $arr_f =  array("code"=> "200", "count"=> $count, "data"=>  $return_arr, "str_in_date" => $str_in_date, "str_out_date" => $str_out_date, "interval" => $interval, "chk_in_time" => $check_in_str, "chk_out_time" => $check_out_str);

} else {

  $arr_f =  array("code"=> "999", "count"=> $count, "data"=>  $return_arr, "str_in_date" => $str_in_date, "str_out_date" => $str_out_date, "interval" => $interval, "chk_in_time" => $check_in_str, "chk_out_time" => $check_out_str);

}

  echo json_encode($arr_f);

?>
