function monemploi_save_status_job($) {
	jQuery('.save_status_candidacy').on('click', function(event) {
	    event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		
			var $this_i = jQuery(this),
               		i = $this_i.data('object-id');
        
			var $this = $("#status_"+i),
				object_id = $this.data('object-id');
				
			var status = $("#status_"+i).find(":selected").val();

		jQuery.ajax({
			type: 'post',
			url: save_status_monemploi_ajax_url,
			data: {
			    'object_id': object_id,
			    'status': status,
			    'i': i,
				'action': 'monemploi_save_status'
            },
            dataType: 'JSON',
			success: function(data) {
				jQuery('.save_status_candidacy_message').html('');
				jQuery('.save_status_candidacy_message').html(data);
				setTimeout(function() {
				    jQuery('.save_status_candidacy_message').html('');
				}, 2500);
				monemploi_save_status_job($);
			},
			error: function(error) {
				console.log(error);
			}
       		 })
	});
}

jQuery(document).ready(function($) {
	monemploi_save_status_job($);
});