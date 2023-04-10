<?php
include_once("./_common.php");

// if(!$is_member) {
// 	goto_url(TB_MBBS_URL.'/login.php?url='.$urlencode);
// }

$tb['title'] = "통합검색";
include_once(TB_MAPP_PATH."/_yolo_head.php");

ini_set( "display_errors", 1 );

//슬라이드 번호
$c = 0;

//형태소분석기로 단어 나누기
$options = array('-d', '/opt/mecab-ko-dic-2.0.1-20150920');

$mecab = new \MeCab\Tagger($options);
$text = "$keyword";
$node = $mecab->parseToNode($text);

$string = array();

while($node){
    array_push($string,$node->getSurface());
   //$string = $node->getSurface();
  // var_dump($node->getFeature());
   $node = $node->getNext();
}

$cnt_string = count($string);

$search_add_query = '';

for($i=1; $i < $cnt_string-1; $i++) {
    if($i == $cnt_string-2 ) {
      $search_add_query .= "match (r.company_name, r.company_addr1, r.seller_item) against ('+*".$string[$i]."*' in boolean mode) ";
    } else {
      $search_add_query .= "match (r.company_name, r.company_addr1, r.seller_item) against ('+*".$string[$i]."*' in boolean mode) and ";
    }
}
    $search_original .= "match (r.company_name, r.company_addr1, r.seller_item) against ('+*".$keyword."*' in boolean mode) ";
//통합검색 무한스크롤 페이지별 나누기
$rows = 10;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($search_page == "") { $search_page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($search_page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($search_page-1)*$rows);

//인원수 페이지 설정값
$integrated_search = 'list';
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

$str_in_date = $in_month."월 ".substr($check_in, 8, 2)."일(".$in_yoil.") ";
$str_out_date = $out_month."월 ".substr($check_out, 8, 2)."일(".$out_yoil.") ";



//지역, 날짜, 인원수 조건 영역
include_once(TB_MSEARCH_THEME.'/list_head.skin.php');

//달력불러오기
include_once(TB_MROOM_THEME."/room_calendar.skin.php");




if($type == "room") {

  //연박일시
  if((int)$interval >= 2) {
    $price_parameters = ", truncate(avg(g.normal_price), 0) AS normal_price, truncate(avg(s.final_percent), 0) AS final_percent, truncate(avg(s.final_price), 0) AS final_price ";
  } else {
    $price_parameters = ", min(g.normal_price) AS normal_price, min(final_percent) AS final_percent, min(final_price) AS final_price";
  }


  $sql = 'sELECT * FROM (
              SELECT g.mb_id AS g_mb_id '.$price_parameters.'
              FROM hi_sales AS s
              LEFT JOIN hi_goods AS g ON s.gs_id = g.index_no
              WHERE s.use_yn = "판매중" AND (s.sales_date >= "'.get_session('start_time').'" and s.sales_date < "'.get_session('end_time').'") AND (s.gs_tot_qty - s.gs_use_qty) != 0 and g.goods_ca = "R" and g.max_pplNum >= '.$total_num.'
              GROUP BY g.gcode
              ORDER BY final_price
            ) AS f
            LEFT JOIN hi_room AS r ON r.mb_id = f.g_mb_id
            LEFT JOIN (SELECT seller_id, round(AVG(score), 1) AS avg_score, COUNT(score) AS cnt_score FROM hi_goods_review group by seller_id) AS rev ON rev.seller_id = f.g_mb_id
            where (('.$search_add_query.') or '.$search_original.') and normal_price IS NOT null
            GROUP BY mb_id
            order by final_price
            limit '.$from_record.', '.$rows.' ';

  $result = sql_query($sql);
  // echo $sql;

  include_once(TB_MROOM_THEME."/filter_modal.skin.php");

  include_once(TB_MSEARCH_THEME.'/room_list.skin.php');
} else if($type == "rent") {
  include_once(TB_MSEARCH_THEME.'/rent_list.skin.php');
}

//인원수 팝업
include_once(TB_MROOM_THEME."/number_people.skin.php");
// include_once("./_tail.php");
?>
