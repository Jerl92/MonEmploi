function monemploie_send_get_job($) {
	jQuery('.ns-btn-ticket').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		
			var $this = jQuery(this),
				object_id = $this.data('object-id');

		jQuery.ajax({
			type: 'post',
			url: send_monemploi_ajax_url,
			data: {
			    'object_id': object_id,
				'action': 'monemploi_send_job'
            },
            dataType: 'JSON',
			success: function(data) {
				jQuery('.entry-meta-apply').html('');
				jQuery('.entry-meta-apply').html(data);
				monemploie_send_job($, object_id);
			},
			error: function(error) {
				console.log(error);
			}
        })
	});
}

jQuery(document).ready(function($) {
	monemploie_send_get_job($);
});