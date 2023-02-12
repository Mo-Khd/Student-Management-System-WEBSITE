 $(document).ready(function () {

        // ANIMATEDLY DISPLAY THE NOTIFICATION COUNTER.
    
        $('#noti_Counter')
            .css({ opacity: 0 })
            .text(noti_num)      
            .css({ top: '-10px' })
            .animate({ top: '-2px', opacity: 1 }, 400);
      if (noti_num == 0) {
          $('#noti_Counter') .css({ display: 'none' });
      }
        $('#noti_Button').click(function () {

            // TOGGLE (SHOW OR HIDE) NOTIFICATION WINDOW.
            $('#notifications').fadeToggle('fast', 'linear', function () {
                if ($('#notifications').is(':hidden')) {
				 // $('#noti_Button').css('background-color', '#2E467C');
                }
				// CHANGE BACKGROUND COLOR OF THE BUTTON.
               // else $('#noti_Button').css('background-color', '#FFF');
            });

            $('#noti_Counter').fadeOut('slow');     // HIDE THE COUNTER.

            return false;
        });

        // HIDE NOTIFICATIONS WHEN CLICKED ANYWHERE ON THE PAGE.
        $(document).click(function () {
            $('#notifications').hide();

            // CHECK IF NOTIFICATION COUNTER IS HIDDEN.
            if ($('#noti_Counter').is(':hidden')) {
              
            // pass subIdArray variable to notifications as POST
            $.ajax({
            type: "POST",
            url: "notification_handling.php",
            data: { stIdArray: stIdArray, subIdArray: subIdArray }
            });
            }
        });
        
        // if the notification button clicked
        $('#notifications').click(function () {
            return false;
        });
    });