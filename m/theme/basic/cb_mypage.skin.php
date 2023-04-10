<?php
//회원정보불러오기
$sql = "select * from hi_member where id= '{$member['id']}'";
$member = sql_fetch($sql);

//쿠폰
$sql_search = " where mb_id = '{$member['id']}' ";
$sql_search .= " and mb_use='0' and ( (cp_inv_type='0' and (cp_inv_edate = '9999999999' or cp_inv_edate > curdate())) or (cp_inv_type='1' and date_add(`cp_wdate`, interval `cp_inv_day` day) > now()) ) ";
$sql = "select * from hi_coupon_log $sql_search ";
$coupon = sql_query($sql);
$list_count = sql_num_rows($coupon);

?>
<section class="mypage_section">
  <!-- <table class="top_tit_side_3">
    <td>
      <span onclick="history.back();"><img src="../img/prev.png" /></span>
    </td>
    <td>
    </td>
    <td>

    </td>
  </table> -->
  <div class="auto_side">
    <?php if($member['id']){ ?>
    <span class="welcome_name"><?php echo $member['name']; ?>님 반갑습니다.</span>
    <span><button type="button" onclick="location.href='<?php echo TB_MSHOP_URL; ?>/cb_mypage_set.php';" class="mypage_set_btn">설정<img src="../img/chevron-small-right.png" /></button></span>
    <?php } else { ?>
      <div>
        <button type="button" onclick="location.href='<?php echo TB_MSHOP_URL; ?>/cb_login.php';">로그인</button> / <button type="button" onclick="location.href='<?php echo TB_MSHOP_URL; ?>/cb_member_regist_agree.php';">회원가입</button>
      </div>
    <?php } ?>
  </div>

  <?php if($member['id']){ ?>
  <h4>혜택정보</h4>
  <div class="benefits_info">
    <button type="button" class="shadow_box" onclick="location.href='<?php echo TB_MSHOP_URL; ?>/cb_point_list.php';"><img src="../img/benefits_point.png" /><span class="ben_btn_tit">포인트</span><span class="ben_btn_point"><span class="p_num"><?php echo number_format($member['point']);?></span> P</span></button>
    <button type="button" class="shadow_box" onclick="location.href='<?php echo TB_MSHOP_URL; ?>/cb_coupon_list.php';"><img src="../img/benefits_coupon.png" /><span class="ben_btn_tit">쿠폰</span><span class="ben_btn_point"><span class="p_num"><?php echo $list_count ?></span> 개</span></button>
    <!-- <button type="button" class="shadow_box" onclick="location.href='<?php echo TB_MSHOP_URL; ?>/cb_token_list.php';"><img src="../img/benefits_coin.png" /><span class="ben_btn_tit">욜로</span><span class="ben_btn_point"><?php echo number_format($member['tocken']);?> YOLOC</span></button> -->
    <button type="button" class="shadow_box" onclick="location.href='<?php echo TB_MSHOP_URL; ?>/cb_token_list.php';"><img src="../img/benefits_coin.png" /><span class="ben_btn_tit">욜로</span><span class="ben_btn_point"><span class="p_num"><?php echo number_format($member['tocken']);?></span> Yoloc</span></button>
  </div>
  <?php } ?>

  <h4>마이페이지</h4>
  <div class="mypage_list_div">
    <ul class="cb_my_menu">
      <li onclick="location.href='<?php echo TB_MSHOP_URL; ?>/cb_lately_item.php';" class="auto_side"><span>최근 본 목록</span><img src="../img/chevron-small-right.png" /></li>
      <?php if($member['id']){ ?>
      <li onclick="location.href='<?php echo TB_MSHOP_URL; ?>/cb_card.php';" class="auto_side"><span>간편결제 카드관리</span><img src="../img/chevron-small-right.png" /></li>
      <?php } ?>
      <li onclick="location.href='../bbs/board_list.php?boardid=43';" class="auto_side"><span>욜로 소식</span><img src="../img/chevron-small-right.png" /></li>
      <li onclick="location.href='../bbs/board_list.php?boardid=44';" class="auto_side"><span>이벤트 전체보기</span><img src="../img/chevron-small-right.png" /></li>
      <li onclick="location.href='../bbs/board_list.php?boardid=45';" class="auto_side"><span>자주묻는 질문</span><img src="../img/chevron-small-right.png" /></li>
      <li onclick="location.href='<?php echo TB_MSHOP_URL; ?>/carbang_setting.php';" class="auto_side"><span>환경설정</span><img src="../img/chevron-small-right.png" /></li>
    </ul>
  </div>

  <div class="cs_tit auto_side">
    <h4>고객센터</h4><small>오전 9시 ~ 새벽 3시</small>
  </div>
  <div class="mypage_list_div">
    <ul calss="cb_my_menu">
      <li class="auto_side"><span>상담원 연결</span><img src="../img/call_icon.png" class="call_icon"/></li>
      <!-- <li class="auto_side"><span>욜로 정보</span><img src="../img/chevron-small-right.png" /></li> -->
    </ul>
  </div>

  <!-- [고정] 메인 > 인기상품 하단 { -->
  <!-- <?php if($slider8 = mobile_slider(8, $pt_id)) { ?>
    <div class="swiper-container swiper_banner">
      <div class="swiper-wrapper">
      </div>
    </div>

    <script>
      var swiper = new Swiper('.swiper-container', {
        loop: true,
      });

      var test = '<?php echo $slider8; ?>';
      var img = new Array();
      img = test.split(",");

      for(let i=0;i < img.length-1; i++) {
        swiper.appendSlide(
          '<div class="swiper-slide" style="width: 100%; height: 200px;">'
          + '<img src="'+img[i]+'" />'
          + '</div>'
        );
      }
      swiper.update();
    </script>

  <?php } ?> -->
  <!-- } [고정] 메인 > 인기상품 하단 끝 -->

  <!-- <p class="cbm_line"></p>

  <div class="cb_footer">
    <ul>
      <li class="cbf_tit cbf_pd"><span class="cbf_tit_span">욜로 | YOLO</span><span class="info_show">사업자정보<span class="cbm_down">▽</span><span class="cbm_up">△</span></span></li>
      <li class="cbf_hide">주소: 부산광역시 </li>
      <li class="cbf_hide">대표자: 최환</li>
      <li class="cbf_hide">통신판매번호: 2020-부산남구-305호</li>
      <li class="cbf_hide">전화번호: 0000-0000</li>
      <li class="cbf_hide">전자우편주소: eroumshop@naver.com</li>
      <li class="cbf_hide">호스팅서비스제공자의 상호 표시: 욜로</li>
      <li class="cbf_bot cbf_pd">이용약관 | <span class="cbf_bold">개인정보 처리방침</span> | 위치정보 이용약관</li>
      <li class="cbf_bot cbf_pd">욜로는 통신판매중개자로서 통신판매의 당사자가 아니며, 상품의 예약,
         이용 및 환불 등과 관련한 의무와 책임은 각 판매자에게 있습니다.</li>
    </ul>
  </div> -->

</section>

<script>
  $(".info_show").click(function(){
    if($(".cbm_up").css("display") == "none") {
      $(".cbm_down").hide();
      $(".cbm_up").show();
    } else {
      $(".cbm_up").hide();
      $(".cbm_down").show();
    }
    $(".cbf_hide").toggle();
  });
</script>
