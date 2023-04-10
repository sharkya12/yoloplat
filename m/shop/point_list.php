<?php
//작성자 : 최환
//작성일 : 2020-12-15
//내용 : 포인트 존의 품목리스트 출력

include_once("./_common.php");

/*
$sql = " select *
		   from shop_category
		  where catecode = '$ca_id'
		    and cateuse = '0'
			and find_in_set('$pt_id', catehide) = '0' ";
$ca = sql_fetch($sql);
if(!$ca['catecode'])
    alert('등록된 분류가 없습니다.');

$tb['title'] = $ca['catename'];
include_once("./_head.php");

$sql_search = " and (ca_id like '$ca_id%' or ca_id2 like '$ca_id%' or ca_id3 like '$ca_id%') ";
$sql_common = sql_goods_list($sql_search);

// 상품 정렬
if($sort && $sortodr)
	$sql_order = " order by {$sort} {$sortodr}, rank desc, index_no desc ";
else
	$sql_order = " order by rank desc, index_no desc ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt $sql_common ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$mod = 2; // 가로 출력 수
$rows = ($mod*9);
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * $sql_common $sql_order limit $from_record, $rows ";
$result = sql_query($sql);
*/

include_once("./_head.php");

//상품정렬
if($sort && $sortodr){
  if($sort != "readcount"){
    $sql_order = " order by cast({$sort} as unsigned ) {$sortodr} ";
  }else{
    $sql_order = "";
  }
}
else{
	$sql_order = "";
}

$sql_search = " and brandName like '$ca_id%'  ";
// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt from gift_goods where goodsStateCd = 'SALE' {$sql_search}";
$row = sql_fetch($sql);
$total_count = $row['cnt'];


$mod = 2; // 가로 출력 수
$rows = $page_rows ? (int)$page_rows : ($mod*10);
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * from gift_goods where goodsStateCd = 'SALE' {$sql_search} {$sql_order} limit $from_record, $rows ";

$result = sql_query($sql);


include_once(TB_MTHEME_PATH.'/point_list.skin.php');

//include_once("./_tail.php");
?>
