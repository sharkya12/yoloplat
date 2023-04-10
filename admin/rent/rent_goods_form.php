<?php
if(!defined('_TUBEWEB_')) exit;

//객실시설 목록 가지고 오는 sql 구문
$sql_in_gr = " select * FROM hi_room_facilities_group WHERE fac_type = 'IN' ORDER BY fac_group_cd";
$fac_gr_list = sql_query($sql_in_gr);

	if($w == "") {
	$gs['mb_id']		= '';
	$gs['gcode']		= time();
	$gs['sc_type']		= 0; // 배송비 유형	0:공통설정, 1:무료배송, 2:조건부 무료배송, 3:유료배송
	$gs['sc_method']	= 0; // 배송비 결제	0:선불, 1:착불, 2:사용자선택
	$gs['stock_mod']	= 0;
	$gs['noti_qty']		= 999;
	$gs['simg_type']	= 0;
	$gs['isopen']		= 1;
	$gs['notax']		= 1;
	$gs['ppay_type']	= 0;
	$gs['ppay_rate']	= 0;
	$gs['zone']			= '전국';

} else if($w == "u") {

	$sql = " select mb_id,gcode FROM hi_goods WHERE index_no = {$gs_id}";
	$room_type_result = sql_fetch($sql);
	$_mb_id = $room_type_result['mb_id'];
	$_gcode = $room_type_result['gcode'];

	$sql = " select * FROM hi_goods_price WHERE mb_id = '{$_mb_id}' AND gcode = '{$_gcode}' ";
	$gs_type = sql_fetch($sql);

	$gs = get_goods($gs_id);
    if(!$gs)
        alert("존재하지 않은 상품 입니다.");

	$readonly = " readonly style='background-color:#ddd;'";

	if(is_null_time($gs['sb_date'])) $gs['sb_date'] = '';
	if(is_null_time($gs['eb_date'])) $gs['eb_date'] = '';
}

if(isset($sel_ca1))			$qstr .= "&sel_ca1=$sel_ca1";
if(isset($sel_ca2))			$qstr .= "&sel_ca2=$sel_ca2";
if(isset($sel_ca3))			$qstr .= "&sel_ca3=$sel_ca3";
if(isset($sel_ca4))			$qstr .= "&sel_ca4=$sel_ca4";
if(isset($sel_ca5))			$qstr .= "&sel_ca5=$sel_ca5";
if(isset($q_date_field))	$qstr .= "&q_date_field=$q_date_field";
if(isset($q_brand))			$qstr .= "&q_brand=$q_brand";
if(isset($q_zone))			$qstr .= "&q_zone=$q_zone";
if(isset($q_stock_field))	$qstr .= "&q_stock_field=$q_stock_field";
if(isset($fr_stock))		$qstr .= "&fr_stock=$fr_stock";
if(isset($to_stock))		$qstr .= "&to_stock=$to_stock";
if(isset($q_price_field))	$qstr .= "&q_price_field=$q_price_field";
if(isset($fr_price))		$qstr .= "&fr_price=$fr_price";
if(isset($to_price))		$qstr .= "&to_price=$to_price";
if(isset($q_isopen))		$qstr .= "&q_isopen=$q_isopen";
if(isset($q_option))		$qstr .= "&q_option=$q_option";
if(isset($q_supply))		$qstr .= "&q_supply=$q_supply";
if(isset($q_notax))			$qstr .= "&q_notax=$q_notax";

include_once(TB_LIB_PATH.'/goodsinfo.lib.php');
include_once(TB_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$frm_submit = '<div class="btn_confirm">
    <input type="submit" value="저장" class="btn_large" accesskey="s">';
if($w == "u" && $bak) {
    $frm_submit .= PHP_EOL.'<a href="./goods.php?code='.$bak.$qstr.'&page='.$page.'" class="btn_large bx-white">목록</a>';
	$frm_submit .= '<a href="./goods.php?code=form" class="btn_large bx-red">추가</a>'.PHP_EOL;
}
$frm_submit .= '</div>';

$pg_anchor = <<<EOF
<ul class="anchor">
	<li><a href="#anc_sitfrm_ini">기본정보</a></li>
	<li><a href="#anc_sitfrm_pCost">기간별 이용요금</a></li>
	<li><a href="#anc_sitfrm_option">차량추가옵션</a></li>
	<li><a href="#anc_sitfrm_cost">차량 이용요금</a></li>
	<!--<li><a href="#anc_sitfrm_pay">가맹점수수료</a></li>-->
	<!--<li><a href="#anc_sitfrm_sendcost">배송비</a></li>-->
	<!--<li><a href="#anc_sitfrm_compact">요약정보</a></li>-->
	<li><a href="#anc_sitfrm_img">차량이미지</a></li>
</ul>
EOF;
?>

<p>
	<?php echo $row3; ?>
</p>

<script src="<?php echo TB_JS_URL; ?>/categoryform.js?ver=<?php echo TB_JS_VER; ?>"></script>

<form name="fregform" method="post" onsubmit="return fregform_submit(this)" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="gs_id" value="<?php echo $gs_id; ?>">
<input type="hidden" name="q1" value="<?php echo $qstr; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="bak" value="<?php echo $bak; ?>">
<input type="hidden" name="ca_id" value="">
<input type="hidden" name="ca_id2" value="">
<input type="hidden" name="ca_id3" value="">

<section id="anc_sitfrm_ini">
<h2>기본정보</h2>
<?php echo $pg_anchor; ?>
<?php if($w == 'u') { ?>
<div class="local_desc02 local_desc">
	<p>상품 등록일시 : <b><?php echo $gs['reg_time']; ?></b>, 최근 수정일시 : <b><?php echo $gs['update_time']; ?></b></p>
</div>
<?php } ?>
<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">업체코드</th>
		<td>
			<input type="text" name="mb_id" value="<?php echo $gs['mb_id']; ?>" id="mb_id" required itemname="업체코드" class="required frm_input">
			<a href="./rent_supply.php" onclick="win_open(this,'pop_supply','550','500','no');return false" class="btn_small">업체선택</a>
		</td>
	</tr>
	<tr>
		<th scope="row">상품코드</th>
		<td>
			<input type="text" name="gcode" value="<?php echo $gs['gcode']; ?>" required itemname="상품코드" class="required frm_input"<?php echo $readonly; ?>>
			<?php if($w == "u") { ?><a href="<?php echo TB_SHOP_URL; ?>/view.php?index_no=<?php echo $gs_id; ?>" target="_blank" class="btn_small">미리보기</a><?php } ?>
		</td>
	</tr>
	<tr>
		<th scope="row">차종</th>
		<td colspan="3">

			<?php echo get_car_category_select_1('sel_ca1', $sca); ?>
			<?php echo get_car_category_select_2('sel_ca2', $sca); ?>
			<?php echo get_category_select_3('sel_ca3', $sca); ?>
			<?php echo get_category_select_4('sel_ca4', $sca); ?>


			<script>
			$(function() {

				$("#sel_ca1").multi_select_box("#sel_ca",4,tb_admin_url+"/ajax.car_select_json.php","=카테고리선택=");
				$("#sel_ca2").multi_select_box("#sel_ca",4,tb_admin_url+"/ajax.car_select_json.php","=카테고리선택=");
				$("#sel_ca3").multi_select_box("#sel_ca",4,tb_admin_url+"/ajax.car_select_json.php","=카테고리선택=");
				$("#sel_ca4").multi_select_box("#sel_ca",4,"","=카테고리선택=");

			});
			</script>
			<tr>
				<th scope="row">차량구분</th>
				<td class="td_label">
					<?php echo radio_checked('notax', $gs['notax'], '1', '일반'); ?>
					<?php echo radio_checked('notax', $gs['notax'], '0', '캠핑카'); ?>
				</td>
			</tr>
		</td>
	</tr>
	<tr>
		<th scope="row">차량번호</th>
		<td><input type="text" name="car_number" value="<?php echo $gs['car_number']; ?>" required itemname="차량번호" class="required frm_input" size="80"></td>
	</tr>
	<tr>
		<th scope="row">상품명</th>
		<td><input type="text" name="gname" value="<?php echo $gs['gname']; ?>" required itemname="상품명" class="required frm_input" size="80"></td>
	</tr>
	<tr>
		<th scope="row">짧은설명</th>
		<td><input type="text" name="explan" value="<?php echo $gs['explan']; ?>" class="frm_input" size="80"></td>
	</tr>
	<tr>
		<th scope="row">검색키워드</th>
		<td>
			<input type="text" name="keywords" value="<?php echo $gs['keywords']; ?>" class="frm_input wfull">
			<?php echo help('단어와 단어 사이는 콤마 ( , ) 로 구분하여 여러개를 입력할 수 있습니다. 예시) 빨강, 노랑, 파랑'); ?>
		</td>
	</tr>

	<!--tr>
		<th scope="row">모델명</th>
		<td><input type="text" name="model" value="<?php echo $gs['model']; ?>" class="frm_input"></td>
	</tr-->
	<!--tr>
		<th scope="row">생산국(원산지)</th>
		<td><input type="text" name="origin" value="<?php echo $gs['origin']; ?>" class="frm_input"></td>
	</tr-->
	<!--tr>
		<th scope="row">제조사</th>
		<td><input type="text" name="maker" value="<?php echo $gs['maker']; ?>" class="frm_input"></td>
	</tr-->

	<tr>
		<th scope="row">운영여부</th>
		<td class="td_label">
			<?php echo radio_checked('isopen', $gs['isopen'], '1', '진열'); ?>
			<?php echo radio_checked('isopen', $gs['isopen'], '2', '마감'); ?>
			<!--?php echo radio_checked('isopen', $gs['isopen'], '3', '단종'); ?-->
			<?php echo radio_checked('isopen', $gs['isopen'], '4', '중지'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">기준 인원</th>
		<td class="td_label">
			<input type="text" name="standard_pplNum" value="<?php echo $gs['standard_pplNum']?>" class="required frm_input" /> 인 기준
		</td>
	</tr>
	<tr>
		<th scope="row">최대 인원</th>
		<td class="td_label">
			<input type="text" name="max_pplNum" value="<?php echo $gs['max_pplNum']?>" class="required frm_input" /> 인 최대
		</td>
	</tr>
	<!--tr>
		<th scope="row">네이버쇼핑 상품ID</th>
		<td>
			<input type="text" name="ec_mall_pid" value="<?php echo $gs['ec_mall_pid']; ?>" id="ec_mall_pid" class="frm_input">
			<?php echo help("네이버쇼핑에 입점한 경우 네이버쇼핑 상품ID를 입력하시면 네이버페이와 연동됩니다.<br>일부 쇼핑몰의 경우 네이버쇼핑 상품ID 대신 쇼핑몰 상품ID를 입력해야 하는 경우가 있습니다.<br>네이버페이 연동과정에서 이 부분에 대한 안내가 이뤄지니 안내받은 대로 값을 입력하시면 됩니다."); ?>
		</td>
	</tr-->
	</tbody>
	</table>
</div>
</section>

<?php echo $frm_submit; ?>

<section id="anc_sitfrm_pCost">
<h2>기간별 이용요금</h2>
<?php echo $pg_anchor; ?>
<div class="tbl_frm02">
	<table>
		<colgroup>
			<col class="w180">
			<col>
		</colgroup>
		<tbody>
			<tr>
				<th scope="row">성수기</th>
				<td>
					<table class="mart7">
						<colgroup>
							<col width="60px">
							<col width="150px">
							<col width="85px">
							<col>
						</colgroup>
							<tbody>
								<tr>
									<th scope="row">주중 금액</th>
									<td><input type="text" name="peak_week_amt" value="<?php echo number_format($gs_type['peak_week_amt']); ?>" class="frm_input w80" onkeyup="addComma(this);"/> 원</td>
								</tr>
								<tr>
									<th scope="row">금요일 금액</th>
									<td><input type="text" name="peak_fri_amt" value="<?php echo number_format($gs_type['peak_fri_amt']); ?>" class="frm_input w80" onkeyup="addComma(this);"/> 원</td>
								</tr>
								<tr>
									<th scope="row">토요일 금액</th>
									<td><input type="text" name="peak_sat_amt" value="<?php echo number_format($gs_type['peak_sat_amt']); ?>" class="frm_input w80" onkeyup="addComma(this);"/> 원</td>
								</tr>
								<tr>
									<th scope="row">일요일 금액</th>
									<td><input type="text" name="peak_sun_amt" value="<?php echo number_format($gs_type['peak_sun_amt']); ?>" class="frm_input w80" onkeyup="addComma(this);"onkeyup="addComma(this);"/> 원</td>
								</tr>
								<tr>
									<th scope="row">휴일 금액</th>
									<td><input type="text" name="peak_holi_amt" value="<?php echo number_format($gs_type['peak_holi_amt']); ?>" class="frm_input w80" onkeyup="addComma(this);"/> 원</td>
								</tr>
								<tr>
									<th scope="row">추가인원 금액</th>
									<td><input type="text" name="peak_add_pers_amt" value="<?php echo number_format($gs_type['peak_add_pers_amt']); ?>" class="frm_input w80" onkeyup="addComma(this);"/> 원</td>
								</tr>
							</tbody>
					</table>
				</td>
			</tr>

			<tr>
				<th scope="row">준성수기</th>
				<td>
					<table class="mart7">
						<colgroup>
							<col width="60px">
							<col width="150px">
							<col width="85px">
							<col>
						</colgroup>
							<tbody>
								<tr>
									<th scope="row">주중 금액</th>
									<td><input type="text" name="mid_week_amt" value="<?php echo number_format($gs_type['mid_week_amt']); ?>" class="frm_input w80" onkeyup="addComma(this);"/> 원</td>
								</tr>
								<tr>
									<th scope="row">금요일 금액</th>
									<td><input type="text" name="mid_fri_amt" value="<?php echo number_format($gs_type['mid_fri_amt']); ?>" class="frm_input w80" onkeyup="addComma(this);"/> 원</td>
								</tr>
								<tr>
									<th scope="row">토요일 금액</th>
									<td><input type="text" name="mid_sat_amt" value="<?php echo number_format($gs_type['mid_sat_amt']); ?>" class="frm_input w80" onkeyup="addComma(this);"/> 원</td>
								</tr>
								<tr>
									<th scope="row">일요일 금액</th>
									<td><input type="text" name="mid_sun_amt" value="<?php echo number_format($gs_type['mid_sun_amt']); ?>" class="frm_input w80" onkeyup="addComma(this);"/> 원</td>
								</tr>
								<tr>
									<th scope="row">휴일 금액</th>
									<td><input type="text" name="mid_holi_amt" value="<?php echo number_format($gs_type['mid_holi_amt']); ?>" class="frm_input w80" onkeyup="addComma(this);"/> 원</td>
								</tr>
								<tr>
									<th scope="row">추가인원 금액</th>
									<td><input type="text" name="mid_add_pers_amt" value="<?php echo number_format($gs_type['mid_add_pers_amt']); ?>" class="frm_input w80" onkeyup="addComma(this);"/> 원</td>
								</tr>
							</tbody>
					</table>
				</td>
			</tr>

			<tr>
				<th scope="row">비수기</th>
				<td>
					<table class="mart7">
						<colgroup>
							<col width="60px">
							<col width="150px">
							<col width="85px">
							<col>
						</colgroup>
							<tbody>
								<tr>
									<th scope="row">주중 금액</th>
									<td><input type="text" name="off_week_amt" value="<?php echo number_format($gs_type['off_week_amt']); ?>" class="frm_input w80" onkeyup="addComma(this);"/> 원</td>
								</tr>
								<tr>
									<th scope="row">금요일 금액</th>
									<td><input type="text" name="off_fri_amt" value="<?php echo number_format($gs_type['off_fri_amt']); ?>" class="frm_input w80" onkeyup="addComma(this);"/> 원</td>
								</tr>
								<tr>
									<th scope="row">토요일 금액</th>
									<td><input type="text" name="off_sat_amt" value="<?php echo number_format($gs_type['off_sat_amt']); ?>" class="frm_input w80" onkeyup="addComma(this);"/> 원</td>
								</tr>
								<tr>
									<th scope="row">일요일 금액</th>
									<td><input type="text" name="off_sun_amt" value="<?php echo number_format($gs_type['off_sun_amt']); ?>" class="frm_input w80" onkeyup="addComma(this);"/> 원</td>
								</tr>
								<tr>
									<th scope="row">휴일 금액</th>
									<td><input type="text" name="off_hoil_amt" value="<?php echo number_format($gs_type['off_hoil_amt']); ?>" class="frm_input w80" onkeyup="addComma(this);"/> 원</td>
								</tr>
								<tr>
									<th scope="row">추가인원 금액</th>
									<td><input type="text" name="off_add_pers_amt" value="<?php echo number_format($gs_type['off_add_pers_amt']); ?>" class="frm_input w80" onkeyup="addComma(this);"/> 원</td>
								</tr>
							</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</div>
</section>

<?php echo $frm_submit; ?>

<section id="anc_sitfrm_option">
	<h2>차량옵션</h2>
	<div class="tbl_frm01">
		<table class="tablef">
			<colgroup>
				<col class="w140">
				<col>
			</colgroup>
			<tbody>
				<?php
				$a=0;
				for($j=0; $row=sql_fetch_array($fac_gr_list); $j++) {
					$fac_group_cd = $row['fac_group_cd'];
					$fac_group_nm = $row['fac_group_nm'];

					$sql_out_ma = " select F.index_no, F.fac_group_cd, F.seq, F.fac_name
												, case
													when F.fac_idx IS NULL  then 'N'
													ELSE 'Y'
												  END AS chk_yn
												FROM
												(
													SELECT a.index_no, a.fac_group_cd, a.seq, a.fac_name
													,(SELECT fac_idx FROM hi_room_facilities WHERE fac_idx = a.index_no AND mb_id = '{$mb_id}' ) AS fac_idx
													FROM hi_room_facilities_master  AS a
													WHERE a.fac_type = 'OUT' and a.fac_group_cd = ".$fac_group_cd."
												) AS F
												order by F.seq";
					$fac_ma_list = sql_query($sql_out_ma);
				?>
					<tr>
						<th scope="row">
							<input type="checkbox" class="fac_group_chk" id="fac_cate<?php echo $j?>" /> <label for="fac_cate<?php echo $j?>"> <?php echo $fac_group_nm; ?> </label>
						</th>
						<td class="register_td_100">
						<?php for($z=0; $row2=sql_fetch_array($fac_ma_list); $z++) {
							$fac_name = $row2['fac_name'];
						?>
							<ul>
								<li>
									<input type="hidden" name="fac_idx[]" value="<?php echo $row2['index_no']; ?>" />
									<input type="hidden" name="fac_gr_cd[]" value="<?php echo $row['fac_group_cd']; ?>" />
									<input type="hidden" name="fac_ma_seq[]" value="<?php echo $row2['seq']; ?>" />
									<input type="hidden" name="fac_name[]" value="<?php echo $row2['fac_name']; ?>" />
									<input type="checkbox" name="room_facility[]" id="<?php echo $j?>chk_room_facility<?php echo $z?>" class="frm_input" value="<?php echo $a; ?>" <?php echo $row2['chk_yn'] == 'Y' ? 'checked': '' ?> />
									<label for="<?php echo $j ?>chk_room_facility<?php echo $z?>"> <?php echo $fac_name; ?> </label>
								</li>
							</ul>
						<?php $a++; }?>
						</td>
					</tr>
				<?php }
					$sql_facMaxNum = " select max(F.seq) as facMaxNum FROM
						(
							SELECT  count(seq) AS seq
							FROM hi_room_facilities_master
							WHERE fac_type = 'OUT' GROUP BY fac_group_cd
						) AS F";
					$facMaxNum = sql_fetch($sql_facMaxNum);
				?>
			</tbody>
			<tr>
				<th>
					부대시설 정보
				</th>
				<td colspan="<?php echo $facMaxNum['facMaxNum']?>">
					<textarea id="fac_remark" name="fac_remark" rows="8" cols="50"><?php echo $room_row['fac_remark'] ?></textarea>
				</td>
			</tr>
		</table>
	</div>


			<script>
			$(function() {
				<?php if($gs['index_no'] && $po_run) { ?>
				//옵션항목설정
				var arr_opt1 = new Array();
				var arr_opt2 = new Array();
				var arr_opt3 = new Array();
				var opt1 = opt2 = opt3 = '';
				var opt_val;

				$(".opt-cell").each(function() {
					opt_val = $(this).text().split(" > ");
					opt1 = $.trim(opt_val[0]);
					opt2 = $.trim(opt_val[1]);
					opt3 = $.trim(opt_val[2]);

					if(opt1 && $.inArray(opt1, arr_opt1) == -1)
						arr_opt1.push(opt1);

					if(opt2 && $.inArray(opt2, arr_opt2) == -1)
						arr_opt2.push(opt2);

					if(opt3 && $.inArray(opt3, arr_opt3) == -1)
						arr_opt3.push(opt3);
				});

				$("input[name=opt1]").val(arr_opt1.join());
				$("input[name=opt2]").val(arr_opt2.join());
				$("input[name=opt3]").val(arr_opt3.join());
				<?php } ?>

				// 옵션목록생성
				$("#option_table_create").click(function() {
					var gs_id = $.trim($("input[name=gs_id]").val());
					var opt1_subject = $.trim($("#opt1_subject").val());
					var opt2_subject = $.trim($("#opt2_subject").val());
					var opt3_subject = $.trim($("#opt3_subject").val());
					var opt1 = $.trim($("#opt1").val());
					var opt2 = $.trim($("#opt2").val());
					var opt3 = $.trim($("#opt3").val());
					var $option_table = $("#sit_option_frm");

					if(!opt1_subject || !opt1) {
						alert("옵션명과 옵션항목을 입력해 주십시오.");
						return false;
					}

					$.post(
						tb_admin_url+"/goods/goods_option.php",
						{ gs_id: gs_id, w: "<?php echo $w; ?>", opt1_subject: opt1_subject, opt2_subject: opt2_subject, opt3_subject: opt3_subject, opt1: opt1, opt2: opt2, opt3: opt3 },
						function(data) {
							$option_table.empty().html(data);
						}
					);
				});

				// 모두선택
				$(document).on("click", "input[name=opt_chk_all]", function() {
					if($(this).is(":checked")) {
						$("input[name='opt_chk[]']").attr("checked", true);
					} else {
						$("input[name='opt_chk[]']").attr("checked", false);
					}
				});

				// 선택삭제
				$(document).on("click", "#sel_option_delete", function() {
					var $el = $("input[name='opt_chk[]']:checked");
					if($el.size() < 1) {
						alert("삭제하려는 옵션을 하나 이상 선택해 주십시오.");
						return false;
					}

					$el.closest("tr").remove();
				});

				// 일괄적용
				$(document).on("click", "#opt_value_apply", function() {
					if($(".opt_com_chk:checked").size() < 1) {
						alert("일괄 수정할 항목을 하나이상 체크해 주십시오.");
						return false;
					}

					var opt_supply_price = $.trim($("#opt_com_supply_price").val());
					var opt_price = $.trim($("#opt_com_price").val());
					var opt_stock = $.trim($("#opt_com_stock").val());
					var opt_noti = $.trim($("#opt_com_noti").val());
					var opt_use = $("#opt_com_use").val();
					var $el = $("input[name='opt_chk[]']:checked");

					// 체크된 옵션이 있으면 체크된 것만 적용
					if($el.size() > 0) {
						var $tr;
						$el.each(function() {
							$tr = $(this).closest("tr");

							if($("#opt_com_supply_price_chk").is(":checked"))
								$tr.find("input[name='opt_supply_price[]']").val(opt_supply_price);

							if($("#opt_com_price_chk").is(":checked"))
								$tr.find("input[name='opt_price[]']").val(opt_price);

							if($("#opt_com_stock_chk").is(":checked"))
								$tr.find("input[name='opt_stock_qty[]']").val(opt_stock);

							if($("#opt_com_noti_chk").is(":checked"))
								$tr.find("input[name='opt_noti_qty[]']").val(opt_noti);

							if($("#opt_com_use_chk").is(":checked"))
								$tr.find("select[name='opt_use[]']").val(opt_use);
						});
					} else {
						if($("#opt_com_supply_price_chk").is(":checked"))
							$("input[name='opt_supply_price[]']").val(opt_supply_price);

						if($("#opt_com_price_chk").is(":checked"))
							$("input[name='opt_price[]']").val(opt_price);

						if($("#opt_com_stock_chk").is(":checked"))
							$("input[name='opt_stock_qty[]']").val(opt_stock);

						if($("#opt_com_noti_chk").is(":checked"))
							$("input[name='opt_noti_qty[]']").val(opt_noti);

						if($("#opt_com_use_chk").is(":checked"))
							$("select[name='opt_use[]']").val(opt_use);
					}
				});
			});
			</script>
		</td>
	</tr>
	<?php
	$spl_subject = explode(',', $gs['spl_subject']);
	$spl_count = count($spl_subject);
	?>

			<script>
			$(function() {
				<?php if($gs['index_no'] && $ps_run) { ?>
				// 추가옵션의 항목 설정
				var arr_subj = new Array();
				var subj, spl;

				$("input[name='spl_subject[]']").each(function() {
					subj = $.trim($(this).val());
					if(subj && $.inArray(subj, arr_subj) == -1)
						arr_subj.push(subj);
				});

				for(i=0; i<arr_subj.length; i++) {
					var arr_spl = new Array();
					$(".spl-subject-cell").each(function(index) {
						subj = $.trim($(this).text());
						if(subj == arr_subj[i]) {
							spl = $.trim($(".spl-cell:eq("+index+")").text());
							arr_spl.push(spl);
						}
					});

					$("input[name='spl[]']:eq("+i+")").val(arr_spl.join());
				}
				<?php } ?>
				// 입력필드추가
				$("#add_supply_row").click(function() {
					var $el = $("#sit_supply_frm tr:last");
					var fld = "<tr>\n";
					fld += "<th scope=\"row\"><label for=\"\">추가</label></th>\n";
					fld += "<td><input type=\"text\" name=\"spl_subject[]\" value=\"\" class=\"frm_input wfull\"></td>\n";
					fld += "<th scope=\"row\" class=\"ssupply_type\"><label for=\"\">추가 항목</label></th>\n";
					fld += "<td><input type=\"text\" name=\"spl[]\" value=\"\" class=\"frm_input wfull\"></td>\n";
					fld += "<td class=\"tac\"><button type=\"button\" id=\"del_supply_row\" class=\"btn_ssmall bx-white\">삭제</button></td>\n";
					fld += "</tr>";

					$el.after(fld);

					supply_sequence();
				});

				// 입력필드삭제
				$(document).on("click", "#del_supply_row", function() {
					$(this).closest("tr").remove();

					supply_sequence();
				});

				// 옵션목록생성
				$("#supply_table_create").click(function() {
					var gs_id = $.trim($("input[name=gs_id]").val());
					var subject = new Array();
					var supply = new Array();
					var subj, spl;
					var count = 0;
					var $el_subj = $("input[name='spl_subject[]']");
					var $el_spl = $("input[name='spl[]']");
					var $supply_table = $("#sit_option_addfrm");

					$el_subj.each(function(index) {
						subj = $.trim($(this).val());
						spl = $.trim($el_spl.eq(index).val());

						if(subj && spl) {
							subject.push(subj);
							supply.push(spl);
							count++;
						}
					});

					if(!count) {
						alert("추가옵션명과 추가옵션항목을 입력해 주십시오.");
						return false;
					}

					$.post(
						tb_admin_url+"/goods/goods_spl.php",
						{ gs_id: gs_id, w: "<?php echo $w; ?>", 'subject[]': subject, 'supply[]': supply },
						function(data) {
							$supply_table.empty().html(data);
						}
					);
				});

				// 모두선택
				$(document).on("click", "input[name=spl_chk_all]", function() {
					if($(this).is(":checked")) {
						$("input[name='spl_chk[]']").attr("checked", true);
					} else {
						$("input[name='spl_chk[]']").attr("checked", false);
					}
				});

				// 선택삭제
				$(document).on("click", "#sel_supply_delete", function() {
					var $el = $("input[name='spl_chk[]']:checked");
					if($el.size() < 1) {
						alert("삭제하려는 옵션을 하나 이상 선택해 주십시오.");
						return false;
					}

					$el.closest("tr").remove();
				});

				// 일괄적용
				$(document).on("click", "#spl_value_apply", function() {
					if($(".spl_com_chk:checked").size() < 1) {
						alert("일괄 수정할 항목을 하나이상 체크해 주십시오.");
						return false;
					}

					var spl_supply_price = $.trim($("#spl_com_supply_price").val());
					var spl_price = $.trim($("#spl_com_price").val());
					var spl_stock = $.trim($("#spl_com_stock").val());
					var spl_noti = $.trim($("#spl_com_noti").val());
					var spl_use = $("#spl_com_use").val();
					var $el = $("input[name='spl_chk[]']:checked");

					// 체크된 옵션이 있으면 체크된 것만 적용
					if($el.size() > 0) {
						var $tr;
						$el.each(function() {
							$tr = $(this).closest("tr");

							if($("#spl_com_supply_price_chk").is(":checked"))
								$tr.find("input[name='spl_supply_price[]']").val(spl_supply_price);

							if($("#spl_com_price_chk").is(":checked"))
								$tr.find("input[name='spl_price[]']").val(spl_price);

							if($("#spl_com_stock_chk").is(":checked"))
								$tr.find("input[name='spl_stock_qty[]']").val(spl_stock);

							if($("#spl_com_noti_chk").is(":checked"))
								$tr.find("input[name='spl_noti_qty[]']").val(spl_noti);

							if($("#spl_com_use_chk").is(":checked"))
								$tr.find("select[name='spl_use[]']").val(spl_use);
						});
					} else {
						if($("#spl_com_supply_price_chk").is(":checked"))
							$("input[name='spl_supply_price[]']").val(spl_supply_price);

						if($("#spl_com_price_chk").is(":checked"))
							$("input[name='spl_price[]']").val(spl_price);

						if($("#spl_com_stock_chk").is(":checked"))
							$("input[name='spl_stock_qty[]']").val(spl_stock);

						if($("#spl_com_noti_chk").is(":checked"))
							$("input[name='spl_noti_qty[]']").val(spl_noti);

						if($("#spl_com_use_chk").is(":checked"))
							$("select[name='spl_use[]']").val(spl_use);
					}
				});
			});

			function supply_sequence()
			{
				var $tr = $("#sit_supply_frm tr");
				var seq;
				var th_label, td_label;

				$tr.each(function(index) {
					seq = index + 1;
					$(this).find("th label").attr("for", "spl_subject_"+seq).text("추가"+seq);
					$(this).find("td input").attr("id", "spl_subject_"+seq);
					$(this).find("th.ssupply_type label").attr("for", "spl_item_"+seq);
					$(this).find("th.ssupply_type label").text("추가"+seq+" 항목");
					$(this).find("td input").attr("id", "spl_item_"+seq);
				});
			}
			</script>
		</td>
	</tr>
	</tbody>
	</table>
</div>
</section>

<?php echo $frm_submit; ?>

<section id="anc_sitfrm_cost">
<h2>차량 이용요금</h2>
<?php echo $pg_anchor; ?>
<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">기본요금</th>
		<td>
			<input type="text" name="normal_price" value="<?php echo number_format($gs['normal_price']); ?>" class="frm_input w80" onkeyup="addComma(this);"> 원
			<span class="fc_197 marl5">본래 기준이 되는 요금 (할인요금보다 크지않으면 기본요금 표시안함)</span>
		</td>
	</tr>
	<tr>
		<th scope="row">정산가격</th>
		<td>
			<input type="text" name="supply_price" value="<?php echo number_format($gs['supply_price']); ?>" class="frm_input w80" onkeyup="addComma(this);"> 원
			<span class="fc_197 marl5">판매가-(판매가*수수료율)-카방이용비</span>
		</td>
	</tr>
	<tr>
		<th scope="row">판매가격</th>
		<td>
			<input type="text" name="goods_price" value="<?php echo number_format($gs['goods_price']); ?>" class="frm_input w80" onkeyup="addComma(this);"> 원
			<span class="fc_197 marl5">실제 차량 판매가 입력 (대표가격으로 사용)</span>
		</td>
	</tr>
	<tr>
		<th scope="row">포인트</th>
		<td>
			<input type="text" name="gpoint" value="<?php echo number_format($gs['gpoint']); ?>" class="frm_input w80" onkeyup="addComma(this);"> P
			<input type="text" name="marper" class="frm_input w50"> %
		</td>
	</tr>
	<!--tr>
		<th scope="row">가격 대체문구</th>
		<td>
			<input type="text" name="price_msg" value="<?php echo $gs['price_msg']; ?>" class="frm_input">
			<span class="fc_197 marl5">가격대신 보여질 문구를 노출할 때 입력, 주문불가</span>
		</td>
	</tr-->
	<tr>
		<th scope="row">차량수량</th>
		<td>
			<input type="radio" name="stock_mod" value="0" id="ids_stock_mode1"<?php echo get_checked('0', $gs['stock_mod']); ?> onclick="chk_stock(0);">
			<label for="ids_stock_mode1" class="marr10">무제한</label>
			<input type="radio" name="stock_mod" value="1" id="ids_stock_mode2"<?php echo get_checked('1', $gs['stock_mod']); ?> onclick="chk_stock(1);">
			<label for="ids_stock_mode2">한정</label>
			<input type="text" name="stock_qty" value="<?php echo number_format($gs['stock_qty']); ?>" class="frm_input w80" onkeyup="addComma(this);"> 개,
			<b class="marl10">차량재고 통보수량</b> <input type="text" name="noti_qty" value="<?php echo number_format($gs['noti_qty']); ?>" class="frm_input w80" onkeyup="addComma(this);"> 개
			<p class="fc_197 mart7">차량의 재고가 통보수량보다 작을 때 차량 재고관리에 표시됩니다.<br>옵션이 있는 상품은 개별 옵션의 통보수량이 적용됩니다. 설정이 무제한이면 재고관리에 표시되지 않습니다.</p>
		</td>
	</tr>
	<tr>
		<th scope="row">연박한도</th>
		<td>
			최대 <input type="text" name="odr_min" value="<?php echo $gs['odr_min']; ?>" class="frm_input w80">일
			<span class="fc_197 marl5">미입력시 무제한</span>
		</td>
	</tr>
	<!--tr>
		<th scope="row">판매기간 설정</th>
		<td>
			<label for="sb_date" class="sound_only">시작일</label>
			<input type="text" name="sb_date" value="<?php echo $gs['sb_date']; ?>" id="sb_date" class="frm_input w80" maxlength="10"> ~
			<label for="eb_date" class="sound_only">종료일</label>
			<input type="text" name="eb_date" value="<?php echo $gs['eb_date']; ?>" id="eb_date" class="frm_input w80" maxlength="10">
			<a href="javascript:void(0);" class="btn_small is_reset">기간초기화</a>
			<div class="fc_197 mart7">
				설정된 기간 동안만 판매 가능하며, 설정된 종료일 이후에는 판매되지 않습니다.<br>
				일시 판매중지 처리하실 경우, 종료일을 현재날짜 이전의 과거 날짜를 넣어주시면 됩니다.
			</div>
			<script>
			$(function(){
				// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
				$("#sb_date,#eb_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99"});

				// 기간초기화
				$(document).on("click", ".is_reset", function() {
					$("#sb_date, #eb_date").val("");
				});
			});
			</script>
		</td>
	</tr-->
	<tr>
		<th scope="row">가능 레벨</th>
		<td>
			<?php echo get_goods_level_select('buy_level', $gs['buy_level']); ?>
			<label class="marl5"><input type="checkbox" name="buy_only" value="1"<?php echo get_checked('1', $gs['buy_only']); ?>> 현재 레벨이상 가격공개</label>
		</td>
	</tr>
	</tbody>
	</table>
</div>
</section>

<?php echo $frm_submit; ?>

<!--?php echo $frm_submit; ?-->



<section id="anc_sitfrm_img">
<h2>차량이미지 및 차량사진</h2>
<?php echo $pg_anchor; ?>
<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">이미지 등록방식</th>
		<td class="td_label">
			<input type="radio" name="simg_type" id="simg_type_1" value="0"<?php echo get_checked('0', $gs['simg_type']); ?> onclick="chk_simg_type(0);">
			<label for="simg_type_1">직접 업로드</label>
			<input type="radio" name="simg_type" id="simg_type_2" value="1"<?php echo get_checked('1', $gs['simg_type']); ?> onclick="chk_simg_type(1);">
			<label for="simg_type_2">URL 입력</label>
		</td>
	</tr>
	<?php
	for($i=1; $i<=6; $i++) {
		if($i == 1) {
			$item_wpx = $default['de_item_small_wpx'];
			$item_hpx = $default['de_item_small_hpx'];
		} else {
			$item_wpx = $default['de_item_medium_wpx'];
			$item_hpx = $default['de_item_medium_hpx'];
		}

		$image_str = '';
		if(in_array($i,array(1,2))) {
			$image_str = ' <strong class="fc_red">[필수]</strong>';
		}
	?>
	<tr class="item_img_fld">
		<th scope="row">차량 이미지<?php echo $i; ?> <span class="fc_197">(<?php echo $item_wpx; ?> * <?php echo $item_hpx; ?>)</span><?php echo $image_str; ?></th>
		<td>
			<div class="item_file_fld">
				<input type="file" name="simg<?php echo $i; ?>">
				<?php echo get_look_ahead($gs['simg'.$i], "simg{$i}_del"); ?>
			</div>
			<div class="item_url_fld">
				<input type="text" name="simg<?php echo $i; ?>" value="<?php echo $gs['simg'.$i]; ?>" class="frm_input" size="80" placeholder="http://">
			</div>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<th scope="row">차량이미지</th>
		<td>
			<?php echo editor_html('memo', get_text(stripcslashes($gs['memo']), 0)); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">관리자메모</th>
		<td><textarea name="admin_memo" class="frm_textbox"><?php echo $gs['admin_memo']; ?></textarea></td>
	</tr>
	</tbody>
	</table>
</div>
</section>

<?php echo $frm_submit; ?>
</form>

<script>

//객실시설 열별 체크박스 클릭했을시
$('.fac_group_chk').click(function(){
	var gr_id = $(this).attr("id");
	var gr_num = gr_id.replace(/[^0-9]/g, '');
	var gr_num_chk = gr_num + 'chk_room_facility';
	if($(this).is(":checked")){
		$('input:checkbox[id*='+gr_num_chk+']').prop('checked', true);
	}else {
		$('input:checkbox[id*='+gr_num_chk+']').prop('checked', false);
	}
});

function fregform_submit(f) {
	var multi_caid = new Array();
	var new_caid = "";

	$("select#sel_ca_id option").each(function() {
        new_caid = $(this).val();
        if(new_caid == "")
            return true;

        multi_caid.push(new_caid);
    });

    if(multi_caid.length > 0) {
		$("input[name=ca_id]").val(multi_caid[0]);
		$("input[name=ca_id2]").val(multi_caid[1]);
		$("input[name=ca_id3]").val(multi_caid[2]);
	}


	var sel_count = $("select#sel_ca_id option").size();
	if(sel_count > 3) {
		alert('카테고리는 최대 3개까지만 등록 가능합니다.');
		return false;
	}

	var item = new Array();
    var re_item = gs_id = "";

    $("#reg_relation input[name='re_gs_id[]']").each(function() {
        gs_id = $(this).val();
        if(gs_id == "")
            return true;

        item.push(gs_id);
    });

    if(item.length > 0)
        re_item = item.join();

    $("input[name=gs_list]").val(re_item);

	<?php echo get_editor_js('memo'); ?>

	f.action = "./rent/rent_goods_form_update.php";
    return true;
}

// 배송비 설정
function chk_sc_type(type) {
	switch(type) {
		case "0" : // 공통설정
			$("#sc_amt").hide();
			$("#sc_minimum").hide();
			$("#sc_method").show();
			$("input[name=sc_amt]").attr('disabled', true);
			$("input[name=sc_minimum]").attr('disabled', true);
			$("select[name=sc_method]").attr('disabled', false);
			break;
		case "1" : // 무료배송
			$("#sc_amt").hide();
			$("#sc_minimum").hide();
			$("#sc_method").hide();
			$("input[name=sc_amt]").attr('disabled', true);
			$("input[name=sc_minimum]").attr('disabled', true);
			$("select[name=sc_method]").attr('disabled', true);
			break;
		case "2" : // 조건부무료배송
			$("#sc_amt").show();
			$("#sc_minimum").show();
			$("#sc_method").show();
			$("input[name=sc_amt]").attr('disabled', false);
			$("input[name=sc_minimum]").attr('disabled', false);
			$("select[name=sc_method]").attr('disabled', false);
			break;
		case "3" : // 유료배송
			$("#sc_amt").show();
			$("#sc_minimum").hide();
			$("#sc_method").show();
			$("input[name=sc_amt]").attr('disabled', false);
			$("input[name=sc_minimum]").attr('disabled', true);
			$("select[name=sc_method]").attr('disabled', false);
			break;
	}
}

// 이미지 등록방식
function chk_simg_type(type) {
	if(type == 0) { // 직접업로드
		$(".item_file_fld").show();
		$(".item_url_fld").hide();
	} else { // URL 입력
		$(".item_img_fld").show();
		$(".item_file_fld").hide();
		$(".item_url_fld").show();
	}
}

// 재고수량 체크
function chk_stock(type) {
	if(type == 0) {
		$("input[name=stock_qty]").attr('disabled', true);
		$("input[name=noti_qty]").attr('disabled', true);
	} else {
		$("input[name=stock_qty]").attr('disabled', false);
		$("input[name=noti_qty]").attr('disabled', false);
	}
}

// 수수료 적용
function chk_ppay_type(type) {
	if(type == 0) {
		$("input[name=ppay_dan]").attr('disabled', true);
		$("select[name=ppay_rate]").attr('disabled', true);
		$("#chk_ppay_auto").text("가맹점관리 > 가맹점 수수료정책 (기본설정 사용중)");
	} else {
		$("input[name=ppay_dan]").attr('disabled', false);
		$("select[name=ppay_rate]").attr('disabled', false);
		$("#chk_ppay_auto").text("수수료를 적용할 단계를 입력하세요");
	}
}

function chk_ppay_dan(no, index){
	$.post(
		tb_admin_url+"/goods/goods_form_auto.php",
		{ "no": no, "index": index },
		function(data) {
			$("#chk_ppay_auto").empty().html(data);
		}
	);
}
</script>

<script>
chk_sc_type("<?php echo $gs[sc_type]; ?>");
chk_simg_type("<?php echo $gs[simg_type]; ?>");
chk_stock("<?php echo $gs[stock_mod]; ?>");
chk_ppay_type("<?php echo $gs[ppay_type]; ?>");
<?php if($gs['ppay_dan']) { ?>
chk_ppay_dan("<?php echo $gs[ppay_dan]; ?>","<?php echo $gs[index_no]; ?>");
<?php } ?>
</script>
