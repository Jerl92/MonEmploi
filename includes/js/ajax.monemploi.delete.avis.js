function delete_avis($) {
jQuery('.delete-avis').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		
		var $this = jQuery(this),
			object_id = $this.data('object-id'),
			object_string = $this.data('object-string'),
			object_userid = $this.data('object-userid');
		
		if (confirm('Êtes-vous sur de vouloir supprimer cette avis ?')) {
			jQuery.ajax({
				type: 'post',
				url: delete_avis_monemploi_ajax_url,
				data: {
					'object_id': object_id,
					'object_userid': object_userid,
					'action': 'delete_avis'
				},
				dataType: 'JSON',
				success: function(data) {
					jQuery('.'+object_string).html('');
					jQuery('.'+object_string).html(data[0]);
					    setTimeout(function() {
					        jQuery('.'+object_string).remove();
				        }, 5000);
				    jQuery('.moyenne-score-wrapper').html(null);
				    jQuery('.moyenne-score-wrapper').html(data[1]);
				    jQuery('.avis-message-employeur-wrapper').html(data[2]);
					delete_avis($);
					avis_employeur_monemploi($);
					monemploi_function($);
				},
				error: function(error) {
					console.log(error);
				}
			})
		}
       });
}

jQuery(document).ready(function($) {
	delete_avis($);
});