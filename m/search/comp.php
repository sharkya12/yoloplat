<?php
include_once("./_common.php");
//
// include_once(TB_MAPP_PATH."/_yolo_head.php");
?>

<!DOCTYPE html>
<html>
  <head>
    <script src="../src/CurrentTime.js"></script>
  </head>
  <body>
    <current-time></current-time>
  </body>
</html>

<script>
class CurrentTime extends HTMLElement {
  constructor() {
    super(); // 항상 맨 앞에!

    console.log("yey!");
  }
}
window.customElements.define("current-time", CurrentTime);
</script>
