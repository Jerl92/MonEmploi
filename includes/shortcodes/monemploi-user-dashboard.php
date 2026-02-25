<?php

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
							echo '<div>'. get_user_meta( $userid, 'certification_description_'.$unique_string_certification, true) .'</div>';
						
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
							echo '<div>'. get_user_meta( $userid, 'school_description_'.$unique_string_school, true) .'</div>';
						
						echo '</div>';
								
			}
		}

	
	echo '</div>';	
		
}
add_shortcode('monemploi-user-dashboard', 'monemploi_user_dashboard');