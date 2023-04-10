<?php
include_once("./_common.php");

$index_no = $_POST['review_index'];
$mb_id = $_POST['mb_id'];

$sql = " select a.index_no as a_index_no, a.company_name as a_company_name
        ,b.company_img as b_company_img, c.company_img as c_company_img, d.company_img as d_company_img
        ,b.company_addr1 as b_company_addr, c.company_addr1 as c_company_addr, d.company_addr1 as d_company_addr
        ,a.*, b.*, c.*, d.*, e.simg1 as e_image, a.seller_id as a_seller_id, a.gs_id as a_gs_id
        FROM hi_order AS a
        LEFT JOIN hi_room AS b ON a.seller_id = b.mb_id
        LEFT JOIN hi_rent AS c ON a.seller_id = c.mb_id
        LEFT JOIN hi_exp AS d ON a.seller_id = d.mb_id
        LEFT JOIN hi_goods AS e on a.gs_id = e.index_no
        WHERE a.mb_id = '{$mb_id}' and a.index_no = '{$index_no}'";

$result = sql_fetch($sql);

$goods_ca = $result['goods_ca'];
$a_company_name = $result['a_company_name'];
$gname = $result['gname'];
$image = $result['e_image'];
$seller_id = $result['a_seller_id'];
$gs_id = $result['a_gs_id'];


$arr_f = array("seller_id"=> $seller_id, "gs_id"=> $gs_id, "goods_ca"=> $goods_ca, "a_company_name"=> $a_company_name, "gname"=> $gname, "image"=> $image );

echo json_encode($arr_f);
?>
