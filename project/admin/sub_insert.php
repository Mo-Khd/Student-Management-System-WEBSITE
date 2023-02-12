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

$teacher_id = $_POST['teacher'];

if($_POST['subject'] != null && !empty($_POST['grade'])){
$subject = trim($_POST['subject']);
$grade = $_POST['grade'];

$year_id;

$sql = "SELECT * FROM year_table WHERE grade = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $grade);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($result->num_rows == 0){
$sql = "INSERT INTO year_table (grade) VALUES (?)";
$stmt= $db->prepare($sql);
$stmt->bind_param("i", $grade);
$stmt->execute();

$sql = "SELECT * FROM year_table WHERE grade = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $grade);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
}
$year_id = $row["year_id"];

$sql = "SELECT * FROM subject_table WHERE sub_name = ? AND year_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('si', $subject, $year_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0){
$sql = "INSERT INTO subject_table (sub_name, year_id, teacher_id) VALUES (?, ?, ?)";
$stmt= $db->prepare($sql);
$stmt->bind_param("sii", $subject, $year_id, $teacher_id);
$stmt->execute();

if($teacher_id == null){
$m = 1;
$message = "Thanks. Grade " .$grade. " " .$subject. ", created. To link teacher to it, please go to <a href='link.php'>Link</a> page.";
}
else{
$m = 1;
$message = "Thanks. Grade " .$grade. " " .$subject. ", created and linked to teacher id " .$teacher_id;
}
}
else{
$m = 0;
$message = "Grade " .$grade. " " .$subject. ", already exist. To link teacher to it, please go to <a href='link.php'>Link</a> page.";
}
}
}

$sql = "SELECT * FROM teacher_table";
$stmt = mysqli_query($db, $sql); 

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
    
    function capitalizeFirstLetter(input) {
        var value = input.value;
        value = value.charAt(0).toUpperCase() + value.slice(1);
        input.value = value;
    }

</script>

<style>

form {
margin-left: 10px;
}

input[type='reset'] {
  background-color: #ebf2fa;
  color: grey;
  padding: 5px 10px;
  margin: 4px 2px;
  cursor: pointer;
  font-size: 16px;
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

.btn.btn-lg {
  border-radius: 4px;
  border: none;
  }

input {
    background-color: #ebf2fa;
    border-color: #a57ccb;
    padding: 10px 10px;
    margin: 1px 1px;
    font-size: 14px;
}

select{
   text-align: center;
    background-color: #ebf2fa;
    border-color: #a57ccb;
    color: grey;
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
      <a class="selected1" href="sub_insert.php">Add subject</a>
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

<form class="mt-4" method="POST" name="subject" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
<p>Please make sure that the table exists for the subject you insert here, else you MUST create the table in the database manually</p>

    <input type="text" name="subject" placeholder="SUBJECT *" oninput="capitalizeFirstLetter(this)" required/></br>
    <input type="number" name="grade" placeholder="GRADE *" min="1" required/>
    <select name="teacher" id="optional">
    <option disabled selected value>TEACHER (Iid - name)</option> 
    <?php
    while($row = mysqli_fetch_assoc($stmt))
    {
     echo '<option value="' .$row["teacher_id"]. '">' .$row["teacher_id"]. ' - ' .$row["teacher_name"]. '</option>'; 
    }
    ?>
    </select>
    <label for="optional">(Optional)</label>
    </br>
    <button class="btn btn-primary btn-lg mt-1" name="submit" type="submit" onclick="return clicked();">Add subject</button>
    </br>
    <input class="btn btn-md" type="reset" value="Reset" />
</form>

<div class="d-flex mt-2">
<div class="border">
<?php echo '<p class="message' .$m. '">' .$message. '</p>'; ?>
</div>
</div>

</body>
</html>