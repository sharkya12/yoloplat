<form name="card_form" id="card_form" action="./cb_card_update.php" onsubmit="return card_form_submit(this);" method="post" autocomplete="off">
  <input type="hidden" name="w" value="" />
  <input type="hidden" name="mb_id" id="mb_id" value="<?php echo $member['id']; ?>" />
  <section class="card_form_section">
    <table class="top_tit_side_3">
      <td>
        <span onclick="history.back();"><img src="../img/prev.png" /></span>
      </td>
      <td>
      </td>
      <td>
      </td>
    </table>
    <h2>간편카드 관리</h2>
    <small class="small_ex">본인명의 신용/체크카드를 등록해 주세요.</small>



    <div class="card_tit">카드번호</div>
    <div class="card_number">
      <input type="text" maxlength="4" placeholder="0000" id="card_1" />
      <input type="text" maxlength="4" placeholder="0000" id="card_2" />
      <input type="text" maxlength="4" placeholder="0000" id="card_3" />
      <input type="text" maxlength="4" placeholder="0000" id="card_4" name="card_4" />
    </div>
    <div class="card_tit">유효기간</div>
    <div class="card_date">
      <input type="text" maxlength="2" placeholder="YY" id="date_yy" /><input type="text" maxlength="2"  placeholder="MM" id="date_mm" />
    </div>
    <!-- 이 페이지에서 생년월일 변경불가 -->
    <div class="card_tit">본인확인 <small>(개인카드 : 생년월일 6자리)</small></div>
    <div class="card_birth">
      <input type="text" maxlength="6" placeholder="등록된 생년월일 정보가 없습니다." id="card_birth" value="<?php echo $member_row['mb_birth']; ?>" disabled/>
    </div>
    <div class="card_tit">카드비밀번호 <small>(앞 2자리)</small></div>
    <div class="card_pw">
      <input type="password" maxlength="2" placeholder="00" id="card_pw" />
    </div>
    <div class="card_tit">계정비밀번호 <small>(로그인 시 비밀번호)</small></div>
    <div class="id_pw">
      <input type="password" name="mb_password" id="input_pw" placeholder="아이디 로그인 시 비밀번호"/>
      <div id="keyShow">
        <img src="../img/eyes.png" id="eyes_on"/>
        <img src="../img/eyes_off.png" id="eyes_off"/>
      </div>
    </div>

  </section>

  <div class="card_form_add_btn">
    <button type="submit" id="card_reg_btn" disabled>등록</button>
  </div>
</form>


<script>
  $("div input").keyup(function(e) {
    if($("#card_1").val().length == 4 && $("#card_2").val().length == 4 && $("#card_3").val().length == 4 && $("#card_4").val().length == 4
        && $("#date_yy").val().length == 2 && $("#date_mm").val().length == 2 && $("#card_pw").val().length == 2
        && $("#input_pw").val().length >= 4) {
      $("#card_reg_btn").attr("disabled",false);
    } else {
      $("#card_reg_btn").attr("disabled",true);
    }
  });

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


  function card_form_submit(f)
  {

    if ($("#card_birth").val() == "" ) {
      alert("생년월일 정보가 필요합니다. 마이욜로 페이지 -> 설정 -> 내정보 에서 생년월일을 등록해주시기 바랍니다.");
      return false;
    }
    if (!($("#card_birth").val().length == 6) ) {
  		alert("잘못된 생년월일입니다. 6자리로 등록하여 주십시오..");
      return false;
    }

    var mb_id = $('#mb_id').val();
    var input_pw = $('#input_pw').val();

    $.ajax({
      url: "./ajax.cb_card_form_passwd.php",
      type: "POST",
      // dataType:"json",
      async: false,
      data: { input_pw:input_pw, mb_id:mb_id },
      success:function(data) {
        if (data == 1){
          alert(data);
          document.getElementById("btn_submit").disabled = "disabled";

            return true;
        } else if (data == 0 ){
          alert("로그인 비밀번호가 틀렸습니다.");
            return false;
        }


      },
      error:function(error) {
         alert("오류");
      }
    });

  	// if(f.mb_password.value != "<?php echo $member_row['passwd']; ?>") {
  	// 	alert("비밀번호가 틀렸습니다.");
  	// 	f.mb_password.focus();
  	// 	return false;
  	// }

  	// if(f.mb_password.value.length > 0) {
  	// 	if(f.mb_password_re.value.length < 8) {
  	// 		alert("비밀번호를 8글자 이상 입력하십시오.");
  	// 		f.mb_password_re.focus();
  	// 		return false;
  	// 	}
  	// }

  	// document.getElementById("btn_submit").disabled = "disabled";
    //
    //   return true;

  }



</script>
