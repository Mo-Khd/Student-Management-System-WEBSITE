<?php

// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin_a"]) && $_SESSION["loggedin_a"] !== true){
    header("location: index.php");
    exit;
}

require_once "dbcon.php";

$name = $_SESSION["username"];

$st_id = 0;
$st_id = $_REQUEST['id'];
if($st_id == 0){
echo "This page will be redirected..";
header('Refresh: 1; URL=student_info.php');
exit;
} 

if($_SERVER["REQUEST_METHOD"] == "POST"){
if($_POST['first_name'] != null){
$value = $_POST['first_name'];
$sql = "UPDATE student SET first_name = ? WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('si', $value, $st_id);
$stmt->execute();
}
if($_POST['last_name'] != null){
$value = $_POST['last_name'];
$sql = "UPDATE student SET last_name = ? WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('si', $value, $st_id);
$stmt->execute();
}

if($_POST['gender'] != null){
$value = $_POST['gender'];
$sql = "UPDATE student SET gender = ? WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('si', $value, $st_id);
$stmt->execute();
}

if($_POST['date'] != null){
$value = $_POST['date'];
$sql = "UPDATE student SET date_and_birth = ? WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('si', $value, $st_id);
$stmt->execute();
}
if($_POST['address'] != null){
$value = $_POST['address'];
$sql = "UPDATE student SET address = ? WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('si', $value, $st_id);
$stmt->execute();
}
}
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
       if (confirm('Are you sure you want to update student info?')) {
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
  letter-spacing: .02rem;
  -webkit-box-shadow: 0 15px 30px 0 rgba(0, 0, 0, 0.2);
  box-shadow: 0 15px 30px 0 rgba(0, 0, 0, 0.2);
  -webkit-transition: .3s all ease;
  -o-transition: .3s all ease;
  transition: .3s all ease;
}

.mb-3, my-3 {
margin-right: 0rem!important;
}

input {
    text-align: center;
    width: 132px;
    height: 25px;
    background-color: #ebf2fa;
    border-color: #a57ccb;
    padding: 10px 10px;
    margin: 1px 1px;
    font-size: 14px;
}

select{
   text-align: center;
    width: 123px;
    height: 25px;
    background-color: #ebf2fa;
    border-color: #a57ccb;
    font-size: 14px;
}

body {
            text-align: center;
        }

table {
  margin: auto;
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
    <a href="student_mark.php">Update student mark</a>
      <a class="selected1" href="student_info.php">Update student info</a>
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

<form class="text-center mt-5" method="POST" name="delete" action="">

<p><a href="student_info.php">Go back</a></p>

<table class="table table-bordered">
  <tr>
     <th>Student ID</th>
     <th>First name</th>
     <th>Last name</th>
     <th>Gender</th>
     <th>Date of birth</th>
     <th>Address</th>
    </tr>
    
<?php
$sql = "SELECT st_id, first_name, last_name, gender, date_and_birth, address FROM student WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $st_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc(); 
    
    echo "<tr>";
    foreach($row as $value) {
        echo "<td>" . $value . "</td>"; 
            }
            echo "</tr>";

        ?>
        <td>
        </td>
        <td>
          <input type="text" name="first_name" placeholder="UPDATE HERE"/>
        </td>
        <td>
          <input type="text" name="last_name" placeholder="UPDATE HERE"/>
        </td>
        <td>
        <select name="gender">
        <option disabled selected value>--Gender--</option>
          <option value="Male">Male</option>
           <option value="Female">Female</option>
            <option value="Other">Other</option>
           </select>
        </td>
         <td>
          <input type="date" name="date" placeholder="UPDATE HERE"/>
         </td>
         <td>
          <input type="text" name="address" placeholder="UPDATE HERE"/>
         </td>
        
         </table>
         <button class="btn btn-primary btn-lg" name="submit" onclick="return clicked();">Update</button>
</form>
                  
</body>
</html>