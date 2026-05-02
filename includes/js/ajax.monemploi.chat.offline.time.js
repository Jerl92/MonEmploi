function chat_offline_time($){          
	if(jQuery('.user-chat-history')) {
	    
	        var userid = jQuery('.user-id').html();

			jQuery.ajax({
				type: 'post',
				url: chat_offline_time_monemploi_ajax_url,
				data: {
				    'userid': userid,
					'action': 'chat_offline_time'
	            },
	            dataType: 'JSON',
				success: function(data) {
				    jQuery('.offline-time').html(data);
				    var myofflineTimer = setTimeout(function() {
                        chat_offline_time($);
				    }, 2500);
				},
				error: function(error) {
					console.log(error);
				}
	        })
	}
}

jQuery(document).ready(function($) {
	chat_offline_time($);
});