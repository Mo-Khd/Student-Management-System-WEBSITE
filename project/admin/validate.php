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

$message = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        $teacher_id;
        $subject;
        $year_id;
        $grade;
        $teacher_name;
        
         $row_number = $_POST["row_number"];
         
         $button_check = "";
         
      for($i = 1; $i <= $row_number; $i++){
          
             if(isset($_POST["submit" .$i])){
            $button_check = "validate";
            $teacher_id = $_POST["teacher_id" .$i];
            $subject = $_POST["sub_name" .$i]; 
            $year_id = $_POST["year_id" .$i];
            $grade  = $_POST["grade" .$i];
            $teacher_name  = $_POST["teacher_name" .$i];
             }
        elseif(isset($_POST["delete" .$i])){
            $button_check = "delete";
            $teacher_id = $_POST["teacher_id" .$i];
            $subject = $_POST["sub_name" .$i]; 
            $year_id = $_POST["year_id" .$i];
        }
    }
    
        
        if($button_check == "validate"){
        $sql = "SELECT * FROM subject_table WHERE sub_name = ? AND year_id = ?";
        $stmt= $db->prepare($sql);
    	$stmt->bind_param("si", $subject, $year_id);
    	$stmt->execute();
    	$result = $stmt->get_result();
    	$row = $result->fetch_assoc();
  
        if ($result->num_rows > 0 && $row["teacher_id"] !== null) {
        $message = 'A teacher for Grade: <b>' .$grade. '</b> - <b>' .$subject. '</b>, is already exist in the SUBJECT table.<br> Please go to <a href="link.php">Link</a> page to link {Teacher ID: ' .$teacher_id. ' - ' .$teacher_name. '} to {Grade <b>' .$grade. '</b> - <b>' .$subject. '</b>}';
        echo '<style>
        .message {
            color: #b02a37 !important;
            }
            .message a{
            text-decoration: none;
            }
        </style>';
        }
        elseif($result->num_rows > 0 && $row["teacher_id"] === null){
        $sql = "UPDATE subject_table SET teacher_id = ? WHERE sub_name = ? AND year_id = ?";
    	$stmt= $db->prepare($sql);
    	$stmt->bind_param("isi", $teacher_id, $subject, $year_id);
    	$stmt->execute();
    	
    	$message = "Validated! {Teacher ID:" .$teacher_id. " - " .$teacher_name. "} is now teaching {" .$subject. "} to grade {" .$grade. "} students";
    	
    	$sql = "DELETE FROM temp_teacher WHERE sub_name = ? AND teacher_id = ? AND year_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('sii', $subject, $teacher_id, $year_id);
        $stmt->execute();
        }
        else{
        $sql = "INSERT INTO subject_table (sub_name, teacher_id, year_id) VALUES (?,?,?)";
    	$stmt= $db->prepare($sql);
    	$stmt->bind_param("sii", $subject, $teacher_id, $year_id);
    	$stmt->execute();
    	
    	$message = "Validated! {Teacher ID:" .$teacher_id. " - " .$teacher_name. "} is now teaching {" .$subject. "} to grade {" .$grade. "} students";
    	
    	$sql = "DELETE FROM temp_teacher WHERE sub_name = ? AND teacher_id = ? AND year_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('sii', $subject, $teacher_id, $year_id);
        $stmt->execute();
        }
        }
        elseif($button_check == "delete"){
        $sql = "DELETE FROM temp_teacher WHERE sub_name = ? AND teacher_id = ? AND year_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('sii', $subject, $teacher_id, $year_id);
        $stmt->execute();
        }
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Link</title>

<link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">

<link rel="stylesheet" type="text/css" href="../vendor/animate/animate.css">

<link rel="stylesheet" type="text/css" href="../vendor/select2/select2.min.css">

<link rel="stylesheet" type="text/css" href="../css/edit-delete.css">

<link rel="stylesheet" type="text/css" href="../vendor/perfect-scrollbar/perfect-scrollbar.css">

<link rel="stylesheet" type="text/css" href="../css/util.css">
<link rel="stylesheet" type="text/css" href="../css/main.css">

<link rel="stylesheet" type="text/css" href="../css/menu-admin.css">

<script type="text/javascript">
    function clicked() {
       if (confirm('Are you sure you want to validate?')) {
           yourformelement.submit();
       } else {
           return false;
       }
    }
    
    function clicked_delete() {
       if (confirm('Are you sure you want to delete?')) {
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


.submit {
  background: none;
  border: none;
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
      
      a.val {
         color: green !important;
      }

.message {
color: green;
}

</style>
</head>
<body>

<!-- starting menu -->
<div class="form1">
<div class="navbar1">
  <a href="admin.php">Home</a>
    <a class="selected1" href="validate.php">Validate</a>
    <a href="student_sheet.php">Marksheet</a>
  <a href="link.php">Link</a>
  <div class="subnav1">
    <button class="subnav1btn">Update<i class="fa fa-caret-down"></i></button>
    <div class="subnav1-content">
    <a href="student_mark.php">Update student mark</a>
      <a href="student_info.php">Update student info</a>
      <a href="teacher_info.php">Update teacher info</a>
       
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

<div class="d-flex justify-content-center mt-5">
Validate new registered teachers, link teachers to their subject and grade
</div>

<form method="POST" name="validate" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

<div class="limiter">
<div class="container-table100">
<div class="wrap-table100">
<div class="table100 ver1 m-b-110">

<div class="table100-head">
<table>
<thead>
<?php
    $sql = "SELECT temp_teacher.teacher_id, teacher_name, temp_teacher.year_id, grade, sub_name FROM temp_teacher, year_table, teacher_table WHERE temp_teacher.year_id = year_table.year_id AND temp_teacher.teacher_id = teacher_table.teacher_id";
    $stmt = mysqli_query($db, $sql); 
    
    
    if($stmt->num_rows > 0){
    echo "
  <tr>
    <th>Teacher ID</th>
    <th>Teacher name</th>
    <th>Year ID</th>
     <th>Grade</th>
     <th>Subject</th>
     <th>VALIDATE</th>
     <th>DELETE</th>
    </tr>
</thead>
</table>
</div>
    ";
    
    ?>
    <div class="table100-body js-pscroll">

<div class="container1">
  <div class="panel1">
    <div class="panel1-body">
<table class="table1">
<tbody>

<?php
    
    $row_number = 1;
    while($row = mysqli_fetch_assoc($stmt))
    {      
    
        $teacher_id  = $row["teacher_id"];
        $year_id  = $row["year_id"];
        $sub_name  = $row["sub_name"];
        $grade  = $row["grade"];
        $teacher_name  = $row["teacher_name"];
        
        echo '
        <input type="hidden" name="row_number" value="' .$row_number. '">
        <input type="hidden" name="teacher_id' .$row_number. '" value="' .$teacher_id. '">
        <input type="hidden" name="year_id' .$row_number. '" value="' .$year_id. '">
        <input type="hidden" name="sub_name' .$row_number. '" value="' .$sub_name. '">
        <input type="hidden" name="grade' .$row_number. '" value="' .$grade. '">
        <input type="hidden" name="teacher_name' .$row_number. '" value="' .$teacher_name. '">
        ';
        
      echo "<tr class='row100 body'>";
    foreach($row as $value) {
        echo "<td class='cell100 column'>" . $value . "</td>"; 
            }

echo '<td class="cell100 column"><button class="submit" name="submit' .$row_number. '" type="submit" onclick="return clicked();"><ul class="action-list"><li><a class="val" data-tip="validate"><i class="fa fa-check">Validate</i></a></li></ul></button></td>';
echo '<td class="cell100 column"><button class="delete" name="delete' .$row_number. '" type="submit" onclick="return clicked_delete();"><ul class="action-list"><li><a class="del" data-tip="delete"><i class="fa fa-trash">Delete</i></a></li></ul></button></td>';

echo "</tr>";
$row_number++;
    }

     
     echo "
     </tbody>
</table>
</div>

    </div>
  </div>
</div>

</div>
</div>
</div>
</div>";
     
     

echo "</form>";
}
else{
echo "<br>No new teacher found.";
}

?>

<div class="d-flex justify-content-center">
<div class="border">
<p class="message"><?php echo $message; ?></p>
</div>
</div>

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