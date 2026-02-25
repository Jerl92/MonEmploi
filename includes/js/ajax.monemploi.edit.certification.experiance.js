function monemploi_edit_certification_experiance($) {
	jQuery('.edit-certification-experiance').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		
		var start_sortable = null;
		
		jQuery(".is-editing-certification-experiance").toggleClass("edit");
		
		var is_editing = jQuery(".is-editing-certification-experiance").text();
		
		if ( jQuery(".is-editing-certification-experiance").hasClass( "edit" ) ) {
			jQuery('.is-editing-certification-experiance').html('true');
		} else {
			jQuery('.is-editing-certification-experiance').html('false');
		}

		jQuery.ajax({
			type: 'post',
			url: certification_edit_experiance_monemploi_ajax_url,
			data: {
				'is_editing': is_editing,
				'action': 'edit_certification_experiance'
            },
            dataType: 'JSON',
			success: function(data) {
								  jQuery( ".profile-wrapper-certification-experiance" ).sortable({
					        start: function(event, ui) { 
					        	start_sortable = ui.item.index();
					        },
					   	update: function(event, ui) { 
							jQuery.ajax({
								type: 'post',
								url: sortable_reindex_monemploi_ajax_url,
								data: {
									'typeof': 'certification',
									'start': start_sortable,
									'update': ui.item.index(),
									'action': 'sortable_reindex'
								},
								dataType: 'JSON',
								success: function(data) {
								jQuery('.wrapper-certification').children('.sortable-certification').each(function(i) {
									$(this).html(i);
								});
								},
								error: function(error) {
									console.log(error);
								}
							})				            	
					        },
                        			handle: ".handle-certification"
					});
                    			jQuery( ".profile-wrapper-certification-experiance" ).disableSelection();
					if ( jQuery(".is-editing-certification-experiance").hasClass( "edit" ) ) {
						jQuery( ".profile-wrapper-certification-experiance" ).sortable( "enable" );
					} else {
						jQuery( ".profile-wrapper-certification-experiance" ).sortable( "disable" );
					}
				jQuery('.profile-wrapper-certification-experiance').html('');
				jQuery('.profile-wrapper-certification-experiance').html(data);
				if ( jQuery(".is-editing-certification-experiance").hasClass( "edit" ) ) {
					jQuery('.is-editing-certification-experiance').html('true');
				} else {
					jQuery('.is-editing-certification-experiance').html('false');
				}
				monemploi_add_certification_experiance($);
				monemploi_delete_certification_experiance($);
				monemploi_edit_certification_experiance($);
				monemploi_save_certification_experiance($);
				$('[data-toggle="datepickerstartcertification_"]').datepicker();
				$('[data-toggle="datepickerendcertification_"]').datepicker();

			},
			error: function(error) {
				console.log(error);
			}
        })
	});
	$('.sortable-certification-up').click(function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		var item = $(this).closest(".certification-experiance-unique-string");
		var prevCard = item.prev('div');
		item.insertBefore(prevCard);
		var itemindex = parseInt(item.index());
		var sum = itemindex + 1;
		jQuery.ajax({
			type: 'post',
			url: sortable_reindex_monemploi_ajax_url,
			data: {
				'typeof': 'certification',
				'start': sum,
				'update': item.index(),
				'action': 'sortable_reindex'
			},
			dataType: 'JSON',
			success: function(data) {
				jQuery('.wrapper-certification').children('.sortable-certification').each(function(i) {
					$(this).html(i);
				});
			},
			error: function(error) {
				console.log(error);
			}
		})	
	});
	$('.sortable-certification-down').click(function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		var item = $(this).closest(".certification-experiance-unique-string");
		var nextCard = item.next('div');
		item.insertAfter(nextCard);
		var itemindex = parseInt(item.index());
		var sum = itemindex - 1;
		jQuery.ajax({
			type: 'post',
			url: sortable_reindex_monemploi_ajax_url,
			data: {
				'typeof': 'certification',
				'start': sum,
				'update': item.index(),
				'action': 'sortable_reindex'
			},
			dataType: 'JSON',
			success: function(data) {
				jQuery('.wrapper-certification').children('.sortable-certification').each(function(i) {
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
	monemploi_edit_certification_experiance($);
});