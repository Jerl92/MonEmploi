function monemploi_function($) {
	jQuery('.show-hide-avis').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
	  if (jQuery(".avis-employeur-wrapper").css("display") === "none") {
	    jQuery(".avis-employeur-wrapper").css("display", "block");
	  } else {
	    jQuery(".avis-employeur-wrapper").css("display", "none");
	  }
	});

	jQuery('.avis-message-employeur').on('keydown', function(event) {
	  jQuery('.number-of-char').text($(this).val().length + ' Caractères');
	});
}

function avis_employeur_monemploi($) {
	jQuery('.avis-employeur-send').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		
			var erreur = 0;
			var $this = jQuery(this),
				object_id = $this.data('object-id');
				
			var avismessage = jQuery('.avis-message-employeur').val();
			var horaire = jQuery('.horaire-employeur').val();
			var superieur = jQuery('.superieur-employeur').val();
			var paie = jQuery('.paie-employeur').val();
			
			jQuery('.avis-employeur-send').text("Veuillez patienter");
			jQuery('.avis-employeur-send').prop("disabled", true);
			
			if(jQuery(".avis-message-employeur").val().length < 3){
				erreur = 1;
				jQuery('.avis-employeur-send').text("Soumettre");
				jQuery('.avis-employeur-send').prop("disabled", false);
				jQuery('.avis_error').append('<div class="avis_error_message" style="color: red">Votre message est vide</div>');
				jQuery('.avis-message-employeur').css('border', '1.5px solid red');
				setTimeout(function() {
					jQuery('.avis_error_message').remove();
				}, 5000);
			} else if(jQuery(".avis-message-employeur").val().length > 250){
				erreur = 1;
				jQuery('.avis-employeur-send').text("Soumettre");
				jQuery('.avis-employeur-send').prop("disabled", false);
				jQuery('.avis_error').append('<div class="avis_error_message" style="color: red;">Votre message est trop long. 250 caractères max</div>');
				jQuery('.avis-message-employeur').css('border', '1.5px solid red');
				setTimeout(function() {
					jQuery('.avis_error_message').remove();
				}, 5000);
			} else {
			    jQuery('.avis-message-employeur').css('border', '0.5px solid gray');
			}
			
			if((jQuery(".horaire-employeur").val().length < 1 || jQuery('.horaire-employeur').val() > 5) || (jQuery('.horaire-employeur').val() < 0)){
				erreur = 1;
				jQuery('.avis-employeur-send').text("Soumettre");
				jQuery('.avis-employeur-send').prop("disabled", false);
				jQuery('.avis_error').append('<div class="avis_error_horaire" style="color: red;">Votre score de lhoraire est pas entre 0 et 5</div>');
				jQuery('.horaire-employeur').css('border', '1.5px solid red');
				setTimeout(function() {
					 jQuery('.avis_error_horaire').remove();
				}, 5000);
			} else {
			    jQuery('.horaire-employeur').css('border', '0.5px solid gray');
			}
			
			if((jQuery(".superieur-employeur").val().length < 1 || jQuery('.superieur-employeur').val() > 5) || (jQuery('.superieur-employeur').val() < 0)){
				erreur = 1;
				jQuery('.avis-employeur-send').text("Soumettre");
				jQuery('.avis-employeur-send').prop("disabled", false);
				jQuery('.avis_error').append('<div class="avis_error_superieur" style="color: red">Votre score du superieur est pas entre 0 et 5</div>');
				jQuery('.superieur-employeur').css('border', '1.5px solid red');
				setTimeout(function() {
					 jQuery('.avis_error_superieur').remove();
				}, 5000);
			} else {
			    jQuery('.superieur-employeur').css('border', '0.5px solid gray');
			}


			if((jQuery(".paie-employeur").val().length < 1 || jQuery('.paie-employeur').val() > 5) || (jQuery('.paie-employeur').val() < 0)){
				erreur = 1;
				jQuery('.avis-employeur-send').text("Soumettre");
				jQuery('.avis-employeur-send').prop("disabled", false);
				jQuery('.avis_error').append('<div class="avis_error_paie" style="color: red">Votre score de la paie est pas entre 0 et 5</div>');
				jQuery('.paie-employeur').css('border', '1.5px solid red');
				setTimeout(function() {
					 jQuery('.avis_error_paie').remove();
				}, 5000);
			} else {
			   jQuery('.paie-employeur').css('border', '0.5px solid gray');
			}

			if(erreur === 0){
			jQuery.ajax({
				type: 'post',
				url: avis_employeur_monemploi_ajax_url,
				data: {
				    'object_id': object_id,
				    'avismessage': avismessage,
				    'horaire': horaire,
				    'superieur': superieur,
				    'paie': paie,
					'action': 'avis_employeur'
	            },
	            dataType: 'JSON',
				success: function(data) {
				    	jQuery('.avis-message-employeur-wrapper').html(null);
					jQuery('.avis-employeur-wrapper').prepend(data[0]);
					jQuery('.avis-message-employeur').val(null);
					jQuery('.moyenne-score-wrapper').html(null);
					jQuery('.moyenne-score-wrapper').html(data[1])
					jQuery('.horaire-employeur').val(null);
					jQuery('.superieur-employeur').val(null);
					jQuery('.paie-employeur').val(null);
					jQuery('.avis-employeur-send').text("Soumettre");
					jQuery('.avis-employeur-send').prop("disabled", false);
					avis_employeur_monemploi($);
					delete_avis($);
					monemploi_function($);
				},
				error: function(error) {
					console.log(error);
				}
		        })
	        }
	});
	
}

jQuery(document).ready(function($) {
	avis_employeur_monemploi($);
	monemploi_function($);
});

jQuery(document).ready(function() {
	// Get the target number from the HTML element's text content
	var targetNumemploi = parseInt(jQuery('.count-target-emploi').html(), 10);
	var targetNumemployeurs = parseInt(jQuery('.count-target-employeurs').html(), 10);
	var targetNumemployer = parseInt(jQuery('.count-target-employer').html(), 10);
	var targetNumcandidacys = parseInt(jQuery('.count-target-candidacys').html(), 10);
	var targetNumcandidacysembaucher = parseInt(jQuery('.count-target-candidacys-embaucher').html(), 10);
	var targetNumavisemployeur = parseInt(jQuery('.count-target-avis-employeur').html(), 10);
	var targetNumavisemployer = parseInt(jQuery('.count-target-avis-employer').html(), 10);
	
	// Set the initial display to 0
	jQuery('.count-target-emploi').html('0');
	jQuery('.count-target-employeurs').html('0');
	jQuery('.count-target-employer').html('0');
	jQuery('.count-target-candidacys').html('0');
	jQuery('.count-target-candidacys-embaucher').html('0');
	jQuery('.count-target-avis-employeur').html('0');
	jQuery('.count-target-avis-employer').html('0');
	
	// Animate the number
	jQuery({ Counter: 0 }).animate({ Counter: targetNumemploi }, {
		duration: 5000, // Animation duration in milliseconds
		easing: 'swing',
		step: function (now) {
		    // Update the element's html with the current animated value, rounded to an integer
		    jQuery('.count-target-emploi').html(Math.ceil(now));
		}
	});
	
	jQuery({ Counter: 0 }).animate({ Counter: targetNumemployeurs }, {
		duration: 5000, // Animation duration in milliseconds
		easing: 'swing',
		step: function (now) {
		    // Update the element's html with the current animated value, rounded to an integer
		    jQuery('.count-target-employeurs').html(Math.ceil(now));
		}
	});
	
	jQuery({ Counter: 0 }).animate({ Counter: targetNumemployer }, {
		duration: 5000, // Animation duration in milliseconds
		easing: 'swing',
		step: function (now) {
		    // Update the element's html with the current animated value, rounded to an integer
		    jQuery('.count-target-employer').html(Math.ceil(now));
		}
	});
	
	jQuery({ Counter: 0 }).animate({ Counter: targetNumcandidacys }, {
		duration: 5000, // Animation duration in milliseconds
		easing: 'swing',
		step: function (now) {
		    // Update the element's html with the current animated value, rounded to an integer
		    jQuery('.count-target-candidacys').html(Math.ceil(now));
		}
	});
	
	jQuery({ Counter: 0 }).animate({ Counter: targetNumcandidacysembaucher }, {
		duration: 5000, // Animation duration in milliseconds
		easing: 'swing',
		step: function (now) {
		    // Update the element's html with the current animated value, rounded to an integer
		    jQuery('.count-target-candidacys-embaucher').html(Math.ceil(now));
		}
	});
	
	jQuery({ Counter: 0 }).animate({ Counter: targetNumavisemployeur }, {
		duration: 5000, // Animation duration in milliseconds
		easing: 'swing',
		step: function (now) {
		    // Update the element's html with the current animated value, rounded to an integer
		    jQuery('.count-target-avis-employeur').html(Math.ceil(now));
		}
	});

        jQuery({ Counter: 0 }).animate({ Counter: targetNumavisemployer }, {
		duration: 5000, // Animation duration in milliseconds
		easing: 'swing',
		step: function (now) {
		    // Update the element's html with the current animated value, rounded to an integer
		    jQuery('.count-target-avis-employer').html(Math.ceil(now));
		}
	});

});


