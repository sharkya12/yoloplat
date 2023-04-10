<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<section class="card_set_section">
  <table class="top_tit_side_3">
    <td>
      <span onclick="history.back();"><img src="../img/prev.png" /></span>
    </td>
    <td class="fw_regular">
      간편결제 카드관리
    </td>
    <td>

    </td>
  </table>
  <div class="card_list">
    <ul>
      <!-- 등록된 카드가 있을때 -->
      <?php if ($total_count == 0) { ?>
        <li class="card_none shadow_box">
          <div class="card_none_tit">
            등록된 카드가 없습니다.
          </div>
        </li>
        <!-- 등록된 카드가 없을때 -->
      <?php } else { ?>
      <?php for($i = 0; $row=sql_fetch_array($result); $i++) { ?>
      <li class="shadow_box" id="card_option<?php echo $i; ?>">
        <div class="card_option shadow_box">
          <button type="button" id="btn_mdfy" class="card_mdfy" data-toggle="modal" data-target="#Modal1">수정</button>
          <!-- 카드삭제 submit form -->
          <form name="card_del" id="card_del" action="./cb_card_update.php" onsubmit="return card_del_submit(this);" method="post" autocomplete="off">
            <input type="hidden" name="w" value="d" />
            <input type="hidden" name="mb_id" id="mb_id" value="<?php echo $member['id']; ?>" />
            <input type="hidden" name="card_4" value="<?php echo $row['card_no']; ?>" />
          <button type="submit" id="btn_del" class="card_del">삭제</button>
          </form>
          <!-- 카드삭제 submit form 끝 -->
        </div>
        <div class="auto_side">
          <span>BC 카드</span><button type="button" class="card_set_btn"><img src="../img/card_btn.png" /></button>
        </div>
        <div class="card_num">
          **** **** **** <?php echo $row['card_no'];?>
        </div>
      </li>
      <?php
        }
      } ?>
    </ul>
    <div class="card_add_btn">
      <button type="button" onclick="location.href='cb_card_form.php';">+ 카드추가</button>
    </div>
  </div>



  <!-- 카드수정시 모달화면 불러오기 -->
  <div class="full-screen-modal fx_none_modal">
    <div class="modal fade modal" id="Modal1" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-body">
            <section class="card_form_section">
              <table class="top_tit_side_3">
                <td>
                  <span id="modalclose" data-dismiss="modal"><img src="../img/prev.png" /></span>
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
                <input type="text" maxlength="4" placeholder="0000" id="card1" /><input type="text" maxlength="4" placeholder="0000" id="card2" /><input type="text" maxlength="4" placeholder="0000" id="card3" /><input type="text" maxlength="4" placeholder="0000" id="card4" />
              </div>
              <div class="card_tit">유효기간</div>
              <div class="card_date">
                <input type="text" maxlength="2" placeholder="YY" id="date_yy" /><input type="text" maxlength="2"  placeholder="MM" id="date_mm" />
              </div>
              <div class="card_tit">본인확인 <small>(개인카드 : 생년월일 6자리)</small></div>
              <div class="card_birth">
                <input type="text" maxlength="6" placeholder="000000" id="card_birth" />
              </div>
              <div class="card_tit">카드비밀번호 <small>(앞 2자리)</small></div>
              <div class="card_pw">
                <input type="password" maxlength="2" placeholder="00" id="card_pw" />
              </div>
              <div class="card_tit">계정비밀번호 <small>(로그인 시 비밀번호)</small></div>
              <div class="id_pw">
                <input type="password" id="input_pw" placeholder="아이디 로그인 시 비밀번호"/>
                <div id="keyShow">
                  <img src="../img/eyes.png" id="eyes_on"/>
                  <img src="../img/eyes_off.png" id="eyes_off"/>
                </div>
              </div>

            </section>

            <div class="card_form_add_btn">
              <button type="button" onclick="location.href='cb_card.php';" id="card_reg_btn" disabled>등록</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<!-- 카드수정시 모달화면 불러오기 끝 -->

</section>

<script>
  $(".card_set_btn").click(function() {
    var this_li = $(this).parent().parent().attr('id');
    $("#"+this_li+" .card_option").toggle();
  });




  $(".card_del").click(function(){
    if(confirm("등록된 카드를 삭제하시겠습니까?") == true){





      alert("삭제되었습니다");

      // ajax 작성

    } else {
      return;
    }
  });


  $("div input").keyup(function(e) {
    if($("#card1").val().length == 4 && $("#card2").val().length == 4 && $("#card3").val().length == 4 && $("#card4").val().length == 4
        && $("#date_yy").val().length == 2 && $("#date_mm").val().length == 2 && $("#card_birth").val().length == 6 && $("#card_pw").val().length == 2
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

  $("#btn_mdfy").click(function (){
    var w = 'u';


  });

  $("#btn_del").click(function (){
    var w = 'd';


  });

  function card_del_submit(f)
  {

    document.getElementById("btn_submit").disabled = "disabled";

      return true;
  }
</script>
