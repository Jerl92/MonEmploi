function job_delete($) {
	jQuery('.delete-job-emplois').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		
	        var $this = jQuery(this),
		        object_id = $this.data('object-id');
			        
		if (confirm('Êtes-vous sur de vouloir supprimer cette emploi ?')) {
			jQuery.ajax({
				type: 'post',
				url: job_delete_monemploi_ajax_url,
				data: {
					'object_id': object_id,
					'action': 'job_delete'
			},
			dataType: 'JSON',
				success: function(data) {
					jQuery('.entry-meta-job-wrapper').html('');
					jQuery('.entry-meta-job-wrapper').html(data);
				},
				error: function(error) {
					console.log(error);
				}
			})
        	}
	});
}


jQuery(document).ready(function($) {
	job_delete($);
});