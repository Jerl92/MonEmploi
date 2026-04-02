function monemploie_send_job() {
	jQuery('.submit_cv').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		
		var error = 0;
		
		var $this = jQuery(this),
			object_id = $this.data('object-id');
		
		var age_legal = jQuery('.age_legal').val();
		var situation_canada = jQuery('.situation_canada').val();
		var permis_travail = jQuery('.permis_travail').val();
		var deja_travaille = jQuery('.deja_travaille').val();
		var sexe = jQuery('.sexe').val();
		var origine_ethnique = jQuery('.origine_ethnique').val();
		var autochtone = jQuery('.autochtone').val();
		var handicap = jQuery('.handicap').val();
		var handicap_ = jQuery('.handicap_').val();
		
		var selectedValues = jQuery('input[name="cv"]:checked').map(function() {
		    return this.value;
		}).get();
		 	 
		var lettre_presentation = jQuery('.lettre_presentation').val();
		
		jQuery('.submit_cv').text('Veuillez patienter');
		jQuery('.submit_cv').prop('disabled', true);
		
		if(jQuery('.age_legal').val() == 0){
		    error = 1;
		    jQuery('.submit_cv').text('Soumettre');
		    jQuery('.submit_cv').prop('disabled', false);
		    jQuery('.age_legal').css('border', '1.5px solid red');
		} else {
	        	jQuery('.age_legal').css('border', '0.5px solid gray');
		}
		
		if(jQuery('.situation_canada').val() == 0){
		    error = 1;
		    jQuery('.submit_cv').text('Soumettre');
		    jQuery('.submit_cv').prop('disabled', false);
		    jQuery('.situation_canada').css('border', '1.5px solid red');
		} else {
	        	jQuery('.situation_canada').css('border', '0.5px solid gray');
		}
		
		if(jQuery('.situation_canada').val() == 3){
			if(jQuery('.permis_travail').val() == 0){
			    error = 1;
			    jQuery('.submit_cv').text('Soumettre');
		    	    jQuery('.submit_cv').prop('disabled', false);
			    jQuery('.permis_travail').css('border', '1.5px solid red');
			} else {
		        	jQuery('.permis_travail').css('border', '0.5px solid gray');
			}
		}
		
		if(jQuery('.deja_travaille').val() == 0){
		    error = 1;
		    jQuery('.submit_cv').text('Soumettre');
		    jQuery('.submit_cv').prop('disabled', false);
		    jQuery('.deja_travaille').css('border', '1.5px solid red');
		} else {
	        	jQuery('.deja_travaille').css('border', '0.5px solid gray');
		}
		
		if(jQuery('.sexe').val() == 0){
		    error = 1; 
		    jQuery('.submit_cv').text('Soumettre');
		    jQuery('.submit_cv').prop('disabled', false);
		    jQuery('.sexe').css('border', '1.5px solid red');
		} else {
	        	jQuery('.sexe').css('border', '0.5px solid gray');
		}
		
		if(jQuery('.origine_ethnique').val() == 0){
		    error = 1;
		    jQuery('.submit_cv').text('Soumettre');
		    jQuery('.submit_cv').prop('disabled', false);
		    jQuery('.origine_ethnique').css('border', '1.5px solid red');
		} else {
	        	jQuery('.origine_ethnique').css('border', '0.5px solid gray');
		}
		
		if(jQuery('.autochtone').val() == 0){
		    error = 1;
		    jQuery('.submit_cv').text('Soumettre');
		    jQuery('.submit_cv').prop('disabled', false);
		    jQuery('.autochtone').css('border', '1.5px solid red');
		} else {
	        	jQuery('.autochtone').css('border', '0.5px solid gray');
		}
		
		if(jQuery('.handicap').val() == 0){
		    error = 1;
		    jQuery('.submit_cv').text('Soumettre');
		    jQuery('.submit_cv').prop('disabled', false);
		    jQuery('.handicap').css('border', '1.5px solid red');
		} else {
	        	jQuery('.handicap').css('border', '0.5px solid gray');
		}
		
		if(jQuery('.handicap').val() == 2){
			if(jQuery('.handicap_').val() == ''){
			    error = 1;
			    jQuery('.submit_cv').text('Soumettre');
		            jQuery('.submit_cv').prop('disabled', false);
			    jQuery('.handicap_').css('border', '1.5px solid red');
			} else {
		        	jQuery('.handicap_').css('border', '0.5px solid gray');
			}
		}
		
		if(selectedValues == ''){
		    error = 1;
		    jQuery('.submit_cv').text('Soumettre');
		    jQuery('.submit_cv').prop('disabled', false);
		    jQuery('.cv-table-wrapper').css('border', '1.5px solid red');
		} else {
	        	jQuery('.cv-table-wrapper').css('border', '0px solid gray');
		}
		
        if(error == 0){
    		jQuery.ajax({
    			type: 'post',
    			url: send_cv_monemploi_ajax_url,
    			data: {
    			    'object_id': object_id,
    				'age_legal': age_legal,
    				'situation_canada': situation_canada,
    				'permis_travail': permis_travail,
    				'deja_travaille': deja_travaille,
    				'sexe': sexe,
    				'origine_ethnique': origine_ethnique,
    				'autochtone': autochtone,
    				'handicap': handicap,
    				'handicap_': handicap_,
    				'selectedValues': selectedValues,
    				'lettre_presentation': lettre_presentation,
    				'action': 'monemploi_send_cv_job'
                },
                dataType: 'JSON',
    			success: function(data) {
    				jQuery('.entry-meta-job-question-wrapper').html('');
    				jQuery('.entry-meta-job-question-wrapper').html(data);
    				
    				jQuery('.submit_cv').text('Soumettre');
				jQuery('.submit_cv').prop('disabled', false);
    			},
    			error: function(error) {
    				console.log(error);
    			}
            })
        }
	});
}

jQuery(document).ready(function($) {
	monemploie_send_job($);
});