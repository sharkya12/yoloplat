<?php
include_once("./_common.php");

check_demo();

check_admin_token();

// input vars 체크
check_input_vars();

$upl_dir = TB_DATA_PATH."/goods";
$upl = new upload_files($upl_dir);

if($_POST['gname'] == "") {
	alert("상품명을 입력하세요.");
}


// 관련상품을 우선 삭제함
sql_query(" delete from hi_goods_relation where gs_id = '$gs_id' ");

// 관련상품의 반대도 삭제
sql_query(" delete from hi_goods_relation where gs_id2 = '$gs_id' ");

// 관련상품 등록
$gs_id2 = explode(",", $gs_list);
for($i=0; $i<count($gs_id2); $i++)
{
	if(trim($gs_id2[$i]))
	{
		$sql = " insert into hi_goods_relation
					set gs_id  = '$gs_id',
						gs_id2 = '$gs_id2[$i]',
						ir_no = '$i' ";
		sql_query($sql, false);

		// 관련상품의 반대로도 등록
		$sql = " insert into hi_goods_relation
					set gs_id  = '$gs_id2[$i]',
						gs_id2 = '$gs_id',
						ir_no = '$i' ";
		sql_query($sql, false);
	}
}

// 기존 선택옵션삭제
sql_query(" delete from hi_goods_option where io_type = '0' and gs_id = '$gs_id' ");

$option_count = count($_POST['opt_id']);
if($option_count) {
    // 옵션명
    $opt1_cnt = $opt2_cnt = $opt3_cnt = 0;
    for($i=0; $i<$option_count; $i++) {
        $opt_val = explode(chr(30), $_POST['opt_id'][$i]);
        if($opt_val[0])
            $opt1_cnt++;
        if($opt_val[1])
            $opt2_cnt++;
        if($opt_val[2])
            $opt3_cnt++;
    }

    if($opt1_subject && $opt1_cnt) {
        $it_option_subject = $opt1_subject;
        if($opt2_subject && $opt2_cnt)
            $it_option_subject .= ','.$opt2_subject;
        if($opt3_subject && $opt3_cnt)
            $it_option_subject .= ','.$opt3_subject;
    }
}

// 기존 추가옵션삭제
sql_query(" delete from hi_goods_option where io_type = '1' and gs_id = '$gs_id' ");

$supply_count = count($_POST['spl_id']);
if($supply_count) {
    // 추가옵션명
    $arr_spl = array();
    for($i=0; $i<$supply_count; $i++) {
        $spl_val = explode(chr(30), $_POST['spl_id'][$i]);
        if(!in_array($spl_val[0], $arr_spl))
            $arr_spl[] = $spl_val[0];
    }

    $it_supply_subject = implode(',', $arr_spl);
}

// 상품 정보제공
$value_array = array();
for($i=0; $i<count($_POST['ii_article']); $i++) {
    $key = $_POST['ii_article'][$i];
    $val = $_POST['ii_value'][$i];
    $value_array[$key] = $val;
}
$it_info_value = addslashes(serialize($value_array));

unset($value);
if($_POST['simg_type']) { // URL 입력
	$value['simg1'] = $_POST['simg1'];
	$value['simg2'] = $_POST['simg2'];
	$value['simg3'] = $_POST['simg3'];
	$value['simg4'] = $_POST['simg4'];
	$value['simg5'] = $_POST['simg5'];
	$value['simg6'] = $_POST['simg6'];
} else {
	for($i=1; $i<=6; $i++) {
		if($img = $_FILES['simg'.$i]['name']) {
			if(!preg_match("/\.(gif|jpg|png)$/i", $img)) {
				alert("이미지가 gif, jpg, png 파일이 아닙니다.");
			}
		}
		if($_POST['simg'.$i.'_del']) {
			$upl->del($_POST['simg'.$i.'_del']);
			$value['simg'.$i] = '';
		}
		if($_FILES['simg'.$i]['name']) {
			$value['simg'.$i] = $upl->upload($_FILES['simg'.$i]);
		}
	}
}

$value['goods_ca']		= "C"; //차량상품
$value['car_id']			= $_POST['car_id']; //대표카테고리
$value['car_id2']		= $_POST['car_id2']; //추가카테고리2
$value['car_id3']		= $_POST['car_id3']; //추가카테고리3
$value['car_id4']		= $_POST['car_id4']; //추가카테고리4
$value['car_number']			= $_POST['car_number']; //차량번호
$value['mb_id']			= $_POST['mb_id']; //업체코드
$value['gname']			= $_POST['gname']; //상품명
$value['isopen']		= $_POST['isopen']; //진열상태
$value['explan']		= $_POST['explan']; //짧은설명
$value['keywords']		= $_POST['keywords']; //키워드
$value['admin_memo']	= $_POST['admin_memo']; //관리자메모
$value['memo']			= $_POST['memo']; //상품설명
$value['standard_pplNum'] = $_POST['standard_pplNum']; //기준인원수
$value['max_pplNum'] = $_POST['max_pplNum']; //최대인원수
$value['goods_price']	= conv_number($_POST['goods_price']); //판매가격
$value['supply_price']	= conv_number($_POST['supply_price']); //공급가격
$value['normal_price']	= conv_number($_POST['normal_price']); //시중가격
$value['gpoint']		= get_gpoint($value['goods_price'],$_POST['marper'],$_POST['gpoint']);
$value['maker']			= $_POST['maker']; //제조사
$value['origin']		= $_POST['origin']; //원산지
$value['model']			= $_POST['model']; //모델명
$value['opt_subject']	= $it_option_subject; //상품 선택옵션
$value['spl_subject']	= $it_supply_subject; //상품 추가옵션
$value['ppay_type']		= $_POST['ppay_type']; //수수료적용타입
$value['ppay_rate']		= $_POST['ppay_rate']; //수수료구분
$value['ppay_fee']		= is_array($_POST['ppay_fee'])?implode(chr(30), $_POST['ppay_fee']) : '';
$value['ppay_dan']		= $_POST['ppay_dan'];
$value['stock_qty']		= conv_number($_POST['stock_qty']); //재고수량
$value['noti_qty']		= conv_number($_POST['noti_qty']); //재고 통보수량
$value['brand_uid']		= $_POST['brand_uid']; //브랜드주키
$value['brand_nm']		= get_brand($_POST['brand_uid']); //브랜드명
$value['notax']			= $_POST['notax']; //과세구분
$value['zone']			= $_POST['zone']; //판매가능지역
$value['zone_msg']		= $_POST['zone_msg']; //판매가능지역 추가설명
$value['sc_type']		= $_POST['sc_type']; //배송비 유형	0:공통설정, 1:무료, 2:조건부 무료, 3:유료
$value['sc_method']		= $_POST['sc_method']; //배송비 결제	0:선불, 1:착불, 2:사용자선택
$value['sc_amt']		= conv_number($_POST['sc_amt']); //기본 배송비
$value['sc_minimum']	= conv_number($_POST['sc_minimum']);	//조건 배송비
$value['sc_each_use']	= $_POST['sc_each_use']; //묶음배송불가
$value['info_gubun']	= $_POST['info_gubun']; //상품정보제공 구분
$value['info_value']	= $it_info_value; //상품정보제공 값
$value['price_msg']		= $_POST['price_msg']; //가격 대체문구
$value['stock_mod']		= $_POST['stock_mod']; //수량형식
$value['odr_min']		= conv_number($_POST['odr_min']); //최소 주문한도
$value['odr_max']		= conv_number($_POST['odr_max']); //최대 주문한도
$value['buy_level']		= $_POST['buy_level']; //구매가능 레벨
$value['buy_only']		= $_POST['buy_only']; //가격공개 여부
$value['simg_type']		= $_POST['simg_type']; //이미지 등록방식
$value['sb_date']		= $_POST['sb_date']; //판매 시작일
$value['eb_date']		= $_POST['eb_date']; //판매 종료일
$value['ec_mall_pid']	= $_POST['ec_mall_pid']; //네이버쇼핑 상품ID
$value['update_time']	= TB_TIME_YMDHIS; //수정일시

//hi_goods_price 테이블에 추가
$value_type['type_name']		= $_POST['gname']; // 성수기 주중 금액

$value_type['peak_week_amt']	= conv_number($_POST['peak_week_amt']); // 성수기 주중 금액
$value_type['peak_fri_amt']		= conv_number($_POST['peak_fri_amt']); // 성수기 금요일 금액
$value_type['peak_sat_amt']		= conv_number($_POST['peak_sat_amt']); // 성수기 토요일 금액
$value_type['peak_sun_amt']		= conv_number($_POST['peak_sun_amt']); // 성수기 일요일 금액
$value_type['peak_holi_amt']		= conv_number($_POST['peak_holi_amt']); // 성수기 휴일 금액
$value_type['peak_add_pers_amt']		= conv_number($_POST['peak_add_pers_amt']); // 성수기 추가인원 금액

$value_type['mid_week_amt']		= conv_number($_POST['mid_week_amt']); // 준성수기 주중 금액
$value_type['mid_fri_amt']		= conv_number($_POST['mid_fri_amt']); // 준성수기 토요일 금액
$value_type['mid_sat_amt']		= conv_number($_POST['mid_sat_amt']); // 준성수기 금요일 금액
$value_type['mid_sun_amt']		= conv_number($_POST['mid_sun_amt']); // 준성수기 일요일 금액
$value_type['mid_holi_amt']		= conv_number($_POST['mid_holi_amt']); // 준성수기 휴일 금액
$value_type['mid_add_pers_amt']		= conv_number($_POST['mid_add_pers_amt']); // 준성수기 추가인원 금액

$value_type['off_week_amt']		= conv_number($_POST['off_week_amt']); // 비수기 주중 금액
$value_type['off_fri_amt']		= conv_number($_POST['off_fri_amt']); // 비수기 금요일 금액
$value_type['off_sat_amt']		= conv_number($_POST['off_sat_amt']); // 비수기 토요일 금액
$value_type['off_sun_amt']		= conv_number($_POST['off_sun_amt']); // 비수기 일요일 금액
$value_type['off_hoil_amt']		= conv_number($_POST['off_hoil_amt']); // 비수기 휴일 금액
$value_type['off_add_pers_amt']		= conv_number($_POST['off_add_pers_amt']); // 비수기 추가인원 금액

/*
//기존의 체크된 객실시설옵션 테이블에서 삭제
$mb_id = $_POST['mb_id'];
$gcode = $_POST['gcode'];
sql_query(" delete from hi_room_facilities where mb_id = '$mb_id' and gcode = '$gcode' ");

//hi_room_facilities (객실시설옵션) 테이블에 추가
$fac_ma_idx = $_POST['fac_idx']; //master index
$fac_gr_cd = $_POST['fac_gr_cd']; //시설그룹코드
$fac_ma_seq = $_POST['fac_ma_seq']; //master 시퀀스
$fac_name =  $_POST['fac_name'];

for($j=0; $j<count($_POST['room_facility']); $j++){

	$k = $room_facility[$j];

	$value_fac['mb_id'] = $_POST['mb_id']; //업체코드
	$value_fac['gcode'] = $_POST['gcode']; //숙박업소코드

	$value_fac['fac_idx'] = $fac_ma_idx[$k];
	$value_fac['fac_gr_cd'] = $fac_gr_cd[$k];
	$value_fac['fac_ma_seq'] = $fac_ma_seq[$k];
	$value_fac['fac_name'] = $fac_name[$k];

	$value_fac['use_yn'] = 'Y';

	insert("hi_room_facilities", $value_fac);
}

*/




//차량등록
if($w == "") {
	$value['gcode'] = $_POST['gcode']; //숙박코드
	$value['reg_time'] = TB_TIME_YMDHIS; //등록일시
	insert("hi_goods", $value);

	$gs_id = sql_insert_id();

	$value_type['mb_id'] = $_POST['mb_id']; //업체코드
	$value_type['gcode'] = $_POST['gcode']; //상품코드

	$sql_str = " select ifnull(MAX(type_seq),0)+1 as type_seq FROM hi_goods_price where mb_id = '{$_POST['mb_id']}'";
	$result_seq = sql_fetch($sql_str);
	$max_type_seq = $result_seq['type_seq'];

	$value_type['type_seq'] = $max_type_seq;
	$value_type['reg_date'] = TB_TIME_YMDHIS; //등록일시
	insert("hi_goods_price", $value_type);

}
//차량정보수정 시
else if($w == "u") {
	update("hi_goods", $value," where index_no = '$gs_id'");
	//update("hi_goods_price", $value_type," where index_no = '$gs_id'");
	update("hi_goods_price", $value_type," where mb_id = '{$_POST['mb_id']}' and gcode = '{$_POST['gcode']}'");
}

// 선택옵션등록
if($option_count) {
    $comma = '';
    $sql = " insert into hi_room_goods_option
                    ( `io_id`, `io_type`, `gs_id`, `io_supply_price`, `io_price`, `io_stock_qty`, `io_noti_qty`, `io_use` )
                VALUES ";
    for($i=0; $i<$option_count; $i++) {
        $sql .= $comma . " ( '{$_POST['opt_id'][$i]}', '0', '$gs_id', '{$_POST['opt_supply_price'][$i]}', '{$_POST['opt_price'][$i]}', '{$_POST['opt_stock_qty'][$i]}', '{$_POST['opt_noti_qty'][$i]}', '{$_POST['opt_use'][$i]}' )";
        $comma = ' , ';
    }
    sql_query($sql);
}

// 추가옵션등록
if($supply_count) {
    $comma = '';
    $sql = " insert into hi_room_goods_option
                    ( `io_id`, `io_type`, `gs_id`, `io_supply_price`, `io_price`, `io_stock_qty`, `io_noti_qty`, `io_use` )
                VALUES ";
    for($i=0; $i<$supply_count; $i++) {
        $sql .= $comma . " ( '{$_POST['spl_id'][$i]}', '1', '$gs_id', '{$_POST['spl_supply_price'][$i]}', '{$_POST['spl_price'][$i]}', '{$_POST['spl_stock_qty'][$i]}', '{$_POST['spl_noti_qty'][$i]}', '{$_POST['spl_use'][$i]}' )";
        $comma = ' , ';
    }
    sql_query($sql);
}

//페이지이동
goto_url(TB_ADMIN_URL."/rent.php?code=goods_list");
// if($w == "")
//     goto_url(TB_ADMIN_URL."/rent.php?code=goods_list&w=u&gs_id=$gs_id");
// else if($w == "u")
//     goto_url(TB_ADMIN_URL."/rent.php?code=goods_list&w=u&gs_id=$gs_id$q1&page=$page&bak=$bak");
?>
