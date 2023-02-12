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

$message_insert = "";
$message_update = "";
$message_delete = "";
$message_mark_delete = "";

$subjects = array();

$st_id;

if($_SERVER["REQUEST_METHOD"] == "POST"){

$st_id = $_POST["student"];
$grade = $_POST["grade"];

$sql = "SELECT DISTINCT sub_name FROM subject_table";
            $stmt = mysqli_query($db, $sql);
            
            while($row = mysqli_fetch_assoc($stmt)){
            $subjects[] = $row["sub_name"];
    	    }
    	    
if(isset($_POST["delete"])){
foreach($subjects as $subject){
$sql = "UPDATE $subject SET half_of_the_first_semester = null, end_of_the_first_semester = null, first_semester_result = null, half_of_the_second_semester = null, end_of_the_second_semester = null, second_semester_result = null, year_end_average = null WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $st_id);
$stmt->execute();
}
$message_mark_delete = "All marks are deleted for Student ID " .$st_id;
}

$sub_id;

$sql = "SELECT * FROM year_table WHERE grade = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $grade);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$year_id = $row["year_id"];

$subject_array_insert = "";
$subject_array_update = "";
$subject_array_delete = "";
$marksheet_insert = -1;
$marksheet_update = -1;
$marksheet_delete = -1;
      
foreach($subjects as $subject){
$sql = "SELECT * FROM subject_table WHERE sub_name = ? AND year_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('si', $subject, $year_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($result->num_rows == 1) {
$sub_id = $row["subject_id"];

$sql = "SELECT * FROM $subject WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $st_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
$sql = "INSERT INTO $subject (st_id, sub_id) VALUES (?,?)";
$stmt= $db->prepare($sql);
$stmt->bind_param("ii", $st_id, $sub_id);
$stmt->execute();

$subject_array_insert = $subject. ", " .$subject_array_insert;
$marksheet_insert = 1;
}
else{
$sql = "UPDATE $subject SET sub_id = ? WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('ii', $sub_id, $st_id);
$stmt->execute();

$subject_array_update = $subject. ", " .$subject_array_update;
$marksheet_update = 1;
}

}
else{ // if subject for the new chosen grade not available, then delete marksheet for that subject for that student
$sql = "SELECT * FROM $subject WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $st_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {

$sql = "DELETE FROM $subject WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $st_id);
$stmt->execute();

$subject_array_delete = $subject. ", " .$subject_array_delete;
$marksheet_delete = 1;
}
}
}

if($marksheet_insert == 1)
$message_insert = 'Thanks. ' .$subject_array_insert. ' marksheets created for Student ID ' .$st_id. ' in grade ' .$grade;
if($marksheet_update == 1)
$message_update = 'Thanks. ' .$subject_array_update. ' marksheets updated for Student ID ' .$st_id. ' in grade ' .$grade;
if($marksheet_delete == 1)
$message_delete = 'Thanks. ' .$subject_array_delete. ' marksheets deleted for Student ID ' .$st_id. ' in the previous grade';
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>admin pannel</title>

<link rel="stylesheet" type="text/css" href="../css/menu-admin.css">

<link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">


<link rel="stylesheet" type="text/css" href="../vendor/animate/animate.css">

<link rel="stylesheet" type="text/css" href="../vendor/select2/select2.min.css">

<link rel="stylesheet" type="text/css" href="../vendor/perfect-scrollbar/perfect-scrollbar.css">

<link rel="stylesheet" type="text/css" href="../css/util.css">
<link rel="stylesheet" type="text/css" href="../css/main.css">

<script type="text/javascript">
    function clicked() {
       if (confirm('Are you sure?')) {
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

.form-control {
width: auto;
}

.btn.btn-md {
color: white;
  background-color: #a57ccb;
  -webkit-box-shadow: 0 2px 0px 0 rgba(0, 0, 0, 0.2);
  box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.2);
  -webkit-transition: .3s all ease;
  -o-transition: .3s all ease;
  transition: .3s all ease;
}

</style>

</head>
<body>

<!-- starting menu -->
<div class="form1">
<div class="navbar1">
  <a href="index.php">Home</a>
    <a href="validate.php">Validate</a>
    <a class="selected1" href="student_sheet.php">Marksheet</a>
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


<div class="container mt-5">
<form class="d-flex justify-content-center" method="POST" name="sheet" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

<div class="row">
<div class="col">
Create marksheet for students and update their grades
</div>
</div>
</div>


<div class="container d-flex justify-content-center mt-4">
<div class="row">
<div class="col-4">
<div class="form-group">
 <select class="form-control" name="student" required>
<?php
$sql = "SELECT * FROM student";
$stmt = mysqli_query($db, $sql); 

        echo '<option disabled selected value>--Student ID--</option>';

while($row = mysqli_fetch_assoc($stmt))
    {
        echo '<option value="' .$row["st_id"]. '">' .$row["st_id"]. '</option>';
    }
    ?>
    </select>
    </div>
</div>

<div class="col-3">
<div class="form-group">
      <select class="form-control" name="grade" required>
<?php
$sql = "SELECT * FROM year_table";
$stmt = mysqli_query($db, $sql); 

        echo '<option disabled selected value>--Grade--</option>';
while($row = mysqli_fetch_assoc($stmt))
    {
        echo '<option value="' .$row["grade"]. '">' .$row["grade"]. '</option>';
    }
?>
</select>
</div>
</div>

<div class="col">
<div class="form-group">
<button class="btn btn-md" name="submit" type="submit" onclick="return clicked();">Update marksheets</button></br>
</div>
</div>
</div>
</div>

<div class="container d-flex justify-content-center">
<div class="row">
<div class="col-1" style="margin:5px">
<input type="checkbox" id="delete" name="delete">
</div>
<div class="col">
<label for="delete"> Also delete all of the student's marks</label>
</div>
</div>
</div>

</form>

<div class="d-flex justify-content-center mt-2">
<div class="border">
<p> <?php echo $message_mark_delete ?> </p>
<p> <?php echo $message_insert ?> </p>
<p> <?php echo $message_update ?> </p>
<p> <?php echo $message_delete ?> </p>
</div>
</div>


<div class="limiter">
<div class="container-table100">
<div class="wrap-table100">
<div class="table100 ver1 m-b-110">

<div class="table100-head">
<table>
<thead>
  <tr>
     <th>Student ID</th>
     <th>First name</th>
     <th>Last name</th>
     <th>Gender</th>
     <th>Address</th>
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
          $sql = "SELECT st_id, first_name, last_name, gender, address FROM student";
          $stmt = mysqli_query($db, $sql); 
          
           while ($row = mysqli_fetch_assoc($stmt)) {
            echo "<tr class='row100 body'>";
            foreach($row as $value) {
            echo "<td class='cell100 column'>" . $value . "</td>"; 
             }
           echo "</tr>";
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