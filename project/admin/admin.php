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

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>admin pannel</title>

<link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">

<link rel="stylesheet" type="text/css" href="../css/menu-admin.css">

<style>
body {
  font-family: Arial, Helvetica, sans-serif;

}

</style>
</head>
<body>

<!-- starting menu -->
<div class="form1">
<div class="navbar1">
  <a class="selected1" href="admin.php">Home</a>
    <a href="validate.php">Validate</a>
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

</body>
</html>