<?php

function monemploi_user_edit_info() {

	$userdata = wp_get_current_user();

	$cover_photo = get_user_meta($userdata->ID, 'cover_photo', true);
	$user_avatar = get_user_meta($userdata->ID, 'user_avatar', true);
	
	$user_nicename = $userdata->user_nicename;
	$user_firstname = $userdata->first_name;
	$user_lastname = $userdata->last_name;
	$user_email = $userdata->user_email;

	$company_key = get_user_meta($userdata->ID, 'company_key', true);
	$adresse_key = get_user_meta($userdata->ID, 'adresse_key', true);
	$city_key = get_user_meta($userdata->ID, 'city_key', true);
	$province_key = get_user_meta($userdata->ID, 'province_key', true);
	$country_key = get_user_meta($userdata->ID, 'country_key', true);
	$postal_code_key = get_user_meta($userdata->ID, 'postal_code_key', true);
	$phone_key = get_user_meta($userdata->ID, 'phone_key', true);
	$poste_key = get_user_meta($userdata->ID, 'poste_key', true);
	
	$employer = get_role('employer'); 
	$employer->add_cap('upload_files'); 
	$employer->add_cap( 'unfiltered_upload' );
	
	$employeur = get_role('employeur'); 
	$employeur->add_cap('upload_files'); 
	$employer->add_cap( 'unfiltered_upload' );
	
     if ($_GET['update'] == true) {
        echo "<p>Tout les informations du compte on ete sauvegarder.</p>";
    }
    
        if(isset($_GET['new_email'])) {
            $uniquekey = get_user_meta($userdata->ID, 'unique_email_key', true);
            $newemail = get_user_meta($userdata->ID, 'new_email_key', true);
            if($_GET['new_email'] == $uniquekey){
                $user_data = array(
    	            'ID'         => $userdata->ID,
                    'user_email' => $newemail,
    	        );
	
	            $updated_user_id = wp_update_user( $user_data );
	
                echo "<p>La confirmation du nouveau couriel est fait.</p>";
   
            }
        }
    
	
	
	$str = __( 'Select File', 'text-domain' );
	echo'<input id="open-media-library" type="button" value="' . $str . '" class="button" style="position: relative; z-index: 1;"><img id="frontend-image" />';
	
	?><form id="cover-photo-upload" method="POST" enctype="multipart/form-data">
	    <?php wp_nonce_field('cover_photo_media_upload', 'cover_photo_media_nonce'); ?>
	    <input type="file" name="cover_photo_file" id="cover_photo_file" />
	    <input type="submit" name="submit_upload_cover_photo" value="Upload cover photo" />
	</form><?php
	
	?><form id="user-avatar-upload" method="POST" enctype="multipart/form-data">
	    <?php wp_nonce_field('user_avatar_media_upload', 'user_avatar_media_nonce'); ?>
	    <input type="file" name="user_avatar_file" id="user_avatar_file" />
	    <input type="submit" name="submit_upload_user_avatar" value="Upload user avatar" />
	</form><?php

	
	if (isset($_POST['submit_upload_cover_photo'])) {
	    // 1. Security Check: Verify Nonce
	    if (!isset($_POST['cover_photo_media_nonce']) || !wp_verify_nonce($_POST['cover_photo_media_nonce'], 'cover_photo_media_upload')) {
	        die('Security check failed.');
	    }
	
	    require_once(ABSPATH . 'wp-admin/includes/image.php');
	    require_once(ABSPATH . 'wp-admin/includes/file.php');
	    require_once(ABSPATH . 'wp-admin/includes/media.php');
	
	    $attachment_id = media_handle_upload('cover_photo_file', 0);
	
	    if (is_wp_error($attachment_id)) {
	        echo "Error uploading: " . $attachment_id->get_error_message();
	    } else {
	       update_user_meta($userdata->ID, 'cover_photo', $attachment_id);
	       header("Refresh:0");
	    }
	}
	echo wp_get_attachment_image( $cover_photo, 'thumbnail' );
	
	if (isset($_POST['submit_upload_user_avatar'])) {
	    // 1. Security Check: Verify Nonce
	    if (!isset($_POST['user_avatar_media_nonce']) || !wp_verify_nonce($_POST['user_avatar_media_nonce'], 'user_avatar_media_upload')) {
	        die('Security check failed.');
	    }
	
	    require_once(ABSPATH . 'wp-admin/includes/image.php');
	    require_once(ABSPATH . 'wp-admin/includes/file.php');
	    require_once(ABSPATH . 'wp-admin/includes/media.php');
	
	    $attachment_id = media_handle_upload('user_avatar_file', 0);
	
	    if (is_wp_error($attachment_id)) {
	        echo "Error uploading: " . $attachment_id->get_error_message();
	    } else {
	       update_user_meta($userdata->ID, 'user_avatar', $attachment_id);
	       header("Refresh:0");
	    }
	}
	echo wp_get_attachment_image( $user_avatar, 'thumbnail' );
	echo '<br>';
	
    echo '<form action="'. $_SERVER['REQUEST_URI'] .'" method="post">';
    	echo '<input type="text" id="user_nicename" name="user_nicename" placeholder="Votre nom d&#8216;utilisateur" value="' . $user_nicename . '" disabled>';
    	echo '<br>';
    	echo '<input type="text" id="user_firstname" name="user_firstname" placeholder="Votre prenom" value="' . $user_firstname . '">';
    	echo '<br>';
    	echo '<input type="text" id="user_lastname" name="user_lastname" placeholder="Votre nom de famille" value="' . $user_lastname . '">';
    	echo '<br>';
    	echo '<input type="text" id="user_email" name="user_email" placeholder="Votre E-Mail" value="' . $user_email . '">';
    	echo '<br>';
    	echo '<input type="text" id="company_key" name="company_key" placeholder="Votre nom de compagnie" value="' . $company_key . '">';
    	echo '<br>';
    	echo '<input type="text" id="adresse_key" name="adresse_key" placeholder="Votre adresse" value="' . $adresse_key . '">';
    	echo '<br>';
    	echo '<input type="text" id="city_key" name="city_key" placeholder="Votre ville" value="' . $city_key . '">';
    	echo '<br>';
    	echo '<input type="text" id="province_key" name="province_key" placeholder="Votre province" value="' . $province_key . '">';
    	echo '<br>';
    	echo '<input type="text" id="country_key" name="country_key" placeholder="Votre pays" value="' . $country_key . '">';
    	echo '<br>';
    	echo '<input type="text" id="postal_code_key" name="postal_code_key" placeholder="Votre code postal" value="' . $postal_code_key . '">';
    	echo '<br>';
    	echo '<input type="text" id="phone_key" name="phone_key" placeholder="Votre numero de téléphone" value="' . $phone_key . '">';
    	echo '<br>';
    	echo '<input type="text" id="poste_key" name="poste_key" placeholder="Votre numero de poste" value="' . $poste_key . '">';
    	echo '<br>';
        echo '<input type="submit" value="Mettre a jour" />';
	    echo '<input type="hidden" name="action" value="update_user_info_action" />';
    echo '</form>';

}
add_shortcode('monemploi-user-edit-info', 'monemploi_user_edit_info');

?>
