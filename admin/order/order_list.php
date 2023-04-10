<?php
if(!defined('_TUBEWEB_')) exit;

// 주문서 query 공통
include_once(TB_ADMIN_PATH.'/order/order_query.php');

$btn_frmline = <<<EOF
<a href="#" id="frmOrderPrint" class="btn_lsmall white"><i class="fa fa-print"></i> 예약내용 출력</a>
<a href="#" id="frmOrderExcel" class="btn_lsmall white"><i class="fa fa-file-excel-o"></i> 선택 엑셀저장</a>
<a href="./order/order_excel.php?$q1" class="btn_lsmall white"><i class="fa fa-file-excel-o"></i> 검색결과 엑셀저장</a>
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
		<th scope="row">업체유형</th>
		<td>
			<select name="goods_ca" id="goods_ca">
				<?php echo option_selected('', $goods_ca ,'전체'); ?>
				<?php echo option_selected('R', $goods_ca, '숙박'); ?>
				<?php echo option_selected('C', $goods_ca, '렌트카'); ?>
				<?php echo option_selected('E', $goods_ca, '체험'); ?>
			</select>
			<span id="goods_type"></span>
		</td>
	</tr>
	<tr>
		<th scope="row">예약상태</th>
		<td>
			<?php echo radio_checked('od_status', $od_status,  '', '전체'); ?>
			<!-- <?php echo radio_checked('od_status', $od_status, '1', $gw_status[1]); ?> -->
			<!-- <?php echo radio_checked('od_status', $od_status, '2', $gw_status[2]); ?> -->
			<?php echo radio_checked('od_status', $od_status, '3', $gw_status[3]); ?>
			<?php echo radio_checked('od_status', $od_status, '4', $gw_status[4]); ?>
			<?php echo radio_checked('od_status', $od_status, '5', $gw_status[5]); ?>
			<?php echo radio_checked('od_status', $od_status, '6', $gw_status[6]); ?>
			<?php echo radio_checked('od_status', $od_status, '9', $gw_status[9]); ?>
			<!-- <?php echo radio_checked('od_status', $od_status, '7', $gw_status[7]); ?> -->
			<!-- <?php echo radio_checked('od_status', $od_status, '8', $gw_status[8]); ?> -->
		</td>
	</tr>
	<tr>
		<th scope="row">사용완료</th>
		<td>
			<?php echo radio_checked('od_final', $od_final,  '', '전체'); ?>
			<?php echo radio_checked('od_final', $od_final, '1', '사용완료'); ?>
			<?php echo radio_checked('od_final', $od_final, '0', '사용미완료'); ?>
		</td>
	</tr>
	<!-- <tr>
		<th scope="row">취소 및 한불</th>
		<td> -->
			<!-- <?php echo radio_checked('od_status2', $od_status2,  '', '전체'); ?> -->
			<!-- <?php echo radio_checked('od_status2', $od_status2, '6', $gw_status[6]); ?> -->
			<!-- <?php echo radio_checked('od_status2', $od_status2, '9', $gw_status[9]); ?> -->
			<!-- <?php echo radio_checked('od_status', $od_status, '8', $gw_status[8]); ?> -->
		<!-- </td>
	</tr>  -->
	<tr>
		<th scope="row">결제방법</th>
		<td>
			<?php echo radio_checked('od_settle_case', $od_settle_case,  '', '전체'); ?>
			<?php echo radio_checked('od_settle_case', $od_settle_case, '간편계좌', '간편계좌'); ?>
			<?php echo radio_checked('od_settle_case', $od_settle_case, '통합페이', '통합페이'); ?>
			<?php echo radio_checked('od_settle_case', $od_settle_case, '포인트', '포인트'); ?>
			<?php echo radio_checked('od_settle_case', $od_settle_case, '신용카드', '신용카드(일반)'); ?>
			<?php echo radio_checked('od_settle_case', $od_settle_case, '간편신용카드', '신용카드(간편)'); ?>
			<!--?php echo radio_checked('od_settle_case', $od_settle_case, 'KAKAOPAY', 'KAKAOPAY'); ?-->
		</td>
	</tr>
	<tr>
		<th scope="row">기타선택</th>
		<td>
			<?php echo check_checked('od_taxbill', $od_taxbill, 'Y', '세금계산서'); ?>
			<?php echo check_checked('od_taxsave', $od_taxsave, 'Y', '현금영수증'); ?>
			<?php echo check_checked('od_memo', $od_memo, 'Y', '예약메세지'); ?>
			<?php echo check_checked('od_shop_memo', $od_shop_memo, 'Y', '관리자메모'); ?>
			<?php echo check_checked('od_receipt_point', $od_receipt_point, 'Y', '포인트주문'); ?>
			<?php echo check_checked('od_coupon', $od_coupon, 'Y', '쿠폰할인'); ?>
			<!--?php echo check_checked('od_escrow', $od_escrow, 'Y', '에스크로'); ?-->
		</td>
	</tr>
	<tr>
		<th scope="row">검색어</th>
		<td>
			<select name="sfl">
				<?php echo option_selected('od_id', $sfl, '예약번호'); ?>
				<!-- <?php echo option_selected('od_no', $sfl, '일련번호'); ?> -->
				<?php echo option_selected("mb_id", $sfl, '회원아이디'); ?>
				<?php echo option_selected('name', $sfl, '예약자명'); ?>
				<?php echo option_selected('deposit_name', $sfl, '입금자명'); ?>
				<!-- <?php echo option_selected('bank', $sfl, '입금계좌'); ?> -->
				<?php echo option_selected('b.company_name', $sfl, '업체명'); ?>
				<!-- <?php echo option_selected('b_telephone', $sfl, '예약자집전화'); ?> -->
				<?php echo option_selected('b_cellphone', $sfl, '예약자핸드폰'); ?>
				<!--?php echo option_selected('delivery_no', $sfl, '업체명'); ?-->
				<?php echo option_selected('seller_id', $sfl, '업체ID'); ?>
				<?php echo option_selected('pt_id', $sfl, '모집인ID'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input" size="30">
		</td>
	</tr>
	<tr>
		<th scope="row">기간검색</th>
		<td>
			<select name="sel_field">
				<?php echo option_selected('od_time', $sel_field, "예약일"); ?>
				<?php echo option_selected('receipt_time', $sel_field, "입금완료일"); ?>
				<?php echo option_selected('in_date', $sel_field, "체크인(대여)"); ?>
				<?php echo option_selected('out_date', $sel_field, "체크아웃(반납)"); ?>
				<?php echo option_selected('user_date', $sel_field, "사용완료"); ?>
				<?php echo option_selected('cancel_date', $sel_field, "예약취소일"); ?>
				<?php echo option_selected('refund_date', $sel_field, "환불완료일"); ?>
				<!--?php echo option_selected('return_date', $sel_field, "반품완료일"); ?-->
				<!--?php echo option_selected('change_date', $sel_field, "교환완료일"); ?-->
			</select>
			<?php echo get_search_date("fr_date", "to_date", $fr_date, $to_date); ?>
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

<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
	<select id="page_rows" onchange="location='<?php echo "{$_SERVER['SCRIPT_NAME']}?{$q1}&page=1"; ?>&page_rows='+this.value;" class="marl5">
		<?php echo option_selected('30',  $page_rows, '30줄 정렬'); ?>
		<?php echo option_selected('50',  $page_rows, '50줄 정렬'); ?>
		<?php echo option_selected('100', $page_rows, '100줄 정렬'); ?>
		<?php echo option_selected('150', $page_rows, '150줄 정렬'); ?>
	</select>
	<strong class="ov_a">총주문액 : <?php echo number_format($tot_orderprice); ?>원</strong>
</div>

<form name="forderlist" id="forderlist" method="post">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>


<div class="tbl_head01">
	<table>
	<thead>
	<tr>
		<th>번호</th>
		<th class="date_th">예약일시</th>
		<th>예약번호</th>
		<th><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th colspan="2">예약상품</th>
		<th>체크인<br />(대여)</th>
		<th>체크아웃<br />(반납)</th>
		<th class="price_th">상품금액</th>
		<th class="price_th">옵션비용</th>
		<th class="status_th">예약상태</th>
		<th class="company_th">업체명(업체ID)</th>
		<th>업체유형</th>
		<th>예약자</th>
		<th>예약자연락처</th>
		<th class="user_th">사용자</th>
		<th class="price_th">총결제금액</th>
		<th>결제방법</th>
		<th class="region_th">업체지역</th>
	</tr>
	</thead>
	<tbody>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$bg = 'list'.($i%2);

		$amount = get_order_spay($row['od_id']);
		$sodr = get_order_list($row, $amount);

		$sql = " select * {$sql_common} {$sql_join} {$sql_search} and od_id = '{$row['od_id']}' order by index_no ";
		$res = sql_query($sql);
		$rowspan = sql_num_rows($res);

		for($k=0; $row2=sql_fetch_array($res); $k++) {
			$gs = unserialize($row2['od_goods']);
	?>
	<tr class="<?php echo $bg; ?>">
		<?php if($k == 0) { ?>
		<td rowspan="<?php echo $rowspan; ?>"><?php echo $num--; ?></td>
		<td rowspan="<?php echo $rowspan; ?>">
			<?php echo substr($row['od_time'],2,14); ?>
			<?php echo $sodr['disp_test']; ?>
		</td>
		<td rowspan="<?php echo $rowspan; ?>">
			<a href="<?php echo TB_ADMIN_URL; ?>/pop_orderform.php?od_id=<?php echo $row['od_id']; ?>" onclick="win_open(this,'pop_orderform','1200','800','yes');return false;" class="fc_197"><?php echo $row['od_id']; ?></a>
			<?php echo $sodr['disp_mobile']; ?>
		</td>
		<td rowspan="<?php echo $rowspan; ?>">
			<input type="hidden" name="od_id[<?php echo $i; ?>]" value="<?php echo $row['od_id']; ?>">
			<label for="chk_<?php echo $i; ?>" class="sound_only">주문번호 <?php echo $row['od_id']; ?></label>
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>" id="chk_<?php echo $i; ?>">
		</td>
		<?php } ?>

		<td class="td_img"><a href="<?php echo TB_SHOP_URL; ?>/view.php?index_no=<?php echo $row2['gs_id']; ?>" target="_blank"><?php echo get_od_image($row['od_id'], $gs['simg1'], 30, 30); ?></a></td>
		<td class="td_itname"><a href="<?php echo TB_ADMIN_URL; ?>/goods.php?code=form&w=u&gs_id=<?php echo $row2['gs_id']; ?>" target="_blank"><?php echo get_text($gs['gname']); ?></a></td>
		<td><?php echo substr($row2['in_date'],0,10); ?></td>
		<td><?php echo substr($row2['out_date'],0,10); ?></td>
		<td class="tar"><?php echo number_format($row2['goods_price']); ?></td>
		<td class="tar"><?php echo number_format($row2['supply_price']); ?></td>
		<td>
			<?php if($row['dan']=='9' && $row['cancel_per']!='100') {
				echo '예약완료 후 부분환불';
			} else {
				echo $gw_status[$row2['dan']];
			} ?>
		</td>
		<td>
			<?php if ($row2['goods_ca'] == 'R') {
				echo get_seller_name($row2['seller_id'],'hi_room')."</br>";
			} else if ($row2['goods_ca'] == 'C') {
				echo get_seller_name($row2['seller_id'],'hi_rent')."</br>";
			} else if ($row2['goods_ca'] == 'E') {
				echo get_seller_name($row2['seller_id'],'hi_exp')."</br>";
			} ?>

			(<?php echo get_order_seller_id($row2['seller_id']); ?>)
		</td>
		<td>
				<?php if ($row2['goods_ca'] == 'R') {
					echo get_seller_item($row2['seller_id'],'hi_room')."</br>";
				} else if ($row2['goods_ca'] == 'C') {
					echo get_seller_item($row2['seller_id'],'hi_rent')."</br>";
				} else if ($row2['goods_ca'] == 'E') {
					echo get_seller_item($row2['seller_id'],'hi_exp')."</br>";
				} ?>
		</td>
		<?php if($k == 0) { ?>
		<td rowspan="<?php echo $rowspan; ?>">
			<!-- <?php echo $sodr['disp_od_name']; ?> -->
			<?php $booker = get_member($row['mb_id']); ?>
			<?php echo $booker['name']; ?>
			<?php echo $sodr['disp_mb_id']; ?>
		</td>
		<td rowspan="<?php echo $rowspan; ?>"><?php echo $booker['cellphone']; ?></td>
		<td rowspan="<?php echo $rowspan; ?>"><?php echo $row['b_name']."(".$row['b_cellphone'].")" ?></td>
		<td rowspan="<?php echo $rowspan; ?>" class="td_price"><?php echo number_format($row['use_price']); ?></td>
		<td rowspan="<?php echo $rowspan; ?>"><?php echo $row['PayMethod']; ?></td>
		<?php
			// 업채지역 들고 오는 쿼리
			$seller_info = get_room($row['seller_id'], 'ca_id');
			$sql = "select catename from hi_goods_category where catecode = '".$seller_info['ca_id']."'";
			$result_catename = sql_fetch($sql);
			$catename = $result_catename['catename'];
		?>
		<td rowspan="<?php echo $rowspan; ?>"><?php echo $catename; ?></td> <!-- 지역-->
		<?php } ?>
	<?php
		}
	}
	sql_free_result($result);
	if($i==0)
		echo '<tr><td colspan="16" class="empty_table">자료가 없습니다.</td></tr>';
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

<script>
$( document ).ready(function() {   //새로고침 시 써주는  ready

	// 업체유형 검색시으로 새로고침시에도 선택된 값들 선택하기
	var value = $("#goods_ca").val();
	order_list_ajax(value);
	var goods_type = "<?php echo $_GET['type']; ?>";

	$("input:radio[name='type']:input[value='"+goods_type+"']").attr("checked", true);

});

// 업체유형 바꿀때마다 다른 radio 불러오기
$("#goods_ca").change(function() {
	var value = $("#goods_ca").val();
	order_list_ajax(value);
});

function order_list_ajax(value) {
	$.ajax({
		url: "./order/order_list.ajax.php",
		type: "POST",
		dataType:"html",
		async: false,
		data: { value:value },
		success:function(data) {
			$('#goods_type').html(data);
		},
		error:function(error) {
			alert("오류");
		}
	});
}


$(function(){
    $("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });

	// 주문서출력
	$("#frmOrderPrint, #frmOrderExcel").on("click", function() {
		var type = $(this).attr("id");
		var od_chk = new Array();
		var od_id = "";
		var $el_chk = $("input[name='chk[]']");

		$el_chk.each(function(index) {
			if($(this).is(":checked")) {
				od_chk.push($("input[name='od_id["+index+"]']").val());
			}
		});

		if(od_chk.length > 0) {
			od_id = od_chk.join();
		}

		if(od_id == "") {
			alert("처리할 자료를 하나 이상 선택해 주십시오.");
			return false;
		} else {
			if(type == 'frmOrderPrint') {
				var url = "./order/order_print.php?od_id="+od_id;
				window.open(url, "frmOrderPrint", "left=100, top=100, width=670, height=600, scrollbars=yes");
				return false;
			} else {
				this.href = "./order/order_excel2.php?od_id="+od_id;
				return true;
			}
		}
	});
});
</script>
