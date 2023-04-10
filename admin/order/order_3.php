<?php
if(!defined('_TUBEWEB_')) exit;

// 주문서 query 공통
include_once(TB_ADMIN_PATH.'/order/order_query.php');

$btn_frmline = <<<EOF
<!-- <input type="submit" name="act_button" value="운송장번호수정" class="btn_lsmall white" onclick="document.pressed=this.value"> -->
<a href="#" id="frmOrderPrint" class="btn_lsmall white"><i class="fa fa-print"></i> 주문서출력</a>
<a href="#" id="frmOrderExcel" class="btn_lsmall white"><i class="fa fa-file-excel-o"></i> 선택 엑셀저장</a>
<a href="./order/order_excel.php?$q1" class="btn_lsmall white"><i class="fa fa-file-excel-o"></i> 검색결과 엑셀저장</a>
<!-- <a href="./order.php?code=delivery" class="btn_lsmall"><i class="fa fa-upload"></i> 엑셀배송처리</a> -->
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
				<?php echo option_selected('od_id', $sfl, '예약번호'); ?>
				<?php echo option_selected("mb_id", $sfl, '회원아이디'); ?>
				<?php echo option_selected('name', $sfl, '예약자명'); ?>
				<?php echo option_selected('deposit_name', $sfl, '입금자명'); ?>
				<?php echo option_selected('b.company_name', $sfl, '업체명'); ?>
				<?php echo option_selected('cellphone', $sfl, '예약자핸드폰'); ?>
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
			</select>
			<?php echo get_search_date("fr_date", "to_date", $fr_date, $to_date); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">결제방법</th>
		<td>
			<?php echo radio_checked('od_settle_case', $od_settle_case,  '', '전체'); ?>
			<!-- <?php echo radio_checked('od_settle_case', $od_settle_case, '무통장', '무통장'); ?> -->
			<?php echo radio_checked('od_settle_case', $od_settle_case, '간편계좌', '간편계좌'); ?>
			<?php echo radio_checked('od_settle_case', $od_settle_case, '통합페이', '통합페이'); ?>
			<?php echo radio_checked('od_settle_case', $od_settle_case, '포인트', '포인트'); ?>
			<?php echo radio_checked('od_settle_case', $od_settle_case, '신용카드(일반)', '신용카드(일반)'); ?>
			<?php echo radio_checked('od_settle_case', $od_settle_case, '신용카드(간편)', '신용카드(간편)'); ?>
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
			<!-- <?php echo check_checked('od_escrow', $od_escrow, 'Y', '에스크로'); ?> -->
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

<form name="forderlist" id="forderlist" action="./order/order_update.php" onsubmit="return forderlist_submit(this);" method="post">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
	<table id="sodr_list" class="order3">
	<!-- <colgroup>
		<col class="w50">
		<col class="w100">
		<col class="w150">
		<col class="w40">
		<col class="w150">
		<col class="w40">
		<col class="w120">
		<col class="w80">
		<col class="w80">
		<col class="w90">
		<col class="w90">
		<col class="w120">
		<col class="w90">
		<col class="w90">
		<col class="w90">
		<col class="w90">
		<col class="w90">
		<col class="w90">
	</colgroup> -->
	<thead>
	<tr>
		<th scope="col">번호</th>
		<th scope="col">예약일시</th>
		<th scope="col">예약번호</th>
		<th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th scope="col" colspan="2">예약상품</th>
		<th scope="col">체크인<br />(대여)</th>
		<th scope="col">체크아웃<br />(반납)</th>
		<th scope="col">업체명(업체ID)</th>
		<th scope="col">업체유형</th>
		<th scope="col">예약자</th>
		<th scope="col">예약자연락처</th>
		<th scope="col">사용자</th>
		<th scope="col">상품금액</th>
		<th scope="col">옵션비용</th>
		<th scope="col">총결제금액</th>
		<th scope="col">결제방법</th>
		<th scope="col">업체지역</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$chk_cnt = 0;
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$bg = 'list'.($i%2);

		$amount = get_order_spay($row['od_id']);
		$sodr = get_order_list($row, $amount, "and dan IN ('4','5')");

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
			<?php echo $sodr['disp_baesong']; ?>
		</td>
		<td rowspan="<?php echo $rowspan; ?>">
			<input type="hidden" name="od_id[<?php echo $i; ?>]" value="<?php echo $row['od_id']; ?>">
			<label for="chk_<?php echo $i; ?>" class="sound_only">주문번호 <?php echo $row['od_id']; ?></label>
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>" id="chk_<?php echo $i; ?>">
		</td>
		<!-- <td rowspan="<?php echo $rowspan; ?>">
			<input type="hidden" name="od_id[<?php echo $i; ?>]" value="<?php echo $row['od_id']; ?>">
			<label for="sit_sel_<?php echo $i; ?>" class="sound_only">전체선택</label>
			<input type="checkbox" name="it_sel[]" id="sit_sel_<?php echo $i; ?>">
		</td> -->
		<?php } ?>
		<!-- <td class="td_chk">
			<input type="hidden" name="od_no[<?php echo $chk_cnt; ?>]" value="<?php echo $row2['od_no']; ?>">
			<input type="checkbox" name="chk[]" value="<?php echo $chk_cnt; ?>" id="chk_<?php echo $chk_cnt; ?>" class="sct_sel_<?php echo $i; ?>">
			<label for="chk_<?php echo $chk_cnt; ?>" class="sound_only"><?php echo $gs['gname']; ?></label>
		</td> -->

		<td class="td_imgline"><a href="<?php echo TB_SHOP_URL; ?>/view.php?index_no=<?php echo $row2['gs_id']; ?>" target="_blank"><?php echo get_od_image($row['od_id'], $gs['simg1'], 30, 30); ?></a></td>
		<td class="td_itname"><a href="<?php echo TB_ADMIN_URL; ?>/goods.php?code=form&w=u&gs_id=<?php echo $row2['gs_id']; ?>" target="_blank"><?php echo get_text($gs['gname']); ?></a></td>

		<td><?php echo substr($row2['in_date'],0,10); ?></td>
		<td><?php echo substr($row2['out_date'],0,10); ?></td>
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
					echo get_seller_item($row2['seller_id'],'hi_room');
				} else if ($row2['goods_ca'] == 'C') {
					echo get_seller_item($row2['seller_id'],'hi_rent');
				} else if ($row2['goods_ca'] == 'E') {
					echo get_seller_item($row2['seller_id'],'hi_exp');
				} ?>
		</td>
		<?php if($k == 0) { ?>
		<td rowspan="<?php echo $rowspan; ?>">
			<?php $booker = get_member($row['mb_id']); ?>
			<?php echo $booker['name']; ?>
			<?php echo $sodr['disp_mb_id']; ?>
		</td>
		<td rowspan="<?php echo $rowspan; ?>"><?php echo $booker['cellphone']; ?></td>
		<td rowspan="<?php echo $rowspan; ?>"><?php echo $row['b_name']."(".$row['b_cellphone'].")" ?></td>
		<td class="tar"><?php echo number_format($row2['goods_price']); ?></td>
		<td class="tar"><?php echo number_format($row2['supply_price']); ?></td>
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
		$chk_cnt++;
		}
	}
	sql_free_result($result);
	if($i==0)
		echo '<tr><td colspan="14" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>
<div class="local_frm02">
	<?php echo $btn_frmline; ?>
</div>

<h2>예약 승인처리</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w100">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row" rowspan="2">선택한 상품을</th>
		<!-- <td>
			<?php echo get_delivery_select("delivery2"); ?>
			<input type="text" name="delivery_no2" class="frm_input" placeholder="일괄 운송장번호">
			<?php echo help('선택한 주문을 일괄처리시에 입력하세요. 주문목록에서 개별 입력도 가능합니다.'); ?>
		</td> -->
	</tr>
	<tr>
		<td>
			<input type="submit" name="act_button" value="예약완료" class="btn_medium red" onclick="document.pressed=this.value">
			<input type="submit" name="act_button" value="예약취소 - 전액환불" class="btn_medium white" onclick="document.pressed=this.value">
			<!-- <a class="btn_medium white" onclick="partial_cancellation()" class="fc_197">예약대기 부분취소 - 부분환불</a> -->
			<input type="hidden" name="order_update_status" id="order_update_status" value="0"/>
		</td>
	</tr>
	</tbody>
	</table>
</div>
</form>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>

<!-- <div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="hd">ㆍ주문상태 변경에 제한이 있나요?</div>
		<div class="desc01 accent">
			<p>ㆍ주문리스트 내 선택된 주문의 상태를 <em>"입금완료 &gt; 배송준비 &gt; 배송중 &gt; 배송완료 &gt; 구매확정"</em> 순으로 변경됩니다.</p>
			<p>ㆍ배송준비 상태의 주문은 "배송중" 상태로만 변경할 수 있으며, 주문의 일부 상품만 부분적으로 "배송중" 상태로 변경 가능합니다.</p>
		</div>
		<div class="hd">ㆍ출고전 취소할 수 있나요?</div>
		<div class="desc01 accent">
			<p>ㆍ주문리스트에서 배송준비 상태의 주문을 취소/환불 처리가 불가능합니다.</p>
			<p>ㆍ<em>취소/환불/반품/교환</em> 등의 주문상태로 변경은 해당 주문의 <strong>"주문상세정보"</strong> 페이지에서 처리 가능합니다.</p>
		</div>
	 </div>
</div> -->

<script>
$(function(){
    $("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });

	// // 전체 상품선택
  //   $("#sit_select_all").click(function() {
  //       if($(this).is(":checked")) {
  //           $("input[name='it_sel[]']").attr("checked", true);
  //           $("input[name^=chk]").attr("checked", true);
  //       } else {
  //           $("input[name='it_sel[]']").attr("checked", false);
  //           $("input[name^=chk]").attr("checked", false);
  //       }
  //   });
	//
  //   // 주문의 상품선택
  //   $("input[name='it_sel[]']").click(function() {
  //       var cls = $(this).attr("id").replace("sit_", "sct_");
  //       var $chk = $("input[name^=chk]."+cls);
  //       if($(this).is(":checked"))
  //           $chk.attr("checked", true);
  //       else
  //           $chk.attr("checked", false);
  //   });

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

<script>
function forderlist_submit(f)
{
    if(!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

	if(document.pressed == "배송중") {
		if(f.delivery2.value && f.delivery_no2.value) {
			var $el_chk = $("input[name='chk[]']");
			$el_chk.each(function(index) {
				if($(this).is(":checked")) {
					$("select[name='delivery["+index+"]']").val(f.delivery2.value);
					$("input[name='delivery_no["+index+"]']").val(f.delivery_no2.value);
				}
			});
		}

		if(!confirm("선택하신 주문서의 주문상태를 '"+document.pressed+"'상태로 변경하시겠습니까?"))
			return false;
	}

	return true;
}

// <td rowspan="<?php echo $rowspan; ?>">
// 	<input type="hidden" name="od_id[<?php echo $i; ?>]" value="<?php echo $row['od_id']; ?>">
// 	<label for="chk_<?php echo $i; ?>" class="sound_only">주문번호 <?php echo $row['od_id']; ?></label>
// 	<input type="checkbox" name="chk[]" value="<?php echo $i; ?>" id="chk_<?php echo $i; ?>">
// </td>
// <a href="<?php echo TB_ADMIN_URL; ?>/pop_partial_cancellation.php?od_id=21122310324196" class="btn_medium white" onclick="win_open(this,'pop_orderform','1200','800','yes');return false;" class="fc_197">부분취소</a>

$("input[name='chk[]']").change(function() {
	// let num =
	// if($("input[name='chk[]']").is(":checked")) {
	// 	$(this).val());
	// }
});

function partial_cancellation() {
	var obj_length = document.getElementsByName("chk[]").length;
	let pc_val = new Array();

	for(let v=0; v<obj_length; v++) {
		if(document.getElementsByName("chk[]")[v].checked == true) {
			pc_val.push(document.getElementsByName("chk[]")[v].value);
		}
	}

	let od_id_arr = new Array();

	for(let i=0; i<pc_val.length; i++) {
		od_id_arr.push($("input[name='od_id["+pc_val[i]+"]']").val());
	}

	var popup = window.open('<?php echo TB_ADMIN_URL; ?>/pop_partial_cancellation.php?od_id='+od_id_arr.reverse()+'&sort=hold','pop_partial_cancellation','width=1200,height=800,resizeable=yes');
	var timer = setInterval(function() {
		var order_update_status = $('#order_update_status').val();
		//업데이트 하고 창 자동 닫기 되었을시
		if(order_update_status == 1) {
			location.reload();
		}
	}, 1000);

}
</script>
