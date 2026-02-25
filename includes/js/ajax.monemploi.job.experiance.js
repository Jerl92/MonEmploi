function monemploi_save_job_experiance($) {
	jQuery('.save-job-experiance').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
        
        var $this = jQuery(this),
            unique = $this.data('object-id');
                    
		var job_title = jQuery('.'+unique).children('.monemploi_add_job_experiance_title').val();
		var job_name = jQuery('.'+unique).children('.monemploi_add_job_experiance_name').val();
		var job_description = jQuery('.'+unique).children('.monemploi_add_job_experiance_description').val();
		var datejobstart = jQuery('.'+unique).children('.datepickerstartjob').val();
		var datejobend = jQuery('.'+unique).children('.datepickerendjob').val();
		let formattedHtml = job_description.replace(/\r?\n/g, '<br>');
		
		if (jQuery('.'+unique).children('.still-working').is(':checked')) {
			var stillworking = 1;
        	} else {
        		var stillworking = 0;
        	}
        
		jQuery.ajax({
			type: 'post',
			url: job_experiance_monemploi_ajax_url,
			data: {
				'job_title': job_title,
				'job_name': job_name,
				'job_description': formattedHtml,
				'unique': unique,
				'datejobstart': datejobstart,
                		'datejobend': datejobend,
		 		'stillworking': stillworking,
				'action': 'monemploi_save_job_experiance'
            },
            dataType: 'JSON',
			success: function(data) {
				jQuery('.'+unique).html('');
				jQuery('.'+unique).html(data);
				monemploi_edit_job_experiance($);
				monemploi_add_job_experiance($);
                		monemploi_delete_job_experiance($);
                		monemploi_save_job_experiance($);
			},
			error: function(error) {
				console.log(error);
			}
        })
	});
    
}

function monemploi_add_job_experiance($) {
	jQuery('.add-job-experiance').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();

		jQuery.ajax({
			type: 'post',
			url: job_experiance_monemploi_ajax_url,
			data: {
				'action': 'monemploi_add_job_experiance'
            },
            dataType: 'JSON',
			success: function(data) {
				jQuery('.profile-wrapper-job-experiance').prepend(data);
				monemploi_save_job_experiance($);
				monemploi_delete_job_experiance($);
				jQuery('[data-toggle="datepickerstartjob"]').datepicker();
				jQuery('[data-toggle="datepickerendjob"]').datepicker();
				
			},
			error: function(error) {
				console.log(error);
			}
		})
	});
}

jQuery(document).ready(function($) {
	monemploi_add_job_experiance($);
});