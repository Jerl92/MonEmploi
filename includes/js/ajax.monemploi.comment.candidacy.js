function comment_candidacy_save($){                    
	jQuery('#submit_response').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
        
       		var $this = jQuery(this),
            		object_id = $this.data('object-id');

		var comment = jQuery('#write-message').val();
		var numItems = jQuery('.candidacy-response-cards-wrapper').length;
		
		if(jQuery('#write-message').val().length > 3) {
			
			jQuery('#submit_response').text("Veuillez patienter");
			jQuery('#submit_response').prop("disabled", true);
			
			jQuery.ajax({
				type: 'post',
				url: comment_candidacy_monemploi_ajax_url,
				data: {
					'comment': comment,
					'object_id': object_id,
					'numItems': numItems,
					'action': 'comment_candidacy_save'
	            },
	            dataType: 'JSON',
				success: function(data) {
					jQuery('.candidacy-response-cards').append(data);
					jQuery('#write-message').val(null);
					jQuery('#submit_response').text("Soumettre");
					jQuery('#submit_response').prop("disabled", false);
					comment_candidacy_save($);
					delete_comment_candidacy($);
				},
				error: function(error) {
					console.log(error);
				}
	        })
	}
	});
    
}

jQuery(document).ready(function($) {
	comment_candidacy_save($);
});