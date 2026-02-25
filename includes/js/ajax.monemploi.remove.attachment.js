function monemploi_remove_attachment($) {
	jQuery('.delete-document-attachment').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		
		jQuery( $this ).html('test');
		
		        var $this = jQuery(this),
			        object_id = $this.data('object-id');

		jQuery.ajax({
			type: 'post',
			url: remove_attachment_monemploi_ajax_url,
			data: {
				'object_id': object_id,
				'action': 'remove_attachment'
            },
            dataType: 'JSON',
			success: function(data) {
				jQuery($this).parent().html('');
				jQuery($this).parent().html(data);
			},
			error: function(error) {
				console.log(error);
			}
        })
	});
}


jQuery(document).ready(function($) {
	monemploi_remove_attachment($);
});