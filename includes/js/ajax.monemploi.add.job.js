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

function monemploi_add_job($){
    	jQuery('.ns_submit').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		
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
	                jQuery('#monemploi-new-form').html(null);
	                jQuery('#monemploi-new-form').html(data);
	            },
	            error: function(errorThrown){
	                console.log(errorThrown);
	            }
	        });
    });
}

jQuery(document).ready(function($) {
    monemploi_add_job($);
});