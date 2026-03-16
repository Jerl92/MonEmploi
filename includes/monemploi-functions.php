<?php

/**
 * All the Functions
 *
 * All the responsible functions for NanoSupport.
 *
 * @author      nanodesigns
 * @category    Core
 * @package     NanoSupport
 */

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

function my_custom_after_registration_action( $user_id, $args ) {
    if ( empty( $user_id ) || is_wp_error( $user_id ) ) {
        return;
    }
    
    $user = new WP_User( $user_id );
	$meta_for_user = get_user_meta( $user_id, 'status', true ); 
	$meta_user_status = $meta_for_user[0];
	$statuts_value = sanitize_text_field( $meta_user_status );
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
    return um_get_core_page( 'login' );
}

function auto_approve_all_comments( $approved, $commentdata ) {
    return 1;
}
add_filter( 'pre_comment_approved', 'auto_approve_all_comments', 99, 2 );

function getDecimalPart($number) {
    // Convert to string to avoid float precision issues
    $number_string = (string)$number;
    
    // Find the position of the last decimal point (either '.' or ',')
    $last_dot_pos = strrpos($number_string, '.');
    $last_comma_pos = strrpos($number_string, ',');
    
    $decimal_point_pos = false;
    if ($last_dot_pos !== false) {
        $decimal_point_pos = $last_dot_pos;
    } elseif ($last_comma_pos !== false) {
        $decimal_point_pos = $last_comma_pos;
    }

    if ($decimal_point_pos !== false) {
        // Extract the substring after the decimal point
        return '0.' . substr($number_string, $decimal_point_pos + 1);
    } else {
        // No decimal part found
        return "0"; // or "" depending on desired output
    }
}


?>