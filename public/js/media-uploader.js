jQuery(document).ready(function($) {
    $('#open-media-library').on('click', function(e) {
        e.preventDefault();
        var frame = wp.media({
	            title    : 'Select or Upload Media',
				button   : {
					text : 'Use this media' )
				},
				library: {
					type: [ 'image' ]
				},
				multiple : false  // Set to true to allow multiple files to be selected
        });

        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            console.log(attachment.url); // Use attachment.url or attachment.id
        });

        frame.open();
    });
});