<div class="cb_mb_regist_phone_container">
  <table class="top_tit_side_3">
    <td>
      <span onclick="history.back();"><img src="../img/prev.png" /></span>
    </td>
    <td>
    </td>
    <td>

    </td>
  </table>
  <h2>휴대폰 인증</h2>
  <small>원활한 서비스 제공을 위해, 휴대폰 번호를 입력해 주세요.</small>

  <div class="input_label">
    <label id="label_ph">휴대폰 번호</label>
  </div>
  <div class="cb_ph_input">
    <input type="text" name="ph" maxlength="11" id="input_ph" placeholder="01012345678" numberOnly />
    <input type="hidden" name="check_ph" id="check_ph" />
  </div>

  <div class="input_label">
    <label id="label_birth">생년월일 (6자리)</label>
  </div>
  <div class="cb_ph_input">
    <input type="text" name="birth" id="input_birth" placeholder="생년월일 (6자리)" maxlength="6" numberOnly/>
  </div>

  <div class="ctnum_div">
    <div class="input_label">
      <label id="label_ctnum">인증번호</label>
    </div>
    <div class="cb_ct_input">
      <div class="cb_ct_div auto_side">
        <input type="text" name="ctnum" maxlength="4" id="input_ctnum" placeholder="인증번호" numberOnly />
        <span id="time1">02:00</span>
      </div>
    </div>
    <!--
    <div class="ctnum_re_div" id="re_send_sms" style="display:none;">
      <button type="button" onclick="get_sms_verify()" >인증번호 재전송</button>
    </div>
  -->
  </div>

</div>

<div class="btn_bot_div">
  <button type="button" id="certifi_btn" onclick="get_sms_verify()" disabled>인증번호 전송 </button>
  <button type="button" id="regist_next_btn" onclick="get_sms_verify_code()" disabled>다음</button>


</div>

<script>

  // $(document).ready(function() {
  //   $("#label_ph").hide();
  //   $("#label_ctnum").hide();
  // });
  //
  // $("#input_ph").focus(function() {
  //   $("#label_ph").fadeIn(200);
  // });
  //
  $("#input_ctnum").focus(function() {
    $("#input_ctnum").attr("style","border: 2px solid #CCC;");
  });
  //
  // $("#input_ph").blur(function() {
  //   if($("#input_ph").val() == "") {
  //     $("#label_ph").fadeOut(200);
  //   }
  // });
  //
  $("#time1").click(function() {
    $("#input_ctnum").focus();
  });

  $("#input_ctnum").blur(function() {
    if($("#input_ctnum").val() == "") {
      $("#input_ctnum").attr("style","border: 1px solid #f4f4f4;");
    }
  });

  $("#input_ph").keyup(function(e) {
    if($(this).val().length >= 10 && $("#input_birth").val().length == 6) {
      $("#certifi_btn").attr("disabled",false);
    } else {
      $("#certifi_btn").attr("disabled",true);
    }
  });

  $("#input_birth").keyup(function(e) {
    if($(this).val().length == 6 && $("#input_ph").val().length >= 10) {
      $("#certifi_btn").attr("disabled",false);
    } else {
      $("#certifi_btn").attr("disabled",true);
    }
  });

  $("#input_ctnum").keyup(function(e) {
    if($(this).val().length >= 4) {
      $("#regist_next_btn").attr("disabled",false);
    } else {
      $("#regist_next_btn").attr("disabled",true);
    }
  });



  $("input:text[numberOnly]").on("keyup", function() {
    $(this).val($(this).val().replace(/[^0-9]/g,""));
  });


  var count = 120;
  var countdown = '';


  function start_timer(){
    countdown = setInterval(timer, 1000);
  }


  function timer(){
      $("#time1").html(toHourMinSec(count));
      if (count == 0) {
        clearInterval(countdown);
        $(".sms_verify_area").hide();
        alert("인증시간이 초과되었습니다. 다시 인증해주세요.");
        $("#time1").hide();
        $("#re_send_sms").show();
        count = 120;
        $("#time1").html(toHourMinSec(count+1));
        return;
      }
      count--;
  }


  function get_sms_verify(){
    $("#re_send_sms").hide();
  	var action_url = "../../api/cool_sms/send_sms.php";
  	var user_hp = $("#input_ph").val().replace("-","");
    var input_birth = $("#input_birth").val();

  	user_hp = user_hp.replace(/-/g, '');
  	$("#input_ph").val(user_hp);

  	var regExp =/(01[016789])([1-9]{1}[0-9]{2,3})([0-9]{4})$/;
    exist_mbhp(user_hp,input_birth);
    var ph_cnt = $("#check_ph").val();
    if ( ph_cnt == 0) {
      alert("등록되지 않은 휴대폰 또는 잘못된 생년월일 입니다.");
      return;
    }
  	if(regExp.test(user_hp)){
        $.post(action_url,{param1:'make_key',param2:user_hp},function(data) {
            if(data == 'over') {
              alert("인증번호 발송 횟수가 초과 되어 발송되지 않습니다.");
            } else if(data == 'access denied') {
              alert("오류가 발생했습니다.");
            } else {
            alert("인증번호가 발송되었습니다.");

            start_timer();
            $("#time1").show();

            $(".ctnum_div").show();
            $("#certifi_btn").hide();
            $("#regist_next_btn").show();
          }
        });
        $(".sms_verify_area").show();



  	} else {
  		alert("휴대폰 번호를 확인해주세요.");
  	}

}

function get_sms_verify_code(){
	var action_url = "../../api/cool_sms/send_sms.php";
	var verify_key =  $("#input_ctnum").val();
  var user_hp = $("#input_ph").val();
	$.post(
		action_url,
		{param1:'get_key',param2:verify_key},
		function(data) {
			if(data == 'ok') {
        /*
				$("#sms_auth").val('1');
				$("#vipTel").prop("readonly",true);
				$("#vipTel").css({"font-weight":"bold","color":"#666"});
				$(".sms_verify_area").hide();
				$("#verify_btn").hide();
				$("#verify_btn2").show();
        */
				alert("인증되었습니다.");
        location.href='pw_find2.php?user_hp='+user_hp;
				clearInterval(countdown);
				$("#time1").hide();
			} else {
				alert("인증번호가 다릅니다. 다시 확인해주세요.");
				$("#sms_auth").val('');
			}
		}
	);
}

  function exist_mbhp(mb_hp,input_birth){
    var action_url = "../shop/ajax.find_pw_mbhp.php";
    var count=0;

    $.ajax({
         url: action_url,
         type: 'POST',
         dataType: 'json',
         //contentType: 'application/json',
         data : {cell_phone:mb_hp,input_birth:input_birth},
         async:false,
         /*
         data : JSON.stringify({
                'goods_type': this.value
              }),
          */
         //processData: false,
         success: function (data) {

          var person = JSON.stringify(data);
          var oPerson = JSON.parse(person);
          count = oPerson[0].count;
          $("#check_ph").val(count);

         },
         error: function(){
           console.log("Cannot get data");
         }

       });


    /*
    $.post(
  		action_url,
  		{cell_phone:mb_hp},
  		function(data) {

        var jsonData = JSON.parse(data);

        count = parseInt(jsonData[0].count);


  		}
  	);
    */
    return(count);
  }




  function toHourMinSec(t) {
  	var hour;
  	var min;
  	var sec;
  	hour = Math.floor(t / 3600);
  	min = Math.floor( (t-(hour*3600)) / 60 );
  	sec = t - (hour*3600) - (min*60);
  	//if(hour < 10) hour = "0" + hour;
  	if(min < 10) min = "0" + min;
  	if(sec < 10) sec = "0" + sec;
  	return(min + ":" + sec);
  }
</script>
