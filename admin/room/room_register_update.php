<?php
include_once("./_common.php");
include_once(TB_LIB_PATH.'/register.lib.php');
check_demo();

check_admin_token();

//위도 , 경도 샘플 가이드 라인
$path = '/v2/local/search/address';
$content_type = 'JSON'; // json or xml
$params = http_build_query(array(
  'page' => 1,
  'size' => 10,
  'query' => $_POST['company_addr1']
));

$res = addressTolat($path, $params, $content_type);

//header('Content-Type: application/' . $content_type . '; charset=UTF-8');
//echo $res;
//echo "<br />";
$arr = json_decode($res,TRUE);
$latitude = $arr['documents'][0]['address']['x'];
$longitude = $arr['documents'][0]['address']['y'];

// echo($latitude)."</br>"; // x
// echo($longitude)."</br>"; //y


$upl_dir = TB_DATA_PATH."/room";
$upl = new upload_files($upl_dir);

$mb_id = $_POST['mb_id'];
$w = $_POST['w'];


$mb_password    = trim($_POST['mb_password']);
$mb_password_re = trim($_POST['mb_password_re']);
$mb_name        = trim($_POST['mb_name']);
$mb_email       = trim($_POST['mb_email']);
$mb_tel         = isset($_POST['mb_tel'])           ? trim($_POST['mb_tel'])         : "";
$mb_hp          = isset($_POST['mb_hp'])            ? trim($_POST['mb_hp'])          : "";
$mb_zip			= isset($_POST['company_zip'])           ? trim($_POST['company_zip'])		 : "";
$mb_addr1       = isset($_POST['company_addr1'])         ? trim($_POST['company_addr1'])       : "";
$mb_addr2       = isset($_POST['company_addr2'])         ? trim($_POST['company_addr2'])       : "";
$mb_addr3       = isset($_POST['company_addr3'])         ? trim($_POST['company_addr3'])       : "";
$mb_addr_jibeon = isset($_POST['company_addr_jibeon'])   ? trim($_POST['company_addr_jibeon']) : "";
$mb_recommend   = isset($_POST['mb_recommend'])     ? trim($_POST['mb_recommend'])   : "";
$mb_mailling    = isset($_POST['mb_mailling'])      ? trim($_POST['mb_mailling'])    : "";
$mb_sms         = isset($_POST['mb_sms'])           ? trim($_POST['mb_sms'])         : "";

$mb_name        = clean_xss_tags($mb_name);
$mb_email       = get_email_address($mb_email);
$mb_tel         = clean_xss_tags($mb_tel);
$mb_zip			= preg_replace('/[^0-9]/', '', $mb_zip);
$mb_addr1       = clean_xss_tags($mb_addr1);
$mb_addr2       = clean_xss_tags($mb_addr2);
$mb_addr3       = clean_xss_tags($mb_addr3);
$mb_addr_jibeon = preg_match("/^(N|R)$/", $mb_addr_jibeon) ? $mb_addr_jibeon : '';

if($w == '' || $w == 'u') {

    if($msg = empty_mb_id($mb_id))	alert($msg);
    if($msg = valid_mb_id($mb_id))	alert($msg);
    if($msg = count_mb_id($mb_id))	alert($msg);

    // 이름에 utf-8 이외의 문자가 포함됐다면 오류
    // 서버환경에 따라 정상적으로 체크되지 않을 수 있음.
    $tmp_mb_name = iconv('UTF-8', 'UTF-8//IGNORE', $mb_name);
    if($tmp_mb_name != $mb_name) {
        alert('이름을 올바르게 입력해 주십시오.');
    }

    if($w == '' && !$mb_password)
        alert('비밀번호가 넘어오지 않았습니다.');
    if($w == '' && $mb_password != $mb_password_re)
        alert('비밀번호가 일치하지 않습니다.');

    if($msg = empty_mb_name($mb_name))		alert($msg);
    if($msg = empty_mb_email($mb_email))	alert($msg);
    if($msg = reserve_mb_id($mb_id))		alert($msg);
    // 이름에 한글명 체크를 하지 않는다.
    //if($msg = valid_mb_name($mb_name))	alert($msg);
    if($msg = valid_mb_email($mb_email))	alert($msg);
    if($msg = prohibit_mb_email($mb_email))	alert($msg);

    // 휴대폰 필수입력일 경우 휴대폰번호 유효성 체크
    if(($config['register_use_hp'] || $config['cf_cert_hp']) && $config['register_req_hp']) {
        if($msg = valid_mb_hp($mb_hp))		alert($msg);
    }

    if($w == '') {
        // if($msg = exist_mb_id($mb_id))		alert($msg);
        //
        // if(get_session('ss_check_mb_id') != $mb_id || get_session('ss_check_mb_email') != $mb_email) {
        //     set_session('ss_check_mb_id', '');
        //     set_session('ss_check_mb_email', '');
        //
        //     alert('올바른 방법으로 이용해 주십시오.');
        // }

        // 본인확인 체크
        if($config['cf_cert_use'] && $config['cf_cert_req']) {
            if(trim($_POST['cert_no']) != $_SESSION['ss_cert_no'] || !$_SESSION['ss_cert_no'])
                alert("회원가입을 위해서는 본인확인을 해주셔야 합니다.");
        }

        // if($mb_recommend) {
        //     if(!exist_mb_id($mb_recommend))
        //         alert("추천인이 존재하지 않습니다.");
        // }
        //
        // if(strtolower($mb_id) == strtolower($mb_recommend)) {
        //     alert('본인을 추천할 수 없습니다.');
        // }
    } else {
		// 자바스크립트로 정보변경이 가능한 버그 수정
		// 회원정보의 메일을 이전 메일로 옮기고 아래에서 비교함
		$old_email = $hi_member_row['email'];
	}
  if($w == '') {
    if($msg = exist_mb_email($mb_email, $mb_id))   alert($msg);
  }
}

//===============================================================
//  본인확인
//---------------------------------------------------------------

// 휴대폰 하이픈 달아주기
$mb_hp = hyphen_hp_number($mb_hp);

if($config['cf_cert_use'] && $_SESSION['ss_cert_type'] && $_SESSION['ss_cert_dupinfo']) {
    // 중복체크
    $sql = " select id from hi_member where id <> '{$hi_member_row['id']}' and mb_dupinfo = '{$_SESSION['ss_cert_dupinfo']}' ";
    $row = sql_fetch($sql);
    if($row['id']) {
        alert("입력하신 본인확인 정보로 가입된 내역이 존재합니다.\\n회원아이디 : ".$row['id']);
    }
}

unset($value);
$md5_cert_no = $_SESSION['ss_cert_no'];
$cert_type = $_SESSION['ss_cert_type'];
if($config['cf_cert_use'] && $cert_type && $md5_cert_no) {
    // 해시값이 같은 경우에만 본인확인 값을 저장한다.
    if($_SESSION['ss_cert_hash'] == md5($mb_name.$cert_type.$_SESSION['ss_cert_birth'].$md5_cert_no)) {
        $value['cellphone']		= $mb_hp;
        $value['mb_certify']	= $cert_type;
        $value['mb_adult']		= $_SESSION['ss_cert_adult'];
        $value['mb_birth']		= $_SESSION['ss_cert_birth'];
        $value['gender']		= $_SESSION['ss_cert_sex'];
        $value['mb_dupinfo']	= $_SESSION['ss_cert_dupinfo'];
		$value['age']			= get_birth_age($_SESSION['ss_cert_birth']);
        if($w == 'u')
			$value['name'] = $mb_name;
    } else {
        $value['cellphone']		= $mb_hp;
        $value['mb_certify']	= '';
        $value['mb_adult']		= '0';
        $value['mb_birth']		= '';
        $value['gender']		= '';
		    $value['age']			= '';
    }
} else {
    if(get_session("ss_reg_mb_name") != $mb_name || get_session("ss_reg_mb_hp") != $mb_hp) {
        $value['cellphone']		= $mb_hp;
        $value['mb_certify']	= '';
        $value['mb_adult']		= '0';
        $value['mb_birth']		= '';
        $value['gender']		= '';
		    $value['age']			= '';
    }
}
//===============================================================

$msg = "";

//기존의 체크된 부대시설옵션 테이블에서 해당 업체의 부대시설옵션 삭제
$mb_id = $_POST['mb_id'];
sql_query(" delete from hi_room_facilities where mb_id = '$mb_id'");


//새로운 숙박업소 등록시
if($w == '') {
	$value['id']			= $mb_id; //회원아이디
	$value['passwd']		= $mb_password; //비밀번호
	$value['name']			= $mb_name; //이름
	$value['email']			= $mb_email; //이메일
	$value['telephone']		= $mb_tel;	 //전화번호
	$value['zip']			= $mb_zip; //우편번호
	$value['addr1']			= $mb_addr1; //주소
	$value['addr2']			= $mb_addr2; //상세주소
	$value['addr3']			= $mb_addr3; //참고항목
	$value['addr_jibeon']	= $mb_addr_jibeon; //지번주소
	$value['today_login']	= TB_TIME_YMDHIS; //최근 로그인일시
	$value['reg_time']		= TB_TIME_YMDHIS; //가입일시
	$value['mb_ip']			= $_SERVER['REMOTE_ADDR']; //IP
	$value['grade']			= '9'; //레벨
	$value['pt_id']			= $mb_recommend; //추천인아이디
  $value['rgn_headquarters']		  = $_POST['rgn_headquarters'];
  $value['rgn_branch']		  = $_POST['rgn_branch'];
	$value['login_ip']		= $_SERVER['REMOTE_ADDR']; //최근 로그인IP
	$value['mailser']		= $mb_mailling ? $mb_mailling : 'N'; //E-Mail을 수신
	$value['smsser']		= $mb_sms ? $mb_sms : 'N'; //SMS를 수신


    // 관리자인증을 사용하지 않는다면 인증으로 간주함.
    if(!$config['cert_admin_yes'])
        $value['use_app']	= '1';

	insert("hi_member", $value);
	$mb_no = sql_insert_id();

  // // 회원가입 포인트 부여
  // insert_point($mb_id, $config['register_point'], '회원가입 축하', '@member', $mb_id, '회원가입');
  //
  // // 추천인에게 포인트 부여
	// insert_point($mb_recommend, $config['partner_point'], $mb_id.'의 추천인', '@member', $mb_recommend, $mb_id.' 추천');


//////hi_room 사업자 정보 등록
if($_POST['ca_id'] == "") {
	alert("카테고리를 하나이상 선택하세요.");
}

unset($value);
if($img = $_FILES['simg']['name']) {
  if(!preg_match("/\.(gif|jpg|png)$/", $img)) {
    alert("이미지가 gif, jpg, png 파일이 아닙니다.");
  }
}
if($_POST['simg_del']) {
  $upl->del($_POST['simg_del']);
  $value['simg'] = '';
}
if($_FILES['simg']['name']) {
  $value['company_img'] = $upl->upload($_FILES['simg']);
}
if ($_POST['rep_img_type'] == '1') {
  $value['company_img'] = $_POST['simg'];
  $value['rep_img_type'] = '1';
}

$value['seller_code']		  = code_uniqid('hi_room');
$value['mb_id']				  = $_POST['mb_id'];
$value['ca_id']				  = $_POST['ca_id'];
$value['ca_id2']				  = $_POST['ca_id2'];
$value['ca_id3']				  = $_POST['ca_id3'];
$value['seller_item']		  = $_POST['seller_item'];
$value['company_name']		  = $_POST['company_name'];
$value['company_saupja_no']	  = $_POST['company_saupja_no'];
$value['company_item']		  = $_POST['company_item'];
$value['room_grade']		  = $_POST['room_grade'];
$value['company_service']	  = $_POST['company_service'];
$value['company_owner']		  = $_POST['company_owner'];
$value['company_tel']		  = $_POST['company_tel'];
$value['company_fax']		  = $_POST['company_fax'];
$value['company_zip']		  = $mb_zip;
$value['company_addr1']		  = $mb_addr1;
$value['company_addr2']		  = $mb_addr2;
$value['company_addr3']		  = $mb_addr3;
$value['company_addr_jibeon'] = $mb_addr_jibeon;
$value['latitude'] = $latitude;
$value['longitude'] = $longitude;
$value['company_hompage']	  = $_POST['company_hompage'];
$value['info_name']			  = $_POST['info_name'];
$value['info_tel']			  = $_POST['info_tel'];
$value['info_email']		  = $_POST['info_email'];
$value['bank_name']			  = $_POST['bank_name'];
$value['bank_account']		  = $_POST['bank_account'];
$value['bank_holder']		  = $_POST['bank_holder'];
$value['fee']		  = $_POST['fee'];
$value['rep_img_type']		  = $_POST['rep_img_type'];
$value['ageLimit_val']      = $_POST['ageLimit_val'];
$value['basic_info']				  = $_POST['basic_info'];
$value['add_ppl_info']				  = $_POST['add_ppl_info'];
$value['room_info']				  = $_POST['room_info'];
$value['use_mon']		  = '{ "data"'. ':[{ '.'"in_time":"'.$_POST['mon_chk_in_time'].'", '.'"out_time":"'.$_POST['mon_chk_out_time'].'"}]}';
$value['use_tue']		  = '{ "data"'. ':[{ '.'"in_time":"'.$_POST['tue_chk_in_time'].'", '.'"out_time":"'.$_POST['tue_chk_out_time'].'"}]}';
$value['use_wed']		  = '{ "data"'. ':[{ '.'"in_time":"'.$_POST['wed_chk_in_time'].'", '.'"out_time":"'.$_POST['wed_chk_out_time'].'"}]}';
$value['use_thu']		  = '{ "data"'. ':[{ '.'"in_time":"'.$_POST['thu_chk_in_time'].'", '.'"out_time":"'.$_POST['thu_chk_out_time'].'"}]}';
$value['use_fri']		  = '{ "data"'. ':[{ '.'"in_time":"'.$_POST['fri_chk_in_time'].'", '.'"out_time":"'.$_POST['fri_chk_out_time'].'"}]}';
$value['use_sat']		  = '{ "data"'. ':[{ '.'"in_time":"'.$_POST['sat_chk_in_time'].'", '.'"out_time":"'.$_POST['sat_chk_out_time'].'"}]}';
$value['use_sun']		  = '{ "data"'. ':[{ '.'"in_time":"'.$_POST['sun_chk_in_time'].'", '.'"out_time":"'.$_POST['sun_chk_out_time'].'"}]}';
$value['use_hol']		  = '{ "data"'. ':[{ '.'"in_time":"'.$_POST['hol_chk_in_time'].'", '.'"out_time":"'.$_POST['hol_chk_out_time'].'"}]}';
// $value['use_hol']		  = $_POST['use_hol'] == null ? 'N' :'Y'.",".$_POST['hol_chk_in_time'].",".$_POST['hol_chk_out_time'];
// $value['chk_in_time']		  = $_POST['chk_in_time'];
// $value['chk_out_time']		  = $_POST['chk_out_time'];
$value['res_month']		  = $_POST['res_month'];
$value['res_day']		  = $_POST['res_day'];
$value['short_addr']  = $_POST['short_addr'];
$value['location_desc']		  = $_POST['location_desc'];
$value['parking_info']		  = $_POST['parking_info'];
$value['b_ck_day1']		  = $_POST['b_ck_day1'];
$value['b_ck_day2']		  = $_POST['b_ck_day2'];
$value['b_ck_day3']		  = $_POST['b_ck_day3'];
$value['b_ck_hour1']		  = $_POST['b_ck_hour1'];
$value['b_ck_hour2']		  = $_POST['b_ck_hour2'];
$value['b_ck_hour3']		  = $_POST['b_ck_hour3'];
$value['b_ck_rfd1']		  = $_POST['b_ck_rfd1'];
$value['b_ck_rfd2']		  = $_POST['b_ck_rfd2'];
$value['b_ck_rfd3']		  = $_POST['b_ck_rfd3'];
$value['s_ck_day1']		  = $_POST['s_ck_day1'];
$value['s_ck_day2']		  = $_POST['s_ck_day2'];
$value['s_ck_day3']		  = $_POST['s_ck_day3'];
$value['s_ck_hour1']		  = $_POST['s_ck_hour1'];
$value['s_ck_hour2']		  = $_POST['s_ck_hour2'];
$value['s_ck_hour3']		  = $_POST['s_ck_hour3'];
$value['s_ck_rfd1']		  = $_POST['s_ck_rfd1'];
$value['s_ck_rfd2']		  = $_POST['s_ck_rfd2'];
$value['s_ck_rfd3']		  = $_POST['s_ck_rfd3'];
$value['cnc_rfnd_policy']				  = $_POST['cnc_rfnd_policy'];
$value['fac_remark']	= $_POST['fac_remark']; //객실시설비고
$value['food']				  = $_POST['food'];
$value['food_remarks']				  = $_POST['food_remarks'];
$value['confirmations_other']				  = $_POST['confirmations_other'];
$value['memo']				  = $_POST['memo'];
$value['reg_time']			  = TB_TIME_YMDHIS;
$value['update_time']		  = TB_TIME_YMDHIS;
insert("hi_room", $value);

//hi_room_facilities (부대시설옵션) 테이블에 추가
$fac_ma_idx = $_POST['fac_idx']; //master index
$fac_gr_cd = $_POST['fac_gr_cd']; //시설그룹코드
$fac_ma_seq = $_POST['fac_ma_seq']; //master 시퀀스
$fac_name =  $_POST['fac_name'];

for($j=0; $j<count($_POST['room_facility']); $j++){

	$k = $room_facility[$j];

	$value_fac['mb_id'] = $_POST['mb_id']; //업체코드

	$value_fac['fac_idx'] = $fac_ma_idx[$k];
	$value_fac['fac_gr_cd'] = $fac_gr_cd[$k];
	$value_fac['fac_ma_seq'] = $fac_ma_seq[$k];
	$value_fac['fac_name'] = $fac_name[$k];

	$value_fac['use_yn'] = 'Y';

	insert("hi_room_facilities", $value_fac);
}


}
//기존의 숙박업소 정보를 수정할시
else{
  ///////// hi_member 개인정보 등록
  unset($value);
  if($mb_password)
    $value['passwd']	= $mb_password; //비밀번호
    $value['email']			= $mb_email; //이메일
    $value['telephone']		= $mb_tel;	 //전화번호
    $value['cellphone']		= $mb_hp;	 //휴대폰번호
    $value['zip']			= $mb_zip; //우편번호
    $value['addr1']			= $mb_addr1; //주소
    $value['addr2']			= $mb_addr2; //상세주소
    $value['addr3']			= $mb_addr3; //참고항목
    $value['addr_jibeon']	= $mb_addr_jibeon; //지번주소
    $value['mailser']		= $mb_mailling ? $mb_mailling : 'N'; //E-Mail을 수신
    $value['smsser']		= $mb_sms ? $mb_sms : 'N'; //SMS를 수신
    $value['pt_id']			= $mb_recommend; //추천인아이디
    $value['rgn_headquarters']		  = $_POST['rgn_headquarters'];
    $value['rgn_branch']		  = $_POST['rgn_branch'];
    update("hi_member", $value, " where id = '$mb_id' ");


  unset($value);
  if($img = $_FILES['simg']['name']) {
    if(!preg_match("/\.(gif|jpg|png)$/", $img)) {
      alert("이미지가 gif, jpg, png 파일이 아닙니다.");
    }
  }
  if($_POST['simg_del']) {
    $upl->del($_POST['simg_del']);
    $value['company_img'] = '';
		$value['rep_img_type'] = '0';
  }
  if($_FILES['simg']['name']) {
    $value['company_img'] = $upl->upload($_FILES['simg']);
		$value['rep_img_type'] = '0';
  }

	if ($_POST['rep_img_type'] == '1') {
		$value['company_img'] = $_POST['simg'];
		$value['rep_img_type']		  = '1';
	}

  $value['ca_id']				  = $_POST['ca_id'];
	$value['ca_id2']				  = $_POST['ca_id2'];
	$value['ca_id3']				  = $_POST['ca_id3'];
  $value['seller_item']		  = $_POST['seller_item'];
  $value['company_name']		  = $_POST['company_name'];
  $value['company_saupja_no']	  = $_POST['company_saupja_no'];
  $value['company_item']		  = $_POST['company_item'];
  $value['room_grade']		  = $_POST['room_grade'];
  $value['company_service']	  = $_POST['company_service'];
  $value['company_owner']		  = $_POST['company_owner'];
  $value['company_tel']		  = $_POST['company_tel'];
  $value['company_fax']		  = $_POST['company_fax'];
  $value['company_zip']		  = $mb_zip;
  $value['company_addr1']		  = $mb_addr1;
  $value['company_addr2']		  = $mb_addr2;
  $value['company_addr3']		  = $mb_addr3;
  $value['company_addr_jibeon'] = $mb_addr_jibeon;
	$value['latitude'] = $latitude;
	$value['longitude'] = $longitude;
  $value['company_hompage']	  = $_POST['company_hompage'];
  $value['info_name']			  = $_POST['info_name'];
  $value['info_tel']			  = $_POST['info_tel'];
  $value['info_email']		  = $_POST['info_email'];
  $value['bank_name']			  = $_POST['bank_name'];
  $value['bank_account']		  = $_POST['bank_account'];
  $value['bank_holder']		  = $_POST['bank_holder'];
  $value['fee']		  = $_POST['fee'];
  $value['ageLimit_val']				  = $_POST['ageLimit_val'];
  $value['basic_info']				  = $_POST['basic_info'];
  $value['add_ppl_info']				  = $_POST['add_ppl_info'];
  $value['room_info']				  = $_POST['room_info'];
  $value['use_mon']		  = '{ "data"'. ':[{ '.'"in_time":"'.$_POST['mon_chk_in_time'].'", '.'"out_time":"'.$_POST['mon_chk_out_time'].'"}]}';
  $value['use_tue']		  = '{ "data"'. ':[{ '.'"in_time":"'.$_POST['tue_chk_in_time'].'", '.'"out_time":"'.$_POST['tue_chk_out_time'].'"}]}';
  $value['use_wed']		  = '{ "data"'. ':[{ '.'"in_time":"'.$_POST['wed_chk_in_time'].'", '.'"out_time":"'.$_POST['wed_chk_out_time'].'"}]}';
  $value['use_thu']		  = '{ "data"'. ':[{ '.'"in_time":"'.$_POST['thu_chk_in_time'].'", '.'"out_time":"'.$_POST['thu_chk_out_time'].'"}]}';
  $value['use_fri']		  = '{ "data"'. ':[{ '.'"in_time":"'.$_POST['fri_chk_in_time'].'", '.'"out_time":"'.$_POST['fri_chk_out_time'].'"}]}';
  $value['use_sat']		  = '{ "data"'. ':[{ '.'"in_time":"'.$_POST['sat_chk_in_time'].'", '.'"out_time":"'.$_POST['sat_chk_out_time'].'"}]}';
  $value['use_sun']		  = '{ "data"'. ':[{ '.'"in_time":"'.$_POST['sun_chk_in_time'].'", '.'"out_time":"'.$_POST['sun_chk_out_time'].'"}]}';
  $value['use_hol']		  = '{ "data"'. ':[{ '.'"in_time":"'.$_POST['hol_chk_in_time'].'", '.'"out_time":"'.$_POST['hol_chk_out_time'].'"}]}';

  //{ "data": [{ "in_time": "12:00", "out_time":"15:00" }] }
	// $value['chk_in_time']		  = $_POST['chk_in_time'];
	// $value['chk_out_time']		  = $_POST['chk_out_time'];
	$value['res_month']		  = $_POST['res_month'];
	$value['res_day']		  = $_POST['res_day'];
  $value['short_addr']  = $_POST['short_addr'];
	$value['location_desc']		  = $_POST['location_desc'];
  $value['parking_info']		  = $_POST['parking_info'];
	$value['b_ck_day1']		  = $_POST['b_ck_day1'];
	$value['b_ck_day2']		  = $_POST['b_ck_day2'];
	$value['b_ck_day3']		  = $_POST['b_ck_day3'];
	$value['b_ck_hour1']		  = $_POST['b_ck_hour1'];
	$value['b_ck_hour2']		  = $_POST['b_ck_hour2'];
	$value['b_ck_hour3']		  = $_POST['b_ck_hour3'];
	$value['b_ck_rfd1']		  = $_POST['b_ck_rfd1'];
	$value['b_ck_rfd2']		  = $_POST['b_ck_rfd2'];
	$value['b_ck_rfd3']		  = $_POST['b_ck_rfd3'];
	$value['s_ck_day1']		  = $_POST['s_ck_day1'];
	$value['s_ck_day2']		  = $_POST['s_ck_day2'];
	$value['s_ck_day3']		  = $_POST['s_ck_day3'];
	$value['s_ck_hour1']		  = $_POST['s_ck_hour1'];
	$value['s_ck_hour2']		  = $_POST['s_ck_hour2'];
	$value['s_ck_hour3']		  = $_POST['s_ck_hour3'];
	$value['s_ck_rfd1']		  = $_POST['s_ck_rfd1'];
	$value['s_ck_rfd2']		  = $_POST['s_ck_rfd2'];
	$value['s_ck_rfd3']		  = $_POST['s_ck_rfd3'];
  $value['cnc_rfnd_policy']				  = $_POST['cnc_rfnd_policy'];
  $value['fac_remark']	= $_POST['fac_remark']; //객실시설비고
  $value['food']				  = $_POST['food'];
  $value['food_remarks']				  = $_POST['food_remarks'];
  $value['confirmations_other']				  = $_POST['confirmations_other'];
  $value['memo']				  = $_POST['memo'];
  $value['reg_time']			  = TB_TIME_YMDHIS;
  $value['update_time']		  = TB_TIME_YMDHIS;


  update("hi_room", $value," where mb_id='{$mb_id}'");

  //hi_room_facilities (부대시설옵션) 테이블에 추가
  $fac_ma_idx = $_POST['fac_idx']; //master index
  $fac_gr_cd = $_POST['fac_gr_cd']; //시설그룹코드
  $fac_ma_seq = $_POST['fac_ma_seq']; //master 시퀀스
  $fac_name =  $_POST['fac_name'];

  for($j=0; $j<count($_POST['room_facility']); $j++){

  	$k = $room_facility[$j];

  	$value_fac['mb_id'] = $_POST['mb_id']; //업체코드

  	$value_fac['fac_idx'] = $fac_ma_idx[$k];
  	$value_fac['fac_gr_cd'] = $fac_gr_cd[$k];
  	$value_fac['fac_ma_seq'] = $fac_ma_seq[$k];
  	$value_fac['fac_name'] = $fac_name[$k];

  	$value_fac['use_yn'] = 'Y';

  	insert("hi_room_facilities", $value_fac);
  }

  //식사관리 - 조식가격 수정
  // $value_food['food_amnt'] = $_POST['food_adults'];
  // update("hi_room_food_full", , "")


}


//성인
if ($_POST['food_adults'] != "") {
	$food_cnt = get_food_full_cnt($mb_id, '1');
	if ($food_cnt > 0) {
		unset($value);
		$value['full_div']		  = '1';
		$value['food_amnt']		  = $_POST['food_adults'];
		update("hi_room_food_full", $value, "where mb_id='{$mb_id}' and full_div = '1' and pers_div = '1'");
		}else{
			unset($value);
			$value['mb_id'] = $_POST['mb_id'];
			$value['full_div']		  = '1';
			$value['pers_div']		  = '1';
			$value['food_amnt']		  = $_POST['food_adults'];
			$value['food_desc'] = "조식 성인";
			insert("hi_room_food_full", $value);
		}
	}

//소인
if ($_POST['food_kids'] != "") {
		$food_cnt = get_food_full_cnt($mb_id, '2');
		if ($food_cnt > 0) {
			unset($value);
			$value['full_div']		  = '1';
			$value['food_amnt']		  = $_POST['food_kids'];
			update("hi_room_food_full", $value, "where mb_id='{$mb_id}' and full_div = '1' and pers_div= '2'");
	}else{
		unset($value);
		$value['mb_id'] = $_POST['mb_id'];
		$value['full_div']		  = '1';
		$value['pers_div']		  = '2';
		$value['food_amnt']		  = $_POST['food_kids'];
		$value['food_desc'] = "조식 소인";
		insert("hi_room_food_full", $value);
	}
}

function get_food_full_cnt($mb_id, $pers_div){

	//$pers div 1=성인 , 2=소인
	$sql = " select count(*) as full_cnt from hi_room_food_full where mb_id='{$mb_id}' and full_div = '1' and pers_div = '{$pers_div}' ";
	$result = sql_fetch($sql);
	$abc = $result['full_cnt'];

	return $abc;
}


 goto_url(TB_ADMIN_URL.'/room.php?type=room&code=list');
?>
