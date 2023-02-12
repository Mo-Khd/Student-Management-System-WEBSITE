<?php

session_start();
 
if(!isset($_SESSION["loggedin_t"]) || $_SESSION["loggedin_t"] !== true){
    header("location: login.php");
    exit;
}

// notifications
$sql_noti = "SELECT st_id, sub_id, user, last_updated, last_seen FROM notification, subject_table WHERE sub_id = subject_id AND teacher_id = ?";
$stmt_noti = $db->prepare($sql_noti);
$stmt_noti->bind_param('i', $id);
$stmt_noti->execute();
$result_noti = $stmt_noti->get_result();

$noti_num = 0;
if ($result_noti->num_rows > 0) {
$noti_num = 0;
 	while($row_noti = mysqli_fetch_assoc($result_noti)) {
      	if($row_noti['last_updated'] > $row_noti['last_seen'])
			$noti_num ++;
	}

echo '
<script src="../vendor/jquery/jquery-3.6.3.min.js"></script>
<script src="../js/notification.handling.js"></script>
';
}

echo '
<script>
var noti_num = '.$noti_num.';
</script>
';
//end-notifications
?>