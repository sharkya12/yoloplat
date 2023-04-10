<?php
include_once("./_common.php");

$mb_hp = trim($_POST['mb_hp']);
$mb_password = trim($_POST['mb_password']);
$referer_url = $_GET['referer_url'];
$gs_id = $_GET['gs_id'];
$room_id = $_GET['room_id'];
$referer = $referer_url."&gs_id=".$gs_id."&room_id=".$room_id;


if(!$mb_hp || !$mb_password)
    alert('회원아이디나 비밀번호가 공백이면 안됩니다.');


//$mb = get_member($mb_id);
$mb = get_member_hp($mb_hp);


// 가입된 회원이 아니다. 패스워드가 틀리다. 라는 메세지를 따로 보여주지 않는 이유는
// 회원아이디를 입력해 보고 맞으면 또 패스워드를 입력해보는 경우를 방지하기 위해서입니다.
// 불법사용자의 경우 회원아이디가 틀린지, 패스워드가 틀린지를 알기까지는 많은 시간이 소요되기 때문입니다.
if(!$mb['id'] || !check_password($mb_password, $mb['passwd'])) {
    alert('가입된 전화번호가 아니거나 비밀번호가 틀립니다.\\n비밀번호는 대소문자를 구분합니다.');
}

// 차단된 아이디인가?
if($mb['intercept_date'] && $mb['intercept_date'] <= date("Ymd", TB_SERVER_TIME)) {
    $date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1년 \\2월 \\3일", $mb['intercept_date']);
    alert('회원님의 아이디는 접근이 금지되어 있습니다.\\n처리일 : '.$date);
}
/*
// 인트로 사용시 승인된 회원인지 체크
if(!is_admin($mb['grade']) && !$mb['use_app'] && $config['cert_admin_yes']) {
	alert("승인 된 회원만 로그인 가능합니다.");
}

// 관리비를 사용중일때 기간이 만료되었다면 로그인 차단
if($config['pf_expire_use'] && $config['pf_login_no']) {
	if(is_partner($mb['id']) && !is_null_time($mb['term_date'])) {
		if($mb['term_date'] < TB_TIME_YMD) {
			alert("회원님의 아이디는 관리비 미납으로 접근이 금지되어 있습니다.");
		}
	}
}
*/
// 회원아이디 세션 생성
set_session('ss_mb_id', $mb['id']);

// FLASH XSS 공격에 대응하기 위하여 회원의 고유키를 생성해 놓는다. 관리자에서 검사함 - 110106
set_session('ss_mb_key', md5($mb['reg_time'] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']));

// 포인트 체크
$sum_point = get_point_sum($mb['id']);

$sql= " update hi_member set point = '$sum_point' where id = '{$mb['id']}' ";
sql_query($sql);

// 자동로그인 : 아이디 쿠키에 한달간 저장
if($auto_login) {
    // 쿠키 한달간 저장
    $key = md5($_SERVER['SERVER_ADDR'] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . $mb['passwd']);
    set_cookie('ck_mb_id', $mb['id'], 86400 * 31);
    set_cookie('ck_auto', $key, 86400 * 31);
} else {
    set_cookie('ck_mb_id', '', 0);
    set_cookie('ck_auto', '', 0);
}

 //echo($url."&in_date=".$in_date."&out_date=".$out_date."&gcode=".$gcode);
 $in_date = $_POST['in_date'];
 $out_date = $_POST['out_date'];
 $gcode = $_POST['gcode'];



if($url) {
   $url = $url."&in_date=".$in_date."&out_date=".$out_date."&gcode=".$gcode;
    // url 체크
    check_url_host($url);

    $link = urldecode($url);
    // 2003-06-14 추가 (다른 변수들을 넘겨주기 위함)
    if (preg_match("/\?/", $link))
        $split= "&amp;";
    else
        $split= "?";

    // $_POST 배열변수에서 아래의 이름을 가지지 않은 것만 넘김

    $post_check_keys = array('mb_id', 'mb_password', 'x', 'y', 'url', 'slr_url');

    foreach($_POST as $key=>$value) {
        if ($key && !in_array($key, $post_check_keys)) {
            $link .= "$split$key=$value";
            $split = "&amp;";
        }
    }
} else  {
  if(strpos($referer_url, 'reservation.php') !== false) {
    $link = $referer;
  } else if (strpos($referer_url, 'pw_find_update.php') !== false) {
    $link = TB_MROOM_URL.'/main.php';
  } else {
    $link = $referer_url;
    // $link = TB_MURL."/app/main.php";
  }
}

goto_url($link);
?>
