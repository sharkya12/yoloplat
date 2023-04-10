<?php
include_once("./_common.php");

$room_id = $_POST['room_id'];
$type = $_POST['type'];
$check_in = $_POST['check_in'];
$check_out = $_POST['check_out'];
$total_num = $_POST['total_num'];
$str_in_date = $_POST['str_in_date'];
$str_out_date = $_POST['str_out_date'];
$in_holiday = $_POST['in_holiday'];
$out_holiday = $_POST['out_holiday'];

$room = get_room($room_id);



//체크인 요일 계산
$in_yoil =  get_yoil($check_in);
$out_yoil =  get_yoil($check_out);


//시작일과 종료일의 차이계산
$interval = get_date_interval($check_in, $check_out);

set_session('start_time', $check_in);
set_session('end_time', $check_out);
set_session('in_holiday', $in_holiday);
set_session('out_holiday', $out_holiday);


$sql = " select *, g.index_no AS gs_index, g.mb_id AS g_mb_id
          FROM hi_goods AS g
          LEFT JOIN hi_sales AS s ON s.gs_id = g.index_no
          where g.mb_id = '".$room_id."' and s.use_yn = '판매중' and (s.sales_date >= '".$check_in."' and s.sales_date < '".$check_out."') and g.goods_ca = 'R' and g.max_pplNum >= '".$total_num."'
          group by g.index_no
          having ABS(DATEDIFF('".$check_in."', '".$check_out."')) = COUNT(*)";
$result = sql_query($sql);


if(sql_num_rows($result) > 0) {
  for ($i=0; $row=sql_fetch_array($result); $i++) {


    // $goods_index_no = $row['index_no'];
    $goods_img = get_it_image($row['index_no'], $row['simg1'], 89, 107);
    $gname = $row['gname'];
    $std_capa = $row['standard_pplNum'];
    $max_capa = $row['max_pplNum'];


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

    //상품가격 날짜별로 평균값 계산하기
    // $sql = " select g.gname, g.index_no AS gs_index, g.mb_id AS g_mb_id, floor(avg(g.normal_price)) As normal_price
    //                 , floor(avg(s.off_percent)) as off_percent, floor(avg(s.gs_price)) AS gs_price
    //                 , floor(avg(s.special_percent)) as special_percent, floor(avg(s.special_price)) AS special_price
    //                 , ABS(DATEDIFF('".$check_in."', '".$check_out."')) AS day_interval, COUNT(*) AS day_count
    //           FROM hi_goods AS g
    //           LEFT JOIN hi_sales AS s ON s.gs_id = g.index_no
    //           where s.gs_id = '".$row['gs_index']."' and g.mb_id = '".$room_id."' and s.use_yn = '판매중' and (s.sales_date >= '".$check_in."' and s.sales_date < '".$check_out."') and (s.gs_tot_qty - s.gs_use_qty) != 0 and g.goods_ca = 'R' and g.max_pplNum >= '".$total_num."'
    //           having ABS(DATEDIFF('".$check_in."', '".$check_out."')) = COUNT(*) ";

    $sql = " select g.gname, g.index_no AS gs_index, g.mb_id AS g_mb_id, g.normal_price AS normal_price,

              avg(case
            		when s.special_percent = 0 and s.off_percent = 0
            		then 0
            		when s.special_percent = 0 AND s.off_percent > 0
            		then s.off_percent
            		ELSE s.special_percent
            	END) AS final_percent,

            	avg(case
                  when s.special_price = 0 AND s.gs_price = 0
                  then g.normal_price
                  when s.special_price = 0 AND s.gs_price > 0
                  then s.gs_price
                  ELSE s.special_price
               END)  AS final_price

            FROM hi_goods AS g
            LEFT JOIN hi_sales AS s ON s.gs_id = g.index_no
            where s.gs_id = '".$row['gs_index']."' and g.mb_id = '".$room_id."' and s.use_yn = '판매중' and (s.sales_date >= '".$check_in."' and s.sales_date < '".$check_out."') and (s.gs_tot_qty - s.gs_use_qty) != 0 and g.goods_ca = 'R' and g.max_pplNum >= '".$total_num."'
            having ABS(DATEDIFF('".$check_in."', '".$check_out."')) = COUNT(*) ";

    $row_sales = sql_fetch($sql);


    $nor_price = number_format($row_sales['normal_price']);
    $discount = number_format($row_sales['final_percent']);
    $discount_price = number_format($row_sales['final_price']);

    // //할인율 존재에 따른 할인가격, 특가가격 구분
    // $discount = '';
    // $discount_price = '';
    // //기본 할인율이 존재하고 특가 할인율이 존재 하지 않을때
    // if($row_sales['off_percent'] && !$row_sales['special_percent']){
    //   $discount = $row_sales['off_percent'];
    //   $discount_price = number_format($row_sales['gs_price']);
    // //기본 할인율이 존재하고 특가 할인율이 존재할때
    // } else if($row_sales['off_percent'] && $row_sales['special_percent']){
    //   $discount = $row_sales['special_percent'];
    //   $discount_price = number_format($row_sales['special_price']);
    // //둘다 존재 하지 않을때
    // } else if(!$row_sales['off_percent'] && !$row_sales['special_percent']){
    //   $discount = '0';
    //   $discount_price = number_format($row_sales['normal_price']);
    // }


    //숙박기간 구하기
    $day_interval = $row_sales['day_interval'];

    //남은객실 수 구하기
    $remain_room = $row['gs_tot_qty'] - $row['gs_use_qty'];



    //다음페이지에 get정보 넘기기
    $room_info_str = "gcode=".$row['gcode']."&gs_id=".$row['gs_index']."&room_id=".$room_id."&type=".$type;
    $time_info_str = "&in_date=".$str_in_date."&out_date=".$str_out_date."&interval=".$interval."&chk_in_time=".$check_in_str."&chk_out_time=".$check_out_str;

    $return_arr[] = array(
        "room_info_str" => $room_info_str,
        "time_info_str" => $time_info_str,
        "day_interval" => $interval,
        "goods_img" => $goods_img,
        "gname" => $gname,
        "std_capa" => $std_capa,
        "max_capa" => $max_capa,
        "check_in_str" => $check_in_str,
        "nor_price" => $nor_price,
        "discount" => $discount,
        "discount_price" => $discount_price,
        "remain_room" => $remain_room
    );

  }

  // code : 200 설공
  // code : 999 실패
  $arr_f =  array("code"=> "200", "data"=>  $return_arr, "str_in_date" => $str_in_date, "str_out_date" => $str_out_date, "interval" => $interval);

} else {

  $arr_f =  array("code"=> "999", "data"=>  $return_arr, "str_in_date" => $str_in_date, "str_out_date" => $str_out_date, "interval" => $interval);

}

  echo json_encode($arr_f);

?>
