<div class="ms_container">
  <section class="ms_nav_section">
    <div>
      <button type="button" onclick="history.back();"><i class="fas fa-chevron-left"></i></button>
      <h3>상세내역</h3> <small>(<?php echo $datetime; ?>)</small>
    </div>
  </section>

  <section class="s_detail_table_section">
    <table>
      <tr>
        <th>업체명</th>
        <th>예약번호</th>
        <th>적립금액</th>
        <th>상태</th>
        <th>적립일</th>
      </tr>
      <?php for($i=0; $row=sql_fetch_array($result); $i++) { ?>
      <tr>
        <td><?php echo $row['company_name']; ?></td>
        <td><?php echo $row['pp_rel_action']; ?></td>
        <td><?php echo number_format($row['pp_pay']); ?>원</td>
        <?php if($row['pp_yn'] == 'N') { ?>
          <td>
            <small><?php echo $row['out_date']; ?></small>
            적립예정
          </td>
          <td></td>
        <?php } else if($row['pp_yn'] == 'Y') { ?>
          <td>
            적립완료
          </td>
          <td><small><?php echo $row['out_date']; ?></small></td>
        <?php } ?>
      </tr>
      <?php } ?>
    </table>
  </section>

</div>
