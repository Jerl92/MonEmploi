function chat_nodification($){          
	if(jQuery('.chat-menu-widget')) {

			jQuery.ajax({
				type: 'post',
				url: chat_nodification_monemploi_ajax_url,
				data: {
					'action': 'chat_nodification'
	            },
	            dataType: 'JSON',
				success: function(data) {
				    jQuery.each(data, function(index, value) {
    				    if (Notification.permission === "granted") {
                            new Notification(value['name'], {
                                body: value['text']
                            });
                        }
                    });
				    var myNodification = setTimeout(function() {
                        chat_nodification($);
				    }, 2500);
				},
				error: function(error) {
					console.log(error);
				}
	        })
	}
}

jQuery(document).ready(function($) {
	chat_nodification($);
});