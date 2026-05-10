function monemploi_chat_send_($) {
    if(jQuery('.user-chat-history')) {

		var userid = jQuery(".user-id").html();
		var chatid = jQuery(".chat-id").html();
		var message = null;
		var scrolled = false;	

		jQuery.ajax({
			type: 'post',
			url: chat_send_monemploi_ajax_url,
			data: {
				'userid': userid,
				'chatid': chatid,
				'message': message,
				'action': 'chat_send'
            },
            dataType: 'JSON',
			success: function(data) {
			    jQuery(".user-chat-history-wrapper").html('');
	            jQuery(".user-chat-history-wrapper").html(data);
	            
	            var element = jQuery('.user-chat-history-wrapper');

	            if(element.prop('scrollHeight') - element.scrollTop() > element.outerHeight()) {
	                scrolled = true;
	            }
	            
	            if(element.prop('scrollHeight') - element.scrollTop() == element.outerHeight()) {
		    	    scrolled = false;
		        }
	            
	            if(!scrolled) {
		            element.scrollTop(element.prop('scrollHeight'));
	            }
	            
	            setTimeout(function() {
                    monemploi_chat_send_($);
                }, 2500);
			},
			error: function(error) {
				console.log(error);
			}
        })
    }
}

function monemploi_chat_send($) {
	jQuery('.chat-message-send').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		
		var error = 0;
		
		var userid = jQuery(".user-id").html();
		var chatid = jQuery(".chat-id").html();
		var message = jQuery(".message-chat").val();
		
		jQuery('.chat-message-send').text('Veuillez patienter');
		jQuery('.chat-message-send').prop('disabled', true);
		
		if(message.length === 0) {
		    error = 1;
		    jQuery('.chat-message-send').text('Envoyer');
		    jQuery('.chat-message-send').prop('disabled', false);
		}
		

        if(error === 0) {
    		jQuery.ajax({
    			type: 'post',
    			url: chat_send_monemploi_ajax_url,
    			data: {
    				'userid': userid,
    				'chatid': chatid,
    				'message': message,
    				'action': 'chat_send'
                },
                dataType: 'JSON',
    			success: function(data) {
    	            jQuery(".user-chat-history-wrapper").html('');
    	            jQuery(".user-chat-history-wrapper").html(data);
    	            jQuery(".message-chat").val('');
    	            monemploi_chat_send($);
    	            jQuery('.chat-message-send').text('Envoyer');
		            jQuery('.chat-message-send').prop('disabled', false);
		            var element = jQuery('.user-chat-history-wrapper');
		            element.scrollTop(element.prop('scrollHeight'));
    			},
    			error: function(error) {
    				console.log(error);
    			}
            })
        }
	});	
}

jQuery(document).ready(function($) {
	monemploi_chat_send($);
	monemploi_chat_send_($);
});