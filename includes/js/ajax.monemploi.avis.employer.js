function monemploi_employer_function($) {
	jQuery('.show-hide-avis-employer').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
	  if (jQuery(".avis-employer-wrapper").css("display") === "none") {
	    jQuery(".avis-employer-wrapper").css("display", "block");
	  } else {
	    jQuery(".avis-employer-wrapper").css("display", "none");
	  }
	});

	jQuery('.avis-message-employer').on('keydown', function(event) {
	  jQuery('.number-of-char').text($(this).val().length + ' Caractères');
	});
}

function avis_employer_monemploi($) {
	jQuery('.avis-employer-send').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		
			var erreur = 0;
			var $this = jQuery(this),
				object_id = $this.data('object-id');
				
			var avismessage = jQuery('.avis-message-employer').val();
			var ponctualite = jQuery('.ponctualite-employer').val();
			var connaisance = jQuery('.connaisance-employer').val();
			var attitude = jQuery('.attitude-employer').val();
			
			jQuery('.avis-employer-send').text("Veuillez patienter");
			jQuery('.avis-employer-send').prop("disabled", true);
			
			if(jQuery(".avis-message-employer").val().length < 3){
				erreur = 1;
				jQuery('.avis-employer-send').text("Soumettre");
				jQuery('.avis-employer-send').prop("disabled", false);
				jQuery('.avis-error-employer').append('<div class="avis_error_message" style="color: red">Votre message est vide</div>');
				jQuery('.avis-message-employer').css('border', '1.5px solid red');
				setTimeout(function() {
					jQuery('.avis_error_message').remove();
				}, 5000);
			} else if(jQuery(".avis-message-employer").val().length > 250){
				erreur = 1;
				jQuery('.avis-employer-send').text("Soumettre");
				jQuery('.avis-employer-send').prop("disabled", false);
				jQuery('.avis-error-employer').append('<div class="avis_error_message" style="color: red;">Votre message est trop long. 250 caractères max</div>');
				jQuery('.avis-message-employer').css('border', '1.5px solid red');
				setTimeout(function() {
					jQuery('.avis_error_message').remove();
				}, 5000);
			} else {
			    jQuery('.avis-message-employer').css('border', '0.5px solid gray');
			}
			
			if((jQuery(".ponctualite-employer").val().length < 1 || jQuery('.ponctualite-employer').val() > 5) || (jQuery('.ponctualite-employer').val() < 0)){
				erreur = 1;
				jQuery('.avis-employer-send').text("Soumettre");
				jQuery('.avis-employer-send').prop("disabled", false);
				jQuery('.avis-error-employer').append('<div class="avis_error_horaire" style="color: red;">Votre score de ponctualité est pas entre 0 et 5</div>');
				jQuery('.ponctualite-employer').css('border', '1.5px solid red');
				setTimeout(function() {
					 jQuery('.avis_error_horaire').remove();
				}, 5000);
			} else {
			    jQuery('.ponctualite-employer').css('border', '0.5px solid gray');
			}
			
			if((jQuery(".connaisance-employer").val().length < 1 || jQuery('.connaisance-employer').val() > 5) || (jQuery('.connaisance-employer').val() < 0)){
				erreur = 1;
				jQuery('.avis-employer-send').text("Soumettre");
				jQuery('.avis-employer-send').prop("disabled", false);
				jQuery('.avis-error-employer').append('<div class="avis_error_superieur" style="color: red">Votre score de connaisance est pas entre 0 et 5</div>');
				jQuery('.connaisance-employer').css('border', '1.5px solid red');
				setTimeout(function() {
					 jQuery('.avis_error_superieur').remove();
				}, 5000);
			} else {
			    jQuery('.connaisance-employer').css('border', '0.5px solid gray');
			}


			if((jQuery(".attitude-employer").val().length < 1 || jQuery('.attitude-employer').val() > 5) || (jQuery('.attitude-employer').val() < 0)){
				erreur = 1;
				jQuery('.avis-employer-send').text("Soumettre");
				jQuery('.avis-employer-send').prop("disabled", false);
				jQuery('.avis-error-employer').append('<div class="avis_error_paie" style="color: red">Votre score de lattitude est pas entre 0 et 5</div>');
				jQuery('.attitude-employer').css('border', '1.5px solid red');
				setTimeout(function() {
					 jQuery('.avis_error_paie').remove();
				}, 5000);
			} else {
			   jQuery('.attitude-employer').css('border', '0.5px solid gray');
			}

			if(erreur === 0){
			jQuery.ajax({
				type: 'post',
				url: avis_employer_monemploi_ajax_url,
				data: {
				    'object_id': object_id,
				    'avismessage': avismessage,
				    'ponctualite': ponctualite,
				    'connaisance': connaisance,
				    'attitude': attitude,
					'action': 'avis_employer'
	            },
	            dataType: 'JSON',
				success: function(data) {
				    	jQuery('.avis-message-employer-wrapper').html(null);
					jQuery('.avis-employer-wrapper').prepend(data[0]);
					jQuery('.avis-message-employer').val(null);
					jQuery('.moyenne-score-wrapper').html(null);
					jQuery('.moyenne-score-wrapper').html(data[1])
					jQuery('.ponctualite-employer').val(null);
					jQuery('.connaisance-employer').val(null);
					jQuery('.attitude-employer').val(null);
					jQuery('.avis-employer-send').text("Soumettre");
					jQuery('.avis-employer-send').prop("disabled", false);
					delete_avis_employer($);
					avis_employer_monemploi($);
					monemploi_employer_function($);
				},
				error: function(error) {
					console.log(error);
				}
		        })
	        }
	});
	
}

jQuery(document).ready(function($) {
	avis_employer_monemploi($);
	monemploi_employer_function($);
});