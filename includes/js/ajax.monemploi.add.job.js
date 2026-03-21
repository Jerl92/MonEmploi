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
		
		var $this = jQuery(this),
            		job_status = $this.data('object-status'),
            		postid = $this.data('object-id');
		
	        var job_title = jQuery('.monemploi_add_job_text').val();
	        var ticket_details = getWpEditorValue('new-job-details');
	        var code_postal = jQuery('.monemploi_add_code_postal_text').val();
	        var education = jQuery('.education_terms').find(":selected").val();
	        var annees_dexperience = jQuery('.annees_dexperience').find(":selected").val();
	  	var salaire = jQuery('.monemploi_add_salaire').val();
	  	var city = jQuery('.monemploi_add_city_text').val();
	  	var datepickerstartjobscheduled = jQuery('.datepickerstartjobscheduled').val();
	  	var datepickerendjobscheduled = jQuery('.datepickerendjobscheduled').val();
	  	var timestartjobscheduled = jQuery('#timestartjobscheduled').val();
	  	var timeendjobscheduled = jQuery('#timeendjobscheduled').val();
	  	
	  	var add_heures = jQuery('.monemploi_add_heures').val();
	  	var type_demploi = jQuery('.type_demploi').find(':selected').val();
	  	var type_dhoraire = jQuery('.type_dhoraire').find(':selected').val();
	  	var disponibilites1 = 0;
	  	var disponibilites2 = 0;
	  	if (jQuery('.disponibilites1').is(':checked')) {
			disponibilites1 = 1;
		}
		if (jQuery('.disponibilites2').is(':checked')) {
			disponibilites2 = 1;
		}
		var duree_emploi = jQuery('.duree_emploi').find(':selected').val();
		var permis_conduire = jQuery('.permis_conduire').find(':selected').val();
		var besoin_voiture = jQuery('.besoin_voiture').find(':selected').val();
		var activite_professionnelle = jQuery('.activite_professionnelle').find(':selected').val();
	  	
	  	jQuery('.ns_submit').text('Veuillez patienter');
		jQuery('.ns_submit').prop('disabled', true);
	  	
  		if(jQuery('.monemploi_add_job_text').val().length < 5){
			erreur = 1;
			jQuery('.ns_submit').text('Soumettre');
			jQuery('.ns_submit').prop('disabled', false);
			jQuery('.new_job_error').append('<div class="new_job_title_error" style="color: red;">Votre titre est trop court. 5 caractères minimum.</div>');
			jQuery('.monemploi_add_job_text').css('border', '1.5px solid red');
			setTimeout(function() {
				 jQuery('.new_job_title_error').remove();
			}, 10000);
		} else {
		    jQuery('.monemploi_add_job_text').css('border', '0.5px solid gray');
		}
		
		if(getTinyMCELength('new-job-details') < 15){
			erreur = 1;
			jQuery('.ns_submit').text('Soumettre');
			jQuery('.ns_submit').prop('disabled', false);
			jQuery('.new_job_error').append('<div class="new_job_description_error" style="color: red;">Votre description est trop court. 15 caractères minimum.</div>');
			setTimeout(function() {
				 jQuery('.new_job_description_error').remove();
			}, 10000);
		}
		
		if(jQuery('.monemploi_add_code_postal_text').val().length < 15){
			erreur = 1;
			jQuery('.ns_submit').text('Soumettre');
			jQuery('.ns_submit').prop('disabled', false);
			jQuery('.new_job_error').append('<div class="new_job_code_postal_error" style="color: red;">Votre adresse est trop court. 15 caractères minimum.</div>');
			jQuery('.monemploi_add_code_postal_text').css('border', '1.5px solid red');
			setTimeout(function() {
				 jQuery('.new_job_code_postal_error').remove();
			}, 10000);
		} else {
		    jQuery('.monemploi_add_code_postal_text').css('border', '0.5px solid gray');
		}
		
		if(jQuery('.monemploi_add_city_text').val().length < 5){
			erreur = 1;
			jQuery('.ns_submit').text('Soumettre');
			jQuery('.ns_submit').prop('disabled', false);
			jQuery('.new_job_error').append('<div class="new_job_city_error" style="color: red;">Votre ville est trop court. 5 caractères minimum.</div>');
			jQuery('.monemploi_add_city_text').css('border', '1.5px solid red');
			setTimeout(function() {
				 jQuery('.new_job_city_error').remove();
			}, 10000);
		} else {
			jQuery('.monemploi_add_city_text').css('border', '0.5px solid gray');
		}
		
		if(jQuery('.education_terms').val() == 0){
			erreur = 1;
			jQuery('.ns_submit').text('Soumettre');
			jQuery('.ns_submit').prop('disabled', false);
			jQuery('.new_job_error').append('<div class="new_job_education_terms_error" style="color: red;">Votre diplome est encore a 0.</div>');
			jQuery('.education_terms').css('border', '1.5px solid red');
			setTimeout(function() {
				 jQuery('.new_job_education_terms_error').remove();
			}, 10000);
		} else {
		    jQuery('.education_terms').css('border', '0.5px solid gray');
		}
		
		if(jQuery('.monemploi_add_salaire').val().length < 2){
			erreur = 1;
			jQuery('.ns_submit').text('Soumettre');
			jQuery('.ns_submit').prop('disabled', false);
			jQuery('.new_job_error').append('<div class="new_job_salaire_error" style="color: red;">Votre salaire est trop court. 2 nombres minimum.</div>');
			jQuery('.monemploi_add_salaire').css('border', '1.5px solid red');
			setTimeout(function() {
				 jQuery('.new_job_salaire_error').remove();
			}, 10000);
		} else {
		    jQuery('.monemploi_add_salaire').css('border', '0.5px solid gray');
		}
		
		if(jQuery('.monemploi_add_salaire').val() < 16.60){
			erreur = 1;
			jQuery('.ns_submit').text('Soumettre');
			jQuery('.ns_submit').prop('disabled', false);
			jQuery('.new_job_error').append('<div class="new_job_salaire_minimum_error" style="color: red;">Votre salaire est trop bas. 16.60 le salaire minimum au Québec, depuis le 1er mai 2026.</div>');
			jQuery('.monemploi_add_salaire').css('border', '1.5px solid red');
			setTimeout(function() {
				 jQuery('.new_job_salaire_minimum_error').remove();
			}, 10000);
		} else {
		    jQuery('.monemploi_add_salaire').css('border', '0.5px solid gray');
		}
		
		if(jQuery('.annees_dexperience').val() == 0){
			erreur = 1;
			jQuery('.ns_submit').text('Soumettre');
			jQuery('.ns_submit').prop('disabled', false);
			jQuery('.new_job_error').append('<div class="new_job_annees_dexperience_error" style="color: red;">Votre annees dexperience est encore a 0.</div>');
			jQuery('.annees_dexperience').css('border', '1.5px solid red');
			setTimeout(function() {
				 jQuery('.new_job_annees_dexperience_error').remove();
			}, 10000);
		} else {
		    jQuery('.annees_dexperience').css('border', '0.5px solid gray');
		}
		
		if(jQuery('.monemploi_add_heures').val() < 0){
			erreur = 1;
			jQuery('.ns_submit').text('Soumettre');
			jQuery('.ns_submit').prop('disabled', false);
			jQuery('.new_job_error').append('<div class="monemploi_add_heures_error" style="color: red;">Votre nombre dheure est a 0.</div>');
			jQuery('.monemploi_add_heures').css('border', '1.5px solid red');
			setTimeout(function() {
				 jQuery('.monemploi_add_heures_error').remove();
			}, 10000);
		} else {
		    jQuery('.monemploi_add_heures').css('border', '0.5px solid gray');
		}
		
		if(jQuery('.type_demploi').val() == 0){
			erreur = 1;
			jQuery('.ns_submit').text('Soumettre');
			jQuery('.ns_submit').prop('disabled', false);
			jQuery('.new_job_error').append('<div class="type_demploi_error" style="color: red;">Votre type demploi est a 0.</div>');
			jQuery('.type_demploi').css('border', '1.5px solid red');
			setTimeout(function() {
				 jQuery('.type_demploi_error').remove();
			}, 10000);
		} else {
		    jQuery('.type_demploi').css('border', '0.5px solid gray');
		}		
				
		if(jQuery('.type_dhoraire').val() == 0){
			erreur = 1;
			jQuery('.ns_submit').text('Soumettre');
			jQuery('.ns_submit').prop('disabled', false);
			jQuery('.new_job_error').append('<div class="type_dhoraire_error" style="color: red;">Votre type dhoraire est a 0.</div>');
			jQuery('.type_dhoraire').css('border', '1.5px solid red');
			setTimeout(function() {
				 jQuery('.type_dhoraire_error').remove();
			}, 10000);
		} else {
		    jQuery('.type_dhoraire').css('border', '0.5px solid gray');
		}		
		
		if (!jQuery('.disponibilites1').is(':checked') && !jQuery('.disponibilites2').is(':checked')){
			erreur = 1;
			jQuery('.ns_submit').text('Soumettre');
			jQuery('.ns_submit').prop('disabled', false);
			jQuery('.new_job_error').append('<div class="disponibilites_error" style="color: red;">Votre type de disponibilites nest pas choisie.</div>');
			setTimeout(function() {
				 jQuery('.disponibilites_error').remove();
			}, 10000);
		}	
		
		if(jQuery('.duree_emploi').val() == 0){
			erreur = 1;
			jQuery('.ns_submit').text('Soumettre');
			jQuery('.ns_submit').prop('disabled', false);
			jQuery('.new_job_error').append('<div class="duree_emploi_error" style="color: red;">Votre duree demploi est a 0.</div>');
			jQuery('.duree_emploi').css('border', '1.5px solid red');
			setTimeout(function() {
				 jQuery('.duree_emploi_error').remove();
			}, 10000);
		} else {
		    jQuery('.duree_emploi').css('border', '0.5px solid gray');
		}
		
		if(jQuery('.permis_conduire').val() == 0){
			erreur = 1;
			jQuery('.ns_submit').text('Soumettre');
			jQuery('.ns_submit').prop('disabled', false);
			jQuery('.new_job_error').append('<div class="permis_conduire_error" style="color: red;">Est-ce que vous avez besoin dun permis de conduire.</div>');
			jQuery('.permis_conduire').css('border', '1.5px solid red');
			setTimeout(function() {
				 jQuery('.permis_conduire_error').remove();
			}, 10000);
		} else {
		    jQuery('.permis_conduire').css('border', '0.5px solid gray');
		}
		
		if(jQuery('.besoin_voiture').val() == 0){
			erreur = 1;
			jQuery('.ns_submit').text('Soumettre');
			jQuery('.ns_submit').prop('disabled', false);
			jQuery('.new_job_error').append('<div class="besoin_voiture_error" style="color: red;">Est-ce que vous avez besoin dune voiture.</div>');
			jQuery('.besoin_voiture').css('border', '1.5px solid red');
			setTimeout(function() {
				 jQuery('.besoin_voiture_error').remove();
			}, 10000);
		} else {
		    jQuery('.besoin_voiture').css('border', '0.5px solid gray');
		}
		
		if(jQuery('.activite_professionnelle').val() == 0){
			erreur = 1;
			jQuery('.ns_submit').text('Soumettre');
			jQuery('.ns_submit').prop('disabled', false);
			jQuery('.new_job_error').append('<div class="activite_professionnelle_error" style="color: red;">Choisissez votre activité professionnelle</div>');
			jQuery('.activite_professionnelle').css('border', '1.5px solid red');
			setTimeout(function() {
				 jQuery('.activite_professionnelle_error').remove();
			}, 10000);
		} else {
		    jQuery('.activite_professionnelle').css('border', '0.5px solid gray');
		}
	
		if(erreur == 0){
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
		                'add_heures': add_heures,
		                'type_demploi': type_demploi,
		                'type_dhoraire': type_dhoraire,
		                'disponibilites1': disponibilites1,
		                'disponibilites2': disponibilites2,
		                'duree_emploi': duree_emploi,
		                'permis_conduire': permis_conduire,
		                'besoin_voiture': besoin_voiture,
		                'activite_professionnelle': activite_professionnelle,
		                'job_status': job_status,
		                'postid': postid,
		                'action': 'monemploi_add_job'
		            },
		            dataType: 'json',
		            success: function(data){
		            
			    	jQuery('.monemploi_add_job_text').val('');
			    	tinymce.get('new-job-details').setContent('');
			    	jQuery('.monemploi_add_code_postal_text').val('');
			   	jQuery('.education_terms').val(0);
			   	jQuery('.annees_dexperience').val(0);
			  	jQuery('.monemploi_add_salaire').val('');
			  	jQuery('.monemploi_add_city_text').val('');
			  	jQuery('.datepickerstartjobscheduled').val('');
			  	jQuery('.datepickerendjobscheduled').val('');
			  	jQuery('#timestartjobscheduled').val('');
			  	jQuery('#timeendjobscheduled').val('');
			  	jQuery('.monemploi_add_heures').val('');
			  	jQuery('.type_demploi').val(0);
			  	jQuery('.type_dhoraire').val(0);
				jQuery('.disponibilites1').prop('checked', false);
				jQuery('.disponibilites2').prop('checked', false);
				jQuery('.duree_emploi').val(0);
				jQuery('.permis_conduire').val(0);
				jQuery('.besoin_voiture').val(0);
				jQuery('.activite_professionnelle').val(0);
			  	
		                jQuery('#monemploi-new-form-sumbit').html('');
		                jQuery('#monemploi-new-form-sumbit').html(data);
		                
		                jQuery('.ns_submit').text('Soumettre');
				jQuery('.ns_submit').prop('disabled', false);
				
				if(job_status = 'update'){
					var url = new URL(window.location.href);
					var params = url.searchParams;
					params.delete('postid');
					window.history.pushState({}, '', url.toString());
				}
		                
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