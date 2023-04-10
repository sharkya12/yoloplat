<?php
  include_once("./_common.php");

  $num_rows_index = $_POST['num_rows_index'];
  $type = $_POST['type'];
  $catecode = $_POST['catecode'];
  $upcate = $_POST['upcate'];
  $check_in = $_POST['check_in'];
  $check_out = $_POST['check_out'];
  $total_num = $_POST['total_num'];

  $seller_item = fn_seller_type($type);

  $seller_query = "";
  $comma = ",";
  $item_count = count($seller_item);

    if ($item_count == 0) {
      $seller_query .= "'".$seller_item[$i]."'";
    }else{
      for ($i=0; $i < $item_count; $i++) {
        if ($i == $item_count-1){
          $seller_query .= '"'.$seller_item[$i].'"';
        }else{
          $seller_query .= '"'.$seller_item[$i].'"'.$comma;
        }
      }
    }
  $seller_query = " seller_item in (".$seller_query.")";

  //(String) 1 + 0 = 10 만들고
  // 11부터 20까지 min max 만들기
  $min = $num_rows_index."0"+0; // 10, 20, 30, 40 ....
  $max = $min + 10; // 20, 30, 40, 50 ...

  $return_arr = array();

  $region_condition = '';
  //지역 세션값이 존재하지 않으면 -> large로 보여주기
  if(!$catecode){
    $region_condition = 'and LEFT(r.ca_id, 3) = "001"';
  }
  //지역 세션값이 존재하면 -> medium으로 보여주기
  else if($catecode) {
    //전체지역으로만 존재할때
    if($catecode && !$upcate){
      $region_condition = " and LEFT(r.ca_id, 3) = ".$catecode;
    }
    // //전체 지역 및 세부지역 모두 존재할때
    else if($catecode && $upcate){
      $region_condition = " and r.ca_id = '".$catecode."'";
    }
  }

  $sql = 'sELECT * FROM (
            SELECT g.mb_id AS g_mb_id, g.normal_price AS normal_price, s.off_percent as off_percent, s.gs_price AS gs_price, s.special_percent as special_percent, s.special_price AS special_price
            FROM hi_sales AS s
            LEFT JOIN hi_goods AS g ON s.gs_id = g.index_no
            WHERE s.use_yn = "판매중" AND (s.sales_date BETWEEN "'.$check_in.'" AND "'.$check_out.'") AND (s.gs_tot_qty - s.gs_use_qty) != 0 and g.goods_ca = "R" and g.max_pplNum >= '.$total_num.'
            GROUP BY g.mb_id
            ) AS f LEFT JOIN hi_room AS r ON f.g_mb_id = r.mb_id
            where '.$seller_query.$region_condition.'
            ORDER BY f.gs_price, f.special_price
            limit '.$min.', '.$max;
  $result = sql_query($sql);

  if(sql_num_rows($result) > 0) {
    for($i=0; $row=sql_fetch_array($result); $i++) {

      $mb_id = $row['mb_id'];
      $company_img = $row['company_img'];
      $company_name = $row['company_name'];
      $normal_price = $row['normal_price'];
      $off_percent = $row['off_percent'];
      $special_price = $row['special_price'];
      $special_percent = $row['special_percent'];
      $location_desc = $row['location_desc'];

      $use_mon = $row['use_mon'];
      $use_tue = $row['use_tue'];
      $use_wed = $row['use_wed'];
      $use_thu = $row['use_thu'];
      $use_fri = $row['use_fri'];
      $use_sat = $row['use_sat'];
      $use_sun = $row['use_sun'];

      $sql = " select min(normal_price) AS normal_price, min(goods_price) AS goods_price, discount AS discount FROM hi_goods where mb_id = '{$row['mb_id']}'";
      $price_row = sql_fetch($sql);

      $daily = array('일','월','화','수','목','금','토'); //요일을 배열로
      $weekday = $daily[date('w')]; //오늘 요일

      //체크인 시간
      if($weekday == '월') { $mon = json_decode($row['use_mon'], true); $check_in_str = $mon["data"][0]["in_time"]; }
      if($weekday == '화') { $tue = json_decode($row['use_tue'], true); $check_in_str = $tue["data"][0]["in_time"]; }
      if($weekday == '수') { $wed = json_decode($row['use_wed'], true); $check_in_str = $wed["data"][0]["in_time"]; }
      if($weekday == '목') { $thu = json_decode($row['use_thu'], true); $check_in_str = $thu["data"][0]["in_time"]; }
      if($weekday == '금') { $fri = json_decode($row['use_fri'], true); $check_in_str = $fri["data"][0]["in_time"]; }
      if($weekday == '토') { $sat = json_decode($row['use_sat'], true); $check_in_str = $sat["data"][0]["in_time"]; }
      if($weekday == '일') { $sun = json_decode($row['use_sun'], true); $check_in_str = $sun["data"][0]["in_time"]; }
      //공휴일 어케 해야지?
      // $holiday = json_decode($row['use_hol'], true);

      //할인율 존재에 따른 할인가격, 특가가격 구분
      $discount = '';
      $discount_price = '';
      //기본 할인율이 존재하고 특가 할인율이 존재 하지 않을때
      if($row['off_percent'] && !$row['special_percent']){
        $discount = $row['off_percent'];
        $discount_price = $row['gs_price'];
      //기본 할인율이 존재하고 특가 할인율이 존재할때
      } else if($row['off_percent'] && $row['special_percent']){
        $discount = $row['special_percent'];
        $discount_price = $row['special_price'];
      //둘다 존재 하지 않을때
      } else if(!$row['off_percent'] && !$row['special_percent']){
        $discount = '0';
        $discount_price = $row['normal_price'];
      }

      $normal_price = display_price($price_row['normal_price']);
      $discount_price = display_price($discount_price);

      $return_arr[] = array(
          "mb_id" => $mb_id,
          "company_img" => $company_img,
          "company_name" => $company_name,
          "normal_price" => $normal_price,
          "discount" => $discount,
          "discount_price" => $discount_price,
          "check_in_str" => $check_in_str,
          "location_desc" => $location_desc
      );
  }
  // code : 200 설공
  // code : 999 실패
  $arr_f =  array("code"=>"200","data"=> $return_arr ,"check_in" => $check_in, "check_out" => $check_out);
}

else {
  $arr_f =  array("code"=> "999");
}

echo json_encode($arr_f);

?>
