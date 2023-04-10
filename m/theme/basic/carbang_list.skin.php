<!-- Modal Popup Script -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<!-- Modal Popup Script -->
<?php


//지역 카테고리
$catecode = $_GET['catecode'];
$sql = "select catename from hi_goods_category where catecode = '$catecode'";
$result_catename = sql_fetch($sql);
$catename = $result_catename['catename'];

//체크인 월계산
$in_month = substr($in_date, 5, 2);
if ($in_month < 10) {
  $in_month = str_replace('0','',$in_month);

}
//체크아웃 월계산
$out_month = substr($out_date, 5, 2);
if ($out_month < 10) {
  $out_month = str_replace('0','',$out_month);
}

$str_in_date = $in_month.". ".substr($in_date, 8, 2);
$str_out_date = $out_month.". ".substr($out_date, 8, 2);


if ($in_date == TB_TIME_YMD && $interval == 1) {
  $interval = "오늘";
}else{
  $interval = $interval."박";
}

?>

<div class="carbang_list_container">
  <table class="top_tit_side_3">
    <td>

    </td>
    <td>
      <?php if($_GET['cb_type'] == 'CT') {
        $cb_type_nm = 'room';
        echo '카텔';
      } else if ($_GET['cb_type'] == 'HT') {
        $cb_type_nm = 'room';
        echo '호텔';
      } else if ($_GET['cb_type'] == 'MT') {
        $cb_type_nm = 'room';
        echo '모텔';
      } else if ($_GET['cb_type'] == 'PS') {
        $cb_type_nm = 'room';
        echo '펜션/풀빌라';
      } else if ($_GET['cb_type'] == 'RT') {
        $cb_type_nm = 'rent';
        echo '렌트카';
      } else if ($_GET['cb_type'] == 'KP') {
        $cb_type_nm = 'rent';
        echo '캠핑카';
      } else if ($_GET['cb_type'] == 'EX') {
        $cb_type_nm = 'exp';
        echo '체험';
      } else if ($_GET['cb_type'] == 'EV') {
        echo '이벤트';
      }
      ?>
    </td>
    <td>
      <div class="right_btn">
        <!-- 지도 버튼 -->
        <?php if($catecode && !$upcate) { ?>
          <span onclick="location.href='<?php echo TB_MSHOP_URL; ?>/carbang_map.php?cb_type=<?php echo $cb_type; ?>&catecode=<?php echo $catecode; ?>';">
            <img src="../img/map_icon.png" />
          </span>
        <?php } else if($catecode && $upcate) { ?>
          <span onclick="location.href='<?php echo TB_MSHOP_URL; ?>/carbang_map.php?cb_type=<?php echo $cb_type; ?>&catecode=<?php echo $catecode; ?>&upcate=<?php echo $upcate; ?>';">
            <img src="../img/map_icon.png" />
          </span>
        <?php } else { ?>
          <span onclick="location.href='<?php echo TB_MSHOP_URL; ?>/carbang_map.php?cb_type=<?php echo $cb_type; ?>&catecode=001';">
            <img src="../img/map_icon.png" />
          </span>
        <?php } ?>
      </div>
    </td>
  </table>

  <div class="cb_dateset">
    <button type="button" onclick="location.href='<?php echo TB_MSHOP_URL; ?>/cb_region_select.php?cb_type=<?php echo $cb_type;?>';"><img src="../img/location_icon.png" /><?php if($catecode == null || $catecode == '') {?>서울<?php } else {?><?php echo $catename; }?></button>
    <button type="button" onclick="location.href='<?php echo TB_MSHOP_URL; ?>/reservation_calendar.php?page=main&cb_type=<?php echo $cb_type;?>';"><img src="../img/calendar_icon.png" /><?php echo $str_in_date." - ".$str_out_date;?> (<?php echo $interval;?>)</button>
  </div>

  <div class="category_menu">
    <button type="button" onclick="location.href='./carbang_list.php?cb_type=CT&catecode=<?php echo $catecode;?>';">
      <img src="../img/cartel_icon.png"/>
      <p>카텔</p>
    </button>
    <button type="button" onclick="location.href='./carbang_list.php?cb_type=HT&catecode=<?php echo $catecode;?>';">
      <img src="../img/hotel_icon.png" />
      <p>호텔</p>
    </button>
    <button type="button" onclick="location.href='./carbang_list.php?cb_type=MT&catecode=<?php echo $catecode;?>';">
      <img src="../img/motel_icon.png" />
      <p>모텔</p>
    </button>
    <button type="button" onclick="location.href='./carbang_list.php?cb_type=PS&catecode=<?php echo $catecode;?>';">
      <img src="../img/pension_icon.png" />
      <p>펜션/풀빌라</p>
    </button>
  </div>
  <div class="category_menu">
    <button type="button" onclick="location.href='./carbang_list.php?cb_type=RT&catecode=<?php echo $catecode;?>';">
      <img src="../img/rent_icon.png" />
      <p>렌트카</p>
    </button>
    <button type="button" onclick="location.href='./carbang_list.php?cb_type=KP&catecode=<?php echo $catecode;?>';">
      <img src="../img/camping_icon.png" />
      <p>캠핑카</p>
    </button>
    <button type="button" onclick="location.href='./carbang_list.php?cb_type=EX&catecode=<?php echo $catecode;?>';">
      <img src="../img/experience_icon.png" />
      <p>체험</p>
    </button>
    <button type="button" onclick="location.href='./carbang_list.php?cb_type=EV&catecode=<?php echo $catecode;?>';">
      <img src="../img/event_icon.png" />
      <p>이벤트</p>
    </button>
  </div>

  <?php if($slider7 = mobile_slider(7, $pt_id)) { ?>
  <!-- 메인배너 시작 { -->
  <!-- Swiper -->
  <div class="swiper-container swiper_banner">
    <div class="swiper-wrapper">
    </div>
    <div class="swiper-pagination"></div>

  </div>

  <!-- Initialize Swiper -->
  <script>
    var swiper = new Swiper('.swiper-container', {
      loop: true,
      pagination: {
        el: '.swiper-pagination',
        type: "fraction",
      },
      autoplay : {  // 자동 슬라이드 설정 , 비 활성화 시 false
        delay : 4000,   // 시간 설정
        disableOnInteraction : false,  // false로 설정하면 스와이프 후 자동 재생이 비활성화 되지 않음
      },
    });

    var test = '<?php echo $slider7; ?>';
    var img = new Array();
    img = test.split(",");
    // $(document).ready(function() {
    //   alert(img[1]);
    // });

    for(let i=0;i < img.length-1; i++) {
      swiper.appendSlide(
        '<div class="swiper-slide" style="width: 100%; height: 130px;">'
        + '<img src="'+img[i]+'" />'
        + '</div>'
      );
    }
    swiper.update();
  </script>
  <?php } ?>

  <div class="list_tit">
    <h3>인기 추천</h3>
  </div>

  <button type="button" class="filter_btn" data-toggle="modal" data-target="<?php if($cb_type == 'RT') { echo '#Modal3'; } else { echo '#Modal'; } ?>"><img src="../img/filter_icon.png" /></button>

  <ul class="list_ul">
    <?php
      //불러온 list가 없으면
      if(sql_num_rows($result) == 0){
    ?>
      <li>
        <table style="width:100%">
          <tr>
            <td>
              <span class="none_goods">해당 지역의 상품이 존재하지 않습니다.</span>
            </td>
          </tr>
        </table>
      </li>
    <?php
      }
      else {
        for($i=0;$row=sql_fetch_array($result);$i++){
          $sql = " select min(normal_price) AS normal_price, min(goods_price) AS goods_price FROM hi_goods where mb_id = '{$row['mb_id']}'";
          $price_row = sql_fetch($sql);

          $str_com_addr = explode( ' ', $row['company_addr1'] );
    ?>

      <li onclick="location.href='<?php echo TB_MSHOP_URL; ?>/cb_view.php?room_id=<?php echo $row['mb_id']?>&in_date=<?php echo $in_date;?>&out_date=<?php echo $out_date;?>&cb_type=<?php echo $cb_type;?>';">
        <table style="width:100%;">
          <tr>
            <td>
              <?php if($row['company_img']) { ?>
                <div class="company_img" style="background-image: url('<?php echo "../../data/".$cb_type_nm."/".$row['company_img']; ?>');"></div>
              <!-- <img src="<?php echo "../../data/".$cb_type_nm."/".$row['company_img']; ?>" width="100%"> -->
              <?php } else { ?>
                <div class="company_img" style="background-image: url('../img/image_none.png');"></div>
              <!-- <img src="../img/image_none.png" width="100%"/> -->
              <?php } ?>
            </td>
          </tr>
          <tr>
            <td class="td_content">
              <div><?php echo $row['room_grade'];?></div>
              <p class="hotel_name"><?php echo($row['company_name']);?></p>
              <p class="hotel_location"><?php echo $str_com_addr[0]." | ".$str_com_addr[1];?> </p>
              <span class="hotel_sale_price"><?php echo display_price($price_row['goods_price']); ?></span><del class="hotel_ori_price"><?php echo display_price($price_row['normal_price']);?></del>
            </td>
          </tr>
        </table>
      </li>
    <?php } }?>
  </ul>
</div>

<!-- Modal -->
<div class="full-screen-modal fx_none_modal">
  <div class="modal fade modal" id="Modal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-body">
          <table class="top_tit_side_3">
            <td>
              <span id="modalclose" data-dismiss="modal"><img src="../img/prev.png" /></span>
            </td>
            <td>
              정렬 및 필터
            </td>
            <td>
              <button type="button" class="reset_btn" id="reset_btn">초기화</button>
            </td>
          </table>
          <div class="filter_popup">
            <div class="filter_tit">정렬</div>
            <button type="button" class="sort_btn" data-toggle="modal" data-target="#Modal2">인기순</button>
            <div class="filter_tit">투숙인원</div>
            <div class="personnel_div">
              <span class="pers_text">2명</span><button type="button" id="ps_minus">-</button><button type="button" id="ps_plus">+</button>
              <input type="hidden" class="personnel" value="2">
            </div>

            <div class="filter_tit">등급</div>
            <div class="float-left">
              <ul>
                <li>
                  <label for="특급호텔" class="특급호텔">특급호텔</label>
                  <input type="checkbox" name="grade" id="특급호텔"/>
                </li>
                <li>
                  <label for="일반호텔" class="일반호텔">일반호텔</label>
                  <input type="checkbox" name="grade" id="일반호텔"/>
                </li>
              </ul>
            </div>

            <div class="filter_tit">배드타입</div>
            <div class="float-left">
              <ul>
                <li>
                  <label for="싱글" class="싱글">싱글</label>
                  <input type="checkbox" name="bed_type" id="싱글"/>
                </li>
                <li>
                  <label for="더블" class="더블">더블</label>
                  <input type="checkbox" name="bed_type" id="더블"/>
                </li>
                <li>
                  <label for="트윈" class="트윈">트윈</label>
                  <input type="checkbox" name="bed_type" id="트윈"/>
                </li>
              </ul>
            </div>

            <div class="filter_tit">부대시설</div>
            <div class="float-left">
              <ul>
                <li>
                  <label for="수영장" class="수영장">수영장</label>
                  <input type="checkbox" name="facility" id="수영장"/>
                </li>
                <li>
                  <label for="사우나" class="사우나">사우나</label>
                  <input type="checkbox" name="facility" id="사우나"/>
                </li>
                <li>
                  <label for="주차장" class="주차장">주차장</label>
                  <input type="checkbox" name="facility" id="주차장"/>
                </li>
                <li>
                  <label for="장애인시설" class="장애인시설">장애인시설</label>
                  <input type="checkbox" name="facility" id="장애인시설"/>
                </li>
              </ul>
            </div>

            <div class="filter_tit">가격정보</div>
            <div class="auto_side select_price">
              <div><input type="number" class="text_sm" id="sp1" value="0" maxlength="2" oninput="maxLengthCheck(this)" numberOnly /><span class="sp_span">만원</span></div><span class="wave"> ~ </span><div><input type="number" class="text_sm" id="sp2" value="100" maxlength="3" oninput="maxLengthCheck(this)" numberOnly /><span class="sp_span">만원</span></div>
            </div>

            <!-- <div class="track">
              <span class="range_price"></span><span class="range_price2"></span>
              <input type="range" id="range1" class="range-left" min="0" max="100" value="0"/>
              <input type="range" id="range2" class="range-right" min="0" max="100" value="100"/>
              <div class="range"></div>
              <div class="thumb left"></div>
              <div class="thumb right"></div>
            </div>
            <div class="auto_side">
              <span>0원</span><span>100만원</span>
            </div> -->

            <div class="bottom_fixed_btn">
              <button type="button" id="filter_set_btn">적용</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal end -->

<!-- Modal2 -->
<div class="half-screen-modal fx_none_modal2">
  <div class="modal modal" id="Modal2" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-body">
          <div class="pop_sort_div">
            <input type="hidden" id="modal2close" data-dismiss="modal" />
            <h2>정렬</h2>
            <div>
              <input type="radio" name="list_sort" value="best" id="best" checked><label for="best">인기순</label>
            </div>
            <div>
              <input type="radio" name="list_sort" value="high_price" id="high_price"><label for="high_price">높은 가격순</label>
            </div>
            <div>
              <input type="radio" name="list_sort" value="row_price" id="row_price"><label for="row_price">낮은 가격순</label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal2 end -->


<!-- Modal -->
<div class="full-screen-modal fx_none_modal">
  <div class="modal fade modal" id="Modal3" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-body">
          <table class="top_tit_side_3">
            <td>
              <span id="modal3close" data-dismiss="modal"><img src="../img/prev.png" /></span>
            </td>
            <td>
              필터
            </td>
            <td>
              <button type="button" class="reset_btn" id="reset_btn">초기화</button>
            </td>
          </table>
          <div class="filter_popup">
            <div class="filter_tit">차종 선택</div>
            <div class="float-left">
              <ul>
                <li>
                  <label for="경차" class="경차">경차</label>
                  <input type="checkbox" name="car_type" id="경차"/>
                </li>
                <li>
                  <label for="소형" class="소형">소형</label>
                  <input type="checkbox" name="car_type" id="소형"/>
                </li>
                <li>
                  <label for="준중형" class="준중형">준중형</label>
                  <input type="checkbox" name="car_type" id="준중형"/>
                </li>
                <li>
                  <label for="중형" class="중형">중형</label>
                  <input type="checkbox" name="car_type" id="중형"/>
                </li>
                <li>
                  <label for="고급" class="고급">고급</label>
                  <input type="checkbox" name="car_type" id="고급"/>
                </li>
                <li>
                  <label for="rv_suv" class="rv_suv">RV/SUV</label>
                  <input type="checkbox" name="car_type" id="rv_suv"/>
                </li>
                <li>
                  <label for="승합" class="승합">승합</label>
                  <input type="checkbox" name="car_type" id="승합"/>
                </li>
                <li>
                  <label for="전기" class="전기">전기</label>
                  <input type="checkbox" name="car_type" id="전기"/>
                </li>
                <li>
                  <label for="이륜" class="이륜">이륜</label>
                  <input type="checkbox" name="car_type" id="이륜"/>
                </li>
              </ul>
            </div>

            <div class="filter_tit">제조사 선택</div>
            <div class="float-left">
              <ul>
                <li>
                  <label for="현대" class="현대">현대</label>
                  <input type="checkbox" name="car_brand" id="현대"/>
                </li>
                <li>
                  <label for="기아" class="기아">기아</label>
                  <input type="checkbox" name="car_brand" id="기아"/>
                </li>
                <li>
                  <label for="르노삼성" class="르노삼성">르노삼성</label>
                  <input type="checkbox" name="car_brand" id="르노삼성"/>
                </li>
                <li>
                  <label for="쌍용" class="쌍용">쌍용</label>
                  <input type="checkbox" name="car_brand" id="쌍용"/>
                </li>
                <li>
                  <label for="쉐보레" class="쉐보레">쉐보레</label>
                  <input type="checkbox" name="car_brand" id="쉐보레"/>
                </li>
                <li>
                  <label for="수입" class="수입">수입</label>
                  <input type="checkbox" name="car_brand" id="수입"/>
                </li>
              </ul>
            </div>

            <div class="filter_tit">나이/경력<span class="dc_span"><input type="checkbox" class="check_inline" id="drive_career" /><label for="drive_career">운전 경력 1년 미만 시 체크</label></span></div>
            <div>
              <input type="number" id="driver_birth" placeholder="생년월일 6자리 입력" maxlength="6" oninput="maxLengthCheck(this)" numberOnly />
            </div>

            <div class="bottom_fixed_btn">
              <button type="button" id="filter3_set_btn">적용</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal end -->



<script>
  // $(document).ready(function() {
  //   $(".range_price").text("모든가격");
  // });


  // 가격정보 range script

  // const range_left = document.querySelector("#range1");
  // const range_right = document.querySelector("#range2");
  // result = document.querySelector(".range_price");
  // result2 = document.querySelector(".range_price2");
  //
  // function handleInput(e){
  //   if(`${e.target.value}` == 0 && range_right.value < 100) {
  //     result.innerHTML = ``
  //     result2.innerHTML = range_right.value + `만원 이하`
  //   } else if(`${e.target.value}` > 0 && range_right.value == 100) {
  //     result.innerHTML = `${e.target.value}만원 이상`
  //   } else if(`${e.target.value}`+9 < range_right.value && range_right.value < 100){
  //     result.innerHTML = `${e.target.value} ~ `
  //   } else if(`${e.target.value}` == 0 && range_right.value == 100) {
  //     result.innerHTML = `모든 가격`
  //     result2.innerHTML = ``
  //   }
  // }
  //
  // function handleInput2(e){
  //   if(`${e.target.value}` == 100 && range_left.value > 0) {
  //     result.innerHTML = range_left.value + `만원 이상`
  //     result2.innerHTML = ``
  //   } else if(`${e.target.value}` < 100 && range_left.value == 0) {
  //     result.innerHTML = ``
  //     result2.innerHTML = `${e.target.value}만원 이하`
  //   } else if(`${e.target.value}`-9 > range_left.value && range_left.value > 0){
  //     result.innerHTML = range_left.value + ` ~ `
  //     result2.innerHTML = `${e.target.value}만원`
  //   } else if(`${e.target.value}` == 100 && range_left.value == 0) {
  //     result.innerHTML = `모든 가격`
  //     result2.innerHTML = ``
  //   }
  // }
  //
  // function init(){
  //   range_left.oninput = handleInput;
  //   result.innerText = `${range_left.value}`
  //   range_right.oninput = handleInput2;
  // }
  //
  // init();

  $(".float-left input").change(function() {
    var thisid = $(this).attr("id");
    if($(this).is(":checked") == true) {
      $("."+thisid).attr("style","background-color: #0080FF; color: #fff;");
    } else {
      $("."+thisid).attr("style","background-color: #F4F4F4; color: #000;");
    }
  });

  // 인원정보 - + script
  $("#ps_minus").click(function() {
    var ps_num = $(".personnel").val();
    var ps_val = parseInt(ps_num) - 1;

    $(".pers_text").text(ps_val+"명");
    $(".personnel").val(ps_val);

    if(ps_val == 1) {
      $(this).attr("disabled", true);
    } else if(ps_val < 10) {
      $("#ps_plus").attr("disabled", false);
    }
  });

  $("#ps_plus").click(function() {
    var ps_num = $(".personnel").val();
    var ps_val = parseInt(ps_num) + 1;

    $(".pers_text").text(ps_val+"명");
    $(".personnel").val(ps_val);

    if(ps_val == 10) {
      $(this).attr("disabled", true);
      $(".pers_text").text("10명 이상");
    } else if(ps_val > 1) {
      $("#ps_minus").attr("disabled", false);
    }
  });

  $("td #reset_btn").click(function(){
    $(".float-left input[type=checkbox]").prop("checked", false);
    $(".dc_span input[type=checkbox]").prop("checked", false);
    $("#driver_birth").val("");
    $(".float-left label").attr("style","background-color: #f4f4f4; color: #000;");
    $(".personnel").val("2");
    $(".pers_text").text("2명");
    $("#ps_minus").attr("disabled", false);
    $("#ps_plus").attr("disabled", false);
    $("#sp1").val("0");
    $("#sp2").val("100");

    // $("#range").val("0");
    // $(".range_price").text("모든 가격");
    // $("#range1").val("0");
    // $("#range2").val("100");
    // $(".thumb left").attr("style","left: 0%;");
    // $(".thumb right").attr("style","right: 0%;");
    // $(".range_price").text("모든가격");
    // $(".range_price2").text("");
  });

  $("input[name=list_sort]").change(function() {
    setTimeout(function() {
      $("#modal2close").trigger("click");
    },200);
  });

  $("input[name=list_sort]").change(function() {
    if($(this).val() == "best") {
      $(".sort_btn").text("인기순");
    } else if($(this).val() == "high_price") {
      $(".sort_btn").text("높은 가격순");
    } else if($(this).val() == "row_price") {
      $(".sort_btn").text("낮은 가격순");
    }
  });

  $("#filter_set_btn").click(function() {
    if($("#sp1").val() >= $("#sp2").val()) {
      alert("가격정보를 올바르게 입력해주세요.");
      $("#sp1").focus();
    } else {
      $("#modalclose").trigger("click");
    }
  });

  $("#filter3_set_btn").click(function() {
    $("#modal3close").trigger("click");
  });

  $("input:text[numberOnly]").on("keyup", function() {
    $(this).val($(this).val().replace(/[^0-9]/g,""));
  });

  function maxLengthCheck(object){
    if (object.value.length > object.maxLength){
        object.value = object.value.slice(0, object.maxLength);
    }
  }


//   const inputLeft = document.querySelector(".range-left");
// const inputRight = document.querySelector(".range-right");
//
// const thumbLeft = document.querySelector(".thumb.left");
// const thumbRight = document.querySelector(".thumb.right");
//
// const range1 = document.querySelector(".range");
//
// const setLeftValue = e => {
//   const _this = e.target;
//   const { value, min, max } = _this;
//
//   if (+inputRight.value - +value < 10) {
//     _this.value = +inputRight.value - 10;
//   }
//
//   const percent = ((+_this.value - +min) / (+max - +min)) * 100;
//
//   thumbLeft.style.left = `${percent}%`;
//   range1.style.left = `${percent}%`;
// };
//
// const setRightValue = e => {
//   const _this = e.target;
//   const { value, min, max } = _this;
//
//   if (+value - +inputLeft.value < 10) {
//     _this.value = +inputLeft.value + 10;
//   }
//
//   const percent = ((+_this.value - +min) / (+max - +min)) * 100;
//
//   thumbRight.style.right = `${100 - percent}%`;
//   range1.style.right = `${100 - percent}%`;
// };
//
// if (inputLeft && inputRight) {
//   inputLeft.addEventListener("input", setLeftValue);
//   inputRight.addEventListener("input", setRightValue);
// }

</script>
