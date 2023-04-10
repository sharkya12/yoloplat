<?php
include_once("./_common.php");

//아이디값 input 비밀번호 값 가지고 와서 DB비밀번호와 비교하기
$mb_id = $_POST['mb_id'];
$input_pw = get_encrypt_string($_POST['input_pw']);

$sql = " select passwd from hi_member where id = '".$mb_id."'";
$result = sql_fetch($sql);
$passwd = $result['passwd'];

// input 비밀번호와 DB 비밀번호가 같을때
if ($passwd == $input_pw){
  $result = 1;

} else {
  $result = 0;
}

echo $result;

?>
