<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = new mysqli('localhost','root','','project');
$db->set_charset('utf8mb4'); // charset
?>