function monemploi_edit_job_experiance($) {
	jQuery('.edit-job-experiance').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		
		var start_sortable = null;
		
		jQuery(".is-editing-job-experiance").toggleClass("edit");
		
		var is_editing = jQuery(".is-editing-job-experiance").text();

		jQuery.ajax({
			type: 'post',
			url: job_edit_experiance_monemploi_ajax_url,
			data: {
				'is_editing': is_editing,
				'action': 'edit_job_experiance'
            },
            dataType: 'JSON',
			success: function(data) {
					  jQuery( ".profile-wrapper-job-experiance" ).sortable({
					        start: function(event, ui) { 
					        	start_sortable = ui.item.index();
					        },
					   	update: function(event, ui) { 
							jQuery.ajax({
								type: 'post',
								url: sortable_reindex_monemploi_ajax_url,
								data: {
									'typeof': 'job',
									'start': start_sortable,
									'update': ui.item.index(),
									'action': 'sortable_reindex'
								},
								dataType: 'JSON',
								success: function(data) {
								jQuery('.wrapper-job').children('.sortable-job').each(function(i) {
									$(this).html(i);
								});
								},
								error: function(error) {
									console.log(error);
								}
							})				            	
					        },
                        			handle: ".handle-job"
					});
                    			jQuery( ".profile-wrapper-job-experiance" ).disableSelection();
					if ( jQuery(".is-editing-job-experiance").hasClass( "edit" ) ) {
						jQuery( ".profile-wrapper-job-experiance" ).sortable( "enable" );
					} else {
						jQuery( ".profile-wrapper-job-experiance" ).sortable( "disable" );
					}
				jQuery('.profile-wrapper-job-experiance').html('');
				jQuery('.profile-wrapper-job-experiance').html(data);
				if ( jQuery(".is-editing-job-experiance").hasClass( "edit" ) ) {
					jQuery('.is-editing-job-experiance').html('true');
				} else {
					jQuery('.is-editing-job-experiance').html('false');
				}
				monemploi_add_job_experiance($);
				monemploi_delete_job_experiance($);
				monemploi_edit_job_experiance($);
				monemploi_save_job_experiance($);
				$('[data-toggle="datepickerstartjob_"]').datepicker();
				$('[data-toggle="datepickerendjob_"]').datepicker();
				jQuery('input.still-working').change(function() {
				    	if ($(this).is(':checked')) {
				    		jQuery('[data-toggle="datepickerendjob_"]').val('now');
						jQuery('[data-toggle="datepickerendjob_"]').attr('disabled', true);
					    } else {
						jQuery('[data-toggle="datepickerendjob_"]').attr('disabled', false);
				    	}
				});
			},
			error: function(error) {
				console.log(error);
			}
        })
	});	
	$('.sortable-job-up').click(function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		var item = $(this).closest(".job-experiance-unique-string");
		var prevCard = item.prev('div');
		item.insertBefore(prevCard);
		var itemindex = parseInt(item.index());
		var sum = itemindex + 1;
		jQuery.ajax({
			type: 'post',
			url: sortable_reindex_monemploi_ajax_url,
			data: {
				'typeof': 'job',
				'start': sum,
				'update': item.index(),
				'action': 'sortable_reindex'
			},
			dataType: 'JSON',
			success: function(data) {
				jQuery('.wrapper-job').children('.sortable-job').each(function(i) {
					$(this).html(i);
				});
			},
			error: function(error) {
				console.log(error);
			}
		})	
	});
	$('.sortable-job-down').click(function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		var item = $(this).closest(".job-experiance-unique-string");
		var nextCard = item.next('div');
		item.insertAfter(nextCard);
		var itemindex = parseInt(item.index());
		var sum = itemindex - 1;
		jQuery.ajax({
			type: 'post',
			url: sortable_reindex_monemploi_ajax_url,
			data: {
				'typeof': 'job',
				'start': sum,
				'update': item.index(),
				'action': 'sortable_reindex'
			},
			dataType: 'JSON',
			success: function(data) {
				jQuery('.wrapper-job').children('.sortable-job').each(function(i) {
					$(this).html(i);				
				});
			},
			error: function(error) {
				console.log(error);
			}
		})
	});
}


jQuery(document).ready(function($) {
	monemploi_edit_job_experiance($);
});