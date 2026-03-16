function monemploi_delete_school_experiance($) {
	jQuery('.delete-school-experiance').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		
		        var $this = jQuery(this),
			        object_id = $this.data('object-id');
			        
		if (confirm('Êtes-vous sur de vouloir supprimer cette experience ?')) {
			jQuery.ajax({
				type: 'post',
				url: school_delete_experiance_monemploi_ajax_url,
				data: {
					'object_id': object_id,
					'action': 'delete_school_experiance'
	            },
	            dataType: 'JSON',
				success: function(data) {
					jQuery('.'+object_id).html('Lecole #'+data+'a ete supprimer.');
					setTimeout(function() {
						jQuery('.'+object_id).html('');
					}, 5000);
					jQuery('.wrapper-school').children('.sortable-school').each(function(i) {
						$(this).html(i);
					});				
					monemploi_edit_school_experiance($);
					monemploi_add_school_experiance($);
					monemploi_delete_school_experiance($);
					monemploi_save_school_experiance($);
				},
				error: function(error) {
					console.log(error);
				}
		        })
	        }
	});
}


jQuery(document).ready(function($) {
	monemploi_delete_school_experiance($);
});