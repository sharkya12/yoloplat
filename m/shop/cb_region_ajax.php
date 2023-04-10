<?php
include_once("./_common.php");

  $cb_type = $_POST['cb_type'];
  $region_code = $_POST['region_code'];
  $region_val = $_POST['region_val'];

  $sql = " select * from hi_category where upcate = '$region_code' order by caterank";
  $result_row = sql_query($sql);

  echo '<ul class="region_list_ul">';

  echo '<li onclick="gotoList(\''.$cb_type.'\' , \''.$region_code.'\');"><h2>'.$region_val.' 전체</span></h2>';

  for($i=0; $row=sql_fetch_array($result_row); $i++){

    $catecode = $row['catecode'];
    $upcate = $row['upcate'];

    echo '<li onclick="gotoList2(\''.$cb_type.'\' , \''.$catecode.'\' , \''.$upcate.'\');" class="shadow_box auto_side"><span><div class="region_2">'.$row['catename'].'</div></span><span><img src="../img/chevron-small-right.png"></span></li>';
  }

  echo '</ul>';
?>
