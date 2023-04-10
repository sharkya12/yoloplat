<?php
$sql = "select * from hi_member where id= '{$member['id']}'";
$member = sql_fetch($sql);
$sql = "select * from hi_point where mb_id= '{$member['id']}' order by po_datetime desc";
$point_res = sql_query($sql);
$point_row = sql_num_rows($point_res);

?>

<section class="cpl_section">
  <table class="top_tit_side_3">
    <td>
      <span onclick="history.back();"><img src="../img/prev.png" /></span>
    </td>
    <td>
      포인트
    </td>
  </table>

  <div class="point_view">
    <h3>보유 포인트</h3>
    <h2><?php echo display_point($member['point']); ?></h2>
    <small>포인트는 유효기간 경과 시 자동 소멸합니다.</small>
  </div>
<!--
  <ul>
    <?php
      //조회한 포인트 사용 내역이 없으면
      if ($point_row == 0) {
    ?>
      <li c lass="none_point_li">
        포인트 사용내역이 없습니다.
      </li>
    <?php
      //조회한 포인트 사용 내역이 있으면
      } else {
        for ($i=0; $row=sql_fetch_array($point); $i++) {?>
          <li c lass="none_point_li">
            <?php echo $row['po_content'];?>
          </li>
    <?php
      }
    }
    ?>

  </ul>
-->
  <ul id="point_ul" style="border-top:10px solid #f4f4f4; border-bottom:30px solid #f4f4f4;">
		<?php
		$sum_point1 = $sum_point2 = $sum_point3 = 0;

		for($i=0; $row=sql_fetch_array($point_res); $i++) {

			$point1 = $point2 = 0;
			if($row['po_point'] > 0) {
				$point1 = '+' .number_format($row['po_point']);
				$sum_point1 += $row['po_point'];
			} else {
				$point2 = number_format($row['po_point']);
				$sum_point2 += $row['po_point'];
			}

			$expr = '';
			if($row['po_expired'] == 1)
				$expr = ' txt_expired';
		?>
        <li>
						<table style="width: 100%;">
							<tr>
								<td style="padding: 10px; width:70%;">
									<div style="font-size:15px;"><?php echo $row['po_content']; ?></div>
									<div style="margin-top:8px; font-size:13px;"><?php echo conv_date_format('y.m.d H:m', $row['po_datetime']); ?></div>
								</td>
								<td style="text-align: right; padding: 10px; vertical-align:middle; width:30%;">
									<div class="point_inout" style="font-size:16px;"><b><?php if($point1) echo $point1; else echo $point2; ?> 원</b></div>
									<span class="point_expdate<?php echo $expr; ?>">
                    <?php if($row['po_expired'] == 1) { ?>
                    만료 | <?php echo substr(str_replace('-', '.', $row['po_expire_date']), 2); ?>
                  <?php } else {?> 만료 | <?php echo $row['po_expire_date'] == '9999-12-31' ? '&nbsp;' : $row['po_expire_date']; }?>
                	</span>
								</td>
							</tr>
						</table>

        </li>
        <?php
        }

        if($i == 0)
            echo '<li class="empty_list">자료가 없습니다.</li>';
        else {
            if($sum_point1 > 0)
                $sum_point1 = "+" . number_format($sum_point1);
            $sum_point2 = number_format($sum_point2);
        }
        ?>
    </ul>




</section>
