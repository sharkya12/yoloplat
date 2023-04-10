<?php
if(!defined('_TUBEWEB_')) exit;

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " FROM hi_seller_cal as a
                INNER JOIN (select mb_id, seller_code, company_name from hi_room )AS b ON a.mb_id = b.mb_id";

$sql_group = " GROUP BY b.seller_code, b.company_name, a.mb_id, LEFT(a.reg_time, 10) ";

$sql_order_by = " ORDER BY MIN(reg_time) DESC ";

$sql_search = " WHERE 1=1 ";

if($sfl && $stx) {
    $sql_search .= " and $sfl like '%$stx%' ";
}

if($fr_date && $to_date)
    $sql_search .= " and a.reg_time between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
else if($fr_date && !$to_date)
	$sql_search .= " and a.reg_time between '$fr_date 00:00:00' and '$fr_date 23:59:59' ";
else if(!$fr_date && $to_date)
	$sql_search .= " and a.reg_time between '$to_date 00:00:00' and '$to_date 23:59:59' ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt FROM (SELECT a.mb_id $sql_common $sql_search $sql_group ) AS f  ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];


$rows = 10;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select b.seller_code, b.company_name, a.mb_id, a.reg_time, COUNT(a.mb_id) AS cnt, SUM(a.tot_price) AS tot_price,
        SUM(a.tot_point) AS tot_point, SUM(a.tot_coupon) AS tot_coupon, SUM(a.tot_supply) AS tot_supply, SUM(a.tot_seller) AS tot_seller
        $sql_common $sql_search $sql_group $sql_order_by limit $from_record, $rows ";

$result = sql_query($sql);
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
        <?php echo option_selected('company_name', $sfl, '업체명'); ?>
				<?php echo option_selected('seller_code', $sfl, '업체코드'); ?>
				<?php echo option_selected('a.mb_id', $sfl, '업체아이디'); ?>
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
	</tbody>
	</table>
</div>


</tr>
<div class="btn_confirm">
	<input type="submit" value="검색" class="btn_large">

</div>
</form>

<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
</div>

<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
	<table id="sodr_list">
  	<thead>
  	<tr>
  		<th scope="col">번호</th>
  		<th scope="col">업체코드</th>
  		<th scope="col">업체명</th>
  		<th scope="col">정산일</th>
  		<th scope="col">총건수</th>
  		<th scope="col">판매금액</th>
  		<th scope="col">포인트결제</th>
  		<th scope="col">쿠폰결제</th>
      <th scope="col">결제금액</th>
  		<th scope="col">실정산액</th>
  		<th scope="col">내역</th>
  	</tr>
  	</thead>
  	<tbody>
    <?php if (sql_num_rows($result) == 0) { ?>
      <tr>
        <td colspan="10">
          검색결과가 없습니다.
        </td>
      </tr>
    <?php } else { ?>
      <?php
        for($i=0; $row=sql_fetch_array($result); $i++) {
      ?>
        <tr>
          <td><span><?php echo $num--; ?></span></td>
      		<td scope="col"><?php echo $row['seller_code']; ?></td>
          <td>
            <?php echo get_seller_name($row['mb_id'],'hi_room')."</br>"; ?>
            (<?php echo get_order_seller_id($row['mb_id']); ?>)
          </td>
      		<td scope="col"><?php echo substr($row['reg_time'],0,10); ?></td>
      		<td scope="col"><?php echo $row['cnt']; ?>건</td>
      		<td scope="col"><?php echo number_format($row['tot_price']); ?></td>
      		<td scope="col"><?php echo $row['tot_point']; ?></td>
      		<td scope="col"><?php echo number_format($row['tot_coupon']); ?></td>
          <td scope="col"><?php echo number_format($row['tot_supply']); ?></td>
      		<td scope="col"><?php echo number_format($row['tot_seller']); ?></td>
      		<td scope="col">
            <a href="room.php?code=sales_settlement_list_detail&seller_id=<?php echo $row['mb_id']; ?>&date_time=<?php echo substr($row['reg_time'],0,10); ?>" class="btn_small">상세보기</a>
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

$(function(){
// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
$("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99" });
});

</script>
