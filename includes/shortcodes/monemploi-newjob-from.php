<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function monemploi_newjob_from() {
	$current_user = wp_get_current_user();
	$user_meta = get_userdata($current_user->ID);
	$user_role = $user_meta->roles[0];
	if($user_role == 'employeur'){
        $postid = $_GET['postid']; 
        $author_id = get_post_field( 'post_author', $postid );
			?><div id="monemploi-new-form">
			
			<?php if (isset($_GET['new_job'])) {
			    if(get_permalink($_GET['new_job'])) {
			        $url = get_permalink($_GET['new_job']);
                    $queryString = parse_url($url, PHP_URL_QUERY); 
                    if ($queryString) {
                        parse_str($queryString, $params);
                        if (isset($params['p'])) {
                            echo header("Location: ". get_permalink($_GET['new_job']) ."&new_job=true");
                        }
                    } else {
                        echo header("Location: ". get_permalink($_GET['new_job']) ."?new_job=true");
                    }
			    }
			}
			
			if (isset($_GET['update_job'])) {
			    if(get_permalink($_GET['update_job'])) {
			        $url = get_permalink($_GET['update_job']);
                    $queryString = parse_url($url, PHP_URL_QUERY); 
                    if ($queryString) {
                        parse_str($queryString, $params);
                        if (isset($params['p'])) {
                            echo header("Location: ". get_permalink($_GET['update_job']) ."&update_job=true");
                        }
                    } else {
                        echo header("Location: ". get_permalink($_GET['update_job']) ."?update_job=true");
                    }
			    }
			} ?>
			
			    <p>Vous etre connecter en tant que <?php echo $current_user->user_login; ?><p>
			        <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
					<p>Titre de l'emploi</p>
					<?php if(isset($postid) && $author_id = get_current_user_id()){ ?>
						<input type="text" class="job_title" name="job_title" id="job_title" value="<?php echo get_the_title($postid); ?>" style="width: 100%;">
					<?php } else { ?>
	                    			<input type="text" class="job_title" name="job_title" id="job_title" style="width: 100%;">
					<?php } ?>
					
					<p>Descrption de l'emploi</p>
					<?php $wp_editor_array = array(
						'media_buttons'		=> false,
						'textarea_name'		=> 'new_job_details',
						'textarea_rows'		=> 10,
						'editor_class'		=> 'form-control',
						'quicktags'			=> false,
						'tinymce'			=> true
					);
					
					$wp_editor_specs = apply_filters( 'ns_wp_editor_specs', $wp_editor_array );
					
					if(isset($postid) && $author_id = get_current_user_id()){
						$job_content = get_post_field('post_content', $postid);
					} else {
					    $job_content = '';
					}
			        
					wp_editor(
						$content   = $job_content,
						$editor_id = 'new-job-details',
						$wp_editor_specs
					); ?>
					
					<p>Debut de l'affichage de l&#8216;emploi</p>
					<?php if(isset($postid) && $author_id = get_current_user_id()){ ?>
						<input type="text" id="datepickerstartjobscheduled" class="datepickerstartjobscheduled" name="datepickerstartjobscheduled" data-toggle="datepickerstartjobscheduled" value="<?php echo get_post_meta( $postid, 'my_start_job_date_scheduled_key', true); ?>">
					<?php } else { ?>
						<input type="text" id="datepickerstartjobscheduled" class="datepickerstartjobscheduled" name="datepickerstartjobscheduled" data-toggle="datepickerstartjobscheduled">
					<?php } ?>
					<?php if(isset($postid) && $author_id = get_current_user_id()){ ?>
						<input type="time" id="timestartjobscheduled" name="timestartjobscheduled" value="<?php echo get_post_meta( $postid, 'my_start_job_time_scheduled_key', true); ?>">
					<?php } else { ?>
						<input type="time" id="timestartjobscheduled" name="timestartjobscheduled">
					<?php } ?>
					
					<p>Fin de l'affichage de l&#8216;emploi</p>
					<?php if(isset($postid) && $author_id = get_current_user_id()){ ?>
						<input type="text" id="datepickerendjobscheduled" class="datepickerendjobscheduled" name="datepickerendjobscheduled" data-toggle="datepickerendjobscheduled" value="<?php echo get_post_meta( $postid, 'my_end_job_date_scheduled_key', true); ?>">
					<?php } else { ?>
						<input type="text" id="datepickerendjobscheduled" class="datepickerendjobscheduled" name="datepickerendjobscheduled" data-toggle="datepickerendjobscheduled">
					<?php } ?>
					<?php if(isset($postid) && $author_id = get_current_user_id()){ ?>
						<input type="time" id="timeendjobscheduled" name="timeendjobscheduled" value="<?php echo get_post_meta( $postid, 'my_end_job_time_scheduled_key', true); ?>">
					<?php } else { ?>
						<input type="time" id="timeendjobscheduled" name="timeendjobscheduled">
					<?php } ?>
	                
	               	<p>Adresse de l&#8216;emploi</p>
	               	<?php if(isset($postid) && $author_id = get_current_user_id()){ ?>
						<input type="text" class="code_postal" name="code_postal" id="code_postal" style="width: 100%;" value="<?php echo get_post_meta( $postid, 'my_code_postal_key', true); ?>">
					<?php } else { ?>
						<input type="text" class="code_postal" name="code_postal" id="code_postal" style="width: 100%;">
					<?php } ?>
					
					<p>Ville de l&#8216;emploi</p>
					<?php if(isset($postid) && $author_id = get_current_user_id()){ ?>
						<input type="text" class="city" name="city" id="city" style="width: 100%;" value="<?php echo get_post_meta( $postid, 'my_city_key', true); ?>">
					<?php } else { ?>
						<input type="text" class="city" name="city" id="city" style="width: 100%;">
					<?php } ?>
	                               
	                <?php $education_terms = get_terms( array(
						'taxonomy' => 'education',
						'orderby' => 'term_id',
					    'order' => 'ASC', // or ASC
						'hide_empty' => false,
					) ); ?>
	                               
			                <p>Diplôme</p>
			                <select name="education" class="education" id="education">
					 <?php if ( $education_terms ) {
						echo '<option value="0">'. esc_html__( 'Choisiser un niveau d&#8216;éducation' , 'monemploi' ) .'</option>';
						foreach ( $education_terms as $education_term ) {
							if(isset($postid) && $author_id = get_current_user_id()){
								$education = get_post_meta( $postid, 'my_education_key', true);
								if($education == $education_term->term_id){
									echo '<option value="'. $education_term->term_id .'" selected>'. $education_term->name.'</option>';
								} else {
									echo '<option value="'. $education_term->term_id .'">'. $education_term->name.'</option>';
								}
							} else {
								echo '<option value="'. $education_term->term_id .'">'. $education_term->name.'</option>';
							}
						}
					}?>	
					</select>
					
					<p>Salaire de l&#8216;heure</p>
					<?php if(isset($postid) && $author_id = get_current_user_id()){ ?>
						<input type="number" class="salaire" name="salaire" id="salaire" value="<?php echo get_post_meta( $postid, 'my_salaire_key', true); ?>" style="width: 100px;">
					<?php } else { ?>
						<input type="number" class="salaire" name="salaire" id="salaire" style="width: 100px;">
					<?php } ?>
	
					
					<p>Années d&#8216;expérience</p>
		                	<select name="annees_dexperience" class="annees_dexperience" id="annees_dexperience">
		                		<?php if(isset($postid) && $author_id = get_current_user_id()){ ?>
		                			<?php $annees_dexperience = get_post_meta( $postid, 'my_annees_dexperience_key', true); ?>
		                			<?php if($annees_dexperience == 0){ ?>
								<option value="0" selected><?php echo esc_html( 'Choisissez un année d&#8216;expérience' , 'monemploi' ); ?></option>
							<?php } else { ?>
								<option value="0"><?php echo esc_html( 'Choisissez un année d&#8216;expérience' , 'monemploi' ); ?></option>
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
								<option value="2" selected>2-3 an</option>
							<?php } else { ?>
								<option value="2">2-3 an</option>
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
						<?php } else { ?>
							<option value="0"><?php echo esc_html( 'Choisissez un année d&#8216;expérience' , 'monemploi' ); ?></option>
							<option value="1">Auccun</option>
							<option value="2">1 an</option>
							<option value="3">2-3 ans</option>
							<option value="4">4-5 ans</option>
							<option value="5">6-9 ans</option>
							<option value="6">10 ans+</option>
						<?php } ?>
					</select>	
					
					<p>Nombre d&#8216;heures par semaine</p>
					<?php if(isset($postid) && $author_id = get_current_user_id()){ ?>
						<input type="number" class="add_heures" name="add_heures" id="add_heures" value="<?php echo get_post_meta( $postid, 'my_add_heures_key', true); ?>" style="width: 100px;">
					<?php } else { ?>
						<input type="number" class="add_heures" name="add_heures" id="add_heures" style="width: 100px;">
					<?php } ?>
					
					
					<p>Type d&#8216;emploi</p>
		                	<select name="type_demploi" class="type_demploi" id="type_demploi">
			                	<?php if(isset($postid) && $author_id = get_current_user_id()){ ?>
			                		<?php $type_demploi = get_post_meta( $postid, 'my_type_demploi_key', true); ?>
			                		<?php if($type_demploi == 0){ ?>
								<option value="0" selected><?php echo esc_html( 'un type d&#8216;emploi' , 'monemploi' ); ?></option>
							<?php } else { ?>
								<option value="0" ><?php echo esc_html( 'un type d&#8216;emploi' , 'monemploi' ); ?></option>
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
						<?php } else { ?>
							<option value="0"><?php echo esc_html( 'un type d&#8216;emploi' , 'monemploi' ); ?></option>
							<option value="1">Temps plein</option>
							<option value="2">Temps partiel</option>
						<?php } ?>
					</select>
					
					<p>Activité professionnelle</p>
		                	<select name="activite_professionnelle" class="activite_professionnelle" id="activite_professionnelle">
			                	<?php if(isset($postid) && $author_id = get_current_user_id()){ ?>
			                		<?php $activite_professionnelle = get_post_meta( $postid, 'my_activite_professionnelle_key', true); ?>
			                		<?php if($activite_professionnelle == 0){ ?>
								<option value="0" selected><?php echo esc_html( 'Type d&#8216;activité professionnelle' , 'monemploi' ); ?></option>
							<?php } else { ?>
								<option value="0" ><?php echo esc_html( 'Type d&#8216;activité professionnelle' , 'monemploi' ); ?></option>
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
						<?php } else { ?>
							<option value="0"><?php echo esc_html( 'Type d&#8216;activité professionnelle' , 'monemploi' ); ?></option>
							<option value="1">Travail en présentiel</option>
							<option value="2">Télétravail</option>
							<option value="3">Mode hybride</option>
						<?php } ?>
					</select>
					
					<p>Type d&#8216;horaire</p>
		                	<select name="type_dhoraire" class="type_dhoraire" id="type_dhoraire">
		                		<?php if(isset($postid) && $author_id = get_current_user_id()){ ?>
		                			<?php $type_dhoraire = get_post_meta( $postid, 'my_type_dhoraire_key', true); ?>
		                			<?php if($type_dhoraire == 0){ ?>
								<option value="0" selected><?php echo esc_html( 'Choisissez un type d&#8216;horaire' , 'monemploi' ); ?></option>
							<?php } else { ?>
								<option value="0"><?php echo esc_html( 'Choisissez un type d&#8216;horaire' , 'monemploi' ); ?></option>
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
						<?php } else { ?>
							<option value="0"><?php echo esc_html( 'Choisissez un type d&#8216;horaire' , 'monemploi' ); ?></option>
							<option value="1">Jour</option>
							<option value="2">Soir</option>
							<option value="3">Nuit</option>
						<?php } ?>
					</select>
	
					<p>Type de disponibilités</p>
					<?php if(isset($postid) && $author_id = get_current_user_id()){ ?>
						<?php $disponibilites1 = get_post_meta( $postid, 'my_disponibilites1_key', true); ?>
							<?php if($disponibilites1 == 1){ ?>
								<input type="checkbox" id="disponibilites1" class="disponibilites1" name="disponibilites1" value="1" checked>
							<?php } else { ?>
								<input type="checkbox" id="disponibilites1" class="disponibilites1" name="disponibilites1" value="1">
							<?php } ?>
					<?php } else { ?>
						<input type="checkbox" id="disponibilites1" class="disponibilites1" name="disponibilites1" value="1">
					<?php } ?>
					<label for="disponibilites1">Semaine</label>
					<?php if(isset($postid) && $author_id = get_current_user_id()){ ?>
						<?php $disponibilites2 = get_post_meta( $postid, 'my_disponibilites2_key', true); ?>
							<?php if($disponibilites2 == 1){ ?>
								<input type="checkbox" id="disponibilites2" class="disponibilites2" name="disponibilites2" value="1" checked>
							<?php } else { ?>
								<input type="checkbox" id="disponibilites2" class="disponibilites2" name="disponibilites2" value="1">
							<?php } ?>
					<?php } else { ?>
						<input type="checkbox" id="disponibilites2" class="disponibilites2" name="disponibilites2" value="1">
					<?php } ?>
					<label for="disponibilites2">Fin de semaine</label>
					
					<p>Durée de l&#8216;emploi</p>
		                	<select name="duree_emploi" class="duree_emploi" id="duree_emploi">
		                		<?php if(isset($postid) && $author_id = get_current_user_id()){ ?>
		                			<?php $duree_emploi = get_post_meta( $postid, 'my_duree_emploi_key', true); ?>
		                			<?php if($duree_emploi == 0){ ?>
								<option value="0" selected><?php echo esc_html( 'Choisissez une durée de l&#8216;emploi' , 'monemploi' ); ?></option>
							<?php } else { ?>
								<option value="0"><?php echo esc_html( 'Choisissez une durée de l&#8216;emploi' , 'monemploi' ); ?></option>
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
						<?php } else { ?>
							<option value="0"><?php echo esc_html( 'Choisissez une durée de l&#8216;emploi' , 'monemploi' ); ?></option>
							<option value="1">Permanent</option>
							<option value="2">Contrat</option>
							<option value="3">Sur appel</option>
	
						<?php } ?>
					</select>
					
					<p>Besoin d&#8216;un permis de conduire</p>
		                	<select name="permis_conduire" class="permis_conduire" id="permis_conduire">
						<?php if(isset($postid) && $author_id = get_current_user_id()){ ?>
		                			<?php $permis_conduire = get_post_meta( $postid, 'my_permis_conduire_key', true); ?>
		                			<?php if($permis_conduire == 0){ ?>
								<option value="0" selected><?php echo esc_html( 'Choisissez une réponse' , 'monemploi' ); ?></option>
							<?php } else { ?>
								<option value="0"><?php echo esc_html( 'Choisissez une réponse' , 'monemploi' ); ?></option>
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
						<?php } else { ?>
							<option value="0"><?php echo esc_html( 'Choisissez une réponse' , 'monemploi' ); ?></option>
							<option value="1">Non</option>
							<option value="2">Oui</option>
						<?php } ?>
					</select>
					
					<p>Besoin d&#8216;une voiture</p>
		                	<select name="besoin_voiture" class="besoin_voiture" id="besoin_voiture">
		                		<?php if(isset($postid) && $author_id = get_current_user_id()){ ?>
		                			<?php $besoin_voiture = get_post_meta( $postid, 'my_besoin_voiture_key', true); ?>
		                			<?php if($besoin_voiture == 0){ ?>
								<option value="0" selected><?php echo esc_html( 'Choisissez une réponse' , 'monemploi' ); ?></option>
							<?php } else { ?>
								<option value="0"><?php echo esc_html( 'Choisissez une réponse' , 'monemploi' ); ?></option>
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
						<?php } else { ?>
							<option value="0"><?php echo esc_html( 'Choisissez une réponse' , 'monemploi' ); ?></option>
							<option value="1">Non</option>
							<option value="2">Oui</option>
						<?php } ?>
					</select>
					
					<p>E-mail de l&#8216;employeur</p>
					<?php $email_employeur = get_post_meta( $postid, 'my_email_employeur_key', true); ?>
					<?php if(isset($postid) && $author_id = get_current_user_id()){ ?>
						<input type="text" class="email_employeur" name="email_employeur" id="email_employeur" value="<?php echo $email_employeur; ?>" style="width: 100%;">
					<?php } else { ?>
	                    			<input type="text" class="email_employeur" name="email_employeur" id="email_employeur" style="width: 100%;">
					<?php } ?>
					
					<p>Lien de l&#8216;employeur</p>
					<?php $email_employeur = get_post_meta( $postid, 'my_lien_employeur_key', true); ?>
					<?php if(isset($postid) && $author_id = get_current_user_id()){ ?>
						<input type="text" class="lien_employeur" name="lien_employeur" id="lien_employeur" value="<?php echo $lien_employeur; ?>" style="width: 100%;">
					<?php } else { ?>
	                    			<input type="text" class="lien_employeur" name="lien_employeur" id="lien_employeur" style="width: 100%;">
					<?php } ?>
				
					<br><br>
																		
					<?php if(isset($postid) && $author_id = get_current_user_id()){ ?>
						<input type="hidden" name="action" value="ns_submit" />
						<input type="hidden" name="postid" value="<?php echo $postid; ?>" />
						<input type="hidden" name="job_status" value="update" />
						<button class="ns_submit" type="submit" name="submit">
							<?php esc_html_e( 'Mettre à jour l&#39;emploi', 'monemploi' ); ?>
						</button>
					<?php } else { ?>
						<input type="hidden" name="action" value="ns_submit" />
						<input type="hidden" name="postid" value="0" />
						<input type="hidden" name="job_status" value="new" />
						<button class="ns_submit" type="submit" name="submit">
							<?php esc_html_e( 'Ajouter l&#39;emploi', 'monemploi' ); ?>
						</button>
					<?php } ?>
					
				</form>
				
			</div><?php
	} else {
	
		echo '<h2>Vous n&#39;avez pas les autorisations pour crée un emploi.<h2>';
		
	}
}
add_shortcode('monemploi-newjob-from', 'monemploi_newjob_from');

?>