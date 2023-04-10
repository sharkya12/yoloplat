<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

?>

<form name="flogin" action="<?php echo $login_action_url; ?>" onsubmit="return flogin_submit(this);" method="post">

<div class="cb_login_container">
  <section class="login_nav_section">
    <button type="button" onclick="history.back();"><i class="fas fa-chevron-left"></i></button>
    <h1>로그인</h1>
    <button type="button" onclick="location.href='manager_login.php';">매니저 로그인</button>
  </section>

  <h3>욜로플랫 방문을 환영합니다.</h3>
  <!-- <small>아직 계정이 없으신 경우 생성을 도와드립니다.</small> -->
  <div class="input_label">
    <label id="label_id">휴대폰번호</label>
  </div>
  <div class="cb_login_input">
    <input type="text" name="mb_hp" id="input_id" placeholder="휴대폰번호" numberOnly/>
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
  <div class="auto_side">
    <div class="cb_auto_login">
      <label for="auto_login_ck">자동 로그인</label><input type="checkbox" id="auto_login_ck"/>
    </div>
    <div class="cb_find_pw">
      <button type="button" class="pw_find_btn" onclick="location.href='<?php echo TB_MAPP_URL; ?>/pw_find.php';">비밀번호 찾기</button>
    </div>
  </div>

  <div class="btn_div">
    <button type="submit" class="login_btn" id ="login_btn" disabled>로그인</button>

  </div>
  <div class="cb_regist_btn">
    계정이 없으신가요? <a href="member_regist_agree.php">회원가입</a>
  </div>
</div>

</from>

<script>

  var _outerHeight = window.outerHeight;

  $(document).ready(function() {
      $(".cb_regist_btn").attr("style", "margin-top: "+_outerHeight/5+"px;")
  });

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

    if ($("#auto_login_ck").is(":checked") == true) {

       // 자동로그인
      if(window.MyApp && window.MyApp.callMessage){

        // CARD EBANK EPAY
        var phone  = $("#input_id").val();
        var password = $("#input_pw").val();

        var obj = { auto_yn:'Y', phone:phone, password:password };
        var str = JSON.stringify(obj);
        window.MyApp.autoLogin(str);
      }
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

  $("input:text[numberOnly]").on("keyup", function() {
    $(this).val($(this).val().replace(/[^0-9]/g,""));
  });

</script>
