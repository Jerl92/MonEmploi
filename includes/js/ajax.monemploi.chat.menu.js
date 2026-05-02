function chat_menu($){          
	if(jQuery('.user-chat-menu')) {

			jQuery.ajax({
				type: 'post',
				url: chat_menu_monemploi_ajax_url,
				data: {
					'action': 'chat_menu'
	            },
	            dataType: 'JSON',
				success: function(data) {
				    jQuery('.chat-menu-wrapper').html('');
				    jQuery('.chat-menu-wrapper').html(data);
				    var myMenuTimer = setTimeout(function() {
                        chat_menu($);
				    }, 2500);
				},
				error: function(error) {
					console.log(error);
				}
	        })
	}
}

jQuery(document).ready(function($) {
	chat_menu($);
});