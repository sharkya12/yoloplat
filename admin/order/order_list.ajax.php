<?php
  include_once("./_common.php");

  $value = $_POST['value'];
  // 숙박일때 업체유형 불러오기
  if ($value == "R") {
    echo radio_checked('type', $type,  '', '숙박전체');
    echo radio_checked('type', $type, 'HT', '호텔');
    echo radio_checked('type', $type, 'MT', '모텔');
    echo radio_checked('type', $type, 'PS', '펜션/풀빌라');
    echo radio_checked('type', $type,  'RS', '리조트/콘도');
    echo radio_checked('type', $type, 'PET', '반려견과함께');
    echo radio_checked('type', $type, 'KP', '캠핑/글랭핑');
    echo radio_checked('type', $type, 'GE', '게스트하우스');
    echo radio_checked('type', $type, 'MO', '한달살기');
  }
  // 렌트일때 업체유형 불러오기
  if ($value == "C") {
    echo radio_checked('type', $type,  '', '렌트전체');
    echo radio_checked('type', $type, 'RT', '렌트카');
    echo radio_checked('type', $type, 'KP', '캠핑카');
  }
  // 체험일때 업체유형 불러오기
  if ($value == "E") {
    echo radio_checked('type', $type,  '', '체험전체');
    echo radio_checked('type', $type, '맛집', '맛집');
    echo radio_checked('type', $type, '마사지', '마사지');
    echo radio_checked('type', $type, '골프장', '골프장');
    echo radio_checked('type', $type, '요트', '요트');
    echo radio_checked('type', $type, '박물관', '박물관');
  }
?>
