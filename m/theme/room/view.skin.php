<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<input type="hidden" name="latitude" id="latitude" value="<?php echo $room['latitude'];?>" />
<input type="hidden" name="longitude" id="longitude" value="<?php echo $room['longitude'];?>" />

<div class="view_container">

  <section class="img_slide_section">
    <div class="swiper" id="view-swiper">
      <div class="swiper-wrapper">
        <!-- <div class="swiper-slide" style="background-image: url('../img/hotel_img.jpg');"></div>
        <div class="swiper-slide" style="background-image: url('../img/hotel_img.jpg');"></div>
        <div class="swiper-slide" style="background-image: url('../img/hotel_img.jpg');"></div> -->
        <?php if(sql_num_rows($img_result) == 0) { ?>
          <div class="swiper-slide" style="background-image: url('../img/image_none.png')"></div>
        <?php } ?>
          <div class="swiper-slide" style="background-image: url('../../data/room/<?php echo $room['company_img'];?>')"></div>
        <?php for ($i=0; $row=sql_fetch_array($img_result); $i++) { ?>

          <?php if($row['simg1'] != ""){ ?>
              <div class="swiper-slide" style="background-image: url('../../data/goods/<?php echo $row['simg1'];?>')"></div>
          <? } ?>
          <?php if($row['simg2'] != ""){ ?>
              <div class="swiper-slide" style="background-image: url('../../data/goods/<?php echo $row['simg2'];?>')"></div>
          <? }?>
          <?php if($row['simg3'] != ""){ ?>
              <div class="swiper-slide" style="background-image: url('../../data/goods/<?php echo $row['simg3'];?>')"></div>
          <? }?>
          <?php if($row['simg4'] != ""){ ?>
              <div class="swiper-slide" style="background-image: url('../../data/goods/<?php echo $row['simg4'];?>')"></div>
          <? }?>
          <?php if($row['simg5'] != ""){ ?>
              <div class="swiper-slide" style="background-image: url('../../data/goods/<?php echo $row['simg5'];?>')"></div>
          <? }?>
          <?php if($row['simg6'] != ""){ ?>
              <div class="swiper-slide" style="background-image: url('../../data/goods/<?php echo $row['simg6'];?>')"></div>
          <? }?>

        <?php }?>
      </div>
      <div class="swiper-pagination view-pagination"></div>
    </div>

    <div class="view_nav auto_side">
      <button type="button" onclick="history.back();"><i class="fas fa-chevron-left"></i></button>
      <button type="button"><i class="far fa-share-square"></i></button>
    </div>
  </section>

  <section class="view_tit_section">
    <div class="view_tit_box shadow_box">
      <div class="auto_side">
        <h1><?php echo $room['company_name'] ?></h1>
        <div>
          <?php
            if($_GET['type'] == 'HT' || $_GET['type'] == 'MT' || $_GET['type'] == 'PS' || $_GET['type'] == 'RS') {
              $sql = " select * from hi_wish_list where mb_id ='{$member['id']}' and seller_id = '{$room_id}'";
            } else {
              $sql = " select * from hi_wish_list where mb_id ='{$member['id']}' and seller_id = '{$rent_id}'";
            }
            $zzim_result = sql_query($sql);

            $zzim_count = sql_num_rows($zzim_result);

          ?>
          <input type="hidden" id="zzim_count" name="zzim_count" value="<?php echo $zzim_count;?>" />
          <input type ="hidden" id="seller_id" name="seller_id" value="<?php echo $room_id;?>"/>
          <input type ="hidden" id="mb_id" name="mb_id" value="<?php echo $member['id'];?>"/>
          <input type ="hidden" id="cb_type" name=cb_type value="<?php echo $type;?>"/>
          <i class="fas fa-heart" id="zzim_active"></i>
          <i class="fas fa-heart" id="zzim_icon"></i>
        </div>
      </div>
      <span class="grade_box">
        <span class="star_icon">
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
        </span>
        <span class="grade_num"><?php echo $score; ?></span><span class="dvs">|</span><a href="<?php echo TB_MAPP_URL; ?>/review.php" class="review_btn">리뷰 3,000</a>
      </span>
      <div class="location_box auto_side">
        <?php if($room['short_addr']){ ?>
          <a href="#view_location"><span><i class="fas fa-map-marker-alt"></i><?php echo $room['short_addr'];?></span></a>
        <? } else { ?>
          <a href="#view_location"><span><i class="fas fa-map-marker-alt"></i>짧은 주소 설명이 없습니다.</span></a>
        <? } ?>
        <a href="tel:010-0000-0000"><span><i class="fas fa-phone-alt"></i>업소 문의하기</span></a>
      </div>
    </div>
  </section>

  <section class="date_select_section">
    <div class="date_select_box auto_side calendar_sel_btn" data-toggle="modal" data-target="#date_sel_modal">
      <div class="date auto_side">
        <div>
          <small>체크인 - 체크아웃</small>
          <h3 class="auto_side"><?php echo $str_in_date; ?> - <?php echo $str_out_date; ?><i class="fas fa-caret-down"></i></h3>
        </div>
        <div>
          <small>연박</small>
          <h3><?php echo $interval; ?>박</h3>
        </div>
      </div>
    </div>
  </section>


  <?php if (sql_num_rows($result) == 0) { ?>
  <section class="goods_list_section">
    <h1>객실 리스트</h1>
    <ul>
      <li class="list_none_box">
        <div>
        <span>예약 가능한 객실이 없습니다. 날짜를 변경 해주세요.</span>
        </div>
      </li>
    </ul>
  </section>
  <?php } else { ?>
  <section class="goods_list_section">
    <h1>객실 리스트</h1>
    <ul>
      <?php for ($i=0; $row=sql_fetch_array($result); $i++) {

        // 요일별 체크인, 체크아웃 변수 값 자르기
        if($in_yoil == '월') { $mon = json_decode($room['use_mon'], true); $check_in_str = $mon["data"][0]["in_time"]; $check_out_str = $mon["data"][0]["out_time"]; }
        if($in_yoil == '화') { $tue = json_decode($room['use_tue'], true); $check_in_str = $tue["data"][0]["in_time"]; $check_out_str = $tue["data"][0]["out_time"]; }
        if($in_yoil == '수') { $wed = json_decode($room['use_wed'], true); $check_in_str = $wed["data"][0]["in_time"]; $check_out_str = $wed["data"][0]["out_time"]; }
        if($in_yoil == '목') { $thu = json_decode($room['use_thu'], true); $check_in_str = $thu["data"][0]["in_time"]; $check_out_str = $thu["data"][0]["out_time"]; }
        if($in_yoil == '금') { $fri = json_decode($room['use_fri'], true); $check_in_str = $fri["data"][0]["in_time"]; $check_out_str = $fri["data"][0]["out_time"]; }
        if($in_yoil == '토') { $sat = json_decode($room['use_sat'], true); $check_in_str = $sat["data"][0]["in_time"]; $check_out_str = $sat["data"][0]["out_time"]; }
        if($in_yoil == '일') { $sun = json_decode($room['use_sun'], true); $check_in_str = $sun["data"][0]["in_time"]; $check_out_str = $sun["data"][0]["out_time"]; }
        if($in_holiday == 'Y') { $hol = json_decode($room['use_hol'], true); $check_in_str = $hol["data"][0]["in_time"]; $check_out_str = $sun["data"][0]["out_time"]; }

        //상품가격 날짜별로 평균값 계산하기
        $sql = " select g.gname, g.index_no AS gs_index, g.mb_id AS g_mb_id, g.normal_price AS normal_price, min(s.gs_tot_qty - s.gs_use_qty) as room_gty,

                  avg(case
                    when s.special_percent = 0 and s.off_percent = 0
                    then 0
                    when s.special_percent = 0 AND s.off_percent > 0
                    then s.off_percent
                    ELSE s.special_percent
                  END) AS final_percent,

                  round(avg(case
                      when s.special_price = 0 AND s.gs_price = 0
                      then g.normal_price
                      when s.special_price = 0 AND s.gs_price > 0
                      then s.gs_price
                      ELSE s.special_price
                   END),-2)  AS final_price

                FROM hi_goods AS g
                LEFT JOIN hi_sales AS s ON s.gs_id = g.index_no
                where s.gs_id = '".$row['gs_index']."' and g.mb_id = '".$room_id."' and s.use_yn = '판매중' and (s.sales_date >= '".$check_in."' and s.sales_date < '".$check_out."') and g.goods_ca = 'R' and g.max_pplNum >= '".$total_num."'
                having ABS(DATEDIFF('".$check_in."', '".$check_out."')) = COUNT(*) ";

        $row_sales = sql_fetch($sql);

        $discount = number_format($row_sales['final_percent']);
        $discount_price = number_format($row_sales['final_price']);

        //남은객실 수 구하기
        $remain_room = $row_sales['room_gty'];

        //편의시설 불러오기
        $sql = " select fac_name from hi_room_facilities
                  where mb_id = '{$room_id}'
                  order by fac_gr_cd, fac_ma_seq";
        $room_fac = sql_query($sql);

        //다음페이지에 get정보 넘기기
        $room_info_str = "gcode=".$row['gcode']."&gs_id=".$row['gs_index']."&room_id=".$room_id."&type=".$type;

      ?>
      <li class="auto_side shadow_box" onclick="location.href='<?php echo TB_MROOM_URL; ?>/detail_view.php?<?PHP echo $room_info_str;?>';">

        <div class="img_div">
          <?php if(!$row['simg1']) { ?>
            <div class="img_box" style="background-image: url('../img/image_none.png');"></div>
          <?php } else { ?>
            <div class="img_box" style="background-image: url('/data/goods/<?php echo $row['simg1']; ?>');"></div>
          <?php } ?>
        </div>
        <div class="info_box">
          <div class="info_top">
            <h3><?php echo $row['gname'];?></h3>
            <small>기준 <?php echo $row['standard_pplNum'];?>명 / 최대 <?php echo $row['max_pplNum'];?>명</small>
          </div>

          <?php if ($remain_room == 0) { ?>
            <div class="rif_close">
              <span class="rv_close">예약마감</span>
            </div>

          <?php } else { ?>
            <div class="info_bot auto_side">
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
                  <h3><span class="night1">1박 기준가</span><span class="price_bold"><?php echo $discount_price;?></span>원</h3>
                  <div class="remain">남은 객실 <?php echo $remain_room ?>개</div>

                <!-- 할인가격이 있을때 -->
                <?php } else { ?>
                  <span class="pers"><?php echo $discount;?>% </span><del><?php echo number_format($row_sales['normal_price']);?>원</del>
                  <h3><span class="night1">1박 기준가</span><span class="price_bold"><?php echo $discount_price;?></span>원</h3>
                  <div class="remain">남은 객실 <?php echo $remain_room; ?>개</div>
                <?php } ?>

              <!-- 1박이용시일때 -->
              <?php } else { ?>

                <!-- 할인가격이 없을때 -->
                <?php if($discount == '0') { ?>
                  <span class="pers"> </span><del> </del>
                  <h3><span class="night1"></span><span class="price_bold"><?php echo $discount_price;?></span>원</h3>
                  <div class="remain">남은 객실 <?php echo $remain_room ?>개</div>

                <!-- 할인가격이 있을때 -->
                <?php } else { ?>
                  <span class="pers"><?php echo $discount;?>% </span><del><?php echo number_format($row_sales['normal_price']);?>원</del>
                  <h3><span class="night1"></span><span class="price_bold"><?php echo $discount_price;?></span>원</h3>
                  <div class="remain">남은 객실 <?php echo $remain_room; ?>개</div>
                <?php } ?>

              <?php } ?>
              </div>
            </div>
          <?php } ?>

        </div>
      </li>
      <?php } ?>
    </ul>
  </section>
<?php } ?>

  <section class="goods_info_section">
    <div class="info_box">
      <h2>숙소소개</h2>
      <ul>
        <?php if(!$room['basic_info']) { ?>
          <li>
            <textarea id="textarea1" disabled>준비중입니다.</textarea>
          </li>
        <?php } else { ?>
          <li>
            <textarea id="textarea1" disabled><?php echo $room['basic_info']; ?></textarea>
          </li>
        <?php } ?>
      </ul>
    </div>

    <div class="info_box">
      <h2>편의시설 및 서비스</h2>
      <ul>
        <?php if(sql_num_rows($room_fac) == 0) { ?>
          <li>
            준비중입니다.
          </li>
        <?php } else { ?>
          <li>
            <?php for ($i=0; $row = sql_fetch_array($room_fac); $i++) { ?>
              <span class="room_fac"><?php echo $row['fac_name']; ?></span>
            <?php } ?>
          </li>
        <?php } ?>
      </ul>
    </div>
      <?php if(!$room['parking_info']) { } else { ?>
        <div class="info_box">
          <h2>주차장 정보</h2>
          <ul>
            <li>
              <?php echo $room['parking_info']; ?>
            </li>
          </ul>
        </div>
      <?php } ?>
    <div id="view_location" class="info_box">
      <h2>찾아오시는 길</h2>
      <div id="map" style="width:100%;height:150px;"></div>
      <div class="address auto_side">
        <div>
          <i class="fas fa-map-marker-alt"></i>
          <span id="company_address"><?php echo $room['company_addr1'];?></span>
        </div>
        <input type="button" onclick="location_copy()" value="주소 복사"/>
      </div>
      <ul>
        <li>
          <span><?php echo $room['location_desc'];?></span>
        </li>
      </ul>
    </div>
  </section>

  <section class="review_section">
    <div class="tit auto_side">
      <h1>리뷰 <i class="fas fa-star"></i> 4.7 <span>| 3,000건</span></h1>
      <button type="button" onclick="location.href='<?php echo TB_MAPP_URL; ?>/review.php';">전체보기</button>
    </div>
    <ul>
      <?php for($l=0; $l<4; $l++) { ?>
      <li class="review_box">
        <div class="subj_box auto_side">
          <div>
            <h3>fso**** | 프티 트윈</h3>
            <div class="review_star">
              <?php for($i=1;$i<=4;$i++) { ?>
              <i class="fas fa-star"></i>
              <?php } ?>
              <?php for($y=1;$y<=(5-4);$y++) { ?>
              <i class="far fa-star"></i>
              <?php } ?>
            </div>
          </div>
          <div>
            <h4>2021. 07. 17</h4>
          </div>
        </div>
        <div class="content_box" id="content_box<?php echo $l; ?>">
           <p class="content">실내가 너무 깨끗하고 사장님도 친절하시고 다음에 또 이용할게요~!!실내가 너무 깨끗하고 사장님도 친절하시고 다음에 또 이용할게요~!!실내가 너무 깨끗하고 사장님도 친절하시고 다음에 또 이용할게요~!!실내가 너무 깨끗하고 사장님도 친절하시고 다음에 또 이용할게요~!!실내가 너무 깨끗하고 사장님도 친절하시고 다음에 또 이용할게요~!!실내가 너무 깨끗하고 사장님도 친절하시고 다음에 또 이용할게요~!!실내가 너무 깨끗하고 사장님도 친절하시고 다음에 또 이용할게요~!!</p>
           <div class="more_div">
             <button type="button" class="more_show" id="more_show<?php echo $l; ?>">더보기</button>
             <button type="button" class="more_hide" id="more_hide<?php echo $l; ?>">숨기기</button>
           </div>
        </div>
        <div class="answer_box" id="answer_box<?php echo $l; ?>">
          <div class="auto_side">
            <h3>숙소답변</h3>
            <h4>2021.07.17</h4>
          </div>
          <p class="content">
            너무 감사합니다.<br />또 이용해주세요.<br />너무 감사합니다.<br />또 이용해주세요.<br />너무 감사합니다.<br />또 이용해주세요.<br />
          </p>
          <div class="more_div">
            <button type="button" class="more_show" id="more_show<?php echo $l; ?>">더보기</button>
            <button type="button" class="more_hide" id="more_hide<?php echo $l; ?>">숨기기</button>
          </div>
        </div>
      </li>
      <script>
        $(document).ready(function() {
          var content_h = (document.querySelector('#content_box<?php echo $l; ?> .content').offsetHeight);
          var answer_h = (document.querySelector('#answer_box<?php echo $l; ?> .content').offsetHeight);
          if(content_h > 71) {
            $("#content_box<?php echo $l; ?> .content").css("-webkit-line-clamp","4");
            $("#content_box<?php echo $l; ?> .more_div").css("display","block");
            $(".content_box #more_show<?php echo $l; ?>").click(function() {
              $("#content_box<?php echo $l; ?> .content").css("-webkit-line-clamp","unset");
              $(".content_box #more_hide<?php echo $l; ?>").show();
              $(".content_box #more_show<?php echo $l; ?>").hide();
            });
            $(".content_box #more_hide<?php echo $l; ?>").click(function() {
              $("#content_box<?php echo $l; ?> .content").css("-webkit-line-clamp","4");
              $(".content_box #more_hide<?php echo $l; ?>").hide();
              $(".content_box #more_show<?php echo $l; ?>").show();
            });
          } else {
            $("#content_box<?php echo $l; ?> .more_div").css("display","none");
          }

          if(answer_h > 71) {
            $("#answer_box<?php echo $l; ?> .content").css("-webkit-line-clamp","4");
            $("#answer_box<?php echo $l; ?> .more_div").css("display","block");
            $(".answer_box #more_show<?php echo $l; ?>").click(function() {
              $("#answer_box<?php echo $l; ?> .content").css("-webkit-line-clamp","unset");
              $(".answer_box #more_hide<?php echo $l; ?>").show();
              $(".answer_box #more_show<?php echo $l; ?>").hide();
            });
            $(".answer_box #more_hide<?php echo $l; ?>").click(function() {
              $("#answer_box<?php echo $l; ?> .content").css("-webkit-line-clamp","4");
              $(".answer_box #more_hide<?php echo $l; ?>").hide();
              $(".answer_box #more_show<?php echo $l; ?>").show();
            });
          } else {
            $("#content_box<?php echo $l; ?> .more_div").css("display","none");
          }
        });
      </script>
      <?php } ?>
    </ul>
  </section>
</div>




<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=17aab66598b882a5494494233fef7048"></script>
<script>

//뒤로가기 새로고침 활성화
window.onpageshow = function(event) {
  if ( event.persisted || (window.performance && window.performance.navigation.type == 2)) {

    window.location.reload(true);
  } else {

  }
}

  //위치좌표 불러오기
  var latitude = $("#latitude").val();
  var longitude = $("#longitude").val();
  // KAKAO MAP API
  var mapContainer = document.getElementById('map'), // 지도를 표시할 div
      mapOption = {
          center: new kakao.maps.LatLng(longitude, latitude), // 지도의 중심좌표
          level: 5 // 지도의 확대 레벨
      };

  var map = new kakao.maps.Map(mapContainer, mapOption); // 지도를 생성합니다

  // 마커가 표시될 위치입니다
  var markerPosition  = new kakao.maps.LatLng(longitude, latitude);

  // 마커를 생성합니다
  var marker = new kakao.maps.Marker({
      position: markerPosition
  });

  // 마커가 지도 위에 표시되도록 설정합니다
  marker.setMap(map);

  // 아래 코드는 지도 위의 마커를 제거하는 코드입니다
  // marker.setMap(null);


  $(document).ready(function() {

    var view_img_w = (document.querySelector('.img_slide_section').offsetWidth);

    $(".img_slide_section #view-swiper .swiper-slide").css("height", view_img_w+"px");
    // if(view_img_w < 360 ){
    //   $(".goods_list_section ul li .info_box .lif h3").css("font-size","16px");
    //   $(".goods_list_section ul li .info_box .lif small").css("font-size","11px");
    //   $(".goods_list_section ul li .info_box .lif h4").css("font-size","10px");
    //   $(".goods_list_section ul li .info_box .rif span").css("font-size","10px");
    //   $(".goods_list_section ul li .info_box .rif del").css("font-size","10px");
    //   $(".goods_list_section ul li .info_box .rif h3 .night1").css("font-size","10px");
    //   $(".goods_list_section ul li .info_box .rif h3 .price_bold").css("font-size","16px");
    //   $(".goods_list_section ul li .info_box .rif h3").css("font-size","14px");
    //   $(".goods_list_section ul li .info_box .rif").css("margin-top","-16px");
    //   $(".goods_list_section ul li .info_box .rif div").css("margin", "5px 0 0 70px").css("font-size", "11px").css("padding", "1px 1px");
    // }

    var zzim_count = $("#zzim_count").val();

    if (zzim_count > 0) {
      $("#zzim_icon").hide();
      $("#zzim_active").show();
    } else {
      $("#zzim_icon").show();
      $("#zzim_active").hide();
    }

    //로그인 확인 (찜하기 통신에러뜹니다. 확인부탁드려요!)
    // var seller_id = $("#seller_id").val();
    // var mb_id = $("#mb_id").val();
    // var cb_type = $("#cb_type").val();
    // if (mb_id == "") {
    //   return;
    // }else{
    //   $.ajax({
    //       type : "POST",
    //       url : "./ajax.lately_item_update.php",
    //       async : false,
    //       data : {seller_id:seller_id,mb_id:mb_id,cb_type:cb_type},
    //       //contentType: "application/json; charset=utf-8",
    //       //dataType : "json",
    //       cache : false,
    //       success : function(data){
    //
    //           console.log(data);
    //
    //       },
    //       error : function(data){
    //         console.log(data);
    //         alert("통신에러");
    //       }
    //   });
    // }

  });

  function adjustHeight() {
    var textEle = $('#textarea1');
    textEle[0].style.height = 'auto';
    var textEleHeight = textEle.prop('scrollHeight');
    textEle.css('height', textEleHeight).css('border', '0').css('resize', 'none');
  };

  adjustHeight();

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
  swiper.update();

  // 주소 클립보드에 저장하기
  function location_copy(){

  var location = document.getElementById("company_address");
  var range = document.createRange();
  range.selectNode(location);
  window.getSelection().addRange(range);

  try {

    var successful = document.execCommand('copy', false, null);
    var msg = successful ? 'successful' : 'unsuccessful';

    if(true){
        $().addClass("copied").html("Copied");
    }
    alert('주소가 복사되었습니다.')
  } catch(err) {
    alert('이 브라우저는 지원하지 않습니다.')
  }

  window.getSelection().removeAllRanges();
}

//달력확인버튼 클릭시
$('#cal_ok_btn').click(function() {

  var check_in = $("#check_in").val();
  var check_out = $("#check_out").val();
  var total_num = $('#total_num').val();
  var type = $("#type").val();
  var room_id = $("#room_id").val();
  console.log(type);
  //휴일체크
  var in_holiday = $("#str_in_holiday").val();
  var out_holiday = $("#str_out_holiday").val();


  $.ajax({
    url: "<?php echo TB_MROOM_URL; ?>/view_date.ajax.php",
    type: "POST",
    dataType:"json",
    async: false,
    data: { check_in:check_in, check_out:check_out, in_holiday:in_holiday, out_holiday:out_holiday, total_num:total_num,type:type, room_id:room_id },
    success:function(data) {

      console.log(data);
      var str_in_date = data.str_in_date; // 체크인날짜
      var str_out_date = data.str_out_date; // 체크아웃날짜
      var interval = data.interval; // 숙박기간


      var date_info = '<div class="date_select_box auto_side calendar_sel_btn" data-toggle="modal" data-target="#date_sel_modal">'+
                        '<div class="date auto_side">'+
                          '<div>'+
                            '<small>체크인 - 체크아웃</small>'+
                            '<h3 class="shadow_box">'+str_in_date+' - '+str_out_date+'<i class="fas fa-caret-down"></i></h3>'+
                          '</div>'+
                          '<div>'+
                            '<small>연박</small>'+
                            '<h3 class="shadow_box">'+interval+'박</h3>'+
                          '</div>'+
                        '</div>'+
                      '</div>';

      $('.date_select_section').html(date_info);


      //리스트 데이터가 없으면
      if(data.code == '999') {

        $('.goods_list_section ul li').remove();

        var empty_tag = '<li class="list_none_box">'+
                          '<div>'+
                          '<span>예약 가능한 객실이 없습니다. 날짜를 변경 해주세요.</span>'+
                          '</div>'+
                        '</li>';

        $(".goods_list_section ul").append(empty_tag);
      }
      //리스트 데이터가 있으면
      else if(data.code == '200') {

        $('.goods_list_section ul li').remove();

        for(var i=0; i<data.data.length; i++) {

          var room_info_str = data.data[i].room_info_str;
          var day_interval = data.data[i].day_interval;
          var goods_img = data.data[i].goods_img;
          var gname = data.data[i].gname;
          var std_capa = data.data[i].std_capa;
          var max_capa = data.data[i].max_capa;
          var check_in_str = data.data[i].check_in_str;
          var discount = data.data[i].discount;
          var nor_price = data.data[i].nor_price;
          var discount_price = data.data[i].discount_price;
          var remain_room = data.data[i].remain_room;

          //객실상태값에 따라 다르게 출력
          var room_state = '';
          //남은 객실 수가 없을때
          if(remain_room == 0) {
            room_state = '<div class="rif_close"><span class="rv_close">예약마감</span></div>';
          } else {
            //연박으로 검색할때
            if (day_interval >= 2) {
              //할인가격이 없을때
              if (discount == '0') {
                room_state = '<div class="info_right">'+
                            '<span class="pers"> </span><del> </del>'+
                            '<h3><span class="night1">1박 기준가</span><span class="price_bold">'+nor_price+'</span>원</h3>'+
                            '<div class="remain">남은 객실 '+remain_room+'개</div>'+
                            '</div>';
              //할인가격이 있을때
              } else {
                room_state = '<div class="info_right">'+
                            '<span class="pers">'+discount+'% </span><del>'+nor_price+'</del>'+
                            '<h3><span class="night1">1박 기준가</span><span class="price_bold">'+discount_price+'</span>원</h3>'+
                            '<div class="remain">남은 객실 '+remain_room+'개</div>'+
                            '</div>';
              }
            //1박으로 검색할때
            } else {
              //할인가격이 없을때
              if (discount == '0') {
                room_state = '<div class="info_right">'+
                            '<span class="pers"> </span><del> </del>'+
                            '<h3><span class="night1"></span><span class="price_bold">'+nor_price+'</span>원</h3>'+
                            '<div>남은 객실 '+remain_room+'개</div>'+
                            '</div>';
              //할인가격이 있을때
              } else {
                room_state = '<div class="info_right">'+
                            '<span class="pers">'+discount+'% </span><del>'+nor_price+'</del>'+
                            '<h3><span class="night1"></span><span class="price_bold">'+discount_price+'</span>원</h3>'+
                            '<div class="remain">남은 객실 '+remain_room+'개</div>'+
                            '</div>';
              }
            }
          }

          //이미지파일이 없을때
          if (!goods_img) {
            var goods_img_div = '<div class="img_box" style="background-image: url(\'../img/image_none.png\');"></div>';
          } else {
            var goods_img_div = '<div class="img_box" style="background-image: url(\'\/data\/goods\/'+goods_img+'\');"></div>';
          }


          var room_info = '<li class="auto_side shadow_box" onclick="location.href=\'<?php echo TB_MROOM_URL; ?>/detail_view.php?'+room_info_str+'\';">'+
                            '<div class="img_div">'+goods_img_div+'</div>'+
                            '<div class="info_box">'+
                              '<div class="info_top">'+
                                '<h3>'+gname+'</h3>'+
                                '<small>기준 '+std_capa+'명 / 최대 '+max_capa+'명</small>'+
                              '</div>'+
                              '<div class="info_bot auto_side">'+
                                '<div class="info_left">'+
                                  '<small>숙박</small>'+
                                  '<h4>'+check_in_str+' 부터</h4>'+
                                '</div>'+
                              room_state+
                              '</div>'+
                            '</div>'+
                          '</li>';

          $('.goods_list_section ul').append(room_info);
        }
      }

    },
    error:function(error) {
       alert("오류");
    }
  });

});

  $("#zzim_icon").click(function() {
  $("#zzim_icon").hide();
  $("#zzim_active").show();

  var seller_id = $("#seller_id").val();
  var mb_id = $("#mb_id").val();
  var w = "";
  var cb_type = $("#cb_type").val();
  if (mb_id == "") {
    alert("회원가입후 이용가능 합니다.");
    return;
  }
console.log(cb_type);
      $.ajax({
          type : "POST",
          url : "./ajax.wish_update.php",
          async : false,
          data : {seller_id:seller_id,mb_id:mb_id, w:w, cb_type:cb_type},
          //contentType: "application/json; charset=utf-8",
          //dataType : "json",
          cache : false,
          success : function(data){



          },
          error : function(data){

            alert("통신에러");
          }
      });

  });

  $("#zzim_active").click(function() {
  $("#zzim_icon").show();
  $("#zzim_active").hide();
  var seller_id = $("#seller_id").val();
  var mb_id = $("#mb_id").val();
  var w = "";
  var cb_type = $("#cb_type").val();
  if (mb_id == "") {
    alert("회원가입후 이용가능 합니다.");
    return;
  }
      $.ajax({
          type : "POST",
          url : "./ajax.wish_update.php",
          async : false,
          data : {seller_id:seller_id,mb_id:mb_id, w:'d', cb_type:cb_type},
          //contentType: "application/json; charset=utf-8",
          //dataType : "json",
          cache : false,
          success : function(data){


          },
          error : function(data){

            alert("통신에러");
          }
      });
  });



</script>
