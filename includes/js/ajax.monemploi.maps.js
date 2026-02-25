

function getDistance($) {          
  jQuery('.autocompleteDeparture').each(function(i, obj) {
     var distanceService = new google.maps.DistanceMatrixService();
     distanceService.getDistanceMatrix({
        origins: [jQuery(".autocompleteDeparture_"+i).text()],
        destinations: [jQuery(".autocompleteArrival_"+i).text()],
        travelMode: google.maps.TravelMode.WALKING,
        unitSystem: google.maps.UnitSystem.METRIC,
        durationInTraffic: true,
        avoidHighways: false,
        avoidTolls: false
    },    
    function (response, status) {
        if (status !== google.maps.DistanceMatrixStatus.OK) {
          jQuery(".distance_"+i).html(status);
        } else {
          jQuery(".distance_"+i).html( response.rows[0].elements[0].distance.text );
        }
    });
    
    });
    
  }
  
  function getDistance_($) {
  jQuery('.completeDeparture').each(function(i, obj) {
     var distanceService = new google.maps.DistanceMatrixService();
     distanceService.getDistanceMatrix({
        origins: [jQuery(".completeDeparture_"+i).text()],
        destinations: [jQuery(".completeArrival_"+i).text()],
        travelMode: google.maps.TravelMode.WALKING,
        unitSystem: google.maps.UnitSystem.METRIC,
        durationInTraffic: true,
        avoidHighways: false,
        avoidTolls: false
    },    
    function (response, status) {
        if (status !== google.maps.DistanceMatrixStatus.OK) {
          jQuery(".widgetdistance_"+i).html(status);
        } else {
          jQuery(".widgetdistance_"+i).html( response.rows[0].elements[0].distance.text );
        }
    });
    
    });
    
  }
  
jQuery(document).ready(function($) {
    getDistance($);
    getDistance_($);
    
    	jQuery('.monemploi_add_code_postal_text').on('keydown', function(event) {
	    	geocoder = new google.maps.Geocoder();
	    	var adresse = jQuery('.monemploi_add_code_postal_text').val();
		geocoder.geocode({ 'address': adresse }, function(results, status) {
			if (results[0]) {
				var city = "";
				for (var i = 0; i < results[0].address_components.length; i++) {
				    for (var b = 0; b < results[0].address_components[i].types.length; b++) {
				        if (results[0].address_components[i].types[b] == "locality") {
				            city = results[0].address_components[i].long_name;
				            break;
				        }
				    }
				    if (city) break;
				}
			}			
		        jQuery(".monemploi_add_city_text").val(city);
		});
	});
  
});