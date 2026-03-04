function getWpEditorValue(editorId) {
    var content;

    // Check if the TinyMCE editor is initialized and not hidden (Visual tab is active)
    if (typeof tinyMCE !== 'undefined' && tinyMCE.get(editorId) && !tinyMCE.get(editorId).isHidden()) {
        content = tinymce.get(editorId).getContent();
    } else {
        // Fallback to the standard textarea value (Text/HTML tab is active or TinyMCE not loaded)
        content = jQuery('#' + editorId).val();
    }

    return content;
}

function getTinyMCELength(editorId) {
    // Get the HTML content from the active editor instance
    var contentHtml = tinymce.get(editorId).getContent(); //

    // To get the plain text length (excluding HTML tags), you can process the HTML.
    // A common way to get a rough character count without tags is:
    var contentTextLength = tinymce.get(editorId).getContent({ format: 'text' }).length; //
    
    // Or, a more robust way to strip tags for a plain text count:
    var plainText = contentHtml.replace(/(<([^>]+)>)/ig, ""); //
    var plainTextLength = plainText.length;
    
    return plainTextLength;
}

function monemploi_add_job($){
    	jQuery('.ns_submit').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		
		var erreur = 0;
		
	        var job_title = jQuery('.monemploi_add_job_text').val();
	        var ticket_details = getWpEditorValue('ns-ticket-details');
	        var code_postal = jQuery('.monemploi_add_code_postal_text').val();
	        var education = jQuery('.education_terms').find(":selected").val();
	        var annees_dexperience = jQuery('.annees_dexperience').find(":selected").val();
	  	var salaire = jQuery('.monemploi_add_salaire').val();
	  	var city = jQuery('.monemploi_add_city_text').val();
	  	var datepickerstartjobscheduled = jQuery('.datepickerstartjobscheduled').val();
	  	var datepickerendjobscheduled = jQuery('.datepickerendjobscheduled').val();
	  	var timestartjobscheduled = jQuery('#timestartjobscheduled').val();
	  	var timeendjobscheduled = jQuery('#timeendjobscheduled').val();
	  	
	  	jQuery('.ns_submit').text("Veuillez patienter");
		jQuery('.ns_submit').prop("disabled", true);
	  	
  		if( (jQuery('.monemploi_add_job_text').val().length < 5) ){
			erreur = 1;
			jQuery('.ns_submit').text("Soumettre");
			jQuery('.ns_submit').prop("disabled", false);
			jQuery('.new_job_error').append('<div class="new_job_title_error" style="color: red;">Votre titre est trop court. 5 caractères minimum.</div>');
			jQuery('.monemploi_add_job_text').css('border', '1.5px solid red');
			setTimeout(function() {
				 jQuery('.new_job_title_error').remove();
			}, 10000);
		} else {
		    jQuery('.monemploi_add_job_text').css('border', '0.5px solid gray');
		}
		
		if( (getTinyMCELength('ns-ticket-details') < 15) ){
			erreur = 1;
			jQuery('.ns_submit').text("Soumettre");
			jQuery('.ns_submit').prop("disabled", false);
			jQuery('.new_job_error').append('<div class="new_job_description_error" style="color: red;">Votre description est trop court. 15 caractères minimum.</div>');
			setTimeout(function() {
				 jQuery('.new_job_description_error').remove();
			}, 10000);
		}
		
		if( (jQuery('.monemploi_add_code_postal_text').val().length < 15) ){
			erreur = 1;
			jQuery('.ns_submit').text("Soumettre");
			jQuery('.ns_submit').prop("disabled", false);
			jQuery('.new_job_error').append('<div class="new_job_code_postal_error" style="color: red;">Votre adresse est trop court. 15 caractères minimum.</div>');
			jQuery('.monemploi_add_code_postal_text').css('border', '1.5px solid red');
			setTimeout(function() {
				 jQuery('.new_job_code_postal_error').remove();
			}, 10000);
		} else {
		    jQuery('.monemploi_add_code_postal_text').css('border', '0.5px solid gray');
		}
		
		if( (jQuery('.monemploi_add_city_text').val().length < 5) ){
			erreur = 1;
			jQuery('.ns_submit').text("Soumettre");
			jQuery('.ns_submit').prop("disabled", false);
			jQuery('.new_job_error').append('<div class="new_job_city_error" style="color: red;">Votre ville est trop court. 5 caractères minimum.</div>');
			jQuery('.monemploi_add_city_text').css('border', '1.5px solid red');
			setTimeout(function() {
				 jQuery('.new_job_city_error').remove();
			}, 10000);
		} else {
			jQuery('.monemploi_add_city_text').css('border', '0.5px solid gray');
		}
		
		if( (jQuery('.education_terms').val() == 0) ){
			erreur = 1;
			jQuery('.ns_submit').text("Soumettre");
			jQuery('.ns_submit').prop("disabled", false);
			jQuery('.new_job_error').append('<div class="new_job_education_terms_error" style="color: red;">Votre diplome est encore a 0.</div>');
			jQuery('.education_terms').css('border', '1.5px solid red');
			setTimeout(function() {
				 jQuery('.new_job_education_terms_error').remove();
			}, 10000);
		} else {
		    jQuery('.education_terms').css('border', '0.5px solid gray');
		}
		
		if( (jQuery('.monemploi_add_salaire').val().length < 2) ){
			erreur = 1;
			jQuery('.ns_submit').text("Soumettre");
			jQuery('.ns_submit').prop("disabled", false);
			jQuery('.new_job_error').append('<div class="new_job_salaire_error" style="color: red;">Votre salaire est trop court. 2 nombres minimum.</div>');
			jQuery('.monemploi_add_salaire').css('border', '1.5px solid red');
			setTimeout(function() {
				 jQuery('.new_job_salaire_error').remove();
			}, 10000);
		} else {
		    jQuery('.monemploi_add_salaire').css('border', '0.5px solid gray');
		}
		
		if( (jQuery('.monemploi_add_salaire').val() < 16.60) ){
			erreur = 1;
			jQuery('.ns_submit').text("Soumettre");
			jQuery('.ns_submit').prop("disabled", false);
			jQuery('.new_job_error').append('<div class="new_job_salaire_minimum_error" style="color: red;">Votre salaire est trop bas. 16.60 le salaire minimum au Québec, depuis le 1er mai 2026.</div>');
			jQuery('.monemploi_add_salaire').css('border', '1.5px solid red');
			setTimeout(function() {
				 jQuery('.new_job_salaire_minimum_error').remove();
			}, 10000);
		} else {
		    jQuery('.monemploi_add_salaire').css('border', '0.5px solid gray');
		}
		
		if( (jQuery('.annees_dexperience').val() == 0) ){
			erreur = 1;
			jQuery('.ns_submit').text("Soumettre");
			jQuery('.ns_submit').prop("disabled", false);
			jQuery('.new_job_error').append('<div class="new_job_annees_dexperience_error" style="color: red;">Votre annees dexperience est encore a 0.</div>');
			jQuery('.annees_dexperience').css('border', '1.5px solid red');
			setTimeout(function() {
				 jQuery('.new_job_annees_dexperience_error').remove();
			}, 10000);
		} else {
		    jQuery('.annees_dexperience').css('border', '0.5px solid gray');
		}
						
	
		if(erreur === 0){
		        jQuery.ajax({
		            type: 'post',
		            url: add_job_monemploi_ajax_url,
		            data: {
		                'job_title': job_title,
		                'ticket_details': ticket_details,
		                'code_postal': code_postal,
		                'education': education,
		                'annees_dexperience': annees_dexperience,
		                'salaire': salaire,
		                'city': city,
		                'datepickerstartjobscheduled': datepickerstartjobscheduled,
		                'datepickerendjobscheduled': datepickerendjobscheduled,
		                'timestartjobscheduled': timestartjobscheduled,
		                'timeendjobscheduled': timeendjobscheduled,
		                'action': 'monemploi_add_job'
		            },
		            dataType: 'json',
		            success: function(data){
		            
			        jQuery('.monemploi_add_job_text').val('');
			        tinymce.get('ns-ticket-details').setContent('');
			        jQuery('.monemploi_add_code_postal_text').val('');
			   	jQuery('.education_terms').val(0);
			   	jQuery('.annees_dexperience').val(0);
			  	jQuery('.monemploi_add_salaire').val('');
			  	jQuery('.monemploi_add_city_text').val('');
			  	jQuery('.datepickerstartjobscheduled').val('');
			  	jQuery('.datepickerendjobscheduled').val('');
			  	jQuery('#timestartjobscheduled').val('');
			  	jQuery('#timeendjobscheduled').val('');
			  	
		                jQuery('#monemploi-new-form-sumbit').html('');
		                jQuery('#monemploi-new-form-sumbit').html(data);
		                
		                jQuery('.ns_submit').text("Soumettre");
				jQuery('.ns_submit').prop("disabled", false);
		                
		                setTimeout(function() {
					jQuery('#monemploi-new-form-sumbit').html('');
			        }, 10000);
		            },
		            error: function(errorThrown){
		                console.log(errorThrown);
		            }
		        });
		}
    });
}

jQuery(document).ready(function($) {
    monemploi_add_job($);
});