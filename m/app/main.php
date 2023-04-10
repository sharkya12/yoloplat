<?php
include_once("./_common.php");


$tb['title'] = "욜로플랫";

function fn_curl($url){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_HTTPHEADER,CURLAUTH_BASIC);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_URL,$url);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

  curl_setopt($ch, CURLOPT_HTTPHEADER,array('Accept: application/json','Content-Type: application/json',
                                            'Authorization: KakaoAK 9267446f60957dff614def0938f5665b'));
  curl_setopt($ch, CURLOPT_VERBOSE, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

  $res = curl_exec($ch);
  return $res;
}

include_once(TB_MSHOP_PATH."/_head2.php");

include_once(TB_MYOLO_THEME.'/main.skin.php');
include_once(TB__THEME.'/main.skin.php');
include_once(TB_MSHOP_PATH."/_tail.php");
?>
