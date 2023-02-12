<?php
// ئەم پەیجە هەم فۆڕمی ڕیجیسترەیشنی قوتابی تیایە، هەم کە کلیکی لە سەبمیت کرد هەر دێتەوە بۆ ئەم پەیجە و زایناریەکانی تۆمار ئەکات لە داتابەیسدا
// هەروەها هەموو کارتەکانی قۆناغی یەکەم دروست ئەکات بۆ ئەو قوتابیەی کە خۆی تۆمار ئەکات، چونکە کە قوتابیەکا تازە خۆی تۆمار ئەکات بە گشتی تازە دێتە ئەو زانکۆیە یان قوتابخانەیە و لە قۆناغی یەکەمەوە دەست پێ ئەکات.. بەڵام ئەگەر قوتابیەک لە قۆناغێکی ترەوە خۆی تۆمار کرد، ئەوە ئەبێ ئەدمین چاکی بکات

session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: results.php");
    exit;
}

    require_once "dbcon.php";
    
    $first_name = "";
    $last_name = "";
    $password = "";
    $gender = "";
     $date_and_birth = "";
     $address = "";
    $phone_email = "";
  
        
 if($_SERVER["REQUEST_METHOD"] == "POST"){
        $year_id;
        $sub_id;
        $grade;
        $subjects = array(); // ئەڕەیەکی بەتاڵ دروست ئەکەین کە دوایی لە خوارەوە هەموو سەبجێکتەکانی قۆناغی یەکەمی تێ بخەین
        
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $phone_email = trim($_POST['phone_email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $gender = $_POST['gender'];
        $date_and_birth = $_POST['date_and_birth'];
        $address = $_POST['address'];
        
        if ($_POST['radio'] == 'student'){
                    
        $sql = "SELECT * FROM student WHERE phone_email = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('s', $phone_email);
        $stmt->execute();
        
        $result = $stmt->get_result();

      if ($result->num_rows == 1) { // ئەگەر ئیمەیڵەکەی هەبوو لە داتابەیسدا ئەوە پێی بڵێ هەیە و تۆماری مەکە
                
            echo '
                <script src="../vendor/jquery/jquery-3.6.3.min.js"></script>
                <script>
                $(document).ready(function(){
                $("#phone_email").addClass("is-invalid");
                $(".original").hide();
                $("#phone_email").after("<div class=\'invalid-feedback\'>This email/phone is already in use, please use another one.</div>");
                 });
                </script>';
           
        }
        
        else{ // ئەگەر ئیمەیڵەکەی نەبوو تۆماری بکە
            
        $sql = "INSERT INTO student (first_name, last_name, phone_email, password, gender, date_and_birth, address) VALUES (?,?,?,?,?,?,?)";
        $stmt= $db->prepare($sql);
        $stmt->bind_param("sssssss", $first_name, $last_name, $phone_email, $password, $gender, $date_and_birth, $address);
        $stmt->execute();
        // تۆمارکردن تەواو
        
        // ئای دی قوتابیەکە وەرگرە
        $sql="SELECT * FROM student WHERE phone_email = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('s', $phone_email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $st_id = $row["st_id"];
        
        // $year_id
        // ساڵی یەکەم وەرگرە بۆی قوتابیەکە بخەینە مەرحەلە یەک
        $sql = "SELECT * FROM year_table WHERE grade = 1";
        $stmt = mysqli_query($db, $sql);
        $row = mysqli_fetch_assoc($stmt);
        
        // ئەگەر لە تەیبڵەکەدا مەرحەلە یەک بوونی نەبوو دروستی بکە
        // if grade 1 doesn't exist in year_table, create it
        if($stmt->num_rows <= 0){
            $sql = "INSERT INTO year_table (grade) VALUES (?)";
            $grade = 1;
            $stmt= $db->prepare($sql);
            $stmt->bind_param("i", $grade);
            $stmt->execute();
            
            $sql = "SELECT * FROM year_table WHERE grade = 1";
            $stmt = mysqli_query($db, $sql);
            $row = mysqli_fetch_assoc($stmt);
             }
             
            $year_id = $row["year_id"];
            
            // هەموو سەبجێکتەکان بۆ قۆناغی یەکەم وەرەگرین
            $sql = "SELECT * FROM subject_table WHERE year_id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param('i', $year_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
            $subjects[] = $row["sub_name"]; // سەبجێکتەکان دانە دانە ئەخەینە ئەڕەیەوە
            }
        
        foreach($subjects as $subject){ // لوپێک بەناو هەموو سەبجێکتەکاندا ئەکەین
        // foreach: لووپ ئەکات بەناو ئەڕەیدا با دانە دانە
        // $subjects as $subject
        // ڤاریەبڵی یەکەم ئەڕەیەکەیە کە لەسەرەوە دروستمان کرد و هەموو سەبجێکتەکانی قۆناعی یەکەمی تیایە
        // ڤاریەبڵی دووەم هەر یەکسەر لەویا دروستی ئەکەین و ئەڕەی نیە بەڵکو دانە دانە سەبجێکتەکان لە ئەڕەیەکە وەردەگرێ و هەرجارێک فۆڕەکە لووپ ئەکاتەوە سەبجێکتەکەی ئەگۆڕێث بۆ سەبجێکتی دواتر لەناو ئەڕەیەکەدا
         $sql = "SELECT * FROM subject_table WHERE year_id = ? AND sub_name = ?";
         $stmt = $db->prepare($sql);
         $stmt->bind_param('is', $year_id, $subject);
         $stmt->execute();
         $result = $stmt->get_result();
         $row = $result->fetch_assoc();
         
         $sub_id = $row["subject_id"];
            
            // کارتەکە بۆ قوتابیەکە زیاد ئەکەین
        // we don't need the if because the row should be 1, but we just make sure
            if ($result->num_rows == 1) { // ئەم ئفە ئەگەر ئەو سەبجێکت ئایدیە بوونی هەبێت لە تەیبڵی سەبجێکتدا ئینجا کارتەکە دروست ئەبێت
            $sql = "INSERT INTO $subject (st_id, sub_id) VALUES (?,?)";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("ii", $st_id, $sub_id);
            $stmt->execute();
            }
        }
            mysqli_stmt_close($stmt);
            mysqli_close($db);
            header("location: login.php");
            exit;    
    }
        
        }
        }
        else { // if radio was not student
        
        $sql = "SELECT * FROM teacher_table WHERE phone_email = ?";  // کیۆریەکی داتابەیس ئەنوسین کە لە تەیبڵی تیچەردا بگەڕێت بۆ ئەو ئیمەیڵەی مامۆستاکە خۆی پێ تۆمار کرد
        $stmt = $db->prepare($sql); // کیۆریەکە ئامادە ئەکەین
        // $db ناوی کۆنێکشنەکەیە کە لە پەیجی کۆنێکتی داتا بەیس نوسیمان
        $stmt->bind_param('s', $phone_email); // ئەو ئیمەیڵەی وەرمان گرت لە شوێنی نیشانەی پرسیارەکەی کۆدی کیۆریەکە دای ئەنێین
        // s => ئێس واتە ئەو ڤاریەبڵەی ئیمەیڵەکەی تیایە سترینگە یاخود بڵێین
        // varchar ە
        
        // $stmt->bind_param('s', $phone_email); لەم بەشەدا ئەکرێ s i d b دابنرێ
        // s: واتە سترینگ یان نوسین
        // i: واتە ژمارە
        // d: واتە double یان ژمارە بە پۆنیتەوە
        // ئەتوانیت هەر هەمووی بە سترینگ دابنێی

        $stmt->execute(); // کیۆریەکە جێ بەجێ بکە و بینێرە بۆ داتا بەیس
        $result = $stmt->get_result(); // ئەنجامی ئەو کیۆریە کە دێتەوە لە داتابەیسەکەوە بۆمان وەرگرە و بیخەرە ڤاریەبڵێکەوە

      if ($result->num_rows > 0) { // ئەگەر ئەو ڕۆوانەی هاتنەوە لە داتابەیسەوە لە سفر زیاتر بوو ئەوە واتە ئەو ئیمەیڵە پێشتر کەسێک خۆی پێ تۆمار کردووە و ڕێگەی پێنادەین دووبارە خۆی پێ تۆمار بکاتەوە.. وە لە داتابەیسیشدا ئەو فیڵدی ئیمەیڵەمان کردووە بە یونیک کە بەس یەکجار بتوانرێت داخڵ بکرێت و دووبارە بەبێت
      
       echo '
                <script src="../vendor/jquery/jquery-3.6.3.min.js"></script>
                <script>
                $(document).ready(function(){
                $("#phone_email").addClass("is-invalid");
                $(".original").hide();
                $("#phone_email").after("<div class=\'invalid-feedback\'>This phone/email is already in use, please use another one.</div>");
                 });
                </script>';
        
        }
        else{
            
            $ref = $_POST['referral'];
            $sql = "SELECT * FROM code WHERE referral = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param('s', $ref);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if($result->num_rows <= 0){
        
             echo '
                <script src="../vendor/jquery/jquery-3.6.3.min.js"></script>
                <script>
                $(document).ready(function(){
                if(!$("#referral").length){
                var input = $("<input>", {
                type: "text",
                name: "referral",
                id: "referral",
                placeholder: "Referral code",
                class: "form-control form-input",
                css: {"border": "dotted #dc3545"},
                required: true
                });
                $("#referralDiv").prepend(input);
                $("#referralDiv").addClass("form-group");
                }
                });
                
                $(document).ready(function(){
                $("input[name=\'radio\']:eq(1)").prop("checked", true);
                $("#referral").addClass("is-invalid");
                $(".original").hide();
                $("#referral").after("<div class=\'invalid-feedback\'>Your referral code is invalid.</div>");
                 });
                 </script>';
                 
            }
            else{
            $row = $result->fetch_assoc();

            if($result->num_rows == 1){
            $subject = $row["subject"];
            $grade = $row["grade"];
            }
            
            $name = $first_name. " "  .$last_name; // ناوی مامۆستاکە کە لە فۆڕمی خۆ تۆمارکردنەکە نوسی وەری ئەگرین و ئەیخەینە ڤاریەبڵێکەوە
            $date_of_birth = $date_and_birth;
            
        // ئەگەر ئەو ئفەی سەرەوە جێ بەجێ نەبوو ئەوە واتە کێشەی نیە و ئەو ئیمەیڵە لە داتابەیسدا بوونی نیە، کەواتە ئەو مامۆستایە تۆمار ئەکەین و زانیاریەکانی ئەخەینە تەیبڵی مامۆستاوە
        $sql = "INSERT INTO teacher_table (teacher_name, phone_email, password, date_of_birth, gender, address) 
        VALUES (?,?,?,?,?,?)";
        // ڵێرەدا فیڵدی teacher id
        // دانەنراوە لەبەر ئەوەی لە داتابەیسدا کردومانە بە
        // AUTO_INCREMENT
        // واتە خۆی دانە دانە زیاد ئەکات

        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssssss", $name, $phone_email, $password, $date_of_birth, $gender, $address);
        $stmt->execute();
        // لێرەدا زانیاریەکانی تۆمار بوو و کۆتایی هات
    
        // ئینجا ئەچین مامۆستاکە ئەکەین بە مامۆستای ئەو وانەیەی کە کۆدەکەمان پێدا
        
        // ئای دی مامۆستاکە ئەدۆزینەوە لە تەیبڵی مامۆستاکەدا بەهۆی زانینی ئیمەیڵەکەیەوە
        $sql="SELECT * FROM teacher_table WHERE phone_email = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('s', $phone_email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc(); // ئەو ڕۆیەی کە دێتەوە وەری ئەگرین و ئەیخەینە ڤاریەبڵی ئەڕەیەکەوە بە هەرناوێک
        
        $teacher_id = $row['teacher_id']; // ئای دی مامۆستاکە وەرئەگرین لەو ڕۆیەی هەمانە
        
        // $row هەرناوێکە خۆمان ئەینوسین
        // ئەوەی لەناو کەوانەکەیدا ئەینوسین ناوی ئەو فیڵدەیە کە لە داتابەیسەکە هەیە پێویستە هەمان ناو بێت
         // $teacher_id ڤاریەبڵێکە کە ئەو ئایدیەی ئەخەینە ناو، ئەم ڤاریەبڵە ئەکرێت بەهەر ناوێکەوە بێث

        $sql = "SELECT * FROM year_table WHERE grade = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('i', $grade);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
         // ئەگەر ئەو ساڵەی مامۆستاکە وانەی تیا ئەڵێتەوە لە تەیبڵی
         // year_table
         // دا بوونی نەبوو ئەوە ساڵەکە زیاد ئەکەین، هەرچەنە ئەبێت بوونی هەبێت بەڵام ئەگەر بوونی نەبوو با دروست ببێت
        if($result->num_rows <= 0){
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
               
          $year_id = $row['year_id'];
               
          // ئەگەر ئەو وانەیەشی ئەیڵێتەوە بوونی نەبوو ئەویش دروست ئەکەین لە تەیبڵی
          // subject_table دا
        $sql = "SELECT * FROM subject_table WHERE sub_name = ? AND year_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('si', $subject, $year_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if($result->num_rows <= 0){
            $sql = "INSERT INTO subject_table (sub_name, year_id) VALUES (?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("ss", $subject, $year_id);
            $stmt->execute();
        }
        
        // we will link the teacher to the subject in a temporary way
        // we will create it in temp_teacher then validate it in admin link
        // پاشان ئای دی مامۆستاکە و وانەکەی و ئەو ساڵەی دەرسی تێدا ئەڵێتەوە ئەیخەینە تەیبڵێکی کاتیەوە
        // ئەتوانین ڕاستەوخۆ بیخەینە
        // subject_Table ەوە
        // بەڵام بەم جۆرە سکیور ترە بۆی ئەگەر کەسێکی تر ئەو کۆدەی بەدەست کەوتبوو و بەناوی مامۆستاوە خۆی تۆمار کرد ڕاستەوخۆ نەتوانێت دەسەڵاتی هەبێت بەسەر بینینی کارتی قوتابیەکاندا
        // بۆیە سەرەتا زانیاریەکان هەلدەگرین لە تەیبڵێکدا بە کاتی، پاشان ئەدمین ئەچێت پشتڕاستی ئەکاتەوە کە بەڵێ ئەم مامۆستایە بەڕاستی مامۆستایەو زایناریەکانی ئەخاتە تەیبڵی 
        /// subject_Table ەوە
        $sql = "INSERT INTO temp_teacher (sub_name, teacher_id, year_id) VALUES (?,?,?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sss", $subject, $teacher_id, $year_id);
        $stmt->execute();
        
        mysqli_stmt_close($stmt); // هەموو کیۆریەکان دا خەینەوە
        mysqli_close($db); // کۆنەکشنەکە لەگەڵ داتابەیس لا ئەبەین
        
    header("Location: login.php"); 
    exit;
    }
    }
        }
  }
    
   ?>

<!DOCTYPE html>
<head>
<meta charset="UTF-8">
<title>Registration</title>
 <!-- Font Icon -->
    <link rel="stylesheet" href="../fonts/material-icon/css/material-design-iconic-font.min.css">
    
<link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.css">

<link rel="stylesheet" type="text/css" href="../css/menu.css">

    <!-- Main css -->
    <link rel="stylesheet" href="../css/style.css">
    
    
<script>

         // Function to show the text input element
        function showInput() {
            // Check if the radio button with the value "teacher" is selected
    if (document.getElementById("teacher").checked) {
       // Check if the input element already exists
      if (!document.getElementById("referral")) {
        // If the input element does not exist, create it
     var input = document.createElement("input");
      input.type = "text";
      input.name = "referral";
      input.id = "referral";
      input.placeholder = "Referral code";
      input.className = "form-control form-input";
      input.style.border = "dotted #d05ed9";
      input.required = true;

      // Append the text input element to the div
    var referralDiv = document.getElementById("referralDiv");
    referralDiv.insertBefore(input, referralDiv.firstChild);
    referralDiv.className = "form-group";
      }
    } else {
      // If the radio button is not selected, remove the input element from the div
      document.getElementById("referralDiv").removeChild(document.getElementById("referral"));
      document.getElementById("referralDiv").className ="";
    }
  }
  
   function capitalizeFirstLetter(input) {
        var value = input.value;
        value = value.charAt(0).toUpperCase() + value.slice(1);
        input.value = value;
    }
     </script>
     
     <script src="../vendor/jquery/jquery-3.6.3.min.js"></script>
  
  
<style>
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
	        <a href="s-registration.php"><button class="blackLink siteLink">REGISTER</button></a>
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

      <div class="main">
        <section class="signup">
            <div class="container">
                <div class="signup-content">
      <form id="signup-form" class="signup-form needs-validation" method="POST" name="student" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" novalidate>
          <h2 class="form-title">Registeration form</h2>
        
        <!-- 
        // <?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>
        ئەم کۆدە واتە کە فۆڕمەکە سەبمیت کرا و کلیک لە بەتنەکە کرا، با زانیاریەکانی ناو فۆڕمەکە بڕواتەوە بۆ هەمان پەیج
        لەناو 
        action=""
        هەرچیەک بنوسی فۆڕمەکە ئەچێتە ئەو شوێنە
        
        // htmlspecialchars
        ئەمە بۆ ئەوە کە هەر کۆدێکی ئێچ تی ئێم ئێڵ لەناو ئەوەدا بوو نەیکات بە کۆد و وەکو خۆی بینوسێت بۆ نمونە:
        //    echo "hello <b>workd</b>"
        ئەم کۆدە وشەی دووەمی بە تۆخی دەرەچێت لەبەر ئەوە کۆدی ئێچ تی ئێم ئێڵی تیایە
        بەڵام ئەمە:
        // echo htmlspecialchars("hello <b>workd</b>");
        کۆدەکە وەکو خۆی دەرەچێتەوە بەبێ لابردنی تاگەکان و بەبێ کردنی بە بۆڵد
        هەرچەندە لە کۆدەکەی ناو فۆڕمەکەدا هیج تاگێکی ئێچ تی ئێم ئێڵی تیا نیە بۆیە ئەتوانی بەم جۆرەش بینوسی:
        // action="<?php echo $_SERVER['PHP_SELF']; ?>"
        
        
        // onSubmit="return check();" 
        ئەم کۆدە ئەڵێت کە ئەم فۆڕمە سەبمیت کرا بڕۆ بۆ فەنکشنی چێک کە لەناو سکریپتا لەسەرەوە نوسیومانە تا بزانێت هەردوو پاسۆردەکەی داخڵی کردووە یەکسانن
        
        -->
         <div class="form-outline">
        <div class="form-group">
            <input class="form-control form-input" type="text" id="first_name" name="first_name" value="<?php echo $first_name; ?>" placeholder="First name" pattern="(^((?:[A-Z](?:('|(?:[a-z]{1,3}))[A-Z])?[a-z]+)|(?:[A-Z]\.))(?:([ -])((?:[A-Z](?:('|(?:[a-z]{1,3}))[A-Z])?[a-z]+)|(?:[A-Z]\.)))?$)" oninput="capitalizeFirstLetter(this)" required>
            <div class="invalid-feedback">Please provide your first name.</div>
            </div>
              </div>
            <!-- 
            // required
            ئەم کۆدە واتە ئەو فیڵدە پێویستە و نابێ بە بەتاڵی جێ بهێڵرێ
            -->
    
        <div class="form-outline">
         <div class="form-group">
        <input class="form-control form-input" type="text" id="last_name" name="last_name" value="<?php echo $last_name; ?>" placeholder="Last name" pattern="(^((?:[A-Z](?:('|(?:[a-z]{1,3}))[A-Z])?[a-z]+)|(?:[A-Z]\.))(?:([ -])((?:[A-Z](?:('|(?:[a-z]{1,3}))[A-Z])?[a-z]+)|(?:[A-Z]\.)))?$)" oninput="capitalizeFirstLetter(this)" required>
        <div class="invalid-feedback">Please provide your last name.</div>
            </div>
        </div>

        <div class="form-outline">
         <div class="form-group">
        <input class="form-control form-input" type="text" name="phone_email"  id="phone_email" value="<?php echo $phone_email; ?>" placeholder="Phone or Email"  pattern="([a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$)|[+]?[0-9]{8}[0-9]+" required>
         <div class="invalid-feedback original">Please provide a valid phone/email.</div>
            </div>          
        </div>
      
         <div class="form-outline">
         <div class="form-group">
        <input class="form-control form-input" type="password" name="password"  id="password" minlength="8" maxlength="20" placeholder="Password" required>
        <div  class="progress" style="height: 5px;">
    <div id="progressbar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 10%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
      
    </div>
		</div>
		
		<small id="passwordHelpBlock" class="form-text text-muted">
					Your password must be 8-20 characters long,  must contain special characters "!@#$%&*_?", numbers, lower and upper letters only.
		</small>

				<div id="feedbackin" class="valid-feedback">
					Strong Password!
				</div>
				<div id="feedbackirn" class="invalid-feedback">
					Minimum 8 characters,
					Number, special character 
					Caplital Letter and Small letters
				</div>
        </div>
</div>
        
        <div class="form-outline">
         <div class="form-group">
        <input class="form-control form-input" type="password" name="confirm_password" id="confirm_password" minlength="8" maxlength="20" placeholder="Confirm password"  required>

            <div style="font-size: 80%;" id="CheckPasswordMatch"></div>
        </div>
</div>
        
        <div class="form-inline mb-3 mt-3">
        <div class="form-group">
        <select name="gender" class="form-control" style="width: fit-content" value="<?php echo $gender; ?>" required>
        <option disabled selected value>--Gender--</option>
          <option value="Male">Male</option>
           <option value="Female">Female</option>
            <option value="Other">Other</option>
           </select>
              <div class="invalid-feedback">Please choose your gender.</div>
            </div>
           </div>
        
         <div class="form-group">
         <input type="radio" id="student" name="radio" value="student" onclick="showInput()"  checked> Student
        <input type="radio" id="teacher" name="radio" vaule="teacher" onclick="showInput()" > Teacher
        </div>
        
         <div id="referralDiv">
         <div class="invalid-feedback original">Please enter your referral code.</div>
        </div>
        
        <div class="form-outline">
         <div class="form-group">
       <input class="form-control form-input" id="date_and_birth" value="<?php echo $date_and_birth; ?>" placeholder="Birth date" name="date_and_birth" onfocus="(this.type='date')" onblur="(this.type='text')"/ required>
        <div class="invalid-feedback">Please provide your date of birth.</div>
            </div>
       </div>

        
        <div class="form-outline">
         <div class="form-group">
        <input class="form-control form-input" id="address"  type="text" name="address"  value="<?php echo $address; ?>" placeholder="Address"  required>
          <div class="invalid-feedback">Please provide a valid address.</div>
            </div>
        </div>
        
             <div class="form-group">
               <button  id="submit" class="form-submit" name="submit" type="submit" >Register</button>
               </div>
               <p class="loginhere">
               Already have an account? <a class="loginhere-link" href="login.php">Login here</a></p>
      </form>
      
         </div>
            </div>
        </section>

    </div>
    
     <!-- JS -->
     
    
    <script src="../js/validation.js"></script>
    
    <script src="../vendor/jquery/jquery-3.6.3.min.js"></script>
    <script src="../vendor/jquery/jquery-3.6.3.js"></script>
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../js/main.js"></script>
    <script src="../vendor/bootstrap/js/popper.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.js"></script>
	<script src="../vendor/jquery/jquery-3.6.3.slim.js"></script>
<script src="../js/menu.js"></script>

</body>
</html>
