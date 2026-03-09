function comment_candidacy_reply($){                   
	if(jQuery('.candidacy-response-cards')) {
        
            		var object_id = jQuery('.candidacy-response-cards').data('object-id');
			var object_count = jQuery('.candidacy-response-cards-wrapper').length;
			
			jQuery.ajax({
				type: 'post',
				url: comment_candidacy_reply_monemploi_ajax_url,
				data: {
					'object_id': object_id,
					'object_count': object_count,
					'action': 'comment_candidacy_reply'
	            },
	            dataType: 'JSON',
				success: function(data) {
					jQuery('.candidacy-response-cards').append(data);
					comment_candidacy_save($);
					delete_comment_candidacy($);
					setTimeout(function(){ comment_candidacy_reply($); }, 5000);
				},
				error: function(error) {
					console.log(error);
				}
	        })
	}
    
}

jQuery(document).ready(function($) {
	comment_candidacy_reply($);
});