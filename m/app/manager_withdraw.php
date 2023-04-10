<?php
include_once("./_common.php");

$tb['title'] = "정산 출금현황";
include_once(TB_MAPP_PATH."/_yolo_head.php");


$mb_id = 'dlscjf';

function gift_getGoods_Data($url){

  $ch = curl_init(); //curl 사용 전 초기화 필수(curl handle)

  curl_setopt($ch, CURLOPT_URL, $url); //URL 지정하기
  curl_setopt($ch, CURLOPT_POST, 0); //0이 default 값이며 POST 통신을 위해 1로 설정해야 함
  curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여줌. 이 값을 1로 하면 결과값을 return하게 되어 변수에 저장 가능(테스트 시 기본값은 1인듯?)

  $res = curl_exec ($ch);

  curl_close($ch);
  return $res;
}

$member_url = "https://sbokmall.com/api/member/get_partner.php?mb_id={$mb_id}";

$mb_info = gift_getGoods_Data($member_url);


$mb = json_decode($mb_info, ture);

$id = $mb[0]['id']; //회원아이디
$bank_name = $mb[0]['bank_name']; //계좌거래은행
$bank_account = $mb[0]['bank_account']; //계좌번호
$bank_holder = $mb[0]['bank_holder']; //예금주명
$pay = $mb[0]['pay'];

echo $pay;
echo $bank_name;


include_once(TB_MYOLO_THEME.'/manager_withdraw.skin.php');

?>
