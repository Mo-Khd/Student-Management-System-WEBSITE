<?php

// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin_a"]) && $_SESSION["loggedin_a"] !== true){
    header("location: index.php");
    exit;
}

require_once "dbcon.php";

$st_id = 0;
$st_id = $_REQUEST['id'];
if($st_id == 0){
echo "This page will be redirected..";
header('Refresh: 1; URL=student_mark.php');
exit;
}
   
$mark = "";
$message = "";
$year_id;
$teacher_id;
$name = $_SESSION["username"];
$average = null;
$subject = $_REQUEST['sub'];
$grade = $_REQUEST['grade'];

$sql = "SELECT * FROM year_table WHERE grade = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $grade);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$year_id = $row["year_id"];

$sql = "SELECT * FROM subject_table WHERE sub_name = ? AND year_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('si', $subject, $year_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$teacher_id = $row["teacher_id"];
$sub_id = $row["subject_id"];

$sql = "SELECT * FROM teacher_table WHERE teacher_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$t_name = $row["teacher_name"];

?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    
<link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">

<link rel="stylesheet" type="text/css" href="../css/menu-admin.css">
    
    <script type="text/javascript">
    function clicked() {
       if (confirm('Are you sure you want to update mark?')) {
           yourformelement.submit();
       } else {
           return false;
       }
    }
    
    function clicked_clear() {
       if (confirm('Are you sure you want to clear mark?')) {
           yourformelement.submit();
       } else {
           return false;
       }
    }

</script>
    
    <style>
    
    
    form {
        margin: 50px 210px 210px;
    }
    
 .table-bordered td, .table-bordered th {
        border: 1px solid #c3c3c3;
}

th, td {
  padding: 10px;
}

th {
  color: #414141;
}

td {
color: #4f4f4f;
}

.Fails3,  .Fails6, .Fails7{
   font-weight: bold;
}

.Passs3,  .Passs6, .Passs7{
   font-weight: bold;
}

.s3,  .s6, .s7{
   font-weight: bold;
}

.s3,  .s6, .s7{
   font-weight: bold;
}

.Fails7{
   color: red;
}

.Passs7{
   color: green;
}

.btn.btn-lg {
  border-radius: 4px;
  border: none;
  letter-spacing: .02rem;
  -webkit-box-shadow: 0 15px 30px 0 rgba(0, 0, 0, 0.2);
  box-shadow: 0 15px 30px 0 rgba(0, 0, 0, 0.2);
  -webkit-transition: .3s all ease;
  -o-transition: .3s all ease;
  transition: .3s all ease;
}

.btn.btn-light {
  border-radius: 4px;
  font-size: 13px;
  margin: 4px 2px;
  border: none;
  letter-spacing: .02rem;
  -webkit-box-shadow: 1px 1px 1px 1px rgba(0, 0, 0, 0.2);
  box-shadow: 1px 1px 1px 1px rgba(0, 0, 0, 0.2);
  -webkit-transition: .3s all ease;
  -o-transition: .3s all ease;
  transition: .3s all ease;
}

.btn-primary:hover, .btn-primary:focus {
  background-color: #8F5CBF;
}

.btn-primary {
  background-color: #a57ccb;
}

.btn.btn-lg {
  font-size: 18px;
  padding-top: 15px;
  padding-bottom: 15px;
  padding-left: 30px;
  padding-right: 30px;
}

.mb-3, my-3 {
margin-right: 0rem!important;
}

input[type='number']{
    text-align: center;
    width: 132px;
    height: 50px;
    background-color: #ebf2fa;
    border-color: #a57ccb;
    padding: 10px 10px;
    margin: 1px 1px;
    font-size: 14px;
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
    <a class="selected1" href="student_mark.php">Update student mark</a>
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


<?php

$update_clicked = -1;
if($_SERVER["REQUEST_METHOD"] == "POST"){
$update_clicked = 1;

$is_updated = false;

if(isset($_POST["submit"])){
if($_POST['half_first'] != null){
$value = $_POST['half_first'];
$sql = "UPDATE $subject SET half_of_the_first_semester = ? WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('di', $value, $st_id);
$stmt->execute();

$is_updated = true;
}
if($_POST['end_first'] != null){
$value = $_POST['end_first'];
$sql = "UPDATE $subject SET end_of_the_first_semester = ? WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('di', $value, $st_id);
$stmt->execute();

$is_updated = true;
}

if($_POST['half_second'] != null){
$value = $_POST['half_second'];
$sql = "UPDATE $subject SET half_of_the_second_semester = ? WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('di', $value, $st_id);
$stmt->execute();

$is_updated = true;
}

if($_POST['end_second'] != null){
$value = $_POST['end_second'];
$sql = "UPDATE $subject SET end_of_the_second_semester = ? WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('di', $value, $st_id);
$stmt->execute();

$is_updated = true;
}
}
else{
if(isset($_POST["clear_half_first"])){
$sql = "UPDATE $subject SET half_of_the_first_semester = null WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $st_id);
$stmt->execute();

$is_updated = true;
}
if(isset($_POST["clear_end_first"])){
$sql = "UPDATE $subject SET end_of_the_first_semester = null WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $st_id);
$stmt->execute();

$is_updated = true;
}
if(isset($_POST["clear_half_second"])){
$sql = "UPDATE $subject SET half_of_the_second_semester = null WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $st_id);
$stmt->execute();

$is_updated = true;
}
if(isset($_POST["clear_end_second"])){
$sql = "UPDATE $subject SET end_of_the_second_semester = null WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $st_id);
$stmt->execute();

$is_updated = true;
}
}

if($is_updated) {
$sql_noti = "SELECT * FROM notification WHERE st_id = ? AND sub_id = ?";
$stmt_noti = $db->prepare($sql_noti);
$stmt_noti->bind_param('ii', $st_id, $sub_id);
$stmt_noti->execute();
$result_noti = $stmt_noti->get_result();

if ($result_noti->num_rows > 0) {
$sql_noti = "UPDATE notification SET user = ?, last_updated = now() WHERE st_id = ? AND sub_id = ?";
$stmt_noti = $db->prepare($sql_noti);
$stmt_noti->bind_param('sii', $name, $st_id, $sub_id);
$stmt_noti->execute();
}
else {
$sql_noti = "INSERT INTO notification (user, st_id, sub_id)  VALUES (?, ?, ?)";
$stmt_noti = $db->prepare($sql_noti);
$stmt_noti->bind_param('sii', $name, $st_id, $sub_id);
$stmt_noti->execute();
}
}

}

$sql = "SELECT * FROM $subject WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $st_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$first_result = $second_result = $average = -1;

if($row['half_of_the_first_semester'] !== null && $row['end_of_the_first_semester'] !== null){
$first_result = $row['half_of_the_first_semester'] + $row['end_of_the_first_semester'];
}
else{
$first_result = -1;
}


if($row['half_of_the_second_semester'] !== null && $row['end_of_the_second_semester'] !== null){
$second_result = $row['half_of_the_second_semester'] + $row['end_of_the_second_semester'];
}
else{
$second_result = -1;
}

if($row['first_semester_result'] !== null && $row['second_semester_result'] !== null){
$average = $row['first_semester_result'] + $row['second_semester_result'];
}
else{
$average = -1;
}

if($first_result != -1){
$sql = "UPDATE $subject SET first_semester_result = ? WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('di', $first_result, $st_id);
$stmt->execute();
}
else{
$sql = "UPDATE $subject SET first_semester_result = null WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $st_id);
$stmt->execute();
}
if($second_result != -1){
$sql = "UPDATE $subject SET second_semester_result = ? WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('di', $second_result, $st_id);
$stmt->execute();
}
else{
$sql = "UPDATE $subject SET second_semester_result = null WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $st_id);
$stmt->execute();
}
if($average != -1){
$sql = "UPDATE $subject SET year_end_average = ? WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('di', $average, $st_id);
$stmt->execute();

if($update_clicked == 1)
header("Refresh:0");
}
else{
$sql = "UPDATE $subject SET year_end_average = null WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $st_id);
$stmt->execute();

if($update_clicked == 1)
header("Refresh:0");
}

if($average < 100 && $average != -1)
$mark = "Fail";
elseif($average >= 100)
$mark = "Pass";
else
$mark = "";



echo '<form class="text-center" name="update" method="POST" action=""> ';
echo "<table class='table table-bordered'>";
$sql = "SELECT half_of_the_first_semester, end_of_the_first_semester, first_semester_result, half_of_the_second_semester, end_of_the_second_semester, second_semester_result, year_end_average FROM $subject WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $st_id);
$stmt->execute();
$result = $stmt->get_result();

echo "
  <tr>
     <th class='vertical' colspan='4'></th>
     <th class='vertical'>Half of the first semester</th>
     <th class='vertical'>End of the first semester</th>
     <th class='vertical'>First semester results</th>
     <th class='vertical'>Half of the second semester</th>
     <th class='vertical'>End of the Second semester</th>
     <th class='vertical'>Second semester results</th>
     <th class='vertical'>End year average</th>
    </tr>
            ";
 echo "
  <tr>
     <th>Student ID</th>
     <th>Student grade</th>
     <th>Subject</th>
     <th>Teacher</th>
     <th>20</th>
     <th>80</th>
     <th>100</th>
     <th>20</th>
     <th>80</th>
     <th>100</th>
     <th>200</th>
    </tr>
    ";
    
$row = $result->fetch_assoc(); 
    
    echo "<tr>";
     echo "<td>" .$st_id. "</td><td>" .$grade. "</td><td>" .$subject. "</td><td>" .$t_name. "</td>";
     $count = 1;
    foreach($row as $value) {
        echo "<td class='" .$mark. "s" .$count. "'>" . $value . "</td>";
            $count++;
            }
            echo "</tr>";

        ?>
        <p><a href="student_mark.php">Go back</a></p>

      <tr>
        <td colspan="4" rowspan="4"></td>
        <td> 
        <input type="number" name="half_first" placeholder="UPDATE HERE" min="0" max="20" step="0.01">
        </td>
        <td>
          <input type="number" name="end_first" placeholder="UPDATE HERE" min="0" max="80" step="0.01">
        </td>
        <td rowspan="2">
          
        </td>
        <td>
          <input type="number" name="half_second" placeholder="UPDATE HERE" min="0" max="20" step="0.01">
        </td>
        <td>
          <input type="number" name="end_second" placeholder="UPDATE HERE" min="0" max="80" step="0.01">
        </td>
        <td>
          
        </td>
        <td>
          <div class="<?php echo $mark ?>s7"><?php echo  $mark ?></div>
        </td>
      </tr>        
    </tr>
    
     <tr>
        <td> 
        <button class="btn mb-3 mr-3 btn-light" name="clear_half_first" onclick="return clicked_clear();">Clear mark</button
        </td>
        <td>
          <button class="btn mb-3 mr-3 btn-light"name="clear_end_first" onclick="return clicked_clear();">Clear mark</button
        </td>

        <td>
          <button class="btn mb-3 mr-3 btn-light" name="clear_half_second" onclick="return clicked_clear();">Clear mark</button
        </td>
        <td>
          <button class="btn mb-3 mr-3 btn-light" name="clear_end_second" onclick="return clicked_clear();">Clear mark</button
        </td>
        <td colspan="2">          
        </td>
        
      </tr>        
    </tr>


          
 </table>
 <button class="btn btn-primary btn-lg" name="submit" value="Update"  onclick="return clicked();">Update</button>
 </form>

          <!-- JS -->
   
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/popper.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.js"></script>
	<script src="../vendor/jquery/jquery-3.6.3.slim.js"></script>
	<script src="../js/menu.js"></script>
</body>
</html>