<?php
include_once("./_common.php");

$value = $_POST['value'];
// 숙박일때 업체유형 불러오기

$sql = " select catecode from hi_office_category where catename = '".$value."'" ;
$result = sql_fetch($sql);
$catacode = $result['catecode'];


//지역지사명 가져오기
$sql2 = " sELECT * FROM hi_office_category WHERE LEFT(catecode, 3) = '".$catacode."' AND upcate != 0 ORDER BY caterank";
$result2 = sql_query($sql2);


echo option_selected('', $hi_member_row['rgn_branch'] ,'입력필수');
for($i=0; $row=sql_fetch_array($result2); $i++) {
  // echo option_selected($row['catename'], $hi_member_row['rgn_branch'], $row['catename']);
  echo "<option id=".$row['office_id']." value=".$row['catename'].">".$row['catename']."</option>";
  // $rgn_branch = $hi_member_row['rgn_branch'];
  // $catename = $row['catename'];
  // $office_id = $row['office_id'];
  //
  // $return_arr[] = array(
  //     "catename" => $catename,
  //     "rgn_branch" => $rgn_branch,
  //     "office_id" => $office_id
  // );

}

// $arr_f =  array("data"=> $return_arr);
// echo json_encode($arr_f);
?>
