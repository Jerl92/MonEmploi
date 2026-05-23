
// Source - https://stackoverflow.com/a/43325129
// Posted by Yogesh Mistry, modified by community. See post 'Timeline' for change history
// Retrieved 2026-05-12, License - CC BY-SA 3.0

function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
    return false;
};

	function myCalendar(curr_month = "curr") {
		  var datestring = "";
		  var timeunix = "";	
		  var tbody_html = "";
		  var td_class = "";
		  var word = [];
		  var weekday_count = 1;
		  var tr_count = 1;
		  var td_count = 1;
		  var offset_td = 0;
		  var counter = 1;
		  var month = master_data.months[curr_month];
		  var start_of_curr = master_data.day_start[curr_month];
		  var current_url = jQuery(".current-url").html();
		if(curr_month!=="curr"){
			if(master_data.months[curr_month]===11 && curr_month==="prev"){
				year--;
			}
			if(master_data.months[curr_month]===0 && curr_month==="next"){
				year++;
			}
		}
		
		  // Displays the current month in Strings and the actual year 
		  jQuery('.month-year').html("<h3 style='text-align: center;'>"+months_full[month]+" "+year+"</h3>");
		  jQuery('ul.horaire-job li').each(function(index, element) {
			var text = jQuery(element).text(); 
			var myArray = text.split("||");
			var datestart = new Date(myArray[2] * 1000);
			datestart.setDate(datestart.getDate() + 1);
			var formattedDatestart = (datestart.getMonth() + 1) + '/' + datestart.getDate() + '/' + datestart.getFullYear();
			var dateend = new Date(myArray[4] * 1000);
			dateend.setDate(dateend.getDate() + 1);
			var formattedDateend = (dateend.getMonth() + 1) + '/' + dateend.getDate() + '/' + dateend.getFullYear();
			word.push([myArray[0], formattedDatestart, myArray[3], formattedDateend, myArray[5]]);
		  });
		  				  
		  //To build the calendar body
		  while (counter <= daysOfMonth[month]) {
		  	var today = new Date();
		  	var dayOfMonth = today.getDate();
		  	datestring = months_full[month]+" "+counter+", "+year;
		  	timeunix = parseInt((new Date(datestring).getTime() / 1000).toFixed(0));
		  	if(weekday_count === 8){
		  		tbody_html += "</tr>";
		  		weekday_count = 1;
		  	}
		  	if(weekday_count === 1){
		  		tbody_html += "<tr>";
		  		tr_count++;
		  	}
	  		// prepend blank tds
		  	while(offset_td < start_of_curr){
		  		tbody_html += "<td class='empty'></td>";
		  		offset_td++;
		  		weekday_count++;
		  		td_count++;
		  	}
		  	if(month === d.getUTCMonth() && year === d.getUTCFullYear()){
		  		if(counter === dayOfMonth){
		  			td_class = "today";
		  		} else {
		  			td_class = "currentMonth";
		  		}
		  	}
		  	
			var date = new Date(timeunix * 1000);
			date.setDate(date.getDate());			
			var formattedDate = (date.getMonth() + 1) + '/' + date.getDate() + '/' + date.getFullYear();
			
			var timestart = null;
			var timeend = null;
			var results = jQuery.grep(word, function(item) {
			    if(jQuery.inArray(formattedDate, item) !== -1){
			        timestart = item[2];
			        timeend = item[4];
			    }
			    return jQuery.inArray(formattedDate, item) !== -1;
			});
			
		  	if (results.length == 1) {
				tbody_html += "<td class='"+td_class+"' style='text-align: center; width: 14.28%;'><a href='"+current_url+"?daytime="+timeunix+"'>"+counter+"</a><br><span>"+timestart+"</span><br><span>"+timeend+"</span></td>";
			} else if (results.length > 1) {
				tbody_html += "<td class='"+td_class+"' style='text-align: center; width: 14.28%;'><a href='"+current_url+"?daytime="+timeunix+"'>"+counter+"</a><br><span>"+results.length+" horaires</span></td>";
			} else {
				tbody_html += "<td class='"+td_class+"' style='text-align: center; width: 14.28%;'><a href='"+current_url+"?daytime="+timeunix+"'>"+counter+"</a></td>";
			}
		  	counter++;
		  	weekday_count++;
		  	td_count++;
		  }
			// append blank tds
			
			while((td_count-1) < (tr_count-1)*7){
				tbody_html += "<td class='empty'></td>";
				td_count++;
			}
			// setting master_data
			master_data.months.prev = month === 0 ? 11 : month - 1;
			master_data.months.next = month === 11 ? 0 : month + 1;
			// setting master_data.day_start
			master_data.day_start.curr = start_of_curr;
			var temp_prev_som = start_of_curr - daysOfMonth[master_data.months.prev]%7; 
			if(temp_prev_som < 0){
				temp_prev_som = 7 + temp_prev_som;
			}
			master_data.day_start.prev = temp_prev_som;
			master_data.day_start.next = weekday_count === 8 ? 0 : weekday_count-1;
			jQuery('.calendar_tbody').html(tbody_html);
		}
	
    function myCalendarpaieroll(curr_month = "curr") {
		  var datestring = "";
		  var timeunix = "";	
		  var tbody_html = "";
		  var td_class = "";
		  var word = [];
		  var weekday_count = 1;
		  var tr_count = 1;
		  var td_count = 1;
		  var offset_td = 0;
		  var counter = 1;
		  var month = master_data.months[curr_month];
		  var start_of_curr = master_data.day_start[curr_month];
		  var current_url = jQuery(".current-url").html();
		if(curr_month!=="curr"){
			if(master_data.months[curr_month]===11 && curr_month==="prev"){
				year--;
			}
			if(master_data.months[curr_month]===0 && curr_month==="next"){
				year++;
			}
		}
		
		  // Displays the current month in Strings and the actual year 
		  jQuery('.month-year').html("<h3 style='text-align: center;'>"+months_full[month]+" "+year+"</h3>");
		  jQuery('ul.gettimepay li').each(function(index, element) {
			var text = jQuery(element).text(); 
			var myArray = text.split("||");
            var date = new Date(myArray[0] * 1000);
			date.setDate(date.getDate());			
			var formattedDate = (date.getMonth() + 1) + '/' + date.getDate() + '/' + date.getFullYear();
			word.push([formattedDate, myArray[1]]);
		  });
		  				  
		  //To build the calendar body
		  while (counter <= daysOfMonth[month]) {
		  	var today = new Date();
		  	var dayOfMonth = today.getDate();
		  	datestring = months_full[month]+" "+counter+", "+year;
		  	timeunix = parseInt((new Date(datestring).getTime() / 1000).toFixed(0));
		  	if(weekday_count === 8){
		  		tbody_html += "</tr>";
		  		weekday_count = 1;
		  	}
		  	if(weekday_count === 1){
		  		tbody_html += "<tr>";
		  		tr_count++;
		  	}
	  		// prepend blank tds
		  	while(offset_td < start_of_curr){
		  		tbody_html += "<td class='empty'></td>";
		  		offset_td++;
		  		weekday_count++;
		  		td_count++;
		  	}
		  	if(month === d.getUTCMonth() && year === d.getUTCFullYear()){
		  		if(counter === dayOfMonth){
		  			td_class = "today";
		  		} else {
		  			td_class = "currentMonth";
		  		}
		  	}
		  	
			var date = new Date(timeunix * 1000);
			date.setDate(date.getDate());			
			var formattedDate = (date.getMonth() + 1) + '/' + date.getDate() + '/' + date.getFullYear();
			
			var timepay = [];
			var results = jQuery.grep(word, function(item) {
			    if(jQuery.inArray(formattedDate, item) !== -1){
                    timepay.push(item[1]);
			    }
			    return jQuery.inArray(formattedDate, item) !== -1;
			});
            
            var timepaycalc = 0;
            jQuery.each(timepay, function(index, value) {
                timepaycalc += parseFloat(value);
            });
            
            if(timepaycalc === 0){
                timepaycalc = '';
            }
			
            var roundnum = parseFloat(timepaycalc);
            
            var fixednum = roundnum.toFixed(2);
            
            if(isNaN(fixednum)){
                fixednum = '';
            } else {
                fixednum = fixednum+"$";
            }
			
			tbody_html += "<td class='"+td_class+"' style='text-align: center; width: 14.28%;'>"+counter+"</a><br><span>"+fixednum+"</span></td>";
		  	counter++;
		  	weekday_count++;
		  	td_count++;
		  }
			// append blank tds
			
			while((td_count-1) < (tr_count-1)*7){
				tbody_html += "<td class='empty'></td>";
				td_count++;
			}
			// setting master_data
			master_data.months.prev = month === 0 ? 11 : month - 1;
			master_data.months.next = month === 11 ? 0 : month + 1;
			// setting master_data.day_start
			master_data.day_start.curr = start_of_curr;
			var temp_prev_som = start_of_curr - daysOfMonth[master_data.months.prev]%7; 
			if(temp_prev_som < 0){
				temp_prev_som = 7 + temp_prev_som;
			}
			master_data.day_start.prev = temp_prev_som;
			master_data.day_start.next = weekday_count === 8 ? 0 : weekday_count-1;
			jQuery('.calendarpaieroll_tbody').html(tbody_html);
		}
		
		var d = new Date();
		var year = d.getUTCFullYear();
		var day = d.getUTCDay();
		var date = d.getUTCDate();
		var month = d.getUTCMonth();
		// our global object
		var master_data = {
			day_start: {
				prev: 0, curr: day - (date%7 - 1), next: 0
			},
			months: {
				prev: month-1, curr: month, next: month+1
			}
		};
		//Getting February Days Including The Leap Year
		if ((year % 100!=0) && (year% 4==0) || (year%400 == 0 )) {
			var febDays = 29;
		} else {
			var febDays = 28;
		}
		// Getting The Months and Days of the Week
		var daysOfMonth = [31, febDays, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
		var months_full = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

		var debug = false;
		jQuery(document).ready(function(){
			console.clear();
			myCalendar();
            		myCalendarpaieroll();
			var main_obj = master_data;
		  //Navigation Buttons
		  jQuery('.prev-month').click(function(){
		  	myCalendar("prev");	
		  	myCalendarpaieroll("prev");
		  });

		  jQuery('.next-month').click(function(){
		  	myCalendar("next");
		  	myCalendarpaieroll("next");
		  });
		  		  
		  	var dt = new Date();
		  	var minutes = dt.getMinutes();
			jQuery('.minutes-update').html(minutes);
		});