<?php

function monemploi_home() {

	echo '<h4>MonEmploi.net est un site québécois de recherche d&#39;emploi pour la province de Québec.</h4>';
	
	$get_args_emploi = array( 
		'post_type' => 'emploi',
		'posts_per_page' => -1,
		'orderby' => 'date',
		'order' => 'DESC'
	); 
	
	$get_emploi = get_posts( $get_args_emploi );
	
	echo '<h2>Avec plus de <span class="count-target-emploi">' . count($get_emploi) . '</span> emplois disponible.</h2>';
	
	$args_um_employeur = array(
		'role__in' => array( 'um_employeur' ),
		'orderby' => 'date',
		'order'   => 'DESC'
	);
	
	$um_employeurs = get_users( $args_um_employeur );
	
	echo '<h2>Avec plus de <span class="count-target-employeurs">' . count($um_employeurs) . '</span> employeurs disponible.</h2>';
	
	$args_employer = array(
		'role__in' => array( 'employer' ),
		'orderby' => 'date',
		'order'   => 'DESC'
	);
	
	$employer = get_users( $args_employer );
	
	echo '<h2>Avec plus de <span class="count-target-employer">' . count($employer) . '</span> de candidats disponible.</h2>';
	
	$get_args_candidacys = array( 
		'post_type' => 'candidacy',
		'posts_per_page' => -1,
		'orderby' => 'date',
		'order' => 'DESC',
	); 
	
	$get_candidacys = get_posts( $get_args_candidacys );
	
	echo '<h2>Avec plus de <span class="count-target-candidacys">' . count($get_candidacys) . '</span> demande demplois de fait.</h2>';
	
	$candidacy_status_count = 0;
	
	foreach($get_candidacys as $get_candidacy){
	
		if(get_post_meta($get_candidacy->ID, 'candidacy_status_', true) == 3) {
			$candidacy_status_count++;
		} 
	
	}
	
	echo '<h2>Avec plus de <span class="count-target-candidacys-embaucher">' . $candidacy_status_count . '</span> candidats embaucher.</h2>';
	
	$args_avis = array( 
		'post_type' => 'avis',
		'posts_per_page' => -1,
		'orderby' => 'date',
		'order' => 'DESC',
	); 
	
	$get_avis_  = get_posts( $args_avis );
	
	$um_employeur_count = 0;
	
	foreach($get_avis_ as $get_avis){
	
		if(get_user_meta($get_avis->ID, 'role_key', true) == 'um_employeur'){
		
			$um_employeur_count++;
		
		}
	
	}

	echo '<h2>Avec plus de <span class="count-target-avis-employeur">' . $um_employeur_count . '</span> avis sur les employeurs.</h2>';
	
	$args_avis_employer = array( 
		'post_type' => 'avis',
		'posts_per_page' => -1,
		'orderby' => 'date',
		'order' => 'DESC',
	); 
	
	$get_avis_employer  = get_posts( $args_avis_employer );
	
	$employer_count = 0;
	
	foreach($get_avis_employer as $get_avis){
	
		if(get_user_meta($get_avis->ID, 'role_key', true) == 'employer'){
		
			$employer_count++;
		
		}
	
	}
	
	echo '<h2>Avec plus de <span class="count-target-avis-employer">' . $employer_count . '</span> avis sur les employés.</h2>';
	
}
add_shortcode('monemploi-home', 'monemploi_home');

?>