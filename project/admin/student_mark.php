<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin_a"]) && $_SESSION["loggedin_a"] !== true){
    header("location: index.php");
    exit;
}

require_once "dbcon.php";

$grade = $subject = $sub = $st_id = 0;
$name = $_SESSION["username"];
$subjects = array();
          
$sql = "SELECT DISTINCT sub_name FROM subject_table";
            $stmt = mysqli_query($db, $sql);
            
            while($row = mysqli_fetch_assoc($stmt)){
            $subjects[] = $row["sub_name"];
    	    }
    ?>
 
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    
    <link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    
    <link rel="stylesheet" type="text/css" href="../css/edit-delete.css">
    <link rel="stylesheet" type="text/css" href="../vendor/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="../vendor/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="../vendor/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" type="text/css" href="../css/util.css">
    <link rel="stylesheet" type="text/css" href="../css/main.css">
    
    <link rel="stylesheet" type="text/css" href="../css/menu-admin.css">
    
     <style>
     
.table100.ver1 th {
    background-color: #a57ccb;
}

.form-control {
width: auto;
}

.btn.btn-md {
color: #838383;
  background-color: ##afadad;
  -webkit-box-shadow: 0 2px 0px 0 rgba(0, 0, 0, 0.2);
  box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.2);
  -webkit-transition: .3s all ease;
  -o-transition: .3s all ease;
  transition: .3s all ease;
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

<div class="container mt-5">
 <form class="d-flex justify-content-center" method="POST" name="filter" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
 
 <div class="row">
<div class="col">
Update students marksheet
</div>
</div>
</div>
 
 <div class="container d-flex justify-content-center mt-4">
<div class="row">
<div class="col">
<div class="form-group">
 <select class="form-control" name="student">
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

<div class="col">
<div class="form-group">
<select class="form-control" name="subject">
<?php

echo '<option disabled selected value>--Subject--</option>';
foreach($subjects as $subject){
        echo '<option value="' .$subject. '">' .$subject. '</option>';
    }

?>
</select>
</div>
</div>

<div class="col">
<div class="form-group">
<select class="form-control" name="grade">
<?php
$sql = "SELECT grade FROM year_table";
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
<button class="btn btn-md" name="filter" type="submit">Filter</button>
</div>
</div>

<div class="col">
<div class="form-group">
<button class="btn btn-md" name="reset" type="submit">Reset</button>
</div>
</div>

</form>
</div>
</div>

<div class="d-flex justify-content-center mt-3">
<p style="color:red;">ئەگەر قوتابیەک لەم لیستەی خوارەوەدا نەبوو ئەوە واتە کارتی وانەکەی زیاد نەکراوە، <a href="student_sheet.php">تکایە لێرە زیادی بکە</a></p>
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
     <th>Subject</th>
     <th>Grade</th>
     <th>First name</th>
     <th>Last name</th>
     <th>Gender</th>
     <th>Date of birth</th>
     <th>EDIT MARKS</th>
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

 if($_SERVER["REQUEST_METHOD"] == "POST"){
  if(isset($_POST["filter"])){
       $st_id = $_POST["student"];
       $sub = $_POST["subject"];
       $grade = $_POST["grade"];
    if(isset($_POST["student"]) && !isset($_POST["subject"]) && !isset($_POST["grade"])){
        foreach($subjects as $subject){
             $sql = "SELECT student.st_id, sub_name, grade, first_name, last_name, gender, date_and_birth FROM student, year_table, subject_table, $subject WHERE sub_id = subject_id AND subject_table.year_id = year_table.year_id AND student.st_id = $subject.st_id AND student.st_id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param('i', $st_id);
            $stmt->execute();
            $result = $stmt->get_result(); 

            while ($row = $result->fetch_assoc()) {

            echo "<tr class='row100 body'>";
            foreach($row as $value) {
            echo "<td class='cell100 column'>" . $value . "</td>"; 
             }
           echo "<td class='cell100 column'> <ul class='action-list'>";
            echo "<li><a class='ed' href='mark_update.php?id=" .$row['st_id']. "&sub=" .$row['sub_name']. "&grade=" .$row['grade']. "' data-tip='edit'><i class='fa fa-edit'></i></a></li></ul></td>"; 
            echo "</tr>";

          }
          }
    }
    elseif(!isset($_POST["student"]) && isset($_POST["subject"]) && !isset($_POST["grade"])){
        $sql = "SELECT student.st_id, sub_name, grade, first_name, last_name, gender, date_and_birth FROM student, year_table, subject_table, $sub WHERE sub_id = subject_id AND subject_table.year_id = year_table.year_id AND student.st_id = $sub.st_id AND sub_name = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param('s', $sub);
            $stmt->execute();
            $result = $stmt->get_result(); 

            while ($row = $result->fetch_assoc()) { 
    
            echo "<tr class='row100 body'>";
            foreach($row as $value) {
            echo "<td class='cell100 column'>" . $value . "</td>"; 
             }
           echo "<td class='cell100 column'> <ul class='action-list'>";
            echo "<li><a class='ed' href='mark_update.php?id=" .$row['st_id']. "&sub=" .$row['sub_name']. "&grade=" .$row['grade']. "' data-tip='edit'><i class='fa fa-edit'></i></a></li></ul></td>"; 
            echo "</tr>";
        }
}
elseif(!isset($_POST["student"]) && !isset($_POST["subject"]) && isset($_POST["grade"])){
        foreach($subjects as $subject){
        $sql = "SELECT student.st_id, sub_name, grade, first_name, last_name, gender, date_and_birth FROM student, year_table, subject_table, $subject WHERE sub_id = subject_id AND subject_table.year_id = year_table.year_id AND student.st_id = $subject.st_id AND grade = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param('i', $grade);
            $stmt->execute();
            $result = $stmt->get_result(); 

            while ($row = $result->fetch_assoc()) { 
    
            echo "<tr class='row100 body'>";
            foreach($row as $value) {
            echo "<td class='cell100 column'>" . $value . "</td>"; 
             }
           echo "<td class='cell100 column'> <ul class='action-list'>";
            echo "<li><a class='ed' href='mark_update.php?id=" .$row['st_id']. "&sub=" .$row['sub_name']. "&grade=" .$row['grade']. "' data-tip='edit'><i class='fa fa-edit'></i></a></li></ul></td>"; 
            echo "</tr>";

          }
        }
}
elseif(isset($_POST["student"]) && isset($_POST["subject"]) && !isset($_POST["grade"])){
        $sql = "SELECT student.st_id, sub_name, grade, first_name, last_name, gender, date_and_birth FROM student, year_table, subject_table, $sub WHERE sub_id = subject_id AND subject_table.year_id = year_table.year_id AND student.st_id = $sub.st_id AND sub_name = ? AND student.st_id = ?";
        $stmt= $db->prepare($sql);
    	$stmt->bind_param("si", $sub, $st_id);
    	$stmt->execute();
    	$result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) { 
    
             echo "<tr class='row100 body'>";
            foreach($row as $value) {
            echo "<td class='cell100 column'>" . $value . "</td>"; 
             }
           echo "<td class='cell100 column'> <ul class='action-list'>";
            echo "<li><a class='ed' href='mark_update.php?id=" .$row['st_id']. "&sub=" .$row['sub_name']. "&grade=" .$row['grade']. "' data-tip='edit'><i class='fa fa-edit'></i></a></li></ul></td>"; 
            echo "</tr>";

          }
}
elseif(isset($_POST["student"]) && !isset($_POST["subject"]) && isset($_POST["grade"])){
        foreach($subjects as $subject){
        $sql = "SELECT student.st_id, sub_name, grade, first_name, last_name, gender, date_and_birth FROM student, year_table, subject_table, $subject WHERE sub_id = subject_id AND subject_table.year_id = year_table.year_id AND student.st_id = $subject.st_id AND grade = ? AND student.st_id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param('ii', $grade, $st_id);
            $stmt->execute();
            $result = $stmt->get_result(); 

            while ($row = $result->fetch_assoc()) { 
    
            echo "<tr class='row100 body'>";
            foreach($row as $value) {
            echo "<td class='cell100 column'>" . $value . "</td>"; 
             }
           echo "<td class='cell100 column'> <ul class='action-list'>";
            echo "<li><a class='ed' href='mark_update.php?id=" .$row['st_id']. "&sub=" .$row['sub_name']. "&grade=" .$row['grade']. "' data-tip='edit'><i class='fa fa-edit'></i></a></li></ul></td>"; 
            echo "</tr>";

          }
          }
}
elseif(!isset($_POST["student"]) && isset($_POST["subject"]) && isset($_POST["grade"])){
        $sql = "SELECT student.st_id, sub_name, grade, first_name, last_name, gender, date_and_birth FROM student, year_table, subject_table, $sub WHERE sub_id = subject_id AND subject_table.year_id = year_table.year_id AND sub_name = ? AND grade = ? AND student.st_id = $sub.st_id";
            $stmt = $db->prepare($sql);
            $stmt->bind_param('si', $sub, $grade);
            $stmt->execute();
            $result = $stmt->get_result(); 

            while ($row = $result->fetch_assoc()) {
    
             echo "<tr class='row100 body'>";
            foreach($row as $value) {
            echo "<td class='cell100 column'>" . $value . "</td>"; 
             }
           echo "<td class='cell100 column'> <ul class='action-list'>";
            echo "<li><a class='ed' href='mark_update.php?id=" .$row['st_id']. "&sub=" .$row['sub_name']. "&grade=" .$row['grade']. "' data-tip='edit'><i class='fa fa-edit'></i></a></li></ul></td>"; 
            echo "</tr>";

          }
}
elseif(isset($_POST["student"]) && isset($_POST["subject"]) && isset($_POST["grade"])){
        $sql = "SELECT student.st_id, sub_name, grade, first_name, last_name, gender, date_and_birth FROM student, year_table, subject_table, $sub WHERE sub_id = subject_id AND subject_table.year_id = year_table.year_id AND sub_name = ? AND grade = ? AND student.st_id = $sub.st_id AND student.st_id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param('sii', $sub, $grade, $st_id);
            $stmt->execute();
            $result = $stmt->get_result(); 

            while ($row = $result->fetch_assoc()) {
    
             echo "<tr class='row100 body'>";
            foreach($row as $value) {
            echo "<td class='cell100 column'>" . $value . "</td>"; 
             }
           echo "<td class='cell100 column'> <ul class='action-list'>";
            echo "<li><a class='ed' href='mark_update.php?id=" .$row['st_id']. "&sub=" .$row['sub_name']. "&grade=" .$row['grade']. "' data-tip='edit'><i class='fa fa-edit'></i></a></li></ul></td>"; 
            echo "</tr>";

          }
}
else{
echo "No column is selected.";
}

}
else if(isset($_POST["reset"])){
 foreach($subjects as $subject){
            $sql = "SELECT student.st_id, sub_name, grade, first_name, last_name, gender, date_and_birth FROM student, year_table, subject_table, $subject WHERE sub_id = subject_id AND subject_table.year_id = year_table.year_id AND student.st_id = $subject.st_id";
            $stmt = mysqli_query($db, $sql); 

            while ($row = mysqli_fetch_assoc($stmt)) {
    
            echo "<tr class='row100 body'>";
            foreach($row as $value) {
            echo "<td class='cell100 column'>" . $value . "</td>"; 
             }
           echo "<td class='cell100 column'> <ul class='action-list'>";
            echo "<li><a class='ed' href='mark_update.php?id=" .$row['st_id']. "&sub=" .$row['sub_name']. "&grade=" .$row['grade']. "' data-tip='edit'><i class='fa fa-edit'></i></a></li></ul></td>"; 
            echo "</tr>";    
}
        }
}

}
else{
            
            foreach($subjects as $subject){
            $sql = "SELECT student.st_id, sub_name, grade, first_name, last_name, gender, date_and_birth FROM student, year_table, subject_table, $subject WHERE sub_id = subject_id AND subject_table.year_id = year_table.year_id AND student.st_id = $subject.st_id";
            $stmt = mysqli_query($db, $sql); 

            while ($row = mysqli_fetch_assoc($stmt)) {
    
            echo "<tr class='row100 body'>";
            foreach($row as $value) {
            echo "<td class='cell100 column'>" . $value . "</td>"; 
             }
           echo "<td class='cell100 column'> <ul class='action-list'>";
            echo "<li><a class='ed' href='mark_update.php?id=" .$row['st_id']. "&sub=" .$row['sub_name']. "&grade=" .$row['grade']. "' data-tip='edit'><i class='fa fa-edit'></i></a></li></ul></td>"; 
            echo "</tr>";
    
}
        }
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