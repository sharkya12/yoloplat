<?php
if(!defined('_TUBEWEB_')) exit;

$sql = "select * from hi_room where mb_id= '{$mb_id}'";
$room_row = sql_fetch($sql);

//조식 - 성인
$sql = "select * from hi_room_food_full where full_div = '1' and pers_div = '1' and mb_id = '{$room_row['mb_id']}' ";
$breakfast_ad = sql_fetch($sql);
//조식 - 소인
$sql = "select * from hi_room_food_full where full_div = '1' and pers_div = '2' and mb_id = '{$room_row['mb_id']}' ";
$breakfast_kd = sql_fetch($sql);

//부대시설 목록 가지고 오는 sql 구문
$sql_out_gr = " select * FROM hi_room_facilities_group WHERE fac_type = 'OUT' ORDER BY fac_group_cd";
$fac_gr_list = sql_query($sql_out_gr);

$hi_member_row = get_member($mb_id);

if($hi_member_row['rgn_headquarters']) {
	$sql = " select catecode from hi_office_category where catename = '{$hi_member_row['rgn_headquarters']}' ";
	$headquarters = sql_fetch($sql);
	$catecode = $headquarters['catecode'];
} else {
	$catecode = '001';
}

//지역본부명 가져오기
$sql = " sELECT * FROM hi_office_category WHERE LENGTH(catecode) <= 3 AND length(upcate) = 0 ORDER BY caterank";
$result = sql_query($sql);

//지역지사명 가져오기
$sql2 = " sELECT * FROM hi_office_category WHERE LEFT(catecode, 3) = '{$catecode}' AND upcate != 0 ORDER BY caterank";
$result2 = sql_query($sql2);
?>


<input type="hidden" name="token" value="">

<script src="<?php echo TB_JS_URL; ?>/categoryform.js?ver=<?php echo TB_JS_VER; ?>"></script>
<?php if($config['cf_cert_use'] && ($config['cf_cert_ipin'] || $config['cf_cert_hp'])) { ?>
<script src="<?php echo TB_JS_URL; ?>/certify.js?v=<?php echo TB_JS_VER; ?>"></script>
<?php } ?>
<script src="<?php echo TB_JS_URL; ?>/jquery.register_form.js"></script>


<form name="fregform" id="fregisterform" method="post" onsubmit="return fregform_submit(this)" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="w" value="<?php echo $w; ?>">

<input type="hidden" name="cert_type" value="<?php echo $hi_member_row['mb_certify']; ?>">
<input type="hidden" name="cert_no" value="">
<input type="hidden" name="ca_id" value="">
<input type="hidden" name="ca_id2" value="">
<input type="hidden" name="ca_id3" value="">
<input type="hidden" id="seller_item_temp" name="seller_item_temp" value ="<?php echo $room_row['seller_item'];?>" />
<input type="hidden" name="bf_flag" id="bf_flag" value="<?php echo $room_row['food']; ?>">

<section id="anc_sitfrm_cate">
	<h2>지역 카테고리</h2>
	<?php echo $pg_anchor; ?>
	<div class="local_desc02 local_desc">
		<p>선택된 지역 카테고리에 <span class="fc_084">최상위 지역 카테고리는 대표 카테고리로 자동설정</span>되며, 최소 1개의 지역 카테고리는 등록하셔야 합니다.</p>
	</div>
	<div class="tbl_frm02">
		<table>
			<colgroup>
				<col class="w180">
				<col>
			</colgroup>
			<tbody>
				<tr>
					<th scope="row">지역 카테고리 선택</th>
					<td>
						<div class="sub_frm01">
							<table>
								<tr>
									<td class="w20p bg1">
										<?php echo get_category_select_1('sel_ca1', '', ' size="10" class="multiple-select"'); ?>
									</td>
									<td class="w20p bg1">
										<?php echo get_category_select_2('sel_ca2', '', ' size="10" class="multiple-select"'); ?>
									</td>
									<td class="w20p bg1">
										<?php echo get_category_select_3('sel_ca3', '', ' size="10" class="multiple-select"'); ?>
									</td>
									<td class="w20p bg1">
										<?php echo get_category_select_4('sel_ca4', '', ' size="10" class="multiple-select"'); ?>
									</td>
									<td class="w20p bg1">
										<?php echo get_category_select_5('sel_ca5', '', ' size="10" class="multiple-select"'); ?>
									</td>
								</tr>
							</table>
						</div>
						<div class="btn_confirm02">
							<button type="button" class="btn_lsmall blue" onclick="category_add();">카테고리 추가</button>
						</div>
						<script>
						$(function() {
							$("#sel_ca1").multi_select_box("#sel_ca",5,tb_admin_url+"/ajax.category_select_json.php","=카테고리선택=");
							$("#sel_ca2").multi_select_box("#sel_ca",5,tb_admin_url+"/ajax.category_select_json.php","=카테고리선택=");
							$("#sel_ca3").multi_select_box("#sel_ca",5,tb_admin_url+"/ajax.category_select_json.php","=카테고리선택=");
							$("#sel_ca4").multi_select_box("#sel_ca",5,tb_admin_url+"/ajax.category_select_json.php","=카테고리선택=");
							$("#sel_ca5").multi_select_box("#sel_ca",5,"","=카테고리선택=");
						});
						</script>
					</td>
				</tr>
				<tr>
					<th scope="row">선택된 지역 카테고리<br><span class="fc_red">(최대 2개까지만 등록)</span></th>
					<td>
						<select name="sel_ca_id" id="sel_ca_id" size="5" class="multiple-select">
						<?php
						if($w == "u") {
							if($ca_id1 = adm_category_navi($room_row['ca_id']))
								echo '<option value="'.$room_row['ca_id'].'">' .$ca_id1. '</option>'.PHP_EOL;
							if($ca_id2 = adm_category_navi($room_row['ca_id2']))
								echo '<option value="'.$room_row['ca_id2'].'">'.$ca_id2.'</option>'.PHP_EOL;
							if($ca_id3 = adm_category_navi($room_row['ca_id3']))
								echo '<option value="'.$room_row['ca_id3'].'">'.$ca_id3.'</option>'.PHP_EOL;
						}
						?>
						</select>
						<div class="btn_confirm02">
							<button type="button" class="btn_lsmall bx-white" onclick="category_move('sel_ca_id', 'prev');">▲ 위로</button>
							<button type="button" class="btn_lsmall bx-white" onclick="category_move('sel_ca_id', 'next');">▼ 아래로</button>
							<button type="button" class="btn_lsmall frm_option_del red">카테고리 삭제</button>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</section>

<h2>사이트 이용정보 입력</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="reg_mb_id">아이디</label></th>
		<td>
			<input type="text" name="mb_id" value="<?php echo $mb_id; ?>" id="reg_mb_id"<?php echo $required; ?><?php echo $w == 'u' ? 'readonly' : ''; ?> class="frm_input<?php echo $required; ?>" size="20" maxlength="20">
			<span id="msg_mb_id"></span>
			<span class="frm_info">영문자, 숫자, _ 만 입력 가능. 최소 3자이상 입력하세요.</span>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_mb_password">비밀번호</label></th>
		<td><input type="password" name="mb_password" id="reg_mb_password"<?php echo $required; ?> class="frm_input<?php echo $required; ?>" size="20" maxlength="20"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_mb_password_re">비밀번호 확인</label></th>
		<td><input type="password" name="mb_password_re" id="reg_mb_password_re"<?php echo $required; ?> class="frm_input<?php echo $required; ?>" size="20" maxlength="20"></td>
	</tr>
	</tbody>
	</table>
</div>

<h2 class="mart30">개인정보 입력</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="reg_mb_name">이름</label></th>
		<td>
			<input type="text" name="mb_name" value="<?php echo $hi_member_row['name']; ?>" id="reg_mb_name"<?php echo $required; ?><?php echo $readonly; ?> class="frm_input<?php echo $required; ?>" size="20">
			<?php
			if($config['cf_cert_use']) {
				if($config['cf_cert_ipin'])
					echo '<button type="button" id="win_ipin_cert" class="btn_small">아이핀 본인인증</button>'.PHP_EOL;
				if($config['cf_cert_hp'])
					echo '<button type="button" id="win_hp_cert" class="btn_small">휴대폰 본인인증</button>'.PHP_EOL;
 					echo '<noscript>본인인증을 위해서는 자바스크립트 사용이 가능해야합니다.</noscript>'.PHP_EOL;
			}
			if($config['cf_cert_use'] && $hi_member_row['mb_certify']) {
				if($hi_member_row['mb_certify'] == 'ipin')
					$mb_cert = '아이핀';
				else
					$mb_cert = '휴대폰';
			?>
			<div id="msg_certify">
				<strong><?php echo $mb_cert; ?> 본인인증</strong><?php if($hi_member_row['mb_adult']) { ?> 및 <strong>성인인증</strong><?php } ?> 완료
			</div>
			<?php } ?>
			<?php if($config['cf_cert_use']) { ?>
			<span class="frm_info">아이핀 본인인증 후에는 이름이 자동 입력되고 휴대폰 본인인증 후에는 이름과 휴대폰번호가 자동 입력되어 수동으로 입력할수 없게 됩니다.</span>
			<?php } ?>
		</td>
	</tr>
	<?php if($config['register_use_tel']) { ?>
	<tr>
		<th scope="row"><label for="reg_mb_tel">전화번호</label></th>
		<td><input type="text" name="mb_tel" value="<?php echo $hi_member_row['telephone']; ?>" id="reg_mb_tel"<?php echo $config['register_req_tel']?' required':''; ?> class="frm_input<?php echo $config['register_req_tel']?' required':''; ?>" size="20" maxlength="20"></td>
	</tr>
	<?php } ?>
	<?php if($config['register_use_hp'] || $config['cf_cert_hp']) { ?>
	<tr>
		<th scope="row"><label for="reg_mb_hp">휴대폰번호</label></th>
		<td>
			<input type="text" name="mb_hp" value="<?php echo $hi_member_row['cellphone']; ?>" id="reg_mb_hp"<?php echo $config['register_req_hp']?' required':''; ?> class="frm_input<?php echo $config['register_req_hp']?' required':''; ?>" size="20" maxlength="20">
			<span class="frm_info">
				<label><input type="checkbox" name="mb_sms" value="Y"<?php echo ($w=='' || $hi_member_row['smsser'] == 'Y')?' checked':''; ?>> 휴대폰 문자메세지를 받겠습니다.</label>
			</span>
			<?php if($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
			<input type="hidden" name="old_mb_hp" value="<?php echo $hi_member_row['cellphone']; ?>">
			<?php } ?>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<th scope="row"><label for="reg_mb_email">E-mail</label></th>
		<td>
			<input type="text" name="mb_email" value="<?php echo $hi_member_row['email']; ?>" id="reg_mb_email" required class="frm_input required" size="40" maxlength="100">
			<span class="frm_info">
				<label><input type="checkbox" name="mb_mailling" value="Y" id="reg_mb_mailling"<?php echo ($w=='' || $hi_member_row['mailser'] == 'Y')?' checked':''; ?>> 정보 메일을 받겠습니다.</label>
			</span>
		</td>
	</tr>
	<tr>
		<th scope="row">지역 본부</th>
		<td colspan="3">
			<select name="rgn_headquarters" id="rgn_headquarters" required>
				<?php echo option_selected('', $hi_member_row['rgn_headquarters'] ,'입력필수'); ?>
					<?php for($i=0; $row=sql_fetch_array($result); $i++) { ?>
						<option id="<?php echo $row['office_id'];?>" value="<?php echo $row['catename']; ?>" <?php if($hi_member_row['rgn_headquarters'] == $row['catename']) { ?>selected <?php } ?>><?php echo $row['catename']; ?></option>
					<?php } ?>
			</select>
			<span id="headquarters_id"><?php echo $headquarters_id; ?></span>
		</td>

	</tr>
	<tr>
		<th scope="row">지역 지사</th>
		<td colspan="3">
			<select name="rgn_branch" id="rgn_branch" required>
				<?php echo option_selected('', $hi_member_row['rgn_branch'] ,'입력필수'); ?>
				<?php for($i=0; $row=sql_fetch_array($result2); $i++) { ?>
					<option id="<?php echo $row['office_id'];?>" value="<?php echo $row['catename']; ?>" <?php if($hi_member_row['rgn_branch'] == $row['catename']) { ?>selected <?php } ?>><?php echo $row['catename']; ?></option>
				<?php } ?>
			</select>
			<span id="branch_id"><?php echo $branch_id; ?></span>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_mb_email">추천인</label></th>
		<td>
			 <input type="text" name="mb_recommend" id="reg_mb_recommend" value="<?php echo $hi_member_row['pt_id']; ?>"   class="frm_input"  size="40" maxlength="100">
			 <input type="button" class="btn_small grey" value="추천인 아이디 확인" onclick="pt_id_check()">
			 <input type="hidden" name="pt_id_chk" id="pt_id_chk" value=""  />
		</td>
	</tr>
	</tbody>
	</table>
</div>

<script>
$(document).ready(function() {
	var headquarters_id = $("#rgn_headquarters option:selected").attr("id");
		$("#headquarters_id").text(headquarters_id);
	var branch_id = $("#rgn_branch option:selected").attr("id");
		$("#branch_id").text(branch_id);

});
// 지역본부에 따라서 지역지사 select_box 리스트 불러오기
$("#rgn_headquarters").change(function() {
	var headquarters_id = $("#rgn_headquarters option:selected").attr("id");

		if (!headquarters_id) {
			alert('없는 아이디입니다');
		} else {
			$("#headquarters_id").text(headquarters_id);
		}
	var value = $("#rgn_headquarters").val();
	office_category_list_ajax(value);
});

// 지역지사값 선택될때 아이디값 불러오기
$("#rgn_branch").change(function() {
	var branch_id = $("#rgn_branch option:selected").attr("id");
		if (!branch_id) {
			alert('없는 아이디입니다');
		} else {
			$("#branch_id").text(branch_id);
		}
});

function office_category_list_ajax(value) {
	$.ajax({
		url: "./room/room_register_rgn_branch.ajax.php",
		type: "POST",
		dataType:"html",
		async: false,
		data: { value:value },
		success:function(data) {
			$('#rgn_branch').html(data);
		},
		error:function(error) {
			alert("오류");
		}
	});
}

function pt_id_check(){
	var pt_id = $('#reg_mb_recommend').val();
	console.log('버튼클릭');
	$.ajax({
		url: "./room/room_register_id_chk.ajax.php",
		type: "POST",
		dataType:"html",
		async: false,
		data: { pt_id:pt_id },
		success:function(data) {
			if (data == 'Y') {
				alert('존재하는 아이디입니다.');
				$('#pt_id_chk').val('Y');
			} else {
				alert('존재하지 않는 아이디입니다.');
				$('#reg_mb_recommend').val('');
				$('#pt_id_chk').val('N');
			}
		},
		error:function(request, status, error) {
			console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
		}
	});

}

</script>

<h2>사업자 정보</h2>
<div class="tbl_frm01">
	<table class="tablef">
		<colgroup>
			<col class="w140">
			<col>
			<col class="w140">
			<col>
		</colgroup>
		<tbody>
			<tr>
				<th scope="row">숙박업소명</th>
				<td><input type="text" name="company_name" required itemname="업소명" class="required frm_input" size="30" value="<?php echo $room_row['company_name'];?>"></td>
				<th scope="row">숙박시설 형태</th>
				<td>
					<select id="seller_item" name="seller_item" class="required frm_input" required style="width:150px;">
						<option value="">전체</option>
						<option value="호텔" <?php if($room_row['seller_item'] == '호텔') { ?>selected <?php } ?>>호텔</option>
						<option value="모텔" <?php if($room_row['seller_item'] == '모텔') { ?>selected <?php } ?>>모텔</option>
						<option value="펜션" <?php if($room_row['seller_item'] == '펜션') { ?>selected <?php } ?>>펜션</option>
						<option value="풀빌라" <?php if($room_row['seller_item'] == '풀빌라') { ?>selected <?php } ?>>풀빌라</option>
						<option value="리조트" <?php if($room_row['seller_item'] == '리조트') { ?>selected <?php } ?>>리조트</option>
						<option value="콘도" <?php if($room_row['seller_item'] == '콘도') { ?>selected <?php } ?>>콘도</option>
						<option value="게스트하우스" <?php if($room_row['seller_item'] == '게스트하우스') { ?>selected <?php } ?>>게스트하우스</option>
						<option value="캠핑" <?php if($room_row['seller_item'] == '캠핑') { ?>selected <?php } ?>>캠핑</option>
						<option value="글램핑" <?php if($room_row['seller_item'] == '글램핑') { ?>selected <?php } ?>>글램핑</option>
					</select>
				</tr>
			<tr>
				<th scope="row">대표자명</th>
				<td><input type="text" name="company_owner" required itemname="대표자명" class="required frm_input" size="30" value="<?php echo $room_row['company_owner'];?>"></td>
				<th scope="row">사업자등록번호</th>
				<td><input type="text" name="company_saupja_no" class="frm_input" size="30" placeholder="예) 000-00-00000" value="<?php echo $room_row['company_saupja_no'];?>" ></td>
			</tr>
			<tr>
				<th scope="row">종류</th>
				<td><input type="text" name="company_item" class="frm_input" size="30" placeholder="예) 호텔업 " value="<?php echo $room_row['company_item'];?>"></td>
				<th scope="row">종목</th>
				<td><input type="text" name="company_service" class="frm_input" size="30" placeholder="예) 숙박서비스" value="<?php echo $room_row['company_service'];?>"></td>
			</tr>
			<tr>
				<th scope="row">등급</th>
				<td>
					<select id="room_grade" name="room_grade" class="required frm_input" required style="width:100px;" <?php if($room_row['seller_item'] != '호텔') {?> disabled <?php }?>>
						<option value="">선택</option>
						<option value="1" <?php if($room_row['room_grade'] == '1') { ?>selected <?php } ?>>1성</option>
						<option value="2" <?php if($room_row['room_grade'] == '2') { ?>selected <?php } ?>>2성</option>
						<option value="3" <?php if($room_row['room_grade'] == '3') { ?>selected <?php } ?>>3성</option>
						<option value="4" <?php if($room_row['room_grade'] == '4') { ?>selected <?php } ?>>4성</option>
						<option value="5" <?php if($room_row['room_grade'] == '5') { ?>selected <?php } ?>>5성</option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row">전화번호</th>
				<td><input type="text" name="company_tel" required itemname="전화번호" class="required frm_input" size="30" placeholder="예) 02-1234-5678" value="<?php echo $room_row['company_tel'];?>"></td>
				<th scope="row">팩스번호</th>
				<td><input type="text" name="company_fax" class="frm_input" size="30" placeholder="예) 02-1234-5678" value="<?php echo $room_row['company_fax'];?>"></td>
			</tr>
			<tr>
				<th scope="row">사업장주소</th>
				<td colspan="3">
					<p><input type="text" name="company_zip" class="frm_input" size="8" maxlength="5" value="<?php echo $room_row['company_zip'];?>"> <a href="javascript:win_zip('fregform', 'company_zip', 'company_addr1', 'company_addr2', 'company_addr3', 'company_addr_jibeon');" class="btn_small grey">주소검색</a></p>
					<p class="mart3"><input type="text" name="company_addr1" class="frm_input" size="60" value="<?php echo $room_row['company_addr1'];?>"> 기본주소</p>
					<p class="mart3"><input type="text" name="company_addr2" class="frm_input" size="60" value="<?php echo $room_row['company_addr2'];?>"> 상세주소</p>
					<p class="mart3"><input type="text" name="company_addr3" class="frm_input" size="60" value="<?php echo $room_row['company_addr3'];?>"> 참고항목
					<input type="hidden" name="company_addr_jibeon" value="<?php echo $room_row['company_addr_jibeon'];?>"></p>
				</td>
			</tr>
			<tr>
				<th scope="row">홈페이지</th>
				<td colspan="3"><input type="text" name="company_hompage" class="frm_input" size="30" placeholder="http://" value="<?php echo $room_row['company_hompage'];?>"></td>
			</tr>
		</tbody>
	</table>
</div>

<section id="anc_sitfrm_img">
	<h2>대표 이미지</h2>
	<?php echo $pg_anchor; ?>
	<div class="tbl_frm01">
		<table>
			<colgroup>
				<col class="w180">
				<col>
			</colgroup>
			<tbody>
				<tr>
					<th scope="row">이미지 등록방식</th>
					<td class="td_label">
						<input type="radio" name="rep_img_type" id="simg_type_1" checked value="0"<?php echo get_checked('0', $room_row['rep_img_type']); ?> onclick="chk_simg_type(0);">
						<label for="simg_type_1">직접 업로드</label>
						<input type="radio" name="rep_img_type" id="simg_type_2" value="1"<?php echo get_checked('1', $room_row['rep_img_type']); ?> onclick="chk_simg_type(1);">
						<label for="simg_type_2">URL 입력</label>
					</td>
				</tr>

				<?php
				$item_wpx = $default['rep_logo_wpx'];
				$item_hpx = $default['rep_logo_hpx'];
				$image_str = ' <strong class="fc_red">[필수]</strong>';
				?>

				<tr class="item_img_fld">
					<th scope="row">대표 이미지 <span class="fc_197">(<?php echo $item_wpx; ?> * <?php echo $item_hpx; ?>)</span><?php echo $image_str; ?></th>
					<td>
						<div class="item_file_fld">
							<input type="file" name="simg">
							<?php
								if ($room_row['rep_img_type'] == '0') {
								echo get_look_ahead_rep($room_row['company_img'],"room", "simg_del");
								}
							?>
						</div>
						<div class="item_url_fld">
							<input type="text" name="simg" value="<?php echo $room_row['company_img']; ?>" class="frm_input" size="80" placeholder="http://">
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</section>

<h2>정산계좌 정보</h2>
<div class="tbl_frm01">
	<table class="tablef">
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">은행명</th>
			<td><input type="text" name="bank_name" class="frm_input" size="30" value="<?php echo $room_row['bank_name'];?>"></td>
		</tr>
		<tr>
			<th scope="row">계좌번호</th>
			<td><input type="text" name="bank_account" class="frm_input" size="30" value="<?php echo $room_row['bank_account'];?>"></td>
		</tr>
		<tr>
			<th scope="row">예금주명</th>
			<td><input type="text" name="bank_holder" class="frm_input" size="30" value="<?php echo $room_row['bank_holder'];?>"></td>
		</tr>
		</tbody>
	</table>
</div>

<h2>담당자 정보</h2>
<div class="tbl_frm01">
	<table class="tablef">
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tbody>
			<tr>
				<th scope="row">담당자명</th>
				<td><input type="text" name="info_name" class="frm_input" size="30" value="<?php echo $room_row['info_name'];?>"></td>
			</tr>
			<tr>
				<th scope="row">담당자 핸드폰</th>
				<td><input type="text" name="info_tel" class="frm_input" size="30" value="<?php echo $room_row['info_tel'];?>" numberOnly></td>
			</tr>
			<tr>
				<th scope="row">담당자 이메일</th>
				<td><input type="text" name="info_email" class="frm_input" size="30" value="<?php echo $room_row['info_email'];?>"></td>
			</tr>
		</tbody>
	</table>
</div>

<h2>업체수수료</h2>
<div class="tbl_frm01">
	<table class="tablef">
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
		<tr>
			<th scope="row">수수료율</th>
			<td colspan="3"><input type="number" step="0.01" name="fee" class="frm_input" size="5" maxlength="5" oninput="numberMaxLength(this);" value="<?php echo $room_row['fee'];?>"/> %</td>
		</tr>
	</tbody>
	</table>
</div>

<h2>시간정보 (요일별 체크인, 체크아웃시간 정보)</h2>
<div class="tbl_frm01">
	<table class="tablef">
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tbody>

			<?php
				// 요일별 체크인, 체크아웃 변수 값 자르기
				$monday = json_decode($room_row['use_mon'], true);
				$tuesday = json_decode($room_row['use_tue'], true);
				$wednesday = json_decode($room_row['use_wed'], true);
				$thursday = json_decode($room_row['use_thu'], true);
				$friday = json_decode($room_row['use_fri'], true);
				$saturday = json_decode($room_row['use_sat'], true);
				$sunday = json_decode($room_row['use_sun'], true);
				$holiday = json_decode($room_row['use_hol'], true);


		// var_dump($monday);
		// var_dump($monday["data"][0]["out_time"]);

			?>

			<tr>
				<td colspan="2">
					<!-- <input type="checkbox" name="use_mon"  value="Y" id="ck_mon" <?php echo $monday[0] == 'Y' ? 'checked' : ''; ?>/>-->
					<label for="ck_mon">월요일</label>
					<select name="mon_chk_in_time" >
						<option value="">체크인</option>
						<?php for($i=0;$i<24;$i++) {
							if($i < 10) {
							//echo option_selected("0".$i.":00", $monday[1], "0".$i.":00" );
							echo option_selected("0".$i.":00", $monday["data"][0]["in_time"], "0".$i.":00" );
							} else {
							echo option_selected($i.":00", $monday["data"][0]["in_time"], $i.":00" );
							}
						} ?>
					</select>

					<select name="mon_chk_out_time">
						<option value="">체크아웃</option>
						<?php for($i=0;$i<24;$i++) {
							if($i < 10) {
							echo option_selected("0".$i.":00", $monday["data"][0]["out_time"], "0".$i.":00" );
							} else {
							echo option_selected($i.":00", $monday["data"][0]["out_time"], $i.":00" );
							}
						} ?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<!-- <input type="checkbox" name="use_tue"  value="Y" id="ck_tue" <?php echo $tuesday[0] == 'Y' ? 'checked' : ''; ?>/>  -->
					<label for="ck_tue">화요일</label>
					<select name="tue_chk_in_time" >
						<option value="">체크인</option>
						<?php for($i=0;$i<24;$i++) {
							if($i < 10) {
							echo option_selected("0".$i.":00", $tuesday["data"][0]["in_time"], "0".$i.":00" );
							} else {
							echo option_selected($i.":00", $tuesday["data"][0]["in_time"], $i.":00" );
							}
						} ?>
					</select>

					<select name="tue_chk_out_time">
						<option value="">체크아웃</option>
						<?php for($i=0;$i<24;$i++) {
							if($i < 10) {
							echo option_selected("0".$i.":00", $tuesday["data"][0]["out_time"], "0".$i.":00" );
							} else {
							echo option_selected($i.":00", $tuesday["data"][0]["out_time"], $i.":00" );
							}
						} ?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<!-- <input type="checkbox" name="use_wed"  value="Y" id="ck_wed" <?php echo $wednesday[0] == 'Y' ? 'checked' : ''; ?>/>  -->
					<label for="ck_wed">수요일</label>
					<select name="wed_chk_in_time" >
						<option value="">체크인</option>
						<?php for($i=0;$i<24;$i++) {
							if($i < 10) {
							echo option_selected("0".$i.":00", $wednesday["data"][0]["in_time"], "0".$i.":00" );
							} else {
							echo option_selected($i.":00", $wednesday["data"][0]["in_time"], $i.":00" );
							}
						} ?>
					</select>

					<select name="wed_chk_out_time">
						<option value="">체크아웃</option>
						<?php for($i=0;$i<24;$i++) {
							if($i < 10) {
							echo option_selected("0".$i.":00", $wednesday["data"][0]["out_time"], "0".$i.":00" );
							} else {
							echo option_selected($i.":00", $wednesday["data"][0]["out_time"], $i.":00" );
							}
						} ?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<!-- <input type="checkbox" name="use_thu"  value="Y" id="ck_thu" <?php echo $thursday[0] == 'Y' ? 'checked' : ''; ?>/>  -->
					<label for="ck_thu">목요일</label>
					<select name="thu_chk_in_time" >
						<option value="">체크인</option>
						<?php for($i=0;$i<24;$i++) {
							if($i < 10) {
							echo option_selected("0".$i.":00", $thursday["data"][0]["in_time"], "0".$i.":00" );
							} else {
							echo option_selected($i.":00", $thursday["data"][0]["in_time"], $i.":00" );
							}
						} ?>
					</select>

					<select name="thu_chk_out_time">
						<option value="">체크아웃</option>
						<?php for($i=0;$i<24;$i++) {
							if($i < 10) {
							echo option_selected("0".$i.":00", $thursday["data"][0]["out_time"], "0".$i.":00" );
							} else {
							echo option_selected($i.":00", $thursday["data"][0]["out_time"], $i.":00" );
							}
						} ?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<!-- <input type="checkbox" name="use_fri"  value="Y" id="ck_fri" <?php echo $friday[0] == 'Y' ? 'checked' : ''; ?>/>  -->
					<label for="ck_fri">금요일</label>
					<select name="fri_chk_in_time" >
						<option value="">체크인</option>
						<?php for($i=0;$i<24;$i++) {
							if($i < 10) {
							echo option_selected("0".$i.":00", $friday["data"][0]["in_time"], "0".$i.":00" );
							} else {
							echo option_selected($i.":00", $friday["data"][0]["in_time"], $i.":00" );
							}
						} ?>
					</select>

					<select name="fri_chk_out_time">
						<option value="">체크아웃</option>
						<?php for($i=0;$i<24;$i++) {
							if($i < 10) {
							echo option_selected("0".$i.":00", $friday["data"][0]["out_time"], "0".$i.":00" );
							} else {
							echo option_selected($i.":00", $friday["data"][0]["out_time"], $i.":00" );
							}
						} ?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<!-- <input type="checkbox" name="use_sat"  value="Y" id="ck_sat" <?php echo $saturday[0] == 'Y' ? 'checked' : ''; ?>/>  -->
					<label for="ck_sat">토요일</label>
					<select name="sat_chk_in_time" >
						<option value="">체크인</option>
						<?php for($i=0;$i<24;$i++) {
							if($i < 10) {
							echo option_selected("0".$i.":00", $saturday["data"][0]["in_time"], "0".$i.":00" );
							} else {
							echo option_selected($i.":00",$saturday["data"][0]["in_time"], $i.":00" );
							}
						} ?>
					</select>

					<select name="sat_chk_out_time">
						<option value="">체크아웃</option>
						<?php for($i=0;$i<24;$i++) {
							if($i < 10) {
							echo option_selected("0".$i.":00", $saturday["data"][0]["out_time"], "0".$i.":00" );
							} else {
							echo option_selected($i.":00", $saturday["data"][0]["out_time"], $i.":00" );
							}
						} ?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<!-- <input type="checkbox" name="use_sun"  value="Y" id="ck_sun" <?php echo $sunday[0] == 'Y' ? 'checked' : ''; ?>/>  -->
					<label for="ck_sun">일요일</label>
					<select name="sun_chk_in_time" >
						<option value="">체크인</option>
						<?php for($i=0;$i<24;$i++) {
							if($i < 10) {
							echo option_selected("0".$i.":00", $sunday["data"][0]["in_time"], "0".$i.":00" );
							} else {
							echo option_selected($i.":00", $sunday["data"][0]["in_time"], $i.":00" );
							}
						} ?>
					</select>

					<select name="sun_chk_out_time">
						<option value="">체크아웃</option>
						<?php for($i=0;$i<24;$i++) {
							if($i < 10) {
							echo option_selected("0".$i.":00", $sunday["data"][0]["out_time"], "0".$i.":00" );
							} else {
							echo option_selected($i.":00", $sunday["data"][0]["out_time"], $i.":00" );
							}
						} ?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<!-- <input type="checkbox" name="use_hol"  value="Y" id="ck_hol" <?php echo $holiday[0] == 'Y' ? 'checked' : ''; ?>/>  -->
					<label for="ck_sun">공휴일</label>
					<select name="hol_chk_in_time" >
						<option value="">체크인</option>
						<?php for($i=0;$i<24;$i++) {
							if($i < 10) {
							echo option_selected("0".$i.":00", $holiday["data"][0]["in_time"], "0".$i.":00" );
							} else {
							echo option_selected($i.":00", $holiday["data"][0]["in_time"], $i.":00" );
							}
						} ?>
					</select>

					<select name="hol_chk_out_time">
						<option value="">체크아웃</option>
						<?php for($i=0;$i<24;$i++) {
							if($i < 10) {
							echo option_selected("0".$i.":00", $holiday["data"][0]["out_time"], "0".$i.":00" );
							} else {
							echo option_selected($i.":00", $holiday["data"][0]["out_time"], $i.":00" );
							}
						} ?>
					</select>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<h2>기본정보</h2>
<div class="tbl_frm01">
	<table class="tablef">
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">
			투숙가능 제한 나이 <br /> (예약자기준)
		</th>
		<td class="td_label">
			<select id="ageLimit_val" name="ageLimit_val">
				<option value=""></option>
				<option value="17" <?php if($room_row['ageLimit_val'] == '17') {?> selected <?php } ?>>만 17세</option>
				<option value="18" <?php if($room_row['ageLimit_val'] == '18') {?> selected <?php } ?>>만 18세</option>
				<option value="19" <?php if($room_row['ageLimit_val'] == '19') {?> selected <?php } ?>>만 19세</option>
				<option value="20" <?php if($room_row['ageLimit_val'] == '20') {?> selected <?php } ?>>만 20세</option>
				<option value="21" <?php if($room_row['ageLimit_val'] == '21') {?> selected <?php } ?>>만 21세</option>
				<option value="22" <?php if($room_row['ageLimit_val'] == '22') {?> selected <?php } ?>>만 22세</option>
			</select>.

			<span id="ageLimit_status" style="padding-left:10px;">
				<?php if($room_row['ageLimit_val']) { ?>
						만 <?php echo $room_row['ageLimit_val']+1; ?>세부터 투숙가능합니다.
				<?php } ?>
			</span>
		</td>
	</tr>

	<tr>
		<th scope="row">기본정보<br />(숙소소개)</th>
		<td>
		<textarea name="basic_info" placeholder="직접입력"><?php echo $room_row['basic_info'];?></textarea></td>
	</tr>
	</tbody>
	</table>
</div>

<!-- <h2>인원 추가 정보</h2>
<div class="tbl_frm01">
	<table class="tablef">
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">인원 추가 정보</th>
		<td>
		<textarea name="add_ppl_info"><?php echo $room_row['add_ppl_info'];?></textarea></td>
	</tr>
	</tbody>
	</table>
</div> -->

<h2>객실 이용안내</h2>
<div class="tbl_frm01">
	<table class="tablef">
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">객실 이용안내</th>
		<td>
		<textarea name="room_info" placeholder="직접입력"><?php echo $room_row['room_info'];?></textarea></td>
	</tr>
	</tbody>
	</table>
</div>

<!-- <h2>예약정보</h2>
<div class="tbl_frm01">
	<table class="tablef">
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">예약가능 개월수</th>
			<td>오늘부터 <input type="text" name="res_month" class="frm_input input_text_margin" value="<?php echo $room_row['res_month'];?>" size="5" maxlength="3" numberOnly> 개월 이내의 날짜만 예약가능</td>
		</tr>
		<tr>
			<th scope="row">예약가능 일수</th>
			<td>최대 <input type="text" name="res_day" class="frm_input input_text_margin" value="<?php echo $room_row['res_day'];?>" size="5" maxlength="2" numberOnly>일 까지 예약가능 <span class="rv_ex">예) 4일인 경우 3박 4일까지 예약가능</span></td>
		</tr>
		</tbody>
		</table>
	</div> -->

	<h2>위치정보</h2>
	<div class="tbl_frm01">
		<table class="tablef">
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tbody>
			<!-- <tr>
				<th scope="row">지역</th>
				<td>
					<select name="region">
						<option ="">지역</option>
						<option value="서울">서울</option>
						<option value="경기">경기</option>
						<option value="인천">인천</option>
						<option value="강원">강원</option>
						<option value="제주">제주</option>
						<option value="부산">부산</option>
						<option value="경남">경남</option>
						<option value="대구">대구</option>
						<option value="경북">경북</option>
						<option value="울산">울산</option>
						<option value="대전">대전</option>
						<option value="충남">충남</option>
						<option value="충북">충북</option>
						<option value="광주">광주</option>
						<option value="전남">전남</option>
						<option value="전북">전북</option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row">지역상세</th>
				<td>
					<select name="region_detail">
						<option value="">지역상세</option>
					</select>
					 //지역별 상세지역(여기어떄나 야놀자 참고)
				</td>
			</tr> -->
			<tr>
				<th scope="row">짧은주소설명</th>
				<td>
					<input type="text" name="short_addr" id="short_addr" class="frm_input" size="55" placeholder="강남역 1번출구 도보 2분" value="<?php echo $room_row['short_addr'];?>">
					<span id="short_addr_cnt">(0 / 30)</span>
				</td>
			</tr>
			<tr>
				<th scope="row">위치상세 <br />(찾아오시는 길)</th>
				<td>
					<textarea name="location_desc" placeholder="찾아오시는 길"><?php echo $room_row['location_desc'];?></textarea>
				</td>
			</tr>
			<tr>
				<th scope="row">주차장 정보</th>
				<td>
					<textarea name="parking_info" placeholder="주차장 보유시 간략한 정보 기입"><?php echo $room_row['parking_info'];?></textarea>
				</td>
			</tr>
		</tbody>
		</table>
	</div>

<h2>편의시설 및 서비스</h2>
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
				추가 편의시설 </br>및 서비스 정보
			</th>
			<td colspan="<?php echo $facMaxNum['facMaxNum']?>">
				<textarea id="fac_remark" name="fac_remark" rows="8" cols="50" placeholder="체크박스에 없는 편의시설 및 서비스 직접입력"><?php echo $room_row['fac_remark'] ?></textarea>
			</td>
		</tr>
	</table>
</div>

<!-- <h2>식사관리</h2>
<div class="tbl_frm01">
	<table class="tablef">
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tbody>
			<tr>
				<th scope="row">식사</th>
				<td>
					<input type="radio" name="food" id="bf_y" value="Y"<?php echo get_checked('Y', $room_row['food']); ?>>
					<label for="bf_y">YES</label>
					<input type="radio" name="food" id="bf_n" <?php if($w != 'u') {?> checked <?php } ?> value="N"<?php echo get_checked('N', $room_row['food']); ?>>
					<label for="bf_n">NO</label>
				</td>
			</tr>
			<tr class="breakfast_price">

				<th scope="row">조식 가격</th>
				<td>
					<div class="bk_div">
						성인 <input type="text" name="food_adults" value="<?php echo $breakfast_ad['food_amnt'];?>" class="frm_input input_text_margin" size="20" maxlength="8" numberOnly> 원
					</div>
					<div class="bk_div">
						소인 <input type="text" name="food_kids" value="<?php echo $breakfast_kd['food_amnt'];?>"class="frm_input input_text_margin" size="20" maxlength="8" numberOnly> 원
					</div>
				</td>
			</tr> -->

		<!--
			<tr class="lunch_price">

				<th scope="row">중식 가격</th>
				<td>
					<div class="bk_div">
						성인 <input type="text" name="food_adults" value="" class="frm_input input_text_margin" size="20" maxlength="8" numberOnly> 원
					</div>
					<div class="bk_div">
						소인 <input type="text" name="food_kids" class="frm_input input_text_margin" size="20" maxlength="8" numberOnly> 원
					</div>
				</td>
			</tr>

			<tr class="dinner_price">

				<th scope="row">석식 가격</th>
				<td>
					<div class="bk_div">
						성인 <input type="text" name="food_adults" value="" class="frm_input input_text_margin" size="20" maxlength="8" numberOnly> 원
					</div>
					<div class="bk_div">
						소인 <input type="text" name="food_kids" class="frm_input input_text_margin" size="20" maxlength="8" numberOnly> 원
					</div>
				</td>
			</tr> -->

			<!-- <tr>
				<th scope="row">조식 정보</th>
				<td>
				<textarea name="food_remarks"><?php echo $room_row['food_remarks'];?></textarea></td>
			</tr>
		</tbody>
	</table>
</div> -->

<h2>취소 및 환불 규정</h2>
<div class="tbl_frm01">
	<table class="tablef">
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
		<!-- <tr>
			<th scope="row">비수기</th>
			<td>
				<div class="fees_div">
					체크인 <input type="text" name="b_ck_day1" class="frm_input input_text_margin" value="<?php echo $room_row['b_ck_day1'];?>" size="3" maxlength="2" numberOnly> 일
					<input type="text" name="b_ck_hour1" class="frm_input input_text_margin" value="<?php echo $room_row['b_ck_hour1'];?>" size="3" maxlength="2" numberOnly> 시 까지
					<input type="text" name="b_ck_rfd1" class="frm_input input_text_margin" value="<?php echo $room_row['b_ck_rfd1'];?>" size="4" maxlength="3" numberOnly>% 환불
				</div>
				<div class="fees_div">
					체크인 <input type="text" name="b_ck_day2" class="frm_input input_text_margin" value="<?php echo $room_row['b_ck_day2'];?>" size="3" maxlength="2" numberOnly> 일
					<input type="text" name="b_ck_hour2" class="frm_input input_text_margin" value="<?php echo $room_row['b_ck_hour2'];?>" size="3" maxlength="2" numberOnly> 시 까지
					<input type="text" name="b_ck_rfd2" class="frm_input input_text_margin" value="<?php echo $room_row['b_ck_rfd2'];?>" size="4" maxlength="3" numberOnly>% 환불
				</div>
				<div class="fees_div">
					체크인 <input type="text" name="b_ck_day3" class="frm_input input_text_margin" value="<?php echo $room_row['b_ck_day3'];?>" size="3" maxlength="2" numberOnly> 일
					<input type="text" name="b_ck_hour3" class="frm_input input_text_margin" value="<?php echo $room_row['b_ck_hour3'];?>" size="3" maxlength="2" numberOnly> 시 까지
					<input type="text" name="b_ck_rfd3" class="frm_input input_text_margin" value="<?php echo $room_row['b_ck_rfd3'];?>" size="4" maxlength="3" numberOnly>% 환불
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row">성수기</th>
			<td>
				<div class="fees_div">
					체크인 <input type="text" name="s_ck_day1" class="frm_input input_text_margin" value="<?php echo $room_row['s_ck_day1'];?>" size="3" maxlength="2" numberOnly> 일
					<input type="text" name="s_ck_hour1" class="frm_input input_text_margin" value="<?php echo $room_row['s_ck_hour1'];?>" size="3" maxlength="2" numberOnly> 시 까지
					<input type="text" name="s_ck_rfd1" class="frm_input input_text_margin" value="<?php echo $room_row['s_ck_rfd1'];?>" size="4" maxlength="3" numberOnly>% 환불
				</div>
				<div class="fees_div">
					체크인 <input type="text" name="s_ck_day2" class="frm_input input_text_margin" value="<?php echo $room_row['s_ck_day2'];?>" size="3" maxlength="2" numberOnly> 일
					<input type="text" name="s_ck_hour2" class="frm_input input_text_margin" value="<?php echo $room_row['s_ck_hour2'];?>" size="3" maxlength="2" numberOnly> 시 까지
					<input type="text" name="s_ck_rfd2" class="frm_input input_text_margin" value="<?php echo $room_row['s_ck_rfd2'];?>" size="4" maxlength="3" numberOnly>% 환불
				</div>
				<div class="fees_div">
					체크인 <input type="text" name="s_ck_day3" class="frm_input input_text_margin" value="<?php echo $room_row['s_ck_day3'];?>" size="3" maxlength="2" numberOnly> 일
					<input type="text" name="s_ck_hour3" class="frm_input input_text_margin" value="<?php echo $room_row['s_ck_hour3'];?>" size="3" maxlength="2" numberOnly> 시 까지
					<input type="text" name="s_ck_rfd3" class="frm_input input_text_margin" value="<?php echo $room_row['s_ck_rfd3'];?>" size="4" maxlength="3" numberOnly>% 환불
				</div>
			</td>
		</tr> -->
		<tr>
			<th scope="row">취소 및 환불규정</th>
			<td>
			<textarea name="cnc_rfnd_policy" placeholder="직접입력"><?php echo $room_row['cnc_rfnd_policy'];?></textarea></td>
		</tr>
	</tbody>
</table>
</div>

<h2>확인사항 및 기타</h2>
<div class="tbl_frm01">
	<table class="tablef">
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">확인사항 및 기타</th>
		<td>
		<textarea name="confirmations_other" placeholder="직접입력"><?php echo $room_row['confirmations_other'];?></textarea></td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="저장" id="btn_submit" class="btn_large" accesskey="s">
</div>
</form>


<script>

//숙박시설에 따른 등급 활성화/비활성화
$('#seller_item').change(function(){
	//호텔일시 비활성화
	if($(this).val() == '호텔') {
		$('#room_grade').prop('disabled', false);
	}
	else {
		$('#room_grade').prop('disabled', true);
	}
});

//투숙가능 제한 나이에 따른 상태문장 나타내기
$('#ageLimit_val').change(function(){
	var ageLimit_val = $('#ageLimit_val option:selected').val();

	if(ageLimit_val == null || ageLimit_val == '') {
		$('#ageLimit_status').text("");
	} else {
		var new_age = parseInt(ageLimit_val) + 1;
		$('#ageLimit_status').text("만 "  + new_age + "세부터 투숙가능합니다.");
	}
});

// 짧은 주소 설명 글자수 새기
$('#short_addr').on('keyup', function() {
			$('#short_addr_cnt').html("("+$(this).val().length+" / 30)");

			if($(this).val().length > 30) {
					$(this).val($(this).val().substring(0, 30));
					$('#short_addr_cnt').html("(30 / 30)");
			}
	});

$(function() {
	<?php if($config['cf_cert_use'] && $config['cf_cert_ipin']) { ?>
	// 아이핀인증
	$("#win_ipin_cert").click(function() {
		if(!cert_confirm())
			return false;

		var url = "<?php echo TB_OKNAME_URL; ?>/ipin1.php";
		certify_win_open('kcb-ipin', url);
		return;
	});

	<?php } ?>
	<?php if($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
	// 휴대폰인증
	$("#win_hp_cert").click(function() {
		if(!cert_confirm())
			return false;

		<?php
		switch($config['cf_cert_hp']) {
			case 'kcb':
				$cert_url = TB_OKNAME_URL.'/hpcert1.php';
				$cert_type = 'kcb-hp';
				break;
			case 'kcp':
				$cert_url = TB_KCPCERT_URL.'/kcpcert_form.php';
				$cert_type = 'kcp-hp';
				break;
			case 'lg':
				$cert_url = TB_LGXPAY_URL.'/AuthOnlyReq.php';
				$cert_type = 'lg-hp';
				break;
			default:
				echo 'alert("기본환경설정에서 휴대폰 본인인증 설정을 해주십시오");';
				echo 'return false;';
				break;
		}
		?>

		certify_win_open("<?php echo $cert_type; ?>", "<?php echo $cert_url; ?>");
		return;
	});
	<?php } ?>
});



//부대시설 열별 체크박스 클릭했을시
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


$("input:text[numberOnly]").on("keyup", function() {
  $(this).val($(this).val().replace(/[^0-9]/g,""));
});

//시간정보 요일별 체크하기
$("#ck_mon").click(function(){

	  var chk = $(this).is(":checked");

	  //체크 안된 상태에서 체크하기
	  if(chk){
	    $(this).val('Y');
	  }
	  //체크 된 상태에서 체크 풀기
	  else {
	    $(this).val('N');
	  }
});

$("#ck_tue").click(function(){

	  var chk = $(this).is(":checked");

	  //체크 안된 상태에서 체크하기
	  if(chk){
	    $(this).val('Y');
	  }
	  //체크 된 상태에서 체크 풀기
	  else {
	    $(this).val('N');
	  }
});

$("#ck_wed").click(function(){

	  var chk = $(this).is(":checked");

	  //체크 안된 상태에서 체크하기
	  if(chk){
	    $(this).val('Y');
	  }
	  //체크 된 상태에서 체크 풀기
	  else {
	    $(this).val('N');
	  }
});

$("#ck_thu").click(function(){

	  var chk = $(this).is(":checked");

	  //체크 안된 상태에서 체크하기
	  if(chk){
	    $(this).val('Y');
	  }
	  //체크 된 상태에서 체크 풀기
	  else {
	    $(this).val('N');
	  }
});

$("#ck_fri").click(function(){

	  var chk = $(this).is(":checked");

	  //체크 안된 상태에서 체크하기
	  if(chk){
	    $(this).val('Y');
	  }
	  //체크 된 상태에서 체크 풀기
	  else {
	    $(this).val('N');
	  }
});

$("#ck_sat").click(function(){

	  var chk = $(this).is(":checked");

	  //체크 안된 상태에서 체크하기
	  if(chk){
	    $(this).val('Y');
	  }
	  //체크 된 상태에서 체크 풀기
	  else {
	    $(this).val('N');
	  }
});

$("#ck_sun").click(function(){

	  var chk = $(this).is(":checked");

	  //체크 안된 상태에서 체크하기
	  if(chk){
	    $(this).val('Y');
	  }
	  //체크 된 상태에서 체크 풀기
	  else {
	    $(this).val('N');
	  }
});

//식사관리 조식,중식,석식 보이지 않고 시작
// $(function() {
// 	var seller_item = $("#seller_item_temp").val();
// 		$("#seller_item").val(seller_item).prop("selected", true); //값이 1인 option 선택
// 		$(".breakfast_price").hide();
// 		$(".lunch_price").hide();
// 		$(".dinner_price").hide();
//
// 	//로딩시 SHOW
// var value = $('#bf_flag').val();
// 	if(value == "N") {
// 		$(".breakfast_price").hide();
// 		$(".lunch_price").hide();
// 		$(".dinner_price").hide();
// 	} else if(value == "Y") {
// 		$(".breakfast_price").show();
// 		$(".lunch_price").hide();
// 		$(".dinner_price").hide();
// 	}
// });


//저장버튼 눌렀을 시
function fregform_submit(f) {
	//추천인 아이디 검사
	var pt_id_chk = $("#pt_id_chk").val();
	if(pt_id_chk == "" || pt_id_chk == "N") {
		alert("추천인 아이디 확인이 필요합니다.");
		$("#reg_mb_recommend").focus();
		return false;
	}

	// 회원아이디 검사
	if(f.w.value == "") {

		var msg = reg_mb_id_check();

		if(msg) {
			alert(msg);
			f.mb_id.select();
			return false;
		}
	}

	if(f.w.value == "") {
		if(f.mb_password.value.length < 4) {
			alert("비밀번호를 4글자 이상 입력하십시오.");
			f.mb_password.focus();
			return false;
		}
	}

	if(f.mb_password.value != f.mb_password_re.value) {
		alert("비밀번호가 같지 않습니다.");
		f.mb_password_re.focus();
		return false;
	}

	if(f.mb_password.value.length > 0) {
		if(f.mb_password_re.value.length < 4) {
			alert("비밀번호를 4글자 이상 입력하십시오.");
			f.mb_password_re.focus();
			return false;
		}
	}

	// 이름 검사
	if(f.w.value=="") {
		if(f.mb_name.value.length < 1) {
			alert("이름을 입력하십시오.");
			f.mb_name.focus();
			return false;
		}

		/*
		var pattern = /([^가-힣\x20])/i;
		if(pattern.test(f.mb_name.value)) {
			alert("이름은 한글로 입력하십시오.");
			f.mb_name.select();
			return false;
		}
		*/
	}


	// E-mail 검사
	if((f.w.value == "") || (f.w.value == "u" && f.mb_email.defaultValue != f.mb_email.value)) {
		var msg = reg_mb_email_check();
		if(msg) {
			alert(msg);
			f.reg_mb_email.select();
			return false;
		}
	}

	<?php if(($config['register_use_hp'] || $config['cf_cert_hp']) && $config['register_req_hp']) { ?>
	// 휴대폰번호 체크
	var msg = reg_mb_hp_check();
	if(msg) {
		alert(msg);
		f.reg_mb_hp.select();
		return false;
	}
	<?php } ?>

	if(typeof(f.mb_recommend) != "undefined" && f.mb_recommend.value) {
		if(f.mb_id.value == f.mb_recommend.value) {
			alert("본인을 추천할 수 없습니다.");
			f.mb_recommend.focus();
			return false;
		}

		var msg = reg_mb_recommend_check();
		if(msg) {
			alert(msg);
			f.mb_recommend.select();
			return false;
		}
	}

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

	if(!f.ca_id.value) {
				alert("카테고리를 하나이상 선택하세요.");
				return false;
		}

	var sel_count = $("select#sel_ca_id option").size();
	if(sel_count > 3) {
		alert('카테고리는 최대 3개까지만 등록 가능합니다.');
		return false;
	}

	if(confirm("등록 하시겠습니까?") == false)
		return false;

	document.getElementById("btn_submit").disabled = "disabled";

	f.action = "./room/room_register_update.php";
    return true;
}

//  $("input:radio[name='breakfast']").change(function() {
//  	if($(this).val() == "y") {
//  		$(".breakfast_price").show();
//  	} else {
//  		$(".breakfast_price").hide();
//  	}
// });

$("#amenities_add").click(function() {
	var amt = $("#amenities_text").val();
	if(amt == "") {
		alert("입력칸이 공백입니다.");
	} else {
		$(".add_ameities").append(
			'<span class="amenities_span"><input type="checkbox" name="" id="'+amt+'" class="frm_input" value="y" /> <label for="'+amt+'">'+amt+'</label></span> '
		);
	}
});

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


// 조식여부
// $('input:radio[name="food"]').change(function() {
// 	var value = $(this).val();
//
// 	if(value == "N") {
// 		$(".breakfast_price").hide();
// 		$(".lunch_price").hide();
// 		$(".dinner_price").hide();
// 	} else if(value == "Y") {
// 		$(".breakfast_price").show();
// 		$(".lunch_price").hide();
// 		$(".dinner_price").hide();
// 	}
//
//
// 	//console.log(value);
// 	});

function numberMaxLength(e){
	if(e.value.length > e.maxLength) {
			e.value = e.value.slice(0, e.maxLength);
	}
}

chk_simg_type("<?php echo $room_row['rep_img_type']; ?>");

</script>
