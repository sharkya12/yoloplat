<?php
  //지역 카테고리 가져오는 sql
  $sql = " select * from hi_category where length(catecode)='3' order by caterank, catecode ";
  $re_category = sql_query($sql);

  //서울지역 세부카테고리 가져오는 sql
  $sql_detail = " select * from hi_category where upcate = '001' order by caterank";
  $re_category_detail = sql_query($sql_detail);
?>

<section class="rs_section">
  <table class="top_tit_side_3">
    <td>
      <span onclick="history.back();"><img src="../img/x_btn.png" /></span>
    </td>
    <td>
    </td>
    <td>
      <button type="button">내주변<img src="../img/location_b.png" /></button>
    </td>
  </table>


</section>

<nav id="gnb">
  <ul class="gnb_region_btn">
    <?php for ($i=0; $row=sql_fetch_array($re_category); $i++){ ?>
      <li>
        <input type="hidden" id="region_code_<?php echo $i?>" value="<?php echo $row['catecode'];?>">
        <input type="hidden" id="upcate_<?php echo $i?>" value="<?php echo $row['upcate'];?>">
        <input type="hidden" id="region_val_<?php echo $i?>" value="<?php echo $row['catename'];?>">
        <button type="button" class="region_btn" id="region_btn_<?php echo $i; ?>" style="background-image: url('../img/<?php echo $row['cateimg1']; ?>');"><h3><?php echo $row['catename'];?></h3><small><?php echo $row['catedesc'];?></small></button>
      </li>
    <?php }?>
  </ul>
</nav>

<div class="rs_list">

  <!-- Swiper -->
  <div class="swiper-container region-swiper">
    <div class="swiper-wrapper">
      <div class="swiper-slide">
        <ul class="region_list_ul">
          <li onclick="gotoList('<?php echo $cb_type;?>','001');">
            <h2>서울 전체</h2>
          </li>
          <?php
            for($j=0; $row_detail=sql_fetch_array($re_category_detail); $j++){
          ?>
            <li onclick="gotoList2('<?php echo $cb_type;?>', '<?php echo $row_detail['catecode']?>', '<?php echo $row_detail['upcate'] ?>');" class="shadow_box auto_side">
              <span><div class="region_2"><?php echo $row_detail['catename']; ?></div></span><span><img src="../img/chevron-small-right.png"></span>
            </li>
          <?php } ?>
        </ul>
      </div>

    </div>
    <!-- <div class="swiper-pagination"></div> -->
  </div>
</div>


<div class="cbrs_container">




  <!-- Initialize Swiper -->
  <script>
    var swiper = new Swiper('.swiper-container', {
      // pagination: {
      //   el: '.swiper-pagination',
      //   clickable: true,
      //   renderBullet: function (index, className) {
      //     return '<span class="' + className + '">' + (index + 1) + '</span>';
      //   },
      // },
    });

    //지역카테고리 개수
    var region_cnt = $('.region_btn').length;
    var cb_type = $("#cb_type").val();
    for(let i=0; i<region_cnt; i++) {
      $("#region_btn_"+i).click(function() {
        // swiper.slideTo(i);

        var region_code = $('#region_code_'+i).val();
        var region_val = $('#region_val_'+i).val();
        var cb_type = "<?php echo $cb_type;?>";

        $.ajax({
          url: "./cb_region_ajax.php",
          type: "POST",
          data:{region_code:region_code, region_val:region_val, cb_type:cb_type},
          success:function(data) {
            $('.swiper-slide').html(data);
          }
        });


      });
    }
  </script>

</div>
<script>
	//상단 슬라이드 메뉴
	// var menuScroll = null;
	// $(window).ready(function() {
	// 	menuScroll = new iScroll('gnb', {
	// 		hScrollbar:false, vScrollbar:false, bounce:false, click:true
	// 	});
	// });

  //지역 클릭했을시
  function gotoList(cb_type, catecode) {
     if(cb_type == 'RT' || cb_type == 'KP')
      location.href="./carbang_list_car_test.php?cb_type="+cb_type+"&catecode="+catecode;
    } else {
      location.href="./carbang_list_test.php?cb_type="+cb_type+"&catecode="+catecode;
    }
  //세부지역 클릭했을시
  function gotoList2(cb_type, catecode, upcate) {
    if(cb_type == 'RT' || cb_type == 'KP')
      location.href="./carbang_list_car_test.php?cb_type="+cb_type+"&catecode="+catecode+"&upcate="+upcate;
    } else {
      location.href="./carbang_list.php?cb_type="+cb_type+"&catecode="+catecode+"&upcate="+upcate;
    }


</script>
