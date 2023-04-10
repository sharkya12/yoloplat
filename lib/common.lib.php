<?php
if(!defined('_TUBEWEB_')) exit; // 개별 페이지 접근 불가

// 회원 레이어
function get_sideview($mb_id, $name, $type)
{
	// 에러방지를 위해 기호를 치환
	$name = get_text($name, 0, true);

	if(!is_admin() || !$mb_id || $mb_id == 'admin')
		return $name;

	// 사이드뷰 시작
	$mb = get_member($mb_id, 'email, cellphone');
	$phone = conv_number($mb['cellphone']);

	$email = get_email_address($mb['email']);
    $email_enc = new str_encrypt();
    $email = $email_enc->encrypt($email);
	$email = get_text($email);

	$str = "<span class=\"sv_wrap\">\n";
	$str.= "<a href=\"javascript:void(0);\" class=\"sv_member\">{$name}</a>\n";

	$str2 = "<span class=\"sv\">\n";

	$str2.= "<a href=\"".TB_ADMIN_URL."/pop_memberform.php?mb_id={$mb_id}\" onclick=\"win_open(this,'win_member','1200','600','yes');return false;\">회원정보수정</a>\n";

	if(is_seller($mb_id))
		$str2.= "<a href=\"".TB_ADMIN_URL."/pop_sellerform.php?mb_id={$mb_id}\" onclick=\"win_open(this,'win_seller','1200','600','yes');return false;\">공급사정보수정</a>\n";

	if($email)
		$str2 .= "<a href=\"".TB_ADMIN_URL."/formmail.php?mb_id=".$mb_id."&name=".urlencode($name)."&email=".$email."\" onclick=\"win_open(this,'win_email','650','580','no'); return false;\">메일보내기</a>\n";

	if($phone)
		$str2.= "<a href=\"".TB_ADMIN_URL."/sms/sms_user.php?ph={$phone}\" onclick=\"win_open(this,'win_sms','300','360','no'); return false;\">SMS보내기</a>\n";

	//$str2.= "<a href=\"".TB_ADMIN_URL."/admin_ss_login.php?mb_id={$mb_id}\" target=\"_blank\">카방로그인</a>\n";

	if(is_partner($mb_id))
		$str2.= "<a href=\"".TB_ADMIN_URL."/admin_ss_login.php?mb_id={$mb_id}&lg_type=P\" target=\"_blank\">가맹점로그인</a>\n";

	if(is_room($mb_id))
	{
		if ($type == 'room') {
			$str2.= "<a href=\"".TB_ADMIN_URL."/admin_ss_login.php?mb_id={$mb_id}&lg_type=S\" target=\"_blank\">숙박로그인</a>\n";
		}
	}

	if(is_rent($mb_id)){
		if ($type == 'rent') {
			$str2.= "<a href=\"".TB_ADMIN_URL."/admin_ss_login.php?mb_id={$mb_id}&lg_type=R\" target=\"_blank\">렌트로그인</a>\n";
		}

	}

	if(is_exp($mb_id)){
		if ($type == 'exp') {
			$str2.= "<a href=\"".TB_ADMIN_URL."/admin_ss_login.php?mb_id={$mb_id}&lg_type=E\" target=\"_blank\">체험로그인</a>\n";
		}

	}

	$str2.= "</span>\n";
	$str.= $str2;
	$str.= "\n<noscript class=\"sv_nojs\">".$str2."</noscript>";

	$str.= "</span>";

	return $str;
}

// 로고
function display_logo($filed='basic_logo')
{
	global $pt_id;

	$row = sql_fetch("select $filed from shop_logo where mb_id='$pt_id'");
	if(!$row[$filed] && $pt_id != 'admin') {
		$row = sql_fetch("select $filed from shop_logo where mb_id='admin'");
	}

	$file = TB_DATA_PATH.'/banner/'.$row[$filed];
	if(is_file($file) && $row[$filed]) {
		$file = rpc($file, TB_PATH, TB_URL);
		return '<a href="'.TB_URL.'"><img src="'.$file.'"></a>';
	} else {
		return '';
	}
}

// 인기검색어
function get_popular($rows, $pt_id)
{
	$str = "";

	$sql = " select pp_word, count(*) as cnt
			   from shop_popular
			  where pt_id = '$pt_id'
			    and TRIM(pp_word) <> ''
			  group by pp_word
			  order by cnt desc
			  limit $rows ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++){
		$word = get_text($row['pp_word']);
		$href = TB_SHOP_URL.'/search.php?ss_tx='.$word;
		$str .= "<li><a href=\"{$href}\">{$word}</a></li>\n";
	}

	return $str;
}

// alert 메세지 출력
function alert($msg, $move='back', $myname='')
{
	if(!$msg) $msg = '올바른 방법으로 이용해 주십시오.';

	switch($move)
	{
		case "back" :
			$url = "history.go(-1);void(1);";
			break;
		case "close" :
			$url = "window.close();";
			break;
		case "parent" :
			$url = "parent.document.location.reload();";
			break;
		case "replace" :
			$url = "opener.document.location.reload();window.close();";
			break;
		case "no" :
			$url = "";
			break;
		case "shash" :
			$url = "location.hash='{$myname}';";
			break;
		case "thash" :
			$url  = "opener.document.location.reload();";
			$url .= "opener.document.location.hash='{$myname}';";
			$url .= "window.close();";
			break;
		default :
			$url = "location.href='{$move}'";
			break;
	}

	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">";
	echo "<script type=\"text/javascript\">alert(\"{$msg}\");{$url}</script>";
	exit;
}

// 날짜, 조회수의 경우 높은 순서대로 보여져야 하므로 $flag 를 추가
// $flag : asc 낮은 순서 , desc 높은 순서
// 제목별로 컬럼 정렬하는 QUERY STRING
function subject_sort_link($col, $query_string)
{
	global $filed, $orderby;

	if($orderby == 'asc') {
		$q2 = "&filed=$col&orderby=desc";
	} else {
		$q2 = "&filed=$col&orderby=asc";
	}

	return "<a href=\"{$_SERVER['SCRIPT_NAME']}?{$query_string}{$q2}\">";
}

// 5차카테고리
function tree_category($catecode)
{
	global $pt_id;

	$str = "";

	$t_catecode = $catecode;

	$sql_common = " from shop_category ";
	$sql_where  = " where cateuse = '0' and find_in_set('$pt_id', catehide) = '0' ";
	$sql_order  = " order by caterank, catecode ";

	$sql = " select count(*) as cnt {$sql_common} {$sql_where} and upcate = '$catecode' ";
	$res = sql_fetch($sql);
	if($res['cnt'] < 1) {
		$catecode = substr($catecode,0,-3);
	}

	$mod = 5; // 1줄당 노출 수
	$li_width = (int)(100 / $mod);

	$sql = "select * {$sql_common} {$sql_where} and upcate = '$catecode' {$sql_order} ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		if($i==0) $str .= '<ul class="sub_tree">'.PHP_EOL;

		$addclass = "";
		if($t_catecode==$row['catecode'])
			$addclass = ' class="active"';

		$href = TB_SHOP_URL.'/list.php?ca_id='.$row['catecode'];
		$str .= "<li style=\"width:{$li_width}%\"{$addclass}><a href=\"{$href}\">{$row['catename']}</a></li>".PHP_EOL;
	}

	if($i > 0) $str .= '</ul>'.PHP_EOL;

	return $str;
}

//point 카테고리

function point_category()
{


	$str = "";

	$t_catecode = $catecode;

	$sql_common = " from gift_goods ";
	//$sql_where  = " where cateuse = '0' and find_in_set('$pt_id', catehide) = '0' ";
	$sql_where = " where goodsStateCd = 'SALE'";
	$sql_group_by = " group by goodsTypeDtlNm ";
	$sql_order  = " order by goodsTypeDtlNm ";

	$sql = " select count(*) as cnt {$sql_common} {$sql_where} {$sql_group_by} ";
	$res = sql_fetch($sql);
	if($res['cnt'] < 1) {
		$catecode = substr($catecode,0,-3);
	}

	$mod = 5; // 1줄당 노출 수
	$li_width = (int)(100 / $mod);

	$sql = "select * {$sql_common} {$sql_where} {$sql_group_by} {$sql_order} ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		if($i==0) $str .= '<ul class="sub_tree">'.PHP_EOL;

		$addclass = "";
		if($t_catecode==$row['goodsTypeDtlNm'])
			$addclass = ' class="active"';

		$href = TB_SHOP_URL.'/point_list.php?ca_id='.$row['goodsTypeDtlNm'];
		$str .= "<li style=\"width:{$li_width}%\"{$addclass}><a href=\"{$href}\">{$row['goodsTypeDtlNm']}</a></li>".PHP_EOL;
	}

	if($i > 0) $str .= '</ul>'.PHP_EOL;

	return $str;
}

// get_listtype_skin('영역', '이미지가로', '이미지세로', '총 출력수', '추가 class')
function get_listtype_skin($type, $width, $height, $rows, $li_css='')
{
	global $pt_id;

	$str = "";

	$result = display_itemtype($pt_id, $type, $rows);
	for($i=0; $row=sql_fetch_array($result); $i++)
	{
		if($i==0)
			$str .= "<div class=\"pr_desc {$li_css}\">\n<ul>\n";

		$it_href = TB_SHOP_URL.'/view.php?index_no='.$row['index_no'];
		$it_image = get_it_image($row['index_no'], $row['simg1'], $width, $height);
		$it_name = cut_str($row['gname'], 100);
		$it_price = get_price($row['index_no']);
		$it_amount = get_sale_price($row['index_no']);
		$it_point = display_point($row['gpoint']);

		$is_uncase = is_uncase($row['index_no']);
		$is_free_baesong = is_free_baesong($row);
		$is_free_baesong2 = is_free_baesong2($row);

		// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
		$it_sprice = $sale = '';
		if($row['normal_price'] > $it_amount && !$is_uncase) {
			$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
			$sale = '<p class="sale">'.number_format($sett,0).'<span>%</span></p>';
			$it_sprice = display_price2($row['normal_price']);
		}

		$str .= "<li>\n";
			$str .= "<a href=\"{$it_href}\">\n";
			$str .= "<dl>\n";
				$str .= "<dt>{$it_image}</dt>\n";
				$str .= "<dd class=\"pname\">{$it_name}</dd>\n";
				$str .= "<dd class=\"price\">{$it_sprice}{$it_price}</dd>\n";
				if( !$is_uncase && ($row['gpoint'] || $is_free_baesong || $is_free_baesong2) ) {
					$str .= "<dd class=\"petc\">\n";
					if($row['gpoint'])
						$str .= "<span class=\"fbx_small fbx_bg6\">{$it_point} 적립</span>\n";
					if($is_free_baesong)
						$str .= "<span class=\"fbx_small fbx_bg4\">무료배송</span>\n";
					if($is_free_baesong2)
						$str .= "<span class=\"fbx_small fbx_bg4\">조건부무료배송</span>\n";
					$str .= "</dd>\n";
				}
			$str .= "</dl>\n";
			$str .= "</a>\n";
			$str .= "<p class=\"ic_bx\"><span onclick='javascript:itemlistwish(\"$row[index_no]\")' id=\"$row[index_no]\" class=\"$row[index_no] ".zzimCheck($row['index_no'])."\"></span> <a href=\"{$it_href}\" target=\"_blank\" class=\"nwin\"></a></p>\n";
		$str .= "</li>\n";
	}

	if($i > 0)
		$str .= "</ul>\n</div>\n";

	return $str;
}

// get_listtype_best('영역', '이미지가로', '이미지세로', '총 출력수', '추가 class')
function get_listtype_best($type, $width, $height, $rows, $li_css='')
{
	global $pt_id;

	$str = "";

	$result = display_itemtype($pt_id, $type, $rows);
	for($i=0; $row=sql_fetch_array($result); $i++)
	{
		if($i==0)
			$str .= "<div class=\"pr_desc2 {$li_css}\">\n<ul>\n";

		$it_href = TB_SHOP_URL.'/view.php?index_no='.$row['index_no'];
		$it_image = get_it_image($row['index_no'], $row['simg1'], $width, $height);
		$it_name = cut_str($row['gname'], 100);
		$it_price = get_price($row['index_no']);
		$it_amount = get_sale_price($row['index_no']);
		$it_point = display_point($row['gpoint']);

		$is_uncase = is_uncase($row['index_no']);
		$is_free_baesong = is_free_baesong($row);
		$is_free_baesong2 = is_free_baesong2($row);

		// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
		$it_sprice = $sale = '';
		if($row['normal_price'] > $it_amount && !$is_uncase) {
			$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
			$sale = '<p class="sale">'.number_format($sett,0).'<span>%</span></p>';
			$it_sprice = display_price2($row['normal_price']);
		}

		$str .= "<li>\n";
			$str .= "<a href=\"{$it_href}\">\n";
			$str .= "<dl>\n";
				$str .= "<dt>{$it_image}</dt>\n";
				$str .= "<dd>\n";
					$str .= "<div>\n";
					$str .= "<p class=\"pname\">{$it_name}</p>\n";
					$str .= $it_sprice.$it_price;
					if( !$is_uncase && ($row['gpoint'] || $is_free_baesong || $is_free_baesong2) ) {
						$str .= "<p class=\"petc\">\n";
						if($row['gpoint'])
							$str .= "<span class=\"fbx_small fbx_bg6\">{$it_point} 적립</span>\n";
						if($is_free_baesong)
							$str .= "<span class=\"fbx_small fbx_bg4\">무료배송</span>\n";
						if($is_free_baesong2)
							$str .= "<span class=\"fbx_small fbx_bg4\">조건부무료배송</span>\n";
						$str .= "</p>\n";
					}
					$str .= "</div>\n";
				$str .= "</dd>\n";
			$str .= "</dl>\n";
			$str .= "</a>\n";
			$str .= "<p class=\"ic_bx\"><span onclick='javascript:itemlistwish(\"$row[index_no]\")' id=\"$row[index_no]\" class=\"$row[index_no] ".zzimCheck($row['index_no'])."\"></span> <a href=\"{$it_href}\" target=\"_blank\" class=\"nwin\"></a></p>\n";
		$str .= "</li>\n";
	}

	if($i > 0)
		$str .= "</ul>\n</div>\n";

	return $str;
}

// get_listtype_cate('설정값', '이미지가로', '이미지세로')
function get_listtype_cate($list_best, $width, $height)
{
	$mod = 4;
	$ul_str = '';

	for($i=0; $i<count($list_best); $i++) {
		$str = '';

		$list_code = explode(",", $list_best[$i]['code']); // 배열을 만들고
		$list_code = array_unique($list_code); //중복된 아이디 제거
		$list_code = array_filter($list_code); // 빈 배열 요소를 제거
		$list_code = array_values($list_code); // index 값 주기

		$succ_count = 0;
		for($g=0; $g<count($list_code); $g++) {
			$gcode = trim($list_code[$g]);
			$row = sql_fetch(" select * from hi_goods where gcode = '$gcode' ");
			if(!$row['index_no']) continue;
			if($succ_count >= $mod) break;

			$it_href = TB_SHOP_URL.'/view.php?index_no='.$row['index_no'];
			$it_image = get_it_image($row['index_no'], $row['simg1'], $width, $height);
			$it_name = cut_str($row['gname'], 100);
			$it_price = get_price($row['index_no']);
			$it_amount = get_sale_price($row['index_no']);
			$it_point = display_point($row['gpoint']);

			$is_uncase = is_uncase($row['index_no']);
			$is_free_baesong = is_free_baesong($row);
			$is_free_baesong2 = is_free_baesong2($row);

			// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
			$it_sprice = $sale = '';
			if($row['normal_price'] > $it_amount && !$is_uncase) {
				$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
				$sale = '<p class="sale">'.number_format($sett,0).'<span>%</span></p>';
				$it_sprice = display_price2($row['normal_price']);
			}

			$str .= "<li>\n";
				$str .= "<a href=\"{$it_href}\">\n";
				$str .= "<dl>\n";
					$str .= "<dd class=\"pname\">{$it_name}</dd>\n";
					$str .= "<dd class=\"pimg\">{$it_image}</dd>\n";
					$str .= "<dd class=\"price\">{$it_sprice}{$it_price}</dd>\n";
					if( !$is_uncase && ($row['gpoint'] || $is_free_baesong || $is_free_baesong2) ) {
						$str .= "<dd class=\"petc\">\n";
						if($row['gpoint'])
							$str .= "<span class=\"fbx_small fbx_bg6\">{$it_point} 적립</span>\n";
						if($is_free_baesong)
							$str .= "<span class=\"fbx_small fbx_bg4\">무료배송</span>\n";
						if($is_free_baesong2)
							$str .= "<span class=\"fbx_small fbx_bg4\">조건부무료배송</span>\n";
						$str .= "</dd>\n";
					}
				$str .= "</dl>\n";
				$str .= "</a>\n";
				$str .= "<p class=\"ic_bx\"><span onclick='javascript:itemlistwish(\"$row[index_no]\")' id=\"$row[index_no]\" class=\"$row[index_no] ".zzimCheck($row['index_no'])."\"></span> <a href=\"{$it_href}\" target=\"_blank\" class='nwin'></a></p>\n";
			$str .= "</li>\n";

			$succ_count++;
		} // for end

		// 나머지 li
		$cnt = $succ_count%$mod;
		if($cnt) {
			for($k=$cnt; $k<$mod; $k++) { $str .= "<li></li>\n"; }
		}

		if(!$str) $str = "<li class=\"empty_list\">자료가 없습니다.</li>\n";

		$ul_str .= "<ul id=\"bstab_c{$i}\">\n{$str}</ul>\n";
	}

	return $ul_str;
}

// 게시판 리스트 가져오기
function board_latest($boardid, $len, $rows, $pt_id)
{
	global $default;

	$sql_where = "";
	if($default['de_board_wr_use']) {
		$sql_where = " where pt_id = '$pt_id' ";
	}

	$str = '';

	$sql = "select * from shop_board_{$boardid} $sql_where order by wdate desc limit $rows ";
	$res = sql_query($sql);
	for($i=0;$row=sql_fetch_array($res);$i++){
		$subject = cut_str($row['subject'],$len);
		$wdate = date('Y-m-d',intval($row['wdate'],10));
		$href  = TB_BBS_URL."/read.php?boardid={$boardid}&index_no={$row['index_no']}";

		$str .= "<dd><a href=\"{$href}\">{$subject}</a><span class=\"day\">{$wdate}</span></dd>\n";
	}

	return $str;
}

// 회원 총 주문수
function shop_count($mb_id)
{
	if(!$mb_id) return 0;

	$sql = " select count(*) as cnt
			   from hi_order
			  where mb_id = '$mb_id'
				and dan IN(1,2,3,4,5,8) ";
	$row = sql_fetch($sql);

	return (int)$row['cnt'];
}

// 회원 총 주문액
function shop_price($mb_id)
{
	if(!$mb_id) return 0;

	$sql = " select SUM(goods_price + baesong_price) as price
			  from hi_order
			 where mb_id = '$mb_id'
			   and dan IN(1,2,3,4,5,8) ";
	$row = sql_fetch($sql);

	return (int)$row['price'];
}

// 승인완료 검사
function admRequest($table, $add_query='')
{
	if($table == 'shop_goods1') {
		$table = "hi_goods";
		$filed = "shop_state";
		$value = '1';
	} else if($table == 'shop_goods2') {
		$table = "hi_goods";
		$filed = "shop_state";
		$value = '2';
	} else if($table == 'hi_goods_qa') {
		$filed = "iq_reply";
		$value = '0';
	} else {
		$filed = "state";
		$value = '0';
	}

	$sql = "select count(*) as cnt from $table where $filed = '$value' {$add_query} ";
	$row = sql_fetch($sql);

	return (int)$row['cnt'];
}

function sel_count($table, $where)
{
	$row = sql_fetch("select count(*) as cnt from $table $where ");
	return (int)$row['cnt'];
}

// 주문관리(엑셀저장) 공통
function excel_order_list($row, $amount)
{
	// 결제수단
	$od_paytype = '';
	if($row['paymethod']) {
		$od_paytype = $row['paymethod'];

		if($row['paymethod'] == '간편결제') {
			switch($row['od_pg']) {
				case 'lg':
					$od_paytype = 'PAYNOW';
					break;
				case 'inicis':
					$od_paytype = 'KPAY';
					break;
				case 'kcp':
					$od_paytype = 'PAYCO';
					break;
				default:
					$od_paytype = $row['paymethod'];
					break;
			}
		}
	} else {
		$od_paytype = '결제수단없음';
	}

	// 포인트결제가 포함되어있나?
	if($amount['usepoint'] > 0 && $row['paymethod'] != '포인트')
		$od_paytype.= '+포인트';

	// 에스크로 결제인가?
	if($row['od_escrow'])
		$od_paytype.= '(에스크로)';

	// 테스트 주문인가?
	$od_test = '';
	if($row['od_test'])
		$od_test = '(테스트)';

	// 모바일 주문인가?
	$od_mobile = 'PC';
	if($row['od_mobile'])
		$od_mobile = '모바일';

	// 주문자가 회원인가?
	if($row['mb_id'])
		$od_mb_id = $row['mb_id'];
	else
		$od_mb_id = '비회원';

	if(!$row['pt_id'] || $row['pt_id'] == 'admin')
		$od_pt_id = '본사';
	else {
		$mb = get_member($row['pt_id'], 'name');
		if(!$mb['name']) $mb['name'] = '정보없음';
		$od_pt_id = $mb['name'].'('.$row['pt_id'].')';
	}

	// 거래증빙 요청이있나?
	$od_taxbill = '';
	if($row['taxbill_yes'] == 'Y')
		$od_taxbill = "세금계산서 발급요청";
	else if($row['taxsave_yes'] == 'Y' || $row['taxsave_yes'] == 'S')
		$od_taxbill = "현금영수증 발급요청";

	// 배송정보 (예:배송회사|배송추적URL)
	list($delivery_company, $delivery_url) = explode('|', $row['delivery']);

	// 옵션정보
	$it_options = print_complete_options($row['gs_id'], $row['od_id'], 1);

	// 판매자정보
	if($row['seller_id'] == 'admin') {
		$od_seller_id = '본사';
	} else if(substr($row['seller_id'],0,3) == 'AP-') {
		$sr = get_seller_cd($row['seller_id'], 'company_name');
		if(!$sr['company_name']) $sr['company_name'] = '정보없음';
		$od_seller_id = $sr['company_name'].'('.$row['seller_id'].')';
	} else {
		$mb = get_member($row['seller_id'], 'name');
		if(!$mb['name']) $mb['name'] = '정보없음';
		$od_seller_id = $mb['name'].'('.$row['seller_id'].')';
	}

	// 입금일시가 시간이 비었다면 값을 비운다.
	if(is_null_time($row['receipt_time'])) {
		$row['receipt_time'] = '';
	}

	$info = array();
	$info['od_paytype']			 = $od_paytype;
	$info['od_test']			 = $od_test;
	$info['od_mobile']			 = $od_mobile;
	$info['od_mb_id']			 = $od_mb_id;
	$info['od_pt_id']			 = $od_pt_id;
	$info['od_seller_id']		 = $od_seller_id;
	$info['od_taxbill']			 = $od_taxbill;
	$info['it_options']			 = $it_options;
	$info['od_delivery_company'] = $delivery_company;
	$info['od_receipt_time']	 = $row['receipt_time'];

	return $info;
}

// 주문관리 공통
function get_order_list($row, $amount, $baesong_search='')
{
	// 결제수단
	$disp_paytype = '';
	if($row['paymethod']) {
		$disp_paytype = $row['paymethod'];

		if($row['paymethod'] == '간편결제') {
			switch($row['od_pg']) {
				case 'lg':
					$disp_paytype = 'PAYNOW';
					break;
				case 'inicis':
					$disp_paytype = 'KPAY';
					break;
				case 'kcp':
					$disp_paytype = 'PAYCO';
					break;
				default:
					$disp_paytype = $row['paymethod'];
					break;
			}
		}
	} else {
		$disp_paytype = '결제수단없음';
	}

	// 포인트결제가 포함되어있나?
	if($amount['usepoint'] > 0 && $row['paymethod'] != '포인트')
		$disp_paytype.= '<span class="list_point">포인트</span>';

	// 에스크로 결제인가?
	if($row['od_escrow'])
		$disp_paytype.= '<span class="list_escrow">에스크로</span>';

	// 테스트 주문인가?
	$disp_test = '';
	if($row['od_test'])
		$disp_test = '<span class="list_test">테스트</span>';

	// 모바일 주문인가?
	$disp_mobile = '';
	if($row['od_mobile'])
		$disp_mobile = '(M)';

	// 주문자가 회원인가?
	if($row['mb_id'])
		$disp_mb_id = '<span class="list_mb_id">('.$row['mb_id'].')</span>';
	else
		$disp_mb_id = '<span class="list_mb_id">(비회원)</span>';

	if(!$row['pt_id'] || $row['pt_id'] == 'admin')
		$disp_pt_id = '본사';
	else {
		$mb = get_member($row['pt_id'], 'name');
		$mb_name = get_sideview($row['pt_id'], $mb['name']);
		if(!$mb_name) $mb_name = '정보없음';
		$disp_pt_id = $mb_name.'<span class="list_mb_id">('.$row['pt_id'].')</span>';
	}

	// 거래증빙 요청이있나?
	if($row['taxbill_yes'] == 'Y')
		$disp_taxbill = "세금계산서 발급요청";
	else if($row['taxsave_yes'] == 'Y' || $row['taxsave_yes'] == 'S')
		$disp_taxbill = "현금영수증 발급요청";
	else
		$disp_taxbill = '<span class="txt_expired">요청안함</span>';

	// 부분배송이 있는가?
	$disp_baesong = '';
	if($baesong_search) {
		$sql = " select count(*) as cnt
				   from hi_order
				  where od_id = '{$row['od_id']}'
					{$baesong_search} ";
		$tmp = sql_fetch($sql);
		if($tmp['cnt'])
			$disp_baesong = '<span class="list_baesong">부분배송</span>';
	}

	$info = array();
	$info['disp_paytype']	 = $disp_paytype;
	$info['disp_test']		 = $disp_test;
	$info['disp_mobile']	 = $disp_mobile;
	$info['disp_mb_id']		 = $disp_mb_id;
	$info['disp_pt_id']		 = $disp_pt_id;
	$info['disp_taxbill']	 = $disp_taxbill;
	$info['disp_od_name']	 = get_sideview($row['mb_id'], $row['name']);
	$info['disp_baesong']	 = $disp_baesong;
	$info['disp_price']		 = number_format($amount['buyprice']);

	return $info;
}

// 주문관리 판매자
function get_order_seller_id($seller_id)
{
	if($seller_id == 'admin') {
		$disp_sr_id = '본사';
	} else if(substr($seller_id,0,3) == 'AP-') {
		$sr = get_seller_cd($seller_id, 'mb_id');
		$disp_sr_id = get_sideview($sr['mb_id'], $seller_id);
	} else {
		$disp_sr_id = get_sideview($seller_id, $seller_id);
	}

	return $disp_sr_id;
}

// 주문상태에 따른 합계 금액
function admin_order_status_sum($where)
{
	$sql = " select od_id from hi_order {$where} group by od_id ";
	$res = sql_query($sql);
	$od_count = sql_num_rows($res);

	$sql = " select SUM(goods_price + baesong_price) as price from hi_order {$where} ";
	$row = sql_fetch($sql);
	$od_price = (int)$row['price'];

	$info = array();
	$info['cnt']   = $od_count;
	$info['price'] = $od_price;

	return $info;
}

// 가맹점 주문합계
function partner_order_status_sum($pt_id, $sql_search='')
{
	$sql = " select od_id
			   from hi_order
			   where pt_id = '$pt_id'
			     and dan IN(1,2,3,4,5,8)
			   {$sql_search}
			   group by od_id ";
	$res = sql_query($sql);
	$od_count = sql_num_rows($res);

	$sql = " select SUM(goods_price + baesong_price) as price
				from hi_order
				where pt_id = '$pt_id'
				  and dan IN(1,2,3,4,5,8)
				{$sql_search} ";
	$row = sql_fetch($sql);
	$od_price = (int)$row['price'];

	$info = array();
	$info['cnt']   = $od_count;
	$info['price'] = $od_price;

	return $info;
}

// 총 재고부족 상품
function admin_gs_jaego_bujog($add_query='')
{
	$sql = " select count(*) as cnt
			   from hi_goods
			  where stock_qty <= noti_qty and stock_mod = 1 and opt_subject = ''
				{$add_query} ";
	$row = sql_fetch($sql);

	return (int)$row['cnt'];
}

// 총 옵션재고부족 상품
function admin_io_jaego_bujog($add_query='')
{
	$sql = " select count(*) as cnt
			   from hi_goods_option a left join hi_goods b on (a.gs_id=b.index_no)
			  where a.io_use = 1
			    and a.io_noti_qty <> '999999999'
			    and a.io_stock_qty <= a.io_noti_qty
				{$add_query} ";
	$row = sql_fetch($sql);

	return (int)$row['cnt'];
}

// 총 주문 관리자메모
function admin_order_memo($add_query='')
{
	$sql = " select od_id from hi_order where shop_memo <> '' {$add_query} group by od_id ";
	$res = sql_query($sql);
	return sql_num_rows($res);
}

// 총 상품평점 수
function admin_goods_review($add_query='')
{
	$row = sql_fetch("select count(*) as cnt from hi_goods_review where 1 {$add_query} ");
	return (int)$row['cnt'];
}

//  주문관리에 사용될 배송업체 정보를 select로 얻음
function get_delivery_select($name, $selected='', $event='')
{
	global $config;

	$str = "<select name=\"{$name}\"{$event}>\n";
	$str.= "<option value=\"\">배송사선택</option>\n";
	$info = array_filter(explode(",",trim($config['delivery_company'])));
	foreach($info as $k=>$v) {
		$arr = explode("|",trim($info[$k]));
		if(trim($arr[0])){
			$str .= option_selected($info[$k], $selected, trim($arr[0]));
		}
	}
	$str .= "</select>";

	return $str;
}

//  송장번호 일괄등록시 배송추척 URL 추출 (본사, 업체 공용)
function get_info_delivery($company)
{
	global $config;

	if(!$company) return '';

	$fld = trim($company);

	$info = array_filter(explode(",",$config['delivery_company']));
	foreach($info as $k=>$v) {
		$arr = explode("|",trim($info[$k]));
		if(trim($arr[0]) == trim($company)){
			$fld = trim($info[$k]);
			break;
		}
	}

	return $fld;
}

// 쿠폰 : 상세내역
function get_cp_contents()
{
	global $row, $gw_usepart;

	$str = "";
	$str .= "<div>&#183; <strong>".get_text($row['cp_subject'])."</strong></div>";

	// 동시사용 여부
	$str .= "<div class='fc_eb7'>&#183; ";
	if(!$row['cp_dups']) {
		$str .= '동일한 주문건에 중복할인 가능';
	} else {
		$str .= '동일한 주문건에 중복할인 불가 (1회만 사용가능)';
	}
	$str .= "</div>";

	// 쿠폰유효 기간
	$str .= "<div>&#183; 쿠폰유효 기간 : ";
	if(!$row['cp_inv_type']) {
		// 날짜
		if($row['cp_inv_sdate'] == '9999999999') $cp_inv_sdate = '';
		else $cp_inv_sdate = $row['cp_inv_sdate'];

		if($row['cp_inv_edate'] == '9999999999') $cp_inv_edate = '';
		else $cp_inv_edate = $row['cp_inv_edate'];

		if($row['cp_inv_sdate'] == '9999999999' && $row['cp_inv_sdate'] == '9999999999')
			$str .= '제한없음';
		else
			$str .= $cp_inv_sdate . " ~ " . $cp_inv_edate ;

		// 시간대
		$str .= "&nbsp;(시간대 : ";
		if($row['cp_inv_shour1'] == '99') $cp_inv_shour1 = '';
		else $cp_inv_shour1 = $row['cp_inv_shour1'] . "시부터";

		if($row['cp_inv_shour2'] == '99') $cp_inv_shour2 = '';
		else $cp_inv_shour2 = $row['cp_inv_shour2'] . "시까지";

		if($row['cp_inv_shour1'] == '99' && $row['cp_inv_shour1'] == '99')
			$str .= '제한없음';
		else
			$str .= $cp_inv_shour1 . " ~ " . $cp_inv_shour2 ;
		$str .= ")";
	} else {
		$cp_inv_day = date("Y-m-d",strtotime("+{$row[cp_inv_day]} days",strtotime($row['cp_wdate'])));
		$str .= '다운로드 완료 후 ' . $row['cp_inv_day']. '일간 사용가능, 만료일('.$cp_inv_day.')';
	}
	$str .= "</div>";

	// 혜택
	$str .= "<div>&#183; ";
	if($row['cp_sale_type'] == '0') {
		if($row['cp_sale_amt_max'] > 0)
			$cp_sale_amt_max = "&nbsp;(최대 ".display_price($row['cp_sale_amt_max'])."까지 할인)";
		else
			$cp_sale_amt_max = "";

		$str .= $row['cp_sale_percent']. '% 할인' . $cp_sale_amt_max;
	} else {
		$str .= display_price($row['cp_sale_amt']). ' 할인';
	}
	$str .= "</div>";

	// 최대금액
	if($row['cp_low_amt'] > 0) {
		$str .= "<div>&#183; ".display_price($row['cp_low_amt'])." 이상 구매시</div>";
	}

	// 사용가능대상
	$str .= "<div>&#183; ".$gw_usepart[$row['cp_use_part']]."</div>";

	return $str;
}

// 상품 브랜드명 정보의 배열을 리턴
function get_brand_chk($br_name, $mb_id='')
{
	$sql_search  = " and ( br_user_yes = '0' ";
	if($mb_id) $sql_search .= " or (br_user_yes='1' and mb_id=TRIM('$mb_id')) ";
	$sql_search .= " ) ";

	$row = sql_fetch("select br_id from hi_room_brand where br_name=TRIM('$br_name') $sql_search " );
	if($row['br_id'])
		return $row['br_id'];
	else
		return '';
}

// 상품 가격정보의 배열을 리턴
function get_price($gs_id, $msg='<span>원</span>')
{
	global $member, $is_member;

	$gs = get_goods($gs_id, 'index_no, price_msg, buy_level, buy_only');

	$price = get_sale_price($gs_id);

	// 재고가 한정상태이고 재고가 없을때, 품절상태일때..
	if(is_soldout($gs['index_no'])) {
		$str = "<span class=\"soldout\">품절</span>";
	} else {
		if($gs['price_msg']) {
			$str = $gs['price_msg'];
		} else if($gs['buy_only'] == 1 && $member['grade'] > $gs['buy_level']) {
			$str = "";
		} else if($gs['buy_only'] == 0 && $member['grade'] > $gs['buy_level']) {
			if(!$is_member)
				$str = "<span class=\"memopen\">회원공개</span>";
			else
				$str = "<span class=\"mpr\">".number_format($price).$msg."</span>";
		} else {
			$str = "<span class=\"mpr\">".number_format($price).$msg."</span>";
		}
	}

	return $str;
}

//  상품 상세페이지 구매하기, 장바구니, 찜 버튼
function get_buy_button($msg, $gs_id)
{
	global $gs, $pt_id;

	$str = "";
	for($i=1; $i<=3; $i++) {
		switch($i){
			case '1':
				$sw_css = " wset";
				$sw_name = "구매하기";
				$sw_direct = "buy";
				break;
			case '2':
				$sw_css = " grey";
				$sw_name = "장바구니";
				$sw_direct = "cart";
				break;
			case '3':
				$sw_css = " bx-white";
				$sw_name = "찜하기";
				$sw_direct = "wish";
				break;
		}

		if($msg) {
			$str .= "<span><a href=\"javascript:alert('$msg');\" class=\"btn_large".$sw_css."\">".$sw_name."</a></span>";
		} else {
			if($sw_direct == "wish") {
				$str .= "<span><a href=\"javascript:item_wish(document.fbuyform);\" class=\"btn_large".$sw_css."\">".$sw_name."</a></span>";
			} else {
				$str .= "<span><a href=\"javascript:fbuyform_submit('".$sw_direct."');\" class=\"btn_large".$sw_css."\">".$sw_name."</a></span>";
			}
		}
	}

	return $str;
}

//  등록된 상품이미지 미리보기
function get_look_ahead($it_img, $it_img_del)
{
	if(!trim($it_img)) return;

	if(preg_match("/^(http[s]?:\/\/)/", $it_img) == true)
		$file_url = $it_img;
	else
		$file_url = TB_DATA_URL."/goods/".$it_img;

	$str  = "<a href='{$file_url}' target='_blank' class='btn_small bx-white marr7'>미리보기</a> <label class='marr7'><input type='checkbox' name='{$it_img_del}' value='{$it_img}'>삭제</label>";

	return $str;
}

//  등록된 대표이미지 미리보기
function get_look_ahead_rep($it_img, $it_div , $it_img_del)
{
	if(!trim($it_img)) return;

	if(preg_match("/^(http[s]?:\/\/)/", $it_img) == true)
		$file_url = $it_img;
	else
		$file_url = TB_DATA_URL."/".$it_div."/".$it_img;

	$str  = "<a href='{$file_url}' target='_blank' class='btn_small bx-white marr7'>미리보기</a> <label class='marr7'><input type='checkbox' name='{$it_img_del}' value='{$it_img}'>삭제</label>";

	return $str;
}

function get_pagecode($code)
{
	$value_code	= is_array($code) ? $code : array($code);
	$value_code	= implode(",", $value_code);

	return $value_code;
}

// 쿠폰번호 생성함수
function get_coupon_id($reg_type='1')
{
    $len = 16;

	if($reg_type)
		$chars = "ABCDEFGHJKLMNPQRSTUVWXYZ123456789";
	else
		$chars = "1234567890";

    srand((double)microtime()*1000000);

    $i = 0;
    $str = '';

    while($i < $len) {
        $num = rand() % strlen($chars);
        $tmp = substr($chars, $num, 1);
        $str .= $tmp;
        $i++;
    }

    $str = preg_replace("/([0-9A-Z]{4})([0-9A-Z]{4})([0-9A-Z]{4})([0-9A-Z]{4})/", "\\1-\\2-\\3-\\4", $str);

    return $str;
}

// 적립금 (상품수정)
function get_gpoint($price, $marper, $point)
{
	if($marper){
		return round($price * $marper/100);
	} else {
		return conv_number($point);
	}
}

// 카테고리 페이지경로
function get_move($ca_id)
{
	$str = "";

	$len = strlen($ca_id);
	for($i=1;$i<=($len/3);$i++) {
		$cut_id = substr($ca_id,0,($i*3));
		$row = sql_fetch("select * from shop_category where catecode='$cut_id' ");

		$href = TB_SHOP_URL.'/list.php?ca_id='.$row['catecode'];

		$str = $str." <i class=\"ionicons ion-ios-arrow-right\"></i> "."<a href='{$href}'>{$row['catename']}</a>";
	}

	return $str;
}

// 분류를 navigation 형식으로 얻음
function adm_category_navi($ca_id)
{
	if(!$ca_id) return '';

	$str = '';
	for($i=1; $i<=(strlen($ca_id)/3); $i++) {
		$cut_id = substr($ca_id,0,($i*3));
		$row = sql_fetch("select catename from hi_category where catecode='$cut_id'");
		if($row['catename']) {
			$arr[] = $row['catename'];
		}
	}

	if(is_array($arr)) {
		$str = implode(" &gt; ", $arr);
	}

	return $str;
}

// 권한체크 후 링크호출
function get_admin($mb_id)
{
    if(!$mb_id) return;

    if(is_admin())
		return TB_ADMIN_URL.'/';
    if(is_partner($mb_id))
		return TB_MYPAGE_URL.'/page.php?code=partner_info';
    if(is_seller($mb_id))
		return TB_MYPAGE_URL.'/page.php?code=seller_main';

	return '';
}

// 카테고리를 SELECT 형식으로 얻음 (본사, 공급사 공통)
function get_goods_sca_select($name, $selected='', $event='')
{
	$str = "<select id=\"{$name}\" name=\"{$name}\"";
    if($event) $str .= " $event";
    $str .= ">\n";
	$str .= "<option value=''>선택</option>\n";

	$sql_common = " from hi_category ";
	$sql_order  = " order by caterank, catecode ";

	$r = sql_query("select * $sql_common where upcate='' $sql_order ");
	while($row=sql_fetch_array($r))	{
		$str .= "<option value='$row[catecode]'";
		if($row['catecode'] == $selected)
			$str .= " selected";
		$str .= ">[1]$row[catename]</option>\n";

		$r2 = sql_query("select * $sql_common where upcate='$row[catecode]' $sql_order ");
		while($row2=sql_fetch_array($r2)) {
			$len = strlen($row2['catecode']) / 3 - 1;
			$nbsp = "";
			for($i=0; $i<$len; $i++) {
				$nbsp .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			}
			$str .= "<option value='$row2[catecode]'";
			if($row2['catecode'] == $selected)
				$str .= " selected";
			$str .= ">{$nbsp}[2]$row2[catename]</option>\n";

			$r3 = sql_query("select * $sql_common where upcate='$row2[catecode]' $sql_order ");
			while($row3=sql_fetch_array($r3)){
				$len = strlen($row3['catecode']) / 3 - 1;
				$nbsp = "";
				for($i=0; $i<$len; $i++) {
					$nbsp .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				}
				$str .= "<option value='$row3[catecode]'";
				if($row3['catecode'] == $selected)
					$str .= " selected";
				$str .= ">{$nbsp}[3]$row3[catename]</option>\n";

				$r4 = sql_query("select * $sql_common where upcate='$row3[catecode]' $sql_order ");
				while($row4=sql_fetch_array($r4)){
					$len = strlen($row4['catecode']) / 3 - 1;
					$nbsp = "";
					for($i=0; $i<$len; $i++) {
						$nbsp .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					}
					$str .= "<option value='$row4[catecode]'";
					if($row4['catecode'] == $selected)
						$str .= " selected";
					$str .= ">{$nbsp}[4]$row4[catename]</option>\n";

					$r5 = sql_query("select * $sql_common where upcate='$row4[catecode]' $sql_order ");
					while($row5=sql_fetch_array($r5)){
						$len = strlen($row5['catecode']) / 3 - 1;
						$nbsp = "";
						for($i=0; $i<$len; $i++) {
							$nbsp .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						}
						$str .= "<option value='$row5[catecode]'";
						if($row5['catecode'] == $selected)
							$str .= " selected";
						$str .= ">{$nbsp}[5]$row5[catename]</option>\n";
					} //5
				} //4
			} //3
		} //2
	} //1
	$str .= "</select>\n";

	return $str;
}

// 회원권한을 SELECT 형식으로 얻음
function get_member_level_select($name, $start_id=0, $end_id=10, $selected='', $event='')
{
	global $board;

	$str  = "<select id=\"{$name}\" name=\"{$name}\"";
    if($event) $str .= " $event";
    $str .= ">\n";
	for($i=$start_id; $i<=$end_id; $i++)
	{
		$grade = get_grade($i);
		if($grade) {
			$str .= "<option value='$i'";
			if($i == $selected)
				$str .= " selected";
			$str .= ">$grade</option>\n";
		}
	}

	if($board[$name] == '99')
		$sel = " selected";
	$str .= "<option value='99'{$sel}>비회원</option>\n";
	$str .= "</select>\n";

	return $str;
}

// 회원권한을 SELECT 형식으로 얻음
function get_level_select($name, $start_id=1, $end_id=9, $selected='', $event='')
{
	$str  = "<select id=\"{$name}\" name=\"{$name}\"";
    if($event) $str .= " $event";
    $str .= ">\n";

	$sql= "select * from hi_member where (grade>='$start_id' and grade<='$end_id') order by name";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++)
	{
		$str .= "<option value='{$row['id']}'";
		if($row['id'] == $selected)
			$str .= " selected";
		$str .= ">{$row['name']} ({$row['id']})</option>\n";
	}

	$str .= "</select>\n";

	return $str;
}

// 회원권한을 SELECT 형식으로 얻음
function get_member_select($name, $selected='', $event='')
{
	$str  = "<select id=\"{$name}\" name=\"{$name}\"";
    if($event) $str .= " $event";
    $str .= ">\n";

	$sql= "select * from hi_member_grade where gb_name <> '' order by gb_no desc";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++)
	{
		$str .= "<option value='{$row['gb_no']}'";
		if($row['gb_no'] == $selected)
			$str .= " selected";
		$str .= ">[{$row['gb_no']}] {$row['gb_name']}</option>\n";
	}

	$str .= "</select>\n";

	return $str;
}

// 회원권한을 SELECT 형식으로 얻음
function get_goods_level_select($name, $selected='', $event='')
{
	$str  = "<select id=\"{$name}\" name=\"{$name}\"";
    if($event) $str .= " $event";
    $str .= ">\n";
	$str .= "<option value='10'>제한없음</option>\n";

	$sql= "select * from hi_member_grade where gb_name <> '' and gb_no > 1 order by gb_no desc ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++)
	{
		$str .= "<option value='{$row['gb_no']}'";
		if($row['gb_no'] == $selected)
			$str .= " selected";
		$str .= ">[{$row['gb_no']}] {$row['gb_name']}</option>\n";
	}

	$str .= "</select>\n";

	return $str;
}

// 날짜를 select 박스 형식으로 얻는다
function date_select($date, $name="", $date_y, $date_m, $date_d)
{
	$s = "";
	if(substr($date, 0, 4) == "0000") {
		$date = TB_TIME_YMDHIS;
	}
	preg_match("/([0-9]{4})-([0-9]{2})-([0-9]{2})/", $date, $m);

	// 년
	$s .= "<select name='{$name}_y'>";
	$s .= "<option value='0000'>선택";
	for($i=$m[0]-3; $i<=$m[0]+3; $i++) {
		$s .= "<option value='$i'";
		if($date_y == $i) {
			$s .= " selected";
		}
		$s .= ">$i";
	}
	$s .= "</select>년 \n";

	// 월
	$s .= "<select name='{$name}_m'>";
	$s .= "<option value='00'>선택";
	for($i=1; $i<=12; $i++) {
		$ms = sprintf('%02d',$i);
		$s .= "<option value='$ms'";
		if($date_m == $ms) {
			$s .= " selected";
		}
		$s .= ">$ms";
	}
	$s .= "</select>월 \n";

	// 일
	$s .= "<select name='{$name}_d'>";
	$s .= "<option value='00'>선택";
	for($i=1; $i<=31; $i++) {
		$ds = sprintf('%02d',$i);
		$s .= "<option value='$ds'";
		if($date_d == $ds) {
			$s .= " selected";
		}
		$s .= ">$ds";
	}
	$s .= "</select>일 \n";

	return $s;
}

// 입력 폼 안내문
function help($help="", $addclass='fc_125')
{
	$help = str_replace("\n", "<br>", $help);

	if($addclass == 1) {
		$str = '<span class="tooltip"><i class="fa fa-question-circle"></i><span class="tooltiptext">'.$help.'</span></span>';
	} else {
		$str = '<span class="frm_info';
		if($addclass) $str.= ' '.$addclass;
		$str.= '">'.$help.'</span>';
	}

    return $str;
}

// 계좌정보를 select 박스 형식으로 얻는다
function get_bank_account($name, $selected='')
{
	global $default;

	$str  = '<select id="'.$name.'" name="'.$name.'">'.PHP_EOL;
	$str .= '<option value="">선택하십시오</option>'.PHP_EOL;

	$bank = unserialize($default['de_bank_account']);
	for($i=0; $i<5; $i++) {
		$bank_account = $bank[$i]['name'].' '.$bank[$i]['account'].' '.$bank[$i]['holder'];
		if(trim($bank_account)) {
			$str .= option_selected($bank_account, $selected, $bank_account);
		}
	}
	$str .= '</select>'.PHP_EOL;

	return $str;
}

// 게시판 그룹을 SELECT 형식으로 얻음
function get_group_select($name, $selected='', $event='')
{
	$str  = "<select id=\"{$name}\" name=\"{$name}\"";
    if($event) $str .= " $event";
    $str .= ">\n";

	$sql = " select gr_id, gr_subject from shop_board_group order by gr_id desc ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++)
	{
		$str .= "<option value='{$row['gr_id']}'";
		if($row['gr_id'] == $selected) $str .= " selected";
		$str .= ">{$row['gr_subject']}</option>\n";
	}
	$str .= "</select>\n";

	return $str;
}

// 주문 진행상태를 select로 얻음
function get_change_select($name, $selected='', $event='')
{
	global $gw_status, $gw_array_status;

	// 취소,반품,교환,환불 건은 텍스트형식으로만 노출
	if(!in_array($selected, array(2,3,4,5))) {
		return $gw_status[$selected];
	}

	$str = "<select name=\"{$name}\"{$event}>\n";
	foreach($gw_array_status as $key=>$val) {
		if($key != $selected) continue;

		$str .= option_selected($key, $selected, $gw_status[$key]);
		foreach($val as $dan) {
			$str .= option_selected($dan, '', $gw_status[$dan]);
		}
	}
	$str .= "</select>";

	return $str;
}

// 상품 선택옵션
function get_item_options($gs_id, $subject)
{
	if(!$gs_id || !$subject)
		return '';

	$amt = get_sale_price($gs_id);

	$sql = " select * from hi_goods_option where io_type = '0' and gs_id = '$gs_id' and io_use = '1' order by io_no asc ";
	$result = sql_query($sql);
	if(!sql_num_rows($result))
		return '';

	$str = '';
	$subj = explode(',', $subject);
	$subj_count = count($subj);

	if($subj_count > 1) {
		$options = array();

		// 옵션항목 배열에 저장
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$opt_id = explode(chr(30), $row['io_id']);

			for($k=0; $k<$subj_count; $k++) {
				if(!is_array($options[$k]))
					$options[$k] = array();

				if($opt_id[$k] && !in_array($opt_id[$k], $options[$k]))
					$options[$k][] = $opt_id[$k];
			}
		}

		// 옵션선택목록 만들기
		for($i=0; $i<$subj_count; $i++) {
			$opt = $options[$i];
			$opt_count = count($opt);
			$disabled = '';
			if($opt_count) {
				$seq = $i + 1;
				if($i > 0)
					$disabled = ' disabled="disabled"';
				$str .= '<dl>'.PHP_EOL;
				$str .= '<dt><label for="it_option_'.$seq.'">'.$subj[$i].'</label></dt>'.PHP_EOL;

				$select  = '<select id="it_option_'.$seq.'" class="it_option wfull"'.$disabled.'>'.PHP_EOL;
				$select .= '<option value="">(필수) 선택하세요</option>'.PHP_EOL;
				for($k=0; $k<$opt_count; $k++) {
					$opt_val = $opt[$k];
					if($opt_val) {
						$select .= '<option value="'.$opt_val.'">'.$opt_val.'</option>'.PHP_EOL;
					}
				}
				$select .= '</select>'.PHP_EOL;

				$str .= '<dd class="li_select">'.$select.'</dd>'.PHP_EOL;
				$str .= '</dl>'.PHP_EOL;
			}
		}
	} else {
		$str .= '<dl>'.PHP_EOL;
		$str .= '<dt><label for="it_option_1">'.$subj[0].'</label></dt>'.PHP_EOL;

		$select  = '<select id="it_option_1" class="it_option wfull">'.PHP_EOL;
		$select .= '<option value="">(필수) 선택하세요</option>'.PHP_EOL;
		for($i=0; $row=sql_fetch_array($result); $i++) {
			if($row['io_price'] >= 0)
				$price = '&nbsp;&nbsp;(+'.display_price($row['io_price']).')';
			else
				$price = '&nbsp;&nbsp;('.display_price($row['io_price']).')';

			if(!$row['io_stock_qty'])
				$soldout = '&nbsp;&nbsp;[품절]';
			else
				$soldout = '';

			$select .= '<option value="'.$row['io_id'].','.$row['io_price'].','.$row['io_stock_qty'].','.$amt.'">'.$row['io_id'].$price.$soldout.'</option>'.PHP_EOL;
		}
		$select .= '</select>'.PHP_EOL;

		$str .= '<dd class="li_select">'.$select.'</dd>'.PHP_EOL;
		$str .= '</dl>'.PHP_EOL;
	}

	return $str;
}

// 상품 추가옵션
function get_item_supply($gs_id, $subject)
{
	if(!$gs_id || !$subject)
		return '';

	$sql = " select * from hi_goods_option where io_type = '1' and gs_id = '$gs_id' and io_use = '1' order by io_no asc ";
	$result = sql_query($sql);
	if(!sql_num_rows($result))
		return '';

	$str = '';

	$subj = explode(',', $subject);
	$subj_count = count($subj);
	$options = array();

	// 옵션항목 배열에 저장
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$opt_id = explode(chr(30), $row['io_id']);

		if($opt_id[0] && !array_key_exists($opt_id[0], $options))
			$options[$opt_id[0]] = array();

		if($opt_id[1]) {
			if($row['io_price'] >= 0)
				$price = '&nbsp;&nbsp;(+'.display_price($row['io_price']).')';
			else
				$price = '&nbsp;&nbsp;('.display_price($row['io_price']).')';
			$io_stock_qty = get_option_stock_qty($gs_id, $row['io_id'], $row['io_type']);

			if($io_stock_qty < 1)
				$soldout = '&nbsp;&nbsp;[품절]';
			else
				$soldout = '';

			$options[$opt_id[0]][] = '<option value="'.$opt_id[1].','.$row['io_price'].','.$io_stock_qty.',0">'.$opt_id[1].$price.$soldout.'</option>';
		}
	}

	// 옵션항목 만들기
	for($i=0; $i<$subj_count; $i++) {
		$opt = $options[$subj[$i]];
		$opt_count = count($opt);
		if($opt_count) {
			$seq = $i + 1;
			$str .= '<dl>'.PHP_EOL;
			$str .= '<dt><label for="it_supply_'.$seq.'">'.$subj[$i].'</label></dt>'.PHP_EOL;

			$select = '<select id="it_supply_'.$seq.'" class="it_supply wfull">'.PHP_EOL;
			$select .= '<option value="">선택안함</option>'.PHP_EOL;
			for($k=0; $k<$opt_count; $k++) {
				$opt_val = $opt[$k];
				if($opt_val) {
					$select .= $opt_val.PHP_EOL;
				}
			}
			$select .= '</select>'.PHP_EOL;

			$str .= '<dd class="li_select">'.$select.'</dd>'.PHP_EOL;
			$str .= '</dl>'.PHP_EOL;
		}
	}

	return $str;
}

// 주문완료 옵션호출
function print_complete_options($gs_id, $od_id, $xls='')
{
	$sql = " select io_id, ct_option, ct_qty, io_type, io_price
				from shop_cart where od_id = '$od_id' and gs_id = '$gs_id' order by io_type asc, index_no asc ";
	$result = sql_query($sql);

	$str = '';
	$comma = '';
	for($i=0; $row=sql_fetch_array($result); $i++) {
		if($i == 0 && !$xls)
			$str .= '<ul>'.PHP_EOL;

		if(!$row['io_id']) continue;

		$price_plus = '';
        if($row['io_price'] >= 0)
            $price_plus = '+';

		if(!$xls) {

			if($row['io_type'])
				$str .= "<li class='ny'>".$row['ct_option']." ".display_qty($row['ct_qty'])." (".$price_plus.display_price($row['io_price']).")</li>".PHP_EOL;
			else
				$str .= "<li class='ty'>".$row['ct_option']." ".display_qty($row['ct_qty'])." (".$price_plus.display_price($row['io_price']).")</li>".PHP_EOL;
		} else {

			$str .= $comma.$row['ct_option']." ".display_qty($row['ct_qty'])." (".$price_plus.display_price($row['io_price']).")".PHP_EOL;

			$str = trim($str);
			$comma = '|';
		}
	}

	if($i > 0 && !$xls)
		$str .= '</ul>';

	return $str;
}

// 장바구니 옵션호출
function print_item_options($gs_id, $set_cart_id)
{
	$sql = " select io_id, ct_option, ct_qty, io_type, io_price
				from shop_cart where gs_id = '$gs_id' and ct_direct='$set_cart_id' and ct_select='0' order by io_type asc, index_no asc ";
	$result = sql_query($sql);

	$str = '';
	for($i=0; $row=sql_fetch_array($result); $i++) {
		if($i == 0)
			$str .= '<ul>'.PHP_EOL;

		if(!$row['io_id']) continue;

        $price_plus = '';
        if($row['io_price'] >= 0)
            $price_plus = '+';

		// 추가상품일때
		if($row['io_type'])
			$str .= "<li class='ny'>".$row['ct_option']." ".display_qty($row['ct_qty'])." (".$price_plus.display_price($row['io_price']).")</li>".PHP_EOL;
		else
			$str .= "<li class='ty'>".$row['ct_option']." ".display_qty($row['ct_qty'])." (".$price_plus.display_price($row['io_price']).")</li>".PHP_EOL;
	}

	if($i > 0)
		$str .= '</ul>';

	return $str;
}

// 상품상세페이지 : 배송비 구함
function get_sendcost_amt()
{
	global $gs, $config, $sr;

	// 공통설정
	if($gs['sc_type']=='0') {
		if($gs['mb_id'] == 'admin') {
			$delivery_method  = $config['delivery_method'];
			$delivery_price   = $config['delivery_price'];
			$delivery_price2  = $config['delivery_price2'];
			$delivery_minimum = $config['delivery_minimum'];
		} else {
			$delivery_method  = $sr['delivery_method'];
			$delivery_price	  = $sr['delivery_price'];
			$delivery_price2  = $sr['delivery_price2'];
			$delivery_minimum = $sr['delivery_minimum'];
		}

		switch($delivery_method) {
			case '1':
				$str = "무료배송";
				break;
			case '2':
				$str = "상품수령시 결제(착불)";
				break;
			case '3':
				$str = display_price($delivery_price);
				break;
			case '4':
				$str = display_price($delivery_price2)."&nbsp;(".display_price($delivery_minimum)." 이상 구매시 무료)";
				break;
		}

		// sc_type(배송비 유형)   0:공통설정, 1:무료배송, 2:조건부무료배송, 3:유료배송
		// sc_method(배송비 결제) 0:선불, 1:착불, 2:사용자선택
		if(in_array($delivery_method, array('3','4'))) {
			if($gs['sc_method'] == 1)
				$str = '상품수령시 결제(착불)';
			else if($gs['sc_method'] == 2) {
				$str = "<select name=\"ct_send_cost\">
							<option value='0'>주문시 결제(선결제)</option>
							<option value='1'>상품수령시 결제(착불)</option>
						</select>";
			}
		}
	}

	// 무료배송
	else if($gs['sc_type']=='1') {
		$str = "무료배송";
	}

	// 조건부 무료배송
	else if($gs['sc_type']=='2') {
		$str = display_price($gs['sc_amt'])."&nbsp;(".display_price($gs['sc_minimum'])." 이상 구매시 무료)";
	}

	// 유료배송
	else if($gs['sc_type']=='3') {
		$str = display_price($gs['sc_amt']);
	}

	// sc_type(배송비 유형)		0:공통설정, 1:무료배송, 2:조건부 무료배송, 3:유료배송
	// sc_method(배송비 결제)	0:선불, 1:착불, 2:사용자선택
	if(in_array($gs['sc_type'], array('2','3'))) {
		if($gs['sc_method'] == 1)
			$str = '상품수령시 결제(착불)';
		else if($gs['sc_method'] == 2) {
			$str = "<select name=\"ct_send_cost\">
						<option value='0'>주문시 결제(선결제)</option>
						<option value='1'>상품수령시 결제(착불)</option>
					</select>";
		}
	}

	return $str;
}

// 배송비 구함
function get_sendcost_amt2($gs_id, $it_price)
{
	global $config;

	$gs = get_goods($gs_id);

    if(!$gs['index_no'])
        return 0;

	if($gs['use_aff'])
		$sr = get_partner($gs['mb_id']);
	else
		$sr = get_seller_cd($gs['mb_id']);

	// 공통설정
	if($gs['sc_type']=='0') {

		if($gs['mb_id'] == 'admin') {
			$delivery_method  = $config['delivery_method'];
			$delivery_price	  = $config['delivery_price'];
			$delivery_price2  = $config['delivery_price2'];
			$delivery_minimum = $config['delivery_minimum'];
		} else {
			$delivery_method  = $sr['delivery_method'];
			$delivery_price	  = $sr['delivery_price'];
			$delivery_price2  = $sr['delivery_price2'];
			$delivery_minimum = $sr['delivery_minimum'];
		}

		switch($delivery_method) {
			case '1':
			case '2':
				$sendcost = 0;
				break;
			case '3':
				$sendcost = (int)$delivery_price;
				break;
			case '4':
                if($it_price >= (int)$delivery_minimum)
                    $sendcost = 0;
                else
                    $sendcost = (int)$delivery_price2;
				break;
		}

		// sc_type(배송비 유형)		0:공통설정, 1:무료배송, 2:조건부무료배송, 3:유료배송
		// sc_method(배송비 결제)	0:선불, 1:착불, 2:사용자선택
		if(in_array($delivery_method, array('3','4'))) {
			if($gs['sc_method'] == 1) {
				$sendcost = 0;
			}
		}
	}

	// 무료배송
	else if($gs['sc_type']=='1') {
		$sendcost = 0;
	}

	// 조건부 무료배송
	else if($gs['sc_type']=='2') {
		if($it_price >= (int)$gs['sc_minimum'])
			$sendcost = 0;
		else
			$sendcost = (int)$gs['sc_amt'];
	}

	// 유료배송
	else if($gs['sc_type']=='3') {
		$sendcost = (int)$gs['sc_amt'];
	}

	// sc_type(배송비 유형)   0:공통설정, 1:무료배송, 2:조건부 무료배송, 3:유료배송
	// sc_method(배송비 결제) 0:선불, 1:착불, 2:사용자선택
	if(in_array($gs['sc_type'], array('2','3'))) {
		if($gs['sc_method'] == 1) {
			$sendcost = 0;
		}
	}

	return $sendcost;
}

// 카테고리번호 생성
function get_ca_depth($tablename, $upcate)
{
	$sql_fld = " MAX(catecode) as max_caid ";

	$ca = sql_fetch("select {$sql_fld} from {$tablename} where upcate = '$upcate' ");
	$max_caid = $ca['max_caid'] + 1;

	if(strlen($max_caid)%3 == 1) {
		$new_code = '00'.$max_caid;
	} else if(strlen($max_caid)%3 == 2) {
		$new_code = '0'.$max_caid;
	} else {
		$new_code = $max_caid;
	}

	$new_code = substr($new_code,-3);
	$new_code = $upcate.$new_code;

	return $new_code;
}

// 분류별 상단배너
function get_category_head_image($ca_id)
{
	$cgy = array();

	$sql = "select * from shop_category where catecode = '".substr($ca_id,0,3)."' limit 1 ";
	$row = sql_fetch($sql);

	$file = TB_DATA_PATH.'/category/'.$row['headimg'];
	if(is_file($file) && $row['headimg']) {
		if($row['headimgurl']) {
			$a1 = '<a href="'.$row['headimgurl'].'">';
			$a2 = '</a>';
		}

		$file = rpc($file, TB_PATH, TB_URL);
		$cgy['headimg'] = $a1.'<img src="'.$file.'">'.$a2;
	}

	return $cgy;
}

// 포인트존 분류 select 형식으로 얻음
function get_pointZone_select($name, $selected, $event='')
{
	$ca_id1 = substr($selected,0,3);

	$str = "<select id=\"{$name}\" name=\"{$name}\"{$event}>\n";
	$str.= "<option value=\"\">=카테고리선택=</option>\n";

	$sql = "select goodsTypeDtlNm from gift_goods where goodsStateCd = 'SALE' group by goodsTypeDtlNm order by goodsTypeDtlNm ";
	$res = sql_query($sql);
	while($row=sql_fetch_array($res)) {
		$str.= option_selected($row['goodsTypeDtlNm'], $ca_id1, $row['goodsTypeDtlNm']);
	}

	$str.= "</select>\n";

	return $str;
}

// 1차분류를 select 형식으로 얻음
function get_category_select_1($name, $selected, $event='')
{
	$ca_id1 = substr($selected,0,3);

	$str = "<select id=\"{$name}\" name=\"{$name}\"{$event}>\n";
	$str.= "<option value=\"\">=카테고리선택=</option>\n";

	$sql = "select * from hi_category where length(catecode)='3' order by caterank, catecode";
	$res = sql_query($sql);
	while($row=sql_fetch_array($res)) {
		$str.= option_selected($row['catecode'], $ca_id1, $row['catename']);
	}

	$str.= "</select>\n";

	return $str;
}

// 2차분류를 select 형식으로 얻음
function get_category_select_2($name, $selected, $event='')
{
	$ca_id1 = substr($selected,0,3);
	$ca_id2 = substr($selected,0,6);

	$str = "<select id=\"{$name}\" name=\"{$name}\"{$event}>\n";
	$str.= "<option value=\"\">=카테고리선택=</option>\n";

	if($ca_id1) {
		$sql = "select * from hi_category where length(catecode)='6' and upcate='$ca_id1' order by caterank, catecode";
		$res = sql_query($sql);
		while($row=sql_fetch_array($res)) {
			$str.= option_selected($row['catecode'], $ca_id2, $row['catename']);
		}
	}

	$str.= "</select>\n";

	return $str;
}

// 3차분류를 select 형식으로 얻음
function get_category_select_3($name, $selected, $event='')
{
	$ca_id2 = substr($selected,0,6);
	$ca_id3 = substr($selected,0,9);

	$str = "<select id=\"{$name}\" name=\"{$name}\"{$event}>\n";
	$str.= "<option value=\"\">=카테고리선택=</option>\n";

	if($ca_id2) {
		$sql = "select * from hi_category where length(catecode)='9' and upcate='$ca_id2' order by caterank, catecode";
		$res = sql_query($sql);
		while($row=sql_fetch_array($res)) {
			$str.= option_selected($row['catecode'], $ca_id3, $row['catename']);
		}
	}

	$str.= "</select>\n";

	return $str;
}

// 4차분류를 select 형식으로 얻음
function get_category_select_4($name, $selected, $event='')
{
	$ca_id3 = substr($selected,0,9);
	$ca_id4 = substr($selected,0,12);

	$str = "<select id=\"{$name}\" name=\"{$name}\"{$event}>\n";
	$str.= "<option value=\"\">=카테고리선택=</option>\n";

	if($ca_id3) {
		$sql = "select * from hi_category where length(catecode)='12' and upcate='$ca_id3' order by caterank, catecode";
		$res = sql_query($sql);
		while($row=sql_fetch_array($res)) {
			$str.= option_selected($row['catecode'], $ca_id4, $row['catename']);
		}
	}

	$str.= "</select>\n";

	return $str;
}

// 5차분류를 select 형식으로 얻음
function get_category_select_5($name, $selected, $event='')
{
	$ca_id4 = substr($selected,0,12);
	$ca_id5 = substr($selected,0,15);

	$str = "<select id=\"{$name}\" name=\"{$name}\"{$event}>\n";
	$str.= "<option value=\"\">=카테고리선택=</option>\n";

	if($ca_id4) {
		$sql = "select * from hi_category where length(catecode)='15' and upcate='$ca_id4' order by caterank, catecode";
		$res = sql_query($sql);
		while($row=sql_fetch_array($res)) {
			$str.= option_selected($row['catecode'], $ca_id5, $row['catename']);
		}
	}

	$str.= "</select>\n";

	return $str;
}
// Leon CEO
// =============== 차종카테고리 Start ===============//
function get_car_category_select_1($name, $selected, $event='')
{
	$ca_id1 = $selected;

	$str = "<select id=\"{$name}\" name=\"{$name}\"{$event}>\n";
	$str.= "<option value=\"\">=카테고리선택=</option>\n";

	$sql = "select * from hi_car_maker where use_yn ='Y' order by index_no";
	$res = sql_query($sql);
	while($row=sql_fetch_array($res)) {
		$str.= option_selected($row['index_no'], $ca_id1, $row['maker_nm']);
	}

	$str.= "</select>\n";

	return $str;
}

// 2차분류를 select 형식으로 얻음
function get_car_category_select_2($name, $ca_id1, $selected, $event='')
{
	//$ca_id1 = substr($selected,0,3);
	$ca_id2 = $selected;

	$str = "<select id=\"{$name}\" name=\"{$name}\"{$event}>\n";
	$str.= "<option value=\"\">=카테고리선택=</option>\n";

		if($ca_id1){
				$sql = " select * from hi_car_model where  maker_code = {$ca_id1} and use_yn ='Y' order by index_no";
		}else{
			$sql = " select * from hi_car_model where  use_yn ='Y' order by index_no";
		}

		$res = sql_query($sql);
		while($row=sql_fetch_array($res)) {
			$str.= option_selected($row['index_no'], $ca_id2, $row['model_nm']);

	}

	$str.= "</select>\n";

	return $str;
}

// 3차분류를 select 형식으로 얻음
function get_car_category_select_3($name,$ca_id1,$ca_id2, $selected, $event='')
{

	$ca_id3 = $selected;

	$str = "<select id=\"{$name}\" name=\"{$name}\"{$event}>\n";
	$str.= "<option value=\"\">=카테고리선택=</option>\n";

	if($ca_id2) {
		$sql = "select * from hi_car_detail where maker_code ={$ca_id1} and model_code = {$ca_id2}  and use_yn = 'Y' order by index_no";
		$res = sql_query($sql);
		while($row=sql_fetch_array($res)) {
			$str.= option_selected($row['index_no'], $ca_id3, $row['detail_nm']);
		}
	}

	$str.= "</select>\n";

	return $str;
}

// 4차분류를 select 형식으로 얻음
function get_car_category_select_4($name, $selected, $event='')
{
	$ca_id3 = substr($selected,0,9);
	$ca_id4 = substr($selected,0,12);

	$str = "<select id=\"{$name}\" name=\"{$name}\"{$event}>\n";
	$str.= "<option value=\"\">=카테고리선택=</option>\n";

	if($ca_id3) {
		$sql = "select * from hi_category where length(catecode)='12' and upcate='$ca_id3' order by caterank, catecode";
		$res = sql_query($sql);
		while($row=sql_fetch_array($res)) {
			$str.= option_selected($row['catecode'], $ca_id4, $row['catename']);
		}
	}

	$str.= "</select>\n";

	return $str;
}
// =============== 차종카테고리 End   ===============//
// 날짜검색
function get_search_date($fr_date, $to_date, $fr_val, $to_val, $is_last=true)
{
	$input_end = ' class="frm_input w80" maxlength="10">'.PHP_EOL;
	$js = " onclick=\"search_date('{$fr_date}','{$to_date}',this.value);\"";

	$frm = array();
	$frm[] = '<label for="'.$fr_date.'" class="sound_only">시작일</label>'.PHP_EOL;
	$frm[] = '<input type="text" name="'.$fr_date.'" value="'.$fr_val.'" id="'.$fr_date.'"'.$input_end;
	$frm[] = ' ~ '.PHP_EOL;
	$frm[] = '<label for="'.$to_date.'" class="sound_only">종료일</label>'.PHP_EOL;
	$frm[] = '<input type="text" name="'.$to_date.'" value="'.$to_val.'" id="'.$to_date.'"'.$input_end;
	$frm[] = '<span class="btn_group">';
	$frm[] = '<input type="button"'.$js.' class="btn_small white" value="오늘">';
	$frm[] = '<input type="button"'.$js.' class="btn_small white" value="어제">';
	$frm[] = '<input type="button"'.$js.' class="btn_small white" value="일주일">';
	$frm[] = '<input type="button"'.$js.' class="btn_small white" value="지난달">';
	$frm[] = '<input type="button"'.$js.' class="btn_small white" value="1개월">';
	$frm[] = '<input type="button"'.$js.' class="btn_small white" value="3개월">';
	if($is_last) $frm[] = '<input type="button"'.$js.' class="btn_small white" value="전체">';
	$frm[] = '</span>';

	return implode('', $frm);
}

//모바일 날짜검색
function get_mobile_search_date($fr_date, $to_date, $fr_val, $to_val, $is_last=true)
{
	$input_end = ' class="frm_input w80" maxlength="10">'.PHP_EOL;
	$js = " onclick=\"search_date('{$fr_date}','{$to_date}',this.value);\"";

	$frm = array();
	$frm[] = '<div class="search_box">';
	$frm[] = '<label class="label_tit">기간검색</label>';
	$frm[] = '<div class="search_period_div">';
	$frm[] = '<span class="btn_group">';
	$frm[] = '<input type="button"'.$js.' class="bg_btn" id="today" value="오늘">';
	$frm[] = '<input type="button"'.$js.' class="bg_btn" id="week" value="일주일">';
	$frm[] = '<input type="button"'.$js.' class="bg_btn" id="1month" value="1개월">';
	$frm[] = '<input type="button"'.$js.' class="bg_btn" id="3month" value="3개월">';
	if($is_last) $frm[] = '<input type="button"'.$js.' class="bg_btn" id="all" value="전체">';
	$frm[] = '<input type="button"'.$js.' class="bg_btn" id="select" value="직접지정" /> ';
	$frm[] = '</span>';
	$frm[] = '</div>';
	$frm[] = '</div>';
	$frm[] = '<div class="select_date auto_side">';
	$frm[] = '<div>';
	$frm[] = '<h5>시작일</h5>';
	$frm[] = '<input type="date" name="'.$fr_date.'" value="'.$fr_val.'" id="'.$fr_date.'" />';
	$frm[] = '</div>';
	$frm[] = ' ~ '.PHP_EOL;
	$frm[] = '<div>';
	$frm[] = '<h5>종료일</h5>';
	$frm[] = '<input type="date" name="'.$to_date.'" value="'.$to_val.'" id="'.$to_date.'" />';
	$frm[] = '</div>';
	$frm[] = '</div>';


	return implode('', $frm);
}

// 카테고리정보 불러오기
function get_cgy_info($gs)
{
	$str = "";
	$cnt = -1;

	if($ca_id = adm_category_navi($gs['ca_id'])) {
		$str = '<span class="fsitem">'.$ca_id.'</span>';
		$cnt++;
	}
	if(adm_category_navi($gs['ca_id2'])) $cnt++;
	if(adm_category_navi($gs['ca_id3'])) $cnt++;

	if($cnt > 0) $str .= ' 외 '.$cnt.'건';

	return $str;
}
/*
function get_seller_name($mb_id)
{
	global $config;

	$sellerName = '';

	if(substr($mb_id,0,3) == 'AP-') {
		$row = sql_fetch("select company_name from shop_seller where seller_code = '$mb_id'");
		$sellerName = $row['company_name'];
	} else if($mb_id == 'admin') {
		$sellerName = $config['company_name'];
	} else if($mb_id != 'admin') {
		$row = sql_fetch("select company_name from shop_partner where mb_id = '$mb_id'");
		$sellerName = $row['company_name'];
	}

	return $sellerName;
}
*/

function get_seller_name($mb_id, $tb_name)
{
	global $config;

	$sellerName = '';

		$row = sql_fetch("select company_name from $tb_name where mb_id = '$mb_id'");
		$sellerName = $row['company_name'];

	return $sellerName;
}

function get_seller_item($mb_id, $tb_name)
{
	global $config;

	$sellerItem = '';
	$sql = 	" select seller_item from $tb_name where mb_id = '$mb_id'";

		$row = sql_fetch($sql);
		$sellerName = $row['seller_item'];

	return $sellerName;
}



function get_company_name($mb_id)
{
	global $config;

	$sellerName = '';

	if(substr($mb_id,0,3) == 'AP-') {
		$row = sql_fetch("select company_name from hi_room where seller_code = '$mb_id'");
		$sellerName = $row['company_name'];
	} else if($mb_id == 'admin') {
		$sellerName = $config['company_name'];
	} else if($mb_id != 'admin') {
		$row = sql_fetch("select company_name from hi_room where mb_id = '$mb_id'");
		$sellerName = $row['company_name'];
	}

	return $sellerName;
}

// input vars 체크
function check_input_vars()
{
    $max_input_vars = ini_get('max_input_vars');

    if($max_input_vars) {
        $post_vars = count($_POST, COUNT_RECURSIVE);
        $get_vars = count($_GET, COUNT_RECURSIVE);
        $cookie_vars = count($_COOKIE, COUNT_RECURSIVE);

        $input_vars = $post_vars + $get_vars + $cookie_vars;

        if($input_vars > $max_input_vars) {
            alert('폼에서 전송된 변수의 개수가 max_input_vars 값보다 큽니다.\\n전송된 값중 일부는 유실되어 DB에 기록될 수 있습니다.\\n\\n문제를 해결하기 위해서는 서버 php.ini의 max_input_vars 값을 변경하십시오.');
        }
    }
}

// 문자열 암복호화
class str_encrypt
{
    var $salt;
    var $lenght;

    function __construct($salt='')
    {
        if(!$salt)
            $this->salt = md5(preg_replace('/[^0-9A-Za-z]/', substr(TB_MYSQL_USER, -1), TB_MYSQL_PASSWORD));
        else
            $this->salt = $salt;

        $this->length = strlen($this->salt);
    }

    function encrypt($str)
    {
        $length = strlen($str);
        $result = '';

        for($i=0; $i<$length; $i++) {
            $char    = substr($str, $i, 1);
            $keychar = substr($this->salt, ($i % $this->length) - 1, 1);
            $char    = chr(ord($char) + ord($keychar));
            $result .= $char;
        }

        return base64_encode($result);
    }

    function decrypt($str) {
        $result = '';
        $str    = base64_decode($str);
        $length = strlen($str);

        for($i=0; $i<$length; $i++) {
            $char    = substr($str, $i, 1);
            $keychar = substr($this->salt, ($i % $this->length) - 1, 1);
            $char    = chr(ord($char) - ord($keychar));
            $result .= $char;
        }

        return $result;
    }
}


/*************************************************************************
**
**  쇼핑몰 배너관련 함수 모음
**
*************************************************************************/

// 배너 자체만 리턴
function display_banner($code, $mb_id)
{
	$str = "";

	$sql = sql_banner($code, $mb_id);
	$row = sql_fetch($sql);

	$file = TB_DATA_PATH.'/banner/'.$row['bn_file'];
	if(is_file($file) && $row['bn_file']) {
		if($row['bn_link']) {
			$a1 = "<a href=\"{$row['bn_link']}\" target=\"{$row['bn_target']}\">";
			$a2 = "</a>";
		}

		$row['bn_bg'] = preg_replace("/([^a-zA-Z0-9])/", "", $row['bn_bg']);
		if($row['bn_bg']) {
			$bg1 = "<p style=\"background-color:#{$row['bn_bg']};\">";
			$bg2 = "</p>";
		}

		$file = rpc($file, TB_PATH, TB_URL);
		$str = "{$bg1}{$a1}<img src=\"{$file}\" width=\"{$row['bn_width']}\" height=\"{$row['bn_height']}\">{$a2}{$bg2}";
	}

	return $str;
}

// 배너 bg
function display_banner_bg($code, $mb_id)
{
	$str = "";

	$sql = sql_banner($code, $mb_id);
	$row = sql_fetch($sql);

	$file = TB_DATA_PATH.'/banner/'.$row['bn_file'];
	if(is_file($file) && $row['bn_file']) {
		if($row['bn_link']) {
			$a1 = "<a href=\"{$row['bn_link']}\" target=\"{$row['bn_target']}\">";
			$a2 = "</a>";
		}

		$row['bn_bg'] = preg_replace("/([^a-zA-Z0-9])/", "", $row['bn_bg']);
		if($row['bn_bg']) $bg = "#{$row['bn_bg']} ";

		$file = rpc($file, TB_PATH, TB_URL);
		$str = "<p style=\"background:{$bg}url({$file}) no-repeat center;height:{$row['bn_height']}px;\">{$a1}{$a2}</p>";
	}

	return $str;
}

// 배너 (동일한 배너코드가 부여될경우 세로로 계속하여 출력)
function display_banner_rows($code, $mb_id)
{
	$str = "";

	$sql = sql_banner_rows($code, $mb_id);
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++)
	{
		$a1 = $a2 = $bg = '';

		$file = TB_DATA_PATH.'/banner/'.$row['bn_file'];
		if(is_file($file) && $row['bn_file']) {
			if($row['bn_link']) {
				$a1 = "<a href=\"{$row['bn_link']}\" target=\"{$row['bn_target']}\">";
				$a2 = "</a>";
			}

			$row['bn_bg'] = preg_replace("/([^a-zA-Z0-9])/", "", $row['bn_bg']);
			if($row['bn_bg']) $bg = " style=\"background-color:#{$row['bn_bg']};\"";

			$file = rpc($file, TB_PATH, TB_URL);
			$str .= "<li{$bg}>{$a1}<img src=\"{$file}\" width=\"{$row['bn_width']}\" height=\"{$row['bn_height']}\">{$a2}</li>\n";
		}
	}

	if($i > 0)
		$str = "<ul>\n{$str}</ul>\n";

	return $str;
}

// 이미지 배경고정 텍스트 입력 배너
function mask_banner($code, $mb_id)
{
	$str = "";

	$sql = sql_banner($code, $mb_id);
	$row = sql_fetch($sql);

	$file = TB_DATA_PATH.'/banner/'.$row['bn_file'];
	if(is_file($file) && $row['bn_file']) {
		if($row['bn_link']) {
			$a1 = "<a href=\"{$row['bn_link']}\" target=\"{$row['bn_target']}\">";
			$a2 = "</a>";
		}

		$file = rpc($file, TB_PATH, TB_URL);
		$str = "<div class=\"mask_bn\" style=\"background:url('{$file}') no-repeat fixed center;background-size:cover;\">{$a1}<p><span>{$row['bn_text']}.</span></p>{$a2}</div>";
	}

	return $str;
}
?>
