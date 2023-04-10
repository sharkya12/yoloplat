<?php
if(!defined('_TUBEWEB_')) exit;

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

if(isset($od_pay_method))		 $qstr .= "&od_pay_method=$od_pay_method";

$query_string = "code=$code&seller_id=$seller_id$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from hi_order";

$sql_search = " where (dan = 5 or (dan = '9' and cancel_per != '100')) and seller_id = '$seller_id' and sellerpay_yes = 0 and od_id NOT IN (select order_idx from hi_seller_cal )";

if($od_pay_method) {
  if($od_pay_method=='포인트'){
    $sql_search .= " and use_point > 0 ";
  } else {
    $sql_search .= " and paymethod = '$od_pay_method' ";
  }
}
if($sfl && $stx) {
  $sql_search .= " and $sfl like '%$stx%' ";
}

if($fr_date && $to_date)
    $sql_search .= " and invoice_date between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
else if($fr_date && !$to_date)
	$sql_search .= " and invoice_date between '$fr_date 00:00:00' and '$fr_date 23:59:59' ";
else if(!$fr_date && $to_date)
	$sql_search .= " and invoice_date between '$to_date 00:00:00' and '$to_date 23:59:59' ";

$sql_order_by = " ORDER BY refund_date desc, invoice_date DESC ";

// 테이블의 전체 레코드수와 총 판매금액 얻음
$sql = " select count(*) as cnt, SUM(goods_price) AS goods_price $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];
$goods_price = $row['goods_price'];

//업체 정보 가져오기
$room = get_room($seller_id);
$company_name = $room['company_name'];

$rows = 10;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order_by limit $from_record, $rows ";
$result = sql_query($sql);

echo $sql;
include_once(TB_PLUGIN_PATH.'/jquery-ui/datepicker.php');


?>


<div class="room_btn_div">
  <button type="button" id="info_room" onclick="location.href='./room.php?code=register&w=u&mb_id=<?php echo $seller_id; ?>' "><b>숙박업소정보수정</b></button>
  <button type="button" id="sell_room"><b>숙박업소판매내역</b></button>
</div>

<h2>기본검색</h2>
<form name="fsearch" id="fsearch" method="get">
<input type="hidden" name="code" value="<?php echo $code; ?>">
<input type="hidden" name="seller_id" value="<?php echo $seller_id; ?>">
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
				<?php echo option_selected('od_id', $sfl, '예약번호'); ?>
				<?php echo option_selected('name', $sfl, '예약자명'); ?>
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
    <th scope="row">결제방법</th>
    <td>
      <?php echo radio_checked('od_pay_method', $od_pay_method,  '', '전체'); ?>
      <?php echo radio_checked('od_pay_method', $od_pay_method, '간편신용카드', '신용카드(간편)'); ?>
      <?php echo radio_checked('od_pay_method', $od_pay_method, '신용카드', '신용카드(일반)'); ?>
      <?php echo radio_checked('od_pay_method', $od_pay_method, '간편계좌', '계좌 결제'); ?>
      <?php echo radio_checked('od_pay_method', $od_pay_method, '통합페이', '통합페이 결제'); ?>
      <?php echo radio_checked('od_pay_method', $od_pay_method, '포인트', '포인트'); ?>
    </td>
  </tr>

  <!-- <tr>
    <th scope="row">정산상태</th>
    <td>
      <?php echo radio_checked('isopen', $isopen,  '', '전체'); ?>
      <?php echo radio_checked('isopen', $isopen, '1', '정산확정'); ?>
      <?php echo radio_checked('isopen', $isopen, '2', '정산예정'); ?>
      <?php echo radio_checked('isopen', $isopen, '3', '정산보류'); ?>
      <?php echo radio_checked('isopen', $isopen, '4', '환불'); ?>
    </td>
  </tr> -->
  <!-- <tr>
    <th scope="row">기타선택</th>
    <td>
      <?php echo check_checked('taxbill_yes', $taxbill_yes, 'Y', '세금계산서'); ?>
      <?php echo check_checked('taxsave_yes', $taxsave_yes, 'Y', '현금영수증'); ?>
      <?php echo check_checked('memo', $memo, 'Y', '고객메세지'); ?>
      <?php echo check_checked('shop_memo', $shop_memo, 'Y', '관리자메모'); ?>
      <?php echo check_checked('', $od_receipt_point, 'Y', '포인트주문'); ?>
      <?php echo check_checked('', $od_coupon, 'Y', '쿠폰할인'); ?>
      <?php echo check_checked('escrow', $escrow, 'Y', '에스크로'); ?>
    </td>
  </tr> -->

	</tbody>
	</table>
</div>
<br></br>

<div class="btn_confirm">
	<input type="submit" value="검색" class="btn_large">

</div>
</form>

<div class="local_ov mart30">
<?php echo $company_name; ?>
| 전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
| 총 판매금액 : <?php echo number_format($goods_price); ?>원
</div>

<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
	<table id="sodr_list">
	<!-- <colgroup>
		<col class="w80">
		<col class="w200">
		<col class="w120">
		<col class="w120">
		<col class="w100">
		<col class="w100">
		<col class="w120">
		<col class="w100">
		<col class="w120">
		<col class="w100">
		<col class="w120">
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col class="w60">

	</colgroup> -->
	<thead>
	<tr>
		<th scope="col">번호</th>
		<th scope="col">예약번호</th>
		<th scope="col">예약상품</th>
		<th scope="col">예약상태</th>
		<th scope="col">판매가</th>
		<th scope="col">포인트결제</th>
		<th scope="col">쿠폰결제</th>
    <th scope="col">결제금액</th>
		<th scope="col">정산가</th>
		<th scope="col">결제방법</th>
		<th scope="col">예약자명</th>
	</tr>
	</thead>
	<tbody>
  <?php if (sql_num_rows($result) == 0) { ?>
    <tr>
      <td colspan="11">
        검색결과가 없습니다.
      </td>
    </tr>
  <?php } else { ?>
    <?php
      for($i=0; $row=sql_fetch_array($result); $i++) {

        //예약상태값 가져오기
        if($row['dan']=='5') {
          $order_status = '사용완료';
          $real_value = (int)$row['goods_price'] - (int)($row['goods_price'] * 0.077) - (int)$row['coupon_price']; //실제 정산가 계산하기
          $yoil = get_yoil($row['od_time']); // 예약일 요일 가져오기
          $order_time = date("Y.m.d H:i", strtotime($row['od_time'])); //예약일 날짜 가져오기
          $used_date = date("Y.m.d", strtotime($row['invoice_date'])); //사용완료일 가져오기
        } else {
          $order_status = '부분환불';
          $real_value = ((int)$row['goods_price'] - (int)$row['cancel_price']) - (int)($row['goods_price'] * 0.077) - (int)$row['coupon_price']; //실제 정산가 계산하기
          $yoil = get_yoil($row['od_time']); // 예약일 요일 가져오기
          $order_time = date("Y.m.d H:i", strtotime($row['od_time'])); //예약일 날짜 가져오기
          $used_date = date("Y.m.d", strtotime($row['refund_date'])); //사용완료일 가져오기
        }

    ?>
      <tr>
        <td><span><?php echo $num--; ?></sapn></td>
    		<td scope="col"><?php echo $row['od_id']; ?><br /><?php echo $order_time.'('.$yoil.')'; ?></td>
        <td scope="col"><?php echo $row['gname']; ?></td>
        <td scope="col"><?php echo $order_status;?><br /><?php echo $used_date; ?></td>
    		<td scope="col"><?php echo number_format($row['goods_price']); ?></td>
    		<td scope="col"><?php echo number_format($row['use_point']); ?></td>
    		<td scope="col"><?php echo number_format($row['coupon_price']); ?></td>
        <td scope="col"><?php echo number_format($row['use_price']); ?></td>
    		<td scope="col"><?php echo number_format($real_value); ?></td>
    		<td scope="col"><?php echo $row['PayMethod']; ?></td>
        <td>
          <?php $booker = get_member($row['mb_id']); ?>
          <?php echo $booker['name']; ?>
          <?php if($row['mb_id'])
                  $disp_mb_id = '<span class="list_mb_id">('.$row['mb_id'].')</span>';
                else
                  $disp_mb_id = '<span class="list_mb_id">(비회원)</span>';?>
          <?php echo $disp_mb_id; ?>
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


<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>

<script>

$(".sell_room").click(function() {
  location.href='http://yoloplat.net/admin/room.php?code=goods_total_up';
});


$(function(){
// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
$("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99" });
});


</script>
