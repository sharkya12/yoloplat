<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<form name="flogin" action="<?php echo $login_action_url; ?>" onsubmit="return flogin_submit(this);" method="post">

<div class="cb_login_container">
  <table class="top_tit_side_3">
    <td>
      <span onclick="history.back();"><img src="../img/prev.png" /></span>
    </td>
    <td>
    </td>
    <td>

    </td>
  </table>
  <h3>YOLO 방문을 환영합니다.</h3>
  <!-- <small>아직 계정이 없으신 경우 생성을 도와드립니다.</small> -->
  <div class="input_label">
    <label id="label_id">휴대폰번호</label>
  </div>
  <div class="cb_login_input">
    <input type="text" name="mb_hp" id="input_id" placeholder="휴대폰번호"/>
  </div>
  <div class="input_label">
    <label id="label_pw">Password</label>
  </div>
  <div class="cb_login_input">
    <input type="password" name="mb_password" id="input_pw" placeholder="비밀번호"/>
    <div id="keyShow">
      <img src="../img/eyes.png" id="eyes_on"/>
      <img src="../img/eyes_off.png" id="eyes_off"/>
    </div>
  </div>
  <div class="cb_find_pw">
    <button type="button" class="pw_find_btn" onclick="location.href='<?php echo TB_MSHOP_URL;?>/cb_pw_find.php';">비밀번호 찾기</button>
  </div>

  <div class="btn_div">
    <button type="submit" class="login_btn" disabled>로그인</button>

  </div>
</div>
<div class="cb_regist_btn">
  계정이 없으신가요? <a href="cb_member_regist_agree.php">회원가입</a>
</div>
</from>

<script>

  $("#input_id").keyup(function(e) {
    if($(this).val().length >= 4&& $("#input_pw").val().length >= 4) {
      $(".login_btn").attr("disabled",false);
    } else {
      $(".login_btn").attr("disabled",true);
    }
  });
  $("#input_pw").keyup(function(e) {
    if($(this).val().length >= 4 && $("#input_id").val().length >= 4) {
      $(".login_btn").attr("disabled",false);
    } else {
      $(".login_btn").attr("disabled",true);
    }
  });

  function flogin_submit(f)
  {
  	if(!f.mb_hp.value) {
  		alert('핸드폰번호를 입력하세요.');
  		f.mb_ho.focus();
  		return false;
  	}
  	if(!f.mb_password.value) {
  		alert('비밀번호를 입력하세요.');
  		f.mb_password.focus();
  		return false;
  	}

      return true;
  }

  $("#keyShow").click(function() {
    if ($("#input_pw").attr("type") == "password") {
      $("#input_pw").prop("type", "text");
      $("#eyes_off").hide();
      $("#eyes_on").show();
    } else {
      $("#input_pw").prop("type", "password");
      $("#eyes_on").hide();
      $("#eyes_off").show();
    }
  });

</script>
