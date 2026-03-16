function monemploi_delete_job_experiance($) {
	jQuery('.delete-job-experiance').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		
		        var $this = jQuery(this),
			        object_id = $this.data('object-id');

		if (confirm('Êtes-vous sur de vouloir supprimer cette experience ?')) {

				jQuery.ajax({
					type: 'post',
					url: job_delete_experiance_monemploi_ajax_url,
					data: {
						'object_id': object_id,
						'action': 'delete_job_experiance'
		            },
		            dataType: 'JSON',
					success: function(data) {
						jQuery('.'+object_id).html('Lexperiance #'+data+'a ete supprimer.');
						setTimeout(function() {
							jQuery('.'+object_id).html('');
						}, 5000);
						jQuery('.wrapper-job').children('.sortable-job').each(function(i) {
							$(this).html(i);
						});
						monemploi_edit_job_experiance($);
						monemploi_add_job_experiance($);
						monemploi_delete_job_experiance($);
						monemploi_save_job_experiance($);
					},
					error: function(error) {
						console.log(error);
					}
		        })
		}
	});
}


jQuery(document).ready(function($) {
	monemploi_delete_job_experiance($);
});