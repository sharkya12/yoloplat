<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가


//전체리스트 카운트세기
$sql = " select count(*) as cnt
        FROM hi_order
        WHERE mb_id= '{$member['id']}'";
$result = sql_fetch($sql);
$total_count = $result['cnt'];

$rows = 5;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select a.index_no as a_index_no, a.company_name as a_company_name
        ,b.company_img as b_company_img, c.company_img as c_company_img, d.company_img as d_company_img
        ,b.company_addr1 as b_company_addr, c.company_addr1 as c_company_addr, d.company_addr1 as d_company_addr
        ,a.*, b.*, c.*, d.*
        FROM hi_order AS a
        LEFT JOIN hi_room AS b ON a.seller_id = b.mb_id
        LEFT JOIN hi_rent AS c ON a.seller_id = c.mb_id
        LEFT JOIN hi_exp AS d ON a.seller_id = d.mb_id
        LEFT JOIN hi_goods AS e on a.gs_id = e.index_no
        WHERE a.mb_id = '{$mb_id}'
        ORDER BY a.od_time desc
        limit $from_record, $rows";

$order_res = sql_query($sql);


?>
<input type="hidden" id="total_count" value="<?php echo $total_count; ?>" />
<input type="hidden" id="page" value="<?php echo $page; ?>" />
<input type="hidden" id="mb_id" value="<?php echo $mb_id; ?>" />

<section class="rsrv_list_section">
  <section class="rsrv_list_nav_section">
    <button type="button" onclick="history.back();"><i class="fas fa-chevron-left"></i></button>
  </section>
  <h1>이용내역</h1>

<?php if($member['id']){?>
<?php if ($total_count == 0 ) {?>

  <!-- 예약내역이 없을경우 출력 -->
  <div class="info_none">
    <img src="../img/calendar.png" />
    <h2>이용 내역이 없습니다.</h2>
    <small>지금 바로 여행 상품들을 국내 최저가로 예약해보세요.</small>
    <button type="button" onclick="location.href='<?php echo TB_MSHOP_URL; ?>/carbang_list.php?cb_type=HT';">예약 하기</button>
  </div>

<?php } else { ?>
  <div class="pa16">
  <?php
  //  예약내역이 있을 경우 출력
  // for ($i=0; $i < 10; $i++) {
  //   $row = sql_fetch_array($order_res);

  //   echo '-테스트 : '.$row['a_company_name'];
  // }
    for($i=1; $row = sql_fetch_array($order_res); $i++) {
        $in_yoil =  get_yoil($row['in_date']);
        $out_yoil =  get_yoil($row['out_date']);


        //체크인 월계산
        $in_month = substr($row['in_date'], 5, 2);
        if ($in_month < 10) {
          $in_month = str_replace('0','',$in_month);
        }

        //체크아웃 월계산
        $out_month = substr($row['out_date'], 5, 2);
        if ($out_month < 10) {
          $out_month = str_replace('0','',$out_month);
        }

        $in_year = substr($row['in_date'], 2, 2);
        $out_year = substr($row['out_date'], 2, 2);

        $str_in_date = $in_year.". ".$in_month.". ".substr($row['in_date'], 8, 2)." (".$in_yoil.") ";
        $str_out_date = $out_year.". ".$out_month.". ".substr($row['out_date'], 8, 2)." (".$out_yoil.") ";

        $str_chk_in = substr($row['in_date'], 11, 5);
        $str_chk_out = substr($row['out_date'], 11, 5);


        //리뷰 작성 남은일 구하기
        $timenow = date("Y-m-d");
        $chk_out_time = date($row['out_date']);

        $str_now = strtotime($timenow);
        $str_target = strtotime($chk_out_time."+10 days");

        $d_day_calc = date($str_target - $str_now);
        $d_day = ceil($d_day_calc / (60*60*24));

  ?>
  <input type="hidden" id="index_no<?php echo $i; ?>" value="<?php echo $row['a_index_no']; ?>">

    <div class="table_div shadow_box">
      <table>
        <tr>
          <td class="img_td">
              <?php if($row['goods_ca'] == "R") { ?>
                <?php if(!$row['b_company_img']) { ?>
                  <div style="background-image: url('../img/image_none.png')"></div>
                <?php } else { ?>
                  <div style="background-image: url('../../data/room/<?php echo $row['b_company_img']; ?>');"></div>
                <?php } ?>
              <?php } else if($row['goods_ca'] == "C") { ?>
                <?php if(!$row['c_company_img']) { ?>
                  <div style="background-image: url('../img/image_none.png')"></div>
                <?php } else { ?>
                  <div style="background-image: url('../../data/rent/<?php echo $row['c_company_img']; ?>');"></div>
                <?php } ?>
              <?php } else if($row['goods_ca'] == "E") { ?>
                <?php if(!$row['d_company_img']) { ?>
                  <div style="background-image: url('../img/image_none.png')"></div>
                <?php } else { ?>
                  <div style="background-image: url('../../data/exp/<?php echo $row['d_company_img']; ?>');"></div>
                <?php } ?>
              <?php } ?>
          </td>
        </tr>
        <tr>
          <td>
            <div class="rsrv_list_info">
              <div class="auto_side reservation_state">
              <?php if ($row['goods_ca'] == "R") { ?>
                <?php if ($row['dan'] == 3) { ?>
                  <b>예약대기 숙소</b>
                <?php } else if ($row['dan'] == 4) { ?>
                  <b>체크인(예정) 숙소</b>
                <?php } else if ($row['dan'] == 5) { ?>
                  <b>체크아웃 완료 숙소</b>
                <?php } else if ($row['dan'] == 6 || $row['dan'] == 9) { ?>
                  <b>예약 취소된 숙소</b>
                <?php } ?>
              <?php } ?>
                <div class="review_btn_div">
                  <?php if ($d_day > 0) { ?>
                  <button type="button" class="review_open" id="review_open<?php echo $i; ?>"><i class="fas fa-pen"></i>리뷰작성</button>
                  <p><i class="fas fa-stopwatch"></i>리뷰 작성기간이 <?php echo $d_day; ?>일 남았어요!</p>
                  <?php } ?>
                </div>
              </div>
              <div class="auto_side">
                <div>
                  <h3><?php echo $row['a_company_name'];?></h3>
                  <h5><?php echo $row['gname'];?></h5>
                </div>
                <!-- <div class="map_icon">
                  <span data-toggle="modal" data-target="#Modal<?php echo $i; ?>" class="modal_open" id="modal_open<?php echo $i; ?>">
                    <p><img src="../img/map_icon2.png" /></p>
                  </span>
                </div> -->
              </div>
              <div class="rsrv_list_check auto_side">
                <span>
                  <small>체크인</small>
                  <p><?php echo $str_in_date; ?> <?php echo $str_chk_in; ?></p>
                </span>
                <span>
                  <small>체크아웃</small>
                  <p><?php echo $str_out_date; ?> <?php echo $str_chk_out; ?></p>
                </span>
                <!-- <span>
                  <?php if ($d_day > 0) { ?>
                    <button type="button" class="review_open" id="review_open<?php echo $i; ?>">리뷰작성<small style="display:block">(<?php echo $d_day; ?>일 남음)</small></button>
                  <?php } ?>
                  <p onclick="location.href='cb_reservation_info.php?index_no=<?php echo $row['a_index_no'];?>';">자세히보기 <img src="../img/chevron-small-next-y.png" /></p>
                </span> -->
              </div>
            </div>
            <div class="read_more_btn">
              <button type="button" onclick="location.href='cb_reservation_info.php?index_no=<?php echo $row['a_index_no'];?>';">자세히 보기 <i class="fas fa-arrow-right"></i></a>
            </div>
          </td>
        </tr>
      </table>
      <!-- <div class="road_btn_div">
        <button type="button" data-toggle="modal" data-target="#Modal" id="modal_open">길찾기</button>
      </div> -->
    </div>



  <div class="cbre_container">
  <!-- Modal -->
  <div class="cbre_modal">
    <div class="modal fade modal" id="Modal<?php echo $i; ?>" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-body">
            <div class="top_button_div">
              <div class="cancel_icon" data-dismiss="modal"><img src="../img/close_btn.png" width="15px"></div>
              <h3>제휴점 위치 보기</h3>
              <span> </span>
            </div>
            <div id="map<?php echo $i; ?>" style="width:100%;height:450px;"></div>
            <div class="cbre_modal_info">
              <div class="modal_info_between">
                <span>
                  <?php if($row['goods_ca'] == "R") { ?>
                    <h5><?php echo $row['a_company_name'];?></h5>
                    <small id="addr_text<?php echo $i; ?>"><?php echo $row['b_company_addr'];?></small>
                  <?php } else if($row['goods_ca'] == "C") { ?>
                    <h5><?php echo $row['a_company_name'];?></h5>
                    <small id="addr_text<?php echo $i; ?>"><?php echo $row['c_company_addr'];?></small>
                  <?php } else if($row['goods_ca'] == "E") { ?>
                    <h5><?php echo $row['a_company_name'];?></h5>
                    <small id="addr_text<?php echo $i; ?>"><?php echo $row['d_company_addr'];?></small>
                  <?php } ?>
                </span>
                <span class="copy_img">
                  <img src="../img/copy.png" width="15px" />
                </span>
              </div>
              <div>
                <button type="button">지도앱으로 길찾기</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal end -->

  </div>
<?php }
}?>
</div>
<?php if($total_page > $page) { ?>
  <div class="view_more_div">
    <button class="view_more" id="view_more" type="button" >더보기</button>
  </div>
<?php } ?>
<?php } else { ?>
  <div class="info_none">
    <img src="../img/exit.png" />
    <h2>로그인 후 이용해주세요</h2>
    <small>로그인 하시면 다양한 정보를 확인할 수 있습니다.</small>
    <button type="button" onclick="location.href='<?php echo TB_MSHOP_URL; ?>/cb_login.php';">로그인</button>
  </div>
<?php } ?>

  <div class="review_write_modal">
    <div class="modal fade modal" id="review_modal" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-body">
            <div class="review_write_container">
              <form name="review_form" id="review_form" onsubmit="return review_submit(this);" method="post" autocomplete="off">
                <input type="hidden" name="w" value="" />
                <input type="hidden" id="re_mb_id" name="mb_id" value="" />
                <input type="hidden" id="re_seller_id" name="seller_id" value="" />
                <input type="hidden" id="re_gs_id" name="gs_id" value="" />

                <section class="review_wirte_nav">
                  <i class="fas fa-times"  data-dismiss="modal"></i>
                </section>
                <h1>리뷰작성</h1>
                <section class="review_company_info">
                  <div class="auto_side">
                    <div class="img" id="review_img" style="background-image: url('../img/hotel_img.jpg');"></div>
                    <div class="info">
                      <div class="auto_side">
                        <span>예약정보</span>
                        <span id="review_reservation_ifno">숙박</span>
                      </div>
                      <div class="auto_side">
                        <span>업체</span>
                        <span id="review_seller_name">욜로호텔</span>
                      </div>
                      <div class="auto_side">
                        <span>객실</span>
                        <span id="review_goods_name">디럭스</span>
                      </div>
                    </div>
                  </div>
                </section>
                <h2>만족도를 평가해주세요 :)</h2>
                <section class="review_score_section auto_side">
                  <input type="hidden" name="review_score" id="reivew_score" value="" />
                  <button type="button" class="score_btn" id="score_btn1"><i class='fas fa-star'></i></button>
                  <button type="button" class="score_btn" id="score_btn2"><i class='fas fa-star'></i></button>
                  <button type="button" class="score_btn" id="score_btn3"><i class='fas fa-star'></i></button>
                  <button type="button" class="score_btn" id="score_btn4"><i class='fas fa-star'></i></button>
                  <button type="button" class="score_btn" id="score_btn5"><i class='fas fa-star'></i></button>
                </section>
                <section class="review_content_section">
                  <textarea name="review_content" placeholder="후기를 입력해주세요."></textarea>
                </section>

                <section class="review_photo_section">
                  <button type="button">
                    <i class="far fa-images"></i>
                    <div>+ 사진 추가</div>
                  </button>
                </section>
                <button type="submit" class="review_write_btn">리뷰 등록</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  $("section .score_btn").click(function() {
    var this_num = Number($(this).attr('id').replace(/[^0-9]/g,''));
    for(var i=1;i<=this_num;i++) {
      $("#score_btn"+i+" i").css("color","#FCB608");
    }
    for(var y=this_num+1;y<=5;y++) {
      $("#score_btn"+y+" i").css("color","#BBBBBB");
    }
    console.log(this_num);
    $("#review_score").val(this_num);
  });
</script>

  <!-- Modal Popup Script -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <!-- Modal Popup Script -->

  <script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=17aab66598b882a5494494233fef7048&libraries=services"></script>

  <script>

  $(document).ready(function() {

    var _outerHeight = window.outerHeight;
    var nav_h = (document.querySelector('.rsrv_list_nav_section').offsetHeight);

    $(".info_none").attr("style","height:"+(_outerHeight-nav_h)+"px; padding: "+((_outerHeight)/4)+"px 16px 0;");


  });


//더보기버튼 추가되는 줄 함수
function call_reservation_list (dan, goods_ca, b_company_img, c_company_img, d_company_img, num, a_index_no, a_company_name, b_company_addr, c_company_addr, d_company_addr, gname, str_in_date, str_chk_in, str_out_date, str_chk_out, d_day) {

  //이미지 파일 (필요한 파라미터 값 goods_ca, b_company_img, c_company_img, d_company_img)
  if (goods_ca == "R") {
    if (b_company_img) {
      var img_td = '<div style="background-image: url(\'../../data/room/'+b_company_img+'\');"></div>';
    } else {
      var img_td = '<div style="background-image: url(\'../img/image_none.png\');"></div>';
    }
  } else if (goods_ca == "C") {
    if (c_company_img) {
      var img_td = '<div style="background-image: url(\'../../data/rent/'+c_company_img+'\');"></div>';
    } else {
      var img_td = '<div style="background-image: url(\'../img/image_none.png\');"></div>';
    }
  } else if (goods_ca == "E") {
    if (d_company_img) {
      var img_td = '<div style="background-image: url(\'../../data/exp/'+d_company_img+'\');"></div>';
    } else {
      var img_td = '<div style="background-image: url(\'../img/image_none.png\');"></div>';
    }
  }

  //예약현황 단계(숙박만)
  if (goods_ca == "R") {
    if (dan == 3) {
      var rsrv_dan = '<b>예약대기 숙소</b>';
    } else if(dan == 4) {
      var rsrv_dan = '<b>체크인(예정) 숙소</b>';
    } else if(dan == 5) {
      var rsrv_dan = '<b>체크아웃 완료 숙소</b>';
    } else if(dan == 6 || dan == 9) {
      var rsrv_dan = '<b>예약 취소된 숙소</b>';
    } else {
      var rsrv_dan = '';
    }
  } else {
    var rsrv_dan = '';
  }

  //위치 지도보기
  var map_icon =  '<div class="map_icon">'+
                    '<span data-toggle="modal" data-target="#Modal'+num+'" class="modal_open" id="modal_open'+num+'">'+
                      '<p><img src="../img/map_icon2.png" /></p>'+
                    '</span>'+
                  '</div>';

  //리뷰
  if(d_day > 0) {
    var review_button = '<button type="button" class="review_open" id="review_open'+num+'"><i class="fas fa-pen"></i>리뷰작성</button>'+
                        '<p><i class="fas fa-stopwatch"></i>리뷰 작성기간이 '+d_day+'일 남았어요!</p>';
    // '<button type="button" data-toggle="modal" data-target="#review_modal" class="review_open" id="review_open'+num+'">리뷰작성<small style="display:block">('+d_day+'일 남음)</small></button>';
  } else {
    var review_button = '';
  }

  //자세히보기
  var learn_more = '<div class="read_more_btn">' +
                  '<button type="button" onclick="location.href=\'cb_reservation_info.php?index_no='+a_index_no+'\';">자세히 보기 <i class="fas fa-arrow-right"></i></a>' +
                  '</div>';
// '<p onclick="location.href=\'cb_reservation_info.php?index_no='+a_index_no+'\';">자세히보기 <img src="../img/chevron-small-next-y.png" /></p>';
  if (goods_ca == "R") {
    var company_info =  '<span>'+
                          '<h5>'+a_company_name+'</h5>'+
                          '<small id="addr_text'+num+'">'+b_company_addr+'</small>'+
                        '</span>';

  } else if(goods_ca == "C") {
    var company_info =  '<span>'+
                          '<h5>'+a_company_name+'</h5>'+
                          '<small id="addr_text'+num+'">'+c_company_addr+'</small>'+
                        '</span>';

  } else if(goods_ca == "E") {
    var company_info =  '<span>'+
                          '<h5>'+a_company_name+'</h5>'+
                          '<small id="addr_text'+num+'">'+d_company_addr+'</small>'+
                        '</span>';
  }

  //이용내역 추가되는 태그값
  var result_tag =  '<input type="hidden" id="index_no'+num+'" value="'+a_index_no+'"/>'+
                    '<div class="table_div shadow_box">'+
                      '<table>'+
                        '<tr>'+
                          '<td class="img_td">'+img_td+'</td>'+
                        '</tr>'+
                        '<tr>'+
                          '<td>'+
                            '<div class="rsrv_list_info">'+
                              '<div class="auto_side reservation_state">'+
                                rsrv_dan+
                                '<div class="review_btn_div">'+
                                review_button+
                                '</div>'+
                              '</div>'+
                              '<div class="auto_side">'+
                                '<div>'+
                                  // '<h3>'+a_index_no+'</h3>'+
                                  '<h3>'+a_company_name+'</h3>'+
                                  '<h5>'+gname+'</h5>'+
                                '</div>'+
                                // map_icon+
                              '</div>'+
                              '<div class="rsrv_list_check auto_side">'+
                                '<span>'+
                                  '<small>체크인</small>'+
                                  '<p>'+str_in_date+str_chk_in+'</p>'+
                                '</span>'+
                                '<span>'+
                                  '<small>체크아웃</small>'+
                                  '<p>'+str_out_date+str_chk_out+'</p>'+
                                '</span>'+
                              '</div>'+
                            '</div>'+
                            learn_more+
                          '</td>'+
                        '</tr>'+
                      '</table>'+
                    '</div>'+

                    '<div class="cbre_container">'+
                      //Modal
                      '<div class="cbre_modal">'+
                        '<div class="modal fade modal" id="Modal'+num+'" role="dialog">'+
                          '<div class="modal-dialog">'+

                            //Modal content
                            '<div class="modal-content">'+
                              '<div class="modal-body">'+
                                '<div class="top_button_div">'+
                                  '<div class="cancel_icon" data-dismiss="modal"><img src="../img/close_btn.png" width="15px"></div>'+
                                  '<h3>제휴점 위치 보기</h3>'+
                                  '<span> </span>'+
                                '</div>'+
                                '<div id="map'+num+'" style="width:100%;height:450px;"></div>'+
                                '<div class="cbre_modal_info">'+
                                  '<div class="modal_info_between">'+company_info+
                                    '<span class="copy_img">'+
                                      '<img src="../img/copy.png" width="15px" />'+
                                    '</span>'+
                                  '</div>'+
                                  '<div>'+
                                    '<button type="button">지도앱으로 길찾기</button>'+
                                  '</div>'+
                                '</div>'+
                              '</div>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</div>'+
                      // Modal end
                    '</div>';

    return result_tag;
}


// 더보기 버튼 클릭시 이벤트
$(document).on('click', '#view_more', function(){
  var total_count = $("#total_count").val();
  var page = $("#page").val();
  var mb_id = $("#mb_id").val();

  $.ajax({
    url: "./cb_reservation_list_ajax.php",
    type: "POST",
    dataType:"json",
    async: false,
    data: { total_count:total_count, page:page, mb_id:mb_id },
    success:function(data) {
      console.log(data);
      //리스트 데이터가 있으면
      if(data.code == '200') {
        var total_page = data.total_page;
        var page = data.page;
        $("#page").val(page);

        for(var i=0; i<data.data.length; i++) {
          var num = data.data[i].num;
          var dan = data.data[i].dan;
          var goods_ca = data.data[i].goods_ca;
          var b_company_img = data.data[i].b_company_img;
          var c_company_img = data.data[i].c_company_img;
          var d_company_img = data.data[i].d_company_img;
          var a_index_no = data.data[i].a_index_no;
          var a_company_name = data.data[i].a_company_name;
          var b_company_addr = data.data[i].b_company_addr;
          var c_company_addr = data.data[i].c_company_addr;
          var d_company_addr = data.data[i].d_company_addr;
          var gname = data.data[i].gname;
          var str_in_date = data.data[i].str_in_date;
          var str_chk_in = data.data[i].str_chk_in;
          var str_out_date = data.data[i].str_out_date;
          var str_chk_out = data.data[i].str_chk_out;
          var d_day = data.data[i].d_day;

          var addition_tag = call_reservation_list(dan, goods_ca, b_company_img, c_company_img, d_company_img, num, a_index_no, a_company_name, b_company_addr, c_company_addr, d_company_addr, gname, str_in_date, str_chk_in, str_out_date, str_chk_out, d_day);

          //더보기버튼 삭제
          $(".view_more").remove();
          //새로운 더보기버튼 생성
          if (total_page > page) {
            var view_more = '<button class="view_more" id="view_more" type="button" >더보기</button>';
            $(".view_more_div").append(view_more);
          }
          // 5줄씩 추가
          $(".pa16").append(addition_tag);

          console.log('hi');
        }
      }
      //리스트 데이터가 없으면
      else if(data.code == '999') {

      }

    },
    error:function(error) {
       alert("오류");
    }
  });

});


//지도보기 클릭시 이벤트
$(document).on('click', '.modal_open', function() {
  var num_val = $(this).attr('id').replace(/[^0-9]/g,"");
  var addr = $('#addr_text'+num_val).text();
  console.log(addr);
  // 카카오맵 start
  var mapContainer = document.getElementById('map'+num_val), // 지도를 표시할 div
      mapOption = {
          center: new kakao.maps.LatLng(35.1602722655459, 129.16515704429708), // 지도의 중심좌표
          level: 6 // 지도의 확대 레벨
      };

  var map = new kakao.maps.Map(mapContainer, mapOption); // 지도를 생성합니다

    // $(".modal").on('show.bs.modal', function(e) {

      console.log(num_val);
      // 주소-좌표 변환 객체를 생성합니다
      var geocoder = new kakao.maps.services.Geocoder();

      // 주소로 좌표를 검색합니다

      geocoder.addressSearch(addr, function(result, status) {

        // 정상적으로 검색이 완료됐으면
         if (status === kakao.maps.services.Status.OK) {

            var coords = new kakao.maps.LatLng(result[0].y, result[0].x);

            // 결과값으로 받은 위치를 마커로 표시합니다
            var marker = new kakao.maps.Marker({
                map: map,
                position: coords
            });

            // 인포윈도우로 장소에 대한 설명을 표시합니다
            // var infowindow = new kakao.maps.InfoWindow({
            //     content: '<div style="width:150px;text-align:center;padding:6px 0;">우리회사</div>'
            // });
            // infowindow.open(map, marker);

            // 지도의 중심을 결과값으로 받은 위치로 이동시킵니다
            // map.setCenter(coords);
        }
        setTimeout(function() {
          map.relayout();
          map.setCenter(coords);
        },400);
    });
  });

  // 카카오맵 end

  //리뷰작성 modal
  $(document).on('click', 'div .review_open', function(){

    var review_num = $(this).attr('id').replace(/[^0-9]/g,"");
    var review_index = $('#index_no'+review_num).val();
    var mb_id = $("#mb_id").val();

    $.ajax({
      url: "<?php echo TB_MAPP_URL; ?>/reservation_list_review.ajax.php",
      type: "POST",
      dataType:"json",
      async: false,
      data: { review_index:review_index, mb_id:mb_id },
      success:function(data) {

        var goods_ca = data.goods_ca;
        var a_company_name = data.a_company_name;
        var gname = data.gname;
        var review_image = data.image;
        var seller_id = data.seller_id;
        var gs_id = data.gs_id;

        var test = "url('/data/goods/"+review_image+"')";
        console.log(test);
        //리뷰작성시 필요한 값
        $('#re_mb_id').val(mb_id);
        $('#re_seller_id').val(seller_id);
        $('#re_gs_id').val(gs_id);


        //이미지 변경
        // $('#review_img').style.backgroundImage = test;

        if (goods_ca == "R") {
          $('#review_reservation_ifno').text('숙박');
        } else if (goods_ca == "C") {
          $('#review_reservation_ifno').text('렌트');
        } else if (goods_ca == "E") {
          $('#review_reservation_ifno').text('체험');
        }

        $('#review_seller_name').text(a_company_name);
        $('#review_goods_name').text(gname);

      },
      error:function(error) {
         alert("오류");
      }
    });


    $('#review_modal').modal('show');
  });

  $(document).on('click', '.review_write_btn', function(){

    var review_data = $("#review_form").serialize();
    console.log(review_data);
    $.ajax({
      url: "<?php echo TB_MAPP_URL; ?>/review_update.ajax.php",
      type: "POST",
      dataType:"json",
      async: false,
      data: review_data,
      success:function(data) {
        console.log(data);
      },
      error:function(request, error) {
         // alert("오류");
         alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);

      }
    });

    // alert("리뷰가 정상적으로 등록되었습니다.")

  });
  </script>
