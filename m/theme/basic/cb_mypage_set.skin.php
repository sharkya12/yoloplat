
<div class="mypage_set_section">
  <table class="top_tit_side_3">
    <td>
      <span onclick="history.back();"><img src="../img/prev.png" /></span>
    </td>
    <td>
    </td>
    <td>
      <div>

      </div>
    </td>
  </table>
  <h2>내 정보</h2>
  <small>개인정보 보호를 위해 내 정보는 모두 안전하게 암호화됩니다.</small>

  <div class="set_input_div">
    <h5>예약자 이름</h5>
    <!-- 예약자 이름이 설정이 안 됬을 시 -->
    <button type="button" data-toggle="modal" data-target="#Modal">예약자 이름을 입력해주세요.</button>
    <!-- 예약자 이름이 설정 되어 있을 시 -->
    <!-- <button type="button">홍길동</button> -->
  </div>

  <div class="set_input_div">
    <h5>생년월일</h5>
    <button type="button" data-toggle="modal" data-target="#Modal2">870101</button>
  </div>

  <div class="set_input_div">
    <h5>휴대폰 번호</h5>
    <button type="button" data-toggle="modal" data-target="#Modal3" id="phone_change_pop">01012345678</button>
  </div>

  <div class="set_bottom_btn">
    <button type="button" onclick="location.href='<?php echo TB_MBBS_URL;?>/logout.php';">로그아웃</button>
    <button type="button">회원탈퇴</button>
  </div>

</div>

<div class="full-screen-modal">
  <!-- Modal -->
  <div class="modal" id="Modal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-body">
          <table class="top_tit_side_3">
            <td>
              <span data-dismiss="modal"><img src="../img/prev.png" /></span>
            </td>
            <td>
            </td>
            <td>
              <div>

              </div>
            </td>
          </table>
          <div class="mypage_set_modal">
            <h5>예약자 이름</h5>
            <input type="text" id="name" placeholder="예약자 이름을 입력해주세요."/>
            <button type="button" id="name_reset"><img src="../img/input_reset.png" /></button>
          </div>

          <button type="button" id="basic_info_btn1" class="disabled_btn" disabled data-dismiss="modal">변경 저장</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal end -->

  <!-- Modal -->
  <div class="modal" id="Modal2" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-body">
          <table class="top_tit_side_3">
            <td>
              <span data-dismiss="modal"><img src="../img/prev.png" /></span>
            </td>
            <td>
            </td>
            <td>
              <div>

              </div>
            </td>
          </table>
          <div class="mypage_set_modal">
            <h5>생년월일</h5>
            <input type="text" id="birth" value="870101" maxlength="6" numberOnly/>
            <button type="button" id="birth_reset"><img src="../img/input_reset.png" /></button>
          </div>

          <button type="button" id="basic_info_btn2" class="disabled_btn" disabled data-dismiss="modal">변경 저장</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal end -->

  <!-- Modal -->
  <div class="modal fade modal" id="Modal3" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-body">
          <table class="top_tit_side_3">
            <td>
              <span data-dismiss="modal"><img src="../img/prev.png" /></span>
            </td>
            <td>
            </td>
            <td>
              <div>

              </div>
            </td>
          </table>

          <div class="mypage_set_modal">
            <h5>휴대폰번호</h5>
            <input type="text" id="phone" value="01012345678" maxlength="12" numberOnly/>
            <button type="button" id="ph_reset"><img src="../img/input_reset.png" /></button>

            <h5>인증번호</h5>
            <input type="text" id="ctnum" maxlength="4" numberOnly/>
            <button type="button" id="ctnum_reset"><img src="../img/input_reset.png" /></button>
          </div>

          <button type="button" id="basic_info_btn3" class="disabled_btn">인증번호 전송</button>
          <button type="button" id="basic_info_btn4" class="disabled_btn" disabled data-dismiss="modal">인증번호 확인</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal end -->
</div>


<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<script>
  $(document).ready(function() {
    if($("#name").val().length > 0) {
      $("#name_reset").show();
    }
    if($("#birth").val().length > 0) {
      $("#birth_reset").show();
    }
    if($("#phone").val().length > 0) {
      $("#ph_reset").show();
    }
  });

  $("#basic_info_pop").click(function() {
    $("#name").val("");
    $("#birth").val("");
    $("#basic_info_btn").attr("disabled", true);
  });

  $(".mypage_set_modal input").keyup(function() {
    // input 이름 리셋 버튼
    if($("#name").val().length > 0) {
      $("#name_reset").show();
    } else {
      $("#name_reset").hide();
    }

    // input 이름값이 한 자리 보다 크면 버튼 활성화
    if($("#name").val().length > 1) {
      $("#basic_info_btn1").prop("disabled", false);
    } else {
      $("#basic_info_btn1").prop("disabled", true);
    }

    // input 생년월일 리셋 버튼
    if($("#birth").val().length > 0) {
      $("#birth_reset").show();
    } else {
      $("#birth_reset").hide();
    }

    // input 생년월일 6자리 입력 시 버튼 활성화
    if($("#birth").val().length == 6) {
      $("#basic_info_btn2").prop("disabled", false);
    } else {
      $("#basic_info_btn2").prop("disabled", true);
    }

    // input 폰번호 리셋 버튼
    if($("#phone").val().length > 0) {
      $("#ph_reset").show();
    } else {
      $("#ph_reset").hide();
    }

    // input 휴대폰번호 10자리 입력 시 버튼 활성화
    if($("#phone").val().length >= 10) {
      $("#basic_info_btn3").prop("disabled", false);
    } else {
      $("#basic_info_btn3").prop("disabled", true);
    }

    if($("#ctnum").val().length == 4) {
      $("#basic_info_btn4").prop("disabled", false);
    } else {
      $("#basic_info_btn4").prop("disabled", true);
    }

  });

  $("#name_reset").click(function() {
    $("#name").val("");
    $("#name_reset").hide();
  });

  $("#birth_reset").click(function() {
    $("#birth").val("");
    $("#birth_reset").hide();
  });

  $("#ph_reset").click(function() {
    $("#phone").val("");
    $("#ph_reset").hide();
  });

  $("#basic_info_btn3").click(function() {
    $("#basic_info_btn3").hide();
    $("#basic_info_btn4").show();
  })

  // $("#phone_change_pop").click(function() {
  //   $("#phone").attr("disabled", false);
  //   $("#phone").val("01000000000");
  //   $("#phone_crtf").hide();
  //   $("#phone_crtf").val("");
  //   $("#phone_crtf_lb").hide();
  //   $("#phone_crtf_btn").show();
  //   $("#phone_crtf_btn").attr("disabled", true);
  //   $("#phone_change_btn").hide();
  //   $("#phone_change_btn").attr("disabled", true);
  // });

  // $("#Modal2 input").keyup(function() {
  //   if($("#phone").val() != '01000000000' && $("#phone").val().length >= 10) {
  //     $("#phone_crtf_btn").attr("disabled", false);
  //   } else {
  //     $("#phone_crtf_btn").attr("disabled", true);
  //   }
  // });

  $("#phone_crtf_btn").click(function() {
    $("#phone").attr("disabled", true);
    $("#phone_crtf_btn").hide();
    $("#phone_change_btn").show();
    $("#phone_crtf").show();
    $("#phone_crtf_lb").show();
  });

  $("#phone_crtf").keyup(function() {
    if($(this).val().length == 4) {
      $("#phone_change_btn").attr("disabled", false);
    }
  });

  $("input:text[numberOnly]").on("keyup", function() {
    $(this).val($(this).val().replace(/[^0-9]/g,""));
  });
</script>
