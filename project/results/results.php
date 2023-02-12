<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "dbcon.php";

$mark = "";
$name = $_SESSION['name'];
$id = $_SESSION['id'];
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

<link rel="stylesheet" type="text/css" href="../css/menu.css">

<style>

body {
text-align: center;
}

</style>

    <style id="table_style" type="text/css">
    
    table {
       margin: 0 auto;
       width: 50%;
       border-collapse: separate;
        border-spacing: 5px 0;
    }
 
 .table-bordered td, .table-bordered th {
        border: 1px solid #c3c3c3;
}

.table-bordered {
    border: none;
}

th {
  color: #414141;
}

td {
color: #4f4f4f;
}
th, td {
  padding: 10px;
}

.vertical {
    writing-mode: vertical-lr;

}

.Fails2, .Fails5,  .Fails8, .Fails9, .Fails10 {
   font-weight: bold;
}

.Passs2, .Passs5,  .Passs8, .Passs9, .Passs10{
   font-weight: bold;
}

.s2, .s5, .s8, .s9, .s10 {
   font-weight: bold;
}

.s2, .Fails2, .Passs2{
background-color: aliceblue;
}

.s10, .Fails10, .Passs10{
background-color: lavender;
}

.Fails10 {
   color: red;
}

.Passs10 {
   color: green;
}

</style>

<script>
function printMark()
{
   var printWindow = window.open();
        printWindow.document.write('<html><head><title>Table Contents</title>');
 
        //Print the Table CSS.
        var table_style = document.getElementById("table_style").innerHTML;
        printWindow.document.write('<style type = "text/css">');
        printWindow.document.write(table_style);
        printWindow.document.write('</style>');
        printWindow.document.write('</head>');
 
        //Print the DIV contents i.e. the HTML Table.
        printWindow.document.write('<body>');
        var divContents = document.getElementById("printMark").innerHTML;
        printWindow.document.write(divContents);
        printWindow.document.write('</body>');
 
        printWindow.document.write('</html>');
        printWindow.document.close();
        printWindow.print();
}

</script>


</head>
<body>


<div class="mb-5">
	<div id="menuHolder">
	  <div role="navigation" class="sticky-top border-bottom border-top" id="mainNavigation">
	    <div class="flexMain">
	      <div class="flex2">
	        <button class="whiteLink siteLink" style="border-right:1px solid #eaeaea" onclick="menuToggle()"><i class="fas fa-bars me-2"></i> MENU</button>
	      </div>
	      <div class="flex3 text-center" id="siteBrand">
	        Teaching in CIS
	      </div>

	      

Welcone, <?php echo $name ?>  &nbsp;
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

          <br>
<br>
<?php if(!isset($_GET['grade'])){ ?>

<h2>YOUR RESULT:</h2>

<div style="text-align: center">
<button class="btn btn-sm" style="background-color:  #d2d2d263;" onclick="printMark()">Print</button>
</div>
<div id="printMark">
<table class="table-bordered text-center">
   <tr style="background-color: antiquewhite">
   <th>Student ID</th>
     <th><?php echo $id; ?></th>
     </tr>
     <tr style="background-color: seashell">
     <th>Name</th>
    <th> <?php echo $name; ?> </th>
    </tr>
   </table>
<br>
    <table class="table-bordered text-center">

  <tr>
     <th class='vertical'></th>
     <th class='vertical'></th>
     <th class='vertical'>Half of the first semester</th>
     <th class='vertical'>End of the first semester</th>
     <th class='vertical'>First semester results</th>
     <th class='vertical'>Half of the second semester</th>
     <th class='vertical'>End of the Second semester</th>
     <th class='vertical'>Second semester results</th>
     <th class='vertical'>End year average</th>
     <th class='vertical'></th>
    </tr>

  <tr>
      <th>Grade</th>
     <th style="background-color: aliceblue;">Subject</th>
     <th>20</th>
     <th>80</th>
     <th>100</th>
     <th>20</th>
     <th>80</th>
     <th>100</th>
     <th>200</th>
     <th style="background-color: lavender;">RESULT</th>
    </tr>

<?php

foreach($subjects as $subject){
    $sql = "SELECT grade, sub_name, half_of_the_first_semester, end_of_the_first_semester, first_semester_result, half_of_the_second_semester, end_of_the_second_semester, second_semester_result, year_end_average FROM $subject, year_table, subject_table WHERE sub_id = subject_id AND subject_table.year_id = year_table.year_id AND st_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();


$average = -1;
while ($row = $result->fetch_assoc()) {
            

            if($row['year_end_average'] != null){
            $average = $row["year_end_average"];
            }
            
            if($average < 100 && $average != -1){
             $mark = "Fail";
             $average = -1;
             }
            elseif($average >= 100  && $average != -1){
            $mark = "Pass";
            $average = -1;
            }
            else
            $mark = "";

     echo "<tr>";
     $count = 1;
     foreach($row as $value) {
        
        echo "<td class='" .$mark. "s" .$count. "'>" . $value . "</td>";
        $count++;
    }
     echo "<td><div class='" .$mark. "s" .$count. "'>" .$mark. "</div></td>";
    echo "</tr>";
    
    

}

}
echo '</table> </div>';
echo '<br>';


$sql = "SELECT * FROM store_mark WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();


$show = false;

while($row = $result->fetch_assoc()){
if($row['subject'] == 'RESULT'){

$year_id = $row['year_id'];

$sql = "SELECT * FROM year_table WHERE year_id = $year_id";
$stmt = mysqli_query($db, $sql);
$row = mysqli_fetch_assoc($stmt);

if(!$show){
echo "See your previous results: <br>";
}
$show = true;
echo '- <a href="results.php?grade=' .$row['grade']. '">Grade ' .$row['grade']. '</a>  ';

}
}
}

if(isset($_GET['grade'])){
 
$grade = $_GET['grade'];
 $sql = "SELECT grade, subject, half_of_the_first_semester, end_of_the_first_semester, first_semester_result, half_of_the_second_semester, end_of_the_second_semester, second_semester_result, year_end_average, result FROM store_mark, year_table WHERE store_mark.year_id = year_table.year_id AND st_id = ? AND grade = ? ORDER BY id";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('ii', $id, $grade);
    $stmt->execute();
    $result = $stmt->get_result();
    
    echo "<h3>Grade " .$grade. ":</h3>";
    ?>
    
    <div style="text-align: center">
<button class="btn btn-sm" style="background-color:  #d2d2d263;" onclick="printMark()">Print</button>
</div>

<div id="printMark">

   <table class="table-bordered text-center">
   <tr style="background-color: antiquewhite">
   <th>Student ID</th>
     <th><?php echo $id; ?></th>
     </tr>
     <tr style="background-color: seashell">
     <th>Name</th>
    <th> <?php echo $name; ?> </th>
    </tr>
   </table>
<br>
    <table class="table-bordered text-center">

  <tr>
     <th class='vertical'></th>
     <th class='vertical'></th>
     <th class='vertical'>Half of the first semester</th>
     <th class='vertical'>End of the first semester</th>
     <th class='vertical'>First semester results</th>
     <th class='vertical'>Half of the second semester</th>
     <th class='vertical'>End of the Second semester</th>
     <th class='vertical'>Second semester results</th>
     <th class='vertical'>End year average</th>
     <th class='vertical'></th>
    </tr>

  <tr>
      <th>Grade</th>
     <th style="background-color: aliceblue;">Subject</th>
     <th>20</th>
     <th>80</th>
     <th>100</th>
     <th>20</th>
     <th>80</th>
     <th>100</th>
     <th>200</th>
     <th style="background-color: lavender;">RESULT</th>
    </tr>
    
    <?php
    
    while ($row = $result->fetch_assoc()) {
        
        if($row['result'] === "Pass")
            $mark = "Pass";
        elseif($row['result'] === "Fail")
            $mark = "Fail";
        
     echo "<tr>";
     $count = 1;
     foreach($row as $value) {
        
        echo "<td class='" .$mark. "s" .$count. "'>" . $value . "</td>";
        $count++;
    }
    echo "</tr>";

}

}

?>
</table>
</div>
<br>
<?php
if(isset($_GET['grade'])){
echo '<a href="results.php">Go back</a>';
}    
?>
<script src="../js/menu.js"></script>
   	
</body>
</html>