
jQuery(document).ready(function($) {
        jQuery('[data-toggle="datepickerstartjobscheduled"]').datepicker();
        jQuery('[data-toggle="datepickerendjobscheduled"]').datepicker();
        jQuery('[data-toggle="datepickerstarthoraire"]').datepicker();
	jQuery('[data-toggle="datepickerendhoraire"]').datepicker();
	jQuery('[data-toggle="datepickerstartpause"]').datepicker();
	jQuery('[data-toggle="datepickerendpause"]').datepicker();
        jQuery(".punchdateinout").each(function(index, element) {
            var indexadd = index + 1;
            jQuery('[data-toggle="punchdateinout-'+indexadd+'"]').datepicker();
        });
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
    jQuery("#phone_key").keyup(function() {
	    jQuery(this).val(jQuery(this).val().replace(/^(\d{3})(\d{3})(\d+)$/, "($1)$2-$3"));
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
    var handicap = jQuery('.handicap').val();
    var handicap_class = jQuery('.handicap-class').html();
	if(handicap == 2) {
	    jQuery('.handicap_wrapper').css('display', 'block');
	} else if(handicap_class == 2) {
	    jQuery('.handicap_wrapper').css('display', 'block');
	} else {
	    jQuery('.handicap_wrapper').css('display', 'none');
	}
    var situation_canada = jQuery('.situation_canada').val();
    var situation_canada_class = jQuery('.situation-canada-class').html();
        if(situation_canada == 3) {
            jQuery('.permis_travail_wrapper').css('display', 'block');
        } else if(situation_canada_class == 3)  {
            jQuery('.permis_travail_wrapper').css('display', 'block');
        } else  {
            jQuery('.permis_travail_wrapper').css('display', 'none');
        }
    var dossier_criminel = jQuery('.dossier-criminel').val();
    var dossier_criminel_class = jQuery('.dossier-criminel-class').html();
	if(dossier_criminel == 2) {
	    jQuery('.dossier_criminel_wrapper').css('display', 'block');
	} else if(dossier_criminel_class == 2) {
	    jQuery('.dossier_criminel_wrapper').css('display', 'block');
	} else  {
	    jQuery('.dossier_criminel_wrapper').css('display', 'none');
	}
    jQuery('.deja_travaille').change(function() {
        if(jQuery(this).val() == 1) {
            jQuery('.superieur-info').css('display', 'block');
        } else  {
            jQuery('.superieur-info').css('display', 'none');
        }
    });
    jQuery('.handicap').change(function() {
        if(jQuery(this).val() == 2) {
            jQuery('.handicap_wrapper').css('display', 'block');
        } else  {
            jQuery('.handicap_wrapper').css('display', 'none');
        }
    });
    jQuery('.situation_canada').change(function() {
        if(jQuery(this).val() == 3) {
            jQuery('.permis_travail_wrapper').css('display', 'block');
        } else  {
            jQuery('.permis_travail_wrapper').css('display', 'none');
        }
    });
    jQuery('.dossier-criminel').change(function() {
        if(jQuery(this).val() == 2) {
            jQuery('.dossier_criminel_wrapper').css('display', 'block');
        } else  {
            jQuery('.dossier_criminel_wrapper').css('display', 'none');
        }
    });
	var scrolled = false;	
	function updateScroll(){
		var element = jQuery('.user-chat-history-wrapper');	
		if(element.prop('scrollHeight') - element.scrollTop() == element.outerHeight()) {
		    	scrolled = false;
		}
		if(!scrolled){
			element.scrollTop(element.prop('scrollHeight'));
		} 
	}
	
	jQuery('.user-chat-history-wrapper').resize(function() {
	    scrolled = false;
	    updateScroll();	
	});
	
	if(jQuery('.user-chat-history-wrapper')){
		updateScroll();
		setInterval(updateScroll, 1000);
	}
	
	jQuery('.user-chat-history-wrapper').on('scroll', function(){
	    	scrolled = true;
	});
	
	jQuery('.message-chat').keypress(function (e) {
	var key = e.which;
	 if(key == 13) {
	    jQuery('.chat-message-send').click();
	    return false;  
	  }
	});  	
	
	var numItems = $('.job-wrapper-box').length;
	jQuery('.job-search-count').html(numItems);
	
	var usernumItems = $('.user-search-wrapper').length;
	jQuery('.user-search-count').html(usernumItems);
	
	var punchdateinout = parseInt(jQuery('.punchdateinout').length);
	jQuery('.punchquantity').val(punchdateinout);
	
	jQuery(".addpunch").on("click", function() {
		var punchdateinout = jQuery('.punchdateinout').length+1;
		var punchtimeinout = jQuery('.punchtimeinout').length+1;
		if (punchdateinout % 2 === 0) {
			var html = 'sortie - <input type="text" id="punchdateinout-'+punchdateinout+'" class="punchdateinout" name="punchdateinout-'+punchdateinout+'" data-toggle="punchdateinout-'+punchdateinout+'"> - <input type="time" id="punchtimeinout-'+punchtimeinout+'" class="punchtimeinout" name="punchtimeinout-'+punchtimeinout+'"><br>';
		} else {
			var html = 'entrer - <input type="text" id="punchdateinout-'+punchdateinout+'" class="punchdateinout" name="punchdateinout-'+punchdateinout+'" data-toggle="punchdateinout-'+punchdateinout+'"> - <input type="time" id="punchtimeinout-'+punchtimeinout+'" class="punchtimeinout" name="punchtimeinout-'+punchtimeinout+'"><br>';
		}
		jQuery('.addpunchdatetime').append(html);
		jQuery('.punchquantity').val(punchdateinout);
        	jQuery('[data-toggle="punchdateinout-'+punchdateinout+'"]').datepicker();
	});
	jQuery('.dayoff-reason').on('change', function() {
	    var selectedValue = $(this).val();
	    if(selectedValue == 6) {
	    	jQuery('.employee-replace').css('display', 'block');
	    } else {
	    	jQuery('.employee-replace').css('display', 'none');
	    } 
	});
});

jQuery(document).ready(function($) {
	$('.count-target-emploi').each(function () {
	  var $this = $(this);
	  jQuery({ Counter: 0 }).animate({ Counter: $this.text() }, {
	    duration: 10000,
	    easing: 'swing',
	    step: function () {
	      $this.text(Math.ceil(this.Counter));
	    }
	  });
	});
	$('.count-target-employeurs').each(function () {
	  var $this = $(this);
	  jQuery({ Counter: 0 }).animate({ Counter: $this.text() }, {
	    duration: 10000,
	    easing: 'swing',
	    step: function () {
	      $this.text(Math.ceil(this.Counter));
	    }
	  });
	});
	$('.count-target-employer').each(function () {
	  var $this = $(this);
	  jQuery({ Counter: 0 }).animate({ Counter: $this.text() }, {
	    duration: 10000,
	    easing: 'swing',
	    step: function () {
	      $this.text(Math.ceil(this.Counter));
	    }
	  });
	});
	$('.count-target-candidacys').each(function () {
	  var $this = $(this);
	  jQuery({ Counter: 0 }).animate({ Counter: $this.text() }, {
	    duration: 10000,
	    easing: 'swing',
	    step: function () {
	      $this.text(Math.ceil(this.Counter));
	    }
	  });
	});
	$('.count-target-candidacys-embaucher').each(function () {
	  var $this = $(this);
	  jQuery({ Counter: 0 }).animate({ Counter: $this.text() }, {
	    duration: 10000,
	    easing: 'swing',
	    step: function () {
	      $this.text(Math.ceil(this.Counter));
	    }
	  });
	});
	$('.count-target-avis-employeur').each(function () {
	  var $this = $(this);
	  jQuery({ Counter: 0 }).animate({ Counter: $this.text() }, {
	    duration: 10000,
	    easing: 'swing',
	    step: function () {
	      $this.text(Math.ceil(this.Counter));
	    }
	  });
	});
	$('.count-target-avis-employer').each(function () {
	  var $this = $(this);
	  jQuery({ Counter: 0 }).animate({ Counter: $this.text() }, {
	    duration: 10000,
	    easing: 'swing',
	    step: function () {
	      $this.text(Math.ceil(this.Counter));
	    }
	  });
	});
	$('.count-chat').each(function () {
	  var $this = $(this);
	  jQuery({ Counter: 0 }).animate({ Counter: $this.text() }, {
	    duration: 10000,
	    easing: 'swing',
	    step: function () {
	      $this.text(Math.ceil(this.Counter));
	    }
	  });
	});
	$('.count-horaire').each(function () {
	  var $this = $(this);
	  jQuery({ Counter: 0 }).animate({ Counter: $this.text() }, {
	    duration: 10000,
	    easing: 'swing',
	    step: function () {
	      $this.text(Math.ceil(this.Counter));
	    }
	  });
	});
	$('.count-punch-entrer').each(function () {
	  var $this = $(this);
	  jQuery({ Counter: 0 }).animate({ Counter: $this.text() }, {
	    duration: 10000,
	    easing: 'swing',
	    step: function () {
	      $this.text(Math.ceil(this.Counter));
	    }
	  });
	});
	$('.count-punch-sortie').each(function () {
	  var $this = $(this);
	  jQuery({ Counter: 0 }).animate({ Counter: $this.text() }, {
	    duration: 10000,
	    easing: 'swing',
	    step: function () {
	      $this.text(Math.ceil(this.Counter));
	    }
	  });
	});
});

jQuery(document).ready(function($) {

	var mincalcsumavance = jQuery('.mincalcsumavance').html();
	var mincalcsumlate = jQuery('.mincalcsumlate').html();
	var mincalcsumsup = jQuery('.mincalcsumsup').html();
	var mincalcsumhatif = jQuery('.mincalcsumhatif').html();
	var mincalcsumtotal = jQuery('.mincalcsumtotal').html();
	
	jQuery('.mincalcsumavanceup').html(mincalcsumavance);
	jQuery('.mincalcsumlateup').html(mincalcsumlate);
	jQuery('.mincalcsumsupup').html(mincalcsumsup);
	jQuery('.mincalcsumhatifup').html(mincalcsumhatif);
	jQuery('.mincalcsumtotalup').html(mincalcsumtotal);
	
	// Select the checkbox
	const checkbox = document.getElementById('coworker');
	
	// Listen for changes
	checkbox.addEventListener('change', function() {
		if (this.checked) {
		
			if(this.checked){
				var val = true; 
			} 

			jQuery.ajax({
				type: 'post',
				url: coworker_monemploi_ajax_url,
				data: {
					'value': val,
					'action': 'coworker_see_unsee'
				},
			dataType: 'JSON',
				success: function(data) {
					jQuery('#coworkertest').html(data);
					location.reload();
				},
				error: function(error) {
					console.log(error);
				}
			})
			
		} else {
		
			if(!this.checked){
				var val = false; 
			} 

			jQuery.ajax({
				type: 'post',
				url: coworker_monemploi_ajax_url,
				data: {
					'value': val,
					'action': 'coworker_see_unsee'
				},
			dataType: 'JSON',
				success: function(data) {
					location.reload();
				},
				error: function(error) {
					console.log(error);
				}
			})
			
		}
	});

});

jQuery(document).ready(function($) {
	var urlParams = new URLSearchParams(window.location.search);
	var horaireweek = urlParams.get('horaireweek');
	var summary = urlParams.get('summary');	
	var month = urlParams.get('month');
	var biweek = urlParams.get('biweek');	
	var week = urlParams.get('week');
	
	if (jQuery(".employee_horaire_select")[0]){
		var employee_horaire_select = jQuery('#employee_horaire_select').find(":selected").val();
		
		jQuery.ajax({
			type: 'post',
			url: employee_horaire_select_monemploi_ajax_url,
			data: {
				'value': employee_horaire_select,
				'action': 'employee_horaire_select'
			},
		dataType: 'JSON',
			success: function(data) {
				if(horaireweek || summary || month || biweek || week){
				    if (window.location.href.indexOf('reload')==-1) {
				         window.location.replace(window.location.href+'&reload');
				    }
				} else {
				    if (window.location.href.indexOf('reload')==-1) {
				         window.location.replace(window.location.href+'?reload');
				    }
				} 
			},
			error: function(error) {
				console.log(error);
			}
		})
		
		jQuery('#employee_horaire_select').on('change', function() {
		        var selectedValue_horaire = jQuery(this).find(":selected").val();
	
			jQuery.ajax({
				type: 'post',
				url: employee_horaire_select_monemploi_ajax_url,
				data: {
					'value': selectedValue_horaire,
					'action': 'employee_horaire_select'
				},
			dataType: 'JSON',
				success: function(data) {
					location.reload();
				},
				error: function(error) {
					console.log(error);
				}
			})
		});
		
	} else {
	
		jQuery.ajax({
			type: 'post',
			url: employee_horaire_select_monemploi_ajax_url,
			data: {
				'value': -1,
				'action': 'employee_horaire_select'
			},
		dataType: 'JSON',
			success: function(data) {
				//NULL
			},
			error: function(error) {
				console.log(error);
			}
		})
		
	}

	if (jQuery(".employee_scores_select")[0]){
		var employee_scores_select = jQuery('#employee_scores_select').find(":selected").val();
		
		jQuery.ajax({
			type: 'post',
			url: employee_scores_select_monemploi_ajax_url,
			data: {
				'value': employee_scores_select,
				'action': 'employee_scores_select'
			},
		dataType: 'JSON',
			success: function(data) {
				if(month){
				    if (window.location.href.indexOf('reload')==-1) {
				         window.location.replace(window.location.href+'&reload');
				    }
				} else {
				    if (window.location.href.indexOf('reload')==-1) {
				         window.location.replace(window.location.href+'?reload');
				    }
				} 
			},
			error: function(error) {
				console.log(error);
			}
		})
		
		jQuery('#employee_scores_select').on('change', function() {
		        var selectedValue_scores = jQuery(this).find(":selected").val();
	
			jQuery.ajax({
				type: 'post',
				url: employee_scores_select_monemploi_ajax_url,
				data: {
					'value': selectedValue_scores,
					'action': 'employee_scores_select'
				},
			dataType: 'JSON',
				success: function(data) {
					location.reload();
				},
				error: function(error) {
					console.log(error);
				}
			})
		});
		
	} else {
	
		jQuery.ajax({
			type: 'post',
			url: employee_scores_select_monemploi_ajax_url,
			data: {
				'value': -1,
				'action': 'employee_scores_select'
			},
		dataType: 'JSON',
			success: function(data) {
				//NULL
			},
			error: function(error) {
				console.log(error);
			}
		})
		
	}	
	
	if (jQuery(".jobs_horaire_select")[0]){
		var jobs_horaire_select = jQuery('#jobs_horaire_select').find(":selected").val();
		
		jQuery.ajax({
			type: 'post',
			url: horaire_jobs_select_monemploi_ajax_url,
			data: {
				'value': jobs_horaire_select,
				'action': 'jobs_horaire_select'
			},
		dataType: 'JSON',
			success: function(data) {
				if(horaireweek || summary || month || biweek || week){
				    if (window.location.href.indexOf('reload')==-1) {
				         window.location.replace(window.location.href+'&reload');
				    }
				} else {
				    if (window.location.href.indexOf('reload')==-1) {
				         window.location.replace(window.location.href+'?reload');
				    }
				} 
			},
			error: function(error) {
				console.log(error);
			}
		})
		
		jQuery('#jobs_horaire_select').on('change', function() {
		        var jobs_horaire_select_ = jQuery(this).val();
	
			jQuery.ajax({
				type: 'post',
				url: horaire_jobs_select_monemploi_ajax_url,
				data: {
					'value': jobs_horaire_select_,
					'action': 'jobs_horaire_select'
				},
			dataType: 'JSON',
				success: function(data) {
					location.reload();
				},
				error: function(error) {
					console.log(error);
				}
			})
		});
		
	} else {
	
		jQuery.ajax({
			type: 'post',
			url: horaire_jobs_select_monemploi_ajax_url,
			data: {
				'value': -1,
				'action': 'jobs_horaire_select'
			},
		dataType: 'JSON',
			success: function(data) {
				//NULL
			},
			error: function(error) {
				console.log(error);
			}
		})
		
	}
	
	var ponctualite_moyenne_round = jQuery('.ponctualite_moyenne_round').html();
	var connaisance_moyenne_round = jQuery('.connaisance_moyenne_round').html();
	var attitude_moyenne_round = jQuery('.attitude_moyenne_round').html();
	
	jQuery('.ponctualite_moyenne_round_up').html(ponctualite_moyenne_round);
	jQuery('.connaisance_moyenne_round_up').html(connaisance_moyenne_round);
	jQuery('.attitude_moyenne_round_up').html(attitude_moyenne_round);
	
	if(ponctualite_moyenne_round == 'NAN'){
		jQuery('.moyenne_employee_scores').css('display', 'none');
		jQuery('.moyenne_employee_no').css('display', 'block');
	}
	
	if(ponctualite_moyenne_round >= 0.1){
		jQuery('.moyenne_employee_scores').css('display', 'block');
		jQuery('.moyenne_employee_no').css('display', 'none');
	}
});

