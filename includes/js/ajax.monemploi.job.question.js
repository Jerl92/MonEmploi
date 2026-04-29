function monemploie_question_job() {
	jQuery('.question_job').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		
		var error = 0;
		
		var $this = jQuery(this),
			object_id = $this.data('object-id');
		
		var age_legal = jQuery('.age_legal').val();
		var situation_canada = jQuery('.situation_canada').val();
		var permis_travail = jQuery('.permis_travail').val();
		var sexe = jQuery('.sexe').val();
		var origine_ethnique = jQuery('.origine_ethnique').val();
		var autochtone = jQuery('.autochtone').val();
		var handicap = jQuery('.handicap').val();
		var handicap_info = jQuery('.handicap_info').val();
		var dossier_criminel = jQuery('.dossier-criminel').val();
		var dossier_criminel_info = jQuery('.dossier-criminel-info').val();
		
		jQuery('.question_job').text('Veuillez patienter');
		jQuery('.question_job').prop('disabled', true);
		
		if(jQuery('.age_legal').val() == 0){
		    error = 1;
		    jQuery('.question_job').text('Soumettre');
		    jQuery('.question_job').prop('disabled', false);
		    jQuery('.age_legal').css('border', '1.5px solid red');
		} else {
	        	jQuery('.age_legal').css('border', '0.5px solid gray');
		}
		
		if(jQuery('.situation_canada').val() == 0){
		    error = 1;
		    jQuery('.question_job').text('Soumettre');
		    jQuery('.question_job').prop('disabled', false);
		    jQuery('.situation_canada').css('border', '1.5px solid red');
		} else {
	        	jQuery('.situation_canada').css('border', '0.5px solid gray');
		}
		
		if(jQuery('.situation_canada').val() == 3){
			if(jQuery('.permis_travail').val() == 0){
			    error = 1;
			    jQuery('.question_job').text('Soumettre');
		    	    jQuery('.question_job').prop('disabled', false);
			    jQuery('.permis_travail').css('border', '1.5px solid red');
			} else {
		        	jQuery('.permis_travail').css('border', '0.5px solid gray');
			}
		}
		
		if(jQuery('.dossier-criminel').val() == 0){
		    error = 1;
		    jQuery('.question_job').text('Soumettre');
		    jQuery('.question_job').prop('disabled', false);
		    jQuery('.dossier-criminel').css('border', '1.5px solid red');
		} else {
	        	jQuery('.dossier-criminel').css('border', '0.5px solid gray');
		}
		
		if(jQuery('.dossier-criminel').val() == 2){
			if(jQuery('.dossier-criminel-info').val() == ''){
			    error = 1;
			    jQuery('.question_job').text('Soumettre');
		    	    jQuery('.question_job').prop('disabled', false);
			    jQuery('.dossier-criminel-info').css('border', '1.5px solid red');
			} else {
		        	jQuery('.dossier-criminel-info').css('border', '0.5px solid gray');
			}
		}

		
		if(jQuery('.sexe').val() == 0){
		    error = 1; 
		    jQuery('.question_job').text('Soumettre');
		    jQuery('.question_job').prop('disabled', false);
		    jQuery('.sexe').css('border', '1.5px solid red');
		} else {
	        	jQuery('.sexe').css('border', '0.5px solid gray');
		}
		
		if(jQuery('.origine_ethnique').val() == 0){
		    error = 1;
		    jQuery('.question_job').text('Soumettre');
		    jQuery('.question_job').prop('disabled', false);
		    jQuery('.origine_ethnique').css('border', '1.5px solid red');
		} else {
	        	jQuery('.origine_ethnique').css('border', '0.5px solid gray');
		}
		
		if(jQuery('.autochtone').val() == 0){
		    error = 1;
		    jQuery('.question_job').text('Soumettre');
		    jQuery('.question_job').prop('disabled', false);
		    jQuery('.autochtone').css('border', '1.5px solid red');
		} else {
	        	jQuery('.autochtone').css('border', '0.5px solid gray');
		}
		
		if(jQuery('.handicap').val() == 0){
		    error = 1;
		    jQuery('.question_job').text('Soumettre');
		    jQuery('.question_job').prop('disabled', false);
		    jQuery('.handicap').css('border', '1.5px solid red');
		} else {
	        	jQuery('.handicap').css('border', '0.5px solid gray');
		}
		
		if(jQuery('.handicap').val() == 2){
			if(jQuery('.handicap_info').val() == ''){
			    error = 1;
			    jQuery('.question_job').text('Soumettre');
		            jQuery('.question_job').prop('disabled', false);
			    jQuery('.handicap_info').css('border', '1.5px solid red');
			} else {
		        	jQuery('.handicap_info').css('border', '0.5px solid gray');
			}
		}
		
		
        if(error == 0){
    		jQuery.ajax({
    			type: 'post',
    			url: job_question_monemploi_ajax_url,
    			data: {
    			    'object_id': object_id,
    				'age_legal': age_legal,
    				'situation_canada': situation_canada,
    				'permis_travail': permis_travail,
    				'dossier_criminel': dossier_criminel,
    				'dossier_criminel_info': dossier_criminel_info,
    				'sexe': sexe,
    				'origine_ethnique': origine_ethnique,
    				'autochtone': autochtone,
    				'handicap': handicap,
    				'handicap_info': handicap_info,
    				'action': 'monemploi_question_job'
                },
                dataType: 'JSON',
    			success: function(data) {
    				jQuery('.question-job-wrapper').html('');
    				jQuery('.question-job-wrapper').html(data);
    				
    				jQuery('.question_job').text('Soumettre');
				jQuery('.question_job').prop('disabled', false);
    			},
    			error: function(error) {
    				console.log(error);
    			}
            });
        }
	});
}

jQuery(document).ready(function($) {
	monemploie_question_job($);
});