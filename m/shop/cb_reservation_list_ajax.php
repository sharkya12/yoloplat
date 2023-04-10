<?php
include_once("./_common.php");

$total_count = $_POST['total_count'];
$page = $_POST['page']+1;
$mb_id = $_POST['mb_id'];
$rows = 5;

$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);


$sql = " select a.index_no as a_index_no, a.company_name as a_company_name
        ,b.company_img as b_company_img, c.company_img as c_company_img, d.company_img as d_company_img
        ,b.company_addr1 as b_company_addr, c.company_addr1 as c_company_addr, d.company_addr1 as d_company_addr
        ,a.*, b.*, c.*, d.*
        FROM hi_order AS a
        LEFT JOIN hi_room AS b ON a.seller_id = b.mb_id
        LEFT JOIN hi_rent AS c ON a.seller_id = c.mb_id
        LEFT JOIN hi_exp AS d ON a.seller_id = d.mb_id
        LEFT JOIN hi_goods AS e on a.gs_id = e.index_no
        WHERE a.mb_id = '{$mb_id}'
        ORDER BY a.od_time desc
        limit $from_record, $rows";

$order_res = sql_query($sql);

if(sql_num_rows($order_res) > 0) {
  for ($i= $from_record+1; $row = sql_fetch_array($order_res); $i++) {

    $in_yoil =  get_yoil($row['in_date']);
    $out_yoil =  get_yoil($row['out_date']);


    //체크인 월계산
    $in_month = substr($row['in_date'], 5, 2);
    if ($in_month < 10) {
      $in_month = str_replace('0','',$in_month);
    }

    //체크아웃 월계산
    $out_month = substr($row['out_date'], 5, 2);
    if ($out_month < 10) {
      $out_month = str_replace('0','',$out_month);
    }

    $in_year = substr($row['in_date'], 2, 2);
    $out_year = substr($row['out_date'], 2, 2);

    $str_in_date = $in_year.". ".$in_month.". ".substr($row['in_date'], 8, 2)." (".$in_yoil.") ";
    $str_out_date = $out_year.". ".$out_month.". ".substr($row['out_date'], 8, 2)." (".$out_yoil.") ";

    $str_chk_in = substr($row['in_date'], 11, 5);
    $str_chk_out = substr($row['out_date'], 11, 5);

    //리뷰 작성 남은일 구하기
    $timenow = date("Y-m-d");
    $chk_out_time = date($row['out_date']);

    $str_now = strtotime($timenow);
    $str_target = strtotime($chk_out_time."+10 days");

    $d_day_calc = date($str_target - $str_now);
    $d_day = ceil($d_day_calc / (60*60*24));



    $num = $i;
    $goods_ca = $row['goods_ca'];
    $b_company_img = $row['b_company_img'];
    $c_company_img = $row['c_company_img'];
    $d_company_img = $row['d_company_img'];
    $a_index_no = $row['a_index_no'];
    $a_company_name = $row['a_company_name'];
    $b_company_addr = $row['b_company_addr'];
    $c_company_addr = $row['c_company_addr'];
    $d_company_addr = $row['d_company_addr'];
    $gname = $row['gname'];
    $dan = $row['dan'];

    $return_arr[] = array(
        "num" => $num,
        "dan" => $dan,
        "goods_ca" => $goods_ca,
        "b_company_img" => $b_company_img,
        "c_company_img" => $c_company_img,
        "d_company_img" => $d_company_img,
        "a_index_no" => $a_index_no,
        "a_company_name" => $a_company_name,
        "b_company_addr" => $b_company_addr,
        "c_company_addr" => $c_company_addr,
        "d_company_addr" => $d_company_addr,
        "gname" => $gname,
        "str_in_date" => $str_in_date,
        "str_chk_in" => $str_chk_in,
        "str_out_date" => $str_out_date,
        "str_chk_out" => $str_chk_out,
        "d_day" => $d_day

    );
  }
  // code : 200 설공
  // code : 999 실패
  $arr_f =  array("code"=>"200","data"=> $return_arr, "total_page" => $total_page, "page" => $page);
}

else {
  $arr_f =  array("code"=> "999");
}

echo json_encode($arr_f);


// echo $test1;
?>
