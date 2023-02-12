<?php
// ئەم پەیجە تەنیا پەیجی سەرەکی وێبسایتەکەمانە و لینکی هۆم و لۆگین و خۆ تۆمارکردنی تێدایە
// هەر پەیجێک بەناوی
// index.php
// نوسرا، ئەوە ئەو هەر کە فۆڵدەرەکەت کردەوە ڕاستەوخۆ وێبسایتەکە ئەچێتە ئەو پەیجە

// Initialize the session
session_start(); // ئەمە بۆ ئەوەیە کە دەست بکات بە کۆنێکت بوون بە کوکی بڕاوسەرەکەوە بۆ ئەو پەیجانەی ناو وێبسایتەکەمان کە ئەم کۆدەیان تیایە، واتە سەیری ئەو یوزەرە ئەکات کە داخڵی وێبسایتەکە بووە بزانێت پێشتر لۆگین بووە
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){ // لێرەدا چێکی ئەکەین بزانین قوتابی کە ئێستە لەم پەیجەیە لۆگین بووە
    header("location: results.php"); // ئەگەر لۆگین بووبوو ئەوە بە بچێت بۆ بینینی کارتەکانی
    exit;
}
elseif(isset($_SESSION["loggedin_t"]) && $_SESSION["loggedin_t"] === true){ // لێرەدا چێکی ئەکەین بزانین کە ئەگەر قوتابی نەبوو کەواتە ئایا مامۆستایە و لۆگین بووە یان نا
    header("location: teacher_pannel.php"); // ئەگەر وابوو کەواتە با بچێت بۆ پەیجی بینینی قوتابیەکان
    exit;
}

// $_SESSION["loggedin"] و $_SESSION["loggedin_t"]
// $_SESSION : ئەم کۆدە خۆی هەیە لە پی ئێچ پیدا و ئەڕڕەیەکە کە هەموو ئەو زانیاریانە بۆ هەڵ ئەگرێت لەکاتی لۆگین بوون تا لۆگئاوت کردن
// ئەوەی لەناو کەوانەکەیا ئەنووسرێ تەنیا ناون خۆمان دامان ناوە، ئەو ناوانە سەرەتا لەکاتی لۆگین کردنی مامۆستا یان قوتابی دامان ناوە لە پەیجی لۆگین کردن
// login.php



// خۆ ئەگەر هیچ یەک لەمانە نەبوو ئەوە ئەوکاتە ئێچ تی ئێم ئێڵەکەمان ئەکرێتەوە کە کۆدەکانی لە خوارەوەیە
?>
<!DOCTYPE html>
<head>
<meta charset="UTF-8">

<title>Home</title>

<link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.css">

<link rel="stylesheet" type="text/css" href="../css/menu.css">

<style>

body {
  font-size: 14px;
  line-height: 1.8;
  font-weight: 400;
  background-image: url("../images/bg.jpg");
  background-repeat: no-repeat;
  background-size: cover;
}


#siteBrand {
margin-left: 100px;
}

</style>

</head>
<body>

	<div id="menuHolder">
	  <div role="navigation" class="sticky-top border-bottom border-top" id="mainNavigation">
	    <div class="flexMain">
	      <div class="flex2">
	        <button class="whiteLink siteLink" style="border-right:1px solid #eaeaea" onclick="menuToggle()"><i class="fas fa-bars me-2"></i> MENU</button>
	      </div>
	      <div class="flex3 text-center" id="siteBrand">
	        Teaching in CIS
	      </div>

	      

	      <div class="flex2 text-end d-none d-md-block">
	        <a href="s-registration.php"><button class="whiteLink siteLink">REGISTER</button></a>
	        <a href="login.php"><button class="whiteLink siteLink">LOGIN</button></a>
	      </div>
	    </div>
	  </div>

	  <div id="menuDrawer">
	    <div>
	      <a href="index.php" class="nav-menu-item"><i class="fas fa-home me-3"></i>Home</a>
	      <a href="s-registration.php" class="nav-menu-item"><i class="fas fa-dollar-sign me-3"></i>Register</a>
	      <a href="login.php" class="nav-menu-item"><i class="fas fa-file-alt me-3"></i>Login</a>
	      <a href="about.php" class="nav-menu-item"><i class="fas fa-building me-3"></i>About Us</a>
	    </div>
	  </div>
	</div>


     <!-- JS -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/popper.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.js"></script>
	<script src="../vendor/jquery/jquery-3.6.3.slim.js"></script>
<script src="../js/menu.js"></script>
</body>
</html>