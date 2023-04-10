<?php
include_once("./_common.php");
  // header('Content-Type: text/html; charset=utf-8');

$pt_id = $_POST['pt_id'];

//소복이맴버가져오기
function gift_getGoods_Data($url){

  $ch = curl_init(); //curl 사용 전 초기화 필수(curl handle)

  curl_setopt($ch, CURLOPT_URL, $url); //URL 지정하기
  curl_setopt($ch, CURLOPT_POST, 0); //0이 default 값이며 POST 통신을 위해 1로 설정해야 함
  curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여줌. 이 값을 1로 하면 결과값을 return하게 되어 변수에 저장 가능(테스트 시 기본값은 1인듯?)

  $res = curl_exec ($ch);

  curl_close($ch);
  return $res;
}

$member_url = "https://sbokmall.com/api/member/get_member.php";

$get_member = gift_getGoods_Data($member_url);


$sobok_member = json_decode($get_member, ture);



foreach($sobok_member as $key=>$value) {
  // echo $key . '=>' . $value . '<br />';
  $row = $value;

  $id = $row['id'];

  if ($pt_id == $id) {
    echo "Y";
    break;
  }


}






 ?>
