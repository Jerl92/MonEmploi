
jQuery(document).ready(function($) {
	jQuery('[data-toggle="datepickerstartjobscheduled"]').datepicker();
	jQuery('[data-toggle="datepickerendjobscheduled"]').datepicker();
});

jQuery(window).on('load', function() {
	var getday = getUrlParameter('days');
	if(getday) {
		jQuery(".days_filter").val(getday);
		jQuery(".job-wrapper-box").each(function(index, element) {
			var day = jQuery('.get-the-date-difference-'+index).html();
			if(parseInt(getday) < parseInt(day)) {
				jQuery('#job-wrapper-box-'+index).remove();
			}
		});	
	}
	var getkm = getUrlParameter('km');
	if(getkm) {
		jQuery(".km_filter").val(getkm);
		jQuery(".job-wrapper-box").each(function(index, element) {
			var distance = jQuery('.distance_'+index).html();
			if(parseInt(getkm) < parseInt(distance)) {
				jQuery('#job-wrapper-box-'+index).remove();
			}
		});
	}
});

jQuery(window).on('load', function() {
        var items = jQuery('.job-wrapper').children('.job-wrapper-box').get();

	var km_sort = getUrlParameter('km_sort');
	if(km_sort == 1) {
	        items.sort(function(a, b) {
	            var valA = parseInt(jQuery(a).find('.distance').text(), 10);
	            var valB = parseInt(jQuery(b).find('.distance').text(), 10);
			
	            return valA - valB;
	        });
        }
        
        if(km_sort == 2) {
	        items.sort(function(a, b) {
	            var valA = parseInt(jQuery(a).find('.distance').text(), 10);
	            var valB = parseInt(jQuery(b).find('.distance').text(), 10);
	
	            return valB - valA;
	        });
        }

        jQuery.each(items, function(index, item) {
            jQuery('.job-wrapper').append(item);
        });
});

jQuery(document).ready(function($) {
	jQuery('.km_sort').on('change', function() {
		jQuery('.title_sort').val('');
		jQuery('.date_sort').val('');
	});
	
	jQuery('.title_sort').on('change', function() {
		jQuery('.km_sort').val('');
		jQuery('.date_sort').val('');
	});
	
	jQuery('.date_sort').on('change', function() {
		jQuery('.km_sort').val('');
		jQuery('.title_sort').val('');
	});
});

jQuery(document).ready(function($) {
    jQuery('.handicap').change(function() {
        if($(this).val() == 2) {
            jQuery('.handicap_wrapper').css('display', 'block');
        } else  {
            jQuery('.handicap_wrapper').css('display', 'none');
        }
    });
    jQuery('.situation_canada').change(function() {
        if($(this).val() == 3) {
            jQuery('.permis_travail_wrapper').css('display', 'block');
        } else  {
            jQuery('.permis_travail_wrapper').css('display', 'none');
        }
    });
    jQuery('.deja_travaille').change(function() {
        if($(this).val() == 1) {
            jQuery('.superieur-info').css('display', 'block');
        } else  {
            jQuery('.superieur-info').css('display', 'none');
        }
    });
    jQuery(".superieur_numero").keyup(function() {
	    jQuery(this).val(jQuery(this).val().replace(/^(\d{3})(\d{3})(\d+)$/, "($1)$2-$3"));
    });
    if (jQuery('.employeur').prop('checked')) {
        jQuery('.employer').prop("checked", false);
    }
    if (jQuery('.employer').prop('checked')) {
        jQuery('.employeur').prop("checked", false);
    }
});

jQuery(document).ready(function($) {
    jQuery("#password").passwordStrength({
        targetDiv:'passwordStrength'
    });
    jQuery("#verifypassword").keyup(function() {
	if(jQuery("#password").val() === jQuery("#verifypassword").val()) {
		jQuery("#verifyPasswordCheck").html('<span style="background-color: green; padding: 5px;">Le mot de passe est identique</span>');
	} else  {
		jQuery("#verifyPasswordCheck").html('<span style="background-color: red; padding: 5px;">Le mot de passe n&#39;est pas identique</span>');
	}
    });
    jQuery("#phone").keyup(function() {
	    jQuery(this).val(jQuery(this).val().replace(/^(\d{3})(\d{3})(\d+)$/, "($1)$2-$3"));
    });
    jQuery("#postalcode").keyup(function() {
	    var regex = /^[A-Za-z]\d[A-Za-z] ?\d[A-Za-z]\d$/;
	    if (!regex.test(jQuery('#postalcode').val())) {
	        jQuery(this).css('border', '1px solide red');
	    } else {
	        jQuery(this).css('border', '1px solide green');
	    }
   });
});