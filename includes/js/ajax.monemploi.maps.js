
var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
    return false;
};

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
  
  
function get_city_name($) {

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

}

function get_directions($) {
	var origin = jQuery('#user-adress').html();
	var destination = jQuery('#job-adress').html();
	var departuretime = jQuery('.departuretime').val();
	var departuredate = jQuery('.departuredate').html();
	
	var map = new google.maps.Map(document.getElementById('map'), {
		zoom: 4,
		center: {lat: -24.345, lng: 134.46}  // Australia.
	});
	
	var geocoder = new google.maps.Geocoder;
	var directionsService = new google.maps.DirectionsService;
	var directionsRenderer = new google.maps.DirectionsRenderer({
		draggable: false,
		map: map
	});
	
	if(getUrlParameter('travelMode') === 'voiture') {
		var request = {
			origin: origin, // e.g., "Origin City, State" or { lat: X, lng: Y }
			destination: destination, // e.g., "Destination City, State" or { lat: A, lng: B }
			travelMode: google.maps.TravelMode.DRIVING,
			
		};
	} else if(getUrlParameter('travelMode') === 'autobus') {
	    if(departuretime != null) {
	    var request = {
			origin: origin, // e.g., "Origin City, State" or { lat: X, lng: Y }
			destination: destination, // e.g., "Destination City, State" or { lat: A, lng: B }
			travelMode: google.maps.TravelMode.TRANSIT,
            drivingOptions: {
                    departureTime: new Date(departuredate+'T'+departuretime+'Z')
            }
		};
	    } else {
        request = {
			origin: origin, // e.g., "Origin City, State" or { lat: X, lng: Y }
			destination: destination, // e.g., "Destination City, State" or { lat: A, lng: B }
			travelMode: google.maps.TravelMode.TRANSIT,
		};
	    }
	} else {
        var request = {
			origin: origin, // e.g., "Origin City, State" or { lat: X, lng: Y }
			destination: destination, // e.g., "Destination City, State" or { lat: A, lng: B }
			travelMode: google.maps.TravelMode.DRIVING,
			
		};
	}

    directionsService.route(request, (result, status) => {
		if (status == google.maps.DirectionsStatus.OK) {
		    // The renderer draws the polyline and adds default markers
		    directionsRenderer.setDirections(result); 
		} else {
		    window.alert("Directions request failed due to " + status);
		}
	});
	
$('.departuretime').on('change', function() {
	directionsService.route(request, (result, status) => {
		if (status == google.maps.DirectionsStatus.OK) {
		    // The renderer draws the polyline and adds default markers
		    directionsRenderer.setDirections(result); 
		} else {
		    window.alert("Directions request failed due to " + status);
		}
	});
});

}

jQuery(window).on('load', function() {
	var get_day = getUrlParameter('day_filter');
	if(get_day) {
		jQuery(".day_filter").val(get_day);
		jQuery(".job-wrapper-box").each(function(index, element) {
			var day = jQuery('.get-the-date-difference-'+index).html();
			if(parseInt(get_day) < parseInt(day)) {
				jQuery('#job-wrapper-box-'+index).remove();
			}
		});	
	}
	var get_km = getUrlParameter('km_filter');
	if(get_km) {
		jQuery(".km_filter").val(get_km);
		jQuery(".job-wrapper-box").each(function(index, element) {
			var distance = jQuery('.distance_'+index).html();
			if(parseInt(get_km) < parseInt(distance)) {
				jQuery('#job-wrapper-box-'+index).remove();
			}
		});
	}
});
  
jQuery(document).ready(function($) {
    getDistance($);
    getDistance_($);
    get_city_name($);
    get_directions($);
});