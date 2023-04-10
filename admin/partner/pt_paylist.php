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
$sql_common = " from hi_partner_pay a1 ";
$sql_search = " where 1=1 ";

//처음화면 로딩시 날짜 기본값 넣어주기
if(!$token) {
  if(!$fr_date)
    $fr_date = date("Y-m-d", strtotime("-7 days"));
  if(!$to_date)
    $to_date = date("Y-m-d", strtotime("Now"));
}

//php 날짜처리
$this_year = date("Y");       //이번년
$this_month = date("m");      //이번달
$this_day = date("d");        //오늘
$this_firstday = date("Y-m-d", mktime(0, 0, 0, intval(date('m')), 1, intval(date('Y'))  ));     //이번달 첫일
$this_lastday = date("Y-m-d", mktime(0, 0, 0, intval(date('m'))+1, 0, intval(date('Y'))  ));    //이번달 말일
// $search_time1 = date("Y-m-d", strtotime($fr_date));     //기간검색 날짜
// $search_time2 = date("Y-m-d", strtotime($to_date));     //기간검색 날짜


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
  $mb_id_query .= " and a1.mb_id IN ({$return_id})";
} else {
  $mb_id_query .= " and 1=0 ";
}

$sql_search .= $mb_id_query;

$sql_searchdate = '';
if($fr_date && $to_date)
  $sql_search_date .= " and pp_datetime between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
else if($fr_date && !$to_date)
	$sql_search_date .= " and pp_datetime between '$fr_date 00:00:00' and '$fr_date 23:59:59' ";
else if(!$fr_date && $to_date)
	$sql_search_date .= " and pp_datetime between '$to_date 00:00:00' and '$to_date 23:59:59' ";

$sql_group = " group by mb_id ";

if(!$orderby) {
    $filed = "pp_id";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = " order by $filed $sod ";


// =================================================================================================
// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt from ( select mb_id $sql_common $sql_search $sql_search_date $sql_group ) as partner_pay ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

//검색결과 회원들의 총수수료잔액
$sql = " select SUM(balance) as stotal_pay from ( select a1.mb_id ,a2.balance $sql_common left join hi_pay_current a2 ON a1.mb_id = a2.mb_id $sql_search $sql_search_date $sql_group ) as partner_pay ";
$row = sql_fetch($sql);
$stotal_pay = $row['stotal_pay'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select a1.mb_id as id ,ifnull(a2.accumulated_cnt ,0) AS num_accumulated_pay ,ifnull(a3.unreserved_cnt ,0) AS num_unreserved_pay
                ,a4.unreserved_pay ,a5.dircet_pay ,a5.rel_pay ,a5.location_pay ,a5.hd_pay ,(a5.dircet_pay + a5.rel_pay + a5.location_pay + a5.hd_pay) AS total_fee
        $sql_common
        left join (select mb_id, count(*) as accumulated_cnt
				            from ( select mb_id
                            from hi_partner_pay
                            where 1=1
                              $sql_search_date
					                    and pp_yn = 'Y'
                          ) as partner_pay
                    $sql_group
	                ) as a2 on a1.mb_id = a2.mb_id
        left join ( select mb_id, COUNT(*) AS unreserved_cnt
					           from (select mb_id
                            from hi_partner_pay
							              where 1=1
                              $sql_search_date
                              and pp_yn = 'N'
                          ) AS parnter_pay
                     $sql_group
	                 ) AS a3 ON a1.mb_id = a3.mb_id
        left join ( select mb_id ,IFNULL(SUM(pp_pay) ,0) AS unreserved_pay
        				    from hi_partner_pay
    				        where 1=1
                      $sql_search_date
                      and pp_yn = 'N'
	                  $sql_group
                    ) AS a4 ON a1.mb_id = a4.mb_id
        left join (	select mb_id ,SUM(dircet_pay) AS dircet_pay
                                 ,SUM(rel_pay) AS rel_pay
                                 ,SUM(location_pay) AS location_pay
                                 ,SUM(hd_pay) AS hd_pay
                    from (select mb_id
                                ,ifnull(case pp_rel_action
      			                            when '직접가맹수수료' then ifnull(SUM(pp_pay),0)
      	                                END ,0) AS dircet_pay
      		                      ,ifnull(case pp_rel_action
      			                            when '추천수수료' then ifnull(SUM(pp_pay),0)
      	                                END ,0) AS rel_pay
                                ,ifnull(case pp_rel_action
      		                              when '지역수수료' then ifnull(SUM(pp_pay),0)
                                        END ,0) AS location_pay
                                ,ifnull(case pp_rel_action
                                        when '본사수수료' then ifnull(SUM(pp_pay),0)
                                        END ,0) AS hd_pay
                          from hi_partner_pay
      	                  where pp_rel_action IN('직접가맹수수료','추천수수료','지역수수료','본사수수료')
    	                    $sql_search_date
                          and pp_yn = 'Y'
                          $sql_group, pp_rel_action
                          )AS f
                      $sql_group
                    )a5 ON a1.mb_id = a5.mb_id
          $sql_search $sql_search_date group by a1.mb_id $sql_order limit $from_record, $rows "; //최종 검색 sql
$result = sql_query($sql);


//배열합치기
for ($i=0; $row=sql_fetch_array($result);  $i++) {
      array_push($array1,$row);
}

$f_array = left_join_arrays("id",  $array1,$array2);


// 이달의 적립금 합계
$row2 = sql_fetch(" select SUM(pp_pay) as sum_pay {$sql_common} where pp_datetime between '$this_firstday 00:00:00' and '$this_lastday 00:00:00' and pp_yn = 'Y' ");
$thismonth_accumulated_pay = (int)$row2['sum_pay'];
// 이달의 미적립금 합계
$row2 = sql_fetch(" select SUM(pp_pay) as sum_pay {$sql_common} where pp_datetime between '$this_firstday 00:00:00' and '$this_lastday 00:00:00' and pp_yn = 'N' ");
$thismonth_unreserved_pay = (int)$row2['sum_pay'];
// 총 적립금합계
$row2 = sql_fetch(" select SUM(pp_pay) as sum_pay {$sql_common} where pp_yn = 'Y' ");
$total_accumulated_pay = (int)$row2['sum_pay'];
// 총 미적립금합계
$row2 = sql_fetch(" select SUM(pp_pay) as sum_pay {$sql_common} where pp_yn = 'N' ");
$total_unreserved_pay = (int)$row2['sum_pay'];
//총 출금요청액합계
$row2 = sql_fetch(" select SUM(balance) as sum_balance from hi_partner_payrun ");
$total_wthdr_Rqst = (int)$row2['sum_balance'];
//총 미지급액 합계
$row2 = sql_fetch(" select SUM(paynet) as sum_paynet from hi_partner_payrun where state = 0 ");
$total_unpaid = (int)$row2['sum_paynet'];
//총 세액공제액합계, 총 지급액합계
$row2 = sql_fetch(" select SUM(paynet) as sum_paynet, SUM(paytax) as sum_paytax from hi_partner_payrun where state = 1 ");
$total_payments = (int)$row2['sum_paynet'];
$total_tax = (int)$row2['sum_paytax'];

//검색조건에 따른 검색결과!!
//총적립건수
$row2 = sql_fetch(" select count(*) as cnt from ( select mb_id $sql_common $sql_search $sql_search_date and pp_yn = 'Y' ) as partner_pay ");
$num_accumulated_pay = (int)$row2['cnt'];
//미적립건수
$row2 = sql_fetch(" select count(*) as cnt from ( select mb_id $sql_common $sql_search $sql_search_date and pp_yn = 'N' ) as partner_pay ");
$num_unreserved_pay = (int)$row2['cnt'];
//미적립금액
$row2 = sql_fetch(" select SUM(pp_pay) as sum_pay {$sql_common} $sql_search $sql_search_date and pp_yn = 'N' ");
$unreserved_pay = (int)$row2['sum_pay'];
//유형별수수료
//영업
$row2 = sql_fetch(" select SUM(pp_pay) as sum_pay {$sql_common} $sql_search $sql_search_date and pp_yn = 'Y' and pp_rel_action = '직접가맹수수료' ");
$sale_fee = (int)$row2['sum_pay'];
//추천
$row2 = sql_fetch(" select SUM(pp_pay) as sum_pay {$sql_common} $sql_search $sql_search_date and pp_yn = 'Y' and pp_rel_action = '추천수수료' ");
$recommend_fee = (int)$row2['sum_pay'];
//지역
$row2 = sql_fetch(" select SUM(pp_pay) as sum_pay {$sql_common} $sql_search $sql_search_date and pp_yn = 'Y' and pp_rel_action = '지역수수료' ");
$area_fee = (int)$row2['sum_pay'];
//본사
$row2 = sql_fetch(" select SUM(pp_pay) as sum_pay {$sql_common} $sql_search $sql_search_date and pp_yn = 'Y' and pp_rel_action = '본사수수료' ");
$hd_fee = (int)$row2['sum_pay'];
//적립수수료합계
$row2 = sql_fetch(" select SUM(pp_pay) as sum_pay {$sql_common} $sql_search $sql_search_date and pp_yn = 'Y' ");
$total_fee = (int)$row2['sum_pay'];
//누적수수료합계
//현재잔액

//총적립액

//총차감액


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
<br></br>

<div class="tbl_head02 mgbt">
  <h3><?php echo $this_year."-".$this_month."-".$this_day; ?> (현재시간 기준)</h3>
	<table id="partner_list" class="td_center blue_bg">
	<colgroup>
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col class="w100">
	</colgroup>
	<thead>
	<tr>
		<th scope="col">이달의 적립액<br />( <?php echo $this_year.". ".$this_month; ?> )</th>
    <th scope="col">이달의 미적립액<br />( <?php echo $this_year.". ".$this_month; ?> )</th>
    <th scope="col">총 적립액</th>
    <th scope="col">총 미적립액</th>
    <th scope="col">총 출금요청액</th>
		<th scope="col">총 미지급액</th>
    <th scope="col">총 세액공제</th>
    <th scope="col">총 지급액</th>
	</tr>
	</thead>
  <tr>
    <td><?php echo number_format($thismonth_accumulated_pay); ?>원</td>
    <td><?php echo number_format($thismonth_unreserved_pay); ?>원</td>
    <td><?php echo number_format($total_accumulated_pay); ?>원</td>
    <td><?php echo number_format($total_unreserved_pay); ?>원</td>
    <td><?php echo number_format($total_wthdr_Rqst); ?>원</td>
    <td><?php echo number_format($total_unpaid); ?>원</td>
    <td><?php echo number_format($total_payments); ?>원</td>
    <td><?php echo number_format($total_tax); ?>원</td>
  </tr>
	</table>
</div>



<div class="tbl_head02 mgbt">
  <?php if($fr_date || $to_date) { ?>
    <h3><?php echo $fr_date; ?> ~ <?php echo $to_date; ?></h3>
  <?php } else { ?>
    <h3>전체기간</h3>
  <?php } ?>
	<table id="partner_list" class="td_center">
	<colgroup>
		<col class="w60">
		<col class="w80">
		<col class="w80">
		<col class="w100">
		<col class="w90">
		<col class="w90">
		<col class="w90">
    <col class="w90">
		<col class="w120">
		<!-- <col class="w100">
		<col class="w100">
		<col class="w100"> -->
	</colgroup>
	<thead>
	<tr>
		<th scope="col" rowspan="2">조회인원</th>
		<th scope="col" rowspan="2">총적립건수</th>
		<th scope="col" rowspan="2">미적립건수</th>
    <th scope="col" rowspan="2">미적립금액</th>
    <th scope="col" colspan="4">유형별 수수료집계</th>
    <th scope="col" rowspan="2">유형별 수수료 적립합계</th>
    <!-- <th scope="col" colspan="3">누적 수수료집계(????)</th> -->
	</tr>
	<tr class="rows">
    <th scope="col" class="th_bg">영업</th>
    <th scope="col" class="th_bg">추천</th>
    <th scope="col" class="th_bg">지역</th>
    <th scope="col" class="th_bg">본사</th>
		<!-- <th scope="col" class="th_bg2">현재잔액(????)</th>
		<th scope="col" class="th_bg2">총적립액(????)</th>
		<th scope="col" class="th_bg2">총차감액(????)</th> -->
	</tr>
	</thead>
  <tr>
		<input type="hidden" name="mb_id[<?php echo $i; ?>]" value="<?php echo $row['id']; ?>">
		<td><?php echo $total_count; ?>명</td>
		<td><?php echo $num_accumulated_pay; ?>건</td>
		<td><?php echo $num_unreserved_pay; ?>건</td>
		<td><?php echo number_format($unreserved_pay); ?>원</td>
    <td><?php echo number_format($sale_fee); ?>원</td>
    <td><?php echo number_format($recommend_fee); ?>원</td>
    <td><?php echo number_format($area_fee); ?>원</td>
    <td><?php echo number_format($hd_fee); ?>원</td>
    <td><?php echo number_format($total_fee); ?>원</td>
    <!-- <td>???????원</td>
    <td>???????원</td>
    <td>???????원</td> -->
	</tr>
	</table>
</div>


<!-- <form name="fpaylist" id="fpaylist" method="post" action="./partner/pt_plistupdate.php" onsubmit="return fpaylist_submit(this);"> -->
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="local_ov mart30">
  <b>검색결과</b>
	전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 명 조회
	<strong class="ov_a">총 수수료잔액 : <?php echo number_format($stotal_pay); ?>원 </strong>
</div>
<!-- <div class="local_frm01">
	<a href="./partner/pt_paylistexcel.php?<?php echo $q1; ?>" class="btn_small bx-white"><i class="fa fa-file-excel-o"></i> 엑셀저장</a>
</div> -->


<div class="tbl_head02">
	<table id="partner_list" class="td_center">
	<colgroup>
		<!-- <col class="w30"> -->
		<col class="w80">
		<col class="w80">
		<col class="w50">
		<col class="w60">
		<col class="w60">
		<col class="w100">
		<col class="w80">
		<col class="w80">
		<col class="w80">
    <col class="w80">
		<col class="w120">
		<col class="w80">
		<col class="w80">
		<col class="w80">
		<!-- <col class="w80"> -->
	</colgroup>
	<thead>
	<tr>
		<!-- <th scope="col" rowspan="2"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th> -->
		<th scope="col" rowspan="2">회원명</th>
		<th scope="col" rowspan="2">아이디</th>
		<th scope="col" rowspan="2">레벨</th>
		<th scope="col" rowspan="2">총적립건수</th>
		<th scope="col" rowspan="2">미적립건수</th>
    <th scope="col" rowspan="2">미적립금액</th>
		<th scope="col" colspan="4">유형별 수수료집계</th>
    <th scope="col" rowspan="2">유형별 수수료 적립합계</th>
    <th scope="col" colspan="3">회원별 누적 수수료집계</th>
	</tr>
	<tr class="rows">
    <th scope="col" class="th_bg">영업</th>
    <th scope="col" class="th_bg">추천</th>
    <th scope="col" class="th_bg">지역</th>
    <th scope="col" class="th_bg">본사</th>
		<th scope="col" class="th_bg2">현재잔액</th>
		<th scope="col" class="th_bg2">총적립액</th>
		<th scope="col" class="th_bg2">총차감액</th>
	</tr>
	</thead>
  <tbody>
	<?php
    $i = 0;
    foreach($f_array as $key=>$value) {
      $row = $value;
      $mb_id = $row['id'];
      $sql = " sELECT t1.mb_id ,t1.balance ,t2.accumulated_pay ,t3.deducted_pay
                FROM hi_pay_current t1
                LEFT JOIN (
                				SELECT mb_id ,SUM(pp_pay) AS accumulated_pay
                				FROM hi_partner_pay
                				WHERE mb_id = '$mb_id' AND pp_yn = 'Y') t2 ON t1.mb_id = t2.mb_id
                LEFT JOIN (
                				SELECT mb_id ,SUM(balance) AS deducted_pay
                				FROM hi_partner_payrun
                				WHERE mb_id = '$mb_id' AND state = '1' ) t3 ON t1.mb_id = t3.mb_id
                WHERE t1.mb_id = '$mb_id' ";
      $member_pay = sql_fetch($sql);

	?>
	<tr class="<?php echo $bg; ?>">
		<!-- <td>
			<input type="hidden" name="mb_id[<?php echo $i; ?>]" value="<?php echo $row['mb_id']; ?>">
			<label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $row['mb_id']; ?> 님</label>
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>" id="chk_<?php echo $i; ?>">
		</td> -->
    <td class="tal"><?php echo $row['name']; ?></td>
		<td class="tal"><?php echo $row['id']; ?></td>
		<td class="tal"><?php echo $row['grade']; ?></td>
		<td class="tal"><?php echo $row['num_accumulated_pay']; ?></td>
		<td class="tal"><?php echo $row['num_unreserved_pay']; ?></td>
		<td class="tar"><?php echo number_format($row['unreserved_pay']); ?>원</td>
		<td class="tar"><?php echo number_format($row['dircet_pay']); ?>원</td>
		<td class="tar"><?php echo number_format($row['rel_pay']); ?>원</td>
		<td class="tar"><?php echo number_format($row['location_pay']); ?>원</td>
    <td class="tar"><?php echo number_format($row['hd_pay']); ?>원</td>
		<td class="tar"><?php echo number_format($row['total_fee']); ?>원</td>
		<td class="tar"><?php echo number_format($member_pay['balance']); ?>원</td>
		<td class="tar"><?php echo number_format($member_pay['accumulated_pay']); ?>원</td>
		<td class="tar"><?php echo number_format($member_pay['deducted_pay']); ?>원</td>
	</tr>
	<?php
      $i++;
    }
  ?>
	</tbody>
	</table>
</div>
<!-- </form> -->

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>

<h2>개별회원 수수료 증감 설정</h2>
<form name="fpaylist2" id="fpaylist2" method="post" action="./partner/pt_ppayupdate.php" autocomplete="off">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="token" value="">
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w120">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="mb_id">회원아이디</label></th>
		<td><input type="text" name="mb_id" id="mb_id" required class="required frm_input"></td>
	</tr>
	<tr>
		<th scope="row"><label for="pp_content">수수료내용</label></th>
		<td><input type="text" name="pp_content" id="pp_content" required class="required frm_input" size="60"></td>
	</tr>
	<tr>
		<th scope="row"><label for="pp_pay">수수료금액</label></th>
		<td><input type="text" name="pp_pay" id="pp_pay" required class="required frm_input" size="10"> 원</td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="수수료적용" class="btn_large red">
</div>
</form>

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="desc02">
			<p>ㆍ수수료를 적립할 경우 양수만 입력하시기 바랍니다. 예) 3000</p>
			<p>ㆍ수수료를 차감할 경우 음수도 포함해 입력하시기 바랍니다. 예) -3000</p>
			<p class="fc_red">ㆍ수수료 차감액이 현재 잔액보다 클경우 차감되지 않습니다.</p>
		</div>
	</div>
</div>

<script>
function fpaylist_submit(f)
{
    if(!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "기간연장") {
        if(f.expire_date.value == 0) {
			alert('연장하실 기간을 선택하세요.');
			f.expire_date.focus();
			return false;
		}

        if(!confirm("선택한 자료를 기간연장 하시겠습니까?")) {
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

<!-- <?php
for($i=0; $row=sql_fetch_array($result); $i++) {

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

  $homepage = '';
  if($row['homepage']) {
    $homepage = set_http($row['homepage']);
    $homepage = '<a href="'.$homepage.'" target="_blank">'.$homepage.'</a>';
  }

  $info  = get_pay_sheet($row['id']); // 누적
  $sale  = get_pay_status($row['id'], 'sale'); // 판매
  $anew  = get_pay_status($row['id'], 'anew'); // 추천
  $visit = get_pay_status($row['id'], 'visit'); // 접속
  $admin = get_pay_status($row['id'], 'passive'); // 본사

  if($i==0)
    echo '<tbody class="list">'.PHP_EOL;

  $bg = 'list'.($i%2);
?>
<tr class="<?php echo $bg; ?>">
  <input type="hidden" name="mb_id[<?php echo $i; ?>]" value="<?php echo $row['id']; ?>">
  <td><?php echo get_sideview($row['id'], $row['name']); ?></td>
  <td><?php echo $expire_date; ?></td>
  <td><?php echo $expire_date; ?></td>
  <td><?php echo $homepage; ?></td>
  <td><?php echo number_format($sale['pay']); ?></td>
  <td><?php echo number_format($anew['pay']); ?></td>
  <td><?php echo number_format($visit['pay']); ?></td>
  <td><?php echo number_format($admin['pay']); ?></td>
  <td><?php echo $homepage; ?></td>
  <td><?php echo number_format($row['pay']); ?></td>
  <td><?php echo number_format($info['pay']); ?></td>
  <td><?php echo number_format($info['usepay']); ?></td>
</tr>
<?php
}
if($i==0)
  echo '<tbody><tr><td colspan="13" class="empty_table">자료가 없습니다.</td></tr>';
?>
</tbody> -->
