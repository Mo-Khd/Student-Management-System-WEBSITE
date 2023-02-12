<?php
// ئەمە پەیجی لۆگینە بۆ قوتابی و مامۆستا پێکەوە، ئیمەیڵەکە هی کامیان بوو ئەویان لۆگین ئەبێت
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: results.php");
    exit;
}
elseif(isset($_SESSION["loggedin_t"]) && $_SESSION["loggedin_t"] === true){
    header("location: teacher_pannel.php");
    exit;
}
 
require_once "dbcon.php";
 
// Define variables and initialize with empty values
 $phone_email = $password = "";
 $password_t = $name_t = $id_t = "";
 $password = $name = $id = "";
 $message = "";
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){ //  POST ئەگەر سێرڤەرەکەمان میثۆدەکەی پۆست بوو، بەواتایەکی تر ئەگەر فۆڕمێک سەبمیت کرابوو کە میثۆدی ئەو فۆڕمە پۆست بوو 

	$phone_email = $_POST['phone_email'];
	
	// ئیمەیڵەکەی داخڵی کردووە بزانین لە تەیبڵی قوتابیدا هەیە
	$sql = "SELECT * FROM student WHERE phone_email = ?";
	$stmt = $db->prepare($sql);
    $stmt->bind_param('s', $phone_email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    // ئیمەیڵەکەی داخڵی کردووە بزانین لە تەیبڵی مامۆستادا هەیە
	$sql_t = "SELECT * FROM teacher_table WHERE phone_email = ?";
    $stmt_t = $db->prepare($sql_t);
    $stmt_t->bind_param('s', $phone_email);
    $stmt_t->execute();
    $result_t = $stmt_t->get_result();
    $row_t = $result_t->fetch_assoc();
	
	
	if ($result->num_rows == 1) { //  ئەگەر ئەو کیوریەی هی قوتابیبوو ڕپوەکەی بەیەک هاتەوە واتە ئیمەیڵەکە لە تەیبڵی قوتابیدا هەبوو، کەواتە ئەوە قوتابیە, کەواتە ئەم کۆدەی ناو ئەم ئفە جێ بەجێ ئەبێ
        $password = $row['password'];
        $name = $row['first_name'];
        $id = $row['st_id'];
	if (password_verify($_POST['password'], $password)){ // پاسوۆردەکەی دەخڵی کرد وەری ئەگرێ و لەگەڵ پاسۆردەکەی ناو داتابەیسەکە بەراوردی ئەکات بزانێت یەکسانن.. لەبەر ئەوەی پێشتر کە خۆی تۆمارکرد پاسۆردەکەمان کرد بە هاش، بۆیە ئەبێ بەم فانکشنی پاسۆرد ڤێریفایە بەراوردەکە بکەین تا بزانێت ئەم پاسۆردە ئاساییەی داخڵ کراوە یەکسانە بە پاسۆردە هاشەکە
// بۆ نمونە پاسۆردەکەی دەخڵی کردوەە = ١٢٣
// وە پاسۆردەکەی ناو داتابەیسیش هەمان شتە بەس بە هاشی واتە شتێکی لەم جۆرەیە:
// $2y$10$vYQCza6pl9sFALof1nqXVOpxVJp8OnSYa65n3qlGzEctYRpLN3L1y
	    
	      session_start(); // سێشنەکە دەست پێ ئەکات
          	$_SESSION["loggedin"] = true; // سێشنێک دروست ئەکەین تا دوایی چێکی بکەین لە پەیجەکانی تر بزانین لۆگینە
          	$_SESSION["name"] = $name;
          	$_SESSION["id"] = $id;
          	header("Location: results.php"); 
        
     }
     else{  // ئەگەر ئیمەیڵەکەی قوتابیەکە تەواو بوو بەس پاسۆرد هەڵە بوو ئەوە ئەم مەسجە پشانبە
     $message = "Wrong password";
     }
          }
          else if ($result_t->num_rows  == 1) { // ئەگەر ڕۆوی قوتابیەکە نەبوو بەس هی مامستاکە هەبوو وەرە ئەم ئفەوە
            $password_t = $row_t['password'];
            $name_t = $row_t['teacher_name'];
            $id_t = $row_t['teacher_id'];
	       if (password_verify($_POST['password'], $password_t)){ // هەمان شتی قوتابیەکە ئەکەینەوە ئەمجارە بۆ مامۆستا
	        session_start();
          	$_SESSION["loggedin_t"] = true;
          	$_SESSION["name"] = $name_t;
          	$_SESSION["id"] = $id_t;
          	header("Location: teacher_pannel.php"); 
        
       }
       else{ // ئەگەر ئیمەیڵەکەی مامۆستاکە تەواو بوو بەس پاسۆرد هەڵە بوو ئەوە ئەم مەسجە پشانبە
        $message = "Wrong password";
      }
          }
          else{ // ئەگەر ئیمەیڵەکە نە لە تەیبڵی مامۆستادا هەبوو نە لە هی قوتابیدا، ئەوە مەسجێکی پشانبە بڵێ ئیمەیڵەکەت هەڵەیە
          	
          	$message = "Invalid email/phone.";
		}
}
?>
 
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    
    <link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">

<link rel="stylesheet" type="text/css" href="../css/menu.css">

    <!-- Main css -->
    <link rel="stylesheet" href="../css/style.css">
    
<style>
#siteBrand {
margin-left: 100px;
}

.message {
color: #b02a37;
}
</style>
   </head>
<body>
	<!-- starting menu -->
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
	        <a href="login.php"><button class="blackLink siteLink">LOGIN</button></a>
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
	<!-- ending menu -->
        
         <div class="main">
        <section class="signup">
            <div class="container">
                <div class="signup-content">

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
         <h2 class="form-title">Login</h2>
         
          <div class="form-group">
                <input class="form-input" id="phone_email" type="text" name="phone_email" placeholder="Phone or Email" value="<?php echo $phone_email; ?>" required>
                </div>
                
                 <div class="form-group">
                <input class="form-input" type="password" name="password" name="id" placeholder="Password" required>
                </div>
                
                 <div class="form-group">
               <button id="submit" class="form-submit" name="submit" type="submit" >Login</button>
                </div>
                
                <div class="message"><?php echo $message; ?></div>
                
                <p class="loginhere">
            Don't have an account? <a class="loginhere-link" href="s-registration.php">Sign up now</a>.</p>
        </form>
        
         </div>
            </div>
        </section>

    </div>
    
     <!-- JS -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/popper.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.js"></script>
	<script src="../vendor/jquery/jquery-3.6.3.slim.js"></script>
<script src="../js/menu.js"></script>

</body>
</html>
