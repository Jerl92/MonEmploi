function chat_widget($){          
	if(jQuery('.chat-menu-widget')) {

			jQuery.ajax({
				type: 'post',
				url: chat_widget_monemploi_ajax_url,
				data: {
					'action': 'chat_widget'
	            },
	            dataType: 'JSON',
				success: function(data) {
				    jQuery('.chat-menu-widget').html('');
				    jQuery('.chat-menu-widget').html(data);
				    var myWidget = setTimeout(function() {
                        chat_widget($);
				    }, 2500);
				},
				error: function(error) {
					console.log(error);
				}
	        })
	}
}

jQuery(document).ready(function($) {
	chat_widget($);
});