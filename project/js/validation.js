 <!-- validation -->

   // Example starter JavaScript for disabling form submissions if there are invalid fields
(() => {
  'use strict';

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  const forms = document.querySelectorAll('.needs-validation');

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms).forEach((form) => {
    form.addEventListener('submit', (event) => {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false);
  });
})();

     
     
     
     
     
     
     
     
     
     <!-- password validation -->
     
     var disableChecker = true;
     (function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      // making sure password enters the right characters
		document.getElementById('password').addEventListener('keypress', function(event){
			console.log("keypress");
			console.log("event.which: " + event.which);
			var checkx = true;
			var chr = String.fromCharCode(event.which);
			console.log("char: " + chr);
			  

			var matchedCase = new Array();
			matchedCase.push("[!@#$%&*_?]"); // Special Charector
			matchedCase.push("[A-Z]");      // Uppercase Alpabates
			matchedCase.push("[0-9]");      // Numbers
			matchedCase.push("[a-z]");

			for (var i = 0; i < matchedCase.length; i++) {
				if (new RegExp(matchedCase[i]).test(chr)) {
					console.log("checkx: is true");					
					checkx = false;
				}
			}	
      
      if(document.getElementById('password').value.length >= 20)
        checkx = true;
			
			if ( checkx ) {
                event.preventDefault();
              	event.stopPropagation();	  
          	}

		});
    
    //Validate Password to have more than 8 Characters and A capital Letter, small letter, number and special character
		// Create an array and push all possible values that you want in password
		var matchedCase = new Array();
		matchedCase.push("[$@$$!%*#?&]"); // Special Charector
		matchedCase.push("[A-Z]");      // Uppercase Alpabates
		matchedCase.push("[0-9]");      // Numbers
		matchedCase.push("[a-z]");     // Lowercase Alphabates
		

		document.getElementById('password').addEventListener('keyup', function(){
		
		var messageCase = new Array();
		messageCase.push(" Special Charector"); // Special Charector
		messageCase.push(" Upper Case");      // Uppercase Alpabates
		messageCase.push(" Numbers");      // Numbers
		messageCase.push(" Lower Case");     // Lowercase Alphabates

		var ctr = 0;
		var rti = "";
		for (var i = 0; i < matchedCase.length; i++) {
			if (new RegExp(matchedCase[i]).test(document.getElementById('password').value)) {
				if(i == 0) messageCase.splice(messageCase.indexOf(" Special Charector"), 1);
				if(i == 1) messageCase.splice(messageCase.indexOf(" Upper Case"), 1);
				if(i == 2) messageCase.splice(messageCase.indexOf(" Numbers"), 1);
				if(i == 3) messageCase.splice(messageCase.indexOf(" Lower Case"), 1);
				ctr++;
				//console.log(ctr);
				//console.log(rti);
			}
		}		
		
		
		//console.log(rti);
		// Display it
		var progressbar = 0;
		var strength = "";
		var bClass = "";
		switch (ctr) {
		case 0:
		case 1: 
			strength = "Way too Weak";
			progressbar = 15;
			bClass = "bg-danger";
			break;
		case 2:
			strength = "Very Weak";
			progressbar = 25;
			bClass = "bg-danger";
			break;
		case 3:
			strength = "Weak";	
			progressbar = 34;
			bClass = "bg-warning";			
			break;
		case 4:
			strength = "Medium";
			progressbar = 65;
			bClass = "bg-warning";						
			break;
		}
		
		if (strength == "Medium" && document.getElementById('password').value.length >= 8 ) {
			strength = "Strong";
			bClass = "bg-success";			
			document.getElementById('password').setCustomValidity("");			
		} else {
			document.getElementById('password').setCustomValidity(strength);
		}

		var sometext = "";

		if(document.getElementById('password').value.length < 8 ){
      var lengthI = 8 - document.getElementById('password').value.length;
			sometext += ` ${lengthI} more Characters, `;
		} 

		sometext += messageCase;
		console.log(sometext);
		
		console.log(sometext);

		if(sometext){
			sometext = " You Need" + sometext;
		}


		$("#feedbackin, #feedbackirn").text(strength + sometext);
		$("#progressbar").removeClass( "bg-danger bg-warning bg-success" ).addClass(bClass);
		var plength = document.getElementById('password').value.length ;
		if(plength > 0) progressbar += ((plength - 0) * 1.75) ;
		//console.log("plength: " + plength);
		var  percentage = progressbar + "%";
		document.getElementById('password').parentNode.classList.add('was-validated');
		//console.log("pacentage: " + percentage);
		$("#progressbar").width( percentage );

				if(document.getElementById('password').checkValidity() === true){
					document.getElementById('confirm_password').disabled = false;
					disableChecker = false;
				} else {
					document.getElementById('confirm_password').disabled = true;
					disableChecker = true;
				}
          		 
      
    }); 
      
    });
  }, false);
})();
     
     
     
     
     
     
     <!-- password confirmation validation -->

$(document).ready(function () {
    var disableChecker = false;
    $("#password, #confirm_password").on('keyup', function(){
        var password = $("#password").val();
        var confirm_password = $("#confirm_password").val();
        if (password != confirm_password && disableChecker === false) {
            $("#CheckPasswordMatch").html("Password does not match!").css("color","#dc3545");
            $("#confirm_password").css("border", "1px solid #dc3545");
        }
        else if (password == confirm_password && disableChecker === false) {
            $("#CheckPasswordMatch").html("Password match!").css("color","#28a745");
            $("#confirm_password").css("border", "1px solid #28a745");
        }
    });
    $("#signup-form").on("submit", function(event){
        var password = $("#password").val();
        var confirm_password = $("#confirm_password").val();
        if (password != confirm_password) {
            event.preventDefault();

        }
    });
});