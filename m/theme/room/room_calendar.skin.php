<!-- Modal -->
<div class="full-screen-modal fx_none_modal cal_modal">
  <div class="modal fade" id="date_sel_modal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-body">
          <section class="check_day_section shadow_box">
            <section class="calendar_nav_section">
              <div>
                <h1>일정 선택</h1>
              </div>
              <button type="button" id="cal_close_btn" data-dismiss="modal"><i class="fas fa-times"></i></button>
            </section>

              <input type="hidden" id="start_date" />
              <input type="hidden" name="page" id="page" value="<?php echo $page;?>" />
              <input type="hidden" name="room_id" id="room_id" value="<?php echo $room_id;?>" />
              <input type="hidden" name="gs_id" id="gs_id" value="<?php echo $gs_id;?>" />
              <input type="hidden" name="type" id="type" value="<?php echo $type;?>" />

              <input type="hidden" name="check_in" id="check_in" value="<?php echo get_session('start_time');?>" />
              <input type="hidden" name="check_out" id="check_out" value="<?php echo get_session('end_time');?>" />

              <input type="hidden" name="catecode" id="catecode" value="<?php echo get_session('catecode'); ?>" />
              <input type="hidden" name="upcate" id="upcate" value="<?php echo get_session('upcate'); ?>" />
              <input type="hidden" name="total_num" id="total_num" value="<?php echo get_session('total_num'); ?>" />
              <input type="hidden" name="str_in_date" id="str_in_date" value="" />
              <input type="hidden" name="str_out_date" id="str_out_date" value="" />
              <input type="hidden" name="str_in_holiday" id="str_in_holiday" value="" />
              <input type="hidden" name="str_out_holiday" id="str_out_holiday" value="" />


            <div class="auto_side shadow_box">
              <div class="check_day_box" id="check_in_box">
                <small>체크인</small>
                <h2>8월 25일(수)</h2>
              </div>
              <div class="check_day_box" id="check_out_box">
                <small>체크아웃</small>
                <h2>8월 27일(금)</h2>
              </div>
            </div>
            <small class="ex">* 일정 선택은 최대 9박까지 선택가능합니다.</small>

            <div class="day auto_side">
              <span class="sun">일</span>
              <span>월</span>
              <span>화</span>
              <span>수</span>
              <span>목</span>
              <span>금</span>
              <span class="sat">토</span>
            </div>
          </section>
          <section class="calendar_section">
            <input type="hidden" name="start_date" id="start_date" />
            <input type="hidden" name="end_date" id="end_date" />
            <input type="hidden" name="s_month" id="s_month" />
            <input type="hidden" name="s_date" id="s_date" />
            <input type="hidden" name="e_month" id="e_month" />
            <input type="hidden" name="e_date" id="e_date" />

            <div class="calendar_box" id="calendar_box1">
              <h3></h3>
              <ul>

              </ul>
            </div>
            <div class="calendar_box" id="calendar_box2">
              <h3></h3>
              <ul>

              </ul>
            </div>
            <div class="calendar_box" id="calendar_box3">
              <h3></h3>
              <ul>

              </ul>
            </div>
            <div class="calendar_box" id="calendar_box4">
              <h3></h3>
              <ul>

              </ul>
            </div>
          </section>
          <section class="calendar_btn_section">
            <button type="button" id="cal_ok_btn" data-dismiss="modal" disabled>확인</button>
          </section>

        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal end -->


<script src="../js/new_calendar.js"></script>
<script>
  $(document).ready(function() {
    $(".cal_modal .modal-body").attr("style","padding: 0px !important;");
  });

  <?php if(!get_session('start_time') && !get_session('start_time')) { ?>
    let today_check = new Date();
    let today_year = today_check.getFullYear();
    let today_month = today_check.getMonth()+1;
    let today_date = today_check.getDate();
    let today_last_date = new Date(today_year, today_month, 0).getDate();

    setTimeout(function() {
      $("#"+today_year+"_"+today_month+"_"+today_date).trigger('click');
      if(today_date+1 > today_last_date) {
        $("#"+today_year+"_"+(today_month+1)+"_"+1).trigger('click');
      } else {
        $("#"+today_year+"_"+today_month+"_"+(today_date+1)).trigger('click');
      }
    }, 1200);

  <?php } else { ?>
    var check_in_year = "<?php echo get_session('start_time'); ?>".split("-")[0];
    var check_in_month = "<?php echo get_session('start_time'); ?>".split("-")[1];
    var check_in_date = "<?php echo get_session('start_time'); ?>".split("-")[2];
    var check_out_year = "<?php echo get_session('end_time'); ?>".split("-")[0];
    var check_out_month = "<?php echo get_session('end_time'); ?>".split("-")[1];
    var check_out_date = "<?php echo get_session('end_time'); ?>".split("-")[2];
    if(check_in_month.substr(0,1) == "0") {
      check_in_month = check_in_month.replaceAll("0","");
    }
    if(check_in_date.substr(0,1) == "0") {
      check_in_date = check_in_date.replaceAll("0","");
    }
    if(check_out_month.substr(0,1) == "0") {
      check_out_month = check_out_month.replaceAll("0","");
    }
    if(check_out_date.substr(0,1) == "0") {
      check_out_date = check_out_date.replaceAll("0","");
    }

    setTimeout(function() {
      $("#"+check_in_year+"_"+check_in_month+"_"+check_in_date).trigger('click');
      $("#"+check_out_year+"_"+check_out_month+"_"+check_out_date).trigger('click');
    }, 1200);
  <?php } ?>


  $("#cal_close_btn").click(function() {
    var check_in_year = "<?php echo get_session('start_time'); ?>".split("-")[0];
    var check_in_month = "<?php echo get_session('start_time'); ?>".split("-")[1];
    var check_in_date = "<?php echo get_session('start_time'); ?>".split("-")[2];
    var check_out_year = "<?php echo get_session('end_time'); ?>".split("-")[0];
    var check_out_month = "<?php echo get_session('end_time'); ?>".split("-")[1];
    var check_out_date = "<?php echo get_session('end_time'); ?>".split("-")[2];
    if(check_in_month.substr(0,1) == "0") {
      check_in_month = check_in_month.replaceAll("0","");
    }
    if(check_in_date.substr(0,1) == "0") {
      check_in_date = check_in_date.replaceAll("0","");
    }
    if(check_out_month.substr(0,1) == "0") {
      check_out_month = check_out_month.replaceAll("0","");
    }
    if(check_out_date.substr(0,1) == "0") {
      check_out_date = check_out_date.replaceAll("0","");
    }
    setTimeout(function() {
      $("#"+check_in_year+"_"+check_in_month+"_"+check_in_date).trigger('click');
      $("#"+check_out_year+"_"+check_out_month+"_"+check_out_date).trigger('click');
    }, 1200);
    console.log($("#check_in").val());
    console.log($("#check_out").val());
    $('html, body').css({'overflow': 'auto', 'height': '100%'});
    //scroll hidden 해제
    $('#element').off('scroll touchmove mousewheel');
    // 터치무브 및 마우스휠 스크롤 가능
  });

  $("#cal_ok_btn").click(function() {
    $('html, body').css({'overflow': 'auto', 'height': '100%'});
    //scroll hidden 해제
    $('#element').off('scroll touchmove mousewheel');
    // 터치무브 및 마우스휠 스크롤 가능
  });


  $(".calendar_sel_btn").click(function() {
    $('html, body').css({'overflow': 'hidden', 'height': '100%'});
    // 모달팝업 중 html,body의 scroll을 hidden시킴
    $('#element').on('scroll touchmove mousewheel', function(event) {
      // 터치무브와 마우스휠 스크롤 방지
      event.preventDefault();
      event.stopPropagation();
      return false;
    });
  });



</script>
