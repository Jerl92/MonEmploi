<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function monemploi_user_dashboard() {

	if ($_GET['user']) {
		$user_meta = $_GET['user']; 	
		$get_user_by_username = get_user_by('login', $user_meta);
		$userid = $get_user_by_username->ID;
		$current_user = wp_get_current_user();
		if($current_user->ID === $userid) {
			$edit = 1;
		} else {
			$edit = 0;
		}
	} else  {
		$userid = get_current_user_id();
		$edit = 1;
	} 
	
	echo '<h3>Experiance de travail</h3>';
	
	if($edit === 1){
		echo '<div class="" style="display: flex;">';
			echo '<div class="add-job-experiance">';
				echo '<i class="material-icons">';
					echo 'add';
				echo '</i>';
			echo '</div>';
			echo '<div class="edit-job-experiance">';
				echo '<i class="material-icons">';
					echo 'edit';
				echo '</i>';
			echo '</div>';
		echo '</div>';
	}
	
	echo '<div class="is-editing-job-experiance" style="display: none;">false</div>';
		
	echo '<div class="profile-wrapper-job-experiance">';

		$unique_strings_job = get_user_meta( $userid, 'job_unique', true );
		
		if($unique_strings_job == null){
		
		
		} else 	{
			foreach($unique_strings_job as $unique_string_job)  {
						
						echo '<div id="job-experiance-unique-string" class="'. $unique_string_job .'" style="padding-bottom: 15px;">';
			                                		
							$date_job_start_strtotime = strtotime(get_user_meta( $userid, 'date_job_start_'.$unique_string_job, true));
							$date_job_end_strtotime = strtotime(get_user_meta( $userid, 'date_job_end_'.$unique_string_job, true));
							$date_job_strtotime = ($date_job_end_strtotime - $date_job_start_strtotime);
							$date_job_strtotime_clac = ($date_job_strtotime/60/60/24/30);
							
							$date_job_end_ = get_user_meta( $userid, 'date_job_end_'.$unique_string_job, true);
							if($date_job_end_ === 'now'){
								$datestrtotime = date('Y-m-d H:i:s');
							} else {
								$datestrtotime = $date_job_end_;
							}
	
							echo '<div class="job-experiance-title-compagny-header" style="padding-bottom: 5px; width: 100%"><span style="font-weight: 600;">'. get_user_meta( $userid, 'job_title_'.$unique_string_job, true) .'</span> - <span style="font-style: italic;">'. get_user_meta( $userid, 'job_name_'.$unique_string_job, true) .'</span></div>';
							echo '<span>' . date('m/Y', strtotime(get_user_meta( $userid, 'date_job_start_'.$unique_string_job, true))) . '</span>';
							echo ' - ';
							echo '<span>' . date('m/Y', strtotime($datestrtotime)) . '</span>';
							echo ' - ';
							echo '<span>' . round($date_job_strtotime_clac) . ' Mois</span>';				
							echo '<div class="job-experiance-description-header" style="white-space: pre-wrap;">'. get_user_meta( $userid, 'job_description_'.$unique_string_job, true) .'</div>';
                        
						
						echo '</div>';
								
			}
		}
	
	echo '</div>';
	
	echo '<h3>Certification</h3>';
	
	if($edit === 1){
		echo '<div class="" style="display: flex;">';
			echo '<div class="add-certification-experiance">';
				echo '<i class="material-icons">';
					echo 'add';
				echo '</i>';
			echo '</div>';
			echo '<div class="edit-certification-experiance">';
				echo '<i class="material-icons">';
					echo 'edit';
				echo '</i>';
			echo '</div>';
		echo '</div>';
	}
	
	echo '<div class="is-editing-certification-experiance" style="display: none;">false</div>';
	
	echo '<div class="profile-wrapper-certification-experiance">';
		
		$unique_strings_certification = get_user_meta( $userid, 'certification_unique', true );
					
		if($unique_strings_certification == null){

		
		} else 	{
			foreach($unique_strings_certification as $unique_string_certification)  {
						
						echo '<div id="certification-experiance-unique-string" class="'. $unique_string_certification .'" style="padding-bottom: 15px;" >';
						
							$date_certification_start_strtotime = strtotime(get_user_meta( $userid, 'datecertificationstart_'.$unique_string_certification, true));
							$date_certification_end_strtotime = strtotime(get_user_meta( $userid, 'datecertificationend_'.$unique_string_certification, true));
							$date_certification_strtotime = ($date_certification_end_strtotime - $date_certification_start_strtotime);
							$date_certification_strtotime_clac = ($date_certification_strtotime/60/60/24/30);

							echo '<div class="certification-experiance-title-header"><span style="font-weight: 600;">'. get_user_meta( $userid, 'certification_title_'.$unique_string_certification, true) .'</span> - <span style="font-style: italic;">'. get_user_meta( $userid, 'certification_name_'.$unique_string_certification, true) .'</span></div>';
							
							echo '<span>' . date('m/Y', $date_certification_start_strtotime) . '</span>';
							echo ' - ';
							echo '<span>' . date('m/Y', $date_certification_end_strtotime) . '</span>';
							echo ' - ';
							echo '<span>' . round($date_certification_strtotime_clac) . ' Mois</span>';
							echo '<div style="white-space: pre-wrap;">'. get_user_meta( $userid, 'certification_description_'.$unique_string_certification, true) .'</div>';
						
						echo '</div>';
								
			}
		}

	
	echo '</div>';
	
	echo '<h3>École</h3>';
	
	if($edit === 1){
		echo '<div class="" style="display: flex;">';
			echo '<div class="add-school-experiance">';
				echo '<i class="material-icons">';
					echo 'add';
				echo '</i>';
			echo '</div>';
			echo '<div class="edit-school-experiance">';
				echo '<i class="material-icons">';
					echo 'edit';
				echo '</i>';
			echo '</div>';
		echo '</div>';
	}
	
	echo '<div class="is-editing-school-experiance" style="display: none;">false</div>';
	
	echo '<div class="profile-wrapper-school-experiance">';
		
		$unique_strings_school = get_user_meta( $userid, 'school_unique', true );
					
		if($unique_strings_school == null){
		
		
		} else 	{
			foreach($unique_strings_school as $unique_string_school)  {
						
						echo '<div id="school-experiance-unique-string" class="'. $unique_string_school .'" style="padding-bottom: 15px;">';
						
							$date_school_start_strtotime = strtotime(get_user_meta( $userid, 'dateschoolstart_'.$unique_string_school, true));
							$date_school_end_strtotime = strtotime(get_user_meta( $userid, 'dateschoolend_'.$unique_string_school, true));
							$date_school_strtotime = ($date_school_end_strtotime - $date_school_start_strtotime);
							$date_school_strtotime_clac = ($date_school_strtotime/60/60/24/30);
							
							echo '<div class="school-experiance-title-header"><span style="font-weight: 600;">'. get_user_meta( $userid, 'school_title_'.$unique_string_school, true) .'</span> - <span style="font-style: italic;">'. get_user_meta( $userid, 'school_name_'.$unique_string_school, true) .'</span></div>';
							echo '<span>' . date('m/Y', $date_school_start_strtotime) . '</span>';
							echo ' - ';
							echo '<span>' . date('m/Y', $date_school_end_strtotime) . '</span>';
							echo ' - ';
							echo '<span>' . round($date_school_strtotime_clac) . ' Mois</span>';						
							echo '<div style="white-space: pre-wrap;">'. get_user_meta( $userid, 'school_description_'.$unique_string_school, true) .'</div>';
						
						echo '</div>';
								
			}
		}

	
	echo '</div>';	
	
	echo '<h3>Questions liées à la candidature</h3>';

	$age_legal = get_user_meta( $userid, 'my_age_legal_key', true );
	echo '<p style="font-weight: 600;">Est-ce que vous avez l&#8216;âge légal pour travailler au Canada?</p>';
	if($edit == 1){
		echo '<select name="age_legal" class="age_legal" id="age_legal">';
			if($age_legal == 0 || $age_legal == ''){
				echo '<option value="0" selected>' . esc_html( 'Choisissez une valeur' , 'monemploi' ) . '</option>';
			} else {
				echo '<option value="0">' . esc_html( 'Choisissez une valeur' , 'monemploi' ) . '</option>';
			}
			if($age_legal == 1){
				echo '<option value="1" selected>' . esc_html( 'Oui' , 'monemploi' ) . '</option>';
			} else {
				echo '<option value="1">' . esc_html( 'Oui' , 'monemploi' ) . '</option>';
			}
			if($age_legal == 2){
				echo '<option value="2" selected>' . esc_html( 'Non' , 'monemploi' ) . '</option>';
			} else {
				echo '<option value="2">' . esc_html( 'Non' , 'monemploi' ) . '</option>';
			}
		echo '</select>';
	} else {
		if($age_legal == 0 || $age_legal == ''){
			echo esc_html( 'Choisissez une valeur' , 'monemploi' );
		}
		if($age_legal == 1){
			echo  esc_html( 'Oui' , 'monemploi' );
		}
		if($age_legal == 2){
			echo  esc_html( 'Non' , 'monemploi' );
		}
	}
		
	
	echo '<br />';
	
	$situation_canada = get_user_meta( $userid, 'my_situation_canada_key', true );
	echo '<p style="font-weight: 600;">Concernant votre situation au Canada, détenez-vous</p>';
	if($edit == 1){
		echo '<select name="situation_canada" class="situation_canada" id="situation_canada">';
			if($situation_canada == 0 || $situation_canada == ''){
				echo '<option value="0" selected>' . esc_html( 'Choisissez une valeur' , 'monemploi' ) . '</option>';
			} else {
				echo '<option value="0">' . esc_html( 'Choisissez une valeur' , 'monemploi' ) . '</option>';
			}
			if($situation_canada == 1){
				echo '<option value="1" selected>' . esc_html( 'La citoyenneté canadienne' , 'monemploi' ) . '</option>';
			} else {
				echo '<option value="1">' . esc_html( 'La citoyenneté canadienne' , 'monemploi' ) . '</option>';
			}
			if($situation_canada == 2){
				echo '<option value="2" selected>' . esc_html( 'La résidence permanente au canada' , 'monemploi' ) . '</option>';
			} else {
				echo '<option value="2">' . esc_html( 'La résidence permanente au canada' , 'monemploi' ) . '</option>';
			}
			if($situation_canada == 3){
				echo '<option value="3" selected>' . esc_html( 'Un permis de travail valide au canada' , 'monemploi' ) . '</option>';
			} else {
				echo '<option value="3">' . esc_html( 'Un permis de travail valide au canada' , 'monemploi' ) . '</option>';
			}
			if($situation_canada == 4){
				echo '<option value="4" selected>' . esc_html( 'Aucun de ces éléments' , 'monemploi' ) . '</option>';
			} else {
				echo '<option value="4">' . esc_html( 'Aucun de ces éléments' , 'monemploi' ) . '</option>';
			}
		echo '</select>';
	} else {
		if($situation_canada == 0 || $situation_canada == ''){
			echo esc_html( 'Choisissez une valeur' , 'monemploi' );
		}
		if($situation_canada == 1){
			echo esc_html( 'La citoyenneté canadienne' , 'monemploi' );
		}
		if($situation_canada == 2){
			echo esc_html( 'La résidence permanente au canada' , 'monemploi' );
		}
		if($situation_canada == 3){
			echo esc_html( 'Un permis de travail valide au canada' , 'monemploi' );
			echo '<div class="situation-canada-class" style="display: none;">3</div>';
		}
		if($situation_canada == 4){
			echo esc_html( 'Aucun de ces éléments' , 'monemploi' );
		}
	} 
	
	echo '<br />';
	
	if($situation_canada == 3){
		echo '<div class="permis_travail_wrapper">';
	} else {
		echo '<div class="permis_travail_wrapper" style="display: none;">';
	}
		$permis_travail = get_user_meta( $userid, 'my_permis_travail_key', true );	
		echo '<p style="font-weight: 600;">Si vous détenez un permis de travail, quel type de permis avez-vous</p>';
		if($edit == 1){
			echo '<select name="permis_travail" class="permis_travail" id="permis_travail">';
				if($permis_travail == 0 || $permis_travail == ''){
					echo '<option value="0" selected>' . esc_html( 'Choisissez une valeur' , 'monemploi' ) . '</option>';
				} else {
					echo '<option value="0">' . esc_html( 'Choisissez une valeur' , 'monemploi' ) . '</option>';
				}
				if($permis_travail == 1){
					echo '<option value="1" selected>' . esc_html( 'Permis fermé avec votre employeur actuel' , 'monemploi' ) . '</option>';
				} else {
					echo '<option value="1">' . esc_html( 'Permis fermé avec votre employeur actuel' , 'monemploi' ) . '</option>';
				}
				if($permis_travail == 2){
					echo '<option value="2" selected>' . esc_html( 'Permis ouvert' , 'monemploi' ) . '</option>';
				} else {
					echo '<option value="2">' . esc_html( 'Permis ouvert' , 'monemploi' ) . '</option>';
				}
				if($permis_travail == 3){
					echo '<option value="3" selected>' . esc_html( 'Permis ouvert lie au statut d&#8216;un autre personne' , 'monemploi' ) . '</option>';
				} else {
					echo '<option value="3">' . esc_html( 'Permis ouvert lie au statut d&#8216;un autre personne' , 'monemploi' ) . '</option>';
				}
				if($permis_travail == 4){
					echo '<option value="4" selected>' . esc_html( 'Permis d&#8216;etudes international' , 'monemploi' ) . '</option>';
				} else {
					echo '<option value="4">' . esc_html( 'Permis d&#8216;etudes international' , 'monemploi' ) . '</option>';
				}
				if($permis_travail == 5){
					echo '<option value="5" selected>' . esc_html( 'Autre (demandeur d&#8216;asile, visiteur)' , 'monemploi' ) . '</option>';
				} else {
					echo '<option value="5">' . esc_html( 'Autre (demandeur d&#8216;asile, visiteur)' , 'monemploi' ) . '</option>';
				}
			echo '</select>';
		} else {
			if($permis_travail == 0 || $permis_travail == ''){
				echo esc_html( 'Choisissez une valeur' , 'monemploi' );
			}
			if($permis_travail == 1){
				echo esc_html( 'Permis fermé avec votre employeur actuel' , 'monemploi' );
			}
			if($permis_travail == 2){
				echo esc_html( 'Permis ouvert' , 'monemploi' );
			}
			if($permis_travail == 3){
				echo esc_html( 'Permis ouvert lie au statut d&#8216;un autre personne' , 'monemploi' );
			}
			if($permis_travail == 4){
				echo esc_html( 'Permis d&#8216;etudes international' , 'monemploi' );
			}
			if($permis_travail == 5){
				echo esc_html( 'Autre (demandeur d&#8216;asile, visiteur)' , 'monemploi' );
			}
		}
	echo '</div>';
	
	echo '<br />';
	
	$dossier_criminel = get_user_meta( $userid, 'my_dossier_criminel_key', true );
	echo '<p style="font-weight: 600;">Est-ce que vous avez eu un dossier criminel dont vous n&#8216;avez pas eu le pardon.</p>';
	if($edit == 1){
	echo '<select name="dossier-criminel" class="dossier-criminel" id="dossier-criminel">';
		if($dossier_criminel == 0 || $dossier_criminel == ''){
			echo '<option value="0" selected>' . esc_html( 'Choisissez une valeur' , 'monemploi' ) . '</option>';
		} else {
			echo '<option value="0">' . esc_html( 'Choisissez une valeur' , 'monemploi' ) . '</option>';
		}
		if($dossier_criminel == 1){
			echo '<option value="1" selected>' . esc_html( 'Ne souhaite pas repondre' , 'monemploi' ) . '</option>';
		} else {
			echo '<option value="1">' . esc_html( 'Ne souhaite pas repondre' , 'monemploi' ) . '</option>';
		}
		if($dossier_criminel == 2){
			echo '<option value="2" selected>' . esc_html( 'Oui' , 'monemploi' ) . '</option>';
		} else {
			echo '<option value="2">' . esc_html( 'Oui' , 'monemploi' ) . '</option>';
		}
		if($dossier_criminel == 3){
			echo '<option value="3" selected>' . esc_html( 'Non' , 'monemploi' ) . '</option>';
		} else {
			echo '<option value="3">' . esc_html( 'Non' , 'monemploi' ) . '</option>';
		}
	echo '</select>';
	} else {
		if($dossier_criminel == 1){
			echo esc_html( 'Ne souhaite pas repondre' , 'monemploi' );
		}
		if($dossier_criminel == 2){
			echo esc_html( 'Oui' , 'monemploi' );
			echo '<div class="dossier-criminel-class" style="display: none;">2</div>';
		}
		if($dossier_criminel == 3){
			echo esc_html( 'Non' , 'monemploi' );
		}
	}
	
	echo '<br>';
	
	if($dossier_criminel == 2){
		echo '<div class="dossier_criminel_wrapper">';
	} else {
		echo '<div class="dossier_criminel_wrapper" style="display: none;">';
	}
		$dossier_criminel_info = get_user_meta( $userid, 'my_dossier_criminel_info_key', true );
		echo '<p style="font-weight: 600;">Si vous avez un dossier criminel, expliquer le</p>';
		if($edit == 1){
			if($dossier_criminel_info == ''){
				echo '<textarea name="dossier-criminel-info" class="dossier-criminel-info" id="dossier-criminel-info" rows="4" cols="50"></textarea>';
			} else {
				echo '<textarea name="dossier-criminel-info" class="dossier-criminel-info" id="dossier-criminel-info" rows="4" cols="50">' . $dossier_criminel_info . '</textarea>';
			}
		} else {
			if($dossier_criminel == 2){
				echo wpautop($dossier_criminel_info);
			}
		echo '<br>';
		}
	echo '</div>';
	
	echo '<br />';
	
	echo '<h3>Équité en emploi</h3>';
	
	$sexe = get_user_meta( $userid, 'my_sexe_key', true );
	echo '<p style="font-weight: 600;">Sexe à la naissance</p>';
	if($edit == 1){
	echo '<select name="sexe" class="sexe" id="sexe">';
		if($sexe == 0 || $sexe == ''){
			echo '<option value="0" selected>' . esc_html( 'Choisissez une valeur' , 'monemploi' ) . '</option>';
		} else {
			echo '<option value="0">' . esc_html( 'Choisissez une valeur' , 'monemploi' ) . '</option>';
		}
		if($sexe == 1){
			echo '<option value="1" selected>' . esc_html( 'Masculin' , 'monemploi' ) . '</option>';
		} else {
			echo '<option value="1">' . esc_html( 'Masculin' , 'monemploi' ) . '</option>';
		}
		if($sexe == 2){
			echo '<option value="2" selected>' . esc_html( 'Féminin' , 'monemploi' ) . '</option>';
		} else {
			echo '<option value="2">' . esc_html( 'Féminin' , 'monemploi' ) . '</option>';
		}
	echo '</select>';
	} else {
		if($sexe == 0 || $sexe == ''){
			echo esc_html( 'Choisissez une valeur' , 'monemploi' );
		}
		if($sexe == 1){
			echo esc_html( 'Masculin' , 'monemploi' );
		}
		if($sexe == 2){
			echo esc_html( 'Féminin' , 'monemploi' );
		}	
	}
	
	echo '<br />';
	
	$origine_ethnique = get_user_meta( $userid, 'my_origine_ethnique_key', true );
	echo '<p style="font-weight: 600;">Origine ethnique</p>';
	if($edit == 1){
	echo '<select name="origine_ethnique" class="origine_ethnique" id="origine_ethnique">';
		if($origine_ethnique == 0 || $origine_ethnique == ''){
			echo '<option value="0" selected>' . esc_html( 'Choisissez une valeur' , 'monemploi' ) . '</option>';
		} else {
			echo '<option value="0">' . esc_html( 'Choisissez une valeur' , 'monemploi' ) . '</option>';
		}
		if($origine_ethnique == 1){
			echo '<option value="1" selected>' . esc_html( 'Nord-américaines' , 'monemploi' ) . '</option>';
		} else {
			echo '<option value="1">' . esc_html( 'Nord-américaines' , 'monemploi' ) . '</option>';
		}
		if($origine_ethnique == 2){
			echo '<option value="2" selected>' . esc_html( 'Européennes' , 'monemploi' ) . '</option>';
		} else {
			echo '<option value="2">' . esc_html( 'Européennes' , 'monemploi' ) . '</option>';
		}
		if($origine_ethnique == 3){
			echo '<option value="3" selected>' . esc_html( 'Caraïbes' , 'monemploi' ) . '</option>';
		} else {
			echo '<option value="3">' . esc_html( 'Caraïbes' , 'monemploi' ) . '</option>';
		}
		if($origine_ethnique == 4){
			echo '<option value="4" selected>' . esc_html( 'Amérique latine - centrale et du Sud' , 'monemploi' ) . '</option>';
		} else {
			echo '<option value="4">' . esc_html( 'Amérique latine - centrale et du Sud' , 'monemploi' ) . '</option>';
		}
		if($origine_ethnique == 5){
			echo '<option value="5" selected>' . esc_html( 'Africaines' , 'monemploi' ) . '</option>';
		} else {
			echo '<option value="5">' . esc_html( 'Africaines' , 'monemploi' ) . '</option>';
		}
		if($origine_ethnique == 6){
			echo '<option value="6" selected>' . esc_html( 'Asiatiques' , 'monemploi' ) . '</option>';
		} else {
			echo '<option value="6">' . esc_html( 'Asiatiques' , 'monemploi' ) . '</option>';
		}
		if($origine_ethnique == 7){
			echo '<option value="7" selected>' . esc_html( 'Océanie' , 'monemploi' ) . '</option>';
		} else {
			echo '<option value="7">' . esc_html( 'Océanie' , 'monemploi' ) . '</option>';
		}
		if($origine_ethnique == 8){
			echo '<option value="8" selected>' . esc_html( 'Autres origines ethniques et culturelles' , 'monemploi' ) . '</option>';
		} else {
			echo '<option value="8">' . esc_html( 'Autres origines ethniques et culturelles' , 'monemploi' ) . '</option>';
		}
	echo '</select>';
	} else {
		if($origine_ethnique == 1){
			echo esc_html( 'Nord-américaines' , 'monemploi' );
		}
		if($origine_ethnique == 2){
			echo esc_html( 'Européennes' , 'monemploi' );
		}
		if($origine_ethnique == 3){
			echo esc_html( 'Caraïbes' , 'monemploi' );
		}
		if($origine_ethnique == 4){
			echo esc_html( 'Amérique latine - centrale et du Sud' , 'monemploi' );
		}
		if($origine_ethnique == 5){
			echo esc_html( 'Africaines' , 'monemploi' );
		}
		if($origine_ethnique == 6){
			echo esc_html( 'Asiatiques' , 'monemploi' );
		}
		if($origine_ethnique == 7){
			echo esc_html( 'Océanie' , 'monemploi' );
		}
		if($origine_ethnique == 8){
			echo esc_html( 'Autres origines ethniques et culturelles' , 'monemploi' );
		}
	}
	echo '<br />';
	
	
	$autochtone = get_user_meta( $userid, 'my_autochtone_key', true );
	echo '<p style="font-weight: 600;">Identification comme Autochtone</p>';
	if($edit == 1){
	echo '<select name="autochtone" class="autochtone" id="autochtone">';
		if($autochtone == 0 || $autochtone == ''){
			echo '<option value="0" selected>' . esc_html( 'Choisissez une valeur' , 'monemploi' ) . '</option>';
		} else {
			echo '<option value="0">' . esc_html( 'Choisissez une valeur' , 'monemploi' ) . '</option>';
		}
		if($autochtone == 1){
			echo '<option value="1" selected>' . esc_html( 'Ne souhaite pas repondre' , 'monemploi' ) . '</option>';
		} else {
			echo '<option value="1">' . esc_html( 'Ne souhaite pas repondre' , 'monemploi' ) . '</option>';
		}
		if($autochtone == 2){
			echo '<option value="2" selected>' . esc_html( 'Oui' , 'monemploi' ) . '</option>';
		} else {
			echo '<option value="2">' . esc_html( 'Oui' , 'monemploi' ) . '</option>';
		}
		if($autochtone == 3){
			echo '<option value="3" selected>' . esc_html( 'Non' , 'monemploi' ) . '</option>';
		} else {
			echo '<option value="3">' . esc_html( 'Non' , 'monemploi' ) . '</option>';
		}
	echo '</select>';
	} else {
		if($autochtone == 1){
			echo esc_html( 'Ne souhaite pas repondre' , 'monemploi' );
		}
		if($autochtone == 2){
			echo esc_html( 'Oui' , 'monemploi' );
		}
		if($autochtone == 3){
			echo esc_html( 'Non' , 'monemploi' );
		}
	}
	
	echo '<br />';
	
	$handicap = get_user_meta( $userid, 'my_handicap_key', true );
	echo '<p style="font-weight: 600;">Personne en situation d&#8216;handicap</p>';
	if($edit == 1){
	echo '<select name="handicap" class="handicap" id="handicap">';
		if($handicap == 0 || $handicap == ''){
			echo '<option value="0" selected>' . esc_html( 'Choisissez une valeur' , 'monemploi' ) . '</option>';
		} else {
			echo '<option value="0">' . esc_html( 'Choisissez une valeur' , 'monemploi' ) . '</option>';
		}
		if($handicap == 1){
			echo '<option value="1" selected>' . esc_html( 'Ne souhaite pas repondre' , 'monemploi' ) . '</option>';
		} else {
			echo '<option value="1">' . esc_html( 'Ne souhaite pas repondre' , 'monemploi' ) . '</option>';
		}
		if($handicap == 2){
			echo '<option value="2" selected>' . esc_html( 'Oui' , 'monemploi' ) . '</option>';
		} else {
			echo '<option value="2">' . esc_html( 'Oui' , 'monemploi' ) . '</option>';
		}
		if($handicap == 3){
			echo '<option value="3" selected>' . esc_html( 'Non' , 'monemploi' ) . '</option>';
		} else {
			echo '<option value="3">' . esc_html( 'Non' , 'monemploi' ) . '</option>';
		}
	echo '</select>';
	} else {
		if($handicap == 1){
			echo esc_html( 'Ne souhaite pas repondre' , 'monemploi' );
		}
		if($handicap == 2){
			echo esc_html( 'Oui' , 'monemploi' );
			echo '<div class="handicap-class" style="display: none;">2</div>';
		}
		if($handicap == 3){
			echo esc_html( 'Non' , 'monemploi' );
		}
	} 
	
	echo '<br />';
	
	if($handicap == 2){
		echo '<div class="handicap_wrapper">';
	} else {
		echo '<div class="handicap_wrapper" style="display: none;">';
	}
		$handicap_info = get_user_meta( $userid, 'my_handicap_info_key', true );
		echo '<p style="font-weight: 600;">Si vous avez un handicap, expliquer le</p>';
		if($edit == 1){
			if($handicap_info == ''){
				echo '<input type="text" name="handicap_info" class="handicap_info" id="handicap_info">';
			} else {
				echo '<input type="text" name="handicap_info" class="handicap_info" id="handicap_info" value="' . $handicap_info . '">';
			}
		} else {
			if($handicap == 2){
				echo $handicap_info;
			}
		echo '<br>';
		}
	echo '</div>';
	
	echo '<br />';
	
	if($edit == 1){
		echo '<div class="question-job-wrapper"></div>';
		echo '<button class="question_job" data-object-id="' . $userid . '">Sauvegarder</button>';
	} 
		
}
add_shortcode('monemploi-user-dashboard', 'monemploi_user_dashboard');