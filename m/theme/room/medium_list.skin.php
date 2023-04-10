<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<section class="medium_list_section">
  <ul>
    <input type="hidden" id="type" value="<?php echo $type; ?>" />
    <input type="hidden" id="catecode" value="<?php echo $catecode; ?>" />
    <input type="hidden" id="upcate" value="<?php echo $upcate; ?>" />
    <!-- <input type="hidden" id="check_in" value="<?php echo $check_in; ?>" />
    <input type="hidden" id="check_out" value="<?php echo $check_out; ?>" /> -->
    <input type="hidden" id="total_num" value="<?php echo $total_num; ?>" />
    <input type="hidden" id="num_rows_index" value="1" />
    <input type="hidden" id="num_rows" value="<?php echo sql_num_rows($result); ?>" />
    <?php
      //불러온 리스트가 없으면
      if(sql_num_rows($result) == 0){
    ?>
    <li class="list_none_box">
      <div class="shadow_box">
        <span>상품이 존재하지 않습니다.</span>
      </div>
    </li>
    <?php
    }
    else {
      for($i=0; $row=sql_fetch_array($result); $i++) {

        $daily = array('일','월','화','수','목','금','토'); //요일을 배열로
        $weekday = $daily[date('w')]; //오늘 요일

        //체크인 시간
        if($weekday == '월') { $mon = json_decode($row['use_mon'], true); $check_in_str = $mon["data"][0]["in_time"]; }
        if($weekday == '화') { $tue = json_decode($row['use_tue'], true); $check_in_str = $tue["data"][0]["in_time"]; }
        if($weekday == '수') { $wed = json_decode($row['use_wed'], true); $check_in_str = $wed["data"][0]["in_time"]; }
        if($weekday == '목') { $thu = json_decode($row['use_thu'], true); $check_in_str = $thu["data"][0]["in_time"]; }
        if($weekday == '금') { $fri = json_decode($row['use_fri'], true); $check_in_str = $fri["data"][0]["in_time"]; }
        if($weekday == '토') { $sat = json_decode($row['use_sat'], true); $check_in_str = $sat["data"][0]["in_time"]; }
        if($weekday == '일') { $sun = json_decode($row['use_sun'], true); $check_in_str = $sun["data"][0]["in_time"]; }

        //공휴일 어케 해야지?
        // $holiday = json_decode($row['use_hol'], true);

        //할인율 존재에 따른 할인가격, 특가가격 구분
        $discount = '';
        $discount_price = '';
        //기본 할인율이 존재하고 특가 할인율이 존재 하지 않을때
        if($row['off_percent'] && !$row['special_percent']){
          $discount = $row['off_percent'];
          $discount_price = $row['gs_price'];
        //기본 할인율이 존재하고 특가 할인율이 존재할때
        } else if($row['off_percent'] && $row['special_percent']){
          $discount = $row['special_percent'];
          $discount_price = $row['special_price'];
        //둘다 존재 하지 않을때
        } else if(!$row['off_percent'] && !$row['special_percent']){
          $discount = '0';
          $discount_price = $row['normal_price'];
        }

    ?>
    <li class="medium_list_box" onclick="location.href='view.php?type=<?php echo $type; ?>&room_id=<?php echo $row['mb_id']; ?>';">
      <div class="auto_side">
        <div class="img_div">
          <?php if($row['company_img']) { ?>
            <div class="img_box" style="background-image: url('<?php echo "../../data/room".$cb_type_nm."/".$row['company_img']; ?>');"></div>
          <?php } else { ?>
            <div class="img_box" style="background-image: url('../img/image_none.png');"></div>
          <?php } ?>
        </div>
        <!-- 세부정보 -->
        <div class="medium_info_box">
          <div class="info_box">
            <h3><?php echo($row['company_name']);?></h3>
            <!-- 리뷰 평균 평점 0.1점 단위로 표현 -->
            <div class="score">
              <?php
                $score = 4.7;
                $score1 = explode('.', $score)[0];
                $score2 = explode('.', $score)[1];

                for($s=0;$s<$score1;$s++) {
                  echo '<img src="../img/score_icon.png" />';
                }
                if($score2 != 0) {
                  echo '<img src="../img/score0_'.$score2.'.png" />';
                } else {
                  if($score1 != 5) {
                  echo '<img src="../img/score_none_icon.png" />';
                  }
                }
                for($n=0;$n<(4-$score1);$n++) {
                    echo '<img src="../img/score_none_icon.png" />';
                }
              ?>
              <span><?php echo $score; ?></span><span class="review_num"> | 1,000</span>
            </div>
          </div>
          <!-- 내 주변 -->
          <!-- <h4>1.5km | 강남구 역삼동</h4> -->
          <div class="price_info auto_side">
            <div class="info_left">
              <small>숙박</small>
              <h4><?php echo $check_in_str; ?> 부터</h4>
            </div>
            <div class="info_right">
                <span class="pers"><?php echo number_format($discount); ?>%</span><del class="ori_price"><?php echo display_price($row['normal_price']);?>~</del>
                <h3><span class="price_bold"><?php echo display_price($discount_price); ?>~</span></h3>
            </div>
          </div>
          <div class="short_ex">
            <?php if($row['location_desc']) {
                echo $row['location_desc'];
            } else { ?>
              <span>짧은 주소 설명이 없습니다.</span>
            <?php }?>
          </div>
        </div>
      </div>
    </li>
  <?php } } ?>

  </ul>
</section>

<!-- Modal -->
<div class="full-screen-modal fx_none_modal">
  <div class="modal fade" id="filter_modal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-body">
          <div class="room_filter">
            <div class="tit">
              <div class="btn_div_left" data-dismiss="modal" id="filter_close_btn">
                <i class="fas fa-chevron-left"></i>
              </div>
              <h2>정렬 및 필터</h2>
              <button type="button" class="reset_btn">초기화</button>
            </div>
            <div class="form_div">
              <div class="input_group">
                <h3>정렬</h3>
                <div class="input_group_select">
                  <select id="list_sort">
                    <option value="인기순" selected>인기순</option>
                    <option value="거리순">거리순</option>
                    <option value="낮은가격순">낮은가격순</option>
                    <option value="높은가격순">높은가격순</option>
                    <option value="평점높은순">평점높은순</option>
                  </select>
                </div>
              </div>

              <!-- <div class="input_group auto_side personnel_div">
                <h3>투숙인원</h3>
                <div class="personnel">
                  <input type="hidden" id="personnel" value="<?php echo get_session('total_num'); ?>"/>
                  <button type="button" id="pers_minus"><i class="fas fa-minus"></i></button>
                  <span id="pers_num"><?php echo get_session('total_num'); ?></span>
                  <button type="button" id="pers_plus"><i class="fas fa-plus"></i></button>
                </div>
              </div> -->

              <?php if($type == 'HT') { ?>
              <div class="input_group">
                <h3>호텔 등급<small>Hotel Rating</small></h3>
                <nav id="filter_group_gnb">
                  <ul class="md_ul grade_ul">
                    <li>
                      <label>
                        <span>
                          <div>5성급</div>
                          <small>5Star</small>
                        </span>
                        <input type="checkbox" name="grade_type" id="grade5" value="5" />
                      </label>
                    </li>
                    <li>
                      <label>
                        <span>
                          <div>4성급</div>
                          <small>4Star</small>
                        </span>
                        <input type="checkbox" name="grade_type" id="grade4" value="4"  />
                      </label>
                    </li>
                    <li>
                      <label>
                        <span>
                          <div>3성급</div>
                          <small>3Star</small>
                        </span>
                        <input type="checkbox" name="grade_type" id="grade3" value="3"  />
                      </label>
                    </li>
                    <li>
                      <label>
                        <span>
                          <div>2성급</div>
                          <small>2Star</small>
                        </span>
                        <input type="checkbox" name="grade_type" id="grade2" value="2"  />
                      </label>
                    </li>
                    <li>
                      <label>
                        <span>
                          <div>1성급</div>
                          <small>1Star</small>
                        </span>
                        <input type="checkbox" name="grade_type" id="grade1" value="1"  />
                      </label>
                    </li>
                  </ul>
                </nav>
              </div>
              <?php } ?>


              <div class="input_group">
                <h3>베드 타입<small>Bed Type</small></h3>
                <nav id="filter_group_gnb">
                  <ul class="md_ul bed_ul">
                    <li>
                      <label>
                        <span>
                          <div>싱글</div>
                          <small>Single</small>
                        </span>
                        <input type="checkbox" name="bed_type" id="bed_type1" value="싱글" />
                      </label>
                    </li>
                    <li>
                      <label>
                        <span>
                          <div>더블</div>
                          <small>Double</small>
                        </span>
                        <input type="checkbox" name="bed_type" id="bed_type2" value="더블"  />
                      </label>
                    </li>
                    <li>
                      <label>
                        <span>
                          <div>트윈</div>
                          <small>Twin</small>
                        </span>
                        <input type="checkbox" name="bed_type" id="bed_type3" value="트윈"  />
                      </label>
                    </li>
                    <li>
                      <label>
                        <span>
                          <div>킹</div>
                          <small>King</small>
                        </span>
                        <input type="checkbox" name="bed_type" id="bed_type4" value="킹"  />
                      </label>
                    </li>
                    <li>
                      <label>
                        <span>
                          <div>엑스트라</div>
                          <small>Extra</small>
                        </span>
                        <input type="checkbox" name="bed_type" id="bed_type5" value="엑스트라"  />
                      </label>
                    </li>
                    <li>
                      <label>
                        <span>
                          <div>온돌</div>
                          <small>On-dol</small>
                        </span>
                        <input type="checkbox" name="bed_type" id="bed_type6" value="온돌"  />
                      </label>
                    </li>
                  </ul>
                </nav>
              </div>


              <div class="input_group">
                <h3>숙박 시설<small>Accommodation</small></h3>
                <nav id="filter_group_gnb">
                  <ul class="md_ul acmdt_ul">
                    <?php
                      $sql = ' select * from hi_room_facilities_master where fac_type = "OUT" and filter_use = "Y"';
                      $fac_result = sql_query($sql);

                      //불러온 리스트가 없으면
                      if(sql_num_rows($fac_result) == 0){
                    ?>
                      <li>
                        <span>시설 필터가 없습니다.</span>
                      </li>
                    <?php
                      }
                      else {
                        for($j=0; $fac_row=sql_fetch_array($fac_result); $j++) {
                    ?>
                    <li>
                      <label>
                        <span>
                          <?php echo $fac_row['fac_name']; ?>
                        </span>
                        <input type="checkbox" name="acmdt_type" id="acmdt_type<?php echo $j; ?>" value="<?php echo $fac_row['fac_group_nm']; ?>" />
                      </label>
                    </li>
                    <?php } } ?>
                  </ul>
                </nav>
              </div>


              <div class="input_group">
                <h3>객실 시설<small>Room Facilities</small></h3>
                <nav id="filter_group_gnb">
                  <ul class="md_ul fclt_ul">
                    <?php
                      $sql = ' select * from hi_room_facilities_master where fac_type = "IN" and filter_use = "Y"';
                      $fac_result = sql_query($sql);

                      //불러온 리스트가 없으면
                      if(sql_num_rows($fac_result) == 0){
                    ?>
                      <li>
                        <span>시설 필터가 없습니다.</span>
                      </li>
                    <?php
                      }
                      else {
                        for($j=0; $fac_row=sql_fetch_array($fac_result); $j++) {
                    ?>
                    <li>
                      <label>
                        <span>
                          <?php echo $fac_row['fac_name']; ?>
                        </span>
                        <input type="checkbox" name="facilities_type" id="fclt_type<?php echo $j; ?>" value="<?php echo $fac_row['fac_group_nm']; ?>" />
                      </label>
                    </li>
                    <?php } } ?>
                  </ul>
                </nav>
              </div>


              <div class="input_group">
                <h3>가격<small>Price</small></h3>
                <nav id="filter_group_gnb">
                  <ul class="md_ul price_ul">
                    <li>
                      <label>
                        <span>5만원 이하</span>
                        <input type="radio" name="price_type" id="price_type1" value="0" />
                      </label>
                    </li>
                    <li>
                      <label>
                        <span>5~10만원</span>
                        <input type="radio" name="price_type" id="price_type2" value="5" />
                      </label>
                    </li>
                    <li>
                      <label>
                        <span>10~20만원</span>
                        <input type="radio" name="price_type" id="price_type3" value="10" />
                      </label>
                    </li>
                    <li>
                      <label>
                        <span>20~30만원</span>
                        <input type="radio" name="price_type" id="price_type4" value="20" />
                      </label>
                    </li>
                    <li>
                      <label>
                        <span>30만원 이상</span>
                        <input type="radio" name="price_type" id="price_type5" value="30" />
                      </label>
                    </li>
                  </ul>
                </nav>
              </div>
            </div>
            <button type="button" class="filter_set_btn" data-dismiss="modal">적용하기</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal end -->


<script>
  $(document).ready(function() {
    var list_img_w = (document.querySelector('.medium_list_box .img_box').offsetWidth);
    var list_img_h = (list_img_w/3)*4;
    var list_info_box_h = (list_img_h/3)*2;
    var list_price_box_h = (list_img_h/3)*1;

    // $(".img_box").css("height", list_img_h+"px");
    // $(".info_box").css("height", list_info_box_h+"px");
    // $(".price_info").css("height", list_price_box_h+"px");

    $(".list_head_select_section").attr("style", "padding-bottom: 0;");
  });

  // 초기화 버튼 또는 팝업창 닫기 버튼 클릭 시
  $(".reset_btn").click(function() {
    $("#list_sort").val("인기순").prop("selected",true);
    // $("#personnel").val("2");
    // $("#pers_num").text("2");
    $("input:checkbox[name=grade_type]").prop("checked", false);
    $(".grade_ul li label").attr("style","background-color: #F1F1F1;");
    $(".grade_ul li label span div").attr("style","color: #000;");
    $(".grade_ul li label span small").attr("style","color: #828282;");
    $("input:checkbox[name=bed_type]").prop("checked", false);
    $(".bed_ul li label").attr("style","background-color: #F1F1F1;");
    $(".bed_ul li label span div").attr("style","color: #000;");
    $(".bed_ul li label span small").attr("style","color: #828282;");
    $("input:checkbox[name=acmdt_type]").prop("checked", false);
    $(".acmdt_ul li label").attr("style","background-color: #F1F1F1;");
    $(".acmdt_ul li label span").attr("style","color: #000;");
    $("input:checkbox[name=facilities_type]").prop("checked", false);
    $(".fclt_ul li label").attr("style","background-color: #F1F1F1;");
    $(".fclt_ul li label span").attr("style","color: #000;");
    $("input:radio[name=price_type]").prop("checked", false);
    $(".radio_price label").attr("style","background-color: #F1F1F1;");
    $(".radio_price label span").attr("style","color: #000;");
  });

  $("#filter_close_btn").click(function() {
    $("#list_sort").val("인기순").prop("selected",true);
    // $("#personnel").val("2");
    // $("#pers_num").text("<?php echo get_session('total_num'); ?>");
    $("input:checkbox[name=grade_type]").prop("checked", false);
    $(".grade_ul li label").attr("style","background-color: #F1F1F1;");
    $(".grade_ul li label span div").attr("style","color: #000;");
    $(".grade_ul li label span small").attr("style","color: #828282;");
    $("input:checkbox[name=bed_type]").prop("checked", false);
    $(".bed_ul li label").attr("style","background-color: #F1F1F1;");
    $(".bed_ul li label span div").attr("style","color: #000;");
    $(".bed_ul li label span small").attr("style","color: #828282;");
    $("input:checkbox[name=acmdt_type]").prop("checked", false);
    $(".acmdt_ul li label").attr("style","background-color: #F1F1F1;");
    $(".acmdt_ul li label span").attr("style","color: #000;");
    $("input:checkbox[name=facilities_type]").prop("checked", false);
    $(".fclt_ul li label").attr("style","background-color: #F1F1F1;");
    $(".fclt_ul li label span").attr("style","color: #000;");
    $("input:radio[name=price_type]").prop("checked", false);
    $(".radio_price label").attr("style","background-color: #F1F1F1;");
    $(".radio_price label span").attr("style","color: #000;");
  });


  // personnel +, -
  // $("#pers_minus").click(function() {
  //   var pers_val = Number($("#personnel").val());
  //   var pers_m_num = pers_val - 1;
  //   if(pers_val > 1) {
  //     $("#personnel").val(pers_m_num);
  //     $("#pers_num").text(pers_m_num);
  //   }
  // });
  // $("#pers_plus").click(function() {
  //   var pers_val = Number($("#personnel").val());
  //   var pers_p_num = pers_val + 1;
  //   if(pers_val < 10) {
  //     $("#personnel").val(pers_p_num);
  //     $("#pers_num").text(pers_p_num);
  //   }
  // });


  // grade checkbox label
  $("input:checkbox[name=grade_type]").change(function() {
    var grade_num = $(this).val();
    if($("#grade"+grade_num).is(":checked") == true) {
      $("#grade"+grade_num).parents('label').attr("style", "background-color: #1770FF;");
      $(this).siblings('span').children('div').attr("style", "color: #FFF;");
      $(this).siblings('span').children('small').attr("style", "color: #FFF;");
    } else {
      $("#grade"+grade_num).parents('label').attr("style", "background-color: #F1F1F1;");
      $(this).siblings('span').children('div').attr("style", "color: #000;");
      $(this).siblings('span').children('small').attr("style", "color: #828282;");
    }
  });


  // bed_type checkbox label
  $("input:checkbox[name=bed_type]").change(function() {
    var bed_type_id = $(this).attr('id');
    if($("#"+bed_type_id).is(":checked") == true) {
      $("#"+bed_type_id).parents('label').attr("style", "background-color: #1770FF;");
      $(this).siblings('span').children('div').attr("style", "color: #FFF;");
      $(this).siblings('span').children('small').attr("style", "color: #FFF;");
    } else {
      $("#"+bed_type_id).parents('label').attr("style", "background-color: #F1F1F1;");
      $(this).siblings('span').children('div').attr("style", "color: #000;");
      $(this).siblings('span').children('small').attr("style", "color: #828282;");
    }
  });


  // Accommodation checkbox label
  $("input:checkbox[name=acmdt_type]").change(function() {
    var fclt_type_id = $(this).attr('id');
    if($("#"+fclt_type_id).is(":checked") == true) {
      $("#"+fclt_type_id).parents('label').attr("style", "background-color: #1770FF;");
      $(this).siblings('span').attr("style", "color: #FFF;");
    } else {
      $("#"+fclt_type_id).parents('label').attr("style", "background-color: #F1F1F1;");
      $(this).siblings('span').attr("style", "color: #000;");
    }
  });


  // facilities_type checkbox label
  $("input:checkbox[name=facilities_type]").change(function() {
    var fclt_type_id = $(this).attr('id');
    if($("#"+fclt_type_id).is(":checked") == true) {
      $("#"+fclt_type_id).parents('label').attr("style", "background-color: #1770FF;");
      $(this).siblings('span').attr("style", "color: #FFF;");
    } else {
      $("#"+fclt_type_id).parents('label').attr("style", "background-color: #F1F1F1;");
      $(this).siblings('span').attr("style", "color: #000;");
    }
  });


  // price_type checkbox label
  $("input:radio[name=price_type]").change(function() {
    var price_type_id = $(this).attr('id');
      $("label input:radio[name=price_type]").parents('label').attr("style", "background-color: #F1F1F1;");
      $("label input:radio[name=price_type]").siblings('span').attr("style", "color: #000;");
      $("#"+price_type_id).parents('label').attr("style", "background-color: #1770FF;");
      $(this).siblings('span').attr("style", "color: #FFF;");
  });


  // 아래로 스크롤 시 지역, 날자, 인원 선택 박스 아래에 그림자 생성
  var _outerHeight = window.outerHeight;

  function myFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
      $(".top_fixed_section").attr("style","box-shadow: 0px 3px 10px 1px rgb(0 0 0 / 15%);");
    } else {
      $(".top_fixed_section").attr("style","box-shadow: unset;");
    }
  }



  //통합 객실박스 태그 만드는 함수
  function callMediumBox (type, mb_id, company_img, company_name, normal_price, discount, discount_price, check_in_str, location_desc) {

    var go_url = 'onclick="location.href=\'<?php echo TB_MROOM_URL; ?>/view.php?type='+type+'&room_id='+mb_id+'\'";';

    if(company_img) {
      var img_div = '<div class="img_div">'
                    +'<div class="img_box" style="background-image: url(\'../../data/room/'+company_img+'\');"></div>'
                    +'</div>';
    }else {
      var img_div = '<div class="img_div">'
                    +'<div class="img_box" style="background-image: url(\'../img/image_none.png\');"></div>'
                    +'</div>';
    }

    var score_icon = '';
    var j = 0;
    for(j=1; j<=4; j++) {
      score_icon += '<img src="../img/score_icon.png" />';
      j=j;
    }
    for(var z=1; z<(5-j); z++) {
      score_icon += '<img src="../img/score_none_icon.png" />';
    }

    var result_tag = '<li class="medium_list_box" '+go_url+'>'+
    // 이미지
    '<div class="auto_side">'+img_div+
      //세부정보
      '<div class="medium_info_box">'+
        '<div class="info_box">'+
          '<h3>'+company_name+'</h3>'+
          '<div class="score">'+score_icon+
            '<span>4.7</span><span class="review_num"> | 1,000</span>'+
          '</div>'+
        '</div>'+
        //할인율 + 가격
        '<div class="price_info auto_side">'+
          '<div class="info_left">'+
            '<small>숙박</small>'+
            '<h4>'+check_in_str+' 부터</h4>'+
          '</div>'+
          '<div class="info_right">'+
            '<span class="pers">'+discount+'%</span><del class="ori_price">'+normal_price+'~</del>'+
            '<h3><span class="price_bold">'+discount_price+'~</span></h3>'+
          '</div>'+
        '</div>'+
        '<div class="short_ex">'+location_desc+'</div>'+
        '</div>'+
      '</div>'+
    '</li>';

    return result_tag;
  }

  //이미지 박스 너비 자동 잡아주는 함수
  function offSetWidth() {
    var list_img_w = (document.querySelector('.medium_list_box .img_box').offsetWidth);
    var list_img_h = (list_img_w/3)*4;
    var list_info_box_h = (list_img_h/3)*2;
    var list_price_box_h = (list_img_h/3)*1;

    // $(".img_box").css("height", list_img_h+"px");
    // $(".info_box").css("height", list_info_box_h+"px");
    // $(".price_info").css("height", list_price_box_h+"px");
  }

  var medium_count = 2;
  var type = $('#type').val();
  var catecode = $('#catecode').val();
  var upcate = $('#upcate').val();
  var check_in = $('#check_in').val();
  var check_out = $('#check_out').val();
  var total_num = $('#total_num').val();


  //무한 스크롤 시작 ------------------------------------------------------------------------

  window.onscroll = function(e) {
      myFunction();
      function myFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
          $(".top_fixed_section").attr("style","box-shadow: 0px 3px 10px 1px rgb(0 0 0 / 15%);");
        } else {
          $(".top_fixed_section").attr("style","box-shadow: unset;");
        }
      }

      var num_rows_index = $('#num_rows_index').val(); //1, 2, 3
      var num_rows = parseInt($('#num_rows').val()); //10, 20, 30
      var num_rows_index_2 = parseInt(num_rows_index+"0");

      //10개 이상이면
      if(num_rows >= num_rows_index_2) {

        if((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {

          setTimeout(function(){
            //바닥 인지 후
            $.ajax({
              url:"./infinite_list_ajax.php",
              type:"POST",
              dataType:"json",
              async: false,
              data: { "num_rows_index":num_rows_index, "type":type, "catecode":catecode, "upcate":upcate, "check_in":check_in, "check_out":check_out, "total_num":total_num },
              success: function(data) {

                //리스트 데이터가 있으면
                if(data.code == '200') {
                  for(var i=0; i<data.data.length; i++) {
                    var mb_id = data.data[i].mb_id;
                    var company_img = data.data[i].company_img;
                    var company_name = data.data[i].company_name;
                    var normal_price = data.data[i].normal_price;
                    var discount = data.data[i].discount;
                    var discount_price = data.data[i].discount_price;
                    var check_in_str = data.data[i].check_in_str;
                    var location_desc = data.data[i].location_desc;

                    var mediumBox_tag = callMediumBox(type, mb_id, company_img, company_name, normal_price, discount, discount_price, check_in_str, location_desc);

                    $(".medium_list_section ul").append(mediumBox_tag);
                  }

                  $('#num_rows_index').val((num_rows_index*=1)+1);
                  num_rows+=parseInt(json_file.length);
                  $('#num_rows').val(num_rows);

                  //이미지 박스 너비 자동 잡아주기
                  offSetWidth();
                }


              },
              error: function(data) {
                // alert("오류");
              }
            });
          }, 1000)
        }
      }

  } //무한 스크롤 했을시 끝나는 함수



  //체크인, 체크아웃 날짜 확인 버튼 눌렸을시 ------------------------------------------------------------------------
  $('#cal_ok_btn').click(function() {

      var check_in = $("#check_in").val();
      var check_out = $("#check_out").val();
      //휴일체크
      var in_holiday = $("#str_in_holiday").val();
      var out_holiday = $("#str_out_holiday").val();
      var type = $("#type").val();
      var upcate = $("#upcate").val();
      var catecode = $("#catecode").val();

      $.ajax({
        url: "<?php echo TB_MROOM_URL; ?>/list_date.ajax.php",
        type: "POST",
        dataType:"json",
        async: false,
        data: { check_in:check_in, check_out:check_out, in_holiday:in_holiday, out_holiday:out_holiday, total_num:total_num,type:type,upcate:upcate,catecode:catecode},
        success:function(data) {

          //리스트 데이터가 있으면
          if(data.code == '200') {
            $('.medium_list_section ul li').remove();

            for(var i=0; i<data.data.length; i++) {
              var mb_id = data.data[i].mb_id;
              var company_img = data.data[i].company_img;
              var company_name = data.data[i].company_name;
              var normal_price = data.data[i].normal_price;
              var discount = data.data[i].discount;
              var discount_price = data.data[i].discount_price;
              var check_in_str = data.data[i].check_in_str;
              var location_desc = data.data[i].location_desc;

              var mediumBox_tag = callMediumBox(type, mb_id, company_img, company_name, normal_price, discount, discount_price, check_in_str, location_desc);

              $(".medium_list_section ul").append(mediumBox_tag);
            }

            //이미지 박스 너비 자동 잡아주기
            offSetWidth();
          }

          //리스트 데이터가 없으면
          else if(data.code == '999') {
            $('.medium_list_section ul li').remove();
            var empty_tag = '<li class="list_none_box">'
              +'<div class="shadow_box">'
              +'<span>상품이 존재하지 않습니다.</span>'
              +'</div>';
            $(".medium_list_section ul").append(empty_tag);
          }

        },
        error:function(error) {
           // alert("오류");
        }
      });

  });


  // 필터 ---------------------------------------------------------------------------------------------

  //정렬-평점높은순 클릭했을시
  $('#list_sort').change(function(){
    if($(this).val() == '평점높은순') {
      alert("평점높은순 기능은 현재 준비중입니다.");
      $(this).val("인기순").prop("selected", true);
    }
  });

  //필터 적용하기 버튼 눌렸을시
  $('.filter_set_btn').click(function() {

    var check_in = $("#check_in").val();
    var check_out = $("#check_out").val();
    var type = $("#type").val();
    var upcate = $("#upcate").val();
    var catecode = $("#catecode").val();

    //필터 요소들 -----------------------
    var list_sort = $('#list_sort').val(); //정렬
    var total_num = $('#personnel').val(); //투숙인원
    var grade_type_chk = []; //등급
    $("input[name=grade_type]:checked").each(function() {
      grade_type_chk.push($(this).val());
    });
    var bed_type_chk = []; //배드타입
    $("input[name=bed_type]:checked").each(function() {
      bed_type_chk.push($(this).val());
    });
    //시설

    $.ajax({
      url:"./list_filter.ajax.php",
      type:"POST",
      dataType:"json",
      async: false,
      data: { check_in:check_in, check_out:check_out, total_num:total_num, type:type, upcate:upcate, catecode:catecode, grade_type_chk:grade_type_chk, bed_type_chk:bed_type_chk },
      success: function(data) {

        //리스트 데이터가 있으면
        if(data.code == '200') {
          $('.medium_list_section ul li').remove();

          for(var i=0; i<data.data.length; i++) {
            var mb_id = data.data[i].mb_id;
            var company_img = data.data[i].company_img;
            var company_name = data.data[i].company_name;
            var normal_price = data.data[i].normal_price;
            var discount = data.data[i].discount;
            var discount_price = data.data[i].discount_price;
            var check_in_str = data.data[i].check_in_str;
            var location_desc = data.data[i].location_desc;

            var mediumBox_tag = callMediumBox(type, mb_id, company_img, company_name, normal_price, discount, discount_price, check_in_str, location_desc);

            $(".medium_list_section ul").append(mediumBox_tag);
          }

          //이미지 박스 너비 자동 잡아주기
          offSetWidth();
        }

        //리스트 데이터가 없으면
        else if(data.code == '999') {
          $('.medium_list_section ul li').remove();
          var empty_tag = '<li class="list_none_box">'
            +'<div class="shadow_box">'
            +'<span>상품이 존재하지 않습니다.</span>'
            +'</div>';
          $(".medium_list_section ul").append(empty_tag);
        }

      },
      error:function(error) {
        // alert("오류");
      }
    });


  });




</script>
