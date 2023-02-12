var stIdArray = [];
var subIdArray = [];
$.post("fetch_noti.php", { query: "SELECT st_id, sub_id, sub_name, user, last_updated, last_seen FROM notification, subject_table WHERE sub_id = subject_id  AND teacher_id = ? ORDER BY last_updated DESC" },
  function(data) {
    var count = 0;
    $.each(data, function(i, item) {
    var st_id = item.st_id;
    var user = item.user;
    var sub_id = item.sub_id;
    var subject = item.sub_name;
    var last_updated = item.last_updated;
    var div = $("<div>", {id: "content"}).html("Student id <strong>" + st_id + "</strong> marks has been updated " + "for <strong>" + subject + "</strong> by <strong>" + user + "</strong>" );
    var date = new Date(last_updated);
    var day = date.getDate();
	var month = date.toLocaleString("en-us", { month: "short" });
	var year = date.getFullYear().toString().slice(-2);
	var hour = date.getHours();
	var minute = date.getMinutes().toString().padStart(2, "0");
	var formattedDate = day + " " + month + " " + year + " at " + hour + ":" + minute;
	date = $("<div>", {id: "date"}).html(formattedDate);
      var wrapper = $("<div>");
      if (count < noti_num) {
        wrapper.addClass("highlighted");
        stIdArray.push(st_id);
        subIdArray.push(sub_id);
        count++;
      }
      wrapper.append(div, date);
      $("#contents").append(wrapper);
    });
  }, "json");
