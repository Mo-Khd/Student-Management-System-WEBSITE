<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(!isset($_SESSION["loggedin_a"]) && $_SESSION["loggedin_a"] !== true){
    header("location: index.php");
    exit;
}

$pass = 0;
$fail = 0;

$name = $_SESSION["username"];
 
require_once "dbcon.php";

$message = "If no message appears here after clicking the button, it means that there is no available data to evaluate for the chosen grade.";

if($_SERVER["REQUEST_METHOD"] == "POST"){

$grade = $_POST["grade"];

$subjects = array();
$st_ids = array();

$count_subject = 0;

$sql = "SELECT * FROM year_table WHERE grade = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $grade);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$year_id = $row["year_id"];

$sql = "SELECT DISTINCT sub_name FROM subject_table WHERE year_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $year_id);
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc()){
$subjects[] = $row["sub_name"];
$count_subject++;
}

$sql = "SELECT * FROM student";
$stmt = mysqli_query($db, $sql);
while($row = mysqli_fetch_assoc($stmt)){
$st_ids[] = $row["st_id"];
}

foreach($st_ids as $st_id){

$half_first_a = 0;
$end_first_a = 0;
$first_result_a = 0;
$half_second_a = 0;
$end_second_a = 0;
$second_result_a = 0;
$average_a = 0;

$average_half_first = 0;
$average_end_first = 0;
$average_first_result = 0;
$average_half_second = 0;
$average_end_second = 0;
$average_second_result = 0;
$average_average = 0;

$count_average = 0;
$count_result = 0;

$decision = "";
$end_decision = "";

foreach($subjects as $subject){

$sql = "SELECT * FROM subject_table WHERE year_id = ? AND sub_name = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('is', $year_id, $subject);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$sub_id = $row["subject_id"];

$sql = "SELECT * FROM $subject WHERE sub_id = ? AND st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('ii', $sub_id, $st_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if($result->num_rows == 1){
$half_first = $row['half_of_the_first_semester'];
$end_first = $row['end_of_the_first_semester'];
$first_result = $row['first_semester_result'];
$half_second = $row['half_of_the_second_semester'];
$end_second = $row['end_of_the_second_semester'];
$second_result = $row['second_semester_result'];
$average = $row['year_end_average'];

$half_first_a = $half_first + $half_first_a;
$end_first_a = $end_first + $end_first_a;
$first_result_a = $first_result + $first_result_a;
$half_second_a = $half_second + $half_second_a;
$end_second_a = $end_second + $end_second_a;
$second_result_a = $second_result + $second_result_a;
$average_a = $average + $average_a;


if($row['year_end_average'] !== null){
$count_average++;

if($row['year_end_average'] >= 100){
$count_result++;
$decision = "Pass";
}
else
$decision = "Fail";

$sql = "SELECT * FROM store_mark WHERE st_id = ? AND year_id = ? AND subject = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('iis', $st_id, $year_id, $subject);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
$sql = "INSERT INTO store_mark (st_id, year_id, subject, half_of_the_first_semester, end_of_the_first_semester, first_semester_result, half_of_the_second_semester, end_of_the_second_semester, second_semester_result, year_end_average, result) 
Values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt= $db->prepare($sql);
$stmt->bind_param("sssssssssss", $st_id, $year_id, $subject, $half_first, $end_first, $first_result, $half_second, $end_second, $second_result, $average, $decision);
$stmt->execute();

$message = "Thanks, the evaluation has been completed";
}
}
}

if($count_subject == $count_average){

$average_half_first = round(($half_first_a / $count_average), 1);
$average_end_first = round(($end_first_a / $count_average), 1);
$average_first_result = round(($first_result_a / $count_average), 1);
$average_half_second = round(($half_second_a / $count_average), 1);
$average_end_second = round(($end_second_a / $count_average), 1);
$average_second_result = round(($second_result_a / $count_average), 1);
$average_average = round(($average_a / $count_average), 1);

if($count_result == $count_average)
$end_decision = "Pass";
else
$end_decision = "Fail";

$sql = "SELECT * FROM mark_table WHERE st_id = ? AND year_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('ii', $st_id, $year_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if($result->num_rows == 0){
$sql = "INSERT INTO mark_table (st_id, year_id, half_of_the_first_semester, end_of_the_first_semester, first_semester_result, half_of_the_second_semester, end_of_the_second_semester, second_semester_result, year_end_average, result) 
Values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt= $db->prepare($sql);
$stmt->bind_param("ssssssssss", $st_id, $year_id, $average_half_first, $average_end_first, $average_first_result, $average_half_second, $average_end_second, $average_second_result, $average_average, $end_decision);
$stmt->execute();
}

$sql = "SELECT * FROM store_mark WHERE st_id = ? AND year_id = ? AND subject = ?";
$stmt = $db->prepare($sql);
$value = 'RESULT';
$stmt->bind_param('iis', $st_id, $year_id, $value);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if($result->num_rows == 0){
$sql = "INSERT INTO store_mark (st_id, year_id, subject, half_of_the_first_semester, end_of_the_first_semester, first_semester_result, half_of_the_second_semester, end_of_the_second_semester, second_semester_result, year_end_average, result) 
Values (?, ?, 'RESULT', ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt= $db->prepare($sql);
$stmt->bind_param("ssssssssss", $st_id, $year_id, $average_half_first, $average_end_first, $average_first_result, $average_half_second, $average_end_second, $average_second_result, $average_average, $end_decision);
$stmt->execute();
}
}

} 
}

$grade_exist = -1;
$pass = 0;
$fail = 0;
$new_st_ids_pass = array();
$new_st_ids_fail = array();
$new_subjects = array();

// changing students grade + 1 if the grade exist
$new_grade = $grade + 1;

$sql = "SELECT * FROM year_table WHERE grade = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $new_grade);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if($result->num_rows == 1){
$grade_exist = 1;

$new_year_id = $row["year_id"];

$sql = "SELECT DISTINCT sub_name FROM subject_table";
$stmt = $db->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc()){
$new_subjects[] = $row["sub_name"];
}

}
else{
$grade_exist = 0;
}

// changing students grade + 1 if the grade exist
$sql1 = "SELECT * FROM mark_table WHERE year_id = ?";
$stmt1 = $db->prepare($sql1);
$stmt1->bind_param('i', $year_id);
$stmt1->execute();
$result1 = $stmt1->get_result();
// here we are using a different variable names ($row1, $sql1 ..) because the while loop get confused with the other variable with the same name inside the while and it causes the while loop to run only once
while($row1 = $result1->fetch_assoc()){
$new_decision = $row1["result"];
$new_st_id = $row1["st_id"];

if($new_decision == 'Pass'){
$new_st_ids_pass[] = $new_st_id;

if($grade_exist == 1){
$pass = 2;

foreach($new_subjects as $new_subject){
$sql = "SELECT * FROM subject_table WHERE year_id = ? AND  sub_name = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('is', $new_year_id, $new_subject);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if($result->num_rows == 1){

$new_sub_id = $row["subject_id"];

$sql = "SELECT * FROM $new_subject WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $new_st_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
$sql = "INSERT INTO $new_subject (st_id, sub_id) VALUES (?, ?)";
$stmt= $db->prepare($sql);
$stmt->bind_param("ii", $new_st_id, $new_sub_id);
$stmt->execute();
}
else{
$sql = "UPDATE $new_subject SET sub_id = ?, half_of_the_first_semester = null, end_of_the_first_semester = null, first_semester_result = null, half_of_the_second_semester = null, end_of_the_second_semester = null, second_semester_result = null, year_end_average = null WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('ii', $new_sub_id, $new_st_id);
$stmt->execute();
}

}
else{
// if subject for the new chosen grade not available, then delete marksheet for that subject for that student
$sql = "DELETE FROM $new_subject WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $new_st_id);
$stmt->execute();
}

}

}
else{
$pass = 1;
}
}
elseif($new_decision == 'Fail'){
$fail = 1;
$new_st_ids_fail[] = $new_st_id;

foreach($subjects as $subject){
$sql = "UPDATE $subject SET half_of_the_first_semester = null, end_of_the_first_semester = null, first_semester_result = null, half_of_the_second_semester = null, end_of_the_second_semester = null, second_semester_result = null, year_end_average = null WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $new_st_id);
$stmt->execute();
}
}

$sql = "DELETE FROM mark_table WHERE year_id = ? AND st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('ii', $year_id, $new_st_id);
$stmt->execute();
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
  <a href="admin.php">Home</a>
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
    
  <a class="selected1" href="evaluation.php">Evaluation</a>
  
      <a style="float:right; margin-right: 20px;" href="logout.php">Logout</a>
  <p style="float:right; margin: 25px 5px 0 0; color:powderblue;" >Welcome, <?php echo $name; ?></p>
  
</div>
<!-- ending menu -->



<div class="container mt-5">
<form class="d-flex justify-content-center" method="POST" name="decision" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

<div class="row">
<div class="col mb-3">
Make final year evaluation for a specific grade
</div>
</div>
</div>

<div class="container d-flex justify-content-center mt-2">
<div class="row">
<div class="col">
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
<button class="btn btn-md" name="submit" type="submit" onclick="return clicked();">Evaluate</button></br>
</div>
</div>
</div>
</div>
</form>

<br>

<?php

echo '<div class="d-flex justify-content-center mt-5">
<div class="row">
<div class="col">';
echo $message;
echo '</div></div></div>';

echo "<br>";

if($pass == 2){
echo '<div class="container d-flex justify-content-center mt-2">
<div class="row">
<div class="col">';
echo '<div class="border">';
echo "Passed from grade " .$grade. " to grade " .$new_grade. " student IDs:</br>";
foreach($new_st_ids_pass as $new_st_id_pass){
echo "- " .$new_st_id_pass;
echo "<br>";
}
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
}
elseif($pass == 1){
echo '<div class="container d-flex justify-content-center">
<div class="row">
<div class="col">';
echo '<div class="border">';
echo "Passed from grade " .$grade. " student IDs:</br>";
foreach($new_st_ids_pass as $new_st_id_pass){
echo "- " .$new_st_id_pass;
echo "<br>";
}
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
}

echo "<br>";

if($fail == 1){
echo '<div class="container d-flex justify-content-center">
<div class="row">
<div class="col">';
echo '<div class="border">';
echo "Failed from grade " .$grade. " student IDs:</br>";
foreach($new_st_ids_fail as $new_st_id_fail){
echo "- " .$new_st_id_fail;
echo "<br>";
}
echo '<div>';
echo '<div>';
echo '<div>';
echo '<div>';
}


?>

</body>
</html>