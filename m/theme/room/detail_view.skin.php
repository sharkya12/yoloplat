<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>
<div class="detail_view_container">

  <section class="img_slide_section">
    <div class="swiper" id="view-swiper">
      <div class="swiper-wrapper">

        <?php if(!$room_row['simg1']) { ?>
          <div class="swiper-slide" style="background-image: url('../img/image_none.png')"></div>
        <?php } ?>
        <?php if($room_row['simg1'] != ""){ ?>
            <div class="swiper-slide" style="background-image: url('../../data/goods/<?php echo $room_row['simg1'];?>')"></div>
        <? } ?>
        <?php if($room_row['simg2'] != ""){ ?>
            <div class="swiper-slide" style="background-image: url('../../data/goods/<?php echo $room_row['simg2'];?>')"></div>
        <? }?>
        <?php if($room_row['simg3'] != ""){ ?>
            <div class="swiper-slide" style="background-image: url('../../data/goods/<?php echo $room_row['simg3'];?>')"></div>
        <? }?>
        <?php if($room_row['simg4'] != ""){ ?>
            <div class="swiper-slide" style="background-image: url('../../data/goods/<?php echo $room_row['simg4'];?>')"></div>
        <? }?>
        <?php if($room_row['simg5'] != ""){ ?>
            <div class="swiper-slide" style="background-image: url('../../data/goods/<?php echo $room_row['simg5'];?>')"></div>
        <? }?>
        <?php if($room_row['simg6'] != ""){ ?>
            <div class="swiper-slide" style="background-image: url('../../data/goods/<?php echo $room_row['simg6'];?>')"></div>
        <? }?>
      </div>
      <div class="swiper-pagination detail-pagination"></div>
    </div>
    <div class="view_nav auto_side">
      <button type="button" id="back_button" onclick="history.back();"><i class="fas fa-chevron-left"></i></button>
      <!-- <button type="button" id="back_button" onclick="location.href='view.php?type=<?php echo $type; ?>&room_id=<?php echo $room['mb_id']; ?>';"><i class="fas fa-chevron-left"></i></button> -->
    </div>
  </section>

  <section class="detail_info_section">
    <div class="detail_info_box shadow_box">
      <h1><?php echo $room_row['gname'];?></h1>
      <small>기준 <?php echo $room_row['standard_pplNum'];?>명 / 최대 <?php echo $room_row['max_pplNum'];?>명</small>
      <div class="room_info auto_side">

        <div class="check_div">
          <small class="check_time">체크인 <span><?php echo $check_in_str;?> 부터</span></small>
          <small class="check_time">체크아웃 <span><?php echo $check_out_str;?> 부터</span></small>
        </div>
        <?php if ($remain_room == 0) { ?>
          <div class="rif_close">
            <span class="rv_close">예약마감</span>
          </div>

        <?php } else { ?>
          <div class="second_div">
            <!-- 연박이용시일때 -->
            <?php if($interval >= 2) { ?>

              <!-- 할인가격이 없을때 -->
              <?php if($discount == '0') { ?>
                <span class="discount"></span><del></del>
                <h2><span class="night1">1박 기준가</span><span><?php echo $discount_price;?></span>원</h2>
                <div>남은 객실 <?php echo $remain_room; ?>개</div>
              <!-- 할인가격이 있을때 -->
              <?php } else { ?>
                <span class="discount"><? echo $discount;?>%</span><del><?php echo number_format($room_row['normal_price']);?>원</del>
                <h2><span class="night1">1박 기준가</span><span><?php echo $discount_price;?></span>원</h2>
                <div>남은 객실 <?php echo $remain_room; ?>개</div>
            <?php } ?>

            <!-- 1박이용시일때 -->
            <?php } else { ?>

              <!-- 할인가격이 없을때 -->
              <?php if($discount == '0') { ?>
                <span class="discount"></span><del></del>
                <h2><span><?php echo $discount_price;?></span>원</h2>
                <div>남은 객실 <?php echo $remain_room; ?>개</div>

              <!-- 할인가격이 있을때 -->
              <?php } else { ?>
                <span class="discount"><? echo $discount;?>%</span><del><?php echo number_format($room_row['normal_price']);?>원</del>
                <h2><span><?php echo $discount_price;?></span>원</h2>
                <div>남은 객실 <?php echo $remain_room; ?>개</div>
              <?php } ?>

            <?php } ?>
          </div>
        <?php } ?>
      </div>
    </div>
  </section>

  <section class="check_date_section">
    <div class="auto_side">
      <div class="shadow_box" data-toggle="modal" data-target="#date_sel_modal">
        <h4>체크인</h4>
        <h2><?php echo $str_in_date;?><i class="fas fa-caret-down"></i></h2>
        <h3><?php echo $check_in_str;?></h3>
      </div>
      <div class="shadow_box" data-toggle="modal" data-target="#date_sel_modal">
        <h4>체크아웃</h4>
        <h2><?php echo $str_out_date;?><i class="fas fa-caret-down"></i></h2>
        <h3><?php echo $check_out_str;?></h3>
      </div>
    </div>
    <h1 class="night shadow_box"><?php echo $interval; ?>박</h1>
  </section>

  <section class="goods_info_section">
    <div class="info_box">
      <div class="auto_side">
        <h2>이용안내</h2>
        <button type="button" onclick="location.href='tel:010-0000-0000';"><i class="fas fa-phone-alt"></i><span>전화하기</span></button>
      </div>
      <ul>
        <?php if(!$room['room_info']) { ?>
          <li>
            <textarea id="textarea2">준비중입니다.</textarea>
          </li>
        <?php } else { ?>
          <textarea id="textarea2" disabled><?php echo $room['room_info']; ?></textarea>
        <?php } ?>
      </ul>
    </div>

    <div class="info_box">
      <h2>객실 내부시설</h2>
      <ul>
        <?php //객실 내부시설 불러오기
          //객실 내부시설 그룹별 숫자 불러오기
          $sql = " select fac_gr_cd from hi_room_goods_facilities
                      where mb_id = '{$room_id}'
                      and gcode = {$gcode}
                      group by fac_gr_cd
                      order by fac_gr_cd";
          $fac_group_cd = sql_query($sql);

          for ($i=0; $row_gr = sql_fetch_array($fac_group_cd); $i++) {

            $fac_gr_cd = $row_gr['fac_gr_cd'];
            //객실 내부시설 그룹별 이름 불러오기
            $sql = " select fac_group_nm, fac_group_cd from hi_room_facilities_group
                        where fac_type = 'IN'
                        and fac_group_cd = {$fac_gr_cd}
                        order by fac_group_cd";
            $fac_gr_nm = sql_query($sql);
            //객실 내부시설 시설명 불러오기
            $sql = " select * from hi_room_goods_facilities
                        where mb_id = '{$room_id}'
                        and gcode = {$gcode}
                        and fac_gr_cd = {$fac_gr_cd}
                        order by fac_gr_cd, fac_ma_seq";
            $room_fac = sql_query($sql);

        ?>

            <?php if(sql_num_rows($room_fac) == 0) { ?> <!-- 내부시설이 없을때 -->
            <li>
              준비중입니다.
            </li>
            <?php } else { ?>
              <?php for ($i=0; $fac_nm = sql_fetch_array($fac_gr_nm) ; $i++) { ?>
                <li>
                  <span><?php echo $fac_nm['fac_group_nm'];?> - </span>
                  <?php for ($i=0; $row = sql_fetch_array($room_fac); $i++) { ?>
                    <span class="room_fac"><?php echo $row['fac_name'];?></span>
                  <?php } ?>
                </li>
              <?php } ?>
            <?php } ?>
        <?php } ?>
      </ul>
    </div>

    <div class="info_box">
      <h2>취소 및 환불규정</h2>
      <ul>
        <?php if(!$room['cnc_rfnd_policy']) { ?>
          <li>
            <textarea id="textarea1">준비중입니다.</textarea>
          </li>
        <?php } else { ?>
          <textarea id="textarea1" disabled><?php echo $room['cnc_rfnd_policy']; ?></textarea>
        <?php } ?>
        <!-- <li>
          각 숙소별 추가적용사항 있을 시 텍스트 박스에서 불러오기
        </li> -->
      </ul>
    </div>
  </section>

  <section class="bottom_ex">
    <p>
      (주)소복이세상은 통신판매중개자로서 통신판매의 당사자가 아니며, 상품의 예약,
      이용 및 환불 등과 관련한 의무와 책임은 각 판매자에게 있습니다.
    </p>
  </section>

  <?php
    //다음페이지에 get정보 넘기기
    $room_info_str = "gcode=".$gcode."&gs_id=".$gs_id."&room_id=".$room_id."&type=".$type;
    $time_info_str = "&in_date=".$in_date."&out_date=".$out_date."&interval=".$interval."&chk_in_time=".$chk_in_time."&chk_out_time=".$chk_out_time;
  ?>
  <section class="fix_btn_section">
    <button type="button" onclick="location.href='<?php echo TB_MAPP_URL; ?>/reservation.php?<?php echo $room_info_str; ?>';">예약하기</button>
    <!-- <button type="button" onclick="location.href='<?php echo TB_MAPP_URL; ?>/reservation.php?room_id=<?php echo $room_id;?>&in_date=<?php echo $in_date;?>&out_date=<?php echo $out_date?>&gcode=<?php echo $room_row['gcode']?>';">예약하기</button> -->
  </section>
</div>

<script>

  //뒤로가기 새로고침 활성화
  window.onpageshow = function (event) {
    if ( event.persisted || (window.performance && window.performance.navigation.type == 2))  {

        window.location.reload(true);
    } else {

    }
  }

  $(document).ready(function() {
    var view_img_w = (document.querySelector('.img_slide_section').offsetWidth);

    $(".img_slide_section #view-swiper .swiper-slide").css("height", view_img_w+"px");
    // $(".view_tit_section").css("top", (app_width-60)+"px");
    if(app_width < 360 ){
      $(".goods_list_section ul li .info_box .lif h3").css("font-size","16px");
      $(".goods_list_section ul li .info_box .lif small").css("font-size","11px");
      $(".goods_list_section ul li .info_box .lif h4").css("font-size","10px");
      $(".goods_list_section ul li .info_box .rif span").css("font-size","10px");
      $(".goods_list_section ul li .info_box .rif del").css("font-size","10px");
      $(".goods_list_section ul li .info_box .rif h3 span").css("font-size","16px");
      $(".goods_list_section ul li .info_box .rif h3").css("font-size","14px");
      $(".goods_list_section ul li .info_box .rif").css("margin-top","-16px");
    }
  });

  $(window).resize(function (){
    var view_img_w = (document.querySelector('.img_slide_section').offsetWidth);

    $(".img_slide_section #view-swiper .swiper-slide").css("height", view_img_w+"px");
  });

  var swiper = new Swiper("#view-swiper", {
    pagination: {
      el: ".swiper-pagination",
      type: "fraction",
    },
  });

  function adjustHeight() {
    var textEle = $('#textarea1');
    textEle[0].style.height = 'auto';
    var textEleHeight = textEle.prop('scrollHeight');
    textEle.css('height', textEleHeight).css('border', '0').css('resize', 'none');
  };

  function adjustHeight2() {
    var textEle = $('#textarea2');
    textEle[0].style.height = 'auto';
    var textEleHeight = textEle.prop('scrollHeight');
    textEle.css('height', textEleHeight).css('border', '0').css('resize', 'none');
  };

  adjustHeight();
  adjustHeight2();

  var check_time_w = (document.querySelector('.check_date_section .auto_side').offsetWidth);
  var check_time_h = (document.querySelector('.check_date_section .auto_side').offsetHeight);

  // if(check_time_w < 400) {
  //   $(".check_date_section h1").css("left",((check_time_w/2)-7)+"px");
  // }
  // if(700 > check_time_w >= 400) {
  //   $(".check_date_section h1").css("left",((check_time_w/2)-15)+"px");
  // }
  // if(check_time_w >= 700) {
  //   $(".check_date_section h1").css("left",((check_time_w/2)-21)+"px");
  // }
  // $(".check_date_section h1").css("margin-top","-"+check_time_h/3*2+"px");


  //달력확인버튼 클릭시
  $('#cal_ok_btn').click(function() {

    var check_in = $("#check_in").val();
    var check_out = $("#check_out").val();
    var total_num = $('#total_num').val();
    var type = $("#type").val();
    var room_id = $("#room_id").val();
    var gs_id = $("#gs_id").val();

    //휴일체크
    var in_holiday = $("#str_in_holiday").val();
    var out_holiday = $("#str_out_holiday").val();

    $.ajax({
      url: "<?php echo TB_MROOM_URL; ?>/detail_view_date.ajax.php",
      type: "POST",
      dataType:"json",
      async: false,
      data: { check_in:check_in, check_out:check_out, in_holiday:in_holiday, out_holiday:out_holiday, total_num:total_num,type:type, room_id:room_id, gs_id:gs_id },
      success:function(data) {

        console.log(data);
        var str_in_date = data.str_in_date; // 체크인날짜
        var str_out_date = data.str_out_date; // 체크아웃날짜
        var chk_in_time = data.chk_in_time; // 체크인시간
        var chk_out_time = data.chk_out_time; // 체크아웃시간
        var interval = data.interval; // 숙박기간

        //리스트 데이터가 없으면
        if(data.code == '999') {
          var date_info = '<section class="check_date_section">'+
                            '<div class="auto_side">'+
                              '<div class="shadow_box" data-toggle="modal" data-target="#date_sel_modal">'+
                                '<h4>체크인</h4>'+
                                '<h2>'+str_in_date+'<i class="fas fa-caret-down"></i></h2>'+
                                '<h3>--:--</h3>'+
                              '</div>'+
                              '<div class="shadow_box" data-toggle="modal" data-target="#date_sel_modal">'+
                                '<h4>체크아웃</h4>'+
                                '<h2>'+str_out_date+'<i class="fas fa-caret-down"></i></h2>'+
                                '<h3>--:--</h3>'+
                              '</div>'+
                            '</div>'+
                            '<h1 class="night shadow_box">'+interval+'박</h1>'+
                          '</section>'


          $('.check_date_section').html(date_info);


          $('.check_div').remove();
          $('.second_div').remove();
          $('.rif_close').remove();

          var empty_tag = '<span>선택하신 날짜에는 해당객실 이용이 어렵습니다. 날짜를 변경 해주세요.</span>';

          $(".detail_info_box .auto_side").append("<div class='second_div'>"+empty_tag+"</div>");

          var next_page = '<button type="button">예약하기</button>';

          $('.fix_btn_section').html(next_page);
        }
        //리스트 데이터가 있으면
        else if(data.code == '200') {

          var date_info = '<div class="auto_side">'+
                            '<div class="shadow_box" data-toggle="modal" data-target="#date_sel_modal">'+
                              '<h4>체크인</h4>'+
                              '<h2>'+str_in_date+'<i class="fas fa-caret-down"></i></h2>'+
                              '<h3>'+chk_in_time+'</h3>'+
                            '</div>'+
                            '<div class="shadow_box" data-toggle="modal" data-target="#date_sel_modal">'+
                              '<h4>체크아웃</h4>'+
                              '<h2>'+str_out_date+'<i class="fas fa-caret-down"></i></h2>'+
                              '<h3>'+chk_out_time+'</h3>'+
                            '</div>'+
                          '</div>'+
                          '<h1 class="night shadow_box">'+interval+'박</h1>';


          $('.check_date_section').html(date_info);

          $('.check_div').remove();
          $('.second_div').remove();
          $('.rif_close').remove();

            var room_info_str = data.data[0].room_info_str;
            // var time_info_str = data.data[0].time_info_str;
            var day_interval = data.data[0].day_interval;
            var gname = data.data[0].gname;
            var check_in_str = data.data[0].check_in_str;
            var discount = data.data[0].discount;
            var nor_price = data.data[0].nor_price;
            var discount_price = data.data[0].discount_price;
            var remain_room = data.data[0].remain_room;

            //객실상태값에 따라 다르게 출력
            var room_state = '';
            var room_info = '';
            //남은 객실 수가 없을때
            if(data.data[0].remain_room == 0) {
              room_state = '예약마감';

              room_info = '<div class ="check_div">'+
                                '<small class="check_time">체크인 <span>'+chk_in_time+' 부터</span></small>'+
                                '<small class="check_time">체크아웃 <span>'+chk_out_time+' 부터</span></small>'+
                              '</div>'+
                              '<div class="rif_close">'+
                                '<span class="rv_close">'+room_state+'</span>'+
                              '</div>';

            } else {
              //연박으로 검색할때
              if (data.data[0].day_interval >= 2) {
                //할인가격이 없을때
                if (data.data[0].discount == '0') {
                  room_state =  '<span></span><del></del>'+
                              '<h2><span class="night1">1박 기준가</span><span>'+nor_price+'</span>원</h2>'+
                              '<div>남은 객실 '+remain_room+'개</div>';
                //할인가격이 있을때
                } else {
                  room_state = '<span class="discount">'+discount+'% </span><del>'+nor_price+'</del>'+
                              '<h2><span class="night1">1박 기준가</span><span>'+discount_price+'</span>원</h2>'+
                              '<div>남은 객실 '+remain_room+'개</div>';
                }
              //1박으로 검색할때
              } else {
                //할인가격이 없을때
                if (data.data[0].discount == '0') {
                  room_state = '<span class="discount"></span><del></del>'+
                              '<h2><span class="night1"></span><span>'+nor_price+'</span>원</h2>'+
                              '<div>남은 객실 '+remain_room+'개</div>';
                //할인가격이 있을때
                } else {
                  room_state = '<span class="discount">'+discount+'% </span><del>'+nor_price+'</del>'+
                              '<h2><span class="night1"></span><span>'+discount_price+'</span>원</h2>'+
                              '<div>남은 객실 '+remain_room+'개</div>';
                }
              }

              room_info =   '<div class ="check_div">'+
                              '<small class="check_time">체크인 <span>'+chk_in_time+' 부터</span></small>'+
                              '<small class="check_time">체크아웃 <span>'+chk_out_time+' 부터</span></small>'+
                            '</div>'+
                            '<div class="second_div">'+room_state+'</div>';
            }


            $('.detail_info_box .auto_side').append(room_info);

            var next_page = '<button type="button" onclick="location.href=\'<?php echo TB_MAPP_URL; ?>/reservation.php?'+room_info_str+'\';">예약하기</button>';

            $('.fix_btn_section').html(next_page);
        }

      },
      error:function(error) {
         alert("오류");
      }
    });

  });
</script>
