<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin_a"]) && $_SESSION["loggedin_a"] === true){
    header("location: admin.php");
    exit;
}
 
require_once "dbcon.php";
 
// Define variables and initialize with empty values
 $message = "";
 $username = null;
 $password = null;
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

	$username = $_POST['username'];
	$sql = "SELECT * FROM admin WHERE username = ?";
	$stmt = $db->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
	
          if ($result->num_rows > 0) {
            $password = $row['password'];
	        if(password_verify($_POST['password'], $password)){
	      session_start();
          	$_SESSION["loggedin_a"] = true;
          	$_SESSION["username"] = $username;
          	header("Location: admin.php"); 
          }
          else{
              $message = "Wrong password.";
        } 	
          }
          else{
          	$message = "Invalid email/phone.";
		}
}
?>
 
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="../images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="../vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="../vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../css/util.css">
	<link rel="stylesheet" type="text/css" href="../css/main-admin.css">
	<link rel="stylesheet" type="text/css" href="../css/menu.css">

<!--===============================================================================================-->

<style>
#menuHolder {
padding: 25px; 
width:100%; 
position:fixed; 
background: rgb(180 175 248 / 53%); 
border-radius: 20px; 
margin-top: 2px;
z-index:2; 
}

</style>
   </head>
<body>

<!-- starting menu -->
	<div id="menuHolder">
	
	     <div class="flex3 text-center" id="siteBrand">
	        ADMIN PANNEL
	      </div>

	</div>
	<!-- ending menu -->

          <div class="limiter">
<div class="container-login100" style="background-image: url('../images/bg-01.jpg');">
<div class="wrap-login100">
<form class="login100-form validate-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
<span class="login100-form-logo">
<i class="zmdi zmdi-lock"></i>
</span>
<span class="login100-form-title p-b-34 p-t-27">
Log in
</span>
<div class="wrap-input100 validate-input" data-validate="Enter username">
                <input class="input100" type="text" name="username" placeholder="Username" value="<?php echo $username; ?>" required>
               <span class="focus-input100" data-placeholder="&#xf207;"></span>
				</div>
				<div class="wrap-input100 validate-input" data-validate="Enter password">
                <input class="input100" type="password" name="password" placeholder="Password" required>
                <span class="focus-input100" data-placeholder="&#xf191;"></span>
					</div>
                <?php echo $message; ?>
                <br><br>
                <div class="container-login100-form-btn">
                   <button class="login100-form-btn" name="submit">
                   Login
                   </button>
                </div>
                
        </form>
    </div>
	</div>
	</div>
	
<!--===============================================================================================-->
	<script src="../vendor/jquery/jquery-3.6.3.min.js"></script>
<!--===============================================================================================-->
	<script src="../vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="../vendor/bootstrap/js/popper.js"></script>
	<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="../vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="../vendor/daterangepicker/moment.min.js"></script>
	<script src="../vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="../vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->

</body>
</html>
