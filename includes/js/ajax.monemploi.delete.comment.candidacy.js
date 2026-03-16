function delete_comment_candidacy($) {
jQuery('.delete-comment-candidacy').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		
		var $this = jQuery(this),
			object_id = $this.data('object-id'),
			object_string = $this.data('object-string');
		
		if (confirm('Êtes-vous sur de vouloir supprimer ce commentaire ?')) {
			jQuery.ajax({
				type: 'post',
				url: delete_comment_candidacy_monemploi_ajax_url,
				data: {
					'object_id': object_id,
					'action': 'delete_comment_candidacy'
			},
			dataType: 'JSON',
				success: function(data) {
					jQuery('.'+object_string).html('');
					jQuery('.'+object_string).html(data);
					    setTimeout(function() {
					        jQuery('.'+object_string).remove();
					    }, 5000);
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
	delete_comment_candidacy($);
});