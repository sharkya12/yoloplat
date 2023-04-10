<?php
include_once("./_common.php");

$tb['title'] = "상세보기";
include_once(TB_MAPP_PATH."/_yolo_head.php");


if(get_cookie('ss_goods_idx')){
  $arr_ss_goods_idx = get_cookie('ss_goods_idx');
  $arr_tmps = explode("|", $arr_ss_goods_idx);

  if(!in_array($gs_id, $arr_tmps)){
    $ss_goods_idx = $gs_id."|".get_cookie('ss_goods_idx');

    set_cookie('ss_goods_idx', $ss_goods_idx, 86400*1 );
  }
}else{
  set_cookie('ss_goods_idx', $gs_id, 86400*1);
}

//달력에서 저장한 체크인,체크아웃 정보 가져오기
$check_in = get_session('start_time');
$check_out = get_session('end_time');

//숙박상품 정보 가져오기
$room = get_room($room_id);

// $sql = " select *, g.index_no AS gs_index, g.mb_id AS g_mb_id, g.normal_price AS normal_price, s.off_percent as off_percent, s.gs_price AS gs_price, s.special_percent as special_percent, s.special_price AS special_price
//           FROM hi_goods AS g
//           LEFT JOIN hi_sales AS s ON s.gs_id = g.index_no
//           where g.mb_id = '".$room_id."' and g.index_no = '{$gs_id}' and s.use_yn = '판매중' and (s.sales_date >= '".$check_in."' and s.sales_date < '".$check_out."') and (s.gs_tot_qty - s.gs_use_qty) != 0 and g.goods_ca = 'R' and g.max_pplNum >= '".$total_num."'
//           group by g.index_no
//           having ABS(DATEDIFF('".$check_in."', '".$check_out."')) = COUNT(*)";

// $sql = "select *, g.index_no AS gs_index, g.mb_id AS g_mb_id, floor(avg(g.normal_price)) As normal_price
//                 , floor(avg(s.off_percent)) as off_percent, floor(avg(s.gs_price)) AS gs_price
//                 , floor(avg(s.special_percent)) as special_percent, floor(avg(s.special_price)) AS special_price
//                 , ABS(DATEDIFF('".$check_in."', '".$check_out."')) AS day_interval, COUNT(*) AS day_count
//           FROM hi_goods AS g
//           LEFT JOIN hi_sales AS s ON s.gs_id = g.index_no
//           where s.gs_id = '{$gs_id}' and g.mb_id = '".$room_id."' and s.use_yn = '판매중' and (s.sales_date >= '".$check_in."' and s.sales_date < '".$check_out."') and (s.gs_tot_qty - s.gs_use_qty) != 0 and g.goods_ca = 'R' and g.max_pplNum >= '".$total_num."'
//           having ABS(DATEDIFF('".$check_in."', '".$check_out."')) = COUNT(*) ";

$sql =    " select * , g.index_no AS gs_index, g.mb_id AS g_mb_id, g.normal_price AS normal_price,

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
            where s.gs_id = '".$gs_id."' and g.mb_id = '".$room_id."' and s.use_yn = '판매중' and (s.sales_date >= '".$check_in."' and s.sales_date < '".$check_out."') and g.goods_ca = 'R' and g.max_pplNum >= '".$total_num."'
            having ABS(DATEDIFF('".$check_in."', '".$check_out."')) = COUNT(*) ";

$room_row = sql_fetch($sql);


$discount = number_format($room_row['final_percent']);
$discount_price = number_format($room_row['final_price']);


//남은객실 수 구하기
$remain_room = $room_row['gs_tot_qty'] - $room_row['gs_use_qty'];

//달력불러오기
include_once(TB_MROOM_THEME."/room_calendar.skin.php");

include_once(TB_MROOM_THEME.'/detail_view.skin.php');

?>
