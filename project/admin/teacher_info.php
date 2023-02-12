<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(!isset($_SESSION["loggedin_a"]) && $_SESSION["loggedin_a"] !== true){
    header("location: index.php");
    exit;
}

$name = $_SESSION["username"];
 
require_once "dbcon.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $button_check = "";
    $t_id;
    $row_number = $_POST["row_number"];
    for($i = 1; $i <= $row_number; $i++){
      if(isset($_POST["delete" .$i])){
            $button_check = "delete";
            $t_id = $_POST["t_id" .$i];
        }
}
if($button_check == "delete"){
        $sql = "DELETE FROM teacher_table WHERE teacher_id = ?";
        $stmt = $db->prepare($sql);
    	$stmt->bind_param("i", $t_id);
    	$stmt->execute();
        }

}

$sql = "SELECT teacher_id, teacher_name, gender, date_of_birth, address FROM teacher_table";
$stmt = mysqli_query($db, $sql); 

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>admin pannel</title>

    <link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" type="text/css" href="../css/edit-delete.css">
    <link rel="stylesheet" type="text/css" href="../vendor/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="../vendor/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="../vendor/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" type="text/css" href="../css/util.css">
    <link rel="stylesheet" type="text/css" href="../css/main.css">

    <link rel="stylesheet" type="text/css" href="../css/menu-admin.css">

<script type="text/javascript">

    function clicked_delete() {
       if (confirm('Are you sure you want to delete teacher?')) {
           yourformelement.submit();
       } else {
           return false;
       }
    }

</script>
<style>

.table100.ver1 th {
    background-color: #a57ccb;
}

button {
      background: none !important;
     border:none !important;
     outline:none !important;
     box-shadow: none !important;
    }
  
  button:active,
  button:focus,
  button:hover{
     background: none !important;
     border:none !important;
     outline:none !important;
     box-shadow: none !important;
     }
     
     a.del {
         color: red !important;
      }
      
      a.ed {
         color: #deb12b !important;
      }

</style>
</head>
<body>
<!-- starting menu -->
<div class="form1">
<div class="navbar1">
  <a href="admin.php">Home</a>
    <a href="validate.php">Validate</a>
    <a href="student_sheet.php">Marksheet</a>
  <a href="link.php">Link</a>
  <div class="subnav1">
    <button class="subnav1btn" style="background-color: #a57ccb;">Update<i class="fa fa-caret-down"></i></button>
    <div class="subnav1-content">
    <a  href="student_mark.php">Update student mark</a>
      <a href="student_info.php">Update student info</a>
      <a class="selected1" href="teacher_info.php">Update teacher info</a>
       
    </div>
  </div> 
    
  <div class="subnav1">
    <button class="subnav1btn">Insert<i class="fa fa-caret-down"></i></button>
    <div class="subnav1-content">
      <a href="sub_insert.php">Add subject</a>
       <a href="y_insert.php">Add grade</a>
    </div>
  </div> 
  
  <div class="subnav1">
    <button class="subnav1btn">Delete<i class="fa fa-caret-down"></i></button>
    <div class="subnav1-content">
      <a href="sub_delete.php">Delete subject</a>
    </div>
    </div>
    
  <a href="evaluation.php">Evaluation</a>
  
      <a style="float:right; margin-right: 20px;" href="logout.php">Logout</a>
  <p style="float:right; margin: 25px 5px 0 0; color:powderblue;" >Welcome, <?php echo $name; ?></p>
  
</div>
<!-- ending menu -->

<form class="mt-5" method="POST" name="delete" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

<div class="limiter">
<div class="container-table100">
<div class="wrap-table100">
<div class="table100 ver1 m-b-110">

<div class="table100-head">
<table>
<thead>
<tr>
     <th>Teacher ID</th>
     <th>Full name</th>
     <th>Gender</th>
     <th>Date of birth</th>
     <th>Adress</th>
     <th>UPDATE INFO</th>
     <th>DELETE</th>
    </tr>
    </thead>
</table>
</div>


<div class="table100-body js-pscroll">

<div class="container1">
  <div class="panel1">
    <div class="panel1-body">
<table class="table1">
<tbody>

<?php

$row_number = 1;
while ($row = mysqli_fetch_assoc($stmt)) {
        
           $t_id  = $row["teacher_id"];
            echo '<p><input type="hidden" name="t_id'  .$row_number. '" value="' .$t_id. '"></p>';
          
            echo "<tr class='row100 body'>";
            foreach($row as $value) {
            echo "<td class='cell100 column'>" . $value . "</td>"; 
             }
           echo "<td class='cell100 column'> <ul class='action-list'>";
           echo "<li><a class='ed' href='t_update.php?id=" .$row['teacher_id']. "'data-tip='edit'><i class='fa fa-edit'></i></a></li></ul></td>";
            echo '
            <input type="hidden" name="row_number" value="' .$row_number. '">
             <td class="cell100 column"><button class="delete" name="delete' .$row_number. '" type="submit" onclick="return clicked_delete();"><ul class="action-list"><li><a class="del" data-tip="delete"><i class="fa fa-trash"></i></a></li></ul></button></td>
            ';

             echo "</tr>";
            
            $row_number++;
          }
?>

</tbody>
</table>
</div>

    </div>
  </div>
</div>

</div>
</div>
</div>
</div>

</form>
    
      <!-- JS -->
   
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/popper.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.js"></script>
	<script src="../vendor/jquery/jquery-3.6.3.slim.js"></script>
	<script src="../js/menu.js"></script>
	
	<script src="../vendor/select2/select2.min.js"></script>

<script src="../vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>

</body>
</html>