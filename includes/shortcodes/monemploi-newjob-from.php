<?php

function monemploi_newjob_from() {
	$current_user = wp_get_current_user();
	$user_meta = get_userdata($current_user->ID);
	$user_role = $user_meta->roles[0];
	if($user_role == 'employeur'){
			?><div id="monemploi-new-form">
			    <p>Vous etre connecter en tant que <?php echo $current_user->user_login; ?><p>
				<p>Titre de l'emploi</p>
				<input type="text" class="monemploi_add_job_text" name="monemploi_job_title" id="monemploi_job_title" value="<?php echo !empty($_POST['monemploi_job_title']); ?>" required>
				
				<p>Descrption de l'emploi</p>
				<?php $wp_editor_array = array(
					'media_buttons'		=> false,
					'textarea_name'		=> 'ns_ticket_details',
					'textarea_rows'		=> 10,
					'editor_class'		=> 'ns-form-control',
					'quicktags'			=> false,
					'tinymce'			=> true
				);
				
				$wp_editor_specs = apply_filters( 'ns_wp_editor_specs', $wp_editor_array );
				
										// initiate the editor.
				wp_editor(
					$content   = $ticket_content,
					$editor_id = 'ns-ticket-details',
					$wp_editor_specs
				); ?>
				
				<p>Debut de l'affichage de l'emploi</p>
				<input type="text" class="datepickerstartjobscheduled" data-toggle="datepickerstartjobscheduled">
				<input type="time" id="timestartjobscheduled" name="timestartjobscheduled">
				
				<p>Fin de l'affichage de l'emploi</p>
				<input type="text" class="datepickerendjobscheduled" data-toggle="datepickerendjobscheduled">
				<input type="time" id="timeendjobscheduled" name="timeendjobscheduled">
                
               			<p>Adresse de l'emploi</p>
				<input type="text" class="monemploi_add_code_postal_text" name="monemploi_code_postal" id="monemploi_code_postal" value="<?php echo !empty($_POST['monemploi_code_postal']); ?>" style="width: 100%;" required>
				
				<p>Ville de l'emploi</p>
				<input type="text" class="monemploi_add_city_text" name="monemploi_add_city_text" id="monemploi_add_city_text" value="<?php echo !empty($_POST['monemploi_add_city_text']); ?>" style="width: 100%;" disabled>
                               
                <?php $education_terms = get_terms( array(
					'taxonomy' => 'education',
					'orderby' => 'term_id',
				    'order' => 'ASC', // or ASC
					'hide_empty' => false,
				) ); ?>
                               
                <p>Diplôme</p>
                <select name="education_terms" class="education_terms" id="education_terms">
				 <?php if ( $education_terms ) {
					echo '<option value="0">'. esc_html__( 'Choisiser un niveau deducation' , 'monemploi' ) .'</option>';
					foreach ( $education_terms as $education_term ) {
						echo '<option value="'. $education_term->term_id .'">'. $education_term->name.'</option>';
					}
				}?>	
				</select>
				
				<p>Salaire de l&#8216;heure</p>
				<input type="number" class="monemploi_add_salaire" name="monemploi_add_salaire" id="monemploi_add_salaire" value="<?php echo !empty($_POST['monemploi_add_salaire']); ?>" style="width: 100px;" required>

				
				<p>Années d&#8216;expérience</p>
	                	<select name="annees_dexperience" class="annees_dexperience" id="annees_dexperience">
					<option value="0"><?php echo esc_html( 'Choisissez un années d&#8216;expérience' , 'monemploi' ); ?></option>
					<option value="1">Auccun</option>
					<option value="2">1 an</option>
					<option value="3">2-3 ans</option>
					<option value="4">4-5 ans</option>
					<option value="5">6-9 ans</option>
					<option value="6">10 ans+</option>
				</select>	
				
				<p>Nombre d&#8216;heures par semaine</p>
				<input type="number" class="monemploi_add_heures" name="monemploi_add_heures" id="monemploi_add_heures" value="<?php echo !empty($_POST['monemploi_add_heures']); ?>" style="width: 100px;" required>
				
				
				<p>Type d&#8216;emploi</p>
	                	<select name="type_demploi" class="type_demploi" id="type_demploi">
					<option value="0"><?php echo esc_html( 'un type d&#8216;emploi' , 'monemploi' ); ?></option>
					<option value="1">Temps plein</option>
					<option value="2">Temps partiel</option>
				</select>
				
				<p>Type d&#8216;horaire</p>
	                	<select name="type_dhoraire" class="type_dhoraire" id="type_dhoraire">
					<option value="0"><?php echo esc_html( 'Choisissez un type d&#8216;horaire' , 'monemploi' ); ?></option>
					<option value="1">Jour</option>
					<option value="2">Soir</option>
					<option value="3">Nuit</option>
				</select>

				<p>Type de disponibilités</p>
				<input type="checkbox" id="disponibilites1" class="disponibilites1" name="disponibilites1" value="1">
				<label for="disponibilites1">Semaine</label>
				<input type="checkbox" id="disponibilites2" class="disponibilites2" name="disponibilites2" value="2">
				<label for="disponibilites2">Fin de semaine</label>
				
				<p>Durée de l&#8216;emploi</p>
	                	<select name="duree_emploi" class="duree_emploi" id="duree_emploi">
					<option value="0"><?php echo esc_html( 'Choisissez une durée de l&#8216;emploi' , 'monemploi' ); ?></option>
					<option value="1">Permanent</option>
					<option value="2">Contrat</option>
					<option value="3">Sur appel</option>
				</select>
				
				<p>Besoin d&#8216;un permis de conduire</p>
	                	<select name="permis_conduire" class="permis_conduire" id="permis_conduire">
					<option value="0"><?php echo esc_html( 'Choisissez une réponse' , 'monemploi' ); ?></option>
					<option value="1">Non</option>
					<option value="2">Oui</option>
				</select>
				
				<p>Besoin d&#8216;une voiture</p>
	                	<select name="besoin_voiture" class="besoin_voiture" id="besoin_voiture">
					<option value="0"><?php echo esc_html( 'Choisissez une réponse' , 'monemploi' ); ?></option>
					<option value="1">Non</option>
					<option value="2">Oui</option>
				</select>
				
				<br><br>
				
				<div id="monemploi-new-form-sumbit"></div>
				
				<div class="new_job_error"></div>
								
				<button class="ns_submit">
					<?php esc_html_e( 'Ajouter l&#39;emploi', 'monemploi' ); ?>
				</button>
				
			</div><?php
	} else {
		?><h2>Vous n'avez pas les autorisations pour cree un emploi.<h2><?php
	}
}
add_shortcode('monemploi-newjob-from', 'monemploi_newjob_from');

?>