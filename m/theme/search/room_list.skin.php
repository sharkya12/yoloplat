<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>
<section class="slide_menu_section">
  <div class="swiper" id="swiper-menu">
    <div class="swiper-wrapper">
    <input type="hidden" id="searched_keyword" value="<?php echo $keyword; ?>">
    <input type="hidden" id="type" value="<?php echo $type; ?>">
    <input type="hidden" id="total_num" value="<?php echo $total_num; ?>">
    <input type="hidden" id="id" value="<?php echo $member['id']?>">

    <!-- 전체 및 카테고리(호텔, 모텔 등등) -->
    <?php for($c=0;$c<=8;$c++) { ?>
    <div class="swiper-slide">
      <section class="medium_list_section" id="medium_list_section<?php echo $c; ?>">

        <ul class="medium_ul<?php echo $c; ?>">

          <?php
            if($c == 0) {
              //불러온 리스트가 없으면
              if(sql_num_rows($result) == 0){
          ?>
              <li class="list_none_box">
                <div>
                  <span>상품이 존재하지 않습니다.</span>
                </div>
              </li>
              <?php
              } else {
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
                  if($in_holiday == 'Y') { $hol = json_decode($room['use_hol'], true); $check_in_str = $hol["data"][0]["in_time"]; }

                  //숙박 가격 구하기
                  $nor_price = number_format($row['normal_price']);
                  $discount = number_format($row['final_percent']);
                  $discount_price = number_format($row['final_price']);

                  // //남은객실 수 구하기
                  // $remain_room = $row['room_gty'];

                  //숙박업소에 따라 타입 바꾸기
                  $seller_item = $row['seller_item'];

                  switch($seller_item) {
                    case "호텔" :
                      $type = "HT";
                      break;
                    case "모텔" :
                      $type = "MT";
                      break;
                    case "펜션" :
                    case "풀빌라" :
                      $type = "PS";
                      break;
                    case "리조트" :
                    case "콘도" :
                      $type = "RS";
                      break;
                    case "반려견" :
                      $type = "PET";
                      break;
                    case "캠핑" :
                    case "글램핑" :
                      $type = "KP";
                      break;
                    case "게스트하우스" :
                      $type = "GE";
                      break;
                    case "한달" :
                      $type = "MO";
                  }

              ?>
              <li class="medium_list_box" onclick="location.href='<?php echo TB_MROOM_URL; ?>/view.php?type=<?php echo $type; ?>&room_id=<?php echo $row['mb_id']; ?>';">
                <div class="auto_side">
                  <div class="img_div">
                    <?php if($row['company_img']) { ?>
                      <div class="img_box" style="background-image: url('<?php echo "../../data/room/".$row['company_img']; ?>');"></div>
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
                          if($row['avg_score']) {
                              $score = $row['avg_score'];
                          } else {
                              $score = '0.0';
                          }
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
                        <span><?php echo $score; ?></span><span class="review_num">  (<?php if($row['cnt_score']) { echo number_format($row['cnt_score']); }  else { echo 0; }?>)</span>
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
                        <!-- 연박이용시일때 -->
                        <?php if((int)$interval >= 2) { ?>
                          <!-- 할인가격이 없을때 -->
                          <?php if($discount == '0') { ?>
                            <span class="pers"> </span><del> </del>
                            <h3><span class="night1">1박 기준가</span><span class="price_bold"><?php echo $nor_price;?></span>원</h3>
                          <!-- 할인가격이 있을때 -->
                          <?php } else { ?>
                            <span class="pers"><?php echo $discount;?>% </span><del><?php echo $nor_price;?>원</del>
                            <h3><span class="night1">1박 기준가</span><span class="price_bold"><?php echo $discount_price;?></span>원</h3>
                          <?php } ?>

                        <!-- 1박이용시일때 -->
                        <?php } else { ?>
                          <!-- 할인가격이 없을때 -->
                          <?php if($discount == '0') { ?>
                            <span class="pers"> </span><del> </del>
                            <h3><span class="night1"></span><span class="price_bold"><?php echo $nor_price;?></span>원</h3>
                          <!-- 할인가격이 있을때 -->
                          <?php } else { ?>
                            <span class="pers"><?php echo $discount;?>% </span><del><?php echo $nor_price;?>원</del>
                            <h3><span class="night1"></span><span class="price_bold"><?php echo $discount_price;?></span>원</h3>
                          <?php } ?>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="short_ex">
                      <?php if($row['location_desc']) { ?>
                        <span><?php echo $row['location_desc']; ?></span>
                      <?php } else { ?>
                        <span>짧은 주소 설명이 없습니다.</span>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              </li>
            <?php } ?>
          <?php } ?>
        <?php } ?>
        </ul>
      </section>
    </div>
    <script>
      // 무한스크롤
      var count = 0;
      document.querySelector('.medium_ul<?php echo $c; ?>').onscroll = function(e) {
        // 바닥을 인지할때
        if((document.querySelector('.medium_ul<?php echo $c; ?>').offsetHeight  + $('.medium_ul<?php echo $c; ?>').scrollTop()) >= $('.medium_ul<?php echo $c; ?>').prop('scrollHeight')) {
          count++;

          var s_index = mSlider.realIndex;
          let s_id = '';
          switch (s_index) {
            case 0 :
              s_id = 'ALL';
              break;
            case 1 :
              s_id = 'HT';
              break;
            case 2 :
              s_id = 'MT';
              break;
            case 3 :
              s_id = 'PS';
              break;
            case 4 :
              s_id = 'RS';
              break;
            case 5 :
              s_id = 'PET';
              break;
            case 6 :
              s_id = 'CP';
              break;
            case 7 :
              s_id = 'GH';
              break;

            default:
              s_id = 'LM';
          }

          console.log(s_id);
          var check_in = $("#check_in").val();
          var check_out = $("#check_out").val();
          var upcate = $("#upcate").val();
          var catecode = $("#catecode").val();
          var total_num = $("#total_num").val();
          //휴일체크
          var in_holiday = $("#str_in_holiday").val();
          var out_holiday = $("#str_out_holiday").val();

          var keyword = $("#searched_keyword").val();
          var muhan = 'yes';

          setTimeout(function(){
            let box_img = "'../img/hotel_img.jpg'";
            $.ajax({
              url: "./list_slide.ajax.php",
              type: "POST",
              dataType:"json",
              async: false,
              data: { s_id:s_id, count:count, keyword:keyword, check_in:check_in, check_out:check_out, in_holiday:in_holiday, out_holiday:out_holiday, total_num:total_num, muhan:muhan },
              success:function(data) {

                var interval = data.interval;
                //리스트 데이터가 있으면
                if(data.code == '200') {
                  for(var i=0; i<data.data.length; i++) {
                    var mb_id = data.data[i].mb_id;
                    var company_img = data.data[i].company_img;
                    var company_name = data.data[i].company_name;
                    var normal_price = data.data[i].normal_price;
                    var discount = data.data[i].discount;
                    var discount_price = data.data[i].discount_price;
                    var avg_score = data.data[i].avg_score;
                    var cnt_score = data.data[i].cnt_score;
                    var check_in_str = data.data[i].check_in_str;
                    var location_desc = data.data[i].location_desc;
                    var type = data.data[i].type;

                    var mediumBox_tag = callMediumBox(type, mb_id, company_img, company_name, normal_price, discount, discount_price, avg_score, cnt_score, check_in_str, location_desc, interval);

                    $(".medium_ul"+s_index).append(mediumBox_tag);
                  }
                } //리스트 데이터가 없으면
                  else if(data.code == '999') {
                    console.log(count);
                }
              },
              error:function(request, status, error) {
                console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
              }
            });

          }, 1000)
        }
      }
    </script>

    <?php } ?>
    </div>
  </div>
</section>

<script>
  $(document).ready(function() {
    //검색어를 입력하고 enter를 눌렸을시
    $('#keyword').on("keyup", function(key){
      if(key.keyCode == 13) {
        add_log($('#keyword').val());
      }
    });
    //검색어 입력하고 검색버튼 눌럿을시
    $("#search").click(function(){
      add_log($('#keyword').val());
    });

  });

  //===============================================
  //공통파라미터 값 정보가져오기
  var check_in = $("#check_in").val();
  var check_out = $("#check_out").val();
  var keyword = $("#searched_keyword").val(); //키워드
  var type = $("#type").val();
  var total_num = $("#total_num").val(); //투숙인원

  //휴일체크
  var in_holiday = $("#str_in_holiday").val();
  var out_holiday = $("#str_out_holiday").val();

  //===============================================



    //ajax를 통한 search_log Table에 data 추가하는 함수
    function add_log(keyword){

      var type = $('#type').val();
      if(type == 'KP'){
        type = 'KP';
      } else if(type == 'room') {
        type = 'room';
      } else if(type == 'RT') {
        type = 'RT';
      } else if(type == 'EX') {
        type = 'EX';
      }

      //keyword 빈값
      var blank_pattern = /^\s+|\s+$/g; //스페이스만 입력한 경우 빈값으로

      if(keyword == null || keyword == '' || keyword == undefined || keyword.replace(blank_pattern, '') == ''){
        alert("검색어를 입력해주세요.");
        return false;
      } else {
        //특수문자
        var regExp = /[\{\}\[\]\/?.,;:|\)*~`!^\-_+<>@\#$%&\\\=\(\'\"]/gi;
        //keyword 정규식
        keyword = keyword.trim(); //빈값
        keyword = keyword.replace(regExp, ""); //특수문자 제거
      }

      //로그인 된 상태이면 검색어 DB에 저장하기
      var id = "";
      if($('#id').val()){
        id = $('#id').val();
      }

      var search_chk_time = $('#check_d').text();
      var search_ppl_num = $('#sel_ppl').text();

      var w = '';

      $.ajax({
        url:"./ajax.searchLog.php",
        type:"POST",
        data:{ w:w, id:id, keyword:keyword, type:type, search_chk_time:search_chk_time, search_ppl_num:search_ppl_num},
        success: function(data) {

            // location.href="./list.php?search=Y&keyword="+keyword+"&type="+type;
            location.href="./list.php?keyword="+keyword+"&type="+type;
        }
      });
    }

  var _outerWidth = window.outerWidth;
  var _outerHeight = window.outerHeight;
  var top_fixed_h = (document.querySelector('.top_fixed_section').offsetHeight);


  //통합 객실박스 태그 만드는 함수
  function callMediumBox (type, mb_id, company_img, company_name, normal_price, discount, discount_price, avg_score, cnt_score, check_in_str, location_desc, interval) {

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
    var score = '';

    if(avg_score == null || avg_score == 0) {
      score = '0.0';
    } else {
      score = avg_score;
    }

    var score_arr = score.split('.');
    var score1 = score_arr[0];
    var score2 = score_arr[1];

    for(var s=0;s<score1;s++) {
      score_icon += '<img src="../img/score_icon.png" />';
    }
    if(score2 != 0) {
      score_icon += '<img src="../img/score0_'+score2+'.png" />';
    } else {
      if(score1 != 5) {
        score_icon += '<img src="../img/score_none_icon.png" />';
      }
    }
    for(var n=0;n<(4-score1);n++) {
        score_icon += '<img src="../img/score_none_icon.png" />';
    }

    //cnt_score : 리뷰 갯수
    var final_cnt_score = '';
    if(cnt_score == null) {
      final_cnt_score = 0;
    } else {
      final_cnt_score = cnt_score;
    }

    //연박으로 검색할때
    if (interval >= 2) {
      //할인가격이 없을때
      if (discount == '0') {
        room_state = '<div class="info_right">'+
                    '<span class="pers"> </span><del> </del>'+
                    '<h3><span class="night1">1박 기준가</span><span class="price_bold">'+normal_price+'원</span></h3>'+
                    '</div>';
      //할인가격이 있을때
      } else {
        room_state = '<div class="info_right">'+
                    '<span class="pers">'+discount+'% </span><del>'+normal_price+'원</del>'+
                    '<h3><span class="night1">1박 기준가</span><span class="price_bold">'+discount_price+'원</span></h3>'+
                    '</div>';
      }
    //1박으로 검색할때
    } else {
      //할인가격이 없을때
      if (discount == '0') {
        room_state = '<div class="info_right">'+
                    '<span class="pers"> </span><del> </del>'+
                    '<h3><span class="night1"></span><span class="price_bold">'+normal_price+'원</span></h3>'+
                    '</div>';
      //할인가격이 있을때
      } else {
        room_state = '<div class="info_right">'+
                    '<span class="pers">'+discount+'% </span><del>'+normal_price+'원</del>'+
                    '<h3><span class="night1"></span><span class="price_bold">'+discount_price+'원</span></h3>'+
                    '</div>';
      }
    }

    //짧은 주소 설명
    if(location_desc) {
      var location_desc_tag = '<div class="short_ex">'+location_desc+'</div>';
    } else {
      var location_desc_tag = '<div class="short_ex">짧은 주소 설명이 없습니다.</div>';
    }

    var result_tag = '<li class="medium_list_box" '+go_url+'>'+
    // 이미지
    '<div class="auto_side">'+img_div+
      //세부정보
      '<div class="medium_info_box">'+
        '<div class="info_box">'+
          '<h3>'+company_name+'</h3>'+
          '<div class="score">'+score_icon+
            '<span>'+score+'</span><span class="review_num"> ('+final_cnt_score+')</span>'+
          '</div>'+
        '</div>'+
        //할인율 + 가격
        '<div class="price_info auto_side">'+
          '<div class="info_left">'+
            '<small>숙박</small>'+
            '<h4>'+check_in_str+' 부터</h4>'+
          '</div>'+room_state+
          // '<div class="info_right">'+
          //   '<span class="pers">'+discount+'%</span><del class="ori_price">'+normal_price+'~</del>'+
          //   '<h3><span class="price_bold">'+discount_price+'~</span></h3>'+
          // '</div>'+
        '</div>'+location_desc_tag+
        // '<div class="short_ex">'+location_desc+'</div>'+
        '</div>'+
      '</div>'+
    '</li>';

    return result_tag;
  }

  // 카테고리 슬라이드
  var cateSlider = new Swiper("#swiper-category", {
  slidesPerView: 3.5,
  // spaceBetween: 10,
  grabCursor: true,
  });

  // 상품 슬라이드
  var mSlider = new Swiper('#swiper-menu', {
    slidesPerView: 1,
    spaceBetween: 0,
    autoHeight: true,
    direction: 'horizontal'
  });

  //카테고리 영역 체크 하는 함수
  function check_category(s_id, s_index) {

    //필터 요소들 -----------------------
    var list_sort = $('#list_sort').val(); //정렬
    var grade_type_chk = []; //등급
    $("input[name=grade_type]:checked").each(function() {
      grade_type_chk.push($(this).val());
    });
    var bed_type_chk = []; //배드타입
    $("input[name=bed_type]:checked").each(function() {
      bed_type_chk.push($(this).val());
    });
    var acmdt_type = []; //숙박시설
    $("input[name=acmdt_type]:checked").each(function() {
      acmdt_type.push($(this).val());
    });
    var facilities_type = []; //객실시설
    $("input[name=facilities_type]:checked").each(function() {
      facilities_type.push($(this).val());
    });

    var price_type = $('input[name=price_type]:checked').val();

    var filter_chk = '';
    //필터 클릭이 하나라도 되어있으면
    if(grade_type_chk.length > 0 || bed_type_chk.length > 0 || acmdt_type.length > 0 || facilities_type.length > 0 || price_type) {
      filter_chk = 'Y';
    } else {
      filter_chk = 'N';
    }

    if(s_index > 0) {
      //해당 카테고리 ajax를 통한 리스트 뿌려주는게 처음이면

      if($(".medium_ul"+s_index+" li").length == 0) {

        $.ajax({
          url: "./list_slide.ajax.php",
          type: "POST",
          dataType:"json",
          async: false,
          data: { s_id:s_id, keyword:keyword, check_in:check_in, check_out:check_out, in_holiday:in_holiday, out_holiday:out_holiday, total_num:total_num },
          success:function(data) {

            var interval = data.interval;
            //리스트 데이터가 있으면
            if(data.code == '200') {
              for(var i=0; i<data.data.length; i++) {
                var mb_id = data.data[i].mb_id;
                var company_img = data.data[i].company_img;
                var company_name = data.data[i].company_name;
                var normal_price = data.data[i].normal_price;
                var discount = data.data[i].discount;
                var discount_price = data.data[i].discount_price;
                var avg_score = data.data[i].avg_score;
                var cnt_score = data.data[i].cnt_score;
                var check_in_str = data.data[i].check_in_str;
                var location_desc = data.data[i].location_desc;
                var type = data.data[i].type;

                var mediumBox_tag = callMediumBox(type, mb_id, company_img, company_name, normal_price, discount, discount_price, avg_score, cnt_score, check_in_str, location_desc, interval);

                $(".medium_ul"+s_index).append(mediumBox_tag);
              }
            } //리스트 데이터가 없으면
              else if(data.code == '999') {
                // console.log('hi');
              var empty_tag = '<li class="list_none_box">'
                +'<div>'
                +'<span>상품이 존재하지 않습니다.</span>'
                +'</div>';
              $(".medium_ul"+s_index).append(empty_tag);
              // console.log('oi');
            }
          },
          error:function(request, status, error) {
            console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
          }
        });
      }
      //해당 카테고리 ajax를 통한 리스트를 이미 뿌려줬다면
      else {
        // alert("이미 있습니다");
      }
    }
  }

  // 슬라이드 할 때 카테고리 메뉴 변경
  mSlider.on('transitionEnd', function() {

    $('#ig_HT').hide();

    var s_index = mSlider.realIndex;
    let s_id = '';
    switch (s_index) {
      case 0 :
        s_id = 'ALL';
        break;
      case 1 :
        s_id = 'HT';
        break;
      case 2 :
        s_id = 'MT';
        break;
      case 3 :
        s_id = 'PS';
        break;
      case 4 :
        s_id = 'RS';
        break;
      case 5 :
        s_id = 'PET';
        break;
      case 6 :
        s_id = 'CP';
        break;
      case 7 :
        s_id = 'GH';
        break;

      default:
        s_id = 'LM';
    }

    $('#s_id').val(s_id);
    $('#s_index').val(s_index);

    $("#swiper-category .swiper-slide span").attr("style", "color: #ababab; border-bottom: 0; font-size: 13px;");
    $("#"+s_id+" span").attr("style", "color: #000000; border-bottom: .2rem solid #2e2e2e; font-size: 13px;");

    // if(_outerWidth < 327) {
    //   $("#swiper-category .swiper-slide span").attr("style", "color: #ababab; border-bottom: 0; font-size: 3.8vw;");
    //   $("#"+s_id+" span").attr("style", "color: #000000; border-bottom: .2rem solid #2e2e2e; font-size: 3.8vw;");
    // } else {
    //   $("#swiper-category .swiper-slide span").attr("style", "color: #ababab; border-bottom: 0; font-size: 4vw;");
    //   $("#"+s_id+" span").attr("style", "color: #000000; border-bottom: .2rem solid #2e2e2e; font-size: 4vw;");
    // }

    if(s_id == 'ALL') {
      $(".filter_btn").hide();
      // $(".map_btn").hide();
    } else {
      // $(".map_btn").show();
      $(".filter_btn").show();
    }

    cateSlider.slideTo(s_index);

    //카테고리 영역 체크
    check_category(s_id, s_index);

  });

  // 카테고리 메뉴 클릭 했을 시 슬라이드 이동
  $("#swiper-category .swiper-slide").click(function() {
    var s_index = cateSlider.realIndex;
    var btn_id = $(this).attr('id');
    var slide_id = $("#"+btn_id+" #slide_id").val();

    $("#swiper-category .swiper-slide span").attr("style", "color: #ababab; border-bottom: 0; font-size: 13px;");
    $("#"+btn_id+" span").attr("style", "color: #000000; border-bottom: .2rem solid #2e2e2e; font-size: 13px;");

    // if(_outerWidth < 327) {
    //   $("#swiper-category .swiper-slide span").attr("style", "color: #ababab; border-bottom: 0; font-size: 12px;");
    //   $("#"+btn_id+" span").attr("style", "color: #000000; border-bottom: .2rem solid #2e2e2e; font-size: 12px;");
    // } else {
    //   $("#swiper-category .swiper-slide span").attr("style", "color: #ababab; border-bottom: 0; font-size: 14px;");
    //   $("#"+btn_id+" span").attr("style", "color: #000000; border-bottom: .2rem solid #2e2e2e; font-size: 14px;");
    // }

    mSlider.slideTo(slide_id);

  });


  //달력확인버튼 클릭시
  $('#cal_ok_btn').click(function() {

    $.ajax({
      url: "./search_date.ajax.php",
      type: "POST",
      // dataType:"json",
      async: false,
      data: { check_in:check_in, check_out:check_out, in_holiday:in_holiday, out_holiday:out_holiday},
      success:function(data) {
        location.href="./list.php?keyword="+keyword+"&type="+type;
      },
      error:function(error) {
         alert("오류");
      }
    });
  });


  //필터 적용하기 버튼 눌렸을시
  $('.filter_set_btn').click(function() {

    var s_index = $('#s_index').val();
    var s_id = $('#s_id').val();

    //필터 요소들 -----------------------
    var list_sort = $('#list_sort').val(); //정렬
    var grade_type_chk = []; //등급

    $("input[name=grade_type]:checked").each(function() {
      grade_type_chk.push($(this).val());
    });
    var bed_type_chk = []; //배드타입
    $("input[name=bed_type]:checked").each(function() {
      bed_type_chk.push($(this).val());
    });
    var acmdt_type = []; //숙박시설
    $("input[name=acmdt_type]:checked").each(function() {
      acmdt_type.push($(this).val());
    });
    var facilities_type = []; //객실시설
    $("input[name=facilities_type]:checked").each(function() {
      facilities_type.push($(this).val());
    });

    var price_type = $('input[name=price_type]:checked').val();

    var filter_chk = '';
    //필터 클릭이 하나라도 되어있으면
    if(grade_type_chk.length > 0 || bed_type_chk.length > 0 || acmdt_type.length > 0 || facilities_type.length > 0 || price_type) {
      filter_chk = 'Y';
    } else {
      filter_chk = 'N';
    }
    console.log(filter_chk);
    $.ajax({
      url:"./list_slide.ajax.php",
      type:"POST",
      dataType:"json",
      async: false,
      data: { s_id:s_id, filter_chk:filter_chk, check_in:check_in, check_out:check_out, total_num:total_num, type:type, grade_type_chk:grade_type_chk, bed_type_chk:bed_type_chk, acmdt_type:acmdt_type, facilities_type:facilities_type, price_type:price_type },
      success: function(data) {


        var interval = data.interval;
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
            var avg_score = data.data[i].avg_score;
            var cnt_score = data.data[i].cnt_score;
            var check_in_str = data.data[i].check_in_str;
            var location_desc = data.data[i].location_desc;
            var type = data.data[i].type;

            var mediumBox_tag = callMediumBox(type, mb_id, company_img, company_name, normal_price, discount, discount_price, avg_score, cnt_score, check_in_str, location_desc, interval);

            $(".medium_ul"+s_index).append(mediumBox_tag);
          }
        } //리스트 데이터가 없으면
          else if(data.code == '999') {
          $('.medium_list_section ul li').remove();

          var empty_tag = '<li class="list_none_box">'
            +'<div>'
            +'<span>상품이 존재하지 않습니다.</span>'
            +'</div>';
          $(".medium_ul"+s_index).append(empty_tag);
          console.log('oi');
        }
      },
      error:function(request, status, error) {
        console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
      }
    });

  });

</script>
