<div class="ms_container">
  <section class="ms_nav_section">
    <div>
      <button type="button" onclick="location.href='manager_settlement.php';"><i class="fas fa-chevron-left"></i></button>
      <h3>출금현황</h3>
    </div>
  </section>
</div>

<section class="earnings2">
  <h2>총 적립액</h2>
  <small>(현재기준 출금신청 가능금액)</small>
  <div>
    <h1>124,000<span>원</span></h1>
  </div>
</section>

<section class="request_section">
  <h3>출금요청</h3>
  <div class="info_box_div shadow_box">
    <div class="info_box auto_side">
      <h4>출금요청금액</h4>
      <div>
        <input type="text" /> 원 <small class="ex">(1,000원 단위로 입력하세요.)</small>
        <small class="ex2">
          총 적립액 124,000원 중 최대 <span>124,000</span> 원까지 출금 가능<br />
          1,000원 이상부터 출금 가능
        </small>
      </div>
    </div>
    <div class="info_box auto_side">
      <h4>입금계좌정보</h4>
      <?php if($bank_name && $bank_account && $bank_holder) { ?>
        <p>
          <?php echo $bank_name.' | '.$bank_account.' | '.$bank_holder; ?>
        </p>
      <?php } else { ?>
        <p>
          미등록
        </p>
      <?php } ?>
    </div>
    <div class="withdraw_btn">
      <button type="button" id="withdraw_btn">출금요청</button>
    </div>
  </div>
</section>

<section class="withdraw_list">
  <div class="ms_tit">
    <h3>출금 내역</h3>
  </div>
  <div class="select_date">
    <small>기간검색</small>
    <input type="date" id="w_start_date"/> ~ <input type="date" id="w_end_date"/>
  </div>
  <table>
    <tr>
      <th>신청날짜</th>
      <th>상태</th>
      <th>출금요청</th>
      <th>세금공제</th>
      <th>실수령액</th>
    </tr>
    <?php for($t=0;$t<7;$t++) { ?>
    <tr>
      <td>2021.10.10 00:12:33</td>
      <td>완료</td>
      <td>224,000 원</td>
      <td>7,392 원</td>
      <td>216,608 원</td>
    </tr>
    <?php } ?>
  </table>
</section>

<script>
  $(document).ready(function() {
    // 직접설정 날짜 오늘 - 내일 로 표시
    var date = new Date();
    var yyyy = date.getFullYear();
    var mm = date.getMonth()+1 > 9 ? date.getMonth()+1 : '0' + date.getMonth()+1;
    var dd = date.getDate() > 9 ? date.getDate() : '0' + date.getDate();
    var n_dd = Number(date.getDate() > 9 ? date.getDate() : '0' + date.getDate())+1;

    $("#w_start_date").val(yyyy+"-"+mm+"-"+dd);
    $("#w_end_date").val(yyyy+"-"+mm+"-"+n_dd);

    // 기간검색 오늘 css
    $("#today_label").css( {'background-color':'#3171e7','color':'#FFF','border':'1px solid #3171e7'} );
  });

  $("#withdraw_btn").click(function() {
    if(confirm("출금요청 하시겠습니까?")) {
        alert("출금요청이 완료되었습니다.");
    }

  });
</script>
