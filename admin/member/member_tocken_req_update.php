<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$_POST = array_map('trim', $_POST);

$mb_id = $_POST['mb_id'];
$to_tocken = $_POST['to_tocken'];
$to_content = $_POST['to_content'];
$expire = preg_replace('/[^0-9]/', '', $_POST['to_expire_term']);

$mb = get_member($mb_id, "id, tocken");

if(!$mb['id'])
    alert("존재하는 회원아이디가 아닙니다.");

if(($to_point < 0) && ($to_tocken * (-1) > $mb['tocken']))
    alert("토큰을 차감하는 경우 현재 토큰보다 작으면 안됩니다.");

insert_tocken($mb_id, $to_tocken, $to_content, '@passive', $mb_id, $member['id'].'-'.uniqid(''), $expire);

alert('정상적으로 처리 되었습니다.','replace');
?>
