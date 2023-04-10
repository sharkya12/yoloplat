<?php
if(!defined('_TUBEWEB_')) exit;

$sql = "select * from hi_rent where mb_id= '{$mb_id}'";
$rent_row = sql_fetch($sql);

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

<script src="<?php echo TB_JS_URL; ?>/categoryform.js?ver=<?php echo TB_JS_VER; ?>"></script>
<script src="<?php echo TB_JS_URL; ?>/categorylist.js?ver=<?php echo TB_JS_VER; ?>"></script>

<!-- 회원정보 입력/수정 시작 { -->
<h2 class="pg_tit">
	<span><?php echo $tb['title']; ?></span>
	<p class="pg_nav">HOME<i>&gt;</i><?php echo $tb['title']; ?></p>
</h2>

<script src="<?php echo TB_JS_URL; ?>/jquery.register_form.js"></script>
<?php if($config['cf_cert_use'] && ($config['cf_cert_ipin'] || $config['cf_cert_hp'])) { ?>
<script src="<?php echo TB_JS_URL; ?>/certify.js?v=<?php echo TB_JS_VER; ?>"></script>
<?php } ?>

<form name="fregisterform" id="fregisterform" action="<?php echo $register_action_url; ?>" onsubmit="return fregisterform_submit(this);" method="post" autocomplete="off">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="agree" value="<?php echo $agree; ?>">
<input type="hidden" name="agree2" value="<?php echo $agree2; ?>">
<input type="hidden" name="cert_type" value="<?php echo $member['mb_certify']; ?>">
<input type="hidden" name="cert_no" value="">
<input type="hidden" name="ca_id" value="">
<input type="hidden" name="ca_id2" value="">
<input type="hidden" name="ca_id3" value="">

<h3>사이트 이용정보 입력</h3>
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
			<input type="text" name="mb_id" value="<?php echo $member['id'] ?>" id="reg_mb_id"<?php echo $required; ?><?php echo $readonly; ?> class="frm_input<?php echo $required; ?>" size="20" maxlength="20">
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

<h3 class="mart30">개인정보 입력</h3>
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
			<input type="text" name="mb_name" value="<?php echo get_text($member['name']); ?>" id="reg_mb_name"<?php echo $required; ?><?php echo $readonly; ?> class="frm_input<?php echo $required; ?>" size="20">
			<?php
			if($config['cf_cert_use']) {
				if($config['cf_cert_ipin'])
					echo '<button type="button" id="win_ipin_cert" class="btn_small">아이핀 본인인증</button>'.PHP_EOL;
				if($config['cf_cert_hp'])
					echo '<button type="button" id="win_hp_cert" class="btn_small">휴대폰 본인인증</button>'.PHP_EOL;

				echo '<noscript>본인인증을 위해서는 자바스크립트 사용이 가능해야합니다.</noscript>'.PHP_EOL;
			}
			if($config['cf_cert_use'] && $member['mb_certify']) {
				if($member['mb_certify'] == 'ipin')
					$mb_cert = '아이핀';
				else
					$mb_cert = '휴대폰';
			?>
			<div id="msg_certify">
				<strong><?php echo $mb_cert; ?> 본인인증</strong><?php if($member['mb_adult']) { ?> 및 <strong>성인인증</strong><?php } ?> 완료
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
		<td><input type="text" name="mb_tel" value="<?php echo get_text($member['telephone']); ?>" id="reg_mb_tel"<?php echo $config['register_req_tel']?' required':''; ?> class="frm_input<?php echo $config['register_req_tel']?' required':''; ?>" size="20" maxlength="20"></td>
	</tr>
	<?php } ?>
	<?php if($config['register_use_hp'] || $config['cf_cert_hp']) { ?>
	<tr>
		<th scope="row"><label for="reg_mb_hp">휴대폰번호</label></th>
		<td>
			<input type="text" name="mb_hp" value="<?php echo get_text($member['cellphone']); ?>" id="reg_mb_hp"<?php echo $config['register_req_hp']?' required':''; ?> class="frm_input<?php echo $config['register_req_hp']?' required':''; ?>" size="20" maxlength="20">
			<span class="frm_info">
				<label><input type="checkbox" name="mb_sms" value="Y"<?php echo ($w=='' || $member['smsser'] == 'Y')?' checked':''; ?>> 휴대폰 문자메세지를 받겠습니다.</label>
			</span>
			<?php if($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
			<input type="hidden" name="old_mb_hp" value="<?php echo get_text($member['cellphone']); ?>">
			<?php } ?>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<th scope="row"><label for="reg_mb_email">E-mail</label></th>
		<td>
			<input type="hidden" name="old_email" value="<?php echo $member['email']; ?>">
			<input type="text" name="mb_email" value="<?php echo isset($member['email'])?$member['email']:''; ?>" id="reg_mb_email" required class="frm_input required" size="40" maxlength="100">
			<span class="frm_info">
				<label><input type="checkbox" name="mb_mailling" value="Y" id="reg_mb_mailling"<?php echo ($w=='' || $member['mailser'] == 'Y')?' checked':''; ?>> 정보 메일을 받겠습니다.</label>
			</span>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_mb_email">담당매니져 코드</label></th>
		<td>
			<?php if ($mb_recommend=="") { ?>
					 <input type="text" name="mb_recommend" id="reg_mb_recommend" required value="<?php echo $mb_recommend ?>"   class="frm_input"  size="40" maxlength="100">
			<?php }else{?>

			<input type="hidden" name="mb_recommend" id="reg_mb_recommend required" value="<?php echo $mb_recommend ?>">

			<span class = "fc_999"> <?php echo $mb_recommend ?> </span>
		<?php } ?>
		</td>
	</tr>
	<!--tr>
		<th scope="row">지역 본부</th>
		<td colspan="3"><input type="text" name="rgn_headquarters" class="frm_input" size="30"  value="<?php echo get_text($member['rgn_headquarters']);?>"></td>
	</tr-->
	<!--tr>
		<th scope="row">지역 지사</th>
		<td colspan="3"><input type="text" name="rgn_branch" class="frm_input" size="30"  value="<?php echo get_text($member['rgn_branch']);?>"></td>
	</tr-->
	</tbody>
	</table>
</div>

<section id="anc_sitfrm_cate">
	<h3>지역 카테고리</h3>
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
							var tb_admin_url = "<?php echo TB_ADMIN_URL; ?>";
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
							if($ca_id1 = adm_category_navi($rent_row['ca_id']))
								echo '<option value="'.$rent_row['ca_id'].'">' .$ca_id1. '</option>'.PHP_EOL;
							if($ca_id2 = adm_category_navi($rent_row['ca_id2']))
								echo '<option value="'.$rent_row['ca_id2'].'">'.$ca_id2.'</option>'.PHP_EOL;
							if($ca_id3 = adm_category_navi($rent_row['ca_id3']))
								echo '<option value="'.$rent_row['ca_id3'].'">'.$ca_id3.'</option>'.PHP_EOL;
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

<h3>사업자 정보 입력</h3>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
  <tr>
		<th scope="row">랜트사명</th>
		<td><input type="text" name="company_name" required itemname="공급사명" class="required frm_input" size="30" value="<?php echo $rent_row['company_name'];?>"></td>
		<th scope="row">보유차량 대수</th>
		<td><input type="text" name="rent_car_amt" required itemname="보유차량 대수" class="required frm_input" size="30" value="<?php echo $rent_row['rent_car_amt'];?>"></td>
	</tr>
  <tr>
		<th scope="row">대표자명</th>
		<td><input type="text" name="company_owner" required itemname="대표자명" class="required frm_input" size="30" value="<?php echo $rent_row['company_owner'];?>"></td>
		<th scope="row">사업자등록번호</th>
		<td><input type="text" name="company_saupja_no" class="frm_input" size="30" placeholder="예) 000-00-00000" value="<?php echo $rent_row['company_saupja_no'];?>"></td>
	</tr>
	<tr>
		<th scope="row">업태</th>
		<td><input type="text" name="company_item" class="frm_input" size="30" placeholder="예) 렌트업 " value="<?php echo $rent_row['company_item'];?>"></td>
		<th scope="row">종목</th>
		<td><input type="text" name="company_service" class="frm_input" size="30" placeholder="예) 렌탈서비스" value="<?php echo $rent_row['company_service'];?>"></td>
	</tr>
	<tr>
		<th scope="row">전화번호</th>
		<td><input type="text" name="company_tel" required itemname="전화번호" class="required frm_input" size="30" placeholder="예) 02-1234-5678" value="<?php echo $rent_row['company_tel'];?>"></td>
		<th scope="row">팩스번호</th>
		<td><input type="text" name="company_fax" class="frm_input" size="30" placeholder="예) 02-1234-5678" value="<?php echo $rent_row['company_fax'];?>"></td>
	</tr>
	<?php if($config['register_use_addr']) { ?>
	<tr>
		<th scope="row">주소</th>
		<td>
			<label for="reg_mb_zip" class="sound_only">우편번호</label>
			<input type="text" name="mb_zip" value="<?php echo $member['zip']; ?>" id="reg_mb_zip"<?php echo $config['register_req_addr']?' required':''; ?> class="frm_input<?php echo $config['register_req_addr']?' required':''; ?>" size="8" maxlength="5">
			<button type="button" class="btn_small" onclick="win_zip('fregisterform', 'mb_zip', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');">주소검색</button><br>
			<input type="text" name="mb_addr1" value="<?php echo get_text($member['addr1']); ?>" id="reg_mb_addr1"<?php echo $config['register_req_addr']?' required':''; ?> class="frm_input<?php echo $config['register_req_addr']?' required':''; ?> frm_address" size="60">
			<label for="reg_mb_addr1">기본주소</label><br>
			<input type="text" name="mb_addr2" value="<?php echo get_text($member['addr2']); ?>" id="reg_mb_addr2" class="frm_input frm_address" size="60">
			<label for="reg_mb_addr2">상세주소</label><br>
			<input type="text" name="mb_addr3" value="<?php echo get_text($member['addr3']); ?>" id="reg_mb_addr3" class="frm_input frm_address" size="60" readonly="readonly">
			<label for="reg_mb_addr3">참고항목</label>
			<input type="hidden" name="mb_addr_jibeon" value="<?php echo get_text($member['addr_jibeon']); ?>">
		</td>
	</tr>
	<?php } ?>
	<tr>
		<th scope="row">홈페이지</th>
		<td colspan="3"><input type="text" name="company_hompage" class="frm_input" size="30" placeholder="http://" value="<?php echo $rent_row['company_hompage'];?>"></td>
	</tr>
	</tbody>
	</table>
</div>

<section id="anc_sitfrm_img">
<h3>대표 이미지</h3>
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
			<input type="radio" name="rep_img_type" id="simg_type_1" checked value="0"<?php echo get_checked('0', $rent_row['rep_img_type']); ?> onclick="chk_simg_type(0);">
			<label for="simg_type_1">직접 업로드</label>
			<input type="radio" name="rep_img_type" id="simg_type_2" value="1"<?php echo get_checked('1', $rent_row['rep_img_type']); ?> onclick="chk_simg_type(1);">
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
					if ($rent_row['rep_img_type'] == '0') {
						echo get_look_ahead_rep($rent_row['company_img'], "rent", "simg_del");
					}	?>
			</div>
			<div class="item_url_fld">
				<input type="text" name="simg" value="<?php echo $rent_row['company_img']; ?>" class="frm_input" size="80" placeholder="http://">
			</div>
		</td>
	</tr>
	</tbody>
	</table>
</div>
</section>

<h3>정산 계좌정보 입력</h3>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
		<tr>
			<th scope="row">은행명</th>
			<td><input type="text" name="bank_name" class="frm_input" size="30" value="<?php echo $rent_row['bank_name'];?>"></td>
		</tr>
		<tr>
			<th scope="row">계좌번호</th>
			<td><input type="text" name="bank_account" class="frm_input" size="30" value="<?php echo $rent_row['bank_account'];?>"></td>
		</tr>
		<tr>
			<th scope="row">예금주명</th>
			<td><input type="text" name="bank_holder" class="frm_input" size="30" value="<?php echo $rent_row['bank_holder'];?>"></td>
		</tr>
	</tbody>
	</table>
</div>

<h3>담당자 정보 입력</h3>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
    <tr>
			<tr>
				<th scope="row">담당자명</th>
				<td><input type="text" name="info_name" class="frm_input" size="30" value="<?php echo $rent_row['info_name'];?>"></td>
			</tr>
			<tr>
				<th scope="row">담당자 핸드폰</th>
				<td><input type="text" name="info_tel" class="frm_input" size="30" value="<?php echo $rent_row['info_tel'];?>"></td>
			</tr>
			<tr>
				<th scope="row">담당자 이메일</th>
				<td><input type="text" name="info_email" class="frm_input" size="30" value="<?php echo $rent_row['info_email'];?>"></td>
			</tr>
	</tbody>
	</table>
</div>

<h3>딜리버리 정보 입력</h3>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
    <tr>
			<th scope="row">시간정보</th>
			<td><input type="time" name="time_info1" class="frm_input" style="width:120px;" value="<?php echo $rent_row['time_info1'];?>"> ~ <input type="time" name="time_info2" class="frm_input" style="width:120px;" value="<?php echo $rent_row['time_info2'];?>"></td>
		</tr>
		<tr>
			<th scope="row">딜리버리 여부</th>
			<td>
				<input type="radio" name="delivery_status" class="frm_input" value="Y" <?php if($rent_row['delivery_status'] == 'Y') { echo checked; }?> id="ds1"><label for="ds1">YES</label>
				<input type="radio" name="delivery_status" class="frm_input" value="N"  <?php if($rent_row['delivery_status'] == 'N') { echo checked; }?> id="ds2"><label for="ds2">NO</label>
			</td>
		</tr>
		<tr>
			<th scope="row">딜리버리 가능지역</th>
			<td><textarea name="delivery_avlbArea"> <?php echo $rent_row['delivery_avlbArea'];?> </textarea></td>
		</tr>
		<tr>
			<th scope="row">대여 및 반납 안내</th>
			<td><textarea name="rental_rtn_info"> <?php echo $rent_row['rental_rtn_info'];?> </textarea></td>
		</tr>
		<tr>
			<th scope="row">딜리버리 체크사항</th>
			<td>
				<div>
					<input type="checkbox" name="delivery_chk1" value="Y" class="frm_input" id="dc_1" <?php echo $rent_row['delivery_chk1'] == 'Y' ? 'checked' : ''; ?>/> <label for="dc_1">영업시간 외 인수/반납 사전 문의(렌트카업체) 필수</label>
				</div>
				<div>
					<input type="checkbox" name="delivery_chk2" value="Y" class="frm_input" id="dc_2" <?php echo $rent_row['delivery_chk2'] == 'Y' ? 'checked' : ''; ?>/> <label for="dc_2">애완동물 동승 가능 여부 사전 문의 필수</label>
				</div>
				<div>
					<input type="checkbox" name="delivery_chk3" value="Y" class="frm_input" id="dc_3" <?php echo $rent_row['delivery_chk3'] == 'Y' ? 'checked' : ''; ?>/> <label for="dc_3">영업시간 카시트 대여 가능 사전 문의 필수</label>
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row">영업시간</th>
			<td><input type="time" name="businessHours1" class="frm_input" style="width:120px;" value="<?php echo $rent_row['businessHours1'];?>"> ~ <input type="time" name="businessHours2" class="frm_input" style="width:120px;" value="<?php echo $rent_row['businessHours2'];?>"></td>
		</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="<?php echo $w==''?'회원가입':'정보수정'; ?>" id="btn_submit" class="btn_large wset" accesskey="s">
	<a href="<?php echo TB_URL; ?>" class="btn_large bx-white">취소</a>
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

function fregisterform_submit(f)
{

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

	<?php if($w == '' && $config['cf_cert_use'] && $config['cf_cert_req']) { ?>
	// 본인인증 체크
	if(f.cert_no.value=="") {
		alert("회원가입을 위해서는 본인인증을 해주셔야 합니다.");
		return false;
	}
	<?php } ?>

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


	document.getElementById("btn_submit").disabled = "disabled";

    return true;
}


</script>
<!-- } 회원정보 입력/수정 끝 -->

 <!-- 사업자 정보 입력 -->
<script>
$("input:text[numberOnly]").on("keyup", function() {
  $(this).val($(this).val().replace(/[^0-9]/g,""));
});

$('#dc_1').click(function(){
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

$('#dc_2').click(function(){
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

$('#dc_3').click(function(){
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


$(function() {
	var seller_item = $("#seller_item_temp").val();
	console.log(seller_item);
    console.log( "ready!" );
		$("#seller_item").val(seller_item).prop("selected", true); //값이 1인 option 선택


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

chk_simg_type("<?php echo $rent_row['rep_img_type']; ?>");
</script>
