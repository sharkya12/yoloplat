<?php
include_once("./_common.php");

// if(!$is_member) {
// 	goto_url(TB_MBBS_URL.'/login.php?url='.$urlencode);
// }

 if ($cb_type == 'CT') { // 카펠
   $cate_name = '카텔';
   $tb['title'] = "카텔 리스트";
   $table_nm = 'hi_room';
   $where_seller_item = 'seller_item = "카텔" and';
 }else if($cb_type == 'HT'){ //호텔
   $cate_name = '호텔';
   $tb['title'] = "호텔 리스트";
   $table_nm = 'hi_room';
   $where_seller_item = 'seller_item = "호텔" and';
 }else if($cb_type == 'MT'){ //모텔
   $cate_name = '모텔';
   $tb['title'] = "모텔 리스트";
   $table_nm = 'hi_room';
   $where_seller_item = 'seller_item = "모텔" and';
 }else if($cb_type == 'PS'){ // 펜션/풀빌라
   $cate_name = '펜션/풀빌라';
   $tb['title'] = "펜션/풀빌라 리스트";
   $table_nm = 'hi_room';
   $where_seller_item = 'seller_item In("펜션", "풀빌라") and';
 }else if($cb_type == 'RT'){ //렌트카
   $cate_name = '렌트카';
   $tb['title'] = "렌트카 리스트";
   $table_nm ='hi_rent a, ( SELECT mb_id, car_seg AS vhcl_Clsfc FROM hi_goods WHERE car_seg="N" group by mb_id) b';
   $where_seller_item = 'a.mb_id = b.mb_id and';
 }else if($cb_type == 'KP'){ //캠핑카
   $cate_name = '캠핑카';
   $tb['title'] = "캠핑카 리스트";
   $table_nm = 'hi_rent a, ( SELECT mb_id, car_seg AS vhcl_Clsfc FROM hi_goods WHERE car_seg="K" group by mb_id) b';
   $where_seller_item = 'a.mb_id = b.mb_id and';
 }else if($cb_type == 'EX'){ //체험
   $cate_name = '체험';
   $tb['title'] = "체험 리스트";
   $table_nm = 'hi_exp';
   $where_seller_item = '';
 }else if($cb_type == 'EV'){ //이벤트
   $cate_name = '이벤트';
   $tb['title'] = "이벤트 리스트";
   $table_nm = 'shop_board_44';
 }

$catecode = $_GET['catecode']; //지역 카테고리 코드
$upcate = $_GET['upcate']; //세부 지역 카테고리 코드

//검색으로 상품을 검색했을시
if($search && $cate && $keyword){

  //모든 카테고리 통합 검색
  if($cate == 'all'){
    $sql = ' select mb_id, ca_id, company_name, company_img from hi_room where company_name LIKE "%'.$keyword.'%" or company_addr1 like "%'.$keyword.'%" UNION select mb_id, ca_id, company_name from hi_rent where company_name LIKE "%'.$keyword.'%" or company_addr1 like "%'.$keyword.'%" UNION select mb_id, ca_id, company_name from hi_exp where company_name LIKE "%'.$keyword.'%" or company_addr1 like "%'.$keyword.'%" ';
  }
  //카텔, 호텔, 모텔, 펜션/풀빌라
  else if($cate == 'CT' || $cate == 'HT' || $cate == 'MT' || $cate == 'PS' ) {

    $table = 'hi_room';

    //펜션/풀빌라일시
    if($cate == 'PS'){
      // '/' 기준으로 문자열 자르기
      $ps_str = explode('/', $cate_name);
      //펜션
      $cate_name_PS1 = $ps_str[0];
      $cate_name_PS2 = $ps_str[1];

      $condition = ' where (seller_item = "'.$cate_name_PS1.'" or seller_item = "'.$cate_name_PS2.'") and (company_name LIKE "%'.$keyword.'%" or company_addr1 like "%'.$keyword.'%" )';
    } else {
      $condition = ' where seller_item = "'.$cate_name.'" and (company_name LIKE "%'.$keyword.'%" or company_addr1 like "%'.$keyword.'%" )';
    }
    $sql = " select * from ".$table.$condition;
  }
  //렌트카 일때
  else if($cate == 'RT') {
    $table = 'hi_rent a, ( SELECT mb_id, car_seg AS vhcl_Clsfc FROM hi_goods WHERE car_seg="N" group by mb_id) b';
    $condition = ' where a.mb_id = b.mb_id and a.seller_item = "'.$cate_name.'" and (a.company_name LIKE "%'.$keyword.'%" or a.company_addr1 like "%'.$keyword.'%" )';
    $sql = " select * from ".$table.$condition;
  }
  //캠핑카 일때
  else if($cate == 'KP') {
    $table = 'hi_rent a, ( SELECT mb_id, car_seg AS vhcl_Clsfc FROM hi_goods WHERE car_seg="K" group by mb_id) b';
    $condition = ' a.mb_id = b.mb_id and where a.seller_item = "'.$cate_name.'" and (a.company_name LIKE "%'.$keyword.'%" or a.company_addr1 like "%'.$keyword.'%" )';
    $sql = " select * from ".$table.$condition;
  }
  //체험 일때
  else if($cate == 'EX') {
    $table = 'hi_exp';
    $condition = ' where seller_item = "'.$cate_name.'" and (company_name LIKE "%'.$keyword.'%" or company_addr1 like "%'.$keyword.'%" )';
    $sql = " select * from ".$table.$condition;
  }


} else {
  //이벤트 메뉴를 클릭했을시
  if($cb_type == 'EV'){
    $sql = " select * from {$table_nm} where btype = '2' order by fid desc, thread asc limit 0, 15";
  }
  //전체 지역으로만 존재할때
  else if($catecode && !$upcate){
    $sql = " select * from {$table_nm} where ". $where_seller_item ." state = '1' and LEFT(ca_id, 3) = {$catecode}";
  }
  //전체 지역 및 세부지역 모두 존재할때
  else if($catecode && $upcate){
    $sql = " select * from {$table_nm} where ". $where_seller_item ." state = '1' and ca_id = '{$catecode}'";
  }
  //아무 조건이 없을떄
  else {
    $sql = " select * from {$table_nm} where ". $where_seller_item ." state = '1' ";
  }
}

echo $sql;
$result = sql_query($sql);
include_once("./_head_test.php");

if($in_date == "" && $out_date == ""){

  $in_date = TB_TIME_YMD; //현재 날짜에 1일을 뺌
  $out_date = TB_TIME_YMD;

}
// 체크인 날짜와 체크아웃 날짜가 같으면 체크인날짜의 -1 함.
if ($in_date == $out_date) {
  $out_date = date("Y-m-d", strtotime($in_date." +1 days")); //현재 날짜에 1일을 뺌
}

$interval = get_date_interval($in_date, $out_date); //시작일과 종료일의 차이계산

if($cb_type == 'EV'){
    include_once(TB_MTHEME_PATH.'/carbang_event_list_test.skin.php');
} else if($search){
    include_once(TB_MTHEME_PATH.'/carbang_list_search_test.skin.php');
} else {
    include_once(TB_MTHEME_PATH.'/carbang_list_car_test.skin.php');
}

// include_once("./_tail.php");
?>
