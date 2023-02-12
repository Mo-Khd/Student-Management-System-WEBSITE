<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin_t"]) || $_SESSION["loggedin_t"] !== true){
    header("location: login.php");
    exit;
}

require_once "dbcon.php";

$name_t = $_SESSION['name'];
$id = $_SESSION['id'];
$subjects = array();

require("notifications.php");

$sql = "SELECT * FROM subject_table WHERE teacher_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {

$sql = "SELECT DISTINCT sub_name FROM subject_table WHERE teacher_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
            
    while($row = $result->fetch_assoc()){
    $subjects[] = $row["sub_name"];
     }
    ?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    
    <link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">

<link rel="stylesheet" type="text/css" href="../css/menu.css">

<link rel="stylesheet" type="text/css" href="../css/edit-delete.css">



<link rel="stylesheet" type="text/css" href="../vendor/animate/animate.css">

<link rel="stylesheet" type="text/css" href="../vendor/select2/select2.min.css">

<link rel="stylesheet" type="text/css" href="../vendor/perfect-scrollbar/perfect-scrollbar.css">

<link rel="stylesheet" type="text/css" href="../css/util.css">
<link rel="stylesheet" type="text/css" href="../css/main.css">

<link rel="stylesheet" type="text/css" href="../css/notifications.css">


     <style>

.form-group {
  margin-bottom: 0;
}

.btn.btn-sm {
margin-top: 3px;
color: #838383;
  background-color: ##afadad;
  -webkit-box-shadow: 0 2px 0px 0 rgba(0, 0, 0, 0.2);
  box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.2);
  -webkit-transition: .3s all ease;
  -o-transition: .3s all ease;
  transition: .3s all ease;
}

</style>

</head>
<body>

<div class="mb-5">
	<div id="menuHolder">
	  <div role="navigation" class="sticky-top border-bottom border-top" id="mainNavigation">
	    <div class="flexMain">
	      <div class="flex2">
	        <button class="whiteLink siteLink" style="border-right:1px solid #eaeaea" onclick="menuToggle()"><i class="fas fa-bars me-2"></i> MENU</button>
	      </div>
	      
	      <!--NOTIFICAIONS.-->
 
        <ul class="noti">
            <li id="noti_Container">
                <div id="noti_Counter" class="badge rounded-pill badge-notification bg-danger"></div>  
                <div id="noti_Button">
                  <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
  <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
</svg>
   </div>    
   
                <div id="notifications">
                    <h5>Notifications</h5>
                  <div id="contents">
                   </div>
                    <div class="seeAll"><!--<a href="#"></a>--></div>
                </div>
            </li>          
        </ul>

    <!--N-->
    
	      <div class="flex3 text-center" id="siteBrand">
	        Teaching in CIS
	      </div>

	      

Welcone, <?php echo $name_t ?>  &nbsp;
	      <div class="flex2 text-end d-none d-md-block">
	        <a href="logout.php"><button class="blackLink">LOG OUT</button></a>
	      </div>
	    </div>
	  </div>

	  <div id="menuDrawer">
	    <div>
	      <a href="index.php" class="nav-menu-item"><i class="fas fa-home me-3"></i>Home</a>
	      <a href="about.php" class="nav-menu-item"><i class="fas fa-building me-3"></i>About Us</a>
	    </div>
	  </div>
	</div>
		</div>



 <form class="d-flex justify-content-center" method="POST" name="filter" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
 
 <div class="row">
 <div class="col-4">
<div class="form-group">
<select class="form-control" name="subject">
<?php

$sql = "SELECT DISTINCT sub_name FROM subject_table WHERE teacher_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

echo '<option disabled selected value>--Subject--</option>';
while($row = $result->fetch_assoc())
    {
        echo '<option value="' .$row["sub_name"]. '">' .$row["sub_name"]. '</option>';
    }

?>
</select>
  </div>
    </div>
  
 <div class="col-4">
<div class="form-group">
<select class="form-control" name="grade">
<?php
$sql = "SELECT grade FROM year_table, subject_table WHERE subject_table.year_id = year_table.year_id AND teacher_id = ? GROUP by grade";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

echo '<option disabled selected value>--Grade--</option>';
while($row = $result->fetch_assoc())
    {
        echo '<option value="' .$row["grade"]. '">' .$row["grade"]. '</option>';
    }
?>
</select>
</div>
</div>

 <div class="col-2">
<button class="btn btn-sm" name="filter" type="submit">Filter</button>
</div>
 <div class="col-2">
<button class="btn btn-sm" name="reset" type="submit">Reset</button>
</div>
</div>
</form>


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
$grade = $sub = 0;
 if($_SERVER["REQUEST_METHOD"] == "POST"){
  if(isset($_POST["filter"])){
      $sub = $_POST["subject"];
      $grade = $_POST["grade"];
    if(isset($_POST["subject"]) && !isset($_POST["grade"])){
        $sql = "SELECT student.st_id, sub_name, grade, first_name, last_name, student.gender, date_and_birth FROM student, year_table, subject_table, $sub WHERE sub_id = subject_id AND subject_table.year_id = year_table.year_id AND teacher_id = ? AND student.st_id = $sub.st_id";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result(); 
		
        while ($row = $result->fetch_assoc()) {
    
                
            echo " <tr class='row100 body'>";
            foreach($row as $value) {
            echo "<td class='cell100 column'>" . $value . "</td>"; 
             }
           echo "<td class='cell100 column'> <ul class='action-list'>";
           echo "<li><a class='ed' href='update.php?id=" .$row['st_id']. "&sub=" .$row['sub_name']. "&grade=" .$row['grade']. "' data-tip='edit'><i class='fa fa-edit'></i></a></li></ul></td>"; 
             echo "</tr>";			
          }
}
elseif(!isset($_POST["subject"]) && isset($_POST["grade"])){
        foreach($subjects as $subject){
        $sql = "SELECT student.st_id, sub_name, grade, first_name, last_name, student.gender, date_and_birth FROM student, year_table, subject_table, $subject WHERE sub_id = subject_id AND subject_table.year_id = year_table.year_id AND teacher_id = ? AND grade = ? AND student.st_id = $subject.st_id";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('ii', $id, $grade);
        $stmt->execute();
        $result = $stmt->get_result(); 

        while ($row = $result->fetch_assoc()) {
    
                
            echo " <tr class='row100 body'>";
            foreach($row as $value) {
            echo "<td class='cell100 column'>" . $value . "</td>"; 
             }
           echo "<td class='cell100 column'> <ul class='action-list'>";
           echo "<li><a class='ed' href='update.php?id=" .$row['st_id']. "&sub=" .$row['sub_name']. "&grade=" .$row['grade']. "' data-tip='edit'><i class='fa fa-edit'></i></a></li></ul></td>"; 
             echo "</tr>";
          }

        }
}
elseif(isset($_POST["subject"]) && isset($_POST["grade"])){
        $sql = "SELECT student.st_id, sub_name, grade, first_name, last_name, gender, date_and_birth FROM student, year_table, subject_table, $sub WHERE sub_id = subject_id AND subject_table.year_id = year_table.year_id AND teacher_id = ? AND grade = ? AND student.st_id = $sub.st_id";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('ii', $id, $grade);
        $stmt->execute();
        $result = $stmt->get_result(); 

        while ($row = $result->fetch_assoc()) {
    
                
            echo " <tr class='row100 body'>";
            foreach($row as $value) {
            echo "<td class='cell100 column'>" . $value . "</td>"; 
             }
           echo "<td class='cell100 column'> <ul class='action-list'>";
           echo "<li><a class='ed' href='update.php?id=" .$row['st_id']. "&sub=" .$row['sub_name']. "&grade=" .$row['grade']. "' data-tip='edit'><i class='fa fa-edit'></i></a></li></ul></td>"; 
             echo "</tr>";
          }
}
else{
echo "No column is selected.";
}

}
else if(isset($_POST["reset"])){
foreach($subjects as $subject){
            $sql = "SELECT student.st_id, sub_name, grade, first_name, last_name, gender, date_and_birth FROM student, year_table, subject_table, $subject WHERE sub_id = subject_id AND subject_table.year_id = year_table.year_id AND teacher_id = ? AND student.st_id = $subject.st_id";
            $stmt = $db->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result(); 

        while ($row = $result->fetch_assoc()) {
    
                
            echo " <tr class='row100 body'>";
            foreach($row as $value) {
            echo "<td class='cell100 column'>" . $value . "</td>"; 
             }
           echo "<td class='cell100 column'> <ul class='action-list'>";
           echo "<li><a class='ed' href='update.php?id=" .$row['st_id']. "&sub=" .$row['sub_name']. "&grade=" .$row['grade']. "' data-tip='edit'><i class='fa fa-edit'></i></a></li></ul></td>"; 
             echo "</tr>";
          }

        }
}
}
else{
            
            foreach($subjects as $subject){
            $sql = "SELECT student.st_id, sub_name, grade, first_name, last_name, gender, date_and_birth FROM student, year_table, subject_table, $subject WHERE sub_id = subject_id AND subject_table.year_id = year_table.year_id AND teacher_id = ? AND student.st_id = $subject.st_id";
            $stmt = $db->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result(); 

        while ($row = $result->fetch_assoc()) {
    
                
            echo " <tr class='row100 body'>";
            foreach($row as $value) {
            echo "<td class='cell100 column'>" . $value . "</td>"; 
             }
           echo "<td class='cell100 column'> <ul class='action-list'>";
           echo "<li><a class='ed' href='update.php?id=" .$row['st_id']. "&sub=" .$row['sub_name']. "&grade=" .$row['grade']. "' data-tip='edit'><i class='fa fa-edit'></i></a></li></ul></td>"; 
             echo "</tr>";
          }

        }
}
    }
    
else{
echo "Sorry, you don't have access to any subject. Please contact admin. <br>
<a href='logout.php'>Log out</a>";
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
  
   <script src="../vendor/jquery/jquery-3.6.3.min.js"></script>
    <script src="../vendor/jquery/jquery-3.6.3.js"></script>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/popper.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.js"></script>

	<script src="../js/menu.js"></script>
	
	<script src="../vendor/select2/select2.min.js"></script>

<script src="../vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>

<script src="../js/main.js"></script>

<script src="../js/notifications.js"></script>


</body>
</html>