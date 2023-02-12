<?php

session_start();
 
if(!isset($_SESSION["loggedin_t"]) || $_SESSION["loggedin_t"] !== true){
    header("location: login.php");
    exit;
}

require_once "dbcon.php";

$stIdArray = $_POST['stIdArray'];
$subIdArray = $_POST['subIdArray'];
// if the notificatiob button clicked which means the sub_id array has been passed to this page, 
// then we clear the notification counter
if (isset($stIdArray) && isset($subIdArray)) {
	
		// just making sure the array is not 0 so we can update last_seen to when notification last seen by teacher when it was new
	if(count($stIdArray) > 0 && count($subIdArray)){
		
	foreach ($stIdArray as $stId) {
		foreach ($subIdArray as $subId) {
			$sql_noti = "UPDATE notification SET last_seen  = now() WHERE st_id = ? AND sub_id = ?";
 			$stmt_noti = $db->prepare($sql_noti);
			$stmt_noti->bind_param('ii', $stId, $subId);
			$stmt_noti->execute();
	}
	}
}
}
else {
	header("location: login.php");
	exit;
}
?>