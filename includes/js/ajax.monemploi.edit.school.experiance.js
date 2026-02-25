function monemploi_edit_school_experiance($) {
	jQuery('.edit-school-experiance').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		
		var start_sortable = null;
		
		jQuery(".is-editing-school-experiance").toggleClass("edit");
		
		var is_editing = jQuery(".is-editing-school-experiance").text();

		jQuery.ajax({
			type: 'post',
			url: school_edit_experiance_monemploi_ajax_url,
			data: {
				'is_editing': is_editing,
				'action': 'edit_school_experiance'
            },
            dataType: 'JSON',
			success: function(data) {
			     jQuery( ".profile-wrapper-school-experiance" ).sortable({
					        start: function(event, ui) { 
					        	start_sortable = ui.item.index();
					        },
					   	update: function(event, ui) { 
							jQuery.ajax({
								type: 'post',
								url: sortable_reindex_monemploi_ajax_url,
								data: {
									'typeof': 'school',	
									'start': start_sortable,
									'update': ui.item.index(),
									'action': 'sortable_reindex'
								},
								dataType: 'JSON',
								success: function(data) {
								jQuery('.wrapper-school').children('.sortable-school').each(function(i) {
									$(this).html(i);
								});
								},
								error: function(error) {
									console.log(error);
								}
							})				            	
					        },
                        			handle: ".handle-school"
					});
                    			jQuery( ".profile-wrapper-school-experiance" ).disableSelection();
					if ( jQuery(".is-editing-school-experiance").hasClass( "edit" ) ) {
						jQuery( ".profile-wrapper-school-experiance" ).sortable( "enable" );
					} else {
						jQuery( ".profile-wrapper-school-experiance" ).sortable( "disable" );
					}
				jQuery('.profile-wrapper-school-experiance').html('');
				jQuery('.profile-wrapper-school-experiance').html(data);
				if ( jQuery(".is-editing-school-experiance").hasClass( "edit" ) ) {
					jQuery('.is-editing-school-experiance').html('true');
				} else {
					jQuery('.is-editing-school-experiance').html('false');
				}
				monemploi_add_school_experiance($);
				monemploi_delete_school_experiance($);
				monemploi_edit_school_experiance($);
				monemploi_save_school_experiance($);
				jQuery('[data-toggle="datepickerstartschool_"]').datepicker();
				jQuery('[data-toggle="datepickerendschool_"]').datepicker();
			},
			error: function(error) {
				console.log(error);
			}
        })
	});
	$('.sortable-school-up').click(function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		var item = $(this).closest(".school-experiance-unique-string");
		var prevCard = item.prev('div');
		item.insertBefore(prevCard);
		var itemindex = parseInt(item.index());
		var sum = itemindex + 1;
		jQuery.ajax({
			type: 'post',
			url: sortable_reindex_monemploi_ajax_url,
			data: {
				'typeof': 'school',
				'start': sum,
				'update': item.index(),
				'action': 'sortable_reindex'
			},
			dataType: 'JSON',
			success: function(data) {
				jQuery('.wrapper-school').children('.sortable-school').each(function(i) {
					$(this).html(i);
				});
			},
			error: function(error) {
				console.log(error);
			}
		})	
	});
	$('.sortable-school-down').click(function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		var item = $(this).closest(".school-experiance-unique-string");
		var nextCard = item.next('div');
		item.insertAfter(nextCard);
		var itemindex = parseInt(item.index());
		var sum = itemindex - 1;
		jQuery.ajax({
			type: 'post',
			url: sortable_reindex_monemploi_ajax_url,
			data: {
				'typeof': 'school',
				'start': sum,
				'update': item.index(),
				'action': 'sortable_reindex'
			},
			dataType: 'JSON',
			success: function(data) {
				jQuery('.wrapper-school').children('.sortable-school').each(function(i) {
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
	monemploi_edit_school_experiance($);
});