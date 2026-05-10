
jQuery(document).ready(function($) {
	// Function to play sound
	function playNotification() {
	    var audio = new Audio(wpNotification.PluginDirUrl+'audio/notification.mp3');
	    audio.play();
	}
	
	function countNotification() {
		// Trigger it with a jQuery event
		var count = jQuery(".chat-menu-widget > div").length;
		var myNotification = setTimeout(function() {
			var count_timeout = jQuery(".chat-menu-widget > div").length;
			if(count_timeout > count) {
				playNotification();
			}
			countNotification();
		}, 1000);	
	}    
	countNotification();
	
    function setCookie(cname, cvalue, exdays) {
        const d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        let expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }
    
    function getCookie(cname) {
	  let name = cname + "=";
	  let decodedCookie = decodeURIComponent(document.cookie);
	  let ca = decodedCookie.split(';');
	  for(let i = 0; i <ca.length; i++) {
	    let c = ca[i];
	    while (c.charAt(0) == ' ') {
	      c = c.substring(1);
	    }
	    if (c.indexOf(name) == 0) {
	      return c.substring(name.length, c.length);
	    }
	  }
	  return "";
	}
	
	Notification.requestPermission().then(function(permission) {
	        if (permission === 'granted') {
	            if(getCookie("NotificationGranted") != 1) {
		    	setCookie("NotificationGranted", parseInt(1), 64);
		            console.log('Permission granted!');
		            new Notification('Success!', { body: 'Notifications are now enabled.' });
		 	}
	        } else {
	            console.warn('Permission denied or dismissed.');
	        }
	});
});