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
    	wp_die('Échec de la connexion. Mot de passe ou nom d&#8216;utilisateur incorrect?');3
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
  if(isset($_POST['action']) || $_POST['action'] === 'my_register_action') {
    
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
    
  }

});

function enqueue_frontend_media_scripts() {
    if (is_user_logged_in()) { // Optional: restrict to logged-in users
        wp_enqueue_media();
    }
}
add_action('wp_enqueue_scripts', 'enqueue_frontend_media_scripts');

?>
