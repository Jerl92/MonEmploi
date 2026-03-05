<?php

function monemploi_city() {
	
	if ($_GET['city']) {
		$get_args_emploi = array( 
			'post_type' => 'emploi',
			'posts_per_page' => -1,
			'post_status' => array('publish', 'draft', 'future'),
			'meta_key'   => 'my_city_key',
			'meta_value' => $_GET['city'],
			'orderby' => 'date',
			'order' => 'DESC'
		); 
		
		$get_emplois = get_posts( $get_args_emploi );
		
		$i = 0;
		foreach ($get_emplois as $post) {
			$city = get_post_meta( $post->ID, 'my_city_key', true );
			$get_city_array[$city][$i] = array('ID' => $post->ID, 'author' => $post->post_author, 'city' => $city);	
			$i++;	
		}
		
		ksort($get_city_array);
		
		$i = 0;
		foreach ($get_city_array as $key => $values) {
			echo $key . ' - ' . count($values);
			echo '</br>';
			foreach ($values as $value) {	
				
				$get_user_by_username = wp_get_current_user();
				$userid = $get_user_by_username->ID;
				$user_meta = get_userdata($userid);
				$user_role = $user_meta->roles[0];
				if(get_post_status($value[ID]) == 'draft' || get_post_status($value[ID]) == 'future') {	
					if(get_current_user_id() == $value[author]) {
						if($user_role == 'um_employeur'){
					
								echo '<a href="' . get_permalink($value[ID]) . '">' . $value[ID] . ' - ' . get_the_title($value[ID]) . '</a> - ';
								$author_id = $value[author];
								echo the_author_meta( 'user_nicename' , $author_id );
								$usermetadata = get_user_meta(get_current_user_id());
								
								if ( function_exists( 'um_user_profile_url' ) ) {
									um_fetch_user( $author_id );
									$profile_url = um_user_profile_url();
									echo ' - ';
									echo um_user('name_org');
									echo ' - ';
									echo um_user('first_name');
									echo ' ';
									echo um_user('last_name');
									um_reset_user();
								}
												
								$field_data = $usermetadata['Code_postal'];				
								if($field_data){
									echo '<span class="autocompleteDeparture">';
									echo '<span class="autocompleteDeparture_'.  $i . '" style="display:none;">'. implode($field_data) . '</span>';
									echo '<span class="autocompleteArrival_' . $i . '" style="display: none;">' . get_post_meta( $value[ID], 'my_code_postal_key', true ) . '</span>';
									echo ' - <span class="distance_' . $i . '"></span>';
									echo '</span>';
								}
								
								if(get_post_status($value[ID]) == 'draft') {
									echo ' - Brouillon';
									echo '</br>';
								} 
								if(get_post_status($value[ID]) == 'future') {
									echo ' - Programmer';
									echo '</br>';
								}	
								$i++;							
									
							}
						}
					} else {
						echo '<a href="' . get_permalink($value[ID]) . '">' . $value[ID] . ' - ' . get_the_title($value[ID]) . '</a> - ';
						$author_id = $value[author];
						echo the_author_meta( 'user_nicename' , $author_id );
						$usermetadata = get_user_meta(get_current_user_id());
						
						if ( function_exists( 'um_user_profile_url' ) ) {
							um_fetch_user( $author_id );
							$profile_url = um_user_profile_url();
							echo ' - ';
							echo um_user('name_org');
							echo ' - ';
							echo um_user('first_name');
							echo ' ';
							echo um_user('last_name');
							um_reset_user();
						}
										
						$field_data = $usermetadata['Code_postal'];				
						if($field_data){
							echo '<span class="autocompleteDeparture">';
							echo '<span class="autocompleteDeparture_'.  $i . '" style="display:none;">'. implode($field_data) . '</span>';
							echo '<span class="autocompleteArrival_' . $i . '" style="display: none;">' . get_post_meta( $value[ID], 'my_code_postal_key', true ) . '</span>';
							echo ' - <span class="distance_' . $i . '"></span>';
							echo '</span>';
						}
						echo '</br>';	
						$i++;				
					}	
					
				}							
			}

	} else  {
	
		$get_args_emploi = array( 
			'post_type' => 'emploi',
			'posts_per_page' => -1,
			'post_status' => array('publish', 'draft', 'future'),
			'orderby' => 'date',
			'order' => 'DESC'
		); 
		
		$get_emplois = get_posts( $get_args_emploi );
		
		$i = 0;
		foreach ($get_emplois as $post) {
			$city = get_post_meta( $post->ID, 'my_city_key', true );
			$get_city_array[$city][$i] = array('ID' => $post->ID, 'city' => $city);	
			$i++;	
		}
		
		ksort($get_city_array);
		
		foreach ($get_city_array as $key => $values) {
			echo '<a href="'. get_site_url() . '' . $_SERVER['REQUEST_URI'] . '?city=' . $key . '">' . $key . '</a>';
			echo ' - ' . count($values);
			echo '</br>';
		}
		
	}
		
}
add_shortcode('monemploi-city', 'monemploi_city');

?>