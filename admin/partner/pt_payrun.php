<?php
if(!defined('_TUBEWEB_')) exit;

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

//소복이마켓 디비 회원 배열과 욜로 디비 회원 배열 선언
$array1 = [];
$array2 = [];

//페이징네이션 처리 변수
$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

//sql 쿼리 공통 변수
$sql_common = " from hi_partner_payrun as a  ";
$sql_search = " where  a.state = '0' ";

//소복이마켓 디비 회원 조회 -> ID값 가져오기
$pt_member_url = "https://sbokmall.com/api/member/get_partner_search.php?$sfl={$stx}&sst={$sst}";
$pt_mb_info = pay_fn_curl($pt_member_url);
$mb = json_decode($pt_mb_info, ture);

//소복이회원아이디 = $return_id
$mb_id_query = '';
$return_id = '';
$for_count = 0;
foreach($mb as $key=>$value) {
  //소복이회원 배열 담기
  $row = $value;
  array_push($array2,$mb[$for_count]);
  //소복이회원 조건에 맞는 id 값 넣기
  if($for_count == 0) {
    $return_id .= "'{$row['id']}'";
  } else {
    $return_id .= ", '{$row['id']}'";
  }

  $for_count++;
}

if($return_id != '') {
  $mb_id_query .= " and a.mb_id IN ({$return_id})";
} else {
  $mb_id_query .= " and 1=0 ";
}

$sql_search .= $mb_id_query;

if($fr_date && $to_date)
    $sql_search .= " and a.reg_time between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
else if($fr_date && !$to_date)
	$sql_search .= " and a.reg_time between '$fr_date 00:00:00' and '$fr_date 23:59:59' ";
else if(!$fr_date && $to_date)
	$sql_search .= " and a.reg_time between '$to_date 00:00:00' and '$to_date 23:59:59' ";

if(!$orderby) {
    $filed = "a.index_no";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = " order by $filed $sod ";


// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select a.index_no as index_key ,a.mb_id as id, a.reg_time as req_time
              ,b.balance AS balance ,a.balance AS wthdr_rqst ,a.paytax ,a.paynet
              ,a.bank_name ,a.bank_account ,a.bank_holder
			$sql_common
      left join hi_pay_current as b on a.mb_id = b.mb_id
			$sql_search
			$sql_order
			limit $from_record, $rows ";
$result = sql_query($sql);

//배열합치기
for ($i=0; $row=sql_fetch_array($result);  $i++) {
      array_push($array1,$row);
}

$f_array = left_join_arrays("id",  $array1,$array2);

// 출금요청액합계
$row2 = sql_fetch(" select SUM(a.balance) as sum_pay {$sql_common} {$sql_search} ");
$stotal_pay = (int)$row2['sum_pay'];

$btn_frmline = <<<EOF
<input type="submit" name="act_button" value="선택정산" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<input type="submit" name="act_button" value="선택삭제" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<a href="./partner/pt_payrunexcel.php?$q1" class="btn_lsmall bx-white"><i class="fa fa-file-excel-o"></i> 엑셀저장</a>
EOF;

include_once(TB_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>

<h2>기본검색</h2>
<form name="fsearch" id="fsearch" method="get">
<input type="hidden" name="code" value="<?php echo $code; ?>">
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w100">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">검색어</th>
		<td>
			<select name="sfl">
				<?php echo option_selected('id', $sfl, '아이디'); ?>
				<?php echo option_selected('name', $sfl, '회원명'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input" size="30">
		</td>
	</tr>
	<tr>
		<th scope="row">기간검색</th>
		<td>
			<?php echo get_search_date("fr_date", "to_date", $fr_date, $to_date); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">레벨검색</th>
		<td>
			<?php echo radio_checked('sst', $sst, '', '전체'); ?>
      <?php echo radio_checked('sst', $sst, '5', '매니저'); ?>
			<?php echo radio_checked('sst', $sst, '2', 'svip'); ?>
			<?php echo radio_checked('sst', $sst, '4', 'vip'); ?>
			<?php echo radio_checked('sst', $sst, '3', 'vvip'); ?>
			<!-- <?php echo get_search_level('sst', $sst, 2, 6); ?> -->
		</td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="검색" class="btn_medium">
	<input type="button" value="초기화" id="frmRest" class="btn_medium grey">
</div>
</form>

<form name="fpayrun" id="fpayrun" method="post" action="./partner/pt_payrunupdate.php" onsubmit="return fpayrun_submit(this);">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
	<strong class="ov_a">총 출금요청액 <?php echo number_format($stotal_pay); ?>원 </strong>
</div>
<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
	<table>
  	<colgroup>
  		<col class="w30">
  		<col class="w80">
  		<col class="w100">
  		<col class="w50">
  		<col class="w160">
  		<col class="w80">
  		<col class="w100">
  		<col class="w100">
  		<col class="w100">
  		<col class="w100">
  		<col class="w200">
  	</colgroup>
  	<thead>
    	<tr>
    		<th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
    		<th scope="col">회원명</th>
    		<th scope="col">아이디</th>
    		<th scope="col">레벨</th>
    		<th scope="col">신청일시</th>
    		<th scope="col">만료일</th>
    		<th scope="col" class="th_bg">현재잔액</th>
    		<th scope="col" class="th_bg">출금요청</th>
    		<th scope="col" class="th_bg">세액공제</th>
    		<th scope="col" class="th_bg">실수령액</th>
    		<th scope="col">회원입금계좌</th>
    	</tr>
  	</thead>
  	<?php

      $i = 0;
      foreach($f_array as $key=>$value) {
        $row = $value;

  		$expr = 'txt_expired';
  		$expire_date = '무제한';

  		// 관리비를 사용중인가?
  		if($config['pf_expire_use']) {
  			if($row['term_date'] < TB_TIME_YMD) {
  				$expr = 'txt_expired';
  				$expire_date = '만료'.substr(conv_number($row['term_date']), 2);
  			} else {
  				$expr = 'txt_active';
  				$expire_date = $row['term_date'];
  			}
  		}

  		if($i==0)
  			echo '<tbody class="list">'.PHP_EOL;

  		$bg = 'list'.($i%2);
  	?>
  	<tr class="<?php echo $bg; ?>">
  		<td>
  			<input type="hidden" name="index_no[<?php echo $i; ?>]" value="<?php echo $row['index_key']; ?>">
  			<input type="hidden" name="mb_id[<?php echo $i; ?>]" value="<?php echo $row['id']; ?>">
  			<label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $row['mb_id']; ?> 님</label>
  			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>" id="chk_<?php echo $i; ?>">
  		</td>
  		<td><?php echo get_sideview($row['mb_id'], $row['name']); ?></td>
  		<td><?php echo $row['id']; ?></td>
  		<td><?php echo $row['grade']; ?></td>
  		<td><?php echo $row['req_time']; ?></td>
  		<td class="<?php echo $expr; ?>"><?php echo $expire_date; ?></td>
  		<td><?php echo number_format($row['balance']); ?></td>
  		<td><?php echo number_format($row['wthdr_rqst']); ?></td>
  		<td class="fc_red"><?php echo number_format($row['paytax']); ?></td>
  		<td class="fc_00f"><?php echo number_format($row['paynet']); ?></td>
  		<td><?php echo print_partner_bank2($row['bank_name'], $row['bank_account'], $row['bank_holder']); ?></td>
  	</tr>
  	<?php
  		$i++;
  	}
  	if($i==0)
  		echo '<tbody><tr><td colspan="11" class="empty_table">자료가 없습니다.</td></tr>';
  	?>
  	</tbody>
	</table>
</div>
<div class="local_frm02">
	<?php echo $btn_frmline; ?>
</div>
</form>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="desc02">
			<p>ㆍ선택정산을 하실 경우 DB상에 데이터값만 변경되므로 실제 본사 은행계좌에서 인출되지는 않습니다.</p>
			<p>ㆍ엑셀저장 후 인터넷뱅킹을 통한 대량 이체가 가능하므로 이체 완료 후 선택정산 하시면 됩니다.</p>
			<!-- <p class="fc_red">ㆍ정산완료를 실수로 처리하셨다면 가맹점 수수료내역에서 "선택삭제"를 통한 복원이 가능합니다.</p> -->
		</div>
	</div>
</div>

<script>
function fpayrun_submit(f)
{
    if(!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택정산") {
        if(!confirm("선택한 자료를 정산하시겠습니까?")) {
            return false;
        }
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}

$(function(){
	// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
	$("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99" });
});
</script>
