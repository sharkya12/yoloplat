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
echo $sql;

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
} else {
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
        echo '렌트카a';
      } else if ($_GET['cb_type'] == 'KP') {
        $cb_type_nm = 'rent';
        echo '캠핑카a';
      } else if ($_GET['cb_type'] == 'EX') {
        $cb_type_nm = 'exp';
        echo '체험';
      } else if ($_GET['cb_type'] == 'EV') {
        echo '이벤트';
      } ?>
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
  <div class="rental_search_div shadow_box">
    <div class="rental_search">
      <h2>대여장소</h2>
      <button type="button" <?php if($_GET['cb_type'] == 'RT') { ?>onclick="location.href='<?php echo TB_MSHOP_URL; ?>/cb_region_select_test.php?cb_type=<?php echo $cb_type;?>';" <?php } else { ?> data-toggle="modal" data-target="#Modal4" <?php } ?> id="region_sel_btn">
        <?php if($catecode == null || $catecode == '') {?>전체지역<?php } else {?><?php echo $catename; }?>
      </button>
      <input type="hidden" value="전체지역" id="sel_region"/>
    </div>

    <div class="date_set">
      <h2>일정을 설정해 주세요.</h2>
      <!-- <div class="auto_side">
        <button type="button">
          <img src="../img/calendar_icon.png" /><span>대여일</span>
          <p>6. 30</p>
        </button>
        <div class="date_set_line"></div>
        <button type="button">
          <img src="../img/calendar_icon.png" /><span>반납일</span>
          <p>7. 01</p>
        </button>
      </div> -->
      <button type="button" onclick="location.href='<?php echo TB_MSHOP_URL; ?>/reservation_calendar.php?page=main&cb_type=<?php echo $cb_type;?>';"><img src="../img/calendar_icon.png" /><?php echo $str_in_date." - ".$str_out_date;?> (<?php echo $interval;?>)</button>
    </div>
    <button type="button" class="car_list_search" onclick="location.href='carbang_list_car_test.php?cb_type=KP&catecode=006';">검색</button>
  </div>


</div>

<?php if(!$_GET['catecode']) { ?>
<section class="today_slide_section">
  <div class="main_tit auto_side">
    <h2>오늘의 특가</h2>
    <a href="">전체보기</a>
  </div>
  <nav id="today_gnb">
    <?php if($_GET['cb_type'] == 'RT') { ?>
      <ul>
        <?php for($i = 0; $row=sql_fetch_array($result); $i++) { ?>
        <li>
          <?php if($row['company_img']) { ?>
            <div class="company_img" style="background-image: url('<?php echo "../../data/".$cb_type_nm."/".$row['company_img']; ?>');"></div>
          <!-- <img src="<?php echo "../../data/".$cb_type_nm."/".$row['company_img']; ?>" width="100%"> -->
          <?php } else { ?>
            <div class="company_img" style="background-image: url('../img/hd_grandeur.png');"></div>
          <!-- <img src="../img/image_none.png" width="100%"/> -->
          <?php } ?>
          <h3><?php echo $row['company_name']; ?></h3>
          <h4>30,000원 ~</h4>
        <?php } ?>
        </li>
    </ul>
  <?php } else if($_GET['cb_type'] == "KP") {  ?>
    <ul>
      <?php for($i = 0; $row=sql_fetch_array($result); $i++) { ?>
      <li>
        <?php if($row['company_img']) { ?>
          <div class="company_img" style="background-image: url('<?php echo "../../data/".$cb_type_nm."/".$row['company_img']; ?>');"></div>
        <!-- <img src="<?php echo "../../data/".$cb_type_nm."/".$row['company_img']; ?>" width="100%"> -->
        <?php } else { ?>
          <div class="company_img" style="background-image: url('../img/hd_grandeur.png');"></div>
        <!-- <img src="../img/image_none.png" width="100%"/> -->
        <?php } ?>
        <h3><?php echo $row['company_name']; ?></h3>
        <h4>30,000원 ~</h4>
      <?php } ?>
      </li>
    </ul>
        <!-- <li>
          <div style="background-image: url('../img/cc5.jpeg');"></div>
          <h3>욜로캠핑카 강원점</h3>
          <h4>420,000원 ~</h4>
        </li>
    </ul> -->
  <?php }?>
</nav>
</section>
<?php } ?>

<div class="carbang_list_container">

  <div class="list_tit">
    <h3>인기 추천</h3>
  </div>
  <?php if($cb_type == 'RT') { ?>
  <button type="button" class="filter_btn" data-toggle="modal" data-target="#Modal3"><img src="../img/filter_icon.png" /></button>
  <?php } ?>

  <ul class="list_ul">
    <?php
      //불러온 list가 없으면
      if(sql_num_rows($result) == 0){
    ?>
      <li>
        <table style="width:100%">
          <tr>
            <td>
              <span>해당 지역의 상품이 존재하지 않습니다.</span>
            </td>
          </tr>
        </table>
      </li>
    <?php
      } else {
        for($i=0;$row=sql_fetch_array($result);$i++) {
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
              <?php if($_GET['cb_type'] == 'RT' || $_GET['cb_type'] == 'KP' || $_GET['cb_type'] == 'EX') { ?>
              <?php } else { ?>
              <div><?php echo $row['room_grade'];?></div>
              <?php } ?>
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


<!-- Modal3 -->
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
<!-- Modal3 end -->


<!-- Modal3 -->
<div class="full-screen-modal fx_none_modal">
  <div class="modal" id="Modal4" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-body">
          <table class="top_tit_side_3">
            <td>
              <span id="modal3close" data-dismiss="modal"><img src="../img/prev.png" /></span>
            </td>
            <td class="fw_regular">
              지역선택
            </td>
            <td>

            </td>
          </table>

          <div class="car_region_div">
            <button class="shadow_box">전체지역</button>
            <div class="auto_side">
              <button type="button" class="shadow_box">서울</button>
              <button type="button" class="shadow_box">부산</button>
            </div>
            <div class="auto_side">
              <button type="button" class="shadow_box">경기</button>
              <button type="button" class="shadow_box">경남</button>
            </div>
            <div class="auto_side">
              <button type="button" class="shadow_box">대구</button>
              <button type="button" class="shadow_box">경북</button>
            </div>
            <div class="auto_side">
              <button type="button" class="shadow_box">대전</button>
              <button type="button" class="shadow_box">충북</button>
            </div>
            <div class="auto_side">
              <button type="button" class="shadow_box">충남</button>
              <button type="button" class="shadow_box">강원</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal4 end -->



<script>
  $(".car_region_div button").click(function() {
    $(".car_region_div button").attr("style", "border: 3px solid #CCCCCC; color: #CCCCCC;");
    $(this).attr("style", "border: 3px solid #0080FF; color: #0080FF;");
    $("#region_sel_btn").text($(this).text());
    $("#sel_region").val($(this).text());
    $("#Modal4").modal('hide');
  });

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

</script>
