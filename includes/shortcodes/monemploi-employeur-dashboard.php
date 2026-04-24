<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function employeur_dashboard() {
    
		if ($_GET['user']) {
			$user_meta = $_GET['user'];
			$get_user_by_username = get_user_by('login', $user_meta);
			$userid = $get_user_by_username->ID;
			$user_meta = get_userdata($userid);
			$user_role = $user_meta->roles[0];
			if($user_role == 'employeur'){
			
				if ($_GET['add_avis']) {
				        echo "<h3>L&#8216;avis #". $_GET['add_avis'] ." à été ajouter.</h3>";
				}
				
				if ($_GET['delete_avis']) {
				        echo "<h3>L&#8216;avis #". $_GET['delete_avis'] ." à été supprimé.</h3>";
				}
			    
				echo '<h1>'. $get_user_by_username->user_firstname . ' ' . $get_user_by_username->user_lastname . ' - ' . get_user_meta($userid, 'company_key', true) . '</h1>';
				
				?><div><?php 
				   
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
				   
				$current_user = wp_get_current_user();
				$user_meta = get_userdata($current_user->ID);
				$user_role = $user_meta->roles[0];
			
				if($user_role == 'employeur'){
				    
					$args = array(
					 'post_type' => 'emploi',
		 			 'author'        =>  $user_id, 
		 			 'post_status'    => array('publish', 'draft', 'future'),
					  'orderby'       =>  'date',
					  'order'         =>  'DESC',
					  'posts_per_page' => -1
					);
				
				} else {
					
					$args = array(
					 'post_type' => 'emploi',
		 			 'author'        =>  $user_id, 
		 			 'post_status'    => array('publish'),
					  'orderby'       =>  'date',
					  'order'         =>  'DESC',
					  'posts_per_page' => -1
					);
				
				}
				
				$posts = get_posts( $args );
				
				echo '<span style="font-weight: 500; font-size: 18px;">Nombre de postes: ' . count($posts) . '</span>';
				
				echo '<br>';
				
				$i = 0;
				
				foreach($posts as $post)  {
				    if ( get_post_status ( $post->ID ) == 'draft' || get_post_status ( $post->ID ) == 'future' ) {
			    		        if(get_current_user_id() == $post->post_author) {
					
							echo '<a href="' . get_permalink( $post->ID ) .'">' .  $post->ID . ' - ' . $post->post_title . '</a>';
							$usermetadata = get_user_meta(get_current_user_id());
							$field_data_adresse = $usermetadata['Adresse'];
							$field_data = $usermetadata['Code_postal'];
							if($field_data){
								echo '<span class="autocompleteDeparture">';
									echo '<span class="autocompleteDeparture_'.  $i . '" style="display:none;">'. $field_data_adresse . ' ' .implode($field_data) . '</span>';
									echo '<span class="autocompleteArrival_' . $i . '" style="display: none;">' . get_post_meta( $post->ID, 'my_code_postal_key', true ) . '</span>';
									echo ' - <span class="distance_' . $i . '"></span> - ';
								echo '</span>';
							}
							echo get_post_meta( $post->ID, 'my_city_key', true );
							$from = strtotime(get_the_date('Y-m-d H:i:s', $post->ID));
							$today = current_time('timestamp');
							$difference = $today - $from;
							$round_difference = round($difference / 60 / 60 / 24, 0);
							if($round_difference < 1){
								echo ' - ' . $round_difference . ' Jour';
							} else {
								echo ' - ' . $round_difference . ' Jours';
							}
							if(get_post_status($post->ID) == 'draft') {
								echo ' - Brouillon';
							} 
							if(get_post_status($post->ID) == 'future') {
								echo ' - Programmer';
							}
							echo '<br>';
							
							$i++;
						
						}
					
					} else {
					
							echo '<a href="' . get_permalink( $post->ID ) .'">' .  $post->ID . ' - ' . $post->post_title . '</a>';
							$usermetadata = get_user_meta(get_current_user_id());
							$field_data_adresse = $usermetadata['Adresse'];
							$field_data = $usermetadata['Code_postal'];
							if($field_data){
								echo '<span class="autocompleteDeparture">';
									echo '<span class="autocompleteDeparture_'.  $i . '" style="display:none;">'. $field_data_adresse . ' ' .implode($field_data) . '</span>';
									echo '<span class="autocompleteArrival_' . $i . '" style="display: none;">' . get_post_meta( $post->ID, 'my_code_postal_key', true ) . '</span>';
									echo ' - <span class="distance_' . $i . '"></span> - ';
								echo '</span>';
							}
							echo ' - ' . get_post_meta( $post->ID, 'my_city_key', true );
							$from = strtotime(get_the_date('Y-m-d H:i:s', $post->ID));
							$today = current_time('timestamp');
							$difference = $today - $from;
							$round_difference = round($difference / 60 / 60 / 24, 0);
							if($round_difference < 1){
								echo ' - ' . $round_difference . ' Jour';
							} else {
								echo ' - ' . $round_difference . ' Jours';
							}
							echo '<br>';
							
							$i++;
					
					}
				
				}
				wp_reset_postdata();
				
				echo '<h4>Avis</h4>';
					
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
					<div class="avis-message-employeur-wrapper" style="padding-bottom: 15px;">
							
						<div style="padding-bottom: 15px;">
							
							<label>Avis</label>
							<textarea id="avis-message-employeur" name="avis-message-employeur" class="avis-message-employeur" rows="5" cols="30" required></textarea>
							<div class="number-of-char"></div>
							
							<label>Horaire</label>
							<input type="number" id="horaire-employeur" name="horaire-employeur" class="horaire-employeur" min="0" max="5" step=".01" required>
							
							<label>Supérieur</label>
							<input type="number" id="superieur-employeur" name="superieur-employeur" class="superieur-employeur" min="0" max="5" step=".01" required>
							
							<label>Paie</label>
							<input type="number" id="paie-employeur" name="paie-employeur" class="paie-employeur" min="0" max="5" step=".01" required>
							
						</div>
							
						<button class="avis-employeur-send">
							<input type="hidden" name="action" value="avis_employeur_send" />
							<input type="hidden" name="userid" value="<?php echo $userid; ?>" />
							<?php esc_html_e( 'Soumettre', 'monemploi' ); ?>
						</button>
						
					</div>
				</form>
				<?php } ?>
				
				<div class="avis-employeur-wrapper">
					<?php 
					
					$i = 0;
																
					$horaire_moyenne = 0;
					$superieur_moyenne = 0;
					$paie_moyenne = 0;
					
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
												<div class="delete-avis" style="width: 75px; margin-left: auto; margin-right: auto;">								
													<form action="<?php $_SERVER['REQUEST_URI'] ?>" method="post">
											                        <input type="hidden" name="avisid" value="<?php echo $avis->ID; ?>" />
											                        <input type="hidden" name="action" value="delete_avis_employeur" />
											                        <button type="submit" name="submit" style="width: 25px; padding: 0; margin: 0;">
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
										echo 'Horaire:';			
										echo get_user_meta( $avis->ID, 'horaire_key', true);
										echo ' - ';
										echo 'Superieur:';
										echo get_user_meta( $avis->ID, 'superieur_key', true);
										echo ' - ';
										echo 'Paie:';
										echo get_user_meta( $avis->ID, 'paie_key', true);
									echo '</div>';
									
								echo '</div>';
								
								$horaire_moyenne += floatval(get_user_meta( $avis->ID, 'horaire_key', true));
								$superieur_moyenne += floatval(get_user_meta( $avis->ID, 'superieur_key', true));
								$paie_moyenne += floatval(get_user_meta( $avis->ID, 'paie_key', true));
								
								$i++;
							
							}
							
						}
					
						wp_reset_query(); 
						wp_reset_postdata();	
					}
					
					echo '</div>';
					
					echo '<span>Moyenne</span>';
					echo '<div class="moyenne-score-wrapper" style="padding-bottom: 25px;">';	
						echo 'Horaire:';						
						echo round($horaire_moyenne/$i, 2);
						echo ' - ';
						echo 'Superieur:';
						echo round($superieur_moyenne/$i, 2);
						echo ' - ';
						echo 'Paie:';
						echo round($paie_moyenne/$i, 2);
					echo '</div>';
			 
			 }
			
		} else  {
	    	    $args = array(
		        'role__in' => array( 'employeur' ),
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
					    $company_key = get_user_meta($user->ID, 'company_key', true);
					    echo '<a href="'. get_site_url() .'/employeur/?user='. $user->user_nicename .'">'. $user->user_nicename .'</a>';
					    echo ' - ';
				 	    echo $user->user_firstname;
				 	    echo ' ';
					    echo $user->user_lastname;
					    if($company_key != ''){
						    echo ' - ';
						    echo $company_key;
					    }
					    echo ' - ';
					    echo get_user_meta($user->ID, 'city_key', true);
					
					 ?></div><?php 
				 
				 }
		    		
		    	  }
    		
    		  ?></div><?php 
	}
}
add_shortcode('employeur-dashboard', 'employeur_dashboard');

?>