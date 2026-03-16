function monemploi_delete_certification_experiance($) {
	jQuery('.delete-certification-experiance').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		
		        var $this = jQuery(this),
			        object_id = $this.data('object-id');
			        
		if (confirm('Êtes-vous sur de vouloir supprimer cette experience ?')) {
			jQuery.ajax({
				type: 'post',
				url: certification_delete_experiance_monemploi_ajax_url,
				data: {
					'object_id': object_id,
					'action': 'delete_certification_experiance'
	            },
	            dataType: 'JSON',
				success: function(data) {
					jQuery('.'+object_id).html('La certification #'+data+'a ete supprimer.');
					setTimeout(function() {
						jQuery('.'+object_id).html('');
					}, 5000);
					jQuery('.wrapper-certification').children('.sortable-certification').each(function(i) {
						$(this).html(i);
					});
					monemploie_edit_certification_experiance($);
					monemploie_add_certification_experiance($);
					monemploie_delete_certification_experiance($);
					monemploi_save_certification_experiance($);
				},
				error: function(error) {
					console.log(error);
				}
		        })
		}
	});
}


jQuery(document).ready(function($) {
	monemploi_delete_certification_experiance($);
});