<?php
if(!defined('_TUBEWEB_')) exit;

/* * ***********************************************************************
 * *
 * *  가맹점 관련 함수 모음
 * *
 * *********************************************************************** */

// 가맹점인가?
function is_partner($mb_id)
{
    if(!$mb_id) return '';

	$mb = get_member($mb_id, 'grade');
	$pt = get_partner($mb_id, 'state');

    if(in_array($mb['grade'], array(2,3,4,5,6)) && $pt['state']) {
		return true;
	} else {
		return false;
	}
}

// 가맹점 정보를 리턴
function get_partner($mb_id, $fileds='*')
{
	return sql_fetch("select $fileds from shop_partner where mb_id = TRIM('$mb_id')");
}

// 가맹점 정산요청 정보를 리턴
function get_partner_payrun($index_no, $fileds='*')
{
	return sql_fetch("select $fileds from shop_partner_payrun where index_no = TRIM('$index_no')");
}

// 가맹점 관리비연장 정보를 리턴
function get_partner_term($index_no, $fileds='*')
{
	return sql_fetch("select $fileds from shop_partner_term where index_no = TRIM('$index_no')");
}

// 가맹점 계좌출력
function print_partner_bank($mb_id)
{
	$row = get_partner($mb_id, 'bank_name, bank_account, bank_holder');

	$info = array();
	$info[] = get_text($row['bank_name']); // 은행명
	$info[] = get_text($row['bank_account']); // 계좌번호
	$info[] = get_text($row['bank_holder']); // 예금주명

	if($info[0] && $info[1] && $info[2])
		$bank_str = implode(' ', $info);
	else
		$bank_str = '미등록';

	return $bank_str;
}

// 가맹점 계좌출력
function print_partner_bank2($bank_name, $bank_account, $bank_holder)
{
	$info = array();
	$info[] = get_text($bank_name); // 은행명
	$info[] = get_text($bank_account); // 계좌번호
	$info[] = get_text($bank_holder); // 예금주명

	if($info[0] && $info[1] && $info[2])
		$bank_str = implode(' ', $info);
	else
		$bank_str = '미등록';

	return $bank_str;
}

// 가맹점 등급별 설정값
function get_partner_basic($gb_no, $fields='*')
{
	$sql = " select $fields from shop_member_grade where gb_no = '$gb_no' ";
	return sql_fetch($sql);
}

// 만료일 기간연장
function get_term_date($term='1')
{
	global $config;

	// 관리비를 사용중인가?
	if($config['pf_expire_use']) {
		$term_date = date("Y-m-d", strtotime("+{$term} month", time()));
	} else {
		$term_date = '9999-12-31';
	}

	return $term_date;
}

// 실제 도메인만 추출
function get_basedomain($url)
{
	global $config;

	// 모든 공백을 제거
	$basedomain = preg_replace("/\s+/", "", $config['pf_basedomain']);

	$value = strtolower(trim($url));
	if(preg_match('/^(?:(?:[0-9a-z_]+):\/\/)?((?:[0-9a-z_\d\-]{2,}\.)+[0-9a-z_]{2,})(?::\d{1,5})?(?:\/[^\?]*)?(?:\?.+)?$/i', $value)) {
		preg_match('/([0-9a-z_\d\-]+(?:\.(?:'.$basedomain.')){1,2})(?::\d{1,5})?(?:\/[^\?]*)?(?:\?.+)?$/i', $value, $matches);
		$host = (!$matches[1]) ? $value : $matches[1];
	}

	return $host;
}

// 가맹점 PG결제 정보를 default 변수에 담는다.
function set_partner_value($mb_id)
{
	global $default;

	if(!is_partner($mb_id))
		return $default;

	$pt = get_partner($mb_id);

	$default['de_bank_use'] = $pt['de_bank_use']; // 무통장입금
	$default['de_card_use'] = $pt['de_card_use']; // 신용카드
	$default['de_iche_use'] = $pt['de_iche_use']; // 계좌이체
	$default['de_vbank_use'] = $pt['de_vbank_use']; // 가상계좌
	$default['de_hp_use'] = $pt['de_hp_use']; // 휴대폰
	$default['de_card_test'] = $pt['de_card_test']; // 결제 테스트
	$default['de_pg_service'] = $pt['de_pg_service']; // 결제대행사
	$default['de_tax_flag_use'] = $pt['de_tax_flag_use']; // 복합과세 결제
	$default['de_taxsave_use'] = $pt['de_taxsave_use']; // 현금영수증 발급사용
	$default['de_card_noint_use'] = $pt['de_card_noint_use']; // 신용카드 무이자할부사용
	$default['de_easy_pay_use'] = $pt['de_easy_pay_use']; // PG사 간편결제 버튼사용
	$default['de_escrow_use'] = $pt['de_escrow_use']; // Escrow 사용여부
	$default['de_kcp_mid'] = $pt['de_kcp_mid']; // NHN KCP SITE CODE
	$default['de_kcp_site_key'] = $pt['de_kcp_site_key']; // NHN KCP SITE KEY
	$default['de_lg_mid'] = $pt['de_lg_mid']; // LG유플러스 상점아이디
	$default['de_lg_mert_key'] = $pt['de_lg_mert_key']; // LG유플러스 MertKey
	$default['de_inicis_mid'] = $pt['de_inicis_mid']; // KG이니시스 상점아이디
	$default['de_inicis_admin_key']	= $pt['de_inicis_admin_key']; // KG이니시스 키패스워드
	$default['de_inicis_sign_key'] = $pt['de_inicis_sign_key']; // KG이니시스 웹결제 사인키
	$default['de_samsung_pay_use'] = $pt['de_samsung_pay_use']; // KG이니시스 삼성페이 버튼
	$default['de_bank_account'] = $pt['de_bank_account']; // 무통장입금계좌
	$default['de_kakaopay_mid'] = $pt['de_kakaopay_mid']; // 카카오페이 상점MID
	$default['de_kakaopay_key'] = $pt['de_kakaopay_key']; // 카카오페이 상점키
	$default['de_kakaopay_enckey'] = $pt['de_kakaopay_enckey']; // 카카오페이 EncKey
	$default['de_kakaopay_hashkey'] = $pt['de_kakaopay_hashkey']; // 카카오페이 HashKey
	$default['de_kakaopay_cancelpwd'] = $pt['de_kakaopay_cancelpwd']; // 카카오페이 결제취소 P/W
	$default['de_naverpay_mid'] = $pt['de_naverpay_mid']; // 네이버페이 가맹점 아이디
	$default['de_naverpay_cert_key'] = $pt['de_naverpay_cert_key']; // 네이버페이 가맹점 인증키
	$default['de_naverpay_button_key'] = $pt['de_naverpay_button_key']; // 네이버페이 버튼 인증키
	$default['de_naverpay_test'] = $pt['de_naverpay_test']; // 네이버페이 결제테스트 아이디
	$default['de_naverpay_mb_id'] = $pt['de_naverpay_mb_id']; // 네이버페이 결제테스트 아이디
	$default['de_naverpay_sendcost'] = $pt['de_naverpay_sendcost']; // 네이버페이 추가배송비 안내
	$default['de_nicepay_mid'] = $pt['de_nicepay_mid']; // 나이스페이 상점아이디
	$default['de_nicepay_key'] = $pt['de_nicepay_key']; // 나이스페이 상점서명키
	$default['de_nicepay_pwd'] = $pt['de_nicepay_pwd']; // 나이스페이 취소패스워드
	$default['de_innopay_mid'] = $pt['de_innopay_mid']; // INNOPAY 상점아이디
	$default['de_innopay_site_key'] = $pt['de_innopay_site_key']; // INNOPAY 상점 서명키
	$default['de_innopay_cancelpwd'] = $pt['de_innopay_cancelpwd']; // INNOPAY 거래취소 비밀번호

	return $default;
}

// 가맹점 설정정보를 default 변수에 담는다.
function set_default_value($mb_id)
{
	global $default, $gw_menu;

	if(!is_partner($mb_id))
		return $default;

	$pt = get_partner($mb_id);

	// 소셜네트워크서비스(SNS : Social Network Service)
	$default['de_sns_login_use'] = $pt['de_sns_login_use'];
	$default['de_naver_appid'] = $pt['de_naver_appid'];
	$default['de_naver_secret'] = $pt['de_naver_secret'];
	$default['de_facebook_appid'] = $pt['de_facebook_appid'];
	$default['de_facebook_secret'] = $pt['de_facebook_secret'];
	$default['de_kakao_rest_apikey'] = $pt['de_kakao_rest_apikey'];
	$default['de_kakao_js_apikey'] = $pt['de_kakao_js_apikey'];
	$default['de_googl_shorturl_apikey'] = $pt['de_googl_shorturl_apikey'];

	// INSTAGRAM / SNS 연결
	$default['de_insta_url'] = $pt['de_insta_url'];
	$default['de_insta_client_id'] = $pt['de_insta_client_id'];
	$default['de_insta_redirect_uri'] = $pt['de_insta_redirect_uri'];
	$default['de_insta_access_token'] = $pt['de_insta_access_token'];
	$default['de_sns_facebook'] = $pt['de_sns_facebook'];
	$default['de_sns_twitter'] = $pt['de_sns_twitter'];
	$default['de_sns_instagram'] = $pt['de_sns_instagram'];
	$default['de_sns_pinterest'] = $pt['de_sns_pinterest'];
	$default['de_sns_naverblog'] = $pt['de_sns_naverblog'];
	$default['de_sns_naverband'] = $pt['de_sns_naverband'];
	$default['de_sns_kakaotalk'] = $pt['de_sns_kakaotalk'];
	$default['de_sns_kakaostory'] = $pt['de_sns_kakaostory'];

	// 스킨1
	if($pt['de_skin1_menu']) $default['de_skin1_menu'] = $pt['de_skin1_menu'];
	if($pt['de_skin1_best']) $default['de_skin1_best'] = $pt['de_skin1_best'];
	if($pt['de_skin1_name']) $default['de_skin1_name'] = $pt['de_skin1_name'];

	// 메뉴 설정
	$pname_run = 0;
	for($i=0; $i<count($gw_menu); $i++) {
		$seq = ($i+1);
		if($pt['de_pname_'.$seq]) {
			$pname_run++;
		}
	}

	if($pname_run) {
		for($i=0; $i<count($gw_menu); $i++) {
			$seq = ($i+1);
			$default['de_pname_'.$seq] = $pt['de_pname_'.$seq];
		}
	}

	return $default;
}

// 가맹점 설정정보를 config 변수에 담는다.
function set_config_value($mb_id)
{
	global $config;

	if(!is_partner($mb_id))
		return $config;

	$pt = get_partner($mb_id);

	if($pt['saupja_yes']) {
		$config['company_type'] = $pt['company_type'];
		$config['shop_name'] = $pt['shop_name'];
		$config['company_name'] = $pt['company_name'];
		$config['company_saupja_no'] = $pt['company_saupja_no'];
		$config['tongsin_no'] = $pt['tongsin_no'];
		$config['company_tel'] = $pt['company_tel'];
		$config['company_fax'] = $pt['company_fax'];
		$config['company_owner'] = $pt['company_owner'];
		$config['company_zip'] = $pt['company_zip'];
		$config['company_addr'] = $pt['company_addr'];
		$config['company_hours'] = $pt['company_hours'];
		$config['company_lunch'] = $pt['company_lunch'];
		$config['company_close'] = $pt['company_close'];
		$config['info_name'] = $pt['info_name'];
		$config['info_email'] = $pt['info_email'];
	}

	$config['meta_author'] = $pt['meta_author'];
	$config['meta_description'] = $pt['meta_description'];
	$config['meta_keywords'] = $pt['meta_keywords'];
	$config['add_meta'] = $pt['add_meta'];
	$config['head_script'] = $pt['head_script'];
	$config['tail_script'] = $pt['tail_script'];

	$config['shop_intro_yes'] = $pt['shop_intro_yes'];
	$config['cert_admin_yes'] = $pt['cert_admin_yes'];

	if($pt['shop_provision']) $config['shop_provision'] = $pt['shop_provision'];
	if($pt['shop_private']) $config['shop_private'] = $pt['shop_private'];
	if($pt['shop_policy']) $config['shop_policy'] = $pt['shop_policy'];

	return $config;
}

/* * ***********************************************************************
 * *
 * *  가맹점 수수료관련 함수 모음
 * *
 * *********************************************************************** */

// 수수료 부여
function insert_pay($mb_id, $pay, $content='', $rel_table='', $rel_id='', $rel_action='', $referer='', $agent='')
{

 /*
	// 수수료가 없거나 승인된 가맹점이 아니라면 업데이트 할 필요 없음
	if($pay == 0 || !is_partner($mb_id)) { return 0; }

	// 이미 등록된 내역이라면 건너뜀
	if($rel_table || $rel_id || $rel_action)
	{
		$sql = " select count(*) as cnt
				   from shop_partner_pay
				  where mb_id = '$mb_id'
					and pp_rel_table = '$rel_table'
					and pp_rel_id = '$rel_id'
					and pp_rel_action = '$rel_action' ";
		$row = sql_fetch($sql);
		if($row['cnt'])
			return -1;
	}
 */
	$pt_pay = get_pay_sum($mb_id); // 회원수수료
	$pp_balance = $pt_pay + $pay; // 잔액

	$sql = " insert into shop_partner_pay
				set mb_id = '$mb_id'
				  , pp_datetime = '".TB_TIME_YMDHIS."'
				  , pp_content = '".addslashes($content)."'
				  , pp_pay = '$pay'
				  , pp_use_pay = '0'
				  , pp_balance = '$pp_balance'
				  , pp_rel_table = '$rel_table'
				  , pp_rel_id = '$rel_id'
				  , pp_rel_action = '$rel_action'
				  , pp_referer = '$referer'
				  , pp_agent = '$agent' ";
	sql_query($sql);

	// 수수료를 사용한 경우 수수료 내역에 사용금액 기록
	if($pay < 0) {
		insert_use_pay($mb_id, $pay);
	}

	// 수수료 UPDATE
	$sql = " update shop_member set pay = '$pp_balance' where id = '$mb_id' ";
	sql_query($sql);

	// 누적수수료에 따른 자동 레벨업
	check_promotion($mb_id);

	return 1;
}

// 사용수수료 입력
function insert_use_pay($mb_id, $pay, $pp_id='')
{
	$pay1 = abs($pay);
	$sql = " select pp_id, pp_pay, pp_use_pay
			   from shop_partner_pay
			  where mb_id = '$mb_id'
				and pp_id <> '$pp_id'
				and pp_pay > pp_use_pay
			  order by pp_id asc ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$pay2 = $row['pp_pay'];
		$pay3 = $row['pp_use_pay'];

		if(($pay2 - $pay3) > $pay1) {
			$sql = " update shop_partner_pay
						set pp_use_pay = pp_use_pay + '$pay1'
					  where pp_id = '{$row['pp_id']}' ";
			sql_query($sql);
			break;
		} else {
			$pay4 = $pay2 - $pay3;
			$sql = " update shop_partner_pay
						set pp_use_pay = pp_use_pay + '$pay4'
					  where pp_id = '{$row['pp_id']}' ";
			sql_query($sql);
			$pay1 -= $pay4;
		}
	}
}

// 사용수수료 삭제
function delete_use_pay($mb_id, $pay)
{
	$pay1 = abs($pay);
	$sql = " select pp_id, pp_use_pay
			   from shop_partner_pay
			  where mb_id = '$mb_id'
				and pp_use_pay > 0
			  order by pp_id desc ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$pay2 = $row['pp_use_pay'];

		if($pay2 > $pay1) {
			$sql = " update shop_partner_pay
						set pp_use_pay = pp_use_pay - '$pay1'
					  where pp_id = '{$row['pp_id']}' ";
			sql_query($sql);
			break;
		} else {
			$sql = " update shop_partner_pay
						set pp_use_pay = '0'
					  where pp_id = '{$row['pp_id']}' ";
			sql_query($sql);

			$pay1 -= $pay2;
		}
	}
}

// 수수료 삭제
function delete_pay($mb_id, $rel_table, $rel_id, $rel_action)
{
	$result = false;
	if($rel_table || $rel_id || $rel_action)
	{
		// 수수료 내역정보
		$sql = " select *
				   from shop_partner_pay
				  where mb_id = '$mb_id'
					and pp_rel_table = '$rel_table'
					and pp_rel_id = '$rel_id'
					and pp_rel_action = '$rel_action' ";
		$row = sql_fetch($sql);

		if($row['pp_pay'] < 0) {
			$mb_id = $row['mb_id'];
			$pp_pay = abs($row['pp_pay']);

			delete_use_pay($mb_id, $pp_pay);
		} else {
			if($row['pp_use_pay'] > 0) {
				insert_use_pay($row['mb_id'], $row['pp_use_pay'], $row['pp_id']);
			}
		}

		$sql = " delete from shop_partner_pay
				  where mb_id = '$mb_id'
					and pp_rel_table = '$rel_table'
					and pp_rel_id = '$rel_id'
					and pp_rel_action = '$rel_action' ";
		$result = sql_query($sql, false);

		// pp_balance에 반영
		$sql = " update shop_partner_pay
					set pp_balance = pp_balance - '{$row['pp_pay']}'
				  where mb_id = '$mb_id'
					and pp_id > '{$row['pp_id']}' ";
		sql_query($sql);

		// 수수료 내역의 합을 구하고
		$sum_pay = get_pay_sum($mb_id);

		// 수수료 UPDATE
		$sql = " update shop_member set pay = '$sum_pay' where id = '$mb_id' ";
		$result = sql_query($sql);
	}

	return $result;
}

// 수수료합
function get_pay_sum($mb_id)
{
	$sql = " select sum(pp_pay) as sum_pay
			   from shop_partner_pay
			  where mb_id = '$mb_id' ";
	$row = sql_fetch($sql);

	return (int)$row['sum_pay'];
}

// 유형별 수수료합
function get_pay_status($mb_id, $rel_table, $select_add='')
{
	$sql = " select count(*) as cnt,
					sum(pp_pay) as pay
			   from shop_partner_pay
			  where mb_id = '$mb_id'
				and pp_rel_table = '$rel_table'
				$select_add ";
	$row = sql_fetch($sql);

	$info = array();
	$info['cnt'] = (int)$row['cnt'];
	$info['pay'] = (int)$row['pay'];

	return $info;
}

// 수수료합 (총적립액, 총지급액)
function get_pay_sheet($mb_id)
{
	$sql_where = " where mb_id = '$mb_id' ";

	$sql1 = " select sum(pp_pay) as pay from shop_partner_pay {$sql_where} and pp_pay > 0 ";
	$row1 = sql_fetch($sql1);

	$sql2 = " select sum(pp_pay) as pay from shop_partner_pay {$sql_where} and pp_pay < 0 ";
	$row2 = sql_fetch($sql2);

	$info = array();
	$info['pay'] = (int)$row1['pay'];
	$info['usepay'] = (int)$row2['pay'];

	return $info;
}

// 누적수수료에 따른 자동 레벨업
function check_promotion($mb_id)
{
	if(!is_partner($mb_id))
		return;

	// 수수료 총적립액
	$info = get_pay_sheet($mb_id);
	$sum_pay = $info['pay'];
	if($sum_pay <= 0)
		return;

	$mb = get_member($mb_id, 'grade');

	// 최상위 레벨이면 리턴
	if($mb['grade'] == 2)
		return;

	$sql = " select gb_no, gb_promotion
			   from shop_member_grade
			  where gb_no between '2' and '6'
			  order by gb_no asc ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		if($mb['grade'] == $row['gb_no'])
			break;

		if(!$row['gb_promotion'])
			continue;

		if($sum_pay >= $row['gb_promotion']) {
			$sql = " update shop_member set grade = '{$row['gb_no']}' where id = '$mb_id' ";
			sql_query($sql);
			break;
		}
	}
}

// 추천수수료 지급
function insert_anew_pay($mb_id)
{
	global $config;

	// 추천수수료를 사용을 하지 않는다면 리턴
	if(!$config['pf_anew_use']) return;

	// 추천수수료를 적용할 단계가 없다면 리턴
	if(!$config['pf_anew_benefit_dan']) return;

	// 신청자가 가맹점이 아니면 리턴
	if(!is_partner($mb_id)) return;

	// 신청자 정보
	$pt = get_partner($mb_id, 'mb_id, anew_grade, receipt_price');

	// 가맹점개설비가 없다면 리턴
	$reg_price = (int)$pt['receipt_price'];
	if($reg_price == 0) return;

	// 신청자의 추천인을 담고
	//$mb = get_member($mb_id, 'pt_id');
	//$pt_id = $mb['pt_id'];

	// 추천인이 가맹점이 아니면 리턴
	//if(!is_partner($pt_id)) return;

	// 추천인은 본인이 될 수 없음
	if($mb_id == $pt_id) return;

  pay_anew_recursive($mb_id,$mb_id,0,'N','N','N');
  /*
	// 신청레벨에 따른 인센티브를 배열로 담는다
	$anew_benefit = explode(chr(30), $config['pf_anew_benefit_'.$pt['anew_grade']]);

	for($i=0; $i<$config['pf_anew_benefit_dan']; $i++)
	{
		// 추천인이 없거나 최고관리자라면 중지
		if(!$pt_id || $pt_id == 'admin')
			break;

		// 적용할 인센티브가 없다면 건너뜀
		$benefit = (int)trim($anew_benefit[$i]);
		if($benefit <= 0) continue;

		if($config['pf_anew_benefit_type'])
			$pt_pay = $benefit; // 설정금액(원)
		else
			$pt_pay = floor($reg_price * $benefit / 100); // 설정비율(%)

		insert_pay($pt_id, $pt_pay, $mb_id.'님 가맹점가입 축하', 'anew', $mb_id, '추천수수료');

		// 단계별 상위 추천인을 담고 다시 배열로 돌린다
		$mb = get_member($pt_id, 'pt_id');
		$pt_id = $mb['pt_id'];
	}
  */
}
//추천인 수수료 재귀함수
// 상위 추천인 추천 가맹점 수수료 20000원
// 상위 첫번째 vip 가맹점 수수료 10000원
// 상위 vvip 가맹점 수수료 5000원
function pay_anew_recursive($mb_id,$msg_id, $tree_lev, $partner_yn, $premium_yn ,$vip_yn){

  $mb = get_member($mb_id, 'pt_id, payment, payflag, grade'); // 상위추천인 아이디 , 자신의레벨

  if(!$mb['pt_id'] || $mb['pt_id'] == "admin"){ // 추천인이 없거나 최고관리자면 중지

    return;
  }

  $mb_grade = $mb['grade']; //자신의레벨
  $pt_grade = get_member($mb['pt_id'], 'grade'); // 추천인레벨


  if (($pt_grade['grade'] == "6" || $pt_grade['grade'] == "5") && ($partner_yn == "Y" || $premium_yn == "Y")) {
    pay_anew_recursive($mb['pt_id'], $msg_id, $tree_lev+1, $partner_yn, $premium_yn ,$vip_yn); // 다음레벨로 넘김
    return;
  }

  if ($pt_grade['grade'] == "6" && $partner_yn == "Y") {
    pay_anew_recursive($mb['pt_id'], $msg_id, $tree_lev+1, $partner_yn, $premium_yn ,$vip_yn); // 다음레벨로 넘김
    return;
  }

  if ($pt_grade['grade'] == "5" && $premium_yn == "Y") {
    pay_anew_recursive($mb['pt_id'], $msg_id, $tree_lev+1, $partner_yn, $premium_yn ,$vip_yn); // 다음레벨로 넘김
    return;
  }


  //차상위 VIP 회원을 한번만 주고 다음부터는 빠진다.
  if ($pt_grade['grade'] == "4" && $vip_yn == "Y") {

    pay_anew_recursive($mb['pt_id'], $msg_id ,$tree_lev+1, $partner_yn, $premium_yn ,$vip_yn); // 다음레벨로 넘김
    return;
  }

  //차상위 SVIP 회원을 한번만 주고 다음부터는 빠진다. - 추가
  if ($pt_grade['grade'] == "2" && $svip_yn == "Y") {

    pay_anew_recursive($mb['pt_id'], $msg_id ,$tree_lev+1, $partner_yn, $premium_yn ,$svip_yn); // 다음레벨로 넘김
    return;
  }



  //수수료 정산시작
   $pt_pay = 0;
   //상위 첫번째이면서 파트너회원 이면 20000원 지급
   if ($tree_lev == 0 && $pt_grade['grade'] == "6") {
     $partner_yn = 'Y';
     $pt_pay = 20000;
   }

   //상위 첫번째이면서 프리미엄회원 이면 20000원 지급
   if ($tree_lev == 0 && $pt_grade['grade'] == "5") {
     $premium_yn ='Y';
     $pt_pay = 20000;
   }

   //상위 첫번째이면서svip 이면 30000원 지급 - 추가
   if ($tree_lev == 0 && $pt_grade['grade'] == "2") {
     $svip_yn = "Y";
     $pt_pay = 30000;
   }


   //상위 첫번째이면서vip 이면 30000원 지급
   if ($tree_lev == 0 && $pt_grade['grade'] == "4") {
     $vip_yn = "Y";
     $pt_pay = 30000;
   }
   //상위 첫번째이면서vvip 이면 35000원 지급
   if ($tree_lev == 0 && $pt_grade['grade'] == "3") {
     $pt_pay = 35000;
   }

    //상위트리레벨이 0 이아니면
   if ($tree_lev != 0) {
     if ($pt_grade['grade'] == "6") {
       $partner_yn = 'Y';
       $pt_pay = 20000;
     }else if ($pt_grade['grade'] == "5") {
        $premium_yn ='Y';
       $pt_pay = 20000;

	 }else if ($pt_grade['grade'] == "2") { //svip
       $vip_yn = "Y";
       $pt_pay = 10000;

     }else if ($pt_grade['grade'] == "4") { //vip
       $vip_yn = "Y";
       $pt_pay = 5000;
	  
     }else if($pt_grade['grade'] == "3"){//vvip
       $pt_pay = 5000;
     }


   }
   //echo("추천인 : ".$mb['pt_id']." / 트리레벨 : ".$tree_lev." / 추천인레벨 :".$pt_grade['grade']." / 수수료 : ".$pt_pay."</br>");
   if($pt_pay !=0){
      insert_pay($mb['pt_id'], $pt_pay, $msg_id.'님 가맹점가입 축하', 'anew', $mb_id, '추천수수료');
   }


   pay_anew_recursive($mb['pt_id'], $msg_id, $tree_lev+1, $partner_yn, $premium_yn ,$vip_yn); // 다음레벨로 넘김
}

// 접속수수료 지급
function insert_visit_pay($mb_id, $remote_addr, $referer, $user_agent)
{
	global $config;

	// 접속수수료를 사용을 하지 않는다면 리턴
	if(!$config['pf_visit_use']) return;

	// 가맹점이 아니면 리턴
	if(!is_partner($mb_id)) return;

	// 가맹점 정보
	$mb = get_member($mb_id, 'grade');

	// 레벨에 따른 접속수수료 설정값
	$pb = get_partner_basic($mb['grade']);

	// 접속수수료가 없다면 리턴
	$pay = (int)$pb['gb_visit_pay'];

	if($pay == 0) return;

	$ip = preg_replace("/([0-9]+).([0-9]+).([0-9]+).([0-9]+)/", TB_IP_DISPLAY, $remote_addr);

	insert_pay($mb_id, $pay, "접속자 ({$ip})", "visit", $remote_addr, TB_TIME_YMD, $referer, $user_agent);
}

// 판매수수료 지급
function insert_sale_pay($pt_id, $od, $gs)
{
	global $config;

	// 판매수수료를 사용을 하지 않는다면 리턴
	if(!$config['pf_sale_use']) return;

	// 가맹점상품이면 리턴
	if($gs['use_aff']) return;

	// 가맹점이 아니면 리턴
	//if(!is_partner($pt_id)) return;

	// 가맹점 정보
	$mb = get_member($pt_id, 'grade');

	$amount = 0;

	// 원가 계산
	if($config['pf_sale_flag']) {
		if($od['supply_price'] > 0) // 공급가
			$amount = $od['goods_price'] - $od['supply_price'];

		if($config['pf_sale_flag'] == 1)
			$amount = $amount - ($od['coupon_price'] + $od['use_point']); // 할인쿠폰 + 포인트결제
	} else {
		$amount = ($od['use_price']+$od['use_money']) - $od['baesong_price']; // (순수결제액 + 머니사용금액)- 배송비
	}

	// 적용할 금액이 없다면 리턴
	if($amount < 1) return;

	if($gs['ppay_type']) { // 개별설정
		$sale_benefit_dan  = $gs['ppay_dan'];
		$sale_benefit_type = $gs['ppay_rate'];
		$sale_benefit	   = explode(chr(30), $gs['ppay_fee']);
	} else { // 공통설정
		$sale_benefit_dan  = $config['pf_sale_benefit_dan'];
		$sale_benefit_type = $config['pf_sale_benefit_type'];
		$sale_benefit	   = explode(chr(30), $config['pf_sale_benefit_'.$mb['grade']]);
	}
  $org_id = $pt_id;
  pay_sale_recursive($pt_id,0,$sale_benefit_type ,$amount,$od,"N",$org_id);
/*
	// 판매수수료를 적용할 단계가 없다면 리턴
	if($sale_benefit_dan < 1) return;

	for($i=0; $i<$sale_benefit_dan; $i++)
	{
		// 추천인이 없거나 최고관리자라면 중지
		if(!$pt_id || $pt_id == 'admin')
			break;

		// 적용할 인센티브가 없다면 건너뜀
		$benefit = (int)trim($sale_benefit[$i]);
		if($benefit <= 0) continue;

		$pt_pay = 0;

		if($sale_benefit_type)
			$pt_pay = (int)($benefit * $od['sum_qty']); // 설정금액(원)
		else
			$pt_pay = (int)($amount * $benefit / 100); // 설정비율(%)

		// 추천인 정보
		$mb = get_member($pt_id, 'pt_id, payment, payflag');

		// 개별 추가 판매수수료
		if($mb['payment']) {
			if($mb['payflag'])
				$pt_pay += (int)($mb['payment'] * $od['sum_qty']); // 설정금액(원)
			else
				$pt_pay += (int)($amount * $mb['payment'] / 100); // 설정비율(%)
		}

		// 적용할 수수료가 없다면 건너뜀
		if($pt_pay <= 0) continue;

		insert_pay($pt_id, $pt_pay, "주문번호 {$od['od_id']} ({$od['od_no']}) 배송완료", 'sale', $od['od_no'], $od['od_id']);

		// 상위 추천인을 담고 다시 배열로 돌린다
		$pt_id = $mb['pt_id'];

	} // for
  */
}
 // 판매수수료 재귀호출 함수 트리레벨 적용

function pay_sale_recursive($mb_id,$tree_lev,$sale_benefit_type ,$amount,$od, $vip_yn,$org_id){
  $org_mb = get_member($org_id, 'pt_id, payment, payflag, grade');
  $mb = get_member($mb_id, 'pt_id, payment, payflag, grade'); // 상위추천인 아이디 , 자신의레벨

  if(!$mb['pt_id'] || $mb['pt_id'] == "admin"){ // 추천인이 없거나 최고관리자면 중지

    return;
  }

  $mb_grade = $mb['grade']; //자신의레벨
  $pt_grade = get_member($mb['pt_id'], 'grade'); // 추천인레벨


  //차상위 SVIP 회원을 한번만 주고 다음부터는 빠진다.  - 추가

  if ($pt_grade['grade'] == "2" && $svip_yn == "Y") {

    pay_sale_recursive($mb['pt_id'],$tree_lev+1,$sale_benefit_type ,$amount,$od, $svip_yn, $org_id); // 다음레벨로 넘김
    return;
  }

  //차상위 VIP 회원을 한번만 주고 다음부터는 빠진다.

  if ($pt_grade['grade'] == "4" && $vip_yn == "Y") {

    pay_sale_recursive($mb['pt_id'],$tree_lev+1,$sale_benefit_type ,$amount,$od, $vip_yn, $org_id); // 다음레벨로 넘김
    return;
  }

  if ($pt_grade['grade'] == "5" && $tree_lev > 2) { // 추천인레벨이 5면서 트리레벨이 0 이아니면

    pay_sale_recursive($mb['pt_id'],$tree_lev+1,$sale_benefit_type ,$amount,$od, $vip_yn, $org_id); // 다음레벨로 넘김
    return;
  }

  if ($pt_grade['grade'] == "6" && $tree_lev != 0) { // 추천인레벨이 6면서 트리레벨이 0 이아니면 리턴

    pay_sale_recursive($mb['pt_id'],$tree_lev+1,$sale_benefit_type ,$amount,$od, $vip_yn, $org_id); // 다음레벨로 넘김
    return;
  }

  if($org_mb['grade'] == "5" && $vip_yn == "Y" && ($pt_grade['grade'] == "5" ||$pt_grade['grade'] =="6") ){
    pay_sale_recursive($mb['pt_id'],$tree_lev+1,$sale_benefit_type ,$amount,$od, $vip_yn, $org_id); // 다음레벨로 넘김
    return;
  }

  if ($pt_grade['grade'] == "1"){
    pay_sale_recursive($mb['pt_id'],$tree_lev+1,$sale_benefit_type ,$amount,$od, $vip_yn, $org_id); // 다음레벨로 넘김
    return;
  }


  //수수료 정산시작
    $lev_parent = 3;  //파트너 수수료
    $lev_premium = 3; //프리미엄 수수료
	$lev_svip = 2;    //svip 수수료 - 추가
    $lev_vpi = 2;     //vip 수수료
    $lev_vvpi =1;     //vvip 수수료

  if ($pt_grade['grade'] == "6") {
    $benefit = $lev_partner;                     // parent를 partner로 오타 수정
  }else if($pt_grade['grade'] == "5"){
    $benefit = $lev_premium;
  }else if($pt_grade['grade'] == "4"){
      $vip_yn = "Y";
      if ($mb_grade == "5" || $mb_grade == "6" || $mb_grade =="9") {
          $benefit = $lev_vpi;
      }else{
        $benefit = 9;
      }


  }else if($pt_grade['grade'] == "3"){
      $benefit = $lev_vvpi;
  }
  //자신의 레벨이 파트너스, 프리미엄, vip, svip, 일반회원 이고 직대 추천인 레벨이 vvip 이면 10% 금액을 준다
  if ($mb_grade == "5" || $mb_grade == "6" || $mb_grade == "4" || $mb_grade =="9" || $mb_grade == "2") {
    if ($tree_lev == 0 && $pt_grade['grade'] == "3") {
        $benefit = 10;
    }
  }

  //자신의 레벨이 파트너스이거나 프리미엄, svip, 일반회원 이고 추천인 레벨이 vip이면 9%금액을 준다
  if ($mb_grade == "5" || $mb_grade == "6" || $mb_grade =="9" || $mb_grade == "2") {
    if ($tree_lev == 0 && $pt_grade['grade'] == "4") {
        $benefit = 9;
    }
  }

  //자신의 레벨이 파트너스이거나 프리미엄이고 추천인 레벨이 svip이면 9%금액을 준다 -> 추가
  if ($mb_grade == "5" || $mb_grade == "6" || $mb_grade =="9") {
    if ($tree_lev == 0 && $pt_grade['grade'] == "2") {
        $benefit = 9;
    }
  }

  // 추천인 레벨이 vvip, vip, svip, 파트너회원 아니고 트리레벨이 1,2 =2% // 수정 트리레벨 1 =2%

  if ($pt_grade['grade'] != "4" || $pt_grade['grade'] != "3"  || $pt_grade['grade'] != "6" || $pt_grade['grade'] != "2") {
    if ($tree_lev == 1 && $pt_grade['grade'] == "5" || $tree_lev == 2 && $pt_grade['grade'] == "5") {
       $benefit = 2;

     }
  }

 //echo("vip 구분 : ".$vip_yn." / 추천인 아이디 : ".$mb['pt_id']." / 자신의 레벨 : ".$pt_grade['grade']." / 수수료 퍼센트 : ".$benefit." / 트리레벨 : ".$tree_lev."</br>"); // 추천인 아이디
 // 금액 정산저장 시작

 $pt_pay = 0;

  if($sale_benefit_type)
    $pt_pay = (int)($benefit * $od['sum_qty']); // 설정금액(원)
  else
    $pt_pay = (int)($amount * $benefit / 100); // 설정비율(%)



  // 개별 추가 판매수수료
  if($mb['payment']) {
    if($mb['payflag'])
      $pt_pay += (int)($mb['payment'] * $od['sum_qty']); // 설정금액(원)
    else
      $pt_pay += (int)($amount * $mb['payment'] / 100); // 설정비율(%)
  }


  if ($pt_pay > 0) {
    insert_pay($mb['pt_id'], $pt_pay, "주문번호 {$od['od_id']} ({$od['od_no']}) 배송완료", 'sale', $od['od_no'], $od['od_id']);
  }


  pay_sale_recursive($mb['pt_id'],$tree_lev+1,$sale_benefit_type ,$amount,$od,$vip_yn, $org_id); // 다음레벨로 넘김


}

// 판매수수료 예상가
function get_payment($gs_id)
{
	global $config, $member;

	// 판매수수료 노출여부 사용중이아니면 리턴
	if(!$config['pf_payment_yes']) return 0;

	// 가맹점이 아니면 리턴
	if(!is_partner($member['id'])) return 0;

	$gs = get_goods($gs_id);

	// 가맹점상품이면 리턴
	if($gs['use_aff']) return 0;

	// 원가 계산
	if($config['pf_sale_flag'])
		$amount = $gs['goods_price'] - $gs['supply_price']; // 판매가 - 공급가
	else
		$amount = $gs['goods_price']; // 판매가

	// 적용할 금액이 없다면 리턴
	if($amount < 1) return 0;

	if($gs['ppay_type']) { // 개별설정
		$sale_benefit_type = $gs['ppay_rate'];
		$sale_benefit	   = explode(chr(30), $gs['ppay_fee']);
	} else { // 공통설정
		$sale_benefit_type = $config['pf_sale_benefit_type'];
		$sale_benefit      = explode(chr(30), $config['pf_sale_benefit_'.$member['grade']]);
	}

	$benefit = (int)trim($sale_benefit[0]);

	if($sale_benefit_type)
		$pt_pay = $benefit; // 설정금액(원)
	else
		$pt_pay = (int)($amount * $benefit / 100); // 설정비율(%)

	// 개별 추가 판매수수료
	if($member['payment']) {
		if($member['payflag'])
			$pt_pay += $member['payment']; // 설정금액(원)
		else
			$pt_pay += (int)($amount * $member['payment'] / 100); // 설정비율(%)
	}

	if($pt_pay < 0)
		$pt_pay = 0;

	return $pt_pay;
}
// 프리미엄회원 승인시 5만머니 지급
function insert_money_pay($mb_id){

  $pt = get_partner($mb_id, 'state, anew_grade, receipt_price');
  $content = "가맹점 프리미업가입 축하 5만머니 지급";
  $anew_grade = $pt['anew_grade']; //가맹점 레벨
  $receipt_price = $pt['receipt_price']; //가맹점가입비용

  if ($anew_grade == "5" && $receipt_price > 0) { // 프리미엄회원이면서 가맹점 가입비가 있으면 5만머니를 준다.
    $sql = " insert into shop_money
               set mb_id = '$mb_id',
                   mo_datetime = '".TB_TIME_YMDHIS."',
                   mo_content = '".addslashes($content)."',
                   mo_point = '50000',
                   mo_use_point = '0',
                   mo_mb_point = '50000',
                   mo_expired = '',
                   mo_expire_date = '',
                   mo_rel_table = '',
                   mo_rel_id = '',
                   mo_rel_action = '' ";
   sql_query($sql);

   $m_sql = "update shop_member set money ='50000' where id = '".$mb_id."' ";
   sql_query($m_sql);
  }
}

//신용카드자동승인 start
// ============================================================//
function set_partnerAuto($mb_id , $anew_grade){
  global $config;
	//$mb_id = $member['id'];
	$pt = get_partner($mb_id, 'state, anew_grade');

	// 이미승인된 회원이면 건너뜀
	if($pt['state'])
		continue;

	// 승인
	$sql = " update shop_partner set state = '1' where mb_id = '$mb_id' ";
	sql_query($sql);

	$term_date = get_term_date($config['pf_expire_term']); // 만료일

	$sql = " update shop_member
				set grade = '{$_POST['anew_grade']}'
					, anew_date = '".TB_TIME_YMD."'
					, term_date = '$term_date'
				where id = '$mb_id' ";
	sql_query($sql);
	if ($anew_grade == "5") {
		// 추천 소개수수료
		insert_anew_pay($mb_id);
		// 가맹점 가입 머니지급
		 insert_money($mb_id, 50000 , TB_TIME_YMD.' 가맹점 프리미업가입 축하 5만머니 지급', '@partner', $mb_id, TB_TIME_YMD);
		//sbok 지급
		insert_sbok_pay($mb_id);
		// 신용카드 자동승인 end
	}
}
// ============================================================//

?>
