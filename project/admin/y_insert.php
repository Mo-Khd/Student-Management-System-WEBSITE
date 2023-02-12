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

$m = "";
$message = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

if(!empty($_POST['grade'])){
$grade = $_POST['grade'];

$sql = "SELECT * FROM year_table WHERE grade = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $grade);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0){
$sql = "INSERT INTO year_table (grade) VALUES (?)";
$stmt= $db->prepare($sql);
$stmt->bind_param("i", $grade);
$stmt->execute();

$m = 1;
$message = "Thanks. Grade " .$grade. " created. To link subject to it, please go to <a href='sub_insert.php'>Add subject</a> page.";
}
else{
$m = 0;
$message = "Grade " .$grade. " already exist. To link subject to it, please go to <a href='sub_insert.php'>Add subject</a> page";
}
}
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>admin pannel</title>

<link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">

<link rel="stylesheet" type="text/css" href="../css/menu-admin.css">

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


.btn-primary:hover, .btn-primary:focus {
  background-color: #8F5CBF;
}

.btn-primary {
  background-color: #a57ccb;
}

.btn.btn-md {
  padding-top: 10px;
  padding-bottom: 10px;
  padding-left: 25px;
  padding-right: 25px;
}

.btn.btn-md {
  border: none;
  }

input {
    background-color: #ebf2fa;
    border-color: #a57ccb;
    padding: 11px 11px;
    margin: 1px 1px;
}

.message0 {
color: red;
}

.message1 {
color: green;
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
    <button class="subnav1btn">Update<i class="fa fa-caret-down"></i></button>
    <div class="subnav1-content">
    <a  href="student_mark.php">Update student mark</a>
      <a href="student_info.php">Update student info</a>
      <a href="teacher_info.php">Update teacher info</a>
       
    </div>
  </div> 
    
  <div class="subnav1">
    <button class="subnav1btn" style="background-color: #a57ccb;">Insert<i class="fa fa-caret-down"></i></button>
    <div class="subnav1-content">
      <a href="sub_insert.php">Add subject</a>
       <a class="selected1" href="y_insert.php">Add grade</a>
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

<form class="mt-4 ml-2" method="POST" name="grade" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="number" name="grade" placeholder="GRADE *" min="1" required/>
    <button class="btn btn-primary btn-md" name="submit" type="submit" onclick="return clicked();">Add subject</button>
</form>

<div class="d-flex mt-2">
<div class="border">
<?php echo '<p class="message' .$m. '">' .$message. '</p>'; ?>
</div>
</div>


</body>
</html>