<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function employee_dashboard() {
	if ($_GET['user'] && !isset($_GET['question']) && !isset($_GET['employeur'])) {
	
		$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . strtok($_SERVER['REQUEST_URI'], '?');
		
		$url = $_SERVER['REQUEST_URI'];
	
		// Extract the query component (e.g., "name=John&age=30...")
		$queryString = parse_url($url, PHP_URL_QUERY);
			
		// Parse the query string into a resulting array
		parse_str($queryString, $params);
		
		if ($_GET['user']) {
			$user_meta = $_GET['user'];
			$get_user_by_username = get_user_by('login', $user_meta);
			$userid = $get_user_by_username->ID;
		} else {
			$current_user = wp_get_current_user();
			$userid = $current_user->ID;
		} 
			$user_meta = get_userdata($userid);
			$user_role = $user_meta->roles[0];
			
				if ($_GET['add_avis']) {
				        echo "<h3>L&#8216;avis #". $_GET['add_avis'] ." à été ajouter.</h3>";
				}
				
				if ($_GET['delete_avis']) {
				        echo "<h3>L&#8216;avis #". $_GET['delete_avis'] ." à été supprimé.</h3>";
				}
			
				echo '<h1>'. $get_user_by_username->user_firstname . ' ' . $get_user_by_username->user_lastname . ' - ' . get_user_meta($userid, 'company_key', true) . '</h1>';
				
				?><div><?php 
				   
				    echo '<div class="" style="position: relative;">';
					    $user_id = intval($userid); // Replace with the desired user ID
					    $userdata = get_userdata( $user_id );
					    echo '<div class="container-image-cover">';
	    					$cover_photo = get_user_meta($user_id, 'cover_photo', true);
	    					$cover_url = wp_get_attachment_url($cover_photo);
	    					if($cover_url){
	    						echo '<img src="'. $cover_url .'" class="image-fond">';
	    				    	} else {
	    						//
	    					}
	    				    
	    				    // Get the URL of the profile picture with a specific size (e.g., 150x150 pixels)
	    				    $user_avatar = get_user_meta($user_id, 'user_avatar', true);
	    					$image_url = wp_get_attachment_url($user_avatar);
	    					
	    					if ( $image_url ) {
	    					   	echo '<img src="' . esc_url( $image_url ) . '" class="image-dessus">';
	    					} else {
	    						//
	    					}
						
					    echo '</div>';
										    
					    $hide_adresse = get_user_meta( $user_id, 'hide_adresse_key', true);
					    $hide_contact = get_user_meta( $user_id, 'hide_contact_key', true);
					    
					    echo $get_user_by_username->user_nicename;
					    echo ' - ';
					    echo $get_user_by_username->user_firstname;
					    echo ' ';
					    echo $get_user_by_username->user_lastname;
					    if(get_user_meta($user_id, 'company_key', true) != ''){
						    echo ' - ';
						    echo get_user_meta($user_id, 'company_key', true);
					    }	
					    echo '<br>';
					    if($hide_adresse == 0 || $hide_adresse == ''){
						    echo get_user_meta($user_id, 'adresse_key', true);
						    echo ' - ';
						    echo get_user_meta($user_id, 'city_key', true);
						    echo ' - ';
						    echo get_user_meta($user_id, 'province_key', true);
						    echo ' - ';
						    echo get_user_meta($user_id, 'country_key', true);
						    echo ' - ';
						    echo get_user_meta($user_id, 'postal_code_key', true);
						    echo '<br>';
					    }
					    if($hide_contact == 0 || $hide_contact == ''){
						    echo get_user_meta($user_id, 'phone_key', true);
						    if(get_user_meta($user_id, 'poste_key', true) != ''){
						    	echo ' - ';
						    	echo get_user_meta($user_id, 'poste_key', true);
						    }
						    echo ' - ';
						    echo $get_user_by_username->user_email;	    
						    echo '<br>';
					    }
					    echo '<br>';
					    
					    $disable_chat = get_user_meta( $user_id, 'disable_chat_key', true);
					    if($disable_chat == 0 || $disable_chat == ''){
						    if(!$cover_url && !$image_url){
						    	echo '<div class="" style="position: absolute; right: 0; top: 25px; display: flex; width: auto;">';
						    }
						    if($cover_url && !$image_url){
						    	echo '<div class="" style="position: absolute; right: 0; top: 300px; display: flex; width: auto;">';
						    }
						    if(!$cover_url && $image_url){
						    	echo '<div class="" style="position: absolute; right: 0; top: 125px; display: flex; width: auto;">';
						    }
						    if($cover_url && $image_url){
						    	echo '<div class="" style="position: absolute; right: 0; top: 325px; display: flex; width: auto;">';
						    }
		    					if($get_user_by_username->ID != get_current_user_id() && is_user_logged_in()){
		                           			 echo '<div class="chat-icons">';
		                                			echo '<a href="' . get_site_url() .'/chat/?username=' . $get_user_by_username->user_nicename . '"><span class="material-icons">mail</span></a>';
								 echo '</div>';
							}
						     echo '</div>';
				 	   }
					    
				    echo '</div>';
				    
				 ?></div> 
				 
				 <?php do_shortcode( '[monemploi-user-dashboard]' ); ?>
				 
				 <?php echo '<h3><a href="'.$current_url.'?user='.$params[user].'&question=true">Questions liées à la candidature</a></h3>'; ?>
				 
				 <?php
				 
				 $args = array(
				    'role'    => 'employeur',
				    'orderby' => 'date',
				    'order'   => 'DESC'
				);
				$users = get_users( $args );
				
				foreach ( $users as $user ) {
					$my_employees = get_user_meta( $user->ID, 'my_employee_key', true);
				   	foreach ( $my_employees as $employee ) {
				   		if(floatval($employee) == floatval($userid)){
				   			$user_employeur_id = $user->ID;
				   		}
				   	}
				}
				 
				 if($user_employeur_id == get_current_user_id() || $userid == get_current_user_id()){
				 	echo '<h3><a href="'.$current_url.'?user='.$params[user].'&employeur=true">Section réservée à l&#8216;employeur</a></h3>';
				 }					 		 		
				 
				 if ( is_user_logged_in() ) {
        
				        $profile_id = $get_user_by_username->ID; // The ID of the user whose profile is being viewed
				        $viewer_id = get_current_user_id();    // The ID of the person currently looking at the profile
				
				        // Don't track if the user is looking at their own profile
				        if ( $profile_id !== $viewer_id ) {
				            $viewers = get_user_meta( $profile_id, 'profile_viewers', true );
				            
				            if ( ! is_array( $viewers ) ) {
				                $viewers = array();
				            }
				            
				            $i = 0;
				            $already_frist = 0;
				            foreach($viewers as $viewer){
						    foreach($viewer as $key => $item){
							     if($key == $viewer_id){
								     if($i == 0){
								     	$already_frist = 1;							     
								     }
							     }
							     $i++;
						    }
					    }
				            		
				            if($already_frist == 0){		
						// Store viewer ID and the time they visited
						$viewers_new[$viewer_id] = current_time( 'timestamp' );
						
						array_unshift($viewers, $viewers_new);
						update_user_meta( $profile_id, 'profile_viewers', $viewers );
									                
						$viewers = get_user_meta( $profile_id, 'profile_viewers', true );
						
						$viewers_slice = array_slice($viewers, 0, 20, true);
						
						update_user_meta( $profile_id, 'profile_viewers', $viewers_slice );	
				           }
				                
				        }
				    }
				    				    
				    if(get_current_user_id() == $get_user_by_username->ID){
				    $get_viewers = get_user_meta( $get_user_by_username->ID, 'profile_viewers', true );
				      	echo '<h4>Visite récente</h4>';
					    foreach($get_viewers as $viewers){
					    foreach($viewers as $key => $item){
					    	$viewer_id = $key;
					    	$get_viewer_by_id = get_user_by('ID', $viewer_id);
					    	$user_id_viewer = $get_viewer_by_id->ID;
					    	$user_info_viewer = get_userdata($user_id_viewer);
					        $user_roles_viewer = $user_info_viewer->roles;
					        if(implode($user_roles_viewer) == 'employeur'){
						    $company_key = get_user_meta($get_viewer_by_id->ID, 'company_key', true);
						    echo '<a href="'. get_site_url() .'/employeur/?user='. $get_viewer_by_id->user_nicename .'">'. $get_viewer_by_id->user_nicename .'</a>';
						    echo ' - ';
					 	    echo $get_viewer_by_id->user_firstname;
					 	    echo ' ';
						    echo $get_viewer_by_id->user_lastname;
						    if($company_key != ''){
							    echo ' - ';
							    echo $company_key;
						    }
						    echo ' - ';
						    echo get_user_meta($get_viewer_by_id->ID, 'city_key', true);
						    echo ' - ';
						    echo 'Employeur';
						    echo ' - ';
						    echo date('Y-m-d H:i:s', $item);
						    echo '<br>';
						}
						
						if(implode($user_roles_viewer) == 'employer'){
						    $company_key = get_user_meta($get_viewer_by_id->ID, 'company_key', true);
						    echo '<a href="'. get_site_url() .'/employee/?user='. $get_viewer_by_id->user_nicename .'">'. $get_viewer_by_id->user_nicename .'</a>';
						    echo ' - ';
					 	    echo $get_viewer_by_id->user_firstname;
					 	    echo ' ';
						    echo $get_viewer_by_id->user_lastname;
						    if($company_key != ''){
							    echo ' - ';
							    echo $company_key;
						    }
						    echo ' - ';
						    echo get_user_meta($get_viewer_by_id->ID, 'city_key', true);
						    echo ' - ';
						    echo 'Employer';
						    echo ' - ';
						    echo date('Y-m-d H:i:s', $item);
						    echo '<br>';
						}
					    }
					}
				    }
				    
				    ?>
				 
				 <h4>Avis</h4>
			
					<?php
					
					echo '<div class="moyenne_employee_scores" style="display:block;">';
						echo '<span>Moyenne</span>';
						echo '<div class="moyenne-score-wrapper">';	
							echo 'Poctualité:';						
	    					        echo '<span class="ponctualite_moyenne_round_up"></span>';
							echo ' - ';
							echo 'Connaisance:';
							echo '<span class="connaisance_moyenne_round_up"></span>';
							echo ' - ';
							echo 'Attitude:';
							echo '<span class="attitude_moyenne_round_up"></span>';					
						echo '</div>';
					echo '</div>';
					echo '<div class="moyenne_employee_no" style="display:none;">';
						echo '<p>Auccun avis sur cette employer</p>'; 
					echo '</div>';
					
					$allready_avis = 0;
									
					$get_args_avis = array( 
						'post_type' => 'avis',
						'posts_per_page' => -1,
						'orderby' => 'date',
						'order' => 'DESC'
					); 
					
					$get_avis = get_posts( $get_args_avis );
					
					if($get_avis){
					
						foreach($get_avis as $avis){
							if(get_user_meta( $avis->ID, 'authorid_key', true) == $user_id && $avis->post_author == get_current_user_id()){
								$allready_avis = 1;
							}
						}
						wp_reset_query(); 
						wp_reset_postdata();
					} ?>
				 
				 	<?php if(is_user_logged_in() && $allready_avis == 0 && get_current_user_id() != $userid){ ?>
				 	<form action="<?php $_SERVER['REQUEST_URI'] ?>" method="post">
						<div class="avis-message-employer-wrapper" style="padding-bottom: 15px;">
								
							<div style="padding-bottom: 15px;">
								
								<textarea id="avis-message-employer" name="avis-message-employer" class="avis-message-employer" rows="5" cols="30" required></textarea>
								<div class="number-of-char"></div>
								
								<label>Ponctualité</label>
								<input type="number" id="ponctualite-employer" name="ponctualite-employer" class="ponctualite-employer" min="0" max="5" step=".01" required>
								
								<label>Connaisance</label>
								<input type="number" id="connaisance-employer" name="connaisance-employer" class="connaisance-employer" min="0" max="5" step=".01" required>
								
								<label>Attitude</label>
								<input type="number" id="attitude-employer" name="attitude-employer" class="attitude-employer" min="0" max="5" step=".01" required>
								
							</div>
								
							<?php
							echo '<button class="avis-employer-send">';
								echo '<input type="hidden" name="userid" value="'. $user_id .'" />';
								echo '<input type="hidden" name="action" value="avis_employer_send" />';
								echo esc_html_e( 'Soumettre', 'monemploi' );
							echo '</button>';
							?>
								
						</div>
					</form>
					<?php } ?>
					
					<div class="avis-employer-wrapper">
						<?php 
						
						$i = 0;
																	
						$ponctualite_moyenne = 0;
						$connaisance_moyenne = 0;
						$attitude_moyenne = 0;
						
						$args = array( 
							'post_type' => 'avis',
							'posts_per_page' => -1,
							'orderby' => 'date',
							'order' => 'DESC'
						); 
					
						$get_avis = get_posts( $args );
						
						if($get_avis){
					
							foreach($get_avis as $avis){
							
								if(get_user_meta( $avis->ID, 'authorid_key', true) == $user_id){
		
								
									echo '<div style="padding-bottom: 25px;">';
																				
										$get_user = get_user_by('id', $avis->post_author);
										$ramdonstring = generate_secure_string();
									
										?>
										<div class="avis-response-cards-wrapper <?php echo $ramdonstring; ?>">
											<div class="ns-col-sm-9">
												<div class="response-head" style="display: flex;">
													<h3 class="ticket-head" id="response-<?php echo esc_attr($i); ?>" style="width: calc(100% - 75px);">
												<?php $userid = $get_user->ID; ?>
												<?php $user_meta = get_userdata($userid); ?>
												<?php $user_role = $user_meta->roles[0]; ?>
												<?php $nicename = get_user_meta( $avis->ID, 'nicename_key', true); ?>
												<?php if($user_role == 'employeur'){ ?>
													<a href="<?php get_site_url(); ?>/employeur/?user=<?php echo $user_meta->user_login ?>"><?php echo $nicename; ?></a> - <?php echo get_user_meta($user_meta->ID, 'company_key', true); ?>
												<?php } elseif($user_role == 'employer'){ ?>
													<a href="<?php get_site_url(); ?>/employee/?user=<?php echo $user_meta->user_login ?>"><?php echo $nicename; ?></a> - <?php echo get_user_meta($user_meta->ID, 'company_key', true); ?>
												<?php } ?>
													</h3>
													<?php if (intval($avis->post_author) == intval(get_current_user_id())){ ?>
													<div class="delete-avis-employer" style="width: 75px; margin-left: auto; margin-right: auto;">																								
														<form action="<?php $_SERVER['REQUEST_URI'] ?>" method="post">
												                        <input type="hidden" name="avisid" value="<?php echo $avis->ID; ?>" />
												                        <input type="hidden" name="action" value="delete_avis_employer" />
												                        <button type="submit" name="submit" style="padding: 0; margin: 0;">
												                        	<i class="material-icons">
												            				delete
												            			</i>
												            		</button>
											            		</form>
													</div>
													<?php } ?>
												</div> <!-- /.response-head -->
											</div>
											<div class="ns-col-sm-3 response-dates">
												<a href="#response-<?php echo esc_attr($i); ?>" class="response-bookmark ns-small"><?php echo date( 'd M Y h:iA', strtotime( $avis->post_date ) ); ?></a>
											</div>
											<?php
														
											echo wpautop($avis->post_content);
											echo 'Ponctualité:';			
											echo get_user_meta( $avis->ID, 'ponctualite_key', true);
											echo ' - ';
											echo 'Connaisance:';
											echo get_user_meta( $avis->ID, 'connaisance_key', true);
											echo ' - ';
											echo 'Attitude:';
											echo get_user_meta( $avis->ID, 'attitude_key', true);
										echo '</div>';
										
									echo '</div>';
									
									$ponctualite_moyenne += floatval(get_user_meta( $avis->ID, 'ponctualite_key', true));
									$connaisance_moyenne += floatval(get_user_meta( $avis->ID, 'connaisance_key', true));
									$attitude_moyenne += floatval(get_user_meta( $avis->ID, 'attitude_key', true));
									
									$i++;
								
								}
								
							}
						
							wp_reset_query(); 
							wp_reset_postdata();	
						}
						
						echo '</div>';
						
						echo '<div style="display:none;">';
							echo '<span>Moyenne</span>';
							echo '<div class="moyenne-score-wrapper" style="padding-bottom: 25px;">';	
								echo 'Poctualité:';						
								$ponctualite_moyenne_round = round($ponctualite_moyenne/$i, 2);
		    					        echo '<span class="ponctualite_moyenne_round">'.$ponctualite_moyenne_round.'</span>';
								echo ' - ';
								echo 'Connaisance:';
								$connaisance_moyenne_round = round($connaisance_moyenne/$i, 2);
								echo '<span class="connaisance_moyenne_round">'.$connaisance_moyenne_round.'</span>';
								echo ' - ';
								echo 'Attitude:';
								$attitude_moyenne_round = round($attitude_moyenne/$i, 2);
								echo '<span class="attitude_moyenne_round">'.$attitude_moyenne_round.'</span>';					
							echo '</div>';
						echo '</div>';
			
		} else if ($_GET['user'] && $_GET['question']) { 
		
		
		if ($_GET['user']) {
			$user_meta = $_GET['user'];
			$get_user_by_username = get_user_by('login', $user_meta);
			$userid = $get_user_by_username->ID;
		} else {
			$current_user = wp_get_current_user();
			$userid = $current_user->ID;
		} 
			$user_meta = get_userdata($userid);
			$user_role = $user_meta->roles[0];
			$current_user = wp_get_current_user();
			if($current_user->ID === $userid) {
				$edit = 1;
			} else {
				$edit = 0;
			}
			
				echo '<h1>'. $get_user_by_username->user_firstname . ' ' . $get_user_by_username->user_lastname . ' - ' . get_user_meta($userid, 'company_key', true) . '</h1>';
				
				?><div><?php 
				   
				    echo '<div class="" style="position: relative;">';
					    $user_id = intval($userid); // Replace with the desired user ID
					    $userdata = get_userdata( $user_id );
					    echo '<div class="container-image-cover">';
	    					$cover_photo = get_user_meta($user_id, 'cover_photo', true);
	    					$cover_url = wp_get_attachment_url($cover_photo);
	    					if($cover_url){
	    						echo '<img src="'. $cover_url .'" class="image-fond">';
	    				    	} else {
	    						//
	    					}
	    				    
	    				    // Get the URL of the profile picture with a specific size (e.g., 150x150 pixels)
	    				    $user_avatar = get_user_meta($user_id, 'user_avatar', true);
	    					$image_url = wp_get_attachment_url($user_avatar);
	    					
	    					if ( $image_url ) {
	    					   	echo '<img src="' . esc_url( $image_url ) . '" class="image-dessus">';
	    					} else {
	    						//
	    					}
						
					    echo '</div>';
										    
					    $hide_adresse = get_user_meta( $user_id, 'hide_adresse_key', true);
					    $hide_contact = get_user_meta( $user_id, 'hide_contact_key', true);
					    
					    echo $get_user_by_username->user_nicename;
					    echo ' - ';
					    echo $get_user_by_username->user_firstname;
					    echo ' ';
					    echo $get_user_by_username->user_lastname;
					    if(get_user_meta($user_id, 'company_key', true) != ''){
						    echo ' - ';
						    echo get_user_meta($user_id, 'company_key', true);
					    }	
					    echo '<br>';
					    if($hide_adresse == 0 || $hide_adresse == ''){
						    echo get_user_meta($user_id, 'adresse_key', true);
						    echo ' - ';
						    echo get_user_meta($user_id, 'city_key', true);
						    echo ' - ';
						    echo get_user_meta($user_id, 'province_key', true);
						    echo ' - ';
						    echo get_user_meta($user_id, 'country_key', true);
						    echo ' - ';
						    echo get_user_meta($user_id, 'postal_code_key', true);
						    echo '<br>';
					    }
					    if($hide_contact == 0 || $hide_contact == ''){
						    echo get_user_meta($user_id, 'phone_key', true);
						    if(get_user_meta($user_id, 'poste_key', true) != ''){
						    	echo ' - ';
						    	echo get_user_meta($user_id, 'poste_key', true);
						    }
						    echo ' - ';
						    echo $get_user_by_username->user_email;	    
						    echo '<br>';
					    }
					    echo '<br>';
					    
					    $disable_chat = get_user_meta( $user_id, 'disable_chat_key', true);
					    if($disable_chat == 0 || $disable_chat == ''){
						    if(!$cover_url && !$image_url){
						    	echo '<div class="" style="position: absolute; right: 0; top: 25px; display: flex; width: auto;">';
						    }
						    if($cover_url && !$image_url){
						    	echo '<div class="" style="position: absolute; right: 0; top: 300px; display: flex; width: auto;">';
						    }
						    if(!$cover_url && $image_url){
						    	echo '<div class="" style="position: absolute; right: 0; top: 125px; display: flex; width: auto;">';
						    }
						    if($cover_url && $image_url){
						    	echo '<div class="" style="position: absolute; right: 0; top: 325px; display: flex; width: auto;">';
						    }
		    					if($get_user_by_username->ID != get_current_user_id()){
		                           			 echo '<div class="chat-icons">';
		                                			echo '<a href="' . get_site_url() .'/chat/?username=' . $get_user_by_username->user_nicename . '"><span class="material-icons">mail</span></a>';
								 echo '</div>';
							}
						     echo '</div>';
				 	   }
					    
				    echo '</div>';
				    
				 ?></div><?php
				 
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
						echo esc_html( 'Aucune valeur a ete sectionné' , 'monemploi' );
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
						echo esc_html( 'Aucune valeur a ete sectionné' , 'monemploi' );
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
							echo esc_html( 'Aucune valeur a ete sectionné' , 'monemploi' );
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
					if($dossier_criminel == 0 || $dossier_criminel == ''){
						echo esc_html( 'Aucune valeur a ete sectionné' , 'monemploi' );
					}
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
						echo esc_html( 'Aucune valeur a ete sectionné' , 'monemploi' );
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
					if($origine_ethnique == 0 || $origine_ethnique == ''){
						echo esc_html( 'Aucune valeur a ete sectionné' , 'monemploi' );
					}
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
					if($autochtone == 0 || $autochtone == ''){
						echo esc_html( 'Aucune valeur a ete sectionné' , 'monemploi' );
					}
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
					if($handicap == 0 || $handicap == ''){
						echo esc_html( 'Aucune valeur a ete sectionné' , 'monemploi' );
					}
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
		
		
		} else if ($_GET['user'] && $_GET['employeur']) { 
		
		
		if ($_GET['user']) {
			$user_meta = $_GET['user'];
			$get_user_by_username = get_user_by('login', $user_meta);
			$userid = $get_user_by_username->ID;
		} else {
			$current_user = wp_get_current_user();
			$userid = $current_user->ID;
		} 
			$user_meta = get_userdata($userid);
			$user_role = $user_meta->roles[0];
			$current_user = wp_get_current_user();
			if($current_user->ID === $userid) {
				$edit = 1;
			} else {
				$edit = 0;
			}
			
				echo '<h1>'. $get_user_by_username->user_firstname . ' ' . $get_user_by_username->user_lastname . ' - ' . get_user_meta($userid, 'company_key', true) . '</h1>';
				
				?><div><?php 
				   
				    echo '<div class="" style="position: relative;">';
					    $user_id = intval($userid); // Replace with the desired user ID
					    $userdata = get_userdata( $user_id );
					    echo '<div class="container-image-cover">';
	    					$cover_photo = get_user_meta($user_id, 'cover_photo', true);
	    					$cover_url = wp_get_attachment_url($cover_photo);
	    					if($cover_url){
	    						echo '<img src="'. $cover_url .'" class="image-fond">';
	    				    	} else {
	    						//
	    					}
	    				    
	    				    // Get the URL of the profile picture with a specific size (e.g., 150x150 pixels)
	    				    $user_avatar = get_user_meta($user_id, 'user_avatar', true);
	    					$image_url = wp_get_attachment_url($user_avatar);
	    					
	    					if ( $image_url ) {
	    					   	echo '<img src="' . esc_url( $image_url ) . '" class="image-dessus">';
	    					} else {
	    						//
	    					}
						
					    echo '</div>';
										    
					    $hide_adresse = get_user_meta( $user_id, 'hide_adresse_key', true);
					    $hide_contact = get_user_meta( $user_id, 'hide_contact_key', true);
					    
					    echo $get_user_by_username->user_nicename;
					    echo ' - ';
					    echo $get_user_by_username->user_firstname;
					    echo ' ';
					    echo $get_user_by_username->user_lastname;
					    if(get_user_meta($user_id, 'company_key', true) != ''){
						    echo ' - ';
						    echo get_user_meta($user_id, 'company_key', true);
					    }	
					    echo '<br>';
					    if($hide_adresse == 0 || $hide_adresse == ''){
						    echo get_user_meta($user_id, 'adresse_key', true);
						    echo ' - ';
						    echo get_user_meta($user_id, 'city_key', true);
						    echo ' - ';
						    echo get_user_meta($user_id, 'province_key', true);
						    echo ' - ';
						    echo get_user_meta($user_id, 'country_key', true);
						    echo ' - ';
						    echo get_user_meta($user_id, 'postal_code_key', true);
						    echo '<br>';
					    }
					    if($hide_contact == 0 || $hide_contact == ''){
						    echo get_user_meta($user_id, 'phone_key', true);
						    if(get_user_meta($user_id, 'poste_key', true) != ''){
						    	echo ' - ';
						    	echo get_user_meta($user_id, 'poste_key', true);
						    }
						    echo ' - ';
						    echo $get_user_by_username->user_email;	    
						    echo '<br>';
					    }
					    echo '<br>';
					    
					    $disable_chat = get_user_meta( $user_id, 'disable_chat_key', true);
					    if($disable_chat == 0 || $disable_chat == ''){
						    if(!$cover_url && !$image_url){
						    	echo '<div class="" style="position: absolute; right: 0; top: 25px; display: flex; width: auto;">';
						    }
						    if($cover_url && !$image_url){
						    	echo '<div class="" style="position: absolute; right: 0; top: 300px; display: flex; width: auto;">';
						    }
						    if(!$cover_url && $image_url){
						    	echo '<div class="" style="position: absolute; right: 0; top: 125px; display: flex; width: auto;">';
						    }
						    if($cover_url && $image_url){
						    	echo '<div class="" style="position: absolute; right: 0; top: 325px; display: flex; width: auto;">';
						    }
		    					if($get_user_by_username->ID != get_current_user_id()){
		                           			 echo '<div class="chat-icons">';
		                                			echo '<a href="' . get_site_url() .'/chat/?username=' . $get_user_by_username->user_nicename . '"><span class="material-icons">mail</span></a>';
								 echo '</div>';
							}
						     echo '</div>';
				 	   }
					    
				    echo '</div>';
				    
				 ?></div><?php
				 
				 echo '</div>';
				 
				 echo '<h3>Section réservée à l&#8216;employeur</h3>';
				 
				 echo '<form action="'. $_SERVER['REQUEST_URI'] .'" method="post">';
				 	$user_meta = get_userdata(get_current_user_id());
				 	$user_role = $user_meta->roles[0];
				 
					 $salary = get_user_meta( $userid, 'salary_key', true);
					 echo '<span>Le salaire de l&#8216;employer par heure</span>';
					 echo '<br>';
					 if($user_role == 'employeur'){
						 if($salary == ''){
						 	echo '<input type="number" name="salaire" placeholder="Le salaire de l&#8216;employer" step=".01" >';
						 } else {
						 	echo '<input type="number" name="salaire" placeholder="Le salaire de l&#8216;employer" step=".01" value="'.$salary.'">';
						 }
					 }
					 if($user_role == 'employer'){
						 if($salary == ''){
						 	echo 'Le salaire n&#8216;a pas encore été choisie.';
						 } else {
						 	echo $salary.'$';
						 }
					 }
					 
					if($user_role == 'employeur'){
						echo '<br>';
						echo '<br>';
						echo '<input type="hidden" name="userid" value="'.$userid.'" />';
						echo '<input type="hidden" name="action" value="new_section_employeur" />';
						echo '<button class="ns_submit" type="submit" name="submit">';
							esc_html_e( 'Sauvegrader', 'monemploi' );
						echo '</button>';
					}
					
				echo '</form>';
	
		} else { 
		$current_user = wp_get_current_user();
		$user_meta = get_userdata($current_user->ID);
		$user_role = $user_meta->roles[0];
		if($user_role == 'employeur'){
		
		    $i = 0;
			
	    	    $args = array(
		        'role__in' => array( 'employer' ),
		        'orderby' => 'date',
		        'order'   => 'DESC'
		    );
		
		    $users = get_users( $args );
		    
		   ?><div style="padding-bottom: 25px;"><?php 
		   
		   foreach ($users as $user) {
    		   	$hide_dashboard = get_user_meta( $user->ID, 'hide_dashboard_key', true);
    		   	if($hide_dashboard == 0 || $hide_dashboard == ''){
	    		   	?><div><?php
	    			    $user_id = $user->ID; // Replace with the desired user ID
	    			    $usermetadata = get_user_meta(get_current_user_id());
	    			    $usermetadata_ = get_user_meta($user_id);
	    			    $get_user_by_username = get_user_by('ID', $user_id);
	    			    $company_key = get_user_meta($user_id, 'company_key', true);
	    			    echo '<a href="'. get_site_url() .'/employee/?user='. $user->user_nicename .'">' . $user->user_nicename. '</a>';
	    			    echo ' - ';
	    		 	    echo $get_user_by_username->user_firstname;
	    		 	    echo ' ';
	    			    echo $get_user_by_username->user_lastname;
	    			    if($company_key != ''){
	    			        echo ' - ';
	    			        echo $company_key;
	    			    }
	    			    echo ' - ';
	    			    echo get_user_meta($user_id, 'city_key', true);    			
	    			 ?></div><?php 
	    			 
	    			 $i++;
	    		}
	    	  }
    		
    		  ?></div><?php 
    		  
    		 } else {
    		 
    		 	echo '<h3>Vous n&#8216;avez pas les autorisation pour consulter cette page.</h3>';
    		 	
    		 	$current_user = wp_get_current_user();
    			$user_meta = get_userdata($current_user->ID);
    			$user_role = $user_meta->roles[0];
    			if($user_role == 'employer'){
    					
    				header("Location: " . get_site_url() . "/employee/?user=" . $user_meta->user_nicename . "");
    				
    			}
    		 
    		 }
	
	} 
}
add_shortcode('employee-dashboard', 'employee_dashboard');

?>