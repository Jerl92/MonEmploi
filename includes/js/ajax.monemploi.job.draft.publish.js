function job_draft_to_publish($) {
	jQuery('.job-draft-to-publish').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		
		        var $this = jQuery(this),
			        object_id = $this.data('object-id');

		jQuery.ajax({
			type: 'post',
			url: job_draft_publish_monemploi_ajax_url,
			data: {
				'object_id': object_id,
				'action': 'job_draft_to_publish'
            },
            dataType: 'JSON',
			success: function(data) {
				jQuery('.draft-or-publish').html('');
				jQuery('.draft-or-publish').html(data);
				job_draft($);
				job_draft_to_publish($);
			},
			error: function(error) {
				console.log(error);
			}
        })
	});
}


jQuery(document).ready(function($) {
	job_draft_to_publish($);
});