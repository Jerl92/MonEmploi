function comment_chat_see($){          
	if(jQuery('.user-chat-history')) {
	    
	        var chatid = jQuery('.chat-id').html();
	        var userid = jQuery('.user-id').html();

            jQuery.ajax({
				type: 'post',
				url: chat_see_monemploi_ajax_url,
				data: {
				    'chatid': chatid,
				    'userid': userid,
					'action': 'chat_see'
	            },
	            dataType: 'JSON',
				success: function(data) {
				    //
				},
				error: function(error) {
					console.log(error);
				}
	        })
	}
}

jQuery(document).ready(function($) {
	comment_chat_see($);
    var myTimer = setTimeout(function() {
        comment_chat_see($);
    }, 2500);
    jQuery(window).focus(function(e) {
    	comment_chat_see($);
        var myTimer = setTimeout(function() {
            comment_chat_see($);
        }, 2500);
    });
    jQuery(window).blur(function(e) {
	    clearTimeout(myTimer);
	});
});