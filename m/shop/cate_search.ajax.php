<?php
include_once("./_common.php");

$cb_type = $_POST['cate_id'];
$keyword = $_POST['keyword'];

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
  $table_nm ='hi_rent';
  $where_seller_item = '';
}else if($cb_type == 'KP'){ //캠핑카
  $cate_name = '캠핑카';
  $tb['title'] = "캠핑카 리스트";
  $table_nm = 'hi_rent';
  $where_seller_item = '';
}else if($cb_type == 'EX'){ //체험
  $cate_name = '체험';
  $tb['title'] = "체험 리스트";
  $table_nm = 'hi_exp';
  $where_seller_item = '';
}

//카텔, 호텔, 모텔, 펜션/풀빌라
if($cb_type == 'CT' || $cb_type == 'HT' || $cb_type == 'MT' || $cb_type == 'PS' ) {

  $table = 'hi_room';

  //펜션/풀빌라일시
  if($cb_type == 'PS'){
    // '/' 기준으로 문자열 자르기
    $ps_str = explode('/', $cate_name);
    //펜션
    $cate_name_PS1 = $ps_str[0];
    $cate_name_PS2 = $ps_str[1];

    $condition = ' where (seller_item = "'.$cate_name_PS1.'" or seller_item = "'.$cate_name_PS2.'") and (company_name LIKE "%'.$keyword.'%" or company_addr1 like "%'.$keyword.'%" )';
  } else {
    $condition = ' where seller_item = "'.$cate_name.'" and (company_name LIKE "%'.$keyword.'%" or company_addr1 like "%'.$keyword.'%" )';
  }
} else if($cb_type == 'RT' || $cb_type == 'KP') {
  $table = 'hi_rent';
  $condition = ' where seller_item = "'.$cate_name.'" and (company_name LIKE "%'.$keyword.'%" or company_addr1 like "%'.$keyword.'%" )';
} else if($cb_type == 'EX') {
  $table = 'hi_exp';
  $condition = ' where seller_item = "'.$cate_name.'" and (company_name LIKE "%'.$keyword.'%" or company_addr1 like "%'.$keyword.'%" )';
}

$sql = " select * from ".$table.$condition;
$result = sql_query($sql);

$return_arr = array();

for($i=0; $row=sql_fetch_array($result); $i++){
  $mb_id = $row['mb_id'];
  $ca_id = $row['ca_id'];
  $company_name = $row['company_name'];
  $company_img = $row['company_img'];
  $goods_price = $row['goods_price'];

  $return_arr[] = array(
  "mb_id" => $mb_id,
  "ca_id" => $ca_id,
  "company_name" => $company_name,
  "company_img" => $company_img,
  "goods_price" => $goods_price
  );
}

echo json_encode($return_arr);
?>
