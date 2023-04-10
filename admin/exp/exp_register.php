<?php
if(!defined('_TUBEWEB_')) exit;

$sql = "select * from hi_exp where mb_id= '{$mb_id}'";
$exp_row = sql_fetch($sql);
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
<input type="hidden" id="seller_item_temp" name="seller_item_temp" value ="<?php echo $exp_row['seller_item'];?>" />

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
							if($ca_id1 = adm_category_navi($exp_row['ca_id']))
								echo '<option value="'.$exp_row['ca_id'].'">' .$ca_id1. '</option>'.PHP_EOL;
							if($ca_id2 = adm_category_navi($exp_row['ca_id2']))
								echo '<option value="'.$exp_row['ca_id2'].'">'.$ca_id2.'</option>'.PHP_EOL;
							if($ca_id3 = adm_category_navi($exp_row['ca_id3']))
								echo '<option value="'.$exp_row['ca_id3'].'">'.$ca_id3.'</option>'.PHP_EOL;
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
<div class="tbl_frm01 tbl_wrap">
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
<div class="tbl_frm01 tbl_wrap">
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
		url: "./exp/exp_register_rgn_branch.ajax.php",
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
		url: "./exp/exp_register_id_chk.ajax.php",
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
				<th scope="row">업체명</th>
				<td><input type="text" name="company_name" required itemname="업체명" class="required frm_input" size="30" value="<?php echo $exp_row['company_name'];?>"></td>
				<th scope="row">상호명</th>
				<td><input type="text" name="business_name" required itemname="상호명" class="required frm_input" size="30" value="<?php echo $exp_row['business_name'];?>"></td>
			</tr>
			<tr>
				<th scope="row">대표자명</th>
				<td><input type="text" name="company_owner" required itemname="대표자명" class="required frm_input" size="30" value="<?php echo $exp_row['company_owner'];?>"></td>
				<th scope="row">사업자등록번호</th>
				<td><input type="text" name="company_saupja_no" class="frm_input" size="30" placeholder="예) 000-00-00000" value="<?php echo $exp_row['company_saupja_no'];?>"></td>
			</tr>
			<tr>
				<th scope="row">대표전화번호</th>
				<td><input type="text" name="company_tel" required itemname="전화번호" class="required frm_input" size="30" placeholder="예) 02-1234-5678" value="<?php echo $exp_row['company_tel'];?>"></td>
				<th scope="row">팩스번호</th>
				<td><input type="text" name="company_fax" class="frm_input" size="30" placeholder="예) 02-1234-5678" value="<?php echo $exp_row['company_fax'];?>"></td>
			</tr>
			<tr>
				<th scope="row">사업장주소</th>
				<td colspan="3">
					<p><input type="text" name="company_zip" class="frm_input" size="8" maxlength="5" value="<?php echo $exp_row['company_zip'];?>"> <a href="javascript:win_zip('fregform', 'company_zip', 'company_addr1', 'company_addr2', 'company_addr3', 'company_addr_jibeon');" class="btn_small grey">주소검색</a></p>
					<p class="mart3"><input type="text" name="company_addr1" class="frm_input" size="60" value="<?php echo $exp_row['company_addr1'];?>"> 기본주소</p>
					<p class="mart3"><input type="text" name="company_addr2" class="frm_input" size="60" value="<?php echo $exp_row['company_addr2'];?>"> 상세주소</p>
					<p class="mart3"><input type="text" name="company_addr3" class="frm_input" size="60" value="<?php echo $exp_row['company_addr3'];?>"> 참고항목
					<input type="hidden" name="company_addr_jibeon" value="<?php echo $exp_row['company_addr_jibeon'];?>"></p>
				</td>
			</tr>
			<tr>
				<th scope="row">홈페이지</th>
				<td colspan="3"><input type="text" name="company_hompage" class="frm_input" size="30" placeholder="http://" value="<?php echo $exp_row['company_hompage'];?>"></td>
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
						<input type="radio" name="rep_img_type" id="simg_type_1" checked value="0"<?php echo get_checked('0', $exp_row['rep_img_type']); ?> onclick="chk_simg_type(0);">
						<label for="simg_type_1">직접 업로드</label>
						<input type="radio" name="rep_img_type" id="simg_type_2" value="1"<?php echo get_checked('1', $exp_row['rep_img_type']); ?> onclick="chk_simg_type(1);">
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
								if ($exp_row['rep_img_type'] == '0') {
								echo get_look_ahead_rep($exp_row['company_img'],"exp", "simg_del");
								}
							?>
						</div>
						<div class="item_url_fld">
							<input type="text" name="simg" value="<?php echo $exp_row['company_img']; ?>" class="frm_input" size="80" placeholder="http://">
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
			<td><input type="text" name="bank_name" class="frm_input" size="30" value="<?php echo $exp_row['bank_name'];?>"></td>
		</tr>
		<tr>
			<th scope="row">계좌번호</th>
			<td><input type="text" name="bank_account" class="frm_input" size="30" value="<?php echo $exp_row['bank_account'];?>"></td>
		</tr>
		<tr>
			<th scope="row">예금주명</th>
			<td><input type="text" name="bank_holder" class="frm_input" size="30" value="<?php echo $exp_row['bank_holder'];?>"></td>
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
				<td><input type="text" name="info_name" class="frm_input" size="30" value="<?php echo $exp_row['info_name'];?>"></td>
			</tr>
			<tr>
				<th scope="row">담당자 핸드폰</th>
				<td><input type="text" name="info_tel" class="frm_input" size="30" value="<?php echo $exp_row['info_tel'];?>" numberOnly></td>
			</tr>
			<tr>
				<th scope="row">담당자 이메일</th>
				<td><input type="text" name="info_email" class="frm_input" size="30" value="<?php echo $exp_row['info_email'];?>"></td>
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
		<th scope="row">기본정보</th>
		<td>
		<textarea name="basic_info" placeholder=""><?php echo $exp_row['basic_info'];?></textarea></td>
	</tr>
	</tbody>
	</table>
</div>

	<h2>위치정보</h2>
	<div class="tbl_frm01">
		<table class="tablef">
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">위치상세</th>
			<td>
			<textarea name="location_desc" placeholder="찾아가는 길"><?php echo $exp_row['location_desc'];?></textarea></td>
		</tr>
		<tr>
			<th scope="row">주차유무</th>
			<td>
				<input type="radio" name="parking" class="frm_input" value="Y" <?php if($exp_row['parking'] == 'Y') { echo checked; }?> id="parking1"><label for="parking1">YES</label>
				<input type="radio" name="parking" class="frm_input" value="N"  <?php if($exp_row['parking'] == 'N') { echo checked; }?> id="parking2"><label for="parking2">NO</label>
			</td>
		</tr>
		</tbody>
		</table>
	</div>

	<h2>운영시간 정보</h2>
	<div class="tbl_frm01">
		<table class="tablef">
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tbody>
			<tr>
				<th scope="row">주중</th>
				<td><input type="time" name="businessHours1" class="frm_input" style="width:120px;" value="<?php echo $exp_row['businessHours1'];?>"> ~ <input type="time" name="businessHours2" class="frm_input" style="width:120px;" value="<?php echo $exp_row['businessHours2'];?>"></td>
			</tr>
			<tr>
				<th scope="row">주말·공휴일</th>
				<td><input type="time" name="offday_time1" class="frm_input" style="width:120px;" value="<?php echo $exp_row['offday_time1'];?>"> ~ <input type="time" name="offday_time2" class="frm_input" style="width:120px;" value="<?php echo $exp_row['offday_time2'];?>"></td>
			</tr>
		</tbody>
		</table>
	</div>

	<h2>환불규정</h2>
	<div class="tbl_frm01">
		<table class="tablef">
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tbody>
			<tr>
				<th scope="row">취소 및 환불규정</th>
				<td>
				<textarea name="cnc_rfnd_policy" placeholder="<?php echo "- 사용한 티켓은 환불 불가능합니다. \n- 유효기간 내 미사용티켓 100% 환불가능";?>"><?php echo $exp_row['cnc_rfnd_policy'];?></textarea></td>
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
		<textarea name="confirmations_other"><?php echo $exp_row['confirmations_other'];?></textarea></td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="저장" id="btn_submit" class="btn_large" accesskey="s">
</div>
</form>


<script>
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


$("input:text[numberOnly]").on("keyup", function() {
  $(this).val($(this).val().replace(/[^0-9]/g,""));
});

$("#parking1").click(function(){

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

$("#parking2").click(function(){

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

	f.action = "./exp/exp_register_update.php";
    return true;
}


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




chk_simg_type("<?php echo $exp_row['rep_img_type']; ?>");

</script>
