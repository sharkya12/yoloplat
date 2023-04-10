<?php
if(!defined('_TUBEWEB_')) exit;

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " FROM hi_order AS a
                INNER JOIN (select mb_id, seller_code from hi_room )AS b ON a.seller_id = b.mb_id ";

$sql_search = " WHERE (a.dan = 5 or (a.dan = '9' and a.cancel_per != '100')) and a.sellerpay_yes = 0 and a.od_id NOT IN (select order_idx from hi_seller_cal ) ";

$sql_group = " GROUP BY seller_code, seller_id ";

$sql_order_by = " ORDER BY refund_date DESC, invoice_date DESC, od_time DESC ";

if($sfl && $stx) {
    $sql_search .= " and $sfl like '%$stx%' ";
}

if($fr_date && $to_date)
  $sql_search .= " and a.invoice_date between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
else if($fr_date && !$to_date)
	$sql_search .= " and a.invoice_date between '$fr_date 00:00:00' and '$fr_date 23:59:59' ";
else if(!$fr_date && $to_date)
	$sql_search .= " and a.invoice_date between '$to_date 00:00:00' and '$to_date 23:59:59' ";


// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt
        FROM (SELECT a.seller_id $sql_common $sql_search $sql_group ) AS f ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 10;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

// 정산관리 결과 쿼리
$sql = " select b.seller_code, a.seller_id, COUNT(a.seller_id) AS cnt, SUM(a.goods_price) AS goods_price,
        SUM(a.use_point) AS use_point, SUM(a.coupon_price) AS coupon_price, SUM(a.cancel_price) AS cancel_price,
        SUM(
          case
			     when a.dan = '5' then a.use_price
	         when a.dan = '9' and a.cancel_per != '100' then (a.use_price - a.cancel_price)
           else 0
	        END) as use_price,
        max(a.refund_date) as refund_date, max(a.invoice_date) as invoice_date
        $sql_common $sql_search $sql_group $sql_order_by limit $from_record, $rows ";
$result = sql_query($sql);

include_once(TB_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$btn_frmline = <<<EOF
<input type="submit" name="act_button" value="선택정산" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<a href="./room/room_list_excel.php?$q1" class="btn_lsmall bx-white"><i class="fa fa-file-excel-o"></i> 엑셀저장</a>
EOF;
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
        <?php echo option_selected('a.company_name', $sfl, '업체명'); ?>
				<?php echo option_selected('b.seller_code', $sfl, '업체코드'); ?>
				<?php echo option_selected('a.seller_id', $sfl, '업체아이디'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input" size="30">
		</td>
	</tr>
	<tr>
		<th scope="row">기간검색<br>(사용완료)</th>
		<td>
			<?php echo get_search_date("fr_date", "to_date", $fr_date, $to_date); ?>
		</td>
	</tr>
  <!-- <tr>
    <th scope="row"><b>정산상태</b></th>
    <td>
      <?php echo radio_checked('q_isopen', $q_isopen,  '', '전체'); ?>
      <?php echo radio_checked('q_isopen', $q_isopen, '1', '정산확정'); ?>
      <?php echo radio_checked('q_isopen', $q_isopen, '2', '정산예정'); ?>
      <?php echo radio_checked('q_isopen', $q_isopen, '3', '정산보류'); ?>
      <?php echo radio_checked('q_isopen', $q_isopen, '4', '환불'); ?>
  		<?php echo radio_checked('q_isopen', $q_isopen, '5', '포인트'); ?>
  		<?php echo radio_checked('q_isopen', $q_isopen, '6', '쿠폰'); ?>
    </td>
  </tr> -->
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="검색" class="btn_medium">
	<input type="button" value="초기화" id="frmRest" class="btn_medium grey">
</div>
</form>

<form name="fsellerlist" id="fsellerlist" method="post" action="./room/room_sales_settlement_update.php" onsubmit="return fsellerlist_submit(this);">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
</div>
<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
	<table id="sodr_list">
	<colgroup>
		<col class="w30">
		<col class="w50">
		<col class="w100">
		<col class="w200">
		<col class="w80">
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col class="w100">
    <col class="w100">
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col class="w60">
	</colgroup>
	<thead>
	<tr>
		<th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th scope="col">번호</th>
		<th scope="col">업체코드</th>
		<th scope="col">업체명</th>
		<th scope="col">총건수</th>
		<th scope="col">판매금액</th>
		<th scope="col">포인트결제</th>
		<th scope="col">쿠폰결제</th>
    <th scope="col">환불금액</th>
		<th scope="col">결제금액</th>
		<th scope="col">업체수수료</th>
		<th scope="col">실정산액</th>
		<th scope="col">매니저수수료</th>
		<th scope="col">pg수수료</th>
		<th scope="col">본사마진</th>
		<th scope="col">내역</th>
	</tr>
	</thead>
	<tbody>
  <?php if (sql_num_rows($result) == 0) { ?>
    <tr>
      <td colspan="15">
        검색결과가 없습니다.
      </td>
    </tr>
  <?php } else { ?>
    <?php
      for($i=0; $row=sql_fetch_array($result); $i++) {
        $company_fee = ((int)$row['goods_price'] - (int)$row['cancel_price']) * 0.077;

        $sql = " sELECT od_id, goods_price, coupon_price, cancel_price
                  FROM hi_order a
                  $sql_search and a.seller_id = '{$row['seller_id']}'  ";
        $goods_price = sql_query($sql);

        $settlement_price = "";

        for($l=0; $row1=sql_fetch_array($goods_price); $l++) {
          if($row1['cancel_price'] > 0) {
            $real_value = ((int)$row1['goods_price'] - (int)$row1['cancel_price']) - (int)(($row1['goods_price'] - (int)$row1['cancel_price']) * 0.077) - (int)$row1['coupon_price'];
          } else {
            $real_value = (int)$row1['goods_price'] - (int)($row1['goods_price'] * 0.077) - (int)$row1['coupon_price'];
          }

          $settlement_price += $real_value;
        }

        $manager_fee = (int)$row['use_price'] * 0.0195;
        $pg_fee = (int)$row['use_price'] * 0.033;
        $headquarter_margin = $company_fee - $manager_fee - $pg_fee;
    ?>
      <tr>
        <td>
          <input type="hidden" name="seller_id[<?php echo $i; ?>]" value="<?php echo $row['seller_id']; ?>">
          <input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
          <input type="hidden" name="fr_date" value="<?php echo $fr_date; ?>" >
          <input type="hidden" name="to_date" value="<?php echo $to_date; ?>" >
        </td>
        <td><span><?php echo $num--; ?></span></td>
    		<td scope="col"><?php echo $row['seller_code']; ?></td>
        <td>
          <?php echo get_seller_name($row['seller_id'],'hi_room')."</br>"; ?>
          (<?php echo get_order_seller_id($row['seller_id']); ?>)
        </td>
    		<td scope="col"><?php echo number_format($row['cnt']); ?>건</td>
    		<td scope="col"><?php echo number_format($row['goods_price']); ?></td>
    		<td scope="col"><?php echo number_format($row['use_point']); ?></td>
    		<td scope="col"><?php echo number_format($row['coupon_price']); ?></td>
        <td scope="col">- <?php echo number_format($row['cancel_price']); ?></td>
    		<td scope="col"><?php echo number_format($row['use_price']); ?></td>
    		<td scope="col"><?php echo number_format($company_fee); ?></td>
    		<td scope="col"><?php echo number_format($settlement_price); ?></td>
    		<td scope="col"><?php echo number_format($manager_fee); ?></td>
    		<td scope="col"><?php echo number_format($pg_fee); ?></td>
    		<td scope="col"><?php echo number_format($headquarter_margin); ?></td>
    		<td scope="col">
          <a href="room.php?code=sales_settlement_detail&seller_id=<?php echo $row['seller_id']; ?>&fr_date=<?php echo $fr_date; ?>&to_date=<?php echo $to_date; ?>" class="btn_small">상세보기</a>
        </td>
      </tr>
    <?php } ?>
  <?php } ?>
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
<script>
function forderlist_submit(f)
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

    return true;
}


$(function(){
// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
$("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99" });
});



</script>
