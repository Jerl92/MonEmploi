<?php

/* Enqueue Script */
add_action( 'wp_enqueue_scripts', 'monemploi_ajax_scripts' );

/**
 * Scripts
 */
function monemploi_ajax_scripts() {

	/* Plugin DIR URL */
	$url = trailingslashit( plugin_dir_url( __FILE__ ) );
    
	wp_enqueue_script( 'maps', "https://maps.googleapis.com/maps/api/js?key=AIzaSyAQJhQQ_wNHUOVollbuGpJG_eAQ6-4xz3E", array( 'jquery' ), '1.0.0', true );
	
	wp_register_script( 'monemploi-ajax-send-cv-scripts', $url . "js/ajax.monemploi.send.js", array( 'jquery' ), '1.0.0', true );
	wp_localize_script( 'monemploi-ajax-send-cv-scripts', 'send_cv_monemploi_ajax_url', admin_url( 'admin-ajax.php', 'relative' ) );
	wp_enqueue_script( 'monemploi-ajax-send-cv-scripts' );
	
	wp_register_script( 'monemploi-ajax-save-status-scripts', $url . "js/ajax.monemploi.status.js", array( 'jquery' ), '1.0.0', true );
	wp_localize_script( 'monemploi-ajax-save-status-scripts', 'save_status_monemploi_ajax_url', admin_url( 'admin-ajax.php', 'relative' ) );
	wp_enqueue_script( 'monemploi-ajax-save-status-scripts' );
	
	wp_register_script( 'monemploi-ajax-maps-scripts', $url . "js/ajax.monemploi.maps.js", array( 'jquery' ), '1.0.0', true );
	wp_localize_script( 'monemploi-ajax-maps-scripts', 'maps_monemploi_ajax_url', admin_url( 'admin-ajax.php', 'relative' ) );
	wp_enqueue_script( 'monemploi-ajax-maps-scripts' ); 
	
	wp_register_script( 'monemploi-ajax-job-question-scripts', $url . "js/ajax.monemploi.job.question.js", array( 'jquery' ), '1.0.0', true );
	wp_localize_script( 'monemploi-ajax-job-question-scripts', 'job_question_monemploi_ajax_url', admin_url( 'admin-ajax.php', 'relative' ) );
	wp_enqueue_script( 'monemploi-ajax-job-question-scripts' );
	
	wp_register_script( 'monemploi-ajax-job-experiance-scripts', $url . "js/ajax.monemploi.job.experiance.js", array( 'jquery' ), '1.0.0', true );
	wp_localize_script( 'monemploi-ajax-job-experiance-scripts', 'job_experiance_monemploi_ajax_url', admin_url( 'admin-ajax.php', 'relative' ) );
	wp_enqueue_script( 'monemploi-ajax-job-experiance-scripts' );
	
	wp_register_script( 'monemploi-ajax-certification-experiance-scripts', $url . "js/ajax.monemploi.certification.experiance.js", array( 'jquery' ), '1.0.0', true );
	wp_localize_script( 'monemploi-ajax-certification-experiance-scripts', 'certification_experiance_monemploi_ajax_url', admin_url( 'admin-ajax.php', 'relative' ) );
	wp_enqueue_script( 'monemploi-ajax-certification-experiance-scripts' );
	
	wp_register_script( 'monemploi-ajax-school-experiance-scripts', $url . "js/ajax.monemploi.school.experiance.js", array( 'jquery' ), '1.0.0', true );
	wp_localize_script( 'monemploi-ajax-school-experiance-scripts', 'school_experiance_monemploi_ajax_url', admin_url( 'admin-ajax.php', 'relative' ) );
	wp_enqueue_script( 'monemploi-ajax-school-experiance-scripts' );
	
	wp_register_script( 'monemploi-ajax-edit-job-experiance-scripts', $url . "js/ajax.monemploi.edit.job.experiance.js", array( 'jquery' ), '1.0.0', true );
	wp_localize_script( 'monemploi-ajax-edit-job-experiance-scripts', 'job_edit_experiance_monemploi_ajax_url', admin_url( 'admin-ajax.php', 'relative' ) );
	wp_enqueue_script( 'monemploi-ajax-edit-job-experiance-scripts' );
	
	wp_register_script( 'monemploi-ajax-delete-job-experiance-scripts', $url . "js/ajax.monemploi.delete.job.experiance.js", array( 'jquery' ), '1.0.0', true );
	wp_localize_script( 'monemploi-ajax-delete-job-experiance-scripts', 'job_delete_experiance_monemploi_ajax_url', admin_url( 'admin-ajax.php', 'relative' ) );
	wp_enqueue_script( 'monemploi-ajax-delete-job-experiance-scripts' );
	        	
	wp_register_script( 'monemploi-ajax-edit-certification-experiance-scripts', $url . "js/ajax.monemploi.edit.certification.experiance.js", array( 'jquery' ), '1.0.0', true );
	wp_localize_script( 'monemploi-ajax-edit-certification-experiance-scripts', 'certification_edit_experiance_monemploi_ajax_url', admin_url( 'admin-ajax.php', 'relative' ) );
	wp_enqueue_script( 'monemploi-ajax-edit-certification-experiance-scripts' );
	
	wp_register_script( 'monemploi-ajax-delete-certification-experiance-scripts', $url . "js/ajax.monemploi.delete.certification.experiance.js", array( 'jquery' ), '1.0.0', true );
	wp_localize_script( 'monemploi-ajax-delete-certification-experiance-scripts', 'certification_delete_experiance_monemploi_ajax_url', admin_url( 'admin-ajax.php', 'relative' ) );
	wp_enqueue_script( 'monemploi-ajax-delete-certification-experiance-scripts' );
	
	wp_register_script( 'monemploi-ajax-edit-school-experiance-scripts', $url . "js/ajax.monemploi.edit.school.experiance.js", array( 'jquery' ), '1.0.0', true );
	wp_localize_script( 'monemploi-ajax-edit-school-experiance-scripts', 'school_edit_experiance_monemploi_ajax_url', admin_url( 'admin-ajax.php', 'relative' ) );
	wp_enqueue_script( 'monemploi-ajax-edit-school-experiance-scripts' );
	
	wp_register_script( 'monemploi-ajax-delete-school-experiance-scripts', $url . "js/ajax.monemploi.delete.school.experiance.js", array( 'jquery' ), '1.0.0', true );
	wp_localize_script( 'monemploi-ajax-delete-school-experiance-scripts', 'school_delete_experiance_monemploi_ajax_url', admin_url( 'admin-ajax.php', 'relative' ) );
	wp_enqueue_script( 'monemploi-ajax-delete-school-experiance-scripts' );
	
	wp_register_script( 'monemploi-ajax-sortable-reindex-scripts', $url . "js/null.js", array( 'jquery' ), '1.0.0', true );
	wp_localize_script( 'monemploi-ajax-sortable-reindex-scripts', 'sortable_reindex_monemploi_ajax_url', admin_url( 'admin-ajax.php', 'relative' ) );
	wp_enqueue_script( 'monemploi-ajax-sortable-reindex-scripts' );
	
	wp_register_script( 'monemploi-ajax-job-draft-scripts', $url . "js/ajax.monemploi.job.draft.js", array( 'jquery' ), '1.0.0', true );
	wp_localize_script( 'monemploi-ajax-job-draft-scripts', 'job_draft_monemploi_ajax_url', admin_url( 'admin-ajax.php', 'relative' ) );
	wp_enqueue_script( 'monemploi-ajax-job-draft-scripts' );
	
	wp_register_script( 'monemploi-ajax-job-draft-publish-scripts', $url . "js/ajax.monemploi.job.draft.publish.js", array( 'jquery' ), '1.0.0', true );
	wp_localize_script( 'monemploi-ajax-job-draft-publish-scripts', 'job_draft_publish_monemploi_ajax_url', admin_url( 'admin-ajax.php', 'relative' ) );
	wp_enqueue_script( 'monemploi-ajax-job-draft-publish-scripts' );

	wp_register_script( 'monemploi-ajax-job-delete-scripts', $url . "js/ajax.monemploi.job.delete.js", array( 'jquery' ), '1.0.0', true );
	wp_localize_script( 'monemploi-ajax-job-delete-scripts', 'job_delete_monemploi_ajax_url', admin_url( 'admin-ajax.php', 'relative' ) );
	wp_enqueue_script( 'monemploi-ajax-job-delete-scripts' );

	wp_register_script( 'monemploi-ajax-comment-reply-scripts', $url . "js/ajax.monemploi.comment.reply.js", array( 'jquery' ), '1.0.0', true );
	wp_localize_script( 'monemploi-ajax-comment-reply-scripts', 'comment_candidacy_reply_monemploi_ajax_url', admin_url( 'admin-ajax.php', 'relative' ) );
	wp_enqueue_script( 'monemploi-ajax-comment-reply-scripts' );
	
}

function get_user_ip() {
    // Check for Cloudflare header
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        return $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    // Fallback for other proxies/load balancers (less reliable/easily spoofed)
    elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // Handle potential comma-separated list of IPs and return the first one (client IP)
        $ip_list = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ip_list[0]);
    }
    // Default: use REMOTE_ADDR
    else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

/* AJAX action callback */
add_action( 'wp_ajax_monemploi_question_job', 'monemploi_question_job' );
add_action( 'wp_ajax_nopriv_monemploi_question_job', 'monemploi_question_job' );
function monemploi_question_job($post) {

	$userid = $_POST['object_id'];
	$age_legal = $_POST['age_legal'];
	$situation_canada = $_POST['situation_canada'];
	$permis_travail = $_POST['permis_travail'];
	$dossier_criminel = $_POST['dossier_criminel'];
	$dossier_criminel_info = $_POST['dossier_criminel_info'];
	$sexe = $_POST['sexe'];
	$origine_ethnique = $_POST['origine_ethnique'];
	$autochtone = $_POST['autochtone'];
	$handicap = $_POST['handicap'];
	$handicap_info = $_POST['handicap_info'];
	
	update_user_meta( $userid, 'my_age_legal_key', $age_legal );
	update_user_meta( $userid, 'my_situation_canada_key', $situation_canada );
	update_user_meta( $userid, 'my_permis_travail_key', $permis_travail );	
	update_user_meta( $userid, 'my_dossier_criminel_key', $dossier_criminel );
	update_user_meta( $userid, 'my_dossier_criminel_info_key', $dossier_criminel_info );       	
	update_user_meta( $userid, 'my_sexe_key', $sexe );
	update_user_meta( $userid, 'my_origine_ethnique_key', $origine_ethnique );
	update_user_meta( $userid, 'my_autochtone_key', $autochtone );
	update_user_meta( $userid, 'my_handicap_key', $handicap );
	update_user_meta( $userid, 'my_handicap_info_key', $handicap_info );
	
	wp_send_json ( 'Tout les informations on ete sauvegarder.' );

}

/* AJAX action callback */
add_action( 'wp_ajax_monemploi_send_cv_job', 'monemploi_send_cv_job' );
add_action( 'wp_ajax_nopriv_monemploi_send_cv_job', 'monemploi_send_cv_job' );
function monemploi_send_cv_job($post) {

	$postid = $_POST['object_id'];
	$deja_travaille = $_POST['deja_travaille'];
	$superieur_nom = $_POST['superieur_nom'];
	$superieur_email = $_POST['superieur_email'];
	$superieur_numero = $_POST['superieur_numero'];
	$superieur_poste = $_POST['superieur_poste'];
	$cv = $_POST['selectedValues'];
	$lettre_presentation = $_POST['lettre_presentation'];
	$confidentialite = $_POST['confidentialite'];
    
	  // Post data array
	    $my_post = array(
	        'post_title'    => wp_strip_all_tags(  get_the_title($postid)  ),
	        'post_content'  => 'Le candida a postulé depuis ladresse IPv4 '. get_user_ip(),
	        'post_status'   => 'publish', // Set status to 'publish', 'draft', 'pending', etc.
	        'post_author'   => get_current_user_id(), // The ID of the post author (e.g., 1 for the first admin user)
	        'post_type'     => 'candidacy', // Can be 'post', 'page', or a custom post type slug
	    );
	
	    // Insert the post into the database
	    $post_id = wp_insert_post( $my_post );
	
	    // Optional: Error handling
	    if ( is_wp_error( $post_id ) ) {
	        error_log( 'Post creation failed: ' . $post_id->get_error_message() );
	    } else {
	        // You can add post meta or other actions here using the new $post_id
	        $author_id = get_post_field( 'post_author', $postid );
	        update_post_meta( $post_id, 'my_cv_key', $cv );
	       	update_post_meta( $post_id, 'my_postid_key', $postid );
	       	update_post_meta( $post_id, 'my_author_id_key', $author_id );
	       	update_post_meta( $post_id, 'my_lettre_presentation_key', $lettre_presentation );  
	       	
	       	update_post_meta( $post_id, 'my_age_legal_key', $age_legal );
	       	update_post_meta( $post_id, 'my_situation_canada_key', $situation_canada );
	       	update_post_meta( $post_id, 'my_permis_travail_key', $permis_travail );
	       	update_post_meta( $post_id, 'my_deja_travaille_key', $deja_travaille );	       	
	       	update_post_meta( $post_id, 'my_superieur_nom_key', $superieur_nom );
	       	update_post_meta( $post_id, 'my_superieur_email_key', $superieur_email );
	       	update_post_meta( $post_id, 'my_superieur_numero_key', $superieur_numero );
	       	update_post_meta( $post_id, 'my_superieur_poste_key', $superieur_poste );	       	
	       	update_post_meta( $post_id, 'my_sexe_key', $sexe );
	       	update_post_meta( $post_id, 'my_origine_ethnique_key', $origine_ethnique );
	       	update_post_meta( $post_id, 'my_autochtone_key', $autochtone );
	       	update_post_meta( $post_id, 'my_handicap_key', $handicap );
	       	update_post_meta( $post_id, 'my_handicap__key', $handicap_ );
	       	update_post_meta( $post_id, 'my_confidentialite_key', $confidentialite );

    		$authorid      = get_post_field( 'post_author', $postid );
    		$author_email   = get_the_author_meta( 'user_email', $authorid );
    		
    		$to = $author_email;
    		$subject = sprintf ( __( 'Nouvelle candidature #%s — %s — %s', 'monemploi' ), $post_id, get_the_title($post_id), get_bloginfo( 'name', 'display' ) );
    		$headers = array('Content-Type: text/html; charset=UTF-8');
    		
    		$message[] .= '<p>';
    		$message[] .= 'Nouvelle candidature #' . $post_id;
    		$message[] .= '</p>';
    		$message[] .= '<a href="' . get_permalink( $post_id ) . '">Voire la candidature</a>';
    
    		wp_mail($to, $subject, implode($message), $headers);
  
	    }
	   
	    $html[] .= 'Votre candidature a ete envoyé avec succès.';
	
	
        wp_send_json ( implode($html) );
}	

/* AJAX action callback */
add_action( 'wp_ajax_monemploi_save_status', 'monemploi_save_status' );
add_action( 'wp_ajax_nopriv_monemploi_save_status', 'monemploi_save_status' );
function monemploi_save_status($post) {

	$object_id = $_POST['object_id'];
	
	$status = $_POST['status'];
	
	$i = $_POST['i'];
	
	$get_candidacy_status = get_post_meta($object_id, 'candidacy_status_', true);
	
	$author_id = get_post_field( 'post_author', $object_id );
	$author_email = get_the_author_meta( 'user_email', $author_id );
	$to = $author_email;
	
	$subject = sprintf ( __( 'Nouveaux status #%s — %s — %s', 'monemploi' ), $object_id, get_the_title($object_id), get_bloginfo( 'name', 'display' ) );
	$headers = array('Content-Type: text/html; charset=UTF-8');
	
	$message[] .= '<p>';
	$message[] .= 'Nouveaux status #' . $object_id;
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
	$message[] .= '<a href="' . get_permalink( $object_id ) . '">Voire le status</a>';
		
	if($get_candidacy_status !== $status){	
		update_post_meta($object_id, 'candidacy_status_', $status);
		
		$my_awesome_post = array(
			'ID'           => $object_id,
			'post_modified_gmt' => date('Y-m-d H:i:s')
		);
		
		// Update the post into the database
		$result = wp_update_post( $my_awesome_post, true );
		
		wp_mail($to, $subject, implode($message), $headers);
	
		wp_send_json ( 'La candidature à été mise à jour à succès.' );
	} else {
		wp_send_json ( 'La candidature na pas été mise à jour.' );
	}	
	
}	

function generate_secure_string($length = 16) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        // Use random_int for secure random number generation
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}


/* AJAX action callback */
add_action( 'wp_ajax_monemploi_add_job_experiance', 'monemploi_add_job_experiance' );
add_action( 'wp_ajax_nopriv_monemploi_add_job_experiance', 'monemploi_add_job_experiance' );
function monemploi_add_job_experiance($post) {

	$ramdonstring = generate_secure_string();
	
	$html[] .= '<div class="'. $ramdonstring .'" style="padding-bottom: 15px;">';
	
	$html[] .= '<p>Titre du poste</p>';
	$html[] .= '<input type="text" class="monemploi_add_job_experiance_title" name="monemploi_job_experiance_title" id="monemploi_job_experiance_title">';
	
	$html[] .= '<p>Nom de Lemployeur</p>';
	$html[] .= '<input type="text" class="monemploi_add_job_experiance_name" name="monemploi_job_experiance_name" id="monemploi_job_experiance_name">';
	
	$html[] .= '<p>Début de lemploi</p>';
	$html[] .= '<input type="text" class="datepickerstartjob" data-toggle="datepickerstartjob" > - ';
	$html[] .= '<input type="checkbox" class="still-working" name="still-working" value="0"> - Toujours au travaille?</input>';
	
	$html[] .= '<p>Fin de lemploi</p>';
	$html[] .= '<input type="text" class="datepickerendjob" data-toggle="datepickerendjob" >';
 	
	$html[] .= '<p>Description des taches</p>';
	$html[] .= '<textarea class="monemploi_add_job_experiance_description" name="monemploi_job_experiance_description" id="monemploi_job_experiance_description" placeholder="Description des taches..." rows="6" cols="75"></textarea>';
	
	$html[] .= '<div class="" style="position: relative;">';
		$html[] .= '<button class="save-job-experiance" data-object-id="' . $ramdonstring . '">Sauvegarder</button>';
		$html[] .= '<div class="delete-job-experiance" style="position: absolute; top: 7.5px; right: 0;" data-object-id="' . $ramdonstring . '"><i class="material-icons">delete</i></div>';
	$html[] .= '</div>';
	
	$html[] .= '</div>';
		
	wp_send_json ( implode($html) );
}	

/* AJAX action callback */
add_action( 'wp_ajax_monemploi_save_job_experiance', 'monemploi_save_job_experiance' );
add_action( 'wp_ajax_nopriv_monemploi_save_job_experiance', 'monemploi_save_job_experiance' );
function monemploi_save_job_experiance($post) {

	$job_title = $_POST['job_title'];
	$job_name = $_POST['job_name'];
	$job_description = $_POST['job_description'];
	$unique = $_POST['unique'];
	$datejobstart = $_POST['datejobstart'];
	$datejobend = $_POST['datejobend'];
	$stillworking = $_POST['stillworking'];
	
	$userid = get_current_user_id();
	
	$unique_strings = get_user_meta( $userid, 'job_unique', true);

	if (!in_array($unique, $unique_strings)) {
		if(!is_array($unique_strings) ){
		
			update_user_meta( $userid, 'job_unique', array($unique));
		
		} else 	{
	
			array_unshift($unique_strings, $unique);
			update_user_meta( $userid, 'job_unique', $unique_strings);
	    }
	}
	
	update_user_meta( $userid, 'job_title_'.$unique, $job_title);
	update_user_meta( $userid, 'job_name_'.$unique, $job_name);
	update_user_meta( $userid, 'date_job_start_'.$unique, esc_attr($datejobstart));
	update_user_meta( $userid, 'date_job_end_'.$unique, esc_attr($datejobend));
	update_user_meta( $userid, 'job_description_'.$unique, $job_description);
	update_user_meta( $userid, 'still_working_'.$unique, $stillworking);
	
	$html[] .= '<div id="job-experiance-unique-string" class="'. $unique .'" style="padding-bottom: 15px;">';
                		
		$date_job_start_strtotime = strtotime(get_user_meta( $userid, 'date_job_start_'.$unique, true));
		$date_job_end_strtotime = strtotime(get_user_meta( $userid, 'date_job_end_'.$unique, true));
		$date_job_strtotime = ($date_job_end_strtotime - $date_job_start_strtotime);
		$date_job_strtotime_clac = ($date_job_strtotime/60/60/24/30);
		
		$date_job_end_ = get_user_meta( $userid, 'date_job_end_'.$unique, true);
		if($date_job_end_ === 'now'){
			$datestrtotime = date('Y-m-d H:i:s');
		} else {
			$datestrtotime = $date_job_end_;
		}

		$html[] .= '<div class="job-experiance-title-compagny-header" style="padding-bottom: 5px; width: 100%"><span style="font-weight: 600;">'. get_user_meta( $userid, 'job_title_'.$unique, true) .'</span> - <span style="font-style: italic;">'. get_user_meta( $userid, 'job_name_'.$unique, true) .'</span></div>';
		$html[] .= '<span>' . date('m/Y', strtotime(get_user_meta( $userid, 'date_job_start_'.$unique, true))) . '</span>';
		$html[] .= ' - ';
		$html[] .= '<span>' . date('m/Y', strtotime($datestrtotime)) . '</span>';
		$html[] .= ' - ';
		$html[] .= '<span>' . round($date_job_strtotime_clac) . ' Mois</span>';				
		$html[] .= '<div class="job-experiance-description-header" style="white-space: pre-wrap;">'. get_user_meta( $userid, 'job_description_'.$unique, true) .'</div>';

	
	$html[] .= '</div>';
    
    wp_send_json ( implode($html) );
}

/* AJAX action callback */
add_action( 'wp_ajax_edit_job_experiance', 'edit_job_experiance' );
add_action( 'wp_ajax_nopriv_edit_job_experiance', 'edit_job_experiance' );
function edit_job_experiance($post) {

	$i = 0;
    
	$is_editing = $_POST['is_editing'];
	
	$userid = get_current_user_id();
	
	$unique_strings = get_user_meta( $userid, 'job_unique', true );
	
	foreach($unique_strings as $unique_string)  {
	
		if($is_editing === 'false') {
		
			$still_working_ = get_user_meta( $userid, 'still_working_'.$unique_string, true );
	
			$html[] = '<div class="job-experiance-unique-string '. $unique_string .'" style="padding-bottom: 15px;">';
					    	
		    	$html[] .= '<div style="position: relative;">';
		    		$html[] .= '<span class="handle-job"><i class="material-icons">menu</i></span>';
					$html[] .= '<div style="position: absolute; top: 0; right: 0;">';
				    	$html[] .= '<span class="sortable-job-up"><i class="material-icons">keyboard_arrow_up</i></span>';
				    	$html[] .= '<span class="wrapper-job"><span class="sortable-job">' . $i . '</span></span>';
				    	$html[] .= '<span class="sortable-job-down"><i class="material-icons">keyboard_arrow_down</i></span>';
			    	    	$html[] .= '</div>';
		    	$html[] .= '</div>';
		    			
			$html[] .= '<p>Titre du poste</p>';
			$html[] .= '<input type="text" class="monemploi_add_job_experiance_title" name="monemploi_job_experiance_title" id="monemploi_job_experiance_title" value="' . get_user_meta( $userid, 'job_title_'.$unique_string, true) . '">';
			
			$html[] .= '<p>Nom de Lemployeur</p>';
			$html[] .= '<input type="text" class="monemploi_add_job_experiance_name" name="monemploi_job_experiance_name" id="monemploi_job_experiance_name" value="' . get_user_meta( $userid, 'job_name_'.$unique_string, true) . '">';
			
			$html[] .= '<p>Début de lemploi</p>';
			$html[] .= '<input type="text" class="datepickerstartjob" data-toggle="datepickerstartjob_" value="' . get_user_meta( $userid, 'date_job_start_'.$unique_string, true) . '"> - ';
			if($still_working_ == 0 || $still_working_ == null){
				$html[] .= '<input type="checkbox" class="still-working" name="still-working" value="0"> - Toujours au travaille?</input>';
		    	} else {
		    		$html[] .= '<input type="checkbox" class="still-working" name="still-working" value="0" checked> - Toujours au travaille?</input>';
		    	}
			$html[] .= '<p>Fin de lemploi</p>';
			$html[] .= '<input type="text" class="datepickerendjob" data-toggle="datepickerendjob_"  value="' . get_user_meta( $userid, 'date_job_end_'.$unique_string, true) . '">';
            
			$html[] .= '<p>Description des taches</p>';
			$html[] .= '<textarea class="monemploi_add_job_experiance_description" name="monemploi_job_experiance_description" id="monemploi_job_experiance_description" placeholder="Description des taches" rows="6" cols="75">' . get_user_meta( $userid, 'job_description_'.$unique_string, true) .'</textarea>';
			
			$html[] .= '<div class="" style="position: relative;">';
				$html[] .= '<button class="save-job-experiance" data-object-id="' . $unique_string . '">Sauvegarder</button>';
				$html[] .= '<div class="delete-job-experiance" style="position:absolute; top:7.5px; right:0;" data-object-id="' . $unique_string . '"><i class="material-icons">delete</i></div>';
			$html[] .= '</div>';
				
			$html[] .= '</div>';
			
			$i++;
		}
	}
	
	foreach($unique_strings as $unique_string)  {
		
		if($is_editing === 'true') {
	
			$html[] .= '<div id="job-experiance-unique-string" class="'. $unique_string .'" style="padding-bottom: 15px;">';
		                		
				$date_job_start_strtotime = strtotime(get_user_meta( $userid, 'date_job_start_'.$unique_string, true));
				$date_job_end_strtotime = strtotime(get_user_meta( $userid, 'date_job_end_'.$unique_string, true));
				$date_job_strtotime = ($date_job_end_strtotime - $date_job_start_strtotime);
				$date_job_strtotime_clac = ($date_job_strtotime/60/60/24/30);
				
				$date_job_end_ = get_user_meta( $userid, 'date_job_end_'.$unique_string, true);
				if($date_job_end_ === 'now'){
					$datestrtotime = date('Y-m-d H:i:s');
				} else {
					$datestrtotime = $date_job_end_;
				}
		
				$html[] .= '<div class="job-experiance-title-compagny-header" style="padding-bottom: 5px; width: 100%"><span style="font-weight: 600;">'. get_user_meta( $userid, 'job_title_'.$unique_string, true) .'</span> - <span style="font-style: italic;">'. get_user_meta( $userid, 'job_name_'.$unique_string, true) .'</span></div>';
				$html[] .= '<span>' . date('m/Y', strtotime(get_user_meta( $userid, 'date_job_start_'.$unique_string, true))) . '</span>';
				$html[] .= ' - ';
				$html[] .= '<span>' . date('m/Y', strtotime($datestrtotime)) . '</span>';
				$html[] .= ' - ';
				$html[] .= '<span>' . round($date_job_strtotime_clac) . ' Mois</span>';	
				$html[] .= '<div class="job-experiance-description-header" style="white-space: pre-wrap;">'. get_user_meta( $userid, 'job_description_'.$unique_string, true) .'</div>';
		
			
			$html[] .= '</div>';
			
		}
			
	}
	
        wp_send_json ( implode($html) );
}	

/* AJAX action callback */
add_action( 'wp_ajax_delete_job_experiance', 'delete_job_experiance' );
add_action( 'wp_ajax_nopriv_delete_job_experiance', 'delete_job_experiance' );
function delete_job_experiance($post) {

	$object_id = $_POST['object_id'];
	
	$userid = get_current_user_id();
	
	$unique_strings = get_user_meta( $userid, 'job_unique', true);
	
	if ( in_array( $object_id, $unique_strings ) ) {
		unset( $unique_strings[array_search( $object_id, $unique_strings )] );
		update_user_meta( $userid, 'job_unique', $unique_strings);
		delete_user_meta( $userid, 'job_title_'.$object_id);
		delete_user_meta( $userid, 'job_name_'.$object_id);	
		delete_user_meta( $userid, 'job_description_'.$object_id);
	}
		
        wp_send_json ( $object_id );
}		

/* AJAX action callback */
add_action( 'wp_ajax_monemploi_add_certification_experiance', 'monemploi_add_certification_experiance' );
add_action( 'wp_ajax_nopriv_monemploi_add_certification_experiance', 'monemploi_add_certification_experiance' );
function monemploi_add_certification_experiance($post) {

	$ramdonstring = generate_secure_string();
	
	$html[] .= '<div class="'. $ramdonstring .'" style="padding-bottom: 15px;">';
	
	$html[] .= '<p>Nom de la certification</p>';
	$html[] .= '<input type="text" class="monemploi_add_certification_experiance_title" name="monemploi_certification_experiance_title" id="monemploi_certification_experiance_title">';
	
	$html[] .= '<p>Companie</p>';
	$html[] .= '<input type="text" class="monemploi_add_certification_experiance_name" name="monemploi_certification_experiance_name" id="monemploi_certification_experiance_name">';
	
	$html[] .= '<p>Début de la certification</p>';
	$html[] .= '<input type="text" class="datepickerstartcertification" data-toggle="datepickerstartcertification" >';
	
	$html[] .= '<p>Fin de la certification</p>';
	$html[] .= '<input type="text" class="datepickerendcertification" data-toggle="datepickerendcertification" >';
	
	$html[] .= '<p>Description des taches</p>';
	$html[] .= '<textarea class="monemploi_add_certification_experiance_description" name="monemploi_add_certification_experiance_description" id="monemploi_add_certification_experiance_description" placeholder="Description des taches..." rows="6" cols="75"></textarea>';	
	
	$html[] .= '<div class="" style="position: relative;">';
		$html[] .= '<button class="save-certification-experiance" data-object-id="' . $ramdonstring . '">Sauvegarder</button>';
		$html[] .= '<div class="delete-certification-experiance" style="position: absolute; top: 7.5px; right: 0;" data-object-id="' . $ramdonstring . '"><i class="material-icons">delete</i></div>';
	$html[] .= '</div>';
	
	$html[] .= '</div>';
	
	wp_send_json ( implode($html) );
}		

/* AJAX action callback */
add_action( 'wp_ajax_monemploi_save_certification_experiance', 'monemploi_save_certification_experiance' );
add_action( 'wp_ajax_nopriv_monemploi_save_certification_experiance', 'monemploi_save_certification_experiance' );
function monemploi_save_certification_experiance($post) {

	$certification_title = $_POST['certification_title'];
	$certification_name = $_POST['certification_name'];
	$certification_description = $_POST['certification_description'];
	$unique = $_POST['unique'];
	$datecertificationstart = $_POST['datecertificationstart'];
	$datecertificationend = $_POST['datecertificationend'];
	
	$userid = get_current_user_id();
	
	$unique_strings = get_user_meta( $userid, 'certification_unique', true);

	if (!in_array($unique, $unique_strings)) {
		if(!is_array($unique_strings) ){
		
			update_user_meta( $userid, 'certification_unique', array($unique));
		
		} else 	{
	
			array_unshift($unique_strings, $unique);
			update_user_meta( $userid, 'certification_unique', $unique_strings);
	    }
	}
	
	update_user_meta( $userid, 'certification_title_'.$unique, $certification_title);
	update_user_meta( $userid, 'certification_name_'.$unique, $certification_name);
	update_user_meta( $userid, 'certification_description_'.$unique, $certification_description);
	update_user_meta( $userid, 'datecertificationstart_'.$unique, $datecertificationstart);
	update_user_meta( $userid, 'datecertificationend_'.$unique, $datecertificationend);

	$date_certification_start_strtotime = strtotime(get_user_meta( $userid, 'datecertificationstart_'.$unique, true));
	$date_certification_end_strtotime = strtotime(get_user_meta( $userid, 'datecertificationend_'.$unique, true));
	$date_certification_strtotime = ($date_certification_end_strtotime - $date_certification_start_strtotime);
	$date_certification_strtotime_clac = ($date_certification_strtotime/60/60/24/30);

	
	$html[] .= '<div class="certification-experiance-title-company-header" style="padding-bottom: 5px; width: 100%"><span style="font-weight: 600;">'. get_user_meta( $userid, 'certification_title_'.$unique, true) .'</span> - <span style="font-style: italic;">'. get_user_meta( $userid, 'certification_name_'.$unique, true) .'</span></div>';
	$html[] .= '<span>' . date('m/Y', $date_certification_start_strtotime) . '</span>';
	$html[] .= ' - ';
	$html[] .= '<span>' . date('m/Y', $date_certification_end_strtotime) . '</span>';
	$html[] .= ' - ';
	$html[] .= '<span>' . round($date_certification_strtotime_clac) . ' Mois</span>';
	$html[] .= '<div class="certification-experiance-description-header" style="white-space: pre-wrap;">'. get_user_meta( $userid, 'certification_description_'.$unique, true) .'</div>';
    
        wp_send_json ( implode($html) );
}


/* AJAX action callback */
add_action( 'wp_ajax_delete_certification_experiance', 'delete_certification_experiance' );
add_action( 'wp_ajax_nopriv_delete_certification_experiance', 'delete_certification_experiance' );
function delete_certification_experiance($post) {

	$object_id = $_POST['object_id'];
	
	$userid = get_current_user_id();
	
	$unique_strings = get_user_meta( $userid, 'certification_unique', true);
	
	if ( in_array( $object_id, $unique_strings ) ) {
		unset( $unique_strings[array_search( $object_id, $unique_strings )] );
		update_user_meta( $userid, 'certification_unique', $unique_strings);
		delete_user_meta( $userid, 'certification_title_'.$object_id);
		delete_user_meta( $userid, 'certification_name_'.$object_id);	
		delete_user_meta( $userid, 'certification_description_'.$object_id);
		delete_user_meta( $userid, 'datecertificationstart_'.$object_id);
		delete_user_meta( $userid, 'datecertificationend_'.$object_id);
	}
		
        wp_send_json ( $object_id );
}	

/* AJAX action callback */
add_action( 'wp_ajax_edit_certification_experiance', 'edit_certification_experiance' );
add_action( 'wp_ajax_nopriv_edit_certification_experiance', 'edit_certification_experiance' );
function edit_certification_experiance($post) {

	$i = 0;

	$is_editing = $_POST['is_editing'];
	
	$userid = get_current_user_id();
	
	$unique_strings = get_user_meta( $userid, 'certification_unique', true );
	
	foreach($unique_strings as $unique_string)  {
	
		if($is_editing === 'false') {
	
			$html[] = '<div class="certification-experiance-unique-string '. $unique_string .'" style="padding-bottom: 15px;">';
		    	$html[] .= '<div style="position: relative;">';
		    		$html[] .= '<span class="handle-certification"><i class="material-icons">menu</i></span>';
					$html[] .= '<div style="position: absolute; top: 0; right: 0;">';
					    	$html[] .= '<span class="sortable-certification-up"><i class="material-icons">keyboard_arrow_up</i></span>';
					    	$html[] .= '<span class="wrapper-certification"><span class="sortable-certification">' . $i . '</span></span>';
					    	$html[] .= '<span class="sortable-certification-down"><i class="material-icons">keyboard_arrow_down</i></span>';
			    	    	$html[] .= '</div>';
		    	$html[] .= '</div>';
			
			$html[] .= '<p>Nom de la certification</p>';
			$html[] .= '<input type="text" class="monemploi_add_certification_experiance_title" name="monemploi_certification_experiance_title" id="monemploi_certification_experiance_title" value="' . get_user_meta( $userid, 'certification_title_'.$unique_string, true) . '">';
			
			$html[] .= '<p>Compagnie</p>';
			$html[] .= '<input type="text" class="monemploi_add_certification_experiance_name" name="monemploi_certification_experiance_name" id="monemploi_certification_experiance_name" value="' . get_user_meta( $userid, 'certification_name_'.$unique_string, true) . '">';
			
			$html[] .= '<p>Début de la certification</p>';
			$html[] .= '<input type="text" class="datepickerstartcertification" data-toggle="datepickerstartcertification_" value="' . get_user_meta( $userid, 'datecertificationstart_'.$unique_string, true) . '">';
			
			$html[] .= '<p>Fin de la certification</p>';
			$html[] .= '<input type="text" class="datepickerendcertification" data-toggle="datepickerendcertification_"  value="' . get_user_meta( $userid, 'datecertificationend_'.$unique_string, true) . '">';
			
			$html[] .= '<p>Description des taches</p>';
			$html[] .= '<textarea class="monemploi_add_certification_experiance_description" name="monemploi_add_certification_experiance_description" id="monemploi_add_certification_experiance_description" placeholder="Description des taches" rows="6" cols="75" style="white-space: pre-wrap;">' . get_user_meta( $userid, 'certification_description_'.$unique_string, true) .'</textarea>';
			
			$html[] .= '<div class="" style="position: relative;">';
				$html[] .= '<button class="save-certification-experiance" data-object-id="' . $unique_string . '">Sauvegarder</button>';
				$html[] .= '<div class="delete-certification-experiance" style="position:absolute; top:7.5px; right:0;" data-object-id="' . $unique_string . '"><i class="material-icons">delete</i></div>';
			$html[] .= '</div>';
						
			$html[] .= '</div>';	
			
			$i++;
			
		}
		
	}
	
	foreach($unique_strings as $unique_string)  {
		
		if($is_editing === 'true') {
	
			$html[] = '<div class="'. $unique_string .'" style="padding-bottom: 15px;">';
			
			$date_certification_start_strtotime = strtotime(get_user_meta( $userid, 'datecertificationstart_'.$unique_string, true));
			$date_certification_end_strtotime = strtotime(get_user_meta( $userid, 'datecertificationend_'.$unique_string, true));
			$date_certification_strtotime = ($date_certification_end_strtotime - $date_certification_start_strtotime);
			$date_certification_strtotime_clac = ($date_certification_strtotime/60/60/24/30);
			
			$html[] .= '<div class="certification-experiance-title-company-header" style="padding-bottom: 5px; width: 100%"><span style="font-weight: 600;">'. get_user_meta( $userid, 'certification_title_'.$unique_string, true) .'</span> - <span style="font-style: italic;">'. get_user_meta( $userid, 'certification_name_'.$unique_string, true) .'</span></div>';	
			$html[] .= '<span>' . date('m/Y', $date_certification_start_strtotime) . '</span>';
			$html[] .= ' - ';
			$html[] .= '<span>' . date('m/Y', $date_certification_end_strtotime) . '</span>';
			$html[] .= ' - ';
			$html[] .= '<span>' . round($date_certification_strtotime_clac) . ' Mois</span>';
			$html[] .= '<div class="certification-experiance-description-header" style="white-space: pre-wrap;">'. get_user_meta( $userid, 'certification_description_'.$unique_string, true) .'</div>';
			
			$html[] .= '</div>';	
			
		}
			
	}
	
        wp_send_json ( implode($html) );
}	

/* AJAX action callback */
add_action( 'wp_ajax_monemploi_add_school_experiance', 'monemploi_add_school_experiance' );
add_action( 'wp_ajax_nopriv_monemploi_add_school_experiance', 'monemploi_add_school_experiance' );
function monemploi_add_school_experiance($post) {

	$ramdonstring = generate_secure_string();
	
	$html[] .= '<div class="'. $ramdonstring .'" style="padding-bottom: 15px;">';
	
	$html[] .= '<p>Domaine détude</p>';
	$html[] .= '<input type="text" class="monemploi_add_school_experiance_title" name="monemploi_school_experiance_title" id="monemploi_school_experiance_title">';
	
	$html[] .= '<p>Nom de lecole</p>';
	$html[] .= '<input type="text" class="monemploi_add_school_experiance_name" name="monemploi_school_experiance_name" id="monemploi_school_experiance_name">';
	
	$html[] .= '<p>Début de lecole</p>';
	$html[] .= '<input type="text" class="datepickerstartschool" data-toggle="datepickerstartschool" >';
	
	$html[] .= '<p>Fin de lecole</p>';
	$html[] .= '<input type="text" class="datepickerendschool" data-toggle="datepickerendschool" >';
	
	$html[] .= '<p>Description des taches</p>';
	$html[] .= '<textarea class="monemploi_add_school_experiance_description" name="monemploi_add_school_experiance_description" id="monemploi_add_school_experiance_description" placeholder="Description des taches..." rows="6" cols="75"></textarea>';
	
	$html[] .= '<div class="" style="position: relative;">';
		$html[] .= '<button class="save-school-experiance" data-object-id="' . $ramdonstring . '">Sauvegarder</button>';
		$html[] .= '<div class="delete-school-experiance" style="position: absolute; top: 7.5px; right: 0;" data-object-id="' . $ramdonstring . '"><i class="material-icons">delete</i></div>';
	$html[] .= '</div>';
	
	$html[] .= '</div>';
	
	wp_send_json ( implode($html) );
}	

/* AJAX action callback */
add_action( 'wp_ajax_monemploi_save_school_experiance', 'monemploi_save_school_experiance' );
add_action( 'wp_ajax_nopriv_monemploi_save_school_experiance', 'monemploi_save_school_experiance' );
function monemploi_save_school_experiance($post) {

	$school_title = $_POST['school_title'];
	$school_name = $_POST['school_name'];
	$school_description = $_POST['school_description'];
	$unique = $_POST['unique'];
	$dateschoolstart = $_POST['dateschoolstart'];
	$dateschoolend = $_POST['dateschoolend'];
	
	$userid = get_current_user_id();
	
	$unique_strings = get_user_meta( $userid, 'school_unique', true);

	if (!in_array($unique, $unique_strings)) {
		if(!is_array($unique_strings) ){
		
			update_user_meta( $userid, 'school_unique', array($unique));
		
		} else 	{
	
			array_unshift($unique_strings, $unique);
			update_user_meta( $userid, 'school_unique', $unique_strings);
	    }
	}
		
	update_user_meta( $userid, 'school_title_'.$unique, $school_title);
	update_user_meta( $userid, 'school_name_'.$unique, $school_name);
	update_user_meta( $userid, 'school_description_'.$unique, $school_description);
	update_user_meta( $userid, 'dateschoolstart_'.$unique, $dateschoolstart);
	update_user_meta( $userid, 'dateschoolend_'.$unique, $dateschoolend);
	
	$date_school_start_strtotime = strtotime(get_user_meta( $userid, 'dateschoolstart_'.$unique, true));
	$date_school_end_strtotime = strtotime(get_user_meta( $userid, 'dateschoolend_'.$unique, true));
	$date_school_strtotime = ($date_school_end_strtotime - $date_school_start_strtotime);
	$date_school_strtotime_clac = ($date_school_strtotime/60/60/24/30);
	
	$html[] .= '<div class="school-experiance-title-company-header" style="padding-bottom: 5px; width: 100%"><span style="font-weight: 600;">'. get_user_meta( $userid, 'school_title_'.$unique, true) .'</span> - <span style="font-style: italic;">'. get_user_meta( $userid, 'school_name_'.$unique, true) .'</span></div>';	
	$html[] .= '<span>' . date('m/Y', $date_school_start_strtotime) . '</span>';
	$html[] .= ' - ';
	$html[] .= '<span>' . date('m/Y', $date_school_end_strtotime) . '</span>';
	$html[] .= ' - ';
	$html[] .= '<span>' . round($date_school_strtotime_clac) . ' Mois</span>';
	$html[] .= '<div class="school-experiance-description-header" style="white-space: pre-wrap;">'. get_user_meta( $userid, 'school_description_'.$unique, true) .'</div>';
    
        wp_send_json ( implode($html) );
}

/* AJAX action callback */
add_action( 'wp_ajax_edit_school_experiance', 'edit_school_experiance' );
add_action( 'wp_ajax_nopriv_edit_school_experiance', 'edit_school_experiance' );
function edit_school_experiance($post) {

	$i = 0;

	$is_editing = $_POST['is_editing'];
	
	$userid = get_current_user_id();
	
	$unique_strings = get_user_meta( $userid, 'school_unique', true );
	
	foreach($unique_strings as $unique_string)  {
	
		if($is_editing === 'false') {
	
			$html[] = '<div class="school-experiance-unique-string '. $unique_string .'" style="padding-bottom: 15px;">';
		    	$html[] .= '<div style="position: relative;">';
		    		$html[] .= '<span class="handle-school"><i class="material-icons">menu</i></span>';
					$html[] .= '<div style="position: absolute; top: 0; right: 0;">';
					    	$html[] .= '<span class="sortable-school-up"><i class="material-icons">keyboard_arrow_up</i></span>';
					    	$html[] .= '<span class="wrapper-school"><span class="sortable-school">' . $i . '</span></span>';
					    	$html[] .= '<span class="sortable-school-down"><i class="material-icons">keyboard_arrow_down</i></span>';
			    	    	$html[] .= '</div>';
		    	$html[] .= '</div>';
		    				
			$html[] .= '<p>Domaine detude</p>';
			$html[] .= '<input type="text" class="monemploi_add_school_experiance_title" name="monemploi_school_experiance_title" id="monemploi_school_experiance_title" value="' . get_user_meta( $userid, 'school_title_'.$unique_string, true) . '">';
			
			$html[] .= '<p>Nom de lecole</p>';
			$html[] .= '<input type="text" class="monemploi_add_school_experiance_name" name="monemploi_school_experiance_name" id="monemploi_school_experiance_name" value="' . get_user_meta( $userid, 'school_name_'.$unique_string, true) . '">';
			
			$html[] .= '<p>Début de lecole</p>';
			$html[] .= '<input type="text" class="datepickerstartschool" data-toggle="datepickerstartschool_" value="' . get_user_meta( $userid, 'dateschoolstart_'.$unique_string, true) . '">';
			
			$html[] .= '<p>Fin de lecole</p>';
			$html[] .= '<input type="text" class="datepickerendschool" data-toggle="datepickerendschool_"  value="' . get_user_meta( $userid, 'dateschoolend_'.$unique_string, true) . '">';
			
			
			$html[] .= '<p>Description des taches</p>';
			$html[] .= '<textarea class="monemploi_add_school_experiance_description" name="monemploi_add_school_experiance_description" id="monemploi_add_school_experiance_description" placeholder="Description des taches" rows="6" cols="75" style="white-space: pre-wrap;">' . get_user_meta( $userid, 'school_description_'.$unique_string, true) .'</textarea>';
			
			$html[] .= '<div class="" style="position: relative;">';
				$html[] .= '<button class="save-school-experiance" data-object-id="' . $unique_string . '">Sauvegarder</button>';
				$html[] .= '<div class="delete-school-experiance" style="position:absolute; top:7.5px; right:0;" data-object-id="' . $unique_string . '"><i class="material-icons">delete</i></div>';
			$html[] .= '</div>';
			
			$html[] .= '</div>';	
			
			$i++;
			
		}
		
	}
	
	foreach($unique_strings as $unique_string)  {
		
		if($is_editing === 'true') {
	
			$html[] = '<div class="'. $unique_string .'" style="padding-bottom: 15px;">';
			
			$date_school_start_strtotime = strtotime(get_user_meta( $userid, 'dateschoolstart_'.$unique_string, true));
			$date_school_end_strtotime = strtotime(get_user_meta( $userid, 'dateschoolend_'.$unique_string, true));
			$date_school_strtotime = ($date_school_end_strtotime - $date_school_start_strtotime);
			$date_school_strtotime_clac = ($date_school_strtotime/60/60/24/30);
			
			$html[] .= '<div class="school-experiance-title-company-header" style="padding-bottom: 5px; width: 100%"><span style="font-weight: 600;">'. get_user_meta( $userid, 'school_title_'.$unique_string, true) .'</span> - <span style="font-style: italic;">'. get_user_meta( $userid, 'school_name_'.$unique_string, true) .'</span></div>';	
			$html[] .= '<span>' . date('m/Y', $date_school_start_strtotime) . '</span>';
			$html[] .= ' - ';
			$html[] .= '<span>' . date('m/Y', $date_school_end_strtotime) . '</span>';
			$html[] .= ' - ';
			$html[] .= '<span>' . round($date_school_strtotime_clac) . ' Mois</span>';
			$html[] .= '<div class="school-experiance-description-header" style="white-space: pre-wrap;">'. get_user_meta( $userid, 'school_description_'.$unique_string, true) .'</div>';
			
			$html[] .= '</div>';	
			
		}
			
	}
	
        wp_send_json ( implode($html) );
}	

/* AJAX action callback */
add_action( 'wp_ajax_delete_school_experiance', 'delete_school_experiance' );
add_action( 'wp_ajax_nopriv_delete_school_experiance', 'delete_school_experiance' );
function delete_school_experiance($post) {

	$object_id = $_POST['object_id'];
	
	$userid = get_current_user_id();
	
	$unique_strings = get_user_meta( $userid, 'school_unique', true);
	
	if ( in_array( $object_id, $unique_strings ) ) {
		unset( $unique_strings[array_search( $object_id, $unique_strings )] );
		update_user_meta( $userid, 'school_unique', $unique_strings);
		delete_user_meta( $userid, 'school_title_'.$object_id);
		delete_user_meta( $userid, 'school_name_'.$object_id);	
		delete_user_meta( $userid, 'school_description_'.$object_id);
		delete_user_meta( $userid, 'dateschoolstart_'.$object_id);
		delete_user_meta( $userid, 'dateschoolend_'.$object_id);
	}
				
        wp_send_json ( $object_id );
}			

/**
 * Get the contents of a single comment by its ID.
 * 
 * @param  int $comment_id The ID of the comment to retrieve.
 * 
 * @return string The comment as a string, if present; null if no comment exists.
 */
function wpse120039_get_comment_by_id( $comment_id ) {
    $comment = get_comment( intval( $comment_id ) );

    if ( ! empty( $comment ) ) {
        return $comment->comment_content;
    } else {
        return '';
    }
}	

function repositionArrayElement(array &$array, $key, int $order): void
{
    if(($a = array_search($key, array_keys($array))) === false){
        throw new \Exception("The {$key} cannot be found in the given array.");
    }
    $p1 = array_splice($array, $a, 1);
    $p2 = array_splice($array, 0, $order);
    $array = array_merge($p2, $p1, $array);
}


/* AJAX action callback */
add_action( 'wp_ajax_sortable_reindex', 'sortable_reindex' );
add_action( 'wp_ajax_nopriv_sortable_reindex', 'sortable_reindex' );
function sortable_reindex($post) {

	$start = $_POST['start'];
	$update = $_POST['update'];
	$typeof = $_POST['typeof'];
	
	$userid = get_current_user_id();
	
	if($typeof == 'job') { 
		$unique_strings = get_user_meta( $userid, 'job_unique', true );
	}
	
	if($typeof == 'certification') { 
		$unique_strings = get_user_meta( $userid, 'certification_unique', true );
	}
	
	if($typeof == 'school') { 
		$unique_strings = get_user_meta( $userid, 'school_unique', true );
	}
	
	repositionArrayElement($unique_strings, $start, $update);
	
	if($typeof == 'job') { 
		update_user_meta( $userid, 'job_unique', $unique_strings );
	}
	
	if($typeof == 'certification') { 
		update_user_meta( $userid, 'certification_unique', $unique_strings );
	}
	
	if($typeof == 'school') { 
		update_user_meta( $userid, 'school_unique', $unique_strings );	
	}
	
	wp_send_json('start: '.$start.' update: '.$update.' typeof:'. $typeof);

	
}	

/* AJAX action callback */
add_action( 'wp_ajax_job_draft', 'job_draft' );
add_action( 'wp_ajax_nopriv_job_draft', 'job_draft' );
function job_draft($post) {

	$post_id = $_POST['object_id'];
	
	
	if ( get_post_status( $post_id ) == 'publish' ) {
		// Prepare the post data array for updating
		$postdata = array(
		    'ID'          => $post_id,
		    'post_status' => 'draft' // Set the status to 'draft'
		);
		
		// Update the post in the database
		wp_update_post( $postdata );
	}
	
	$html[] .= '<div class="job-draft-to-publish" data-object-id="' . $post_id . '">';
		$html[] .= '<span class="material-icons">publish</span>';
	$html[] .= '</div>';
	
	wp_send_json( implode($html) );
	
}	

/* AJAX action callback */
add_action( 'wp_ajax_job_draft_to_publish', 'job_draft_to_publish' );
add_action( 'wp_ajax_nopriv_job_draft_to_publish', 'job_draft_to_publish' );
function job_draft_to_publish($post) {

	$post_id = $_POST['object_id'];
	
	
	if ( get_post_status( $post_id ) == 'draft' || get_post_status( $post_id ) == 'future' ) {
		// Prepare the post data array for updating
		$postdata = array(
		    'ID'          => $post_id,
		    'post_status' => 'publish' // Set the status to 'draft'
		);
		
		// Update the post in the database
		wp_update_post( $postdata );
	}
	
	$html[] .= '<div class="draft-job-emplois" data-object-id="' . $post_id . '">';
		$html[] .= '<span class="material-icons">archive</span>';
	$html[] .= '</div>';
	
	$end_job_scheduled = get_post_meta( $post_id, 'my_end_job_scheduled_key', true);
	$strtotime_now = current_time('timestamp');
	
	if($strtotime_now >= $end_job_scheduled){
		update_post_meta( $post_id, 'my_end_job_scheduled_key', '');
	}
	
	wp_send_json( implode($html) );
	
}

/* AJAX action callback */
add_action( 'wp_ajax_job_delete', 'job_delete' );
add_action( 'wp_ajax_nopriv_job_delete', 'job_delete' );
function job_delete($post) {

	$post_id = $_POST['object_id'];
	
 	wp_trash_post( $post_id );
 		
	$html[] .= '<header class="entry-header">';
		$html[] .= '<h3>' . get_the_title($post_id) . '</h3>';
	$html[] .= '</header>';
	$html[] .= '<br>';
	$html[] .= '<h6>Votre emploi à été supprimer.</h6>';
	$html[] .= '<br>';
	$html[] .= '<button><a href="'. get_site_url() .'/tableaux-de-bord/">Aller au tableau de bord</a></button>';
		
	wp_send_json( implode($html) );
	
}

/* AJAX action callback */
add_action( 'wp_ajax_comment_candidacy_reply', 'comment_candidacy_reply' );
add_action( 'wp_ajax_nopriv_comment_candidacy_reply', 'comment_candidacy_reply' );
function comment_candidacy_reply($post) {

	$post_id = $_POST['object_id'];
	$count_comments = $_POST['object_count'];
	$current_user_id = get_current_user_id();
	
	// Arguments array for get_comments()
	$args = array(
	    'post_id' => $post_id, // The ID of the post
	    'status'  => 'approve', // Only fetch approved comments
	    'order'   => 'ASC', // Order comments from oldest to newest
	    'parent'  => '0' // Only fetch top-level comments (optional, for threaded comments)
	);
	
	// Get the comments
	$comments = get_comments( $args );
	
	$i = 1;
	
	$x = 0;
	
	foreach ( $comments as $response ) {
	
		if($count_comments < count($comments) && $i == count($comments)) {
		
			if($response->user_id != $current_user_id) {
	 
			 	$ramdonstring = generate_secure_string();
				
				$html[] = '<div class="candidacy-response-cards-wrapper ' . $ramdonstring . '">';
					$html[] .= '<div class="ns-row">';
						$html[] .= '<div class="ns-col-sm-9">';
							$html[] .= '<div class="response-head" style="display: flex;">';
								$html[] .= '<h3 class="ticket-head" id="response-' . esc_attr($x) . '" style="width: calc(100% - 25px);">';
									$userid = $response->user_id; 
									$user_meta = get_userdata($userid);
									$user_role = $user_meta->roles[0];
									um_fetch_user( $userid );
									if($user_role == 'employeur'){
										$html[] .= '<a href="' . get_site_url() . '/employeur/?user=' . $user_meta->user_login . '">' . $response->comment_author . '</a> - ' . um_user('name_org'). '';
										um_reset_user();
									} elseif($user_role == 'employer'){
										$html[] .= '<a href="' . get_site_url() . '/employee/?user=' . $user_meta->user_login . '">' . $response->comment_author . '</a> - ' . um_user('name_org'). '';
										um_reset_user(); 
									}
								$html[] .= '</h3>';
								if (intval($response->user_id) == intval($current_user_id)){
									$html[] .= '<div class="delete-comment-candidacy" style="width: 25px; padding-top: 25px;" data-object-id="' . $response->comment_ID . '" data-object-string="' . $ramdonstring . '">';
										$html[] .= '<i class="material-icons">';
											$html[] .= 'delete';
										$html[] .= '</i>';
									$html[] .= '</div>';
								}
							$html[] .= '</div>';
						$html[] .= '</div>';
						$html[] .= '<div class="ns-col-sm-3 response-dates">';
							$html[] .= '<a href="#response-' . esc_attr($x) . '" class="response-bookmark ns-small">' . date( 'd M Y h:iA', strtotime( $response->comment_date ) ) . '</a>';
						$html[] .= '</div>';
					$html[] .= '</div>';
					$html[] .= '<div class="ticket-response">';
						$html[] .= wpautop( $response->comment_content );
					$html[] .= '</div>';
					
				$html[] .= '</div>';
				
			}
			
		}
		
		$i++;
		$x++;
	 
	}
		
	wp_send_json( implode($html) );
	
}

?>