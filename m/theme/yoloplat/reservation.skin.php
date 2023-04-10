<!-- Modal Popup Script -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<!-- Modal Popup Script -->


<?php if($is_member){?>
<section class="rsrv_section">


<section class="rsrv_nav_section">
  <div>
    <h1>결제</h1>
  </div>
  <button type="button" onclick="history.back();"><i class="fas fa-chevron-left"></i></button>
</section>


  <form name="buyform" id="buyform" method="post" action="<?php echo $order_action_url; ?>"  onsubmit="return fbuyform_submit(this);" autocomplete="off">
    <!-- onsubmit="return fbuyform_submit(this);"-->



  <input type="hidden" name="in_date" id="in_date" value="<?php echo $check_in.' '.$check_in_str ;?>" />
  <input type="hidden" name="out_date" id="out_date" value="<?php echo $check_out.' '.$check_out_str;?>" />
  <input type="hidden" name="dan" value="3">
  <input type="hidden" name="name" value="<?php echo $member['name']; ?>">
  <input type="hidden" name="cellphone" value="<?php echo $member['cellphone']; ?>">
  <input type="hidden" name="mb_point" value="<?php echo $member['point']; ?>">
  <input type="hidden" name="mb_money" value="<?php echo $member['money']; ?>">
	<input type="hidden" name="pt_id" value="<?php echo $member['pt_id']; ?>">
  <input type="hidden" name="seller_id" value="<?php echo $room_id; ?>">
  <input type="hidden" name="type" value="<?php echo $type; ?>">
  <input type="hidden" name="goods_ca" value="<?php echo $goods_row['goods_ca']; ?>" >
  <input type="hidden" name="gs_id" value="<?php echo $goods_row['gs_index'];?>">

  <!-- app pay Data-->
  <input type="hidden" name="PayMethod" id="PayMethod" />
  <input type="hidden" name="Moid" id="Moid" value="<?php echo $od_id;?>" />
  <input type="hidden" name="GoodsName" id="GoodsName" value="<?php echo $goods_row['gname'];?>" />
  <input type="hidden" name="Amt" id="Amt" value="<?php echo $goods_row['sales_price'];?>" />
  <input type="hidden" name="DutyFreeAmt" id="DutyFreeAmt" value="<?php echo 0;?>" />
  <input type="hidden" name="GoodsCnt" id="GoodsCnt" value="<?php echo 1;?>" />
  <input type="hidden" name="BuyerName" id="BuyerName" value="<?php echo $member['name'];?>" />
  <input type="hidden" name="MallUserID" id="MallUserID" value="<?php echo $member['id'];?>" />
  <input type="hidden" name="BuyerTel" id="BuyerTel" value="<?php echo $member['cellphone'];?>" />
  <input type="hidden" name="BuyerEmail" id="BuyerEmail" value="<?php echo $member['email'];?>" />
  <!-- -->
  <input type="hidden" name="coupon_total" value="0">
  <!-- <input type="hidden" name="coupon_price" value=""> -->
  <input type="hidden" name="coupon_lo_id" value="">
  <input type="hidden" name="coupon_cp_id" value="">



  <section class="reservation_goods_section">
    <div class="reservation_goods_info">
      <?php if($room_id) { ?>
      <h1><?php echo $room['company_name'];?><span><?php echo $goods_row['gname'];?></span></h1>
      <input type="hidden" name="company_name" value="<?php echo $room['company_name']; ?>"/>
      <input type="hidden" name="gname" value="<?php echo $goods_row['gname']; ?>"/>
      <small>기준 <?php echo $goods_row['standard_pplNum'];?>명 / 최대 <?php echo $goods_row['max_pplNum'];?>명</small>
      <small class="impact"><i class="fas fa-exclamation-circle"></i>연박 예약의 경우, 체크인 시 프론트에 연박여부 사전고지 필수</small>
      <?php } ?>

      <?php if($rent_id) { ?>
        <h2>소복이렌트카</h2>
        <div class="flex_div">
          <h1>그랜저</h1><span class="car_sort">중대형</span>
        </div>
        <small>2021년식 <b>휘발유</b> 블랙 <b>(9.4~10.2km/l)</b></small>
      <?php } ?>

      <?php if($camper_id) { ?>
        <h2>욜로캠핑카</h2>
        <div class="flex_div">
          <h1>스타렉스</h1>
        </div>
        <small>1박(24시간) / 4인승 / 캠핑장비 대여 가능</small>
      <?php } ?>
    </div>
    <div class="reservation_check_info">
      <?php if($room_id) { ?>
        <div class="auto_side">
          <h3>체크인</h3>
          <h3><?php echo $str_in_date;?><?php echo $check_in_str;?></h3>
        </div>
        <div class="auto_side">
          <h3>체크아웃</h3>
          <h3><?php echo $str_out_date;?><?php echo $check_out_str;?></h3>
        </div>
        <div class="auto_side">
          <h3>숙박기간</h3>
          <h3><?php echo $interval;?>박</h3>
        </div>

      <?php } ?>

      <?php if($rent_id || $camper_id) { ?>
        <div class="auto_side">
          <h3>대여일시</h3>
          <h3><?php echo $str_in_date;?><?php echo $check_in_str;?> 12:00</h3>
        </div>
        <div class="auto_side">
          <h3>반납일시</h3>
          <h3><?php echo $str_out_date;?><?php echo $check_out_str;?> 12:00</h3>
        </div>
        <div class="auto_side">
          <h3>대여시간</h3>
          <h3>24시간</h3>
        </div>
        <div class="auto_side">
          <h3>보험종류</h3>
          <h3>일반자차</h3>
        </div>
      <?php } ?>

    </div>
  </section>

  <?php if($room_id) { ?>
  <section class="booker_info">
    <div class="rsrv_info_div">
      <h1 class="essential">예약자 정보</h1>
      <small>
        <i class="fas fa-exclamation-circle"></i>체크인 시 예약자 정보와 다를 경우 입실 하실 수 없습니다.
      </small>
      <div class="auto_side">
        <div class="mb_same_div">
          <input type="checkbox" name="mb_same" id="mb_same"/><label for="mb_same">회원정보와 동일</label>
        </div>
        <span class="ck_info_btn"><button type="button" class="check_info_btn" data-toggle="modal" data-target="#Modal1"><span id="ck_info">체크인 시 필요합니다.</span><span><img src="../img/chevron-small-right.png" /></span></button></span>
      </div>
    </div>
    <div class="around_side">
      <label><input type="radio" name="visit_type" value="도보">도보방문</label>
      <label><input type="radio" name="visit_type" value="차량">차량방문</label>
    </div>
  </section>
  <?php } ?>

  <?php if($rent_id||$camper_id) { ?>
  <section class="driver_info_section">
    <div class="rsrv_info_div">
      <h1 class="essential">운전자 정보</h1>
      <div class="driver_check">
        <label><input type="radio" name="driver_num" value="1" checked /><span>운전자 1명</span></label>
        <label><input type="radio" name="driver_num" value="2" /><span>운전자 2명</span></label>
      </div>
      <script>
        $("input:radio[name=driver_num]").change(function() {
          if($(this).val() == '1') {
            $("#driver2").hide();
          }
          if($(this).val() == '2') {
            $("#driver2").show();
          }
        });
      </script>
      <small>
        <i class="fas fa-info-circle"></i>운전자 추가 1인은 별도의 추가비용 없이 등록 가능합니다
      </small>
      <div class="driver_info" id="driver1">
        <h2>제1운전자</h2>
        <label>
          <h3>이름</h3>
          <input type="text" name="dirver_name" placeholder="운전자 이름" />
        </label>
        <label>
          <h3>생년월일</h3>
          <div class="birth auto_side">
            <input type="text" name="dirver_birth" maxlength="6" placeholder="생년월일 6자리" />
            <div>
              <label><input type="radio" name="driver_gender" value="남성" checked /><span>남성</span></label>
              <label><input type="radio" name="driver_gender" value="여성" / ><span>여성</span></label>
            </div>
          </div>
        </label>
        <label>
          <h3>휴대폰번호</h3>
          <div class="driver_phone auto_side">
            <input type="text"name="dirver_phone" placeholder="휴대폰번호" />
            <button type="button">인증번호받기</button>
          </div>
        </label>
        <label>
          <h3>이메일</h3>
          <input type="text" name="dirver_email" placeholder="이메일" />
        </label>
      </div>
      <div class="driver_info" id="driver2">
        <h2>제2운전자</h2>
        <label>
          <h3>이름</h3>
          <input type="text" name="dirver2_name" placeholder="운전자 이름" />
        </label>
        <label>
          <h3>생년월일</h3>
          <div class="birth auto_side">
            <input type="text" name="dirver2_birth" maxlength="6" placeholder="생년월일 6자리" />
            <div>
              <label><input type="radio" name="driver2_gender" value="남성" checked /><span>남성</span></label>
              <label><input type="radio" name="driver2_gender" value="여성" / ><span>여성</span></label>
            </div>
          </div>
        </label>
        <label>
          <h3>휴대폰번호</h3>
          <div class="driver_phone">
            <input type="text"name="dirver2_phone" placeholder="휴대폰번호" />
          </div>
        </label>
      </div>
    </div>
    <div class="driver_ex">
      <div class="shadow_box">
        <b>차량 대여 시 운전자는 운전면허증을 꼭 지참해 주세요.</b>
        <small>면허증 확인 및 계약서 작성에 꼭 필요하며, 미지참 시 차량 대여가 되지 않습니다.</small>
      </div>
    </div>
  </section>
  <?php } ?>

  <section class="payment_info_section">
    <h1>결제정보 및 할인</h1>
    <div class="auto_side">
      <h3>예약금액</h3>
      <h3><?php echo(display_price($ori_price)); ?></h3>
      <input type="hidden" name="ori_price" id="ori_price" value="<?php echo number_format($ori_price); ?>" />
    </div>
    <div class="auto_side">
      <h3>할인쿠폰</h3>
      <span data-toggle="modal" data-target="#Modal3" id="cou_btn">사용가능 쿠폰 <?php echo $cp_avl_chk[3] ?>장<i class="fas fa-chevron-right"></i></span>
      <input type="hidden" name="coupon_price" id="coupon_price" value="0" />
    </div>
    <?php if(!$is_member || !$config['usepoint_yes']) { ?>
      <input type="hidden" name="use_point" value="0">
    <?php } else { ?>
      <div class="point_div auto_side">
        <h3>포인트</h3>
        <input type="text" placeholder="100P이상 사용가능" name="use_point" id="use_point" value="0" onkeyup="calculate_temp_point(this.value); this.value=number_format(this.value);" class="point_fr" numberOnly/><span>원</span>
        <button type="button" id="point_all">모두사용</button>
      </div>
      <div class="holding_point">
        <span>보유</span> 포인트 <b><?php echo display_point($member['point']);?></b>
        <input type="hidden" name="available_point" id="available_point" value="<?php echo display_point($member['point']); ?>" >
      </div>
    <?php } ?>
    <div class="total_payment auto_side">
      <h2>결제금액</h2>
      <span>
        <input type="text" name="tot_price" id="tot_price" value="<?php echo display_price($ori_price); ?>" class="price_bold" style="outline:0;text-align:right;border:0;" readonly />
      </span>
    </div>
  </section>

  <section class="payment_method_section">
    <h1>결제수단</h1>
    <ul>
      <li><label id="card_lb"><input type="radio" name="pay_method" id="card" value="간편신용카드" /><img src="../img/payment-method.png" alt="신용카드(간편)" />신용카드(간편)</label></li>
      <!-- <li><label id="card_lb"><input type="radio" name="pay_method" id="card" value="간편신용카드" <?php if($member['pay_method'] == '간편신용카드') { ?> checked <?php } ?> /><img src="../img/payment-method.png" alt="신용카드(간편)" />신용카드(간편)</label></li> -->
      <li><label class="lb_check" id="ccard_lb"><input type="radio" name="pay_method" id="credit_card" value="신용카드" <?php if($member['pay_method'] == '신용카드' || $member['pay_method'] == '' || $member['pay_method'] == '간편신용카드') { ?> checked <?php } ?>/><img src="../img/credit_card.png" alt="신용카드(일반)" />신용카드(일반)</label></li>
      <li><label id="bank_lb"><input type="radio" name="pay_method" id="simple_bank" value="간편계좌" <?php if($member['pay_method'] == '간편계좌') { ?> checked <?php } ?>/><img src="../img/online-banking.png" alt="간편계좌" />계좌 결제</label></li>
      <li><label id="pay_lb"><input type="radio" name="pay_method" id="pay" value="통합페이" <?php if($member['pay_method'] == '통합페이') { ?> checked <?php } ?>/><img src="../img/pay.png" alt="통합페이" />통합페이 결제</label></li>
    </ul>
    <label class="next_paymethod"><input type="checkbox" name="next_paymethod" value="Y"/>이 결제 수단을 다음에도 사용합니다.</label>
  </section>

  <section class="card_section">
    <div class="tit auto_side">
      <h4>간편 신용/체크 카드를 선택해주세요.</h4>
      <!-- <button type="button">카드 관리</button> -->
    </div>
    <div class="swiper card-swiper">
      <div class="swiper-wrapper">
        <?php for($i = 0; $row=sql_fetch_array($card_info); $i++) { ?>
          <div class="swiper-slide">
          <div class="card_box">
            <div class="auto_side">
              <!-- <h3>BC카드</h3> -->
              <h3><?php echo $row['card_nm']; ?></h3>
            </div>
            <div class="card_num">
              **** **** **** <?php echo $row['card_no'];?>
            </div>
          </div>
        </div>
        <?php } ?>
        <div class="swiper-slide">
          <div class="card_add_box" data-toggle="modal" data-target="#Modal5" >
            <i class="fas fa-plus-circle"></i>
            <h4>간편 신용/체크카드 추가</h4>
          </div>
        </div>
      </div>
      <div class="swiper-pagination" id="card_pagination"></div>
    </div>
    <input type="hidden" name="select_card" id="select_card" value="<?php if($member['select_card']) {echo $member['select_card'];} else { echo 0; } ?>">
    <div class="cardQuota_div">
      <select name="cardQuota" id="cardQuota">
        <option value="00">일시불</option>
        <option value="02">2개월</option>
        <option value="03">3개월</option>
        <option value="04">4개월</option>
        <option value="05">5개월</option>
        <option value="06">6개월</option>
      </select>
      <i class="fas fa-caret-down"></i>
    </div>
  </section>

  <section class="term_section">
    <div class="auto_side">
      <h1>이용 약관 동의</h1>
      <label><input type="checkbox" id="check_all">전체동의</label>
    </div>

    <div class="term_box">
      <div class="rsrv_term_div">
        <span><input type="checkbox" id="cancel_check"></span>
        <button type="button" id="cancel_agree_btn" data-toggle="modal" data-target="#Modal4" ><?php if($room_id) { echo '숙소'; } else if($rent_id) { echo '렌트카'; } ?> 이용규칙 및 취소/환불규정 동의 (필수)</button>
      </div>
      <div class="rsrv_term_div">
        <span><input type="checkbox" id="privacy_check"></span>
        <button type="button" id="privacy_agree_btn" data-toggle="modal" data-target="#Modal4" >개인정보 수집 및 이용 동의 (필수)</button>
      </div>
      <div class="rsrv_term_div">
        <span><input type="checkbox" id="tp_check"></span>
        <button type="button" id="tp_agree_btn" data-toggle="modal" data-target="#Modal4" >개인정보 제3자 제공 동의 (필수)</button>
      </div>
    </div>
  </section>

  <section class="payment_btn_section">
    <p>(주)소복이세상은 통신판매중개자로서 통신판매의 당사자가 아니며, 상품의 예약, 이용 및 환불 등과
    관련한 의무와 책임은 각 판매자에게 있습니다.</p>
    <div class="submit_btn_div">
      <button type="submit" id="res_submit_btn"><?php echo display_price($ori_price);?> 결제하기</button>
    </div>
  </section>


  <div class="cb_reservation_container">


  <!-- Modal1 예약자 정보 페이지-->
  <div class="modal fade modal1" id="Modal1" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <!-- <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div> -->
        <div class="modal-body">
          <section class="cbrem_nav_section">
            <button type="button" data-dismiss="modal"><i class="fas fa-chevron-left"></i></button>
          </section>

          <h3>예약자 정보</h3>
          <small>입력된 예약자 정보는 숙소에 전달됩니다.</small>
          <small class="impact">* 체크인 시 방문자 정보와 예약자 정보가 다를 경우 입실 하실 수 없습니다.</small>
          <input type="hidden" id="mb_phone" value="<?php echo $member['cellphone']; ?>" />
          <div class="mb_same_div">
            <label for="mb_same">회원정보와 동일</label><input type="checkbox" name="mb_same" id="mb_same_modal"/>
          </div>
          <div class="pop_input_div">
            <h5>예약자 성함</h5>
            <input type="text" name="b_name" id="r_name" placeholder="예약자 이름을 입력하세요." />
          </div>
          <div class="pop_input_div">
            <h5>휴대폰 번호</h5>
            <input type="text" name="b_cellphone" id="r_phone" placeholder="휴대폰 번호를 입력하세요." maxlength="12" numberOnly/>
          </div>
          <div class="pop_button_div">
            <button type="button" id="r_btn" disabled data-dismiss="modal">설정완료</button>
          </div>
        </div>
        <!-- <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div> -->
      </div>
    </div>
  </div>
  <!-- Modal1 end -->

  <!-- Modal3 쿠폰적용페이지 -->
  <div class="modal fade modal3" id="Modal3" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-body">
          <section class="cbrem_nav_section">
            <button type="button" data-dismiss="modal"><i class="fas fa-chevron-left"></i></button>
          </section>

          <?php
          if ($cp_avl_chk[3] == 0) {
            echo "<p class=\"empty_list\">사용가능한 쿠폰이 없습니다.</p>"; ?>
            <!-- <input type="radio" name="coupon_sel" id="coupon0" value="0" checked /> -->
          <?php } else {
          ?>
          <ul class="coupon_radio_ul">
            <input type="hidden" name="coupon_lo_id" id="coupon_lo_id" value="" />
            <input type="hidden" name="coupon_cp_id" id="coupon_cp_id" value="" />
            <li class="shadow_box none_ra_ck">
              <label class="auto_side coupon_label" for="coupon0">
                <div class="auto_side coupon_name">
                  <div class="radio_btn">
                    <input type="radio" name="coupon_sel" id="coupon0" value="0" checked />
                  </div>
                  <div class="info">
                    <h4>적용안함</h4>
                  </div>
                  <div>

                  </div>
                </div>
              </label>
            </li>
            <?php

              $sql = " select * $cp_avl_chk[0] $cp_avl_chk[1] $cp_avl_chk[2]";
              $result = sql_query($sql);

              for ($i=1; $row =sql_fetch_array($result); $i++) {
                // 할인금액(율)
                if($row['cp_sale_type'] == '0') {
                  if($row['cp_sale_amt_max'] > 0) {
                    $cp_sale_amt_max = "<small>(최대 ".display_price($row['cp_sale_amt_max']).")</small>";
                  } else {
                    $cp_sale_amt_max = "";
                  }

                  $sale_amt = $row['cp_sale_percent']. '%' . ' 할인 '. $cp_sale_amt_max;
                } else {
                  $sale_amt = display_price($row['cp_sale_amt']);
                }

                // 쿠폰 사용기한
                if($row['cp_inv_type'] == '0') {
                  if($row['cp_inv_sdate'] == '9999999999') $cp_inv_sdate = '무제한';
                  else $cp_inv_sdate = $row['cp_inv_sdate'];

                  if($row['cp_inv_edate'] == '9999999999') $cp_inv_edate = '무제한';
                  else $cp_inv_edate = $row['cp_inv_edate'];

                  if($row['cp_inv_sdate'] == '9999999999' && $row['cp_inv_edate'] == '9999999999')
                    $inv_date = '무제한';
                  else
                    $inv_date = $cp_inv_sdate . " ~ " . $cp_inv_edate;
                } else {
                  $inv_date = '다운로드 후 ' . $row['cp_inv_day']. '일간';
                }
            ?>
            <li class="shadow_box">
              <label class="coupon_label" for="coupon<?php echo $i; ?>">
                <div class="auto_side coupon_name">
                  <div class="radio_btn">
                    <input type="radio" name="coupon_sel" id="coupon<?php echo $i; ?>" value="<?php echo $sale_amt; ?>" />
                    <input type="hidden" id="coupon_lo_id<?php echo $i; ?>" value="<?php echo $row['lo_id']; ?>" />
                    <input type="hidden" id="coupon_cp_id<?php echo $i; ?>" value="<?php echo $row['cp_id']; ?>" />
                  </div>
                  <div class="info">
                    <div>
                      <h4 class="subj"><? echo $row['cp_subject']; ?></h4>
                      <small><? echo $inv_date; ?> 사용가능</small>
                    </div>
                    <div>
                      <h4><? echo $sale_amt; ?></h4>
                    </div>
                  </div>
                </div>
              </label>
            </li>
            <?php } ?>
          </ul>

          <div class="pop_button_div">
            <button type="button" id="cou_select_btn" data-dismiss="modal">적용하기</button>
          </div>
          <?php } ?>
          <script>
            $("input[name='coupon_sel']:radio").change(function () {
              $(".coupon_radio_ul li").css('background-color','#fff');
              $(this).parents().parents().parents('li').css("background-color","#ffdb4d");
              var coupon_lo_id = $(this).parents().children().eq(1).val();
              var coupon_cp_id = $(this).parents().children().eq(2).val();
              $("#coupon_lo_id").val(coupon_lo_id);
              $("#coupon_cp_id").val(coupon_cp_id);
            })

          </script>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal3 end -->

  <!-- Modal4 -->
  <div class="modal fade modal4" id="Modal4" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-body">
          <table class="top_tit_side_3">
            <td>
              <span class="cancel_icon"  data-dismiss="modal"><img src="../img/prev.png" /></span>
            </td>
            <td class="fw_regular">
              규정 및 이용 동의
            </td>
            <td>
            </td>
          </table>
          <div class="modal-tit" id="cancel_agree_tit">
            <span>숙소이용규칙 및 취소・환불규정 동의 (필수)</span><span class="arrow_up" id="cancel_agree_up"><img src="../img/up-chevron.png" width="13px" /></span><span id="cancel_agree_down"><img src="../img/down-chevron.png" width="13px" /></span>
          </div>
          <ul class="m4_content" id="cancel_agree_content">
            <li>
              ・ 취소, 변경 불가 상품은 규정과 상관없이 취소, 변경이 불가합니다.
            </li>
            <li>
              ・ 당일 결제를 포함한 체크인 당일 취소는 취소, 변경이 불가합니다.
            </li>
            <li>
              ・ 숙소 사정에 의해 취소 발생 시 100% 환불이 가능합니다.
            </li>
            <li>
              ・ 예약취소가 불가능한 시간에 고객 사정에 의한 취소 시, 욜로에서 제공하는 모든 혜택에서 제외될 수 있으며
              (할인 쿠폰 미제공, 이벤트 대상자 제외)본 사용자 쿠폰은 소멸됩니다.
            </li>
            <li>
              ・ 단! 숙소의 객실 정보가 수시로 변경될 수 있으며, 숙소 사정에 의한 취소가 발생된 경우 이로 이한 불이익은 욜로에서 책임지지 않습니다.
            </li>
            <li>
              ・ 최대 인원 초과 시 입실 불가합니다.
            </li>
            <li>
              ・ 전원 기준 요금 외 인원 추가 요금은 현장 결제입니다.
            </li>
            <li>
              ・ 제공 이미지는 배정된 객실과 다를 수 있습니다.
            </li>
            <li>
              ・ 19세 미만 청소년은 보호자 동반 시 투숙이 가능합니다.
            </li>
            <li>
              ・ 체크인 시 배정의 경우, 객실과 배드타입을 보장하지 않습니다.
            </li>
            <li>
              ・ 객실 타입에 시간이 별도 기재된 경우, 체크인/아웃 시간이 상이할 수 있습니다.
            </li>
            <li>
              ・ 업체 현장에서 객실 컨디션 및 서비스로 인해 발생된 분쟁은 욜로에서 책임지지 않습니다.
            </li>
          </ul>
          <div class="modal-tit" id="privacy_agree_tit">
            <span>개인정보수집 및 이용 동의 (필수)</span><span class="arrow_up" id="privacy_agree_up"><img src="../img/up-chevron.png" width="13px" /></span><span id="privacy_agree_down"><img src="../img/down-chevron.png" width="13px" /></span>
          </div>
          <ul class="m4_content" id="privacy_agree_content">
            <li>
              <table class="agree_table">
                <tr>
                  <th class="th1">필수</th>
                  <th class="th2">수집・이용 목적</th>
                  <th class="th3">수집항목</th>
                  <th class="th4">보유・이용기간</th>
                </tr>
                <tr>
                  <td>
                    필수
                  </td>
                  <td>
                    예약/결제 서비스 이용
                  </td>
                  <td>
                    - 예약 서비스 이용 :
                    예약자 이름, 휴대폰 번호
                    <br /><br />
                    - 결제 서비스 이용 :
                    (카드결제 시)<br />
                    카드사명, 카드번호, 유효기간, 이메일<br />
                    (휴대폰 결제 시)<br />
                    휴대폰 번호, 통신사, 결제 승인번호<br />
                    (계좌이체 시)<br />
                    은행명, 계좌번호, 예금주<br />
                    (현금 영수증 발급 시)<br />
                    휴대폰 번호, 이메일<br />
                    (취소・환불을 위한 대금 지급 요청 시)<br />
                    은행명, 계좌번호, 예금주명
                  </td>
                  <td>
                    전자상거래상 소비자 보호에 관한 법률에 따라 5년간 보관
                  </td>
                </tr>
              </table>
              <div class="modal_ex">※ 위 동의 내용을 거부하실 수 있으나, 동의를 거부하실 경우 서비스를 이용하실 수 없습니다.</div>
              <div class="modal_ex">※ 개인정보 처리와 관련된 상세 내용은 '개인정보처리방침'을 참고</div>
            </li>
          </ul>
          <div class="modal-tit" id="tp_agree_tit">
            <span>개인정보 제3자 제공 동의 (필수)</span><span class="arrow_up" id="tp_agree_up"><img src="../img/up-chevron.png" width="13px" /></span><span id="tp_agree_down"><img src="../img/down-chevron.png" width="13px" /></span>
          </div>
          <ul class="m4_content" id="tp_agree_content">
            <li>
              <table class="agree_table2">
                <tr>
                  <th>제공받는 자</th>
                  <td>파라다이스 호텔 부산</td>
                </tr>
                <tr>
                  <th>제공 목적</th>
                  <td>숙박예약서비스 이용계약 이행(서비스 제공, 확인, 이용자 정보 확인)</td>
                </tr>
                <tr>
                  <th>제공하는 정보</th>
                  <td><small>예약한 숙박서비스의 이용자 정보(예약자 이름, 휴대폰번호, 예약번호, 예약한 업체명, 예약한 객실명, 결제금액)</small></td>
                </tr>
                <tr>
                  <th>제공받는 자의 개인정보 보유 및 이용기간</th>
                  <td>예약서비스 제공 완료 후 6개월</td>
                </tr>
              </table>
              <div class="modal_ex">※ 위 동의 내용을 거부하실 수 있으나, 동의를 거부하실 경우 서비스를 이용하실 수 없습니다.</div>
              <div class="modal_ex">※ 개인정보 처리와 관련된 상세 내용은 '개인정보처리방침'을 참고</div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal4 end -->



  <!-- card_add_popup -->
  <div class="middle-screen-modal" id="id_pw_modal">
    <div class="modal fade" id="Modal6" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-body">
            <h3>간편카드 확인</h3>
            <small>간편카드 사용을 위해<br /> 계정 비밀번호를 입력해주세요.</small>
            <input type="hidden" id="card_chk_id" value="<?php echo $member['id']; ?>"/>
            <input type="password" id="card_chk_pass" />
            <div class="btn_div auto_side">
              <button type="button" id="btn_pwd_chek">결제</button>
              <button type="button" data-dismiss="modal">취소</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- card_add_popup end -->


  </div>


</form>

</section>

<!-- card_add_popup -->
<div class="modal fade" id="Modal5" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body">
        <div class="card_add_pop">
          <!-- <form name="card_form" id="card_form" action="./cb_card_update.php" onsubmit="return card_form_submit(this);" method="post" autocomplete="off"> -->
          <form name="card_form" id="card_form" onsubmit="return card_form_submit(this);" method="post" autocomplete="off">

            <input type="hidden" name="w" value="" />
            <input type="hidden" name="mb_id" id="mb_id" value="<?php echo $member['id']; ?>" />
            <section class="card_form_section">
              <section class="card_form_nav">
                <button type="button" data-dismiss="modal"><i class="fas fa-chevron-left"></i></button>
              </section>
              <h2>간편카드 추가</h2>
              <small class="small_ex">본인명의 신용/체크카드를 등록해 주세요.</small>



              <div class="card_tit">카드번호</div>
              <div class="card_number">
                <input type="text" maxlength="4" placeholder="0000" id="card_1" />
                <input type="text" maxlength="4" placeholder="0000" id="card_2" />
                <input type="text" maxlength="4" placeholder="0000" id="card_3" />
                <input type="text" maxlength="4" placeholder="0000" id="card_4" name="card_4" />
              </div>
              <div class="card_tit">유효기간</div>
              <div class="card_date">
                <input type="text" maxlength="2" placeholder="YY" id="date_yy" /><input type="text" maxlength="2"  placeholder="MM" id="date_mm" />
              </div>
              <!-- 이 페이지에서 생년월일 변경불가 -->
              <div class="card_tit">본인확인 <small>(개인카드 : 생년월일 6자리)</small></div>
              <div class="card_birth">
                <input type="text" maxlength="6" placeholder="등록된 생년월일 정보가 없습니다." id="card_birth" value="<?php echo $member['mb_birth']; ?>" disabled/>
              </div>
              <div class="card_tit">카드비밀번호 <small>(앞 2자리)</small></div>
              <div class="card_pw">
                <input type="password" maxlength="2" placeholder="00" id="card_pw" />
              </div>
              <div class="card_tit">계정비밀번호 <small>(로그인 시 비밀번호)</small></div>
              <div class="id_pw">
                <input type="password" name="mb_password" id="input_pw" placeholder="아이디 로그인 시 비밀번호"/>
                <div id="keyShow">
                  <img src="../img/eyes.png" id="eyes_on"/>
                  <img src="../img/eyes_off.png" id="eyes_off"/>
                </div>
              </div>
              <div class="card_tit">카드별칭</div>
              <div class="card_nick">
                <input type="text" name="card_nm" maxlength="6" placeholder="본인이 알기 쉽게 별칭 입력" id="card_nick" />
              </div>

              <div class="card_add_btn_div">
                <button type="submit" id="card_reg_btn" disabled>등록</button>
              </div>

            </section>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- card_add_popup end -->

<?php } else {?>
  <table class="top_tit_side_3">
    <td style="padding: 0 16px;">
      <!-- <span onclick="history.back();"><img src="../img/prev.png" /></span> -->
    </td>
    <td class="fw_regular">
      결제
    </td>
    <td style="padding: 0 16px;">
      <div>

      </div>
    </td>
  </table>

  <div class="info_none">
    <img src="../img/exit.png" />
    <h2>로그인 후 이용해주세요</h2>
    <small>로그인 하시면 다양한 정보를 확인할 수 있습니다.</small>
    <button type="button" onclick="location.href='<?php echo TB_MAPP_URL; ?>/login.php';">로그인</button>
  </div>
<?php } ?>


<script>
$(document).ready(function() {


  // var check_time_w = (document.querySelector('.reservation_check_info .auto_side').offsetWidth);
  // $(".reservation_check_info h1").css("left",((check_time_w/2)-8)+"px");

  $(".card_section").hide();


  // 간편카드 선택
  var card_box_w = (document.querySelector('.card_section .card_box').offsetWidth);
  $(".card_section .card_box").css("height", (card_box_w-90)+"px");
  $(".card_section .card_add_box").css("height", (card_box_w-90)+"px");
  $(".card_section .swiper-wrapper").css("height", (card_box_w-90)+"px");
  $(".card_section .card_box").css("padding", (card_box_w/8)+"px");
  $(".card_section .card_add_box").css("padding", (card_box_w/6)+"px");
  $(".card_section #cardQuota").css("width", card_box_w+"px");


  if($("input:radio[name=pay_method]").val() == "간편신용카드") {
    $("#simple_card_section").show();
  } else {
    $("#simple_card_section").hide();
  }
  $(".paymethod_info").hide();
  var pmval = $("input:radio[name=pay_method]").val();
  if(pmval == '간편신용카드') {
    $("#p_method").text("간편 신용/체크카드");
  } else if(pmval == '신용카드') {
    $("#p_method").text("일반 신용/체크카드");
  } else if(pmval == '간편계좌') {
    $("#p_method").text("계좌 결제");
  // } else if(pmval == '무통장') {
  //   $("#p_method").text("무통장 입금");
  } else if(pmval == '통합페이') {
    $("#p_method").text("통합페이 결제");
  // } else if(pmval == '포인트') {
  //   $("#p_method").text("포인트 결제");
  }

});

//결제하기 클릭했을시
$("#res_submit_btn").click(function() {

    if ($("#r_name").val().length < 1 || $("#r_phone").val().length < 10) {
      alert("예약자 정보를 입력해주세요.");
      $("#Modal1").modal('show');
      return false;
    }
    if ($("#cancel_check").is(":checked") == false ) {
      alert("약관을 모두 동의해주세요.");
      return false;
    }

    alert("현재 오픈 준비중입니다.");
    return false;
      //console.log($('#Modal6').is(':visible'));
    // if ($("input:radio[name=pay_method]:checked").val() == "간편신용카드") {
    //   $("#Modal6").modal('show');
    // 간편신용 임시 주석
    // }

});

//간편카드결제의 팝업창의 비밀번호 입력시
$("#btn_pwd_chek").click(function(){

  var mb_id = $('#card_chk_id').val();
  var input_pw = $('#card_chk_pass').val();
  var state = 0;

  $.ajax({
    url: "./ajax.cb_card_passwd.php",
    type: "POST",
    async: false,
    data: { input_pw:input_pw, mb_id:mb_id },
    success:function(data) {
      state = data;
    },
    error:function(error) {
       alert("오류");
    }
  });

  if (state == 0 ){
    alert("로그인 비밀번호가 틀렸습니다.");
    return;
  } else {
    var f = document.buyform;
    f.submit();
  }
});

// 간편결제 카드 슬라이드
var select_card = $("#select_card").val();

if(select_card=='') {
  $("#select_card").val(0);
  select_card = 0;
}
var card_swiper = new Swiper(".card-swiper", {
  slidesPerView: "auto",
  centeredSlides: true,
  spaceBetween: 30,
  pagination: {
     el: ".swiper-pagination",
     type: "fraction",
   },
  initialSlide: select_card
});

// 슬라이드 할 때 어떤 카드 인지
card_swiper.on('transitionEnd', function() {

  var s_index = card_swiper.realIndex;
  var last_num = $(".swiper-pagination-total").text();

  if(last_num == s_index) {
    $(".cardQuota_div").hide();
    $("#select_card").val("");
    if($("#select_card").val() == "") {
      // alert("선택하신 간편결제 카드가 없습니다.")
    }
  } else {
    $(".cardQuota_div").show();
    $("#select_card").val(s_index);
     // alert("선택하신 간편결제 카드는 "+$("#select_card").val()+" 번째 카드 입니다.");
  }
});


//예약자정보의 회원정보와 동일하기 체크시
$("div #mb_same").change(function() {
  var f_phone = $("#mb_phone").val().toString().replace(/-/gi, "");

  if($(this).is(":checked") == true) {
    $("input:checkbox[name='mb_same']").attr("checked", true);
    $("#r_name").val("<?php echo $member['name'];?>");
    $("#r_phone").val(f_phone);
    $("#r_btn").attr("disabled", false);
    $("#ck_info").text($("#r_name").val()+"   "+$("#r_phone").val());
    $("#ck_info").attr("style","color:#000;");
  } else {
    $("input:checkbox[name='mb_same']").attr("checked", false);
    $("#r_name").val("");
    $("#r_phone").val("");
    $("#r_btn").attr("disabled", true);
    $("#ck_info").text("체크인 시 필요합니다.");
    $("#ck_info").attr("style","color:#000;");
  }
});

//예약자 정보 modal 화면에서 회원정보와 동일하기 체크시
$("div #mb_same_modal").change(function() {
  var f_phone = $("#mb_phone").val().toString().replace(/-/gi, "");

  if($(this).is(":checked") == true) {
    $("input:checkbox[name='mb_same']").attr("checked", true);
    $("#r_name").val("<?php echo $member['name'];?>");
    $("#r_phone").val(f_phone);
    $("#r_btn").attr("disabled", false);
    $("#ck_info").text($("#r_name").val()+"   "+$("#r_phone").val());
    $("#ck_info").attr("style","color:#000;");
  } else {
    $("input:checkbox[name='mb_same']").attr("checked", false);
    $("#r_name").val("");
    $("#r_phone").val("");
    $("#r_btn").attr("disabled", true);
    $("#ck_info").text("체크인 시 필요합니다.");
    $("#ck_info").attr("style","color:#000;");
  }
});
//예약자 정보 설정완료 클릭시
$("#r_btn").click(function() {
  $("#ck_info").text($("#r_name").val()+"   "+$("#r_phone").val());
  $("#ck_info").attr("style","color:#000;");
});
//예약자 성함 글자수
$("#r_name").keyup(function(e) {
  if($(this).val().length >= 1 && $("#r_phone").val().length >= 10) {
    $("#r_btn").attr("disabled",false);
  } else {
    $("#r_btn").attr("disabled",true);
  }
});
//예약자 번호 글자수
$("#r_phone").keyup(function(e) {
  if($(this).val().length >= 10 && $("#r_name").val().length >= 1) {
    $("#r_btn").attr("disabled",false);
  } else {
    $("#r_btn").attr("disabled",true);
  }
});

// 방문방법
$("input:radio[name=visit_type]").change(function() {
  $(".booker_info .around_side label").attr("style", "background-color: #FFFFFF; border: 1px solid #828282; color: #828282;");
  $(this).parent('label').attr("style","background-color: #1770FF; border: 1px solid #1770FF; color: #FFFFFF;");
});


$("input:radio[name=pay_method]").change(function() {
  var method = $(this).val();
  if(method == '간편신용카드') {
    $("#p_method").text("간편 신용/체크카드");
    $("#PayMethod").val("AUTO_CARD");
  } else if(method == '신용카드') {
    $("#p_method").text("일반 신용/체크카드");
    $("#PayMethod").val("CARD");
  } else if(method == '간편계좌') {
    $("#p_method").text("계좌 결제");
    $("#PayMethod").val("BANK");
  } else if(method == '통합페이') {
    $("#p_method").text("통합페이 결제");
    $("#PayMethod").val("EPAY");
  }
  calculate_paymethod($(this).val());
});

var f = document.buyform;

//포인트 사용금액이 0원일때 input 클릭시 빈값으로 시작
$('#use_point').focus(function() {
  var focus_chk = f.use_point.value;
  if(focus_chk == "0") {
    f.use_point.value = '';
    f.use_point.focus();
  }
});

//포인트 사용금액을 입력안하고 벗어났을때 다시 0원이 됨
$('#use_point').blur(function() {
  var focus_chk = f.use_point.value;
  if(focus_chk == ''){
    f.use_point.value = "0";
    calculate_order_price();
  }
});

//포인트 사용 체크사항
function point_chk(tot_price) {
  var min_point	 = parseInt("100");
  var temp_point   = parseInt(no_comma(f.use_point.value));
  var mb_point     = parseInt(no_comma(f.available_point.value));

  if(temp_point > 0 && temp_point > mb_point) {
    alert('포인트 사용금액은 현재 보유포인트 보다 클수 없습니다.');
    f.tot_price.value = number_format(String(tot_price));
    $("#res_submit_btn").text(number_format(String(tot_price))+"원 결제하기");
    f.use_point.value = 0;
    f.use_point.focus();
    return false;
  }

  if (temp_point > 0 && temp_point > tot_price) {
    alert('포인트 사용금액은 최종결제금액 보다 클수 없습니다.');
    f.tot_price.value = number_format(String(tot_price));
    $("#res_submit_btn").text(number_format(String(tot_price))+"원 결제하기");
    f.use_point.value = '';
    f.use_point.focus();
    return false;
  }

  if(temp_point > 0 && (mb_point < min_point)) {
    alert('포인트 사용금액은 '+number_format(String(min_point))+'원 부터 사용가능 합니다.');
    f.tot_price.value = number_format(String(tot_price));
    $("#res_submit_btn").text(number_format(String(tot_price))+"원 결제하기");
    f.use_point.value = '';
    f.use_point.focus();
    return false;
  }
  return true;
}

//포인트사용금액 입력시
function calculate_temp_point(val) {

  var temp_point = parseInt(no_comma(f.use_point.value));
  var ori_price = parseInt(no_comma(f.ori_price.value));
  var coupon_price  = parseInt(no_comma(f.coupon_price.value));

  var tot_price = ori_price - (coupon_price);

  if(val == '' || !checkNum(no_comma(val))) {
    alert('포인트 사용액은 숫자이어야 합니다.');
    f.tot_price.value = number_format(String(tot_price));
    f.use_point.value = '';
    f.use_point.focus();
    return;
  } else {
    f.tot_price.value = number_format(String(tot_price - temp_point));
    $("#res_submit_btn").text(number_format(String(tot_price - temp_point))+"원 결제하기");
    point_chk(tot_price);
  }
}

// 포인트 모두 사용 버튼 클릭시
$("#point_all").click(function() {

  var ori_price = parseInt(no_comma(f.ori_price.value));
  var coupon_price  = parseInt(no_comma(f.coupon_price.value));
  var point_ava = parseInt(no_comma(f.available_point.value));

  var tot_price = ori_price - coupon_price;

  if(tot_price > point_ava){
    f.use_point.value = number_format(String(point_ava));
    calculate_temp_point(parseInt(point_ava));
  } else {
    f.use_point.value = number_format(String(tot_price));
    calculate_temp_point(parseInt(tot_price));
  }
});

//최종 계산
function calculate_order_price() {

  var ori_price = parseInt(no_comma(f.ori_price.value)); // 합계금액
  var coupon_price = parseInt(no_comma(f.coupon_price.value)); //쿠폰금액
  var use_point  = parseInt(no_comma(f.use_point.value)); //포인트금액

	var tot_price  = ori_price - (coupon_price + use_point);

	$("input[name=tot_price]").val(number_format(String(tot_price)));
  $("#res_submit_btn").text(number_format(String(tot_price))+"원 결제하기");

}

// 할인쿠폰 적용
$("input:radio[name=coupon_sel]").change(function() {
  var coupon = $(this).val().replaceAll("원", "").replaceAll(",","");
  var ori_price = parseInt($("#ori_price").val().replaceAll(",",""));
  var use_point  = parseInt(no_comma(f.use_point.value)); //포인트금액

  if(use_point > 0) {
    f.use_point.value = "0";
  }

  percent_chk = /%/;

  //할인율로 쿠폰 계산할때
  if (percent_chk.test(coupon) == true) {
    //쿠폰 문자열 자르기(할인율과 최대 사용금액)
    cut_coupon = coupon.split("%");

    percent = parseInt(cut_coupon[0].replace(/[^0-9]/g,''));
    sale_amt_max = parseInt(cut_coupon[1].replace(/[^0-9]/g,''));
    // 최대 사용금액이 없을때
    if (isNaN(sale_amt_max) == true) {
      coupon_price = ori_price * (percent / 100);
    // 최대 사용금액이 할인율로계산한 금액보다 클때
    } else {
      coupon_price = ori_price * (percent / 100);
      if(coupon_price > sale_amt_max) {
        coupon_price = sale_amt_max;
      }
    }

  //할인금액만 있을때
  } else {
    coupon_price = parseInt(coupon);
  }


    if (coupon == 0) {
      $("#cou_btn").text("사용 가능 쿠폰<?php echo $cp_avl_chk[3] ?>장");
      $("#coupon_price").val(number_format(0));
        calculate_order_price();
    } else {
      $("#cou_btn").text("- "+coupon_price+"원");
      $("#coupon_price").val(number_format(String(coupon_price)));

      // var final_price = ori_price - coupon_price;
      // $("#final_price").val(number_format(String(final_price)));
        calculate_order_price();
  }

});


$("input:text[numberOnly]").on("keyup", function() {
  $(this).val($(this).val().replace(/[^0-9]/g,""));
});






// 결제방법
function calculate_paymethod(type) {

	switch(type) {

    case '간편신용카드':
      alert("현재 간편신용카드는 준비중입니다.");
      // $('.money_sub').hide();
      // $("input[name=use_money]").val(0);
			// $("input[name=use_point]").attr("readonly", true);

			// calculate_order_price();
			// <?php if(!$config['company_type']) { ?>
      //   $(".card_section").show();
      //   $(".payment_method_section ul li label").attr("style","border: 2px solid #DDDDDD;");
      //   $("#card_lb").attr("style","border: 2px solid #1770FF;");
			// <?php } ?>
			// break;

    case '신용카드':
      $('.money_sub').hide();
      // $("input[name=use_money]").val(0);
  		// $("input[name=use_point]").attr("readonly", true);

  		// calculate_order_price();
  		<?php if(!$config['company_type']) { ?>
        $(".card_section").hide();
        $(".payment_method_section ul li label").attr("style","border: 2px solid #DDDDDD;");
        $("#ccard_lb").attr("style","border: 2px solid #1770FF;");
  		<?php } ?>
  		break;

    case '간편계좌':
      $('.money_sub').hide();
      // $("input[name=use_money]").val(0);
  		// $("input[name=use_point]").attr("readonly", true);

  		// calculate_order_price();
  		<?php if(!$config['company_type']) { ?>
        $(".card_section").hide();
        $(".payment_method_section ul li label").attr("style","border: 2px solid #DDDDDD;");
        $("#bank_lb").attr("style","border: 2px solid #1770FF;");
  		<?php } ?>
  		break;

    case '통합페이':
      $('.money_sub').hide();
      // $("input[name=use_money]").val(0);
  		// $("input[name=use_point]").attr("readonly", true);

  		// calculate_order_price();
  		<?php if(!$config['company_type']) { ?>
        $(".card_section").hide();
        $(".payment_method_section ul li label").attr("style","border: 2px solid #DDDDDD;");
        $("#pay_lb").attr("style","border: 2px solid #1770FF;");
  		<?php } ?>
  		break;

  	default: // 그외 결제수단
  		$(".card_section").hide();
  		// $("input[name=use_point]").val(0);
  		// $("input[name=use_point]").attr("readonly", false);
  		break;
	}
}

$("#cancel_agree_btn").click(function() {
  $("#cancel_agree_content").show();
  $("#privacy_agree_content").hide();
  $("#tp_agree_content").hide();
  $("#cancel_agree_up").show();
  $("#cancel_agree_down").hide();
});
$("#cancel_agree_tit").click(function() {
  $("#cancel_agree_content").slideToggle();
  if($("#cancel_agree_up").css("display") == "none") {
    $("#cancel_agree_up").show();
    $("#cancel_agree_down").hide();
  } else {
    $("#cancel_agree_up").hide();
    $("#cancel_agree_down").show();
  }
});

$("#privacy_agree_btn").click(function() {
  $("#privacy_agree_content").show();
  $("#cancel_agree_content").hide();
  $("#tp_agree_content").hide();
  $("#privacy_agree_up").show();
  $("#privacy_agree_down").hide();
});
$("#privacy_agree_tit").click(function() {
  $("#privacy_agree_content").slideToggle();
  if($("#privacy_agree_up").css("display") == "none") {
    $("#privacy_agree_up").show();
    $("#privacy_agree_down").hide();
  } else {
    $("#privacy_agree_up").hide();
    $("#privacy_agree_down").show();
  }
});

$("#tp_agree_btn").click(function() {
  $("#tp_agree_content").show();
  $("#cancel_agree_content").hide();
  $("#privacy_agree_content").hide();
  $("#tp_agree_up").show();
  $("#tp_agree_down").hide();
});
$("#tp_agree_tit").click(function() {
  $("#tp_agree_content").slideToggle();
  if($("#tp_agree_up").css("display") == "none") {
    $("#tp_agree_up").show();
    $("#tp_agree_down").hide();
  } else {
    $("#tp_agree_up").hide();
    $("#tp_agree_down").show();
  }
});

$("#check_all").change(function() {
  if($("#check_all").is(":checked") == true) {
    $("#cancel_check").prop("checked", true);
    $("#privacy_check").prop("checked", true);
    $("#tp_check").prop("checked", true);
  } else {
    $("#cancel_check").prop("checked", false);
    $("#privacy_check").prop("checked", false);
    $("#tp_check").prop("checked", false);
  }
});

$("#cancel_check").change(function() {
  if($("#privacy_check").is(":checked") == true && $("#tp_check").is(":checked") == true && $("#cancel_check").is(":checked") == true) {
    $("#check_all").prop("checked", true);
  } else {
    $("#check_all").prop("checked", false);
  }
});

$("#privacy_check").change(function() {
  if($("#privacy_check").is(":checked") == true && $("#tp_check").is(":checked") == true && $("#cancel_check").is(":checked") == true) {
    $("#check_all").prop("checked", true);
  } else {
    $("#check_all").prop("checked", false);
  }
});

$("#tp_check").change(function() {
  if($("#privacy_check").is(":checked") == true && $("#tp_check").is(":checked") == true && $("#cancel_check").is(":checked") == true) {
    $("#check_all").prop("checked", true);
  } else {
    $("#check_all").prop("checked", false);
  }
});



function fbuyform_submit(f){

  if ($("#r_name").val().length < 1 || $("#r_phone").val().length < 10) {
    alert("예약자 정보를 입력해주세요.");
    $("#Modal1").modal('show');
    return false;
  }
  if ($("#cancel_check").is(":checked") == false ) {
    alert("약관을 모두 동의해주세요.");
    return false;
  }

  if ($("input:radio[name=pay_method]:checked").val() == "간편신용카드") {
    alert("죄송합니다. 현재 간편신용카드는 이용하실 수 없습니다. \n다른결제수단을 이용해주세요.");
    return false;
    // $("#Modal6").modal('show');
    // return false;
    //
    // var mb_id = $('#card_chk_id').val();
    // var input_pw = $('#card_chk_pass').val();
    // var state = 0;
    //
    // $.ajax({
    //   url: "./ajax.cb_card_passwd.php",
    //   type: "POST",
    //   // dataType:"json",
    //   async: false,
    //   data: { input_pw:input_pw, mb_id:mb_id },
    //   success:function(data) {
    //     state = data;
    //
    //   },
    //   error:function(error) {
    //      alert("오류");
    //   }
    // });
    //
    // if (state == 0 ){
    //   alert("로그인 비밀번호가 틀렸습니다.");
    //   return;
    // }
  }



  if(window.MyApp && window.MyApp.callMessage){
    // 해당 브라우저에 MyApp이라는 브릿지가 있고 그 브릿지에 callMessage라는 함수가 있는 경우 호출
    // CARD EBANK EPAY
    var PayMethod = $("#PayMethod").val();
    var Moid = $("#Moid").val();
    var GoodsName = $("#GoodsName").val();
    var Amt = parseInt(f.tot_price.value.replace(/,/g, ''));
    var DutyFreeAmt = $("#DutyFreeAmt").val();
    var GoodsCnt = $("#GoodsCnt").val();
    var BuyerName = $("#BuyerName").val();
    var MallUserID = $("#MallUserID").val();
    var BuyerTel = $("#BuyerTel").val();
    var BuyerEmail = $("#BuyerEmail").val();


    // 카드 & 간편계좌 & 통합페이

      if (PayMethod == "CARD" || PayMethod == "BANK" || PayMethod == "EPAY" ) {

          var obj = { Moid : Moid, GoodsName : GoodsName, Amt : Amt
                      , DutyFreeAmt : DutyFreeAmt, PayMethod : PayMethod
                      , GoodsCnt : GoodsCnt, BuyerName : BuyerName, MallUserID : MallUserID
                      , BuyerTel : BuyerTel, BuyerEmail : BuyerEmail };
          var str = JSON.stringify(obj);

          window.MyApp.getJsoneData(str);

          return false;
      }else {
        return true;
      }
  }else{

    return true;
  }

/*
  var min_point	 = parseInt("100");
  var temp_point   = parseInt(no_comma(f.use_point.value));

  var tot_price   = parseInt(f.tot_price.value);
  var mb_point     = parseInt(f.mb_point.value);


  if(f.use_point.value == '') {
  alert('포인트 사용금액을 입력하세요. 사용을 원치 않을경우 0을 입력하세요.');
  f.use_point.value = 0;
  f.use_point.focus();
  return false;
  }

  if(temp_point > 0 && temp_point > mb_point) {
  alert('포인트 사용금액은 현재 보유포인트 보다 클수 없습니다.');
  f.tot_price.value = number_format(String(tot_price));
  f.use_point.value = 0;
  f.use_point.focus();
  return false;
  }

  if(temp_point > 0 && temp_point > tot_price) {
  alert('포인트 사용금액은 최종결제금액 보다 클수 없습니다.');
  f.tot_price.value = number_format(String(tot_price));
  f.use_point.value = 0;
  f.use_point.focus();
  return false;
  }

  if(temp_point > 0 && (mb_point < min_point)) {
  alert('포인트 사용금액은 '+number_format(String(min_point))+'원 부터 사용가능 합니다.');
  f.tot_price.value = number_format(String(tot_price));
  f.use_point.value = 0;
  f.use_point.focus();
  return false;
  }
  */
  var tot_price   = parseInt(f.tot_price.value);
  return point_chk(tot_price);
}
//앱에서 결제 완료 후 서브밋을 함.
function app_submmit(){
  var bay_form = document.buyform;
  bay_form.submit();
}

/*
function doMessage(){
  if(window.MyApp && window.MyApp.callMessage){
    // 해당 브라우저에 MyApp이라는 브릿지가 있고 그 브릿지에 callMessage라는 함수가 있는 경우 호출
    window.MyApp.callMessage();
  }else{
    alert("해당 기능은 어플에서만 이용 가능합니다.");
  }
}
*/
function callback(message){
  alert(message);
}




// 이전페이지로 이동 버튼클릭시 이전페이지 리로딩
$(".top_side #referrer").click(function() {
  location.href=document.referrer;
});

// 간편카드 추가
$("div input").keyup(function(e) {
  if($("#card_1").val().length == 4 && $("#card_2").val().length == 4 && $("#card_3").val().length == 4 && $("#card_4").val().length == 4
      && $("#date_yy").val().length == 2 && $("#date_mm").val().length == 2 && $("#card_pw").val().length == 2
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

function card_form_submit(f)
{

  if ($("#card_birth").val() == "" ) {
    alert("생년월일 정보가 필요합니다. 마이욜로 페이지 -> 설정 -> 내정보 에서 생년월일을 등록해주시기 바랍니다.");
    return false;
  }
  if (!($("#card_birth").val().length == 6) ) {
		alert("잘못된 생년월일입니다. 6자리로 등록하여 주십시오..");
    return false;
  }

  var mb_id = $('#mb_id').val();
  var input_pw = $('#input_pw').val();
  var state = 0;

  $.ajax({
    url: "./ajax.cb_card_passwd.php",
    type: "POST",
    // dataType:"json",
    async: false,
    data: { input_pw:input_pw, mb_id:mb_id },
    success:function(data) {
      state = data;

    },
    error:function(error) {
       alert("오류");
    }
  });

  if (state == 1){

    var card_data = $("#card_form").serialize();

    $.ajax({
      url: "./ajax.cb_card_update.php",
      type: "POST",
      dataType:"json",
      async: false,
      data: card_data,
      success:function(data) {
        console.log(data);
      },
      error:function(error) {
         alert("오류");
      }
    });


    document.getElementById("btn_submit").disabled = "disabled";
      //return true;
  } else if (state == 0 ){
    alert("로그인 비밀번호가 틀렸습니다.");
      return false;
  }

}
// 간편카드 추가 끝



</script>
