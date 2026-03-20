function monemploi_save_certification_experiance($) {
	jQuery('.save-certification-experiance').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		
        var $this = jQuery(this),
		unique = $this.data('object-id');
		var certification_title = jQuery('.'+unique).children('.monemploi_add_certification_experiance_title').val();
		var certification_name = jQuery('.'+unique).children('.monemploi_add_certification_experiance_name').val();
		var certification_description = jQuery('.'+unique).children('.monemploi_add_certification_experiance_description').val();
		var datecertificationstart = jQuery('.'+unique).children('.datepickerstartcertification').val();
		var datecertificationend = jQuery('.'+unique).children('.datepickerendcertification').val();
		      
		jQuery.ajax({
			type: 'post',
			url: certification_experiance_monemploi_ajax_url,
			data: {
				'certification_title': certification_title,
				'certification_name': certification_name,
				'certification_description': certification_description,
				'unique': unique,
				'datecertificationstart': datecertificationstart,
				'datecertificationend': datecertificationend,
				'action': 'monemploi_save_certification_experiance'
            },
            dataType: 'JSON',
			success: function(data) {
				jQuery('.'+unique).html('');
				jQuery('.'+unique).html(data);
				monemploie_edit_certification_experiance($);
				monemploie_add_certification_experiance($);
				monemploie_delete_certification_experiance($);
				monemploi_save_certification_experiance($);
			},
			error: function(error) {
				console.log(error);
			}
        })
	});
}

function monemploi_add_certification_experiance($) {
	jQuery('.add-certification-experiance').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
        
		jQuery.ajax({
			type: 'post',
			url: certification_experiance_monemploi_ajax_url,
			data: {
				'action': 'monemploi_add_certification_experiance'
            },
            dataType: 'JSON',
			success: function(data) {
				jQuery('.profile-wrapper-certification-experiance').prepend(data);
				monemploi_save_certification_experiance($);
				monemploi_delete_certification_experiance($);
				jQuery('[data-toggle="datepickerstartcertification"]').datepicker();
				jQuery('[data-toggle="datepickerendcertification"]').datepicker();
			},
			error: function(error) {
				console.log(error);
			}
        })
	});
}

jQuery(document).ready(function($) {
	monemploi_add_certification_experiance($);
});