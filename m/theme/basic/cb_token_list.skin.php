<?php
$sql = "select * from hi_member where id= '{$member['id']}'";
$member = sql_fetch($sql);

$sql = "select * from hi_tocken where mb_id= '{$member['id']}' order by to_datetime desc";
$token = sql_query($sql);
$token_row = sql_num_rows($token);

?>

<section class="cpl_section">
  <table class="top_tit_side_3">
    <td>
      <span onclick="history.back();"><img src="../img/prev.png" /></span>
    </td>
    <td>
      yolo코인
    </td>
    <td>
    <span><button type="button" onclick="location.href='<?php echo TB_MSHOP_URL; ?>/cb_token_deposit_withdraw.php';" class="mypage_set_btn">입출금 <img src="../img/chevron-small-right.png" /></button></span>
    </td>
  </table>

  <div class="point_view">
    <h3>보유 코인</h3>
    <h2><?php echo display_tocken($member['tocken']); ?></h2>
  </div>

  <div class="point_view">
    <h3>평가 금액</h3>
    <h2>미상장</h2>
  </div>

  <ul id="point_ul" style="border-top:10px solid #f4f4f4; border-bottom:10px solid #f4f4f4;">
    <?php
      //조회한 코인 내역이 없으면
      if ($token_row == 0) {
    ?>
      <li c lass="none_point_li">
        코인 보유내역이 없습니다.
      </li>
    <?php
      //조회한 코인 내역이 있으면
      } else {
        $sum_point1 = $sum_point2 = $sum_point3 = 0;

        for ($i=0; $row=sql_fetch_array($token); $i++) {

          $point1 = $point2 = 0;
          if($row['to_point'] > 0) {
            $point1 = '+' .number_format($row['to_point']);
            $sum_point1 += $row['to_point'];
          } else {
            $point2 = number_format($row['to_point']);
            $sum_point2 += $row['to_point'];
          }

          $expr = '';
      		  if($row['to_expired'] == 1)
            $expr = ' txt_expired';
        ?>

              <li>
                  <table style="width: 100%;">
                    <tr>
                      <td style="padding: 10px; width:70%;">
                        <div style="font-size:15px;"><?php echo $row['to_content']; ?></div>
                        <div style="margin-top:8px; font-size:13px;"><?php echo conv_date_format('y.m.d H:m', $row['to_datetime']); ?></div>
                      </td>
                      <td style="text-align: right; padding: 10px; vertical-align:middle; width:30%;">
                        <div class="point_inout" style="font-size:16px;"><b><?php if($point1) echo $point1; else echo $point2; ?> yoloc</b></div>
                        <span class="point_expdate<?php echo $expr; ?>">
                          <?php if($row['to_expired'] == 1) { ?>
                          만료 | <?php echo substr(str_replace('-', '.', $row['to_expire_date']), 2); ?>
                        <?php } else { echo $row['to_expire_date'] == '9999-12-31' ? '&nbsp;' : $row['to_expire_date']; }?>
                        </span>
                      </td>
                    </tr>
                  </table>

              </li>
    <?php
      }
    }
    ?>

  </ul>


</section>
