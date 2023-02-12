<?php

session_start();
 
if(!isset($_SESSION["loggedin_t"]) || $_SESSION["loggedin_t"] !== true){
    header("location: login.php");
    exit;
}

if (!isset($_POST['query']) || empty($_POST['query'])) {
    header("location: login.php");
    exit;
  }
  
require_once 'dbcon.php';
  
$id = $_SESSION['id'];

$sql_noti = $_POST['query'];
$stmt_noti = $db->prepare($sql_noti);
$stmt_noti->bind_param('i', $id);
$stmt_noti->execute();
$result_noti = $stmt_noti->get_result();

$data = array();
while($row_noti = $result_noti->fetch_assoc()){
            $data[] = $row_noti;
  }
  echo json_encode($data);
?>