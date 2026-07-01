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

function search_distinct() {
    return "DISTINCT";
}
add_filter('posts_distinct', 'search_distinct');


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
  
        $username = sanitize_user($_POST['log']);
        $password = sanitize_text_field($_POST['pwd']);
  
	$creds = array(
		'user_login'    => $username,
		'user_password' => $password,
		'remember'      => false
	);

	$user_ = wp_signon( $creds, true );
                
	if(filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $user = get_user_by( 'email', $username );
        } else {
            $user = get_user_by( 'login', $username );
        }
        
        if(!$user->ID) {
        	wp_die('Le nom d&#8216;utulisateur ou le couriel n&#8216;a pas été trouvé.');
        	return 0;
        }
        
       	$date1 = current_time( 'timestamp' );
	$login_attemp = get_user_meta($user->ID, 'login_attemp', true);
	$login_lock = get_user_meta($user->ID, 'login_lock', true);
	$lock_time = get_user_meta($user->ID, 'login_lock_time', true);
      
        if(is_wp_error($user_)){
            $login_attemp_count = intval($login_attemp);
            $login_attemp_count += 1;
            update_user_meta($user->ID, 'login_attemp', $login_attemp_count);
            if($login_attemp_count == 5){
                $new_date = strtotime(date("Y-m-d H:i:s", $date1) . "+12hours");
                update_user_meta($user->ID, 'login_lock', 1);
                update_user_meta($user->ID, 'login_lock_time', $new_date);
            }
            if($date1 >= $lock_time && $lock_time != ''){
                update_user_meta($user->ID, 'login_attemp', 0);
                update_user_meta($user->ID, 'login_lock', 0);
                update_user_meta($user->ID, 'login_lock_time', '');
            }
            $login_lock = get_user_meta($user->ID, 'login_lock', true);
            if($login_lock == 1){
            	$unlock = $lock_time - $date1;
 		$time_unlock = gmdate("H:i:s", $unlock);
                wp_die($time_unlock . ' - Votre compte est barré pour 12 heures car vous avez echoué vos 5 tentative de connexion.');
            }
            wp_die('Nombre de tentative:' . $login_attemp_count . '/5 - Échec de la connexion. Mot de passe incorrect?');
        } else {
            if($date1 >= $lock_time && $lock_time != ''){
                update_user_meta($user->ID, 'login_attemp', 0);
                update_user_meta($user->ID, 'login_lock', 0);
                update_user_meta($user->ID, 'login_lock_time', '');
            }
            $login_lock = get_user_meta($user->ID, 'login_lock', true);
	     if($login_lock == 1){
            	$unlock = $lock_time - $date1;
 		$time_unlock = gmdate("H:i:s", $unlock);
                wp_die($time_unlock . ' - Votre compte est barré pour 12 heures car vous avez echoué vos 5 tentative de connexion.');
            }
        }
     
        $user_info = get_userdata($user->ID);
        $user_roles = $user_info->roles;
        if(implode($user_roles) != 'administrator'){
            if(get_user_meta($user->ID, 'account_status', true) != 'approved' ){
                wp_logout();
                wp_die('Votre compte n&#8216;est pas encore confirmé, veuillez regarder vos emails.');
            }
        }
        
        $login_info = get_user_meta($user->ID, 'login_info', true);
        
        $current_time_strtotime = current_time( 'timestamp' );
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    		$ip = $_SERVER['HTTP_CLIENT_IP'];
    	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    	} elseif (!empty($_SERVER["HTTP_CF_CONNECTING_IP"])) {
    		$ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
    	} else {
    		$ip = $_SERVER['REMOTE_ADDR'];
    	}
    	
    	$user_agent = $_SERVER['HTTP_USER_AGENT'];
        
        $new_login_info = array($current_time_strtotime, $ip, $user_agent);
        
        if($login_info == ''){
            update_user_meta($user->ID, 'login_info', [$new_login_info]);
        } else {
        
            array_unshift($login_info, $new_login_info);
        
            update_user_meta($user->ID, 'login_info', $login_info);
        
        }
         
       header('Refresh:0;' . $_SERVER['REQUEST_URI']);
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
    
     // Storing google recaptcha response
    // in $recaptcha variable
    $recaptcha = $_POST['g-recaptcha-response'];

    // Put secret key here, which we get
    // from google console
    $secret_key = '6LdqLRwtAAAAAMHJVP7_ywtkLW8MCU6OaK4iH5kw';

    // Hitting request to the URL, Google will
    // respond with success or error scenario
    $url = 'https://www.google.com/recaptcha/api/siteverify?secret='
          . $secret_key . '&response=' . $recaptcha;

    // Making request to verify captcha
    $response = file_get_contents($url);

    // Response return by google is in
    // JSON format, so we have to parse
    // that json
    $response = json_decode($response);

    // Checking, if response is true or not
    if ($response->success == true) {
	//
    } else {
        $errors->add('error_reCAPTACHA', 'Error in Google reCAPTACHA');
    }
    
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
    
    if (!is_wp_error($user_id)) {
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
    
    if($user_email == ''){
        wp_die('Le email est vide.');
    }
    
    $already_email = 0;
    $all_users = get_users();
    foreach ($all_users as $user) {
        if($user_email == $user->user_email && $user_id != $user->ID) {
            wp_die('Le email existe deja sur un autre compte.');
            $alreadyemail = 1;
        }
    }
    
    $unique_key = generateRandomString();
    $current_user = wp_get_current_user();
    $user_email_already = $current_user->user_email;
    $edit_update_email = false;
    if($already_email === 0 && $user_email_already != $user_email) {
        update_user_meta($user_id, 'unique_email_key', $unique_key);
        update_user_meta($user_id, 'new_email_key', $user_email);
        $subject = 'Votre compte ' . $username . ' vous devez confirmer votre couriel.';
            $message = '
                <p>Bonjour ' . $firstname . ' ' . $lastname . '</p>
                <p>Votre compte a une nouvelle adresse couriel. il ne reste juste qu&#8216;à confirmer le tout.</p>
                <p>Veuiller cliquer ici: <a href="https://monemploi.net/profile/?new_email=' . $unique_key . '&refresh=0">Lien d&#8216;activation</a></p>
            ';
            $headers = array('Content-Type: text/html; charset=UTF-8');
    
            // Send the email
            wp_mail( $user_email, $subject, $message, $headers );
            
            $edit_update_email = true;
                   
    }
    
    	$userdata = wp_get_current_user();
        
		require_once(ABSPATH . 'wp-admin/includes/image.php');
		require_once(ABSPATH . 'wp-admin/includes/file.php');
		require_once(ABSPATH . 'wp-admin/includes/media.php');
		
		$attachment_id = media_handle_upload('cover_photo_file', 0);
		
		if (is_wp_error($attachment_id)) {
			//
		} else {
			$get_cover = get_user_meta($userdata->ID, 'cover_photo', true);
			wp_delete_attachment($get_cover);
			update_user_meta($userdata->ID, 'cover_photo', $attachment_id);
		}
	
	    require_once(ABSPATH . 'wp-admin/includes/image.php');
	    require_once(ABSPATH . 'wp-admin/includes/file.php');
	    require_once(ABSPATH . 'wp-admin/includes/media.php');
	
	    $attachment_id = media_handle_upload('user_avatar_file', 0);
	
	    if (is_wp_error($attachment_id)) {
		//
	    } else {
		$get_avatar = get_user_meta($userdata->ID, 'user_avatar', true);
		wp_delete_attachment($get_avatar);
	       update_user_meta($userdata->ID, 'user_avatar', $attachment_id);
	    }
	
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
	    	
	    	if($edit_update_email == false){
	        	header("Location: " . $current_url . "?edit_update=true");
	        } else {
	        	header("Location: " . $current_url . "?edit_update=true&edit_update_email=true");
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
  if(!isset($_POST['action']) || $_POST['action'] !== 'delete_image_attachment')
      return;
      
      	$object_id = $_POST['attachmentid'];
	
	$deleted_attachment = wp_delete_attachment( $object_id, true);
	
	if ( $deleted_attachment ) {
		$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . strtok($_SERVER['REQUEST_URI'], '?');
		$pathInfo = parse_url($_SERVER['REQUEST_URI']);
		$queryString = $pathInfo['query'];
		$array = explode("&", $queryString);
		$array[1] = '&delete_attachment='. $object_id;
		header("Refresh:0;url=" . $current_url . "?" . implode($array) . "");
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
        $email_employeur =  $_POST['email_employeur'];
        $lien_employeur =  $_POST['lien_employeur'];
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
		$get_current_user_id = get_current_user_id();
		$company_key = get_user_meta($get_current_user_id, 'company_key', true);
		add_post_meta( $postid, 'company_key', $company_key );
		add_post_meta( $postid, 'my_email_employeur_key', $email_employeur );
		add_post_meta( $postid, 'my_lien_employeur_key', $lien_employeur );
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
		$get_current_user_id = get_current_user_id();
		$company_key = get_user_meta($get_current_user_id, 'company_key', true);
		update_post_meta( $postid_update, 'company_key', $company_key );
		update_post_meta( $postid_update, 'my_email_employeur_key', $email_employeur );
		update_post_meta( $postid_update, 'my_lien_employeur_key', $lien_employeur );
	
	}
	
	if($job_status === 'new' && $postid_update === 0){
	
		$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . strtok($_SERVER['REQUEST_URI'], '?');	
		header("Location: " . $current_url . "?add_job=". $postid ."");
	
	}	
	
	if($job_status === 'update' && $postid_update !== 0){
	
		$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . strtok($_SERVER['REQUEST_URI'], '?');
		header("Location: " . $current_url . "?update_job=". $postid_update ."");
	
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
	$password = $_POST['password'];
	
	if (!wp_check_password( $password, $user->data->user_pass, $user->ID ) ) {
		$errors = 'not_the_good_password';
	}
		
	if($password === ''){
		$errors = 'password_empty';
	}

	if ( $errors === 0 ) {
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
		$args = array(
		    'post_type'      => 'attachment',
		    'post_status'    => 'inherit', // Attachments usually have 'inherit' status
		    'author'         => $user->ID,
		    'posts_per_page' => -1,          // Get all attachments
		);
		$attachments = get_posts($args);
		if ($attachments) {
			foreach ($attachments as $attachment) {
				wp_delete_attachment($attachment->ID, false);
			}
		}
		wp_delete_user( $user->ID, null );
		wp_logout();
		header("Location: " . trailingslashit( get_home_url() ) . "?delete_account=true");
	} else {
		$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . strtok($_SERVER['REQUEST_URI'], '?');
		if($errors === 'not_the_good_password'){
			header("Location: " . $current_url . "?delete_account=true&delete_account_wrong_password=true");
		}
		
		if($errors === 'password_empty'){
			header("Location: " . $current_url . "?delete_account=true&delete_account_password_empty=true");
		}
	}

});

add_action('init', function(){

	// not the login request?
	if(!isset($_POST['action']) || $_POST['action'] !== 'my_forget_password_action')
		return;

    $login = sanitize_text_field($_POST['login']);
    
    $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . strtok($_SERVER['REQUEST_URI'], '?');

    if($login == ''){
        header("Location: " . $current_url . "?forget_password_empty=true");
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
        <p>Vous avez demandé un réinitialisation du mot de passe de votre compte.</p>
        <p>Veuillez cliquer ici: <a href="'. $current_url .'?forget_password_key='. $unique_string .'">Lien de récuperation</a></p>
    ';
    $headers = array('Content-Type: text/html; charset=UTF-8');

    // Send the email
    wp_mail( $user->user_email, $subject, $message, $headers );

    header("Location: " . $current_url . "?forget_password_send=true");
            
});

add_action('init', function(){

	// not the login request?
	if(!isset($_POST['action']) || $_POST['action'] !== 'my_forget_password_new_action')
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
            header("Location: " . $current_url . "?forget_password_new=true");
        }
    }
});

add_action( 'init', 'employeur_user_role' );

function employeur_user_role() {
    add_role(
        'employeur',
        __( 'Employeur' ),
    );
}

add_action( 'init', 'employer_user_role' );

function employer_user_role() {
    add_role(
        'employer',
        __( 'Employer' ),
    );
}


add_action( 'init', 'redirect_to_custom_login' );
function redirect_to_custom_login() {
    global $pagenow;
    // Check if on the login page and NOT trying to log out
    if ( 'wp-login.php' == $pagenow && !isset($_GET['action']) ) {
        wp_redirect( home_url( '/login/' ) );
        exit();
    }
}

function my_custom_login_url( $login_url, $redirect, $force_reauth ) {
    $custom_login_page = home_url( '/login/' ); // Your custom page slug
    return add_query_arg( 'redirect_to', $redirect, $custom_login_page );
}
add_filter( 'login_url', 'my_custom_login_url', 10, 3 );


function hide_admin_bar_settings() {
?>
    <style type="text/css">
        .show-admin-bar {
            display: none;
        }
    </style>
<?php
}

function disable_admin_bar()
{
    if(current_user_can('employeur') || current_user_can('employer')) {
        add_filter( 'show_admin_bar', '__return_false' );
        add_action( 'admin_print_scripts-profile.php', 'hide_admin_bar_settings' );
    }
}
add_action('init', 'disable_admin_bar', 9);

add_action('init', function(){

	// not the login request?
	if(!isset($_POST['action']) || $_POST['action'] !== 'avis_employer_send')
		return;

	$authorid = $_POST['userid'];
	$avismessage = $_POST['avis-message-employer'];
	$ponctualite = $_POST['ponctualite-employer'];
	$connaisance = $_POST['connaisance-employer'];
	$attitude = $_POST['attitude-employer'];
	$current_user_id = get_current_user_id();
	
	$get_user = get_user_by('id', $authorid);
	$user_nicename = $get_user->user_firstname . ' ' . $get_user->user_lastname;
	
	if($avismessage != '' && $ponctualite != '' && $connaisance != '' && $attitude != ''){
	
		$new_post = array(
			'post_title' => $user_nicename,
			'post_content' => $avismessage,
			'post_status' => 'publish',
			'post_date_gmt' => date('Y-m-d H:i:s'),
			'post_author' => get_current_user_id(),
			'post_type' => 'avis'
		);
		$post_id = wp_insert_post($new_post);
		
		add_user_meta( $post_id, 'ponctualite_key', $ponctualite);
		add_user_meta( $post_id, 'connaisance_key', $connaisance);
		add_user_meta( $post_id, 'attitude_key', $attitude);
		add_user_meta( $post_id, 'authorid_key', $authorid);
		add_user_meta( $post_id, 'role_key', 'employer');
		add_user_meta( $post_id, 'nicename_key', $user_nicename);
		
		$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . strtok($_SERVER['REQUEST_URI'], '?');
		
		$url = $_SERVER['REQUEST_URI'];

		// Extract the query component (e.g., "name=John&age=30...")
		$queryString = parse_url($url, PHP_URL_QUERY);
		
		// Parse the query string into a resulting array
		parse_str($queryString, $params);
		
		header("Location:0;" . $current_url . "?user=" . $params[user] . "&add_avis=". $post_id ."");
	
	}
	
});

add_action('init', function(){

	// not the login request?
	if(!isset($_POST['action']) || $_POST['action'] !== 'avis_employeur_send')
		return;
		
	$authorid = $_POST['userid'];
	$avismessage = $_POST['avis-message-employeur'];
	$horaire = $_POST['horaire-employeur'];
	$superieur = $_POST['superieur-employeur'];
	$paie = $_POST['paie-employeur'];
	$current_user_id = get_current_user_id();
	
	$get_user_by_id = get_user_by('id', $authorid);
	$user_nicename = $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname;
	
	if($avismessage != '' && $horaire != '' && $superieur != '' && $paie != ''){
	
		$new_post = array(
			'post_title' => $user_nicename,
			'post_content' => $avismessage,
			'post_status' => 'publish',
			'post_date_gmt' => date('Y-m-d H:i:s'),
			'post_author' => get_current_user_id(),
			'post_type' => 'avis'
		);
		$post_id = wp_insert_post($new_post);
		
		add_user_meta( $post_id, 'horaire_key', $horaire);
		add_user_meta( $post_id, 'superieur_key', $superieur);
		add_user_meta( $post_id, 'paie_key', $paie);
		add_user_meta( $post_id, 'authorid_key', $authorid);
		add_user_meta( $post_id, 'role_key', 'um_employeur');
		add_user_meta( $post_id, 'nicename_key', $user_nicename);
		
		$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . strtok($_SERVER['REQUEST_URI'], '?');
		
		$url = $_SERVER['REQUEST_URI'];

		// Extract the query component (e.g., "name=John&age=30...")
		$queryString = parse_url($url, PHP_URL_QUERY);
		
		// Parse the query string into a resulting array
		parse_str($queryString, $params);
		
		header("Location:0;" . $current_url . "?user=" . $params[user] . "&add_avis=". $post_id ."");
		
	}
	
});

add_action('init', function(){

	// not the login request?
	if(!isset($_POST['action']) || $_POST['action'] !== 'delete_avis_employer')
		return;

	$postid = $_POST['avisid'];
	
	wp_delete_post( $postid, false );
	
	$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . strtok($_SERVER['REQUEST_URI'], '?');
		
	$url = $_SERVER['REQUEST_URI'];

	// Extract the query component (e.g., "name=John&age=30...")
	$queryString = parse_url($url, PHP_URL_QUERY);
		
	// Parse the query string into a resulting array
	parse_str($queryString, $params);
		
	header("Location:0;" . $current_url . "?user=" . $params[user] . "&delete_avis=". $postid ."");
	
});

add_action('init', function(){

	// not the login request?
	if(!isset($_POST['action']) || $_POST['action'] !== 'delete_avis_employeur')
		return;

	$postid = $_POST['avisid'];
	
	wp_delete_post( $postid, false );
	
	$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . strtok($_SERVER['REQUEST_URI'], '?');
		
	$url = $_SERVER['REQUEST_URI'];

	// Extract the query component (e.g., "name=John&age=30...")
	$queryString = parse_url($url, PHP_URL_QUERY);
		
	// Parse the query string into a resulting array
	parse_str($queryString, $params);
		
	header("Location:0;" . $current_url . "?user=" . $params[user] . "&delete_avis=". $postid ."");
	
});

add_action('init', function(){

	// not the login request?
	if(!isset($_POST['action']) || $_POST['action'] !== 'delete_comment_candidacy')
		return;

	$commentid = $_POST['commentid'];
	
	wp_delete_comment( $commentid, false );
	
    	$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . strtok($_SERVER['REQUEST_URI'], '?');
	
	header("Location:0;" . $current_url . "?delete_comment=". $commentid ."");

});

add_action('init', function(){

	// not the login request?
	if(!isset($_POST['action']) || $_POST['action'] !== 'privacy_action')
		return;
		
	$userid = $_POST['userid'];
	$hide_search = $_POST['hide-search'];
	$hide_dashboard = $_POST['hide-dashboard'];
	$hide_adresse = $_POST['hide-adresse'];
	$hide_contact = $_POST['hide-contact'];
	$hide_widget = $_POST['hide-widget'];
	
	$disable_chat = $_POST['disable-chat'];
	$hide_online = $_POST['hide-online'];
	$hide_seen = $_POST['hide-seen'];
	
	$hide_adresse_job = $_POST['hide-adresse-job'];
	$hide_contact_job = $_POST['hide-contact-job'];
	$hide_adresse_candidacy = $_POST['hide-adresse-candidacy'];
	$hide_contact_candidacy = $_POST['hide-contact-candidacy'];
	$hide_jobs_search = $_POST['hide-jobs-search'];
	
	update_user_meta( $userid, 'hide_search_key', $hide_search);
	update_user_meta( $userid, 'hide_dashboard_key', $hide_dashboard);
	update_user_meta( $userid, 'hide_adresse_key', $hide_adresse);
	update_user_meta( $userid, 'hide_contact_key', $hide_contact);
	update_user_meta( $userid, 'hide_widget_key', $hide_widget);
	
	update_user_meta( $userid, 'disable_chat_key', $disable_chat);
	update_user_meta( $userid, 'hide_online_key', $hide_online);
	update_user_meta( $userid, 'hide_seen_key', $hide_seen);
	
	update_user_meta( $userid, 'hide_adresse_job_key', $hide_adresse_job);
	update_user_meta( $userid, 'hide_contact_job_key', $hide_contact_job);
	update_user_meta( $userid, 'hide_adresse_candidacy_key', $hide_adresse_candidacy);
	update_user_meta( $userid, 'hide_contact_candidacy_key', $hide_contact_candidacy);
	update_user_meta( $userid, 'hide_jobs_search_key', $hide_jobs_search);

	$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . strtok($_SERVER['REQUEST_URI'], '?');
	
	$url = $_SERVER['REQUEST_URI'];

	// Extract the query component (e.g., "name=John&age=30...")
	$queryString = parse_url($url, PHP_URL_QUERY);
	
	// Parse the query string into a resulting array
	parse_str($queryString, $params);
	
	header("Location:0;" . $current_url . "?privacy=" . $params[privacy] . "&privacy_update=true");
});

add_action('init', function(){

    if(is_user_logged_in()){
    	update_user_meta(get_current_user_id(), 'online_status_', true);
    	update_user_meta(get_current_user_id(), 'offline_time_', 0);
    }
	
});

function custom_logout() {
    $current_time = current_time('timestamp');
    $offline_time = get_user_meta(get_current_user_id(), 'offline_time_', true);
    if($offline_time == 0) {
    	update_user_meta(get_current_user_id(), 'online_status_', false);
    	update_user_meta(get_current_user_id(), 'offline_time_', $current_time);
    }
}
add_action('wp_logout', 'custom_logout');

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

add_action('init', function(){

	// not the login request?
	if(!isset($_POST['action']) || $_POST['action'] !== 'save_status_candidacy')
		return;

	$postid = $_POST['postid'];
	$status = $_POST['status_'];	
	$userid = $_POST['userid'];
	
	$get_candidacy_status = get_post_meta($postid, 'candidacy_status_', true);
	
	$author_id = get_post_field( 'post_author', $postid );
	$author_email = get_the_author_meta( 'user_email', $author_id );
	$to = $author_email;
	
	$subject = sprintf ( __( 'Nouveaux status #%s — %s — %s', 'monemploi' ), $postid, get_the_title($postid), get_bloginfo( 'name', 'display' ) );
	$headers = array('Content-Type: text/html; charset=UTF-8');
	
	$message[] .= '<p>';
	$message[] .= 'Nouveaux status #' . $postid;
	$message[] .= '</p>';
	$message[] .= '<p>';
	$message[] .= 'Acien status: ';
	
	if($get_candidacy_status == 0 || $get_candidacy_status == null) {
		$message[] .= 'En attente';
	} elseif($get_candidacy_status == 1 ){
		$message[] .= 'Refusé';
	} elseif($get_candidacy_status == 2){
		$message[] .= 'Entrevue accepté';
	} elseif($get_candidacy_status == 3){
		$message[] .= 'Embauché';
	}
	
	$message[] .= '</p>';
	$message[] .= '<p>';
	$message[] .= 'Nouveaux status: ';

	if($status == 0 || $status == null) {
             	$message[] .= 'En attente';
         } elseif($status == 1 ){
	 	$message[] .= 'Refusé';
	 } elseif($status == 2){
	 	$message[] .= 'Entrevue accepté';
	 } elseif($status == 3){
	 	$message[] .= 'Embauché';
	}
	
	$message[] .= '</p>';
	$message[] .= '<a href="' . get_permalink( $postid ) . '">Voire le status</a>';
	
	$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . strtok($_SERVER['REQUEST_URI'], '?');
		
	if($get_candidacy_status != $status){	
		$my_employee = get_user_meta( get_current_user_id(), 'my_employee_key', true);
		if($status == 3){
			if($my_employee == ''){
				update_user_meta( get_current_user_id(), 'my_employee_key', array($userid));
			} else {
				if (in_array($userid, $my_employee)) {
					//
				} else {
					array_push($my_employee, $userid);
					update_user_meta( get_current_user_id(), 'my_employee_key', $my_employee);
				}
			}
		} else {
			$pos = array_search($userid, $my_employee);
			if($pos != false){
				unset($my_employee[$pos]);
				sort($my_employee); 
				update_user_meta( get_current_user_id(), 'my_employee_key', $my_employee);
			}
		}
		update_post_meta($postid, 'candidacy_status_', $status);
		
		$my_awesome_post = array(
			'ID'           => $postid,
			'post_modified_gmt' => date('Y-m-d H:i:s')
		);
		
		// Update the post into the database
		$result = wp_update_post( $my_awesome_post, true );
		
		wp_mail($to, $subject, implode($message), $headers);
		
		header("Location: " . $current_url . "?update_status=true");
	} else {		
		header("Location: " . $current_url . "?update_status=false");
	}	
	
});

add_action('init', function(){

	// not the login request?
	if(!isset($_POST['action']) || $_POST['action'] !== 'new_horaire')
		return;
		
	$employee_horaire = $_POST['employee_horaire'];
	$job_horaire = $_POST['job_horaire'];	
	$datepickerstarthoraire = $_POST['datepickerstarthoraire'];
	$timestarthoraire = $_POST['timestarthoraire'];	
	$datepickerendhoraire = $_POST['datepickerendhoraire'];
	$timeendhoraire = $_POST['timeendhoraire'];	
	$datepickerstartpause = $_POST['datepickerstartpause'];
	$timestartpause = $_POST['timestartpause'];
	$datepickerendpause = $_POST['datepickerendpause'];
	$timeendpause = $_POST['timeendpause'];
	
	$user_by_id = get_user_by('id', $employee_horaire);
	
	// Create the post data array
	$my_post = array(
	    'post_title'    => generateRandomString(32),
	    'post_content'  => $user_by_id->user_nicename .'-' . $user_by_id->user_firstname . '-' . $user_by_id->user_lastname,
	    'post_status'   => 'publish', // Use 'draft' if you don't want it public immediately
	    'post_author'   => get_current_user_id(),         // ID of the user who is the author
	    'post_type' => 'horaire'
	);
	
	// Insert the post into the database
	$post_id = wp_insert_post( $my_post );
	
	// Check if there was an error
	if ( is_wp_error( $post_id ) ) {
	    echo $post_id->get_error_message();
	} else {
	    update_post_meta( $post_id, 'employee_horaire_key', $employee_horaire );
	    update_post_meta( $post_id, 'job_horaire_key', $job_horaire );
	    update_post_meta( $post_id, 'datepickerstarthoraire_key', $datepickerstarthoraire );
	    update_post_meta( $post_id, 'timestarthoraire_key', $timestarthoraire );
	    update_post_meta( $post_id, 'datepickerendhoraire_key', $datepickerendhoraire );
	    update_post_meta( $post_id, 'timeendhoraire_key', $timeendhoraire );
	    update_post_meta( $post_id, 'datepickerstartpause_key', $datepickerstartpause );
	    update_post_meta( $post_id, 'timestartpause_key', $timestartpause );
	    update_post_meta( $post_id, 'datepickerendpause_key', $datepickerendpause );
	    update_post_meta( $post_id, 'timeendpause_key', $timeendpause );
	    $timebrackcalc = strtotime($datepickerendpause.'T'.$timeendpause) - strtotime($datepickerstartpause.'T'.$timestartpause);
	    $minutes = floor($timebrackcalc / 60);	    	    	    
	    update_post_meta( $post_id, 'timebrake_key', $minutes );
	    $salary = get_user_meta( $employee_horaire, 'salary_key', true);
	    update_post_meta( $post_id, 'salaire_key', $salary );
	}
		
		
});



add_action('init', function(){

	// not the login request?
	if(!isset($_POST['action']) || $_POST['action'] !== 'new_punch_in_out')
		return;
	
	$userid = $_POST['userid'];
	$postid = $_POST['postid'];
	$getcurrentuserid = get_current_user_id();
	
	$current_time = current_time( 'timestamp' );
	$push_in_out = get_post_meta( $postid, 'push_in_out_key', true );
	$push_ = get_post_meta( $postid, 'push_key', true );
	if($push_in_out == 0 || $push_in_out == ''){
		update_post_meta( $postid, 'push_in_out_key', 1 );
		if($push_ == ''){
			$push_in = array('entrer', $current_time, $getcurrentuserid);
			update_post_meta( $postid, 'push_key', [$push_in] );
		} else {
			$push_in = array('entrer', $current_time, $getcurrentuserid);
			array_push($push_, $push_in);
			update_post_meta( $postid, 'push_key', $push_ );
		}
	}
	if($push_in_out == 1){
		update_post_meta( $postid, 'push_in_out_key', 0 );
		$push_out = array('sortie', $current_time, $getcurrentuserid);
		array_push($push_, $push_out);
		update_post_meta( $postid, 'push_key', $push_ );
	}

});

add_action('init', function(){

	// not the login request?
	if(!isset($_POST['action']) || $_POST['action'] !== 'edit_horaire')
		return;
	
	$userid = $_POST['userid'];
	$postid = $_POST['postid'];	
	$punchquantity = $_POST['punchquantity'];
	
	$datepickerstarthoraire = $_POST['datepickerstarthoraire'];
	$timestarthoraire = $_POST['timestarthoraire'];	
	$datepickerendhoraire = $_POST['datepickerendhoraire'];
	$timeendhoraire = $_POST['timeendhoraire'];
	$datepickerstartpause = $_POST['datepickerstartpause'];
	$timestartpause = $_POST['timestartpause'];
	$datepickerendpause = $_POST['datepickerendpause'];
	$timeendpause = $_POST['timeendpause'];	
	$salaire = $_POST['salaire'];
	
	update_post_meta( $postid, 'datepickerstarthoraire_key', $datepickerstarthoraire );
	update_post_meta( $postid, 'timestarthoraire_key', $timestarthoraire );
	update_post_meta( $postid, 'datepickerendhoraire_key', $datepickerendhoraire );
	update_post_meta( $postid, 'timeendhoraire_key', $timeendhoraire );
	update_post_meta( $postid, 'datepickerstartpause_key', $datepickerstartpause );
	update_post_meta( $postid, 'timestartpause_key', $timestartpause );
	update_post_meta( $postid, 'datepickerendpause_key', $datepickerendpause );
	update_post_meta( $postid, 'timeendpause_key', $timeendpause );
	$timebrackcalc = strtotime($datepickerendpause.'T'.$timeendpause) - strtotime($datepickerstartpause.'T'.$timestartpause);
	$minutes = floor($timebrackcalc / 60);	    	    	    
	update_post_meta( $postid, 'timebrake_key', $minutes );
	update_post_meta( $postid, 'salaire_key', $salaire );

	for ($i = 1; $i <= $punchquantity; $i++) {
		$punchdateinout = $_POST["punchdateinout-".$i];
		$punchtimeinout = $_POST["punchtimeinout-".$i];
		if($punchdateinout != '' && $punchtimeinout != '') {
			$punchunixinout = strtotime($punchdateinout.'T'.$punchtimeinout);
			if ($i % 2 == 0) {
				update_post_meta( $postid, 'push_in_out_key', 0 );
				$push_[$i-1] = array('sortie', $punchunixinout, $userid);
				update_post_meta( $postid, 'push_key', $push_ );
			} else {
				update_post_meta( $postid, 'push_in_out_key', 1 );
				$push_[$i-1] = array('entrer', $punchunixinout, $userid);
				update_post_meta( $postid, 'push_key', $push_ );
			}
		} else {
	            unset($push_[$i-1]);
	            update_post_meta( $postid, 'push_key', $push_ );
		}
	}
});


add_action('init', function(){

	// not the login request?
	if(!isset($_POST['action']) || $_POST['action'] !== 'new_approve_time')
		return;
	
	$userid = $_POST['userid'];
	$postid = $_POST['postid'];
	
	update_post_meta( $postid, 'approve_time_key', 'true' );
	
});

add_action('init', function(){

	// not the login request?
	if(!isset($_POST['action']) || $_POST['action'] !== 'new_desaprovar_time')
		return;
	
	$userid = $_POST['userid'];
	$postid = $_POST['postid'];
	
	update_post_meta( $postid, 'approve_time_key', 'false' );
	
});

add_action('init', function(){

	// not the login request?
	if(!isset($_POST['action']) || $_POST['action'] !== 'new_delete_time')
		return;
	
	$userid = $_POST['userid'];
	$postid = $_POST['postid'];
	
	wp_delete_post( $postid, true);
	
	$url = home_url( $scheme = 'https' );

	header("refresh:0;".$url."/horaire-des-employe/?delete=".$postid."");
		
});


add_action('init', function(){

	// not the login request?
	if(!isset($_POST['action']) || $_POST['action'] !== 'new_dayoff')
		return;
	
	$userid = $_POST['userid'];
	$postid = $_POST['postid'];	
	$status = $_POST['dayoff-status'];
	$reason = $_POST['dayoff-reason'];
	$explication = $_POST['dayoff-explication'];
	$replace = $_POST['employee-replace'];
	
	if($status == ''){
		update_post_meta( $postid, 'dayoff_new_key', 1 );
	} else {
		update_post_meta( $postid, 'dayoff_new_key', 0 );
	}
	update_post_meta( $postid, 'dayoff_status_key', $status );
	update_post_meta( $postid, 'dayoff_reason_key', $reason );
	update_post_meta( $postid, 'dayoff_explication_key', $explication );
	
	if($replace != ''){
		update_post_meta( $postid, 'employee_replace_key', $replace );
	}
	
	// 2. Include required WordPress files (needed for front-end execution)
	require_once(ABSPATH . 'wp-admin/includes/image.php');
	require_once(ABSPATH . 'wp-admin/includes/file.php');
	require_once(ABSPATH . 'wp-admin/includes/media.php');
	
	// 3. Perform the upload
	// Parameters: ($_FILES key, post_id to attach to [0 for none])
	$attachment_id = media_handle_upload('dayoff_upload', $postid);
	
	if (is_wp_error($attachment_id)) {
		//
	} else {
		$dayoff_attachment = get_post_meta( $postid, 'dayoff_attachment_key', true );
		if($dayoff_attachment != ''){
			wp_delete_attachment($dayoff_attachment, true);
		}
		update_post_meta( $postid, 'dayoff_attachment_key', $attachment_id );
	}		

		
});


add_action('init', function(){

	// not the login request?
	if(!isset($_POST['action']) || $_POST['action'] !== 'new_section_employeur')
		return;
		
	$userid = $_POST['userid'];
	$salary = $_POST['salaire'];
	
	update_user_meta( $userid, 'salary_key', $salary);
	
});	

?>