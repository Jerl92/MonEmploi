function chat_online_status($){          
	if(jQuery('.user-chat-history')) {
	    
	        var userid = jQuery('.user-id').html();
	        
			jQuery.ajax({
				type: 'post',
				url: chat_online_status_monemploi_ajax_url,
				data: {
				    'userid': userid,
					'action': 'chat_online_status'
	            },
	            dataType: 'JSON',
				success: function(data) {
				    jQuery('.online-status').html('');
				    jQuery('.online-status').html(data);
				    var myOnlineTimer = setTimeout(function() {
                        chat_online_status($);
				    }, 1000);
				},
				error: function(error) {
					console.log(error);
				}
	        })
	}
}

jQuery(document).ready(function($) {
	chat_online_status($);
});