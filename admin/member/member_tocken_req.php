<?php
include_once("./_common.php");

$tb['title'] = '토큰적립, 차감';
include_once(TB_ADMIN_PATH."/admin_head.php");

$mb	= get_member($mb_id);

$to_expire_term = '';
if($config['cf_tocken_term'] > 0) {
    $to_expire_term = $config['cf_tocken_term'];
}
?>

<form name="ftockenform" method="post" action="./member_tocken_req_update.php">
<input type="hidden" name="mb_id" value="<?php echo $mb_id; ?>">
<input type="hidden" name="token" value="">

<h2 class="newp_tit"><?php echo $tb['title']; ?></h2>
<div class="newp_wrap">
	<div class="tbl_frm01">
		<table>
		<colgroup>
			<col class="w100">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">아이디</th>
			<td><?php echo $mb['id']; ?></td>
		</tr>
		<tr>
			<th scope="row">회원명</th>
			<td><?php echo $mb['name']; ?></td>
		</tr>
		<tr>
			<th scope="row">토큰잔액</th>
			<td><?php echo number_format($mb['tocken']); ?> P</td>
		</tr>
		<tr>
			<th scope="row"><label for="to_content">토큰내용</label></th>
			<td><input type="text" name="to_content" id="to_content" required class="required frm_input wfull"></td>
		</tr>
		<tr>
			<th scope="row"><label for="to_tocken">토큰</label></th>
			<td><input type="text" name="to_tocken" id="to_tocken" required class="required frm_input" size="10"> YOLOC (음수 입력시 토큰차감)</td>
		</tr>
		<?php if($config['cf_tocken_term'] > 0) { ?>
		<tr>
			<th scope="row"><label for="to_expire_term">토큰 유효기간</label></th>
			<td><input type="text" name="to_expire_term" value="<?php echo $to_expire_term; ?>" id="to_expire_term" class="frm_input" size="10"> 일</td>
		</tr>
		<?php } ?>
		</tbody>
		</table>
	</div>
	<div class="btn_confirm">
		<input type="submit" value="토큰적용" class="btn_medium" accesskey="s">
		<button type="button" onclick="self.close();" class="btn_medium bx-white">닫기</button>
	</div>
</div>
</form>

<?php
include_once(TB_ADMIN_PATH.'/admin_tail.sub.php');
?>
