function monemploi_update_user_info($) {
    jQuery('.user-edit-info').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();

        var $this = jQuery(this),
            object_id = $this.data('object-id');

        var user_firstname = jQuery('#user_firstname').val();
        var user_lastname = jQuery('#user_lastname').val();
        var user_email = jQuery('#user_email').val();
        var company_key = jQuery('#company_key').val();
        var adresse_key = jQuery('#adresse_key').val();
        var city_key = jQuery('#city_key').val();
        var province_key = jQuery('#province_key').val();
        var country_key = jQuery('#country_key').val();
        var postal_code_key = jQuery('#postal_code_key').val();
        var phone_key = jQuery('#phone_key').val();
        var poste_key = jQuery('#poste_key').val();

        jQuery.ajax({
            type: 'post',
            url: update_user_info_monemploi_ajax_url,
            data: {
                'object_id': object_id,
                'user_firstname': user_firstname,
                'user_lastname': user_lastname,
                'user_email': user_email,
                'company_key': company_key,
                'adresse_key': adresse_key,
                'city_key': city_key,
                'province_key': province_key,
                'country_key': country_key,
                'postal_code_key': postal_code_key,
                'phone_key': phone_key,
                'poste_key': poste_key,
                'action': 'update_user_info'
            },
            dataType: 'JSON',
            success: function(data) {
                jQuery('.user-edit-info-update').html('');
                jQuery('.user-edit-info-update').html(data);
                setTimeout(function() {
                    jQuery('.user-edit-info-update').html('');
                }, 5000);

            },
            error: function(error) {
                console.log(error);
            }
        })
    });
}


jQuery(document).ready(function($) {
    monemploi_update_user_info($);
});