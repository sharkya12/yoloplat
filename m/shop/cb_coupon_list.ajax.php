<?php
include_once("./_common.php");

$coupon = $_POST['coupon'];
$mb_id = $_POST['mb_id'];
$total_count = $_POST['total_count'];

$sql_common = " from hi_coupon_log ";
$sql_search = " where mb_id = '{$mb_id}' ";
$sql_order  = " order by cp_wdate desc ";



if ($coupon == 'Y') {
$sql_search .= " and mb_use='0' and ( (cp_inv_type='0' and (cp_inv_edate = '9999999999' or cp_inv_edate > curdate())) or (cp_inv_type='1' and date_add(`cp_wdate`, interval `cp_inv_day` day) > now()) ) ";
$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];
$sql = " select * $sql_common $sql_search $sql_order ";
$res_avl = sql_query($sql);


if(!$total_count) {
  echo "<p class='\empty_list\'>자료가 없습니다.</p>";
} else {

  for($i=0; $row=sql_fetch_array($res_avl); $i++) {
    // 할인금액(율)
    if($row['cp_sale_type'] == '0') {
      if($row['cp_sale_amt_max'] > 0)
        $cp_sale_amt_max = "&nbsp;(최대 ".display_price($row['cp_sale_amt_max']).")";
      else
        $cp_sale_amt_max = "";

      $sale_amt = $row['cp_sale_percent']. '%' . $cp_sale_amt_max;
    } else {
      $sale_amt = display_price($row['cp_sale_amt']);
    }

    // 쿠폰 사용기한
    if($row['cp_inv_type'] == '0') {
      if($row['cp_inv_sdate'] == '9999999999') $cp_inv_sdate = '무제한';
      else $cp_inv_sdate = $row['cp_inv_sdate'];

      if($row['cp_inv_edate'] == '9999999999') $cp_inv_edate = '무제한';
      else $cp_inv_edate = $row['cp_inv_edate'];

      if($row['cp_inv_sdate'] == '9999999999' && $row['cp_inv_edate'] == '9999999999')
        $inv_date = '무제한';
      else
        $inv_date = $cp_inv_sdate . " ~ " . $cp_inv_edate;
    } else {
      $inv_date = '다운로드 후 ' . $row['cp_inv_day']. '일간';
    }?>
    <ul>
      <li class="none_point_li">
        <div class="coupon_st">
          <h3><?php echo $row['cp_subject']?></h3>
          <h3><?php echo $sale_amt; ?> 할인</h3>
          <small><?php echo $inv_date; ?></small>
          <div>사용가능</div>
        </div>
        <div class="coupon_se">
          <div>
            <span>투숙 조건</span> <span>[<?php echo $row['cp_week_day']; ?>] 사용 가능</span>
          </div>
          <div>
            <span>사용 조건</span> <span><?php echo $row['cp_explan']?></span>
          </div>
        </div>
      </li>
    </ul>

<?php
    }
  }
}

else if ($coupon == 'N') {

$sql_search .= " and (mb_use='1' or (cp_inv_type='0' and cp_inv_edate != '9999999999' and cp_inv_edate < curdate()) or (cp_inv_type='1' and date_add(`cp_wdate`, interval `cp_inv_day` day) < now()) ) ";
$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];
$sql = " select * $sql_common $sql_search $sql_order ";
$res_unavl = sql_query($sql);

  if(!$total_count) {
  echo "<p class='\empty_list\'>자료가 없습니다.</p>";
  } else {
    for($i=0; $row=sql_fetch_array($res_unavl); $i++) {
      // 할인금액(율)
      if($row['cp_sale_type'] == '0') {
        if($row['cp_sale_amt_max'] > 0)
          $cp_sale_amt_max = "&nbsp;(최대 ".display_price($row['cp_sale_amt_max']).")";
        else
          $cp_sale_amt_max = "";

        $sale_amt = $row['cp_sale_percent']. '%' . $cp_sale_amt_max;
      } else {
        $sale_amt = display_price($row['cp_sale_amt']);
      }

      // 쿠폰 사용기한
      if($row['cp_inv_type'] == '0') {
        if($row['cp_inv_sdate'] == '9999999999') $cp_inv_sdate = '무제한';
        else $cp_inv_sdate = $row['cp_inv_sdate'];

        if($row['cp_inv_edate'] == '9999999999') $cp_inv_edate = '무제한';
        else $cp_inv_edate = $row['cp_inv_edate'];

        if($row['cp_inv_sdate'] == '9999999999' && $row['cp_inv_edate'] == '9999999999')
          $inv_date = '무제한';
        else
          $inv_date = $cp_inv_sdate . " ~ " . $cp_inv_edate;
      } else {
        $inv_date = '다운로드 후 ' . $row['cp_inv_day']. '일간';
      }?>
      <ul>
        <li class="none_point_li">
          <div class="coupon_st">
            <h3><?php echo $row['cp_subject']?></h3>
            <h3><?php echo $sale_amt; ?> 할인</h3>
            <small><?php echo $inv_date; ?></small>
            <div>사용 or 기간만료된 쿠폰</div>
          </div>
          <div class="coupon_se">
            <div>
              <span>투숙 조건</span> <span>[<?php echo $row['cp_week_day']; ?>] 사용 가능</span>
            </div>
            <div>
              <span>사용 조건</span> <span><?php echo $row['cp_explan']?></span>
            </div>
          </div>
        </li>
      </ul>

<?php
    }
  }
}
?>
