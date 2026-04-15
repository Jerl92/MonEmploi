<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function employee_dashboard() {
	if ($_GET['user']) {
	
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
			
				echo '<h1>'. $get_user_by_username->user_firstname . ' ' . $get_user_by_username->user_lastname . ' - ' . get_user_meta($userid, 'company_key', true) . '</h1>';
				
				?><div><?php 
				   
				    $user_id = intval($userid); // Replace with the desired user ID
				    $userdata = get_userdata( $user_id );
				    echo '<div style="display: block;">';
					$cover_photo = get_user_meta($user_id, 'cover_photo', true);
					$cover_url = wp_get_attachment_url($cover_photo);
					if($cover_url){
						echo '<img src="'. $cover_url .'" style="width: 100%; height: 300px; object-fit: cover; object-position: center;">';
				    	} else {
					    echo 'Cover image not found.';
					}
				    
				    // Get the URL of the profile picture with a specific size (e.g., 150x150 pixels)
				    	$user_avatar = get_user_meta($user_id, 'user_avatar', true);
					$image_url = wp_get_attachment_url($user_avatar);
					
					if ( $image_url ) {
					    echo '<img src="' . esc_url( $image_url ) . '" style="border-radius: 50%; width: 150px ">';
					} else {
					    echo 'Profile image not found.';
					}
				   echo '</div>';
									    
				    
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
				    echo get_user_meta($user_id, 'phone_key', true);
				    if(get_user_meta($user_id, 'poste_key', true) != ''){
				    	echo ' - ';
				    	echo get_user_meta($user_id, 'poste_key', true);
				    }
				    echo ' - ';
				    echo $userdata->user_email;	    
				    echo '<br>';

				 ?></div> 
				 
				 <?php do_shortcode( '[monemploi-user-dashboard]' ); ?>
				 
				 <h4>Avis</h4>
			
					<?php
					
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
				 
					 <div class="avis-message-employer-wrapper" style="padding-bottom: 15px;">
						<?php if(is_user_logged_in() && $allready_avis == 0){ ?>
							
							<div style="padding-bottom: 15px;">
							
								<div class="avis-error-employer"></div>
								
								<label>Avis</label>
								<textarea id="avis-message-employer" name="avis-message-employer" class="avis-message-employer" rows="5" cols="30"></textarea>
								<div class="number-of-char"></div>
								
								<label>Ponctualité</label>
								<input type="number" id="ponctualite-employer" name="ponctualite-employer" class="ponctualite-employer" min="0" max="5" step=".01">
								
								<label>Connaisance</label>
								<input type="number" id="connaisance-employer" name="connaisance-employer" class="connaisance-employer" min="0" max="5" step=".01">
								
								<label>Attitude</label>
								<input type="number" id="attitude-employer" name="attitude-employer" class="attitude-employer" min="0" max="5" step=".01">
								
							</div>
								
							<button class="avis-employer-send" data-object-id="<?php echo $user_id; ?>">
								<?php esc_html_e( 'Soumettre', 'monemploi' ); ?>
							</button>
						
						<?php } ?>
					</div>
					
			                <button class="show-hide-avis-employer">Afficher les avis</button>
			                <br>
					<div class="avis-employer-wrapper" style="display: none; padding-bottom: 15px;">
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
													<h3 class="ticket-head" id="response-<?php echo esc_attr($i); ?>" style="width: calc(100% - 25px);">
												<?php $userid = $get_user->ID; ?>
												<?php $user_meta = get_userdata($userid); ?>
												<?php $user_role = $user_meta->roles[0]; ?>
												<?php um_fetch_user( $userid ); ?>
												<?php if($user_role == 'employeur'){ ?>
													<a href="<?php get_site_url(); ?>/employeur/?user=<?php echo $user_meta->user_login ?>"><?php echo $get_user->display_name; ?></a> - <?php echo um_user('name_org'); ?>
													<?php um_reset_user(); ?>
												<?php } elseif($user_role == 'employer'){ ?>
													<a href="<?php get_site_url(); ?>/employee/?user=<?php echo $user_meta->user_login ?>"><?php echo $get_user->display_name; ?></a> - <?php echo um_user('name_org'); ?>
													<?php um_reset_user(); ?>
												<?php } ?>

													</h3>
													<?php if (intval($avis->post_author) == intval(get_current_user_id())){ ?>
													<div class="delete-avis-employer" style="width: 25px; padding-top: 25px;" data-object-id="<?php echo $avis->ID; ?>" data-object-string="<?php echo $ramdonstring; ?>" data-object-userid="<?php echo $userid; ?>">												<i class="material-icons">
															delete
														</i>
													</div>
													<?php } ?>
												</div> <!-- /.response-head -->
											</div>
											<div class="ns-col-sm-3 response-dates">
												<a href="#response-<?php echo esc_attr($i); ?>" class="response-bookmark ns-small"><?php echo date( 'd M Y h:iA', strtotime( $avis->post_date ) ); ?></a>
											</div>
											<?php
														
											echo $avis->post_content;
											echo '<br>';
											echo 'Ponctualité:';			
											echo get_user_meta( $avis->ID, 'ponctualite_key', true);
											for ($x = 1; $x <= get_user_meta( $avis->ID, 'ponctualite_key', true); $x++) {
												echo '<span class="fa fa-star checked"></span>';
											}
											echo ' - ';
											echo 'Connaisance:';
											echo get_user_meta( $avis->ID, 'connaisance_key', true);
											for ($x = 1; $x <= get_user_meta( $avis->ID, 'connaisance_key', true); $x++) {
												echo '<span class="fa fa-star checked"></span>';
											}
											echo ' - ';
											echo 'Attitude:';
											echo get_user_meta( $avis->ID, 'attitude_key', true);
											for ($x = 1; $x <= get_user_meta( $avis->ID, 'attitude_key', true); $x++) {
												echo '<span class="fa fa-star checked"></span>';
											}
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
						
						echo '<span>Moyenne</span>';
						echo '<div class="moyenne-score-wrapper" style="padding-bottom: 25px;">';	
							echo 'Poctualité:';						
							echo round($ponctualite_moyenne/$i, 2);
							for ($x = 1; $x <= round($ponctualite_moyenne/$i, 2); $x++) {
								echo '<span class="fa fa-star checked"></span>';
							}
							echo ' - ';
							echo 'Connaisance:';
							echo round($connaisance_moyenne/$i, 2);
							for ($x = 1; $x <= round($connaisance_moyenne/$i, 2); $x++) {
								echo '<span class="fa fa-star checked"></span>';
							}
							echo ' - ';
							echo 'Attitude:';
							echo round($attitude_moyenne/$i, 2);
							for ($x = 1; $x <= round($attitude_moyenne/$i, 2); $x++) {
								echo '<span class="fa fa-star checked"></span>';
							}
						echo '</div>';
			
		} else  {
		
		
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
		   
		   	?><div><?php
			    $user_id = $user->ID; // Replace with the desired user ID
			    $usermetadata = get_user_meta(get_current_user_id());
			    $usermetadata_ = get_user_meta($user_id);
			    um_fetch_user( $user_id );
			    echo '<a href="'. get_site_url() .'/employee/?user='. $user->user_nicename .'">' . $user->user_nicename. '</a>';
			    echo ' - ';
			    echo '<a href="'. get_site_url() .'/profile/?user='. $user->user_nicename .'">Profile</a>';
			    echo ' - ';
			    echo um_user('name_org');
			    echo ' - ';
		 	    echo um_user('first_name');
		 	    echo ' ';
			    echo um_user('last_name');
			    $field_data = $usermetadata['Code_postal'];
			    	$field_data_ = $usermetadata_['Code_postal'];
			    	if($field_data && $field_data_){
					echo '<span class="autocompleteDeparture">';
						echo '<span class="autocompleteDeparture_'.  $i . '" style="display:none;">'. implode($field_data) . '</span>';
						echo '<span class="autocompleteArrival_' . $i . '" style="display: none;">' . implode($field_data_) . '</span>';
						echo ' - <span class="distance_' . $i . '"></span>';
					echo '</span>';
				}
			    um_reset_user();
			
			 ?></div><?php 
			 
			 $i++;
	    		
	    	  }
    		
    		  ?></div><?php 
    		  
    		 } else {
    		 
    		 	echo '<h3>Vous navez pas les autorisation pour consulter cette page.</h3>';
    		 	
    		 		$current_user = wp_get_current_user();
				$user_meta = get_userdata($current_user->ID);
				$user_role = $user_meta->roles[0];
				if($user_role == 'employer'){
					
					header("Location: get_site_url()/employee/?user=$user_meta->user_nicename");
					die();
				
				}
    		 
    		 }
	
	} 
}
add_shortcode('employee-dashboard', 'employee_dashboard');

?>