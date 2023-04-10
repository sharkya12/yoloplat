<?php

if($_GET['type'] == 'KP') {
  $type = 'KP';
} else if ($_GET['type'] == 'room') {
  $type = 'room';
} else if ($_GET['type'] == 'RT') {
  $type = 'RT';
} else if ($_GET['type'] == 'EX') {
  $type = 'EX';
}

//인기 검색어
$sql = " select search_text, search_count from hi_search_log where type = '$type' group by search_text ORDER BY search_count desc, search_text limit 10";
$hot_result = sql_query($sql);

//최근 검색어
$sql = " select search_text, search_time from hi_search_log where mb_id = '$member[id]' and type = '$type' group by search_text order by search_time desc limit 10";
$recent_result = sql_query($sql);

?>
  <section class="top_tit_section">
    <div class="top_nav auto_side">
      <div class="btn_div_left" onclick="history.back()">
        <i class="fas fa-chevron-left"></i>
      </div>
    </div>
  </section>

  <section class="list_head_select_section">
    <h1 class="list_tit">
      통합검색
    </h1>
  </section>

  <div class="search_cate_btn">
    <button type="button" id="RO"<?php if($_GET['type'] == 'room') { echo 'class="selected"'; } ?>>숙소</button>
    <button type="button" id="KP" <?php if($_GET['type'] == 'KP') { echo 'class="selected"'; } ?>>캠핑카</button>
    <button type="button" id="RT"<?php if($_GET['type'] == 'RT') { echo 'class="selected"'; } ?>>렌트카</button>
    <button type="button" id="EX"<?php if($_GET['type'] == 'EX') { echo 'class="selected"'; } ?>>체험</button>
  </div>

<section class="cb_search_section">
  <!-- <div class="search_nav">
    <div>
      <h1>통합검색</h1>
    </div>
    <button type="button" onclick="history.back();"><i class="fas fa-chevron-left"></i></button>
  </div> -->

  <div class="cb_search_input_div">
    <input type="hidden" id="id" value="<?php echo $member[id]?>">
    <input type="hidden" id="type" value="<?php echo $_GET['type']; ?>">
    <input type="text" id="keyword" class="shadow_box" placeholder="지역, 명소, 업체명을 검색해보세요.">
    <button type="button"><img src="../img/search_icon.png" /></button>
  </div>

  <div class="room_daysel_div auto_side">
    <div class="calendar_sel_btn"  data-toggle="modal" data-target="#date_sel_modal">
      <small>체크인 - 체크아웃</small>
      <h4 id="check_d"><?php echo $str_in_date; ?> - <?php echo $str_out_date; ?></h4>
      <input type="hidden" id="chk_in_time" value="<?php echo $str_in_date; ?>" >
      <input type="hidden" id="chk_out_time" value="<?php echo $str_out_date; ?>" >
    </div>
    <div>
      <!-- <small>연박</small>
      <h4 id="check_n"><?php echo $interval; ?>박</h4> -->
    </div>
    <div data-toggle="modal" data-target="#nb_ppl_modal">
      <small>인원</small>
      <h4 id="sel_ppl">성인 <?php echo $adult_num; ?> / 아동 <?php echo $children_num; ?></h4>
      <input type="hidden" id="adult_num" value="<?php echo $adult_num; ?>" >
      <input type="hidden" id="children_num" value="<?php echo $children_num; ?>" >
      <input type="hidden" id="integrated_search" value="<?php echo $integrated_search; ?>">
    </div>
  </div>



  <!-- <div class="cb_dateset">
    <button type="button" onclick="location.href='<?php echo TB_MSHOP_URL; ?>/cb_region_select.php?type=<?php echo $type;?>';"><img src="../img/location_icon.png" /><?php if($catecode == null || $catecode == '') {?>서울<?php } else {?><?php echo $catename; }?></button>
    <button type="button" onclick="location.href='<?php echo TB_MSHOP_URL; ?>/reservation_calendar.php?page=main&type=<?php echo $type;?>';"><img src="../img/calendar_icon.png" /><?php echo $str_in_date." - ".$str_out_date;?> (<?php echo $interval;?>)</button>
  </div> -->

  <div class="cb_search_btn around_side">
    <button type="button" class="hot_keyword">인기 검색</button>
    <button type="button" class="lately_keyword">최근 검색</button>
  </div>

  <!-- 인기 검색어 -->
  <div class="cb_search_hot">
    <ul>
      <?php if(sql_num_rows($hot_result) == 0) { ?>
      <li>
        <span>최근 인기 검색어가 없습니다.</span>
      </li>
    <?php } else {
        for($i=0; $row_hot=sql_fetch_array($hot_result); $i++) {
      ?>
      <!-- ./carbang_list.php?search=Y&type="+type+"&keyword="+keyword -->
      <li onclick="add_log('<?php echo $row_hot['search_text'];?>')">
        <span class="csh_num"><?php echo $i+1 ?></span><span class="csh_keyword"><?php echo $row_hot['search_text']; ?></span>
      </li>
    <?php } }?>
    </ul>
    <!-- <div class="search_date">2021. 03. 09 23:00 기준</div> -->
  </div>

  <!-- 최근 검색어 -->
  <div class="cb_lately_search">
    <div class="lately_search_all_remove">
      <?php if($member['id']) {
      echo '<button type="button">전체삭제</button>';
      } ?>
    </div>
    <ul>
        <?php if($member['id']){
            if(sql_num_rows($recent_result) == 0) {
        ?>
            <li>
              <span class="csh_lately_keword">최근 검색어가 없습니다.</span>
            </li>
        <?php }
        else {
            for($i=0; $row_recent=sql_fetch_array($recent_result); $i++){
        ?>
          <li class="auto_side">
            <span class="csh_keyword" onclick="add_log('<?php echo $row_recent['search_text'];?>')"><?php echo $row_recent['search_text'];?></span>
            <button type="button"><i class="fas fa-times"></i></button>
          </li>
        <?php }
      }
        } else {?>
            <li>
              <span class="csh_lately_keword">로그인이 필요한 기능입니다.</span>
            </li>
        <?php }?>
    </ul>
  </div>

</section>



<script>
  $(document).ready(function() {

    //업소유형별로 인기검색어 최근검색어 불러오기
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
    add_search_word(type);


    //검색어를 입력하고 enter를 눌렸을시
    $('#keyword').on("keyup", function(key){
      if(key.keyCode == 13) {
        add_log($('#keyword').val());
      }
    });

    $(".hot_keyword").click(function() {
      $(".hot_keyword").attr("style","border-bottom: 2px solid #0080FF; color: #000;");
      $(".lately_keyword").attr("style","color: rgb(0 0 0 / 0.4); border: 0;");
      $(".cb_search_hot").show();
      $(".cb_lately_search").hide();
    });

    $(".lately_keyword").click(function() {
      $(".lately_keyword").attr("style","border-bottom: 2px solid #0080FF; color: #000;");
      $(".hot_keyword").attr("style","color: rgb(0 0 0 / 0.4); border: 0;");
      $(".cb_search_hot").hide();
      $(".cb_lately_search").show();
    });
  });

  $(".search_cate_btn button").click(function() {
    var search_cate = $(this).attr('id');
    // $(".search_cate_btn button").attr("style", "color: #ABABAB; border-bottom: 0;");
    // $(this).attr("style", "color: #000000; border-bottom: 2px solid #000000;");
    //
    // if(search_cate != 'EX') {
    //   $(".cb_dateset").show();
    // } else {
    //   $(".cb_dateset").hide();
    // }
    //
    // add_search_word(search_cate);


  });

  if($("#type").val() == "EX") {
    $(".cb_dateset").hide();
  }

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
    if(keyword == null || keyword == '' || keyword == undefined){
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
    var chk_in_time = $('#chk_in_time').val();
    var chk_out_time = $('#chk_out_time').val();
    var adult_num = $('#adult_num').val();
    var children_num = $('#children_num').val();

    $.ajax({
      url:"./ajax.searchLog.php",
      type:"POST",
      data:{ id:id, keyword:keyword, type:type,chk_in_time:chk_in_time,chk_out_time:chk_out_time,adult_num:adult_num,children_num:children_num },
      success: function(data) {
          location.href="./list.php?search=Y&keyword="+keyword+"&type="+type;
      }
    });
  }

  //인기검색어 최근검색어 바꿔주는 ajax
  function add_search_word(search_cate) {
    //소메뉴에 따른 인기검색어 불러오기
    var id = $('#id').val();
    $('#type').val(search_cate);
    console.log(search_cate);
    $.ajax({
        url:"./search_hot.ajax.php",
        type:"POST",
        data: { "type": search_cate },
        success: function(data) {
          var json_file = JSON.parse(data);

          if(json_file.length > 0) {
            $('.cb_search_hot ul li').remove();

            for(var i=0; i<json_file.length; i++){
              var search_text = json_file[i].keyword;
              // var search_count = json_file[i].search_count;

              var result_tag = '<li onclick="add_log(\''+search_text+'\');"><span class="csh_num">'+(i+1)+'</span><span class="csh_keyword">'+search_text+'</span></li>';

              $('.cb_search_hot ul').append(result_tag);
            }
          } else if(json_file.length == 0) {
            var no_data = "<li><span>최근 인기 검색어가 없습니다.</span></li>";

            $('.cb_search_hot ul li').remove();
            $('.cb_search_hot ul').append(no_data);
          }

        },
        error: function(status) {
          // alert("오류");
        }
    });

    //소메뉴에 따른 최근검색어 불러오기
    //로그인되어있으면
    if(id){

      $.ajax({
          url:"./search_recent.ajax.php",
          type:"POST",
          data: { "id":id, "type": search_cate },
          success: function(data) {
            var json_file = JSON.parse(data);

            if(json_file.length > 0) {
              $('.cb_lately_search ul li').remove();

              for(var i=0; i<json_file.length; i++){
                var search_text = json_file[i].search_text;
                var search_count = json_file[i].search_count;

                var result_tag = '<li class="auto_side">'
                                  + '<span class="csh_keyword" onclick="add_log(\''+search_text+'\');">'+search_text+'</span>'
                                  + '<button type="button"><i class="fas fa-times"></i></button>'
                                  + '</li>';

                $('.cb_lately_search ul').append(result_tag);
              }
            } else if(json_file.length == 0) {
              var no_data = "<li><span>최근 검색어가 없습니다.</span></li>";

              $('.cb_lately_search ul li').remove();
              $('.cb_lately_search ul').append(no_data);
            }

          },
          error: function(status) {
            // alert("오류");
          }
      });
    }

  }

  //달력확인버튼 클릭시
  $('#cal_ok_btn').click(function() {

      var check_in = $("#check_in").val();
      var check_out = $("#check_out").val();
      //휴일체크
      var in_holiday = $("#str_in_holiday").val();
      var out_holiday = $("#str_out_holiday").val();


      $.ajax({
        url: "./search_date.ajax.php",
        type: "POST",
        // dataType:"json",
        async: false,
        data: { check_in:check_in, check_out:check_out, in_holiday:in_holiday, out_holiday:out_holiday},
        success:function(data) {
          // alert('성공');
        },
        error:function(error) {
           // alert("오류");
        }
      });

  });

</script>
