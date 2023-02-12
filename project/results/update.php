<?php

// لەم پەیجەدا دەرەجەکانی تەلەبەیەک ئەهێنین و مامۆستا ئەتوانێت دەرەجەی بۆ دانێت

// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin_t"]) || $_SESSION["loggedin_t"] !== true){
    header("location: login.php");
    exit;
}

require_once "dbcon.php";

$st_id = 0; 
$st_id = $_REQUEST['id']; // ئای دی قوتابیەکە وەردەگرین کە لە لینکی براوسەرەکەدایە
if($st_id == 0){ // ئەگەر ئای دی قوتابیەکە سفر بوو واتە ئەو پەیجە ڕاستەوخۆ چویتەتە ناوی نەک لەڕێی پەیجی تیچەر پەناڵەوە، بۆیە ئەتباتەوە بۆ پەیجی تیچەر پەناڵ.... ئەمە زۆر پێویست نیە ئەتوانیت بیسڕیتەوە بەس باشترە بۆ سیکیوریتی
echo "This page will be redirected..";
header('Refresh: 1; URL=teacher_pannel.php');
exit;
}
   
$mark = "";
$average = null;
$subject = $_REQUEST['sub'];  //سەبجێکتەکە وەردەگرین کە لە لینکی براوسەرەکەدایە
$grade = $_REQUEST['grade'];  //مەرحەلەکە وەردەگرین کە لە لینکی براوسەرەکەدایە

// $_REQUEST:
// ئەمیش وەکو 
// $_POST
// وایە، بەڵام پۆست لە فۆڕمەوە زانیاری وەرەگرێت بەس ڕیکوێست لە لینکەکەی بڕاوسەرەکەوە زانیاری وەرەگرێت
// بۆ نمونە:
// localhost/results/update.php?grade=2
// کە وتمان 
// $_REQUEST['grade'];
// ئەوە لەو لینکەوە ژمارە دووەکە وەرەگرێت
// ئەتوانین زیاد لە زانیاریەک وەرگرین بەم جۆرە:
// localhost/results/update.php?grade=2&sub=kurdish
// $_REQUEST['sub']; کوردیمان بۆ ئەهێنت

$t_name = $_SESSION['name'];
$id = $_SESSION['id'];



// ئەم بەشە کۆدە هەمووی بۆ ئەوەیە کە لە لینکەکەی سەرەوە کە ئای دی و مەرحەلەکە و ناوی دەرسەکەی تیایە، ئەگەر مامۆستایەک بەدەست خۆی چوو ئای دیەکەی گۆڕی بۆ ئایدیەکی تر یان ناوی وانەکەی گۆڕی، ئەوکاتە مامۆستاکە ئەتوانێت دەستکاری دەرەجەی قوتابیەکی تر بکات کە خۆی وانەی پێ ناڵێت
// localhost/results/update.php?id=2&grade=2&sub=kurdish
// بۆ نمونە لەو لینکە ئەچێت وانەی کوردی ئەگۆڕێت بۆ ئینگلیزی کە خۆی ئینگلیزی ناڵێتەوە، یان مەرحەلە دوو ئەگۆڕێت بۆ مەرحەلەیەکی تر
// بۆیە بۆی ڕێ لەوە بگرین بۆ سیکیوریتی دێین بزانین ئای دی ئەو مامۆستایە لە تەیبڵی سەبجێکتا هەیە بۆ ئەو سەبجێکت و ئەو مەرحەلەیە، ئەگەر نەبوو ڕێگەی پێنادەین
// ئەتوانیت ئەم بەشە کۆدە هەمووی بسڕیتەوە ئەگەر ناتەوێ ئەو سیکیوریتیە دابنێی
/////////////////////////////////////لێرەوە دەست پێ ئەکات//////////////////////////////////
$sql = "SELECT * FROM year_table WHERE grade = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $grade);  // i: واتە ژمارە
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$year_id = $row["year_id"];

$sql = "SELECT * FROM subject_table WHERE sub_name = ? AND teacher_id = ? AND year_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('sii', $subject, $id, $year_id); // s: نوسین کە سەبجێکتەکەیە
// i: ژمارە کە ئایدیەکانن
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$subject_id = $row["subject_id"];

$sql = "SELECT * FROM $subject WHERE sub_id = ? AND st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('ii', $subject_id, $st_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows <= 0) {


echo 
    '<script type="text/javascript">
     alert("Access denied!")
     </script>';
    header('Refresh: 0; URL=teacher_pannel.php');
    echo "This page will be redirected..";
    exit;

}
////////////////////////////////لێرە کۆتایی پێدێ////////////////////////////

require("notifications.php");

$button_clicked = -1;
if($_SERVER["REQUEST_METHOD"] == "POST"){
$button_clicked = 1;
if(isset($_POST["submit"])){
if($_POST['half_first'] != null){ // ئەگەر فیڵدی نیوەی سمستەری یەکەم لە فۆڕمەکەدا پڕکرابووەوە 
$value = $_POST['half_first'];
$sql = "UPDATE $subject SET half_of_the_first_semester = ? WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('di', $value, $st_id); // d: double
$stmt->execute();
}
if($_POST['end_first'] != null){ // ئەگەر فیڵدی کۆتایی سمستەری یەکەم لە فۆڕمەکەدا پڕکرابووەوە 
$value = $_POST['end_first'];
$sql = "UPDATE $subject SET end_of_the_first_semester = ? WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('di', $value, $st_id);
$stmt->execute();
}

if($_POST['half_second'] != null){ //..
$value = $_POST['half_second'];
$sql = "UPDATE $subject SET half_of_the_second_semester = ? WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('di', $value, $st_id);
$stmt->execute();
}

if($_POST['end_second'] != null){
$value = $_POST['end_second'];
$sql = "UPDATE $subject SET end_of_the_second_semester = ? WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('di', $value, $st_id);
$stmt->execute();
}
}
else{
if(isset($_POST["clear_half_first"])){ // ئەگەر دوگمەی سڕینەوەی نیوەی سمستەری یەکەم کلیکی لێ کرابوو ئەوە دەرەجەکە بسڕەرەوە
$sql = "UPDATE $subject SET half_of_the_first_semester = null WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $st_id);
$stmt->execute();
}
if(isset($_POST["clear_end_first"])){  // ئەگەر دوگمەی سڕینەوەی کۆتایی سمستەری یەکەم کلیکی لێ کرابوو ئەوە دەرەجەکە بسڕەرەوە
$sql = "UPDATE $subject SET end_of_the_first_semester = null WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $st_id);
$stmt->execute();
}
if(isset($_POST["clear_half_second"])){ // ...
$sql = "UPDATE $subject SET half_of_the_second_semester = null WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $st_id);
$stmt->execute();
}
if(isset($_POST["clear_end_second"])){
$sql = "UPDATE $subject SET end_of_the_second_semester = null WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $st_id);
$stmt->execute();
}
}
}

$sql = "SELECT * FROM $subject WHERE st_id = ?";  // سەبجێکتەکە ئەهێنین لە داتابەیسدا بۆ ئەو قوتابیەی هەمانە و لەخوارەوە کاری لەسەر ئەکەین
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $st_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$first_result = $second_result = $average = -1; // ئەم ڤاریەبڵانە خۆمان دروستمان کردوون کە ئەنجامی کۆکردنەوەی سمستەرەکانی ئەخەینە ناو... سەرەتا بە ماینەس یەک دای ئەنێین بۆی ئەگەر ئەنجاممان نەبوو ئەوە لەخوارەوە نەهێڵین هیچ ئەنجامێک بچێتە داتابەیسەوە

if($row['half_of_the_first_semester'] !== null && $row['end_of_the_first_semester'] !== null){ // لەو سەبجێکتەدا کە تێیداین ئەگەر نیوەی یەکەم و کۆتایی سمستەری یەکەم بەتاڵ نەبوو و دەرەجەکانیان دانرابوو
$first_result = $row['half_of_the_first_semester'] + $row['end_of_the_first_semester']; // ئەوە کۆیان ئەکەینەوە بۆی دوایی ئەنجاکی هەردوو سمستەرەکە دیاری بکەین
}
else{
$first_result = -1; // ئەگەر هەرکام لە نیوەی یەکەمی سمستەری یەکەم یاخود کۆتایی سمستەری یەکەم هیچ ئەنجامی تێدا نەبوو ئەوە با بیکاتەوە بە ماینەس یەک... هەرچەندە لەسەرەوە داماننا کە ماینەس یەکە و وە ئەگەر ئفەکە جێ بەجێ نەبوو خۆی ئەبێت هەر بە ماینەس یەک بمێنێتەوە، بەڵام ئەمە بۆ ئەوەیە کە ئەگەر دەرەجەکە دەستکاری کرا و دواتر لەبرایەوە بۆی ئەنجامی سمستەرەکە بسڕێتەوە لە داتابەیسدا
}

if($row['half_of_the_second_semester'] !== null && $row['end_of_the_second_semester'] !== null){ // ئەمانی تریش بەهەمان شێوەی سەرەوە
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

// لێرەدا سوود لە ماینەس یەکەکان ئەبینی
if($first_result != -1){ // ئەگەر ماینەس یەک نەبوو ئەوە واتە نیوەی سەرەتا و کۆتایی سمستەری یەکەم. بەتاڵ نیە و دەرەجەی بۆ دانراوە، کەواتە ئەو دوو نیوەیە کۆ کراونەتەوە و ئەنجامەکەیان ئەخەینە ناو ئەنجامی سمستەرەکەوە لە داتابەیسدا بەم کۆدەی خوارەوە
$sql = "UPDATE $subject SET first_semester_result = ? WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('di', $first_result, $st_id);
$stmt->execute();
}
else{ // ئەگەریش ماینەس یەک بوو ئەوە واتە یان نیوەی یەکەمی سمستەرەکە یان کۆتاییەکەی یاخود هەردوکیان بەتاڵە، کەواتە نابێت ئەنجامی کۆکردنەوەکەشی دابنێین، بۆیە ئەگەر پێشتر دانرابوو با بیسڕێتەوە بەم کۆدەی خوارەوە
$sql = "UPDATE $subject SET first_semester_result = null WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $st_id);
$stmt->execute();
}
if($second_result != -1){// ئەمانی تریش بەکەمان شێوەی سەرەوە..
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

if($button_clicked == 1) // ئەمە تەنیا بۆ ئەوەیە کە هەموو شتەکان تەواو بوو پەیجەکە ڕیفرێش ببێتەوە بۆی ئەنجامەکە دەرکەوێ لەسەر شاشەکە لە وێبسایتەکەدا و وە تەنیا بۆ ئەڤەرەیجی دەرەجەی کۆتایی کردومانە چونکە ئەو فەنکشنە چەن شتێک پێکەوە ئەکات، هەردوو سمستەرەکە کۆ ئەکاتەوە و پاشان ئەنجامەکەش دەرەچێت ئایا قوتابیەکە دەرچووە یان کەوتووە، بۆیە پەیجەکە تا ڕیفرێش نەبێتەوە پشانی نادات
header("Refresh:0");
}
else{
$sql = "UPDATE $subject SET year_end_average = null WHERE st_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('i', $st_id);
$stmt->execute();

if($button_clicked == 1)
header("Refresh:0");
}

if($average < 100 && $average != -1) // ئەمە واتە ئەگەر ئەنجامی کۆتایی لە ١٠٠ کەمتر بوو واتە کەوتووە و لە ١٠٠ زیاتر بوو واتە دەرچووە، وە پێویستە ماینەس یەکیش نەبێت واتە بەتاڵ نەبێت و پڕکرابێتەوە
$mark = "Fail";
elseif($average >= 100)
$mark = "Pass";
else
$mark = "";

?>


<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    
     <link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">

<link rel="stylesheet" type="text/css" href="../css/menu.css">

<link rel="stylesheet" type="text/css" href="../css/notifications.css">
    
    <!-- دوو فەنکشن دروست ئەکەین بۆ ئەوەی کە کلیک لە ئەپدەیت یان کلیەر کرا دڵنیامان بکاتەوە کە ئەمانەوێت بەردەوام بین یان نا -->
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
     ul {
            display:block;
            list-style:none;
            margin: revert;
        }
        
        
        
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

.s3, .s6, .s7{
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
  font-size: 13px;
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
  background-color: #006adb;
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
    width: 132px;
    height: 50px;
    background-color: #ebf2fa;
    border-color: #2196F3;
    padding: 10px 10px;
    margin: 1px 1px;
    font-size: 14px;
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

	      

Welcone, <?php echo $t_name ?>  &nbsp;
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


<?php

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
     echo "<td >" .$st_id. "</td><td>" .$grade. "</td><td>" .$subject. "</td><td>" .$t_name. "</td>";
     $count = 1;
    foreach($row as $value) {
        echo "<td class='" .$mark. "s" .$count. "'>" . $value . "</td>"; // لێرەدا ڤاریەبڵی مارک و کاونتمان هەمە و کڵاسێکمان کەسان کردووە پێیان کە ئەمە تەنیا بۆ سی ئێس ئێسە... ڤاریەبڵی مارکەکە لەسەرەوە دیاریمان کرد کە ئەگەر دەرچووبوو بنوسێت دەرچوو و ئەگەر کەوتبوو بنوسێت کەوتوو وە ئەگەر ئەنجامی نەبوو ئەوە ڤاریەبڵەکە بەتاڵ بێت.. پاش وشەی دەرچوو و کەوتوو بە ئینگلیزی، پاشان حەرفی ئێس مان داناوە، پاشان ڤاریەبڵی کاونت یەت کە لەخوارەوە دانە دانە زیاد ئەکات واتە تا ڕۆومان مابێت زیاد ئەکات و ژمارەی ڕۆوەکان دیاری ئەکات... بۆ نمونە ئەنجامی هەردوو سمستەرەکە لە ڕۆوی حەوتەمدایە.. پاشان لەسەرەوە بە سی ئێس ئێس ئیشی لەسەر ئەکەین
        
            $count++; // سەرەتا بە یەک دامان نا پاشان دانە دانە زیاد ئەکات
            }
            echo "</tr>";

        ?>
        <p><a href="teacher_pannel.php">Go back</a></p>
      <tr>
        <td colspan="4" rowspan="4"></td>
        <td> 
        <!-- ئەم ئینپوتانە وامان لێ کردوون کە تەنیا ژمارە لە نێوان سفر و بیست یاخود هەشتا وەرگرن و وە بشتوانن ژماری پۆینت وەرگرن  -->
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
        <!-- ئەگەر ئەنجامەکە هەبوو ئەوە ئەچێتە ئەم تەیبڵ ڕۆیەوە و ڤاریەبڵەکە یان ئەبێت بە دەرچوو یان کەوتوو و وە بەپێی ئەو دەرچوو یان کەوتووە ڕەنگەکەی سور یان سەوز ئەبێت بە سی ئێس ئێس -->
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

	<script src="../js/menu.js"></script>
	<script src="../js/notifications.js"></script>
</body>
</html>