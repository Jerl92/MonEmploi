<?php

if (!defined('ABSPATH')) {
    exit;
}

function handle_frontend_media_upload() {
    // Verify nonce for security
    if ( ! isset( $_POST['media_upload_nonce'] ) || ! wp_verify_nonce( $_POST['media_upload_nonce'], 'media_upload' ) ) {
        wp_die( 'Security check failed.' );
    }

    // Check if file was uploaded
    if ( empty( $_FILES['file_upload'] ) ) {
        wp_die( 'No file selected.' );
    }

    // Required WordPress files for media handling
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    require_once( ABSPATH . 'wp-admin/includes/media.php' );

    // Handle the upload using the built-in WordPress function
    $upload = wp_handle_upload( $_FILES['file_upload'], array( 'test_form' => false ) );

    if ( ! empty( $upload['error'] ) ) {
        wp_die( $upload['error'] );
    }

    // Insert the uploaded file into the media library
    if($upload['type'] == 'application/msword' || $upload['type'] == 'application/pdf' || $upload['type'] == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
        $attachment_id = wp_insert_attachment(
            array(
                'guid'           => $upload['url'],
                'post_mime_type' => $upload['type'],
                'post_title'     => basename( $upload['file'] ),
                'post_content'   => '',
                'post_status'    => 'publish',
            ),
            $upload['file']
        );
    } else {
        wp_die( 'Ce fichier nest pas un fichier *.pdf, *.doc ou *.docx' );
    }

    if ( is_wp_error( $attachment_id ) || ! $attachment_id ) {
        wp_die( 'Error adding file to media library.' );
    }

    // Generate metadata and image sizes (essential for images)
    wp_update_attachment_metadata(
        $attachment_id,
        wp_generate_attachment_metadata( $attachment_id, $upload['file'] )
    );

    // Redirect the user after successful upload
    wp_safe_redirect( home_url( '/curriculum-vitae/' ) ); // Redirect back to the front page
    exit;
}

// Hook the function to the 'admin_post_' action specified in the form
add_action( 'admin_post_frontend_media_upload', 'handle_frontend_media_upload' );
add_action( 'admin_post_nopriv_frontend_media_upload', 'handle_frontend_media_upload' ); // For logged out users

function remove_um_profile_navbar() {
    remove_action('um_profile_navbar', 'um_profile_navbar', 9 );
}
add_action('init','remove_um_profile_navbar');

function cf_search_join( $join ) {
    global $wpdb;

        if ( is_search() && !is_admin() ) {    
            $join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
        }

    return $join;
}
add_filter('posts_join', 'cf_search_join' );

/**
 * Modify the search query with posts_where
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where
 */
function cf_search_where( $where ) {
    global $wpdb;

        if ( is_search() ) {
            $where = preg_replace(
                "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
                "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
        }

    return $where;
}
add_filter( 'posts_where', 'cf_search_where' );

function show_draft_posts_on_front( $query ) {
    // Check if we are in the admin area, and if so, return the original query
    if ( is_admin() ) {
        return $query;
    }

    // Check if a user is logged in and has permission to view drafts
    if ( is_user_logged_in() && current_user_can( 'employeur' ) ) {
        // Ensure the main loop is targeted and no suppress_filters argument is present
        if ( $query->is_main_query() && !isset($query->query_vars['suppress_filters']) ) {
            $query->set( 'post_status', [ 'publish', 'draft', 'future' ] );
        }
    }
    
    return $query;
}
add_filter( 'pre_get_posts', 'show_draft_posts_on_front' );

function my_custom_after_registration_action( $user_id, $args ) {
	if ( empty( $user_id ) || is_wp_error( $user_id ) ) {
		return;
	}    
	$user = new WP_User( $user_id );
	$meta_for_user = get_user_meta( $user_id, 'status', true ); 
	$meta_user_status = $meta_for_user[0];
	if($meta_user_status == 'Employeur'){    
		$user->set_role( 'employeur' );
	}
	if($meta_user_status == 'Employer'){ 
		$user->set_role( 'employer' );
	}
}
add_action( 'um_registration_set_extra_data', 'my_custom_after_registration_action', 10, 2 );

add_filter( 'login_url', 'um_custom_login_url', 10, 3 );
function um_custom_login_url( $login_url, $redirect, $force_reauth ) {
	if(function_exists('um_get_core_page')) {
    		return um_get_core_page( 'login' );
    	}
}

function auto_approve_all_comments( $approved, $commentdata ) {
    return 1;
}
add_filter( 'pre_comment_approved', 'auto_approve_all_comments', 99, 2 );

function your_custom_menu_item($items, $args) {
    // Only add to the primary menu location
    if ($args->theme_location == 'top-header-menu') { 
        if (is_user_logged_in()) {
            $items .= '<li><a href="'. wp_logout_url( home_url() ) .'" class="">Déconnexion</a></li>'; 
        }
    }
    return $items;
}
add_filter('wp_nav_menu_items', 'your_custom_menu_item', 10, 2);


function send_email_on_future_publish( $new_status, $old_status, $post ) {
    // Check if the new status is 'publish' and the old status was 'future'
    if ( 'publish' === $new_status && 'future' === $old_status ) {
        // Ensure it's a 'post' type (can add other post types if needed)
        if ( 'emploi' === $post->post_type ) {
            $author = get_userdata( $post->post_author );
            $author_email = $author->user_email;
            $post_title = get_the_title( $post->ID );
            $post_url = get_permalink( $post->ID );

            $subject = 'Your scheduled post "' . $post_title . '" has been published!';
            $message = '
                <p>Hello ' . $author->display_name . ',</p>
                <p>Your post "' . $post_title . '" has been published on the website.</p>
                <p>View it here: <a href="' . $post_url . '">' . $post_url . '</a></p>
            ';
            $headers = array('Content-Type: text/html; charset=UTF-8');

            // Send the email
            wp_mail( $author_email, $subject, $message, $headers );
        }
    }
}
add_action( 'transition_post_status', 'send_email_on_future_publish', 10, 3 );

add_action('init', function(){

  // not the login request?
  if(!isset($_POST['action']) || $_POST['action'] !== 'my_login_action')
      return;
  
      // see the codex for wp_signon()
      $user = wp_signon();
    
        if(is_wp_error($user)){
        	wp_die('Échec de la connexion. Mot de passe ou nom d&#8216;utilisateur incorrect?');
        }
     
        $user_info = get_userdata($user->ID);
        $user_roles = $user_info->roles;
        if(implode($user_roles) != 'administrator'){
            if(get_user_meta($user->ID, 'account_status', true) != 'approved'){
                wp_logout();
                wp_die('Votre compte n&#8216;est pas encore confirmé, veuillez regarder vos emails.');
            }
        }
    
      // redirect back to the requested page if login was successful    
      header('Location: ' . $_SERVER['REQUEST_URI']);
      exit;
});

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }

    return $randomString;
}

add_action('init', function(){

  // not the login request?
  if(!isset($_POST['action']) || $_POST['action'] !== 'my_register_action')
      return;
    
    $errors = new WP_Error();
    
    $username = sanitize_user($_POST['username']);
    $email = sanitize_email($_POST['email']);
    $password = sanitize_text_field($_POST['password']);
    $verifypassword = sanitize_text_field($_POST['verifypassword']);
    
    $firstname = sanitize_text_field($_POST['firstname']);
    $lastname = sanitize_text_field($_POST['lastname']);
    
    $company = sanitize_text_field($_POST['company']);
    $adresse = sanitize_text_field($_POST['adresse']);
    $city = sanitize_text_field($_POST['city']);
    $province = sanitize_text_field($_POST['province']);
    $country = sanitize_text_field($_POST['country']);
    $postalcode = $_POST['postalcode'];
    $phone = $_POST['phone'];
    $poste = $_POST['poste'];
    
    $status = $_POST['status'];
    
    // Basic validation
    if (username_exists($username) || email_exists($email)) {
        $errors->add('user_exists', 'Ce nom d&#8216;utilisateur ou cette adresse e-mail est déjà enregistré(e).');
    }
    if (empty($username) || empty($email) || empty($password) || empty($status)) {
        $errors->add('field_empty', 'Veuillez remplir tous les champs obligatoires.');
    }    
    if ($password != $verifypassword) {
        $errors->add('not_same_password', 'Les mots de passe que vous avez renter ne sont pas identique.');
    }
    
     // If no errors, register 
    if (empty($errors->errors)) {
        if ($status == 'employeur') {
            $userdata = array(
                'user_login'  => $username,
                'first_name'    => $firstname,
                'last_name'    => $lastname,
                'user_email'   => $email,
                'user_pass'   => $password,
                'role' => 'employeur'
            );
        }
    
        if ($status == 'employer') {
            $userdata = array(
                'user_login'  => $username,
                'first_name'    => $firstname,
                'last_name'    => $lastname,
                'user_email'   => $email,
                'user_pass'   => $password,
                'role' => 'employer'
            );
        }
    }
    
    $user_id = wp_insert_user( $userdata );
    
    if (isset($user_id)){
        $account_status = get_user_meta($user_id, 'account_status', true);
                       
	    $unique_key = generateRandomString();
	    
	    update_user_meta($user_id, 'unique_key', $unique_key);
	    if($account_status != 'approved') {
	    	update_user_meta($user_id, 'account_status', 'pending');
	    }
	    
	    update_user_meta($user_id, 'company_key', $company);
	    update_user_meta($user_id, 'adresse_key', $adresse);
	    update_user_meta($user_id, 'city_key', $city);
	    update_user_meta($user_id, 'province_key', $province);
	    update_user_meta($user_id, 'country_key', $country);
	    update_user_meta($user_id, 'postal_code_key', $postalcode);
	    update_user_meta($user_id, 'phone_key', $phone);
	    update_user_meta($user_id, 'poste_key', $poste);
        
        if($account_status == 'pending' || $account_status == ''){
    
            $subject = 'Votre compte ' . $username . ' vous devez confirmer votre compte.';
            $message = '
                <p>Bonjour ' . $firstname . ' ' . $lastname . '</p>
                <p>Votre compte est maintenant crée il ne reste juste qu&#8216;à confirmer le tout.</p>
                <p>Veuiller cliquer ici: <a href="https://monemploi.net/login/?key=' . $unique_key . '">Lien d&#8216;activation</a></p>
            ';
            $headers = array('Content-Type: text/html; charset=UTF-8');
    
            // Send the email
            wp_mail( $email, $subject, $message, $headers );
        }
    }
    
    if (!is_wp_error($user_id)) {
        header("Location: " . $_SERVER['REQUEST_URI'] . "?new_user=true");
    } else {
        foreach ($errors->get_error_messages() as $error) {
        	echo $error;
        }
    }

});

function enqueue_frontend_media_scripts() {
    if (is_user_logged_in()) { // Optional: restrict to logged-in users
        wp_enqueue_media();
    }
}
add_action('wp_enqueue_scripts', 'enqueue_frontend_media_scripts');

add_action('init', function(){

  // not the login request?
  if(!isset($_POST['action']) || $_POST['action'] !== 'update_user_info_action')
      return;
    
    $errors = new WP_Error();
    
    $user_firstname = $_POST['user_firstname'];
    $user_lastname = $_POST['user_lastname'];
    $user_email = $_POST['user_email'];
    $company_key = $_POST['company_key'];
    $adresse_key = $_POST['adresse_key'];
    $city_key = $_POST['city_key'];
    $province_key = $_POST['province_key'];
    $country_key = $_POST['country_key'];
    $postal_code_key = $_POST['postal_code_key'];
    $phone_key = $_POST['phone_key'];
    $poste_key = $_POST['poste_key'];
    
    $user_id = get_current_user_id();
    
    $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . strtok($_SERVER['REQUEST_URI'], '?');
    
    $already_email = 0;
    $all_users = get_users();
    foreach ($all_users as $user) {
        if($user_email == $user->user_email && $user_id != $user->ID) {
            $errors->add('already_email', 'Le email existe deja sur un autre compte.');
            $alreadyemail = 1;
        }
    }
    
    $unique_key = generateRandomString();
    $current_user = wp_get_current_user();
    $user_email_already = $current_user->user_email;
    if($already_email === 0 && $user_email_already != $user_email) {
        update_user_meta($user_id, 'unique_email_key', $unique_key);
        update_user_meta($user_id, 'new_email_key', $user_email);
        $subject = 'Votre compte ' . $username . ' vous devez confirmer votre compte.';
            $message = '
                <p>Bonjour ' . $firstname . ' ' . $lastname . '</p>
                <p>Votre compte a une nouvelle adresse couriel. il ne reste juste qu&#8216;à confirmer le tout.</p>
                <p>Veuiller cliquer ici: <a href="https://monemploi.net/profile/?new_email=' . $unique_key . '&refresh=0">Lien d&#8216;activation</a></p>
            ';
            $headers = array('Content-Type: text/html; charset=UTF-8');
    
            // Send the email
            wp_mail( $user_email, $subject, $message, $headers );
            
            header("Location: " . $current_url . "?edit_update_email=true");
       
    }
	if($user_email_already == $user_email) {
	    	$user_data = array(
	    	    'ID'         => $user_id,
	    	    'first_name' => $user_firstname,
	    	    'last_name'  => $user_lastname
	    	);
		
		$updated_user_id = wp_update_user( $user_data );
		
		if ( is_wp_error( $updated_user_id ) ) {
		        foreach ($errors->get_error_messages() as $error) {
		        	echo $error;
		        }
	   	} else {
		    	update_user_meta($updated_user_id, 'company_key', $company_key);
		    	update_user_meta($updated_user_id, 'adresse_key', $adresse_key);
		    	update_user_meta($updated_user_id, 'city_key', $city_key);
		    	update_user_meta($updated_user_id, 'province_key', $province_key);
		    	update_user_meta($updated_user_id, 'country_key', $country_key);
		    	update_user_meta($updated_user_id, 'postal_code_key', $postal_code_key);
		    	update_user_meta($updated_user_id, 'phone_key', $phone_key);
		    	update_user_meta($updated_user_id, 'poste_key', $poste_key);
		    	
		        header("Location: " . $current_url . "?edit_update=true");
	   
	    }
    
    }
    
});


add_action('init', function(){

  // not the login request?
  if(!isset($_POST['action']) || $_POST['action'] !== 'delete_document_attachment')
      return;
      
      	$object_id = $_POST['attachmentid'];
	
	$deleted_attachment = wp_delete_attachment( $object_id, true);
	
	if ( $deleted_attachment ) {
		header("Location: " . $_SERVER['REQUEST_URI'] . "?delete_attachment=". $object_id ."");
	}
	
});

add_action('init', function(){

  // not the login request?
  if(!isset($_POST['action']) || $_POST['action'] !== 'ns_submit')
      return;

	$emploi_job_title = $_POST['job_title'];
	$ticket_details = $_POST['new_job_details'];
	$code_postal = $_POST['code_postal'];
	$education = $_POST['education'];
	$annees_dexperience = $_POST['annees_dexperience'];
	$salaire = $_POST['salaire'];
	$city = $_POST['city'];
	$datepickerstartjobscheduled = $_POST['datepickerstartjobscheduled'];
	$datepickerendjobscheduled = $_POST['datepickerendjobscheduled'];
	$timestartjobscheduled = $_POST['timestartjobscheduled'];
	$timeendjobscheduled = $_POST['timeendjobscheduled'];
        $add_heures = $_POST['add_heures'];
        $type_demploi = $_POST['type_demploi'];
        $type_dhoraire = $_POST['type_dhoraire'];
        $disponibilites1 = $_POST['disponibilites1'];
        $disponibilites2 = $_POST['disponibilites2'];
        $duree_emploi = $_POST['duree_emploi'];
        $permis_conduire = $_POST['permis_conduire'];
        $besoin_voiture = $_POST['besoin_voiture'];
        $activite_professionnelle = $_POST['activite_professionnelle'];
        $job_status =  $_POST['job_status'];
        $postid_update =  $_POST['postid'];
        
	if($job_status == 'new' && $postid_update == 0){
	
		if($datepickerstartjobscheduled != null && $timestartjobscheduled != null) {
			$schedule_timestamp = strtotime($datepickerstartjobscheduled . 'T' . $timestartjobscheduled);	
			$strtotime_now = current_time('timestamp');
			$publish_date = date('Y-m-d H:i:s', $schedule_timestamp);
			$publish_date_gmt = get_gmt_from_date($publish_date);
			
			if($strtotime_now < $schedule_timestamp) {
			
				$new_post = array(
					'post_title' => $emploi_job_title,
					'post_content' => $ticket_details,
					'post_status' => 'future',
				    	'post_date'     => $publish_date,
				    	'post_date_gmt' => $publish_date_gmt,
					'post_author' => get_current_user_id(),
					'post_type' => 'emploi'
				);
			
			} else if($strtotime_now > $schedule_timestamp) {
			
				$new_post = array(
					'post_title' => $emploi_job_title,
					'post_content' => $ticket_details,
					'post_status' => 'publish',
					'post_date_gmt' => date('Y-m-d H:i:s'),
					'post_author' => get_current_user_id(),
					'post_type' => 'emploi'
				);
			}
			
		} else {
			
			$new_post = array(
				'post_title' => $emploi_job_title,
				'post_content' => $ticket_details,
				'post_status' => 'publish',
				'post_date_gmt' => date('Y-m-d H:i:s'),
				'post_author' => get_current_user_id(),
				'post_type' => 'emploi'
			);
			
		}
		$postid = wp_insert_post($new_post);
	    
	    	add_post_meta( $postid, 'my_code_postal_key', $code_postal );
	    	add_post_meta( $postid, 'my_education_key', $education );
	    	add_post_meta( $postid, 'my_annees_dexperience_key', $annees_dexperience );
	    	add_post_meta( $postid, 'my_salaire_key', $salaire );
	    	add_post_meta( $postid, 'my_city_key', $city );
	    	if($datepickerstartjobscheduled == '' && $timestartjobscheduled == ''){
	    		add_post_meta( $postid, 'my_start_job_scheduled_key', '');
	    	} else {
	    		add_post_meta( $postid, 'my_start_job_scheduled_key', strtotime($datepickerstartjobscheduled . 'T' . $timestartjobscheduled));
	    	}
	    	if($datepickerendjobscheduled == '' && $timeendjobscheduled == ''){
			add_post_meta( $postid, 'my_end_job_scheduled_key', '');
	    	} else {
	    		add_post_meta( $postid, 'my_end_job_scheduled_key', strtotime($datepickerendjobscheduled . 'T' . $timeendjobscheduled));
	    	}
	    	add_post_meta( $postid, 'my_start_job_date_scheduled_key', $datepickerstartjobscheduled );
	    	add_post_meta( $postid, 'my_start_job_time_scheduled_key', $timestartjobscheduled );
	    	add_post_meta( $postid, 'my_end_job_date_scheduled_key', $datepickerendjobscheduled );
	    	add_post_meta( $postid, 'my_end_job_time_scheduled_key', $timeendjobscheduled );   	
	    	add_post_meta( $postid, 'my_add_heures_key', $add_heures );
	    	add_post_meta( $postid, 'my_type_demploi_key', $type_demploi );
		add_post_meta( $postid, 'my_type_dhoraire_key', $type_dhoraire );
		add_post_meta( $postid, 'my_disponibilites1_key', $disponibilites1 );
		add_post_meta( $postid, 'my_disponibilites2_key', $disponibilites2 );
		add_post_meta( $postid, 'my_duree_emploi_key', $duree_emploi );
		add_post_meta( $postid, 'my_permis_conduire_key', $permis_conduire );
		add_post_meta( $postid, 'my_besoin_voiture_key', $besoin_voiture );
		add_post_meta( $postid, 'my_activite_professionnelle_key', $activite_professionnelle );
	}
	
	if($job_status == 'update' && $postid_update != 0){
		
		if($datepickerstartjobscheduled != null && $timestartjobscheduled != null) {
			$schedule_timestamp = strtotime($datepickerstartjobscheduled . 'T' . $timestartjobscheduled);	
			$strtotime_now = current_time('timestamp');
			$publish_date = date('Y-m-d H:i:s', $schedule_timestamp);
			$publish_date_gmt = get_gmt_from_date($publish_date);
			
			if($strtotime_now < $schedule_timestamp) {
			
				$arg = array(
					'ID'           => $postid_update,
					'post_title' => $emploi_job_title,
					'post_content' => $ticket_details,
					'post_status' => 'future',
				    	'post_date'     => $publish_date,
				    	'post_date_gmt' => $publish_date_gmt,
					'post_author' => get_current_user_id(),
					'post_type' => 'emploi'
				);
			
			} else if($strtotime_now >= $schedule_timestamp) {
			
				$arg = array(
					'ID'           => $postid_update,
					'post_title' => $emploi_job_title,
					'post_content' => $ticket_details,
					'post_status' => 'publish',
					'post_date_gmt' => date('Y-m-d H:i:s'),
					'post_author' => get_current_user_id(),
					'post_type' => 'emploi'
				);
			}
			
		} else {
			
			$arg = array(
				'ID'           => $postid_update,
				'post_title' => $emploi_job_title,
				'post_content' => $ticket_details,
				'post_status' => 'publish',
				'post_date_gmt' => date('Y-m-d H:i:s'),
				'post_author' => get_current_user_id(),
				'post_type' => 'emploi'
			);
			
		}
		wp_update_post( $arg );
	    
	    	update_post_meta( $postid_update, 'my_code_postal_key', $code_postal );
	    	update_post_meta( $postid_update, 'my_education_key', $education );
	    	update_post_meta( $postid_update, 'my_annees_dexperience_key', $annees_dexperience );
	    	update_post_meta( $postid_update, 'my_salaire_key', $salaire );
	    	update_post_meta( $postid_update, 'my_city_key', $city );
	    	if($datepickerstartjobscheduled == '' && $timestartjobscheduled == ''){
	    		update_post_meta( $postid_update, 'my_start_job_scheduled_key', '');
	    	} else {
	    		update_post_meta( $postid_update, 'my_start_job_scheduled_key', strtotime($datepickerstartjobscheduled . 'T' . $timestartjobscheduled));
	    	}
	    	if($datepickerendjobscheduled == '' && $timeendjobscheduled == ''){
			update_post_meta( $postid_update, 'my_end_job_scheduled_key', '');
	    	} else {
	    		update_post_meta( $postid_update, 'my_end_job_scheduled_key', strtotime($datepickerendjobscheduled . 'T' . $timeendjobscheduled));
	    	}
	    	update_post_meta( $postid_update, 'my_start_job_date_scheduled_key', $datepickerstartjobscheduled );
	    	update_post_meta( $postid_update, 'my_start_job_time_scheduled_key', $timestartjobscheduled );
	    	update_post_meta( $postid_update, 'my_end_job_date_scheduled_key', $datepickerendjobscheduled );
	    	update_post_meta( $postid_update, 'my_end_job_time_scheduled_key', $timeendjobscheduled );   	
	    	update_post_meta( $postid_update, 'my_add_heures_key', $add_heures );
	    	update_post_meta( $postid_update, 'my_type_demploi_key', $type_demploi );
		update_post_meta( $postid_update, 'my_type_dhoraire_key', $type_dhoraire );
		update_post_meta( $postid_update, 'my_disponibilites1_key', $disponibilites1 );
		update_post_meta( $postid_update, 'my_disponibilites2_key', $disponibilites2 );
		update_post_meta( $postid_update, 'my_duree_emploi_key', $duree_emploi );
		update_post_meta( $postid_update, 'my_permis_conduire_key', $permis_conduire );
		update_post_meta( $postid_update, 'my_besoin_voiture_key', $besoin_voiture );
		update_post_meta( $postid_update, 'my_activite_professionnelle_key', $activite_professionnelle );
	
	}
	
	if($job_status === 'new' && $postid_update === 0){
	
		$url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$clean_url = explode('?', $url)[0];			
		header("Location: " . $clean_url . "?add_job=". $postid ."");
	
	}	
	
	if($job_status === 'update' && $postid_update !== 0){
	
		$url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$clean_url = explode('?', $url)[0];
		header("Location: " . $clean_url . "?update_job=". $postid_update ."");
	
	}
	
});

add_action('init', function(){

  // not the login request?
  if(!isset($_POST['action']) || $_POST['action'] !== 'submit_response')
      return;
      
        $errors = 0;

	$postid = $_POST['postid'];
	$comment = $_POST['ns_response_msg'];
	$current_user_id = get_current_user_id();
	$current_user = get_user_by( 'id', $current_user_id );
	
	if(strlen($comment) < 3){
		$errors = 'Le commentaire est doit etre 3 caractère ou plus.';
	}

	if($errors == 0){
		$commentdata = array(
			'comment_post_ID'       => absint($postid),
			'comment_author'        => wp_strip_all_tags($current_user->display_name),
			'comment_author_email'  => sanitize_email($current_user->user_email),
			'comment_author_url'    => esc_url($current_user->user_url),
			'comment_content'       => $comment,
			'comment_type'          => 'candidacy_response',
			'comment_parent'        => 0,
			'user_id'               => absint($current_user->ID),
			'comment_approved'      => 1
		);

		$comment_id = wp_new_comment($commentdata);
	
		$current_user = wp_get_current_user();
		$user_meta = get_userdata($current_user->ID);
		$user_role = $user_meta->roles[0];
		
		if($user_role == 'employeur'){
			$authorid = get_post_field( 'post_author', $postid );
			$author_email = get_the_author_meta( 'user_email', $authorid );
			$to = $author_email;
		}
		if($user_role == 'employer'){
			$authorid = get_post_meta( $postid, 'my_author_id_key', true );
			$author_email = get_the_author_meta( 'user_email', $authorid );
			$to = $author_email;
		}
		
		$subject = sprintf ( __( 'Nouvelle résponse #%s — %s — %s', 'monemploi' ), $postid, get_the_title($postid), get_bloginfo( 'name', 'display' ) );
		$headers = array('Content-Type: text/html; charset=UTF-8');
		
		$message[] .= '<p>';
		$message[] .= 'Nouvelle résponse #' . $postid;
		$message[] .= '</p>';
		$message[] .= '<a href="' . get_permalink( $postid ) . '">Voire la résponse</a>';
		
		wp_mail($to, $subject, implode($message), $headers);
	
	}
	
	if ($errors === 0) {
        	header("Location: " . $_SERVER['REQUEST_URI'] . "?add_comment=". $comment_id ."");
    	} else {
		 wp_die($errors);
	}
	
});

add_action('init', function(){

	// not the login request?
	if(!isset($_POST['action']) || $_POST['action'] !== 'update_user_password_action')
		return;

	$old_password = sanitize_text_field($_POST['old_password']);
	$new_password = sanitize_text_field($_POST['new_password']);
	$retype_new_password = sanitize_text_field($_POST['retype_new_password']);
	$user = wp_get_current_user();
		
	$errors = 0;
	$good_password = 0;
	
	$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . strtok($_SERVER['REQUEST_URI'], '?');
	
	if($new_password === $retype_new_password){
		//
	} else {
		$errors = 'Le mot de passe n&#8216;est pas pareille pour les deux champs.';
	} 
	
	if ( $user && wp_check_password( $old_password, $user->data->user_pass, $user->ID ) ) {
		$good_password = 1;
	} else {
		$errors = 'Le mot de passe actuel n&#8216;est pas valide.';
	}
	
	$userdata = array(
	    'ID'        => $user->ID, // The user's ID
	    'user_pass' => $retype_new_password // Plaintext password; WordPress hashes it automatically
	);
	
	$user_id = wp_update_user( $userdata );
	
	if ( $user_id && $errors === 0 && $good_password === 1) {
		header("Location: " . $current_url . "?password_change_successfully=true");
	} else {
		wp_die($errors);
        }
      
});

add_action('init', function(){

	// not the login request?
	if(!isset($_POST['action']) || $_POST['action'] !== 'delete_account_action')
		return;
		
	$errors = 0;
	$user = wp_get_current_user();
	$password = sanitize_text_field($_POST['password']);
	require_once( ABSPATH . 'wp-admin/includes/user.php' );
	
	if ( $user && wp_check_password( $password, $user->data->user_pass, $user->ID ) ) {
		// everything is good.
	} else {
		$errors = 'Le mot de passe actuel n&#8216;est pas valide.';
	}

	if ( wp_delete_user( $user->ID, null ) && $errors == 0 ) {
		$all_candidacys = get_posts(array(
		    'post_type' => 'candidacy',
		    'numberposts' => -1,
		    'author'	=> $user->ID,
		    'post_status' => 'any',
		));
		foreach ($all_candidacys as $post) {
		    wp_delete_post($post->ID, false);
		}	
		$all_emplois = get_posts(array(
		    'post_type' => 'emploi',
		    'numberposts' => -1,
		    'author'	=> $user->ID,
		    'post_status' => 'any',
		));
		foreach ($all_emplois as $post) {
		    wp_delete_post($post->ID, false);
		}
		wp_logout();
		header("Location: " . trailingslashit( get_home_url() ) . "?delete_account=true");
	} else {
		wp_die($errors);
	}

});

add_action('init', function(){

	// not the login request?
	if(!isset($_POST['action']) || $_POST['action'] !== 'my_frogot_password_action')
		return;

    $login = sanitize_text_field($_POST['login']);
    
    $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . strtok($_SERVER['REQUEST_URI'], '?');

    if($login == ''){
        header("Location: " . $current_url . "?frogot_password_empty=true");
        return;
    } else {
        if(filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $user = get_user_by( 'email', $login );
        } else {
            $user = get_user_by( 'login', $login );
        }
    }
    
    $unique_string = generateRandomString();
    
    update_user_meta($user->ID, 'unique_string', $unique_string);
    
            $subject = 'Votre mot de passe ' . $user->user_login . ' vous devez confirmer votre nouveaux mot de passe.';
            $message = '
                <p>Bonjour ' . $user->first_name . ' ' . $user->last_name . '</p>
                <p>Vous avez demander un renisilisation du mot de passe de votre compte.</p>
                <p>Veuiller cliquer ici: <a href="'. $current_url .'?frogot_password_key='. $unique_string .'">Lien de récuperation</a></p>
            ';
            $headers = array('Content-Type: text/html; charset=UTF-8');
    
            // Send the email
            wp_mail( $user->user_email, $subject, $message, $headers );

            header("Location: " . $current_url . "?frogot_password_send=true");
            
});

add_action('init', function(){

	// not the login request?
	if(!isset($_POST['action']) || $_POST['action'] !== 'my_frogot_password_new_action')
		return;
    
    $samepassword = 0;
    $password = sanitize_text_field($_POST['password']);
    $retype_password = sanitize_text_field($_POST['retype_password']);
    $userid = $_POST['userid'];

    $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . strtok($_SERVER['REQUEST_URI'], '?');

    if($password === $retype_password){
        $samepassword = 1;
    } else {
        wp_die('Les deux mot de passe ne sont pas identique');
    }
    
    if($samepassword === 1){
        $userdata = [
            'ID'        => $userid,
            'user_pass' => $retype_password
        ];
        
        $result = wp_update_user( $userdata );
        
        if ( ! is_wp_error( $result ) ) {
            update_user_meta($userid, 'unique_string', null);
            header("Location: " . $current_url . "?frogot_password_new=true");
        }
    }
});

?>