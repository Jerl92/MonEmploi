<?php

function employeur_dashboard() {
    
		if ($_GET['user']) {
			$user_meta = $_GET['user'];
			$get_user_by_username = get_user_by('login', $user_meta);
			$userid = $get_user_by_username->ID;
			$user_meta = get_userdata($userid);
			$user_role = $user_meta->roles[0];
			if($user_role == 'um_employeur'){
				
				?><div><?php 
				   
				    $user_id = intval($userid); // Replace with the desired user ID
				    um_fetch_user( $user_id );
				    
				    echo um_user( 'cover_photo' );
				    
				    // Get the URL of the profile picture with a specific size (e.g., 150x150 pixels)
					$image_url = get_avatar_url( $user_id, array( 'size' => 75 ) );
					
					if ( $image_url ) {
					    echo '<img src="' . esc_url( $image_url ) . '" alt="User Profile Image" style="border-radius: 50%; width: 75px;">';
					} else {
					    echo 'Profile image not found.';
					}
									    
				    
				    echo $get_user_by_username->user_nicename;
				    echo ' - ';
				    echo um_user('name_org');
				    echo ' - ';
			 	    echo um_user('first_name');
			 	    echo ' ';
				    echo um_user('last_name');
				    echo ' - ';
			 	    echo um_user('user_email');
				    echo '<br>';
				    echo um_user('Adresse');
				    echo ' - ';
				    echo um_user('Province');
				    echo ' - ';
				    echo um_user('Pays');
				    echo ' - ';
				    echo um_user('Code_postal');
				    echo ' - ';
				    echo um_user('number_phone');
				    if(!um_user('poste') == ''){
					    echo ' - ';
					    echo um_user('poste');
				    }
				    um_reset_user();
				    
				    echo '<br>';
				    
				$args = array(
				 'post_type' => 'emploi',
	 			 'author'        =>  $user_id, 
	 			 'post_status'    => array('publish', 'draft'),
				  'orderby'       =>  'post_date',
				  'order'         =>  'DESC',
				  'posts_per_page' => -1
				);
				
				
				$posts = get_posts( $args );
				
				echo 'Nombre de postes '. count($posts);
				
				echo '<br>';
				
				$i = 0;
				
				foreach($posts as $post)  {
				    if ( get_post_status ( $post->ID ) == 'draft' ) {
			    		        if(get_current_user_id() == $post->post_author) {
					
							echo '<a href="' . get_permalink( $post->ID ) .'">' . $post->post_title . '</a>';
							$usermetadata = get_user_meta(get_current_user_id());
							$field_data = $usermetadata['Code_postal'];
							if($field_data){
								echo '<span class="autocompleteDeparture">';
									echo '<span class="autocompleteDeparture_'.  $i . '" style="display:none;">'. implode($field_data) . '</span>';
									echo '<span class="autocompleteArrival_' . $i . '" style="display: none;">' . get_post_meta( $post->ID, 'my_code_postal_key', true ) . '</span>';
									echo ' - <span class="distance_' . $i . '"></span>';
								echo '</span>';
							}
							echo ' - Brouillon';
							echo '<br>';
							
							$i++;
						
						}
					
					} else {
					
							echo '<a href="' . get_permalink( $post->ID ) .'">' . $post->post_title . '</a>';
							$usermetadata = get_user_meta(get_current_user_id());
							$field_data = $usermetadata['Code_postal'];
							if($field_data){
								echo '<span class="autocompleteDeparture">';
									echo '<span class="autocompleteDeparture_'.  $i . '" style="display:none;">'. implode($field_data) . '</span>';
									echo '<span class="autocompleteArrival_' . $i . '" style="display: none;">' . get_post_meta( $post->ID, 'my_code_postal_key', true ) . '</span>';
									echo ' - <span class="distance_' . $i . '"></span>';
								echo '</span>';
							}
							echo '<br>';
							
							$i++;
					
					}
				
				}
				wp_reset_postdata();
				
				?>
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
				
				<div class="avis-message-employeur-wrapper" style="padding-bottom: 15px;">
					<?php if(is_user_logged_in() && $allready_avis == 0){ ?>
						
						<div style="padding-bottom: 15px;">
						
							<div class="avis_error"></div>
							
							<label>Avis</label>
							<textarea id="avis-message-employeur" name="avis-message-employeur" class="avis-message-employeur" rows="5" cols="30"></textarea>
							<div class="number-of-char"></div>
							
							<label>Horaire</label>
							<input type="number" id="horaire-employeur" name="horaire-employeur" class="horaire-employeur" min="0" max="5" step=".01">
							
							<label>Supérieur</label>
							<input type="number" id="superieur-employeur" name="superieur-employeur" class="superieur-employeur" min="0" max="5" step=".01">
							
							<label>Paie</label>
							<input type="number" id="paie-employeur" name="paie-employeur" class="paie-employeur" min="0" max="5" step=".01">
							
						</div>
							
						<button class="avis-employeur-send" data-object-id="<?php echo $user_id; ?>">
							<?php esc_html_e( 'Soumettre', 'monemploi' ); ?>
						</button>
					
					<?php } ?>
				</div>
				
		                <button class="show-hide-avis">Afficher les avis</button>
		                <br>
				<div class="avis-employeur-wrapper" style="display: none; padding-bottom: 15px;">
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
												<h3 class="ticket-head" id="response-<?php echo esc_attr($i); ?>" style="width: calc(100% - 25px);">
												<?php $userid = $get_user->ID; ?>
												<?php $user_meta = get_userdata($userid); ?>
												<?php $user_role = $user_meta->roles[0]; ?>
												<?php um_fetch_user( $userid ); ?>
												<?php if($user_role == 'um_employeur'){ ?>
													<a href="<?php get_site_url(); ?>/employeur/?user=<?php echo $user_meta->user_login ?>"><?php echo $get_user->display_name; ?></a> - <?php echo um_user('name_org'); ?>
													<?php um_reset_user(); ?>
												<?php } elseif($user_role == 'employer'){ ?>
													<a href="<?php get_site_url(); ?>/employee/?user=<?php echo $user_meta->user_login ?>"><?php echo $get_user->display_name; ?></a> - <?php echo um_user('name_org'); ?>
													<?php um_reset_user(); ?>
												<?php } ?>

												</h3>
												<?php if (intval($avis->post_author) == intval(get_current_user_id())){ ?>
												<div class="delete-avis" style="width: 25px; padding-top: 25px;" data-object-id="<?php echo $avis->ID; ?>" data-object-string="<?php echo $ramdonstring; ?>" data-object-userid="<?php echo $userid; ?>">												<i class="material-icons">
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
										echo 'Horaire:';			
										echo get_user_meta( $avis->ID, 'horaire_key', true);
										for ($x = 1; $x <= get_user_meta( $avis->ID, 'horaire_key', true); $x++) {
											echo '<span class="fa fa-star checked"></span>';
										}
										echo ' - ';
										echo 'Superieur:';
										echo get_user_meta( $avis->ID, 'superieur_key', true);
										for ($x = 1; $x <= get_user_meta( $avis->ID, 'superieur_key', true); $x++) {
											echo '<span class="fa fa-star checked"></span>';
										}
										echo ' - ';
										echo 'Paie:';
										echo get_user_meta( $avis->ID, 'paie_key', true);
										for ($x = 1; $x <= get_user_meta( $avis->ID, 'paie_key', true); $x++) {
											echo '<span class="fa fa-star checked"></span>';
										}
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
						for ($x = 1; $x <= round($horaire_moyenne/$i, 2); $x++) {
							echo '<span class="fa fa-star checked"></span>';
						}
						echo ' - ';
						echo 'Superieur:';
						echo round($superieur_moyenne/$i, 2);
						for ($x = 1; $x <= round($superieur_moyenne/$i, 2); $x++) {
							echo '<span class="fa fa-star checked"></span>';
						}
						echo ' - ';
						echo 'Paie:';
						echo round($paie_moyenne/$i, 2);
						for ($x = 1; $x <= round($paie_moyenne/$i, 2); $x++) {
							echo '<span class="fa fa-star checked"></span>';
						}
					echo '</div>';
			 
			 }
			
		} else  {
	    	    $args = array(
		        'role__in' => array( 'um_employeur' ),
		        'orderby' => 'date',
		        'order'   => 'DESC'
		    );
		
		    $users = get_users( $args );
		    
		   ?><div style="padding-bottom: 25px;"><?php 
		   
		   foreach ($users as $user) {
		   
		   	?><div><?php 
			   
			    $user_id = $user->ID; // Replace with the desired user ID
			    um_fetch_user( $user_id );
			    echo '<a href="'. get_site_url() .'/employeur/?user='. $user->user_nicename .'">Profile</a>';
			    echo ' - ';
			    echo um_user('name_org');
			    echo ' - ';
		 	    echo um_user('first_name');
		 	    echo ' ';
			    echo um_user('last_name');
			    um_reset_user();
			
			 ?></div><?php 
	    		
	    	  }
    		
    		  ?></div><?php 
		}
}
add_shortcode('employeur-dashboard', 'employeur_dashboard');

?>