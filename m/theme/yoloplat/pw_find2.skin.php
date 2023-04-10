
<?php
if(!defined('_TUBEWEB_')) exit;
//암호화
//$en_password = get_encrypt_string($mb_password)

?>

<script src="<?php echo TB_JS_URL; ?>/jquery.register_form.js"></script>
<form name="fregisterform" id="fregisterform" action="./pw_find_update.php" onsubmit="return fregisterform_submit(this);" method="post" autocomplete="off">
<input type="hidden" name="user_hp" id="user_hp" value="<?php echo $user_hp; ?>"/>
    <div class="cb_mb_regist_container">
      <table class="top_tit_side_3">
        <td>
          <span onclick="history.back();"><img src="../img/prev.png" /></span>
        </td>
        <td>
        </td>
        <td>
        </td>
      </table>
      <h2><?php echo $mb_id ?>비밀번호 재설정</h2>
      <div class="input_label">
        <label id="label_pw">새 비밀번호 (최소 8자이상)</label>
      </div>
      <div class="cb_login_input">
        <input type="password" name="mb_password" id="input_pw" placeholder="비밀번호 (최소 8자이상)"/>
        <small id="pw_ex"></small>
      </div>
      <div class="input_label2">
        <label id="label_pw2">새 비밀번호 확인</label>
      </div>
      <div class="cb_login_input">
        <input type="password" name="mb_password_re" id="input_pw2" placeholder="비밀번호 확인"/>
        <small id="pw2_ex"></small>
      </div>
    </div>

    <div class="btn_bot_div">
      <button type="submit" id="next_btn" disabled>다음</button>
      <!-- location.href='cb_login.php disabled-->
    </div>

</form>

<script>
  $(document).ready(function() {
    // $("#label_id").hide();
    // $("#label_pw").hide();
    // $("#label_pw2").hide();
    // $("#label_birth").hide();
  });


  $("#input_pw").focus(function() {
    $("#label_pw").fadeIn(200);
    if($(this).val().length >= 8) {
      $("#pw_ex").text("∟ 사용가능: 안전한 비밀번호입니다.");
      $("#pw_ex").attr("style","color:blue;");
    } else {
      $("#pw_ex").text("∟ 사용불가: 최소 8자 이상 입력해주세요.");
    }
    $("#pw_ex").show();
    $("#input_pw").keyup(function(e) {
      if($(this).val().length >= 8) {
        $("#pw_ex").text("∟ 사용가능: 안전한 비밀번호입니다.");
        $("#pw_ex").attr("style","color:blue;");
      } else {
        $("#pw_ex").text("∟ 사용불가: 최소 8자 이상 입력해주세요.");
        $("#pw_ex").attr("style","color:#ccc;");
      }
      if($("#input_pw2").val() != "") {
        if($(this).val() != $("#input_pw2").val()) {
          $("#pw2_ex").text("∟ 비밀번호가 일치하지 않습니다.");
          $("#pw2_ex").attr("style","color:red;");
        } else {
          $("#pw2_ex").text("");
        }
      }
    });
  });
  $("#input_pw2").focus(function() {
    // $("#label_pw2").fadeIn(200);
    $("#input_pw2").keyup(function(e) {
      if($(this).val() != $("#input_pw").val()) {
        $("#pw2_ex").text("∟ 비밀번호가 일치하지 않습니다.");
        $("#pw2_ex").attr("style","color:red;");
      } else {
        $("#pw2_ex").text("");
      }
    });
  });
  // $("#input_birth").focus(function() {
  //   $("#label_birth").fadeIn(200);
  // });
  //
  // $("#input_id").blur(function() {
  //   if($("#input_id").val() == "") {
  //     $("#label_id").fadeOut(200);
  //   }
  // });
  $("#input_pw").blur(function() {
    // if($("#input_pw").val() == "") {
    //   $("#label_pw").fadeOut(200);
    // }
    if($(this).val().length >= 8) {
      $("#pw_ex").text("");
    } else {
      $("#pw_ex").attr("style","color:red;");
    }
  });
  // $("#input_pw2").blur(function() {
  //   if($("#input_pw2").val() == "") {
  //     $("#label_pw2").fadeOut(200);
  //   }
  // });
  // $("#input_birth").blur(function() {
  //   if($("#input_birth").val() == "") {
  //     $("#label_birth").fadeOut(200);
  //   }
  // });

  $("input:text[numberOnly]").on("keyup", function() {
    $(this).val($(this).val().replace(/[^0-9]/g,""));
  });

  $("#input_pw").keyup(function(e) {
    if($("#input_pw").val().length >= 8) {
      if($("#input_pw").val() == $("#input_pw2").val()) {
          $("#next_btn").attr("disabled",false);
      } else {
        $("#next_btn").attr("disabled",true);
      }
    } else {
      $("#next_btn").attr("disabled",true);
    }
  });
  $("#input_pw2").keyup(function(e) {
    if($("#input_pw").val().length >= 8) {
      if($("#input_pw").val() == $("#input_pw2").val()) {
          $("#next_btn").attr("disabled",false);
      } else {
        $("#next_btn").attr("disabled",true);
      }
    } else {
      $("#next_btn").attr("disabled",true);
    }
  });



  // $("#input_id").keyup(function(e) {
  //   if($(this).val().length >= 6 && $("#input_pw").val().length >= 6) {
  //     $("#next_btn").attr("disabled",false);
  //   } else {
  //     $("#next_btn").attr("disabled",true);
  //   }
  // });


function fregisterform_submit(f)
{
	// 회원아이디 검사

		// var msg = reg_mb_id_check();
		// if(msg) {
		// 	alert(msg);
		// 	f.mb_id.select();
		// 	return false;
		// }



		if(f.mb_password.value.length < 8) {
			alert("비밀번호를 8글자 이상 입력하십시오.");
			f.mb_password.focus();
			return false;
		}



	if(f.mb_password.value != f.mb_password_re.value) {
		alert("비밀번호가 같지 않습니다.");
		f.mb_password_re.focus();
		return false;
	}

	if(f.mb_password.value.length > 0) {
		if(f.mb_password_re.value.length < 8) {
			alert("비밀번호를 8글자 이상 입력하십시오.");
			f.mb_password_re.focus();
			return false;
		}
	}

	document.getElementById("btn_submit").disabled = "disabled";

    return true;
}
</script>
