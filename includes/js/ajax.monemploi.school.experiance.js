function monemploi_save_school_experiance($) {
	jQuery('.save-school-experiance').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		
		var $this = jQuery(this),
		    unique = $this.data('object-id');
		var school_title = jQuery('.'+unique).children('.monemploi_add_school_experiance_title').val();
		var school_name = jQuery('.'+unique).children('.monemploi_add_school_experiance_name').val();
		var school_description = jQuery('.'+unique).children('.monemploi_add_school_experiance_description').val();
		var dateschoolstart = jQuery('.'+unique).children('.datepickerstartschool').val();
		var dateschoolend = jQuery('.'+unique).children('.datepickerendschool').val();
        
		jQuery.ajax({
			type: 'post',
			url: school_experiance_monemploi_ajax_url,
			data: {
				'school_title': school_title,
				'school_name': school_name,
				'school_description': school_description,
				'unique': unique,
				'dateschoolstart': dateschoolstart,
				'dateschoolend': dateschoolend,
				'action': 'monemploi_save_school_experiance'
            },
            dataType: 'JSON',
			success: function(data) {
				jQuery('.'+unique).html('');
				jQuery('.'+unique).html(data);
				monemploi_edit_school_experiance($);
				monemploi_add_school_experiance($);
              	monemploi_delete_school_experiance($);
			},
			error: function(error) {
				console.log(error);
			}
        })
	});
}

function monemploi_add_school_experiance($) {
	jQuery('.add-school-experiance').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();

		jQuery.ajax({
			type: 'post',
			url: school_experiance_monemploi_ajax_url,
			data: {
				'action': 'monemploi_add_school_experiance'
            },
            dataType: 'JSON',
			success: function(data) {
				jQuery('.profile-wrapper-school-experiance').prepend(data);
			        monemploi_save_school_experiance($);
			        monemploi_delete_school_experiance($);
			        jQuery('[data-toggle="datepickerstartschool"]').datepicker();
				jQuery('[data-toggle="datepickerendschool"]').datepicker();
			},
			error: function(error) {
				console.log(error);
			}
        })
	});
}

jQuery(document).ready(function($) {
	monemploi_add_school_experiance($);
});