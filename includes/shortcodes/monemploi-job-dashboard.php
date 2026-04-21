<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function monemploi_job_dashboard() {

	$current_user = wp_get_current_user();
	$user_meta = get_userdata($current_user->ID);
	$user_role = $user_meta->roles[0];

	if($user_role == 'employeur' || $user_role == 'administrator'){

		$get_args_emploi = array( 
			'post_type' => 'emploi',
			'posts_per_page' => -1,
			'post_status' => array('publish', 'draft', 'future'),
			'orderby' => 'date',
			'order' => 'DESC'
		); 
	
	} else {
	
		$get_args_emploi = array( 
			'post_type' => 'emploi',
			'posts_per_page' => -1,
			'post_status' => array('publish'),
			'orderby' => 'date',
			'order' => 'DESC'
		);
		 
	}
	
	$get_emplois = get_posts( $get_args_emploi );
	
	$x = 0;
	foreach ($get_emplois as $post) {
		$city = get_post_meta( $post->ID, 'my_city_key', true );
		$get_city_array[$city][$x] = array('ID' => $post->ID, 'author' => $post->post_author, 'city' => $city);	
		$i++;	
	}
	
	ksort($get_city_array);
		
	?><form action="" method="GET">
	    <select name="city" id="city_filter">
	        <option value="">Tout les villes</option>
	        <?php foreach ($get_city_array as $key => $values) { ?>
	        	<?php if($key == $_GET['city']) { ?>
	        		<option value="<?php echo $key ?>" selected><?php echo $key ?></option>
	    		<?php } else { ?>
	        		<option value="<?php echo $key ?>"><?php echo $key ?></option>
	    		<?php } ?>
	        <?php }  ?>
	    </select>
	    <?php if(is_user_logged_in()) { ?>
	    	<input type="number" id="km_filter" name="km" class="km_filter" min="0" max="1000" placeholder="KM" value="">
	    <?php } ?>
	    <input type="number" id="days_filter" name="days" class="days_filter" min="0" max="1000" placeholder="Jour" value="">
	    <input type="number" id="salary_filter" name="salary" class="salary_filter" min="0" max="1000" placeholder="Salaire" value="<?php echo $_GET['salary']; ?>">
		<?php $education_terms = get_terms( array(
			'taxonomy' => 'education',
			'orderby' => 'term_id',
			'order' => 'ASC', // or ASC
			'hide_empty' => false,
		) ); ?>
		<?php if ( $education_terms ) {
			echo '<select name="school" id="school_filter">';
			echo '<option value="">'. esc_html__( 'Niveau d&#8216;éducation' , 'monemploi' ) .'</option>';
			foreach ( $education_terms as $education_term ) {
				if($_GET['school'] == $education_term->term_id){
					echo '<option value="'. $education_term->term_id .'" selected>'. $education_term->name.'</option>';
				} else {
					echo '<option value="'. $education_term->term_id .'">'. $education_term->name.'</option>';
				}
			}
			echo '</select>';
		} ?>
		<select name="experience" id="experience_filter">
			<?php $annees_dexperience = $_GET['experience']; ?>
			<?php if($annees_dexperience == ''){ ?>
				<option value="" selected><?php echo esc_html( 'Choisissez un année d&#8216;expérience' , 'monemploi' ); ?></option>
			<?php } else { ?>
				<option value=""><?php echo esc_html( 'Choisissez un année d&#8216;expérience' , 'monemploi' ); ?></option>
			<?php } ?>
			<?php if($annees_dexperience == 1){ ?>
				<option value="1" selected>Auccun</option>
			<?php } else { ?>
				<option value="1">Auccun</option>
			<?php } ?>
			<?php if($annees_dexperience == 2){ ?>
				<option value="2" selected>1 an</option>
			<?php } else { ?>
				<option value="2">1 an</option>
			<?php } ?>
			<?php if($annees_dexperience == 3){ ?>
				<option value="3" selected>2-3 an</option>
			<?php } else { ?>
				<option value="3">2-3 an</option>
			<?php } ?>
			<?php if($annees_dexperience == 4){ ?>
				<option value="4" selected>4-5 ans</option>
			<?php } else { ?>
				<option value="4">4-5 ans</option>
			<?php } ?>
			<?php if($annees_dexperience == 5){ ?>
				<option value="5" selected>6-9 ans</option>
			<?php } else { ?>
				<option value="5">6-9 ans</option>
			<?php }	 ?>
			<?php if($annees_dexperience == 6){ ?>
				<option value="6" selected>10 ans+</option>
			<?php } else { ?>
				<option value="6">10 ans+</option>
			<?php } ?>
		</select>
		<input type="number" id="hours_filter" name="hours" class="hours_filter" min="0" max="1000" placeholder="Heures" value="<?php echo $_GET['hours']; ?>">
		<select name="type" id="type_filter">
			<?php $type_demploi = $_GET['type']; ?>
			<?php if($type_demploi == ''){ ?>
				<option value="" selected><?php echo esc_html( 'type d&#8216;emploi' , 'monemploi' ); ?></option>
			<?php } else { ?>
				<option value="" ><?php echo esc_html( 'Type d&#8216;emploi' , 'monemploi' ); ?></option>
			<?php } ?>
			<?php if($type_demploi == 1){ ?>
				<option value="1" selected>Temps plein</option>
			<?php } else { ?>
				<option value="1">Temps plein</option>
			<?php } ?>
			<?php if($type_demploi == 2){ ?>
				<option value="2"selected>Temps partiel</option>
			<?php } else { ?>
				<option value="2">Temps partiel</option>
			<?php } ?>
		</select>
		<select name="activite" id="activite_filter">
	                <?php $activite_professionnelle = $_GET['activite']; ?>
			<?php if($activite_professionnelle == ''){ ?>
				<option value="" selected><?php echo esc_html( 'Type d&#8216;activité professionnelle' , 'monemploi' ); ?></option>
			<?php } else { ?>
				<option value="" ><?php echo esc_html( 'Type d&#8216;activité professionnelle' , 'monemploi' ); ?></option>
			<?php } ?>
			<?php if($activite_professionnelle == 1){ ?>
				<option value="1" selected>Travail en présentiel</option>
			<?php } else { ?>
				<option value="1">Travail en présentiel</option>
			<?php } ?>
			<?php if($activite_professionnelle == 2){ ?>
				<option value="2"selected>Télétravail</option>
			<?php } else { ?>
				<option value="2">Télétravail</option>
			<?php } ?>
			<?php if($activite_professionnelle == 3){ ?>
				<option value="3"selected>Mode hybride</option>
			<?php } else { ?>
				<option value="3">Mode hybride</option>
			<?php } ?>
		</select>
		<select name="horaire" id="horaire_filter">
	        	<?php $type_dhoraire = $_GET['horaire']; ?>
			<?php if($type_dhoraire == ''){ ?>
				<option value="" selected><?php echo esc_html( 'Type d&#8216;horaire' , 'monemploi' ); ?></option>
			<?php } else { ?>
				<option value=""><?php echo esc_html( 'Type d&#8216;horaire' , 'monemploi' ); ?></option>
			<?php } ?>
			<?php if($type_dhoraire == 1){ ?>
				<option value="1" selected>Jour</option>
			<?php } else { ?>
				<option value="1">Jour</option>
			<?php } ?>
			<?php if($type_dhoraire == 2){ ?>
				<option value="2" selected>Soir</option>
			<?php } else { ?>
				<option value="2">Soir</option>
			<?php } ?>
			<?php if($type_dhoraire == 3){ ?>
				<option value="3" selected>Nuit</option>
			<?php } else { ?>
				<option value="3">Nuit</option>
			<?php } ?>
		</select>
	    	<select name="disponibilites" id="disponibilites_filter">
			<?php $type_disponibilites = $_GET['disponibilites']; ?>
			<?php if($type_disponibilites == ''){ ?>
				<option value="" selected><?php echo esc_html( 'Type de disponibilités' , 'monemploi' ); ?></option>
			<?php } else { ?>
				<option value=""><?php echo esc_html( 'Type de disponibilités' , 'monemploi' ); ?></option>
			<?php } ?>
			<?php if($type_disponibilites == 1){ ?>
				<option value="1" selected>Semaine</option>
			<?php } else { ?>
				<option value="1">Semaine</option>
			<?php } ?>
			<?php if($type_disponibilites == 2){ ?>
				<option value="2" selected>Fin de semaine</option>
			<?php } else { ?>
				<option value="2">Fin de semaine</option>
			<?php } ?>
		</select>
		<select name="duree" id="duree_filter">
	        	<?php $duree_emploi = $_GET['duree']; ?>
			<?php if($duree_emploi == ''){ ?>
				<option value="" selected><?php echo esc_html( 'Durée de l&#8216;emploi' , 'monemploi' ); ?></option>
			<?php } else { ?>
				<option value=""><?php echo esc_html( 'Durée de l&#8216;emploi' , 'monemploi' ); ?></option>
			<?php } ?>
			<?php if($duree_emploi == 1){ ?>
				<option value="1" selected>Permanent</option>
			<?php } else { ?>
				<option value="1">Permanent</option>
			<?php } ?>
			<?php if($duree_emploi == 2){ ?>
				<option value="2" selected>Contrat</option>
			<?php } else { ?>
				<option value="2">Contrat</option>
			<?php } ?>
			<?php if($duree_emploi == 3){ ?>
				<option value="3" selected>Sur appel</option>
			<?php } else { ?>
				<option value="3">Sur appel</option>
			<?php } ?>
		</select>
		<select name="permis" id="permis_filter">
	        	<?php $permis_conduire = $_GET['permis'];  ?>
			<?php if($permis_conduire == ''){ ?>
				<option value="" selected><?php echo esc_html( 'Permis de conduire' , 'monemploi' ); ?></option>
			<?php } else { ?>
				<option value=""><?php echo esc_html( 'Permis de conduire' , 'monemploi' ); ?></option>
			<?php } ?>
			<?php if($permis_conduire == 1){ ?>
				<option value="1" selected>Non</option>
			<?php } else { ?>
				<option value="1">Non</option>
			<?php } ?>
			<?php if($permis_conduire == 2){ ?>
				<option value="2" selected>Oui</option>
			<?php } else { ?>
				<option value="2">Oui</option>
			<?php } ?>
		</select>
		<select name="voiture" id="voiture_filter">
                	<?php $besoin_voiture = $_GET['voiture']; ?>
			<?php if($besoin_voiture == ''){ ?>
				<option value="" selected><?php echo esc_html( 'Voiture' , 'monemploi' ); ?></option>
			<?php } else { ?>
				<option value=""><?php echo esc_html( 'Voiture' , 'monemploi' ); ?></option>
			<?php } ?>
			<?php if($besoin_voiture == 1){ ?>
				<option value="1" selected>Non</option>
			<?php } else { ?>
				<option value="1">Non</option>
			<?php } ?>
			<?php if($besoin_voiture == 2){ ?>
				<option value="2" selected>Oui</option>
			<?php } else { ?>
				<option value="2">Oui</option>
			<?php } ?>
		</select>
		</br>
		<select name="km_sort" id="km_sort" class="km_sort">
			<?php $km_sort = $_GET['km_sort']; ?>
			<?php if($km_sort == ''){ ?>
				<option value="" selected><?php echo esc_html( 'Trier par km' , 'monemploi' ); ?></option>
			<?php } else { ?>
				<option value=""><?php echo esc_html( 'Trier par km' , 'monemploi' ); ?></option>
			<?php } ?>
			<?php if($km_sort == 1){ ?>
				<option value="1" selected>Ascendants</option>
			<?php } else { ?>
				<option value="1">Ascendants</option>
			<?php } ?>
			<?php if($km_sort == 2){ ?>
				<option value="2" selected>Descendants</option>
			<?php } else { ?>
				<option value="2">Descendants</option>
			<?php } ?>
		</select>
		<select name="title_sort" id="title_sort" class="title_sort">
			<?php $title_sort = $_GET['title_sort']; ?>
			<?php if($title_sort == ''){ ?>
				<option value="" selected><?php echo esc_html( 'Trier par titre' , 'monemploi' ); ?></option>
			<?php } else { ?>
				<option value=""><?php echo esc_html( 'Trier par titre' , 'monemploi' ); ?></option>
			<?php } ?>
			<?php if($title_sort == 1){ ?>
				<option value="1" selected>Ascendants</option>
			<?php } else { ?>
				<option value="1">Ascendants</option>
			<?php } ?>
			<?php if($title_sort == 2){ ?>
				<option value="2" selected>Descendants</option>
			<?php } else { ?>
				<option value="2">Descendants</option>
			<?php } ?>
		</select>
		<select name="date_sort" id="date_sort" class="date_sort">
			<?php $date_sort = $_GET['date_sort']; ?>
			<?php if($date_sort == ''){ ?>
				<option value="" selected><?php echo esc_html( 'Trier par date' , 'monemploi' ); ?></option>
			<?php } else { ?>
				<option value=""><?php echo esc_html( 'Trier par date' , 'monemploi' ); ?></option>
			<?php } ?>
			<?php if($date_sort == 1){ ?>
				<option value="1" selected>Ascendants</option>
			<?php } else { ?>
				<option value="1">Ascendants</option>
			<?php } ?>
			<?php if($date_sort == 2){ ?>
				<option value="2" selected>Descendants</option>
			<?php } else { ?>
				<option value="2">Descendants</option>
			<?php } ?>
		</select>
		<?php if($user_role == 'employeur') { ?>
			<select name="status_sort" id="status_sort" class="status_sort">
				<?php $status_sort = $_GET['status_sort']; ?>
				<?php if($status_sort == ''){ ?>
					<option value="" selected><?php echo esc_html( 'Trier par status' , 'monemploi' ); ?></option>
				<?php } else { ?>
					<option value=""><?php echo esc_html( 'Trier par status' , 'monemploi' ); ?></option>
				<?php } ?>
				<?php if($status_sort == 1){ ?>
					<option value="1" selected>Publier</option>
				<?php } else { ?>
					<option value="1">Publier</option>
				<?php } ?>
				<?php if($status_sort == 2){ ?>
					<option value="2" selected>Programmer</option>
				<?php } else { ?>
					<option value="2">Programmer</option>
				<?php } ?>
				<?php if($status_sort == 3){ ?>
					<option value="3" selected>Brouillon</option>
				<?php } else { ?>
					<option value="3">Brouillon</option>
				<?php } ?>
			</select>
		<?php } ?>
	    <input type="submit" value="Filter">
	</form>
	<?php

	$i = 0;
	
	$get_user_by_username = wp_get_current_user();
	$userid = $get_user_by_username->ID;
	$user_meta = get_userdata($userid);
	$user_role = $user_meta->roles[0];

	if($user_role == 'employeur'){
		
		$status_sort = $_GET['status_sort'];
		$title_sort = $_GET['title_sort'];
		$date_sort = $_GET['date_sort'];		
		if($status_sort == 1) {
			if($title_sort == 1) {
			        $get_jobs_args = array(
			            'post_type' => 'emploi',
			            'post_status'    => array('publish'),
			            'posts_per_page' => -1,
			            'orderby'	     => 'title',
			            'order'	=> 'ASC'
			        );
		        } elseif($title_sort == 2) {
			        $get_jobs_args = array(
			            'post_type' => 'emploi',
			            'post_status'    => array('publish'),
			            'posts_per_page' => -1,
			            'orderby'	     => 'title',
			            'order'	=> 'DESC'
			        );
		        } elseif($date_sort == 1) {
			        $get_jobs_args = array(
			            'post_type' => 'emploi',
			            'post_status'    => array('publish'),
			            'posts_per_page' => -1,
			            'orderby'	     => 'date',
			            'order'	=> 'ASC'
			        );
		        } elseif($date_sort == 2) {
			        $get_jobs_args = array(
			            'post_type' => 'emploi',
			            'post_status'    => array('publish'),
			            'posts_per_page' => -1,
			            'orderby'	     => 'date',
			            'order'	=> 'DESC'
			        );
		        } else {
			        $get_jobs_args = array(
			            'post_type' => 'emploi',
			            'post_status'    => array('publish'),
			            'posts_per_page' => -1,
			            'orderby'	     => 'date',
			            'order'	=> 'DESC'
			        );
		        }
		} elseif($status_sort == 2) {
			if($title_sort == 1) {
			        $get_jobs_args = array(
			            'post_type' => 'emploi',
			            'post_status'    => array('future'),
			            'posts_per_page' => -1,
			            'orderby'	     => 'title',
			            'order'	=> 'ASC'
			        );
		        } elseif($title_sort == 2) {
			        $get_jobs_args = array(
			            'post_type' => 'emploi',
			            'post_status'    => array('future'),
			            'posts_per_page' => -1,
			            'orderby'	     => 'title',
			            'order'	=> 'DESC'
			        );
		        } elseif($date_sort == 1) {
			        $get_jobs_args = array(
			            'post_type' => 'emploi',
			            'post_status'    => array('future'),
			            'posts_per_page' => -1,
			            'orderby'	     => 'date',
			            'order'	=> 'ASC'
			        );
		        } elseif($date_sort == 2) {
			        $get_jobs_args = array(
			            'post_type' => 'emploi',
			            'post_status'    => array('future'),
			            'posts_per_page' => -1,
			            'orderby'	     => 'date',
			            'order'	=> 'DESC'
			        );
		        } else {
			        $get_jobs_args = array(
			            'post_type' => 'emploi',
			            'post_status'    => array('future'),
			            'posts_per_page' => -1,
			            'orderby'	     => 'date',
			            'order'	=> 'DESC'
			        );
		        }
		} elseif($status_sort == 3) {
			if($title_sort == 1) {
			        $get_jobs_args = array(
			            'post_type' => 'emploi',
			            'post_status'    => array('draft'),
			            'posts_per_page' => -1,
			            'orderby'	     => 'title',
			            'order'	=> 'ASC'
			        );
		        } elseif($title_sort == 2) {
			        $get_jobs_args = array(
			            'post_type' => 'emploi',
			            'post_status'    => array('draft'),
			            'posts_per_page' => -1,
			            'orderby'	     => 'title',
			            'order'	=> 'DESC'
			        );
		        } elseif($date_sort == 1) {
			        $get_jobs_args = array(
			            'post_type' => 'emploi',
			            'post_status'    => array('draft'),
			            'posts_per_page' => -1,
			            'orderby'	     => 'date',
			            'order'	=> 'ASC'
			        );
		        } elseif($date_sort == 2) {
			        $get_jobs_args = array(
			            'post_type' => 'emploi',
			            'post_status'    => array('draft'),
			            'posts_per_page' => -1,
			            'orderby'	     => 'date',
			            'order'	=> 'DESC'
			        );
		        } else {
			        $get_jobs_args = array(
			            'post_type' => 'emploi',
			            'post_status'    => array('draft'),
			            'posts_per_page' => -1,
			            'orderby'	     => 'date',
			            'order'	=> 'DESC'
			        );
		        }	        
		} elseif($status_sort == '') {
			if($title_sort == 1) {
			        $get_jobs_args = array(
			            'post_type' => 'emploi',
			            'post_status'    => array('publish', 'future', 'draft'),
			            'posts_per_page' => -1,
			            'orderby'	     => 'title',
			            'order'	=> 'ASC'
			        );
		        } elseif($title_sort == 2) {
			        $get_jobs_args = array(
			            'post_type' => 'emploi',
			            'post_status'    => array('publish', 'future', 'draft'),
			            'posts_per_page' => -1,
			            'orderby'	     => 'title',
			            'order'	=> 'DESC'
			        );
		        } elseif($date_sort == 1) {
			        $get_jobs_args = array(
			            'post_type' => 'emploi',
			            'post_status'    => array('publish', 'future', 'draft'),
			            'posts_per_page' => -1,
			            'orderby'	     => 'date',
			            'order'	=> 'ASC'
			        );
		        } elseif($date_sort == 2) {
			        $get_jobs_args = array(
			            'post_type' => 'emploi',
			            'post_status'    => array('publish', 'future', 'draft'),
			            'posts_per_page' => -1,
			            'orderby'	     => 'date',
			            'order'	=> 'DESC'
			        );
		        } else {
			        $get_jobs_args = array(
			            'post_type' => 'emploi',
			            'post_status'    => array('publish', 'future', 'draft'),
			            'posts_per_page' => -1,
			            'orderby'	     => 'date',
			            'order'	=> 'DESC'
			        );
		        }
		}
	        
	         if ( $_GET['city'] != ''  ) {
	        	$get_jobs_args['meta_query'][] = array(
		        	array(
			            'key'     => 'my_city_key',
			            'value'   => sanitize_text_field( $_GET['city'] ),
			            'compare' => '=',
			        )
			);
		 }
		 
		if ( $_GET['salary'] != ''  ) {
	        	$get_jobs_args['meta_query'][] = array(
			 	array(
			            'key'     => 'my_salaire_key',
			            'value'   => intval( $_GET['salary'] ),
			            'compare' => '>=',
			        )
			);
		 }
		 
		 if ( $_GET['experience'] != ''  ) {
	        	$get_jobs_args['meta_query'][] = array(
			      	array(
			            'key'     => 'my_annees_dexperience_key',
			            'value'   => intval( $_GET['experience'] ),
			            'compare' => '=',
			        )
			);
		 }
		 
		 if ( $_GET['hours'] != ''  ) {
	        	$get_jobs_args['meta_query'][] = array(
			       	array(
			            'key'     => 'my_add_heures_key',
			            'value'   => intval( $_GET['hours'] ),
			            'compare' => '<=',
			        )
			);
		 }

		 if ( $_GET['type'] != ''  ) {
	        	$get_jobs_args['meta_query'][] = array(
			        array(
			            'key'     => 'my_type_demploi_key',
			            'value'   => intval( $_GET['type'] ),
			            'compare' => '=',
			        )
			);
		 }
		 
		 if ( $_GET['horaire'] != ''  ) {
	        	$get_jobs_args['meta_query'][] = array(
			        array(
			            'key'     => 'my_type_dhoraire_key',
			            'value'   => intval( $_GET['horaire'] ),
			            'compare' => '=',
			        )
			);
		 }
		 
		if ( $_GET['activite'] != ''  ) {
	        	$get_jobs_args['meta_query'][] = array(
			        array(
			            'key'     => 'my_activite_professionnelle_key',
			            'value'   => intval( $_GET['activite'] ),
			            'compare' => '=',
			        )
			);
		}
		 
		if ( $_GET['disponibilites'] != '' ) {
			if($_GET['disponibilites'] == 1) {
		        	$get_jobs_args['meta_query'][] = array(
				        array(
				            'key'     => 'my_disponibilites1_key',
				            'value'   => intval( '1' ),
				            'compare' => '=',
				        )
				);	
			} elseif ($_GET['disponibilites'] == 2) {
		        	$get_jobs_args['meta_query'][] = array(
				        array(
				            'key'     => 'my_disponibilites2_key',
				            'value'   => intval( '1' ),
				            'compare' => '=',
				        )
				);	
			}
		 }

		 if ( $_GET['duree'] != ''  ) {
	        	$get_jobs_args['meta_query'][] = array(
			        array(
			            'key'     => ' my_duree_emploi_key',
			            'value'   => intval( $_GET['duree'] ),
			            'compare' => '=',
			        )
			);
		 }

		 if ( $_GET['permis'] != ''  ) {
	        	$get_jobs_args['meta_query'][] = array(
			        array(
			            'key'     => 'my_permis_conduire_key',
			            'value'   => intval( $_GET['permis'] ),
			            'compare' => '=',
			        )
			);
		 }
		 
		 if ( $_GET['voiture'] != ''  ) {
	        	$get_jobs_args['meta_query'][] = array(
			        array(
			            'key'     => 'my_besoin_voiture_key',
			            'value'   => intval( $_GET['voiture'] ),
			            'compare' => '=',
			        )
			);
		 }
		 
		 if ( $_GET['school'] != ''  ) {
	        	$get_jobs_args['meta_query'][] = array(
			        array(
			            'key'     => 'my_education_key',
			            'value'   => intval( $_GET['school'] ),
			            'compare' => '=',
			        )
			);
		 }
    	        
        } else {
        
		$title_sort = $_GET['title_sort'];
		if($title_sort == 1) {
		        $get_jobs_args = array(
		            'post_type' => 'emploi',
		            'post_status'    => array('publish'),
		            'posts_per_page' => -1,
		            'orderby'	     => 'title',
		            'order'	=> 'ASC'
		        );
	        } elseif($title_sort == 2) {
		        $get_jobs_args = array(
		            'post_type' => 'emploi',
		            'post_status'    => array('publish'),
		            'posts_per_page' => -1,
		            'orderby'	     => 'title',
		            'order'	=> 'DESC'
		        );
	        } 
	        
	       	$date_sort = $_GET['date_sort'];
	        if($date_sort == 1) {
		        $get_jobs_args = array(
		            'post_type' => 'emploi',
		            'post_status'    => array('publish'),
		            'posts_per_page' => -1,
		            'orderby'	     => 'date',
		            'order'	=> 'ASC'
		        );
	        } elseif($date_sort == 2) {
		        $get_jobs_args = array(
		            'post_type' => 'emploi',
		            'post_status'    => array('publish'),
		            'posts_per_page' => -1,
		            'orderby'	     => 'date',
		            'order'	=> 'DESC'
		        );
	        }
	        
	        if($title_sort == '' && $date_sort == '') {
		        $get_jobs_args = array(
		            'post_type' => 'emploi',
		            'post_status'    => array('publish'),
		            'posts_per_page' => -1,
		            'orderby'	     => 'date',
		            'order'	=> 'DESC'
		        );
	        }
	        
	         if ( $_GET['city'] != ''  ) {
	        	$get_jobs_args['meta_query'][] = array(
		        	array(
			            'key'     => 'my_city_key',
			            'value'   => sanitize_text_field( $_GET['city'] ),
			            'compare' => '=',
			        )
			);
		 }
		 
		if ( $_GET['salary'] != ''  ) {
	        	$get_jobs_args['meta_query'][] = array(
			 	array(
			            'key'     => 'my_salaire_key',
			            'value'   => intval( $_GET['salary'] ),
			            'compare' => '>=',
			        )
			);
		 }
		 
		 if ( $_GET['experience'] != ''  ) {
	        	$get_jobs_args['meta_query'][] = array(
			      	array(
			            'key'     => 'my_annees_dexperience_key',
			            'value'   => intval( $_GET['experience'] ),
			            'compare' => '=',
			        )
			);
		 }
		 
		 if ( $_GET['hours'] != ''  ) {
	        	$get_jobs_args['meta_query'][] = array(
			       	array(
			            'key'     => 'my_add_heures_key',
			            'value'   => intval( $_GET['hours'] ),
			            'compare' => '<=',
			        )
			);
		 }

		 if ( $_GET['type'] != ''  ) {
	        	$get_jobs_args['meta_query'][] = array(
			        array(
			            'key'     => 'my_type_demploi_key',
			            'value'   => intval( $_GET['type'] ),
			            'compare' => '=',
			        )
			);
		 }
		 
		 if ( $_GET['horaire'] != ''  ) {
	        	$get_jobs_args['meta_query'][] = array(
			        array(
			            'key'     => 'my_type_dhoraire_key',
			            'value'   => intval( $_GET['horaire'] ),
			            'compare' => '=',
			        )
			);
		 }
		 
		if ( $_GET['activite'] != ''  ) {
	        	$get_jobs_args['meta_query'][] = array(
			        array(
			            'key'     => 'my_activite_professionnelle_key',
			            'value'   => intval( $_GET['activite'] ),
			            'compare' => '=',
			        )
			);
		 }

		if ( $_GET['disponibilites'] != '' ) {
			if($_GET['disponibilites'] == 1) {
		        	$get_jobs_args['meta_query'][] = array(
				        array(
				            'key'     => 'my_disponibilites1_key',
				            'value'   => intval( '1' ),
				            'compare' => '=',
				        )
				);	
			} elseif ($_GET['disponibilites'] == 2) {
		        	$get_jobs_args['meta_query'][] = array(
				        array(
				            'key'     => 'my_disponibilites2_key',
				            'value'   => intval( '1' ),
				            'compare' => '=',
				        )
				);	
			}
		 }

		 if ( $_GET['duree'] != ''  ) {
	        	$get_jobs_args['meta_query'][] = array(
			        array(
			            'key'     => ' my_duree_emploi_key',
			            'value'   => intval( $_GET['duree'] ),
			            'compare' => '=',
			        )
			);
		 }

		 if ( $_GET['permis'] != ''  ) {
	        	$get_jobs_args['meta_query'][] = array(
			        array(
			            'key'     => 'my_permis_conduire_key',
			            'value'   => intval( $_GET['permis'] ),
			            'compare' => '=',
			        )
			);
		 }
		 
		 if ( $_GET['voiture'] != ''  ) {
	        	$get_jobs_args['meta_query'][] = array(
			        array(
			            'key'     => 'my_besoin_voiture_key',
			            'value'   => intval( $_GET['voiture'] ),
			            'compare' => '=',
			        )
			);
		 }
		 
		 if ( $_GET['school'] != ''  ) {
	        	$get_jobs_args['meta_query'][] = array(
			        array(
			            'key'     => 'my_education_key',
			            'value'   => intval( $_GET['school'] ),
			            'compare' => '=',
			        )
			);
		 }

        }
        
        $get_jobs = new WP_Query($get_jobs_args);
        
        echo '<div class="job-wrapper">';
	if ( $get_jobs->have_posts() ) : while ( $get_jobs->have_posts() ) : $get_jobs->the_post();
		if(get_post_status(get_the_ID()) == 'draft' || get_post_status(get_the_ID()) == 'future') {	
				if(get_current_user_id() == $get_jobs->post->post_author) {
					if($user_role == 'employeur'){
						echo '<div class="job-wrapper-box" id="job-wrapper-box-'.$i.'" style="display: block;">';
					    			echo '<a href="' . get_permalink( get_the_ID() ) .'">' . get_the_ID() . ' - ' . $get_jobs->post->post_title . '</a> - ';
								$author_id = $get_jobs->post->post_author;
								echo the_author_meta( 'user_nicename' , $author_id );
								$usermetadata = get_user_meta(get_current_user_id());
								$get_user_by_username = get_user_by('ID', $author_id);
								
						    echo ' - ';
						    echo get_user_meta($author_id, 'company_key', true);
						    echo ' - ';
					 	    echo $get_user_by_username->user_firstname;
					 	    echo ' ';
						    echo $get_user_by_username->user_lastname;
							
							$field_data_adresse = $usermetadata['Adresse'];
							$field_data = $usermetadata['Code_postal'];
							if($field_data){
								echo '<span class="autocompleteDeparture">';
									echo '<span class="autocompleteDeparture_'.  $i . '" style="display:none;">'. implode($field_data_adresse) . ' ' .implode($field_data) . '</span>';
									echo '<span class="autocompleteArrival_' . $i . '" style="display: none;">' . get_post_meta( $post->ID, 'my_code_postal_key', true ) . '</span>';
									echo ' - <span class="distance_' . $i . ' distance"></span>';
								echo '</span>';
							}
							
							if(get_post_status(get_the_ID()) == 'draft') {
								echo ' - Brouillon';
							} 
							if(get_post_status(get_the_ID()) == 'future') {
								echo ' - Programmer';
							}
							
							echo ' - ' . get_post_meta( get_the_ID(), 'my_city_key', true );							
							$from = strtotime(get_the_date('Y-m-d H:i:s', get_the_ID()));
							$today = current_time('timestamp');
							$difference = $today - $from;
							$round_difference = round($difference / 60 / 60 / 24, 0);
							if($round_difference < 1){
								echo ' - <span class="get-the-date-difference-'.$i.'">' . $round_difference . ' Jour</span>';
							} else {
								echo ' - <span class="get-the-date-difference-'.$i.'">' . $round_difference . ' Jours</span>';
							}
						echo '</div>';
					
					}
				
				}
			
			} else {
				echo '<div class="job-wrapper-box" id="job-wrapper-box-'.$i.'" style="display: block;">';
			    		echo '<a href="' . get_permalink( get_the_ID() ) .'">' . get_the_ID() . ' - ' . $get_jobs->post->post_title . '</a> - ';
						$author_id = $get_jobs->post->post_author;
						echo the_author_meta( 'user_nicename' , $author_id );
						$usermetadata = get_user_meta(get_current_user_id());
						$get_user_by_username = get_user_by('ID', $author_id);
								
					    echo ' - ';
					    echo get_user_meta($author_id, 'company_key', true);
					    echo ' - ';
				 	    echo $get_user_by_username->user_firstname;
				 	    echo ' ';
					    echo $get_user_by_username->user_lastname;
						
					$field_data_adresse = $usermetadata['Adresse'];
					$field_data = $usermetadata['Code_postal'];
					if($field_data){
						echo '<span class="autocompleteDeparture">';
							echo '<span class="autocompleteDeparture_'.  $i . '" style="display:none;">'. implode($field_data_adresse) . ' ' .implode($field_data) . '</span>';
							echo '<span class="autocompleteArrival_' . $i . '" style="display: none;">' . get_post_meta( $post->ID, 'my_code_postal_key', true ) . '</span>';
							echo ' - <span class="distance_' . $i . ' distance"></span>';
						echo '</span>';
					}
					
					echo ' - ' . get_post_meta( get_the_ID(), 'my_city_key', true );
			
					$from = strtotime(get_the_date('Y-m-d H:i:s', get_the_ID()));
					$today = current_time('timestamp');
					$difference = $today - $from;
					$round_difference = round($difference / 60 / 60 / 24, 0);
					if($round_difference < 1){
						echo ' - <span class="get-the-date-difference-'.$i.'">' . $round_difference . ' Jour</span>';
					} else {
						echo ' - <span class="get-the-date-difference-'.$i.'">' . $round_difference . ' Jours</span>';
					}
	
				echo '</div>';	
			}	    		
	    	$i++;
	endwhile; endif;
	wp_reset_postdata();
	?> </div> <?php
}
add_shortcode('monemploi-job-dashboard', 'monemploi_job_dashboard');