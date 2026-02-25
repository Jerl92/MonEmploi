function monemploie_send_job($, object_id) {
	jQuery('.submit_cv').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		 	 
		var selectedValues = $('input[name="cv"]:checked').map(function() {
		    return this.value;
		}).get();
		 	 
		 var lettre_presentation = jQuery('.lettre_presentation').val();

		jQuery.ajax({
			type: 'post',
			url: send_cv_monemploi_ajax_url,
			data: {
				'object_id': object_id,
				'selectedValues': selectedValues,
				'lettre_presentation': lettre_presentation,
				'action': 'monemploi_send_cv_job'
            },
            dataType: 'JSON',
			success: function(data) {
				jQuery('.entry-meta-apply').html('');
				jQuery('.entry-meta-apply').html(data);
			},
			error: function(error) {
				console.log(error);
			}
        })
	});
}