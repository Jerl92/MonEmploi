<?php

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
	
	$i = 0;
	foreach ($get_emplois as $post) {
		$city = get_post_meta( $post->ID, 'my_city_key', true );
		$get_city_array[$city][$i] = array('ID' => $post->ID, 'author' => $post->post_author, 'city' => $city);	
		$i++;	
	}
	
	ksort($get_city_array);
	
	$i = 0;
		
	?><form action="" method="GET">
	    <select name="filter_city" id="filter_city">
	        <option value="">Tout les villes</option>
	        <?php foreach ($get_city_array as $key => $values) { ?>
	        	<option value="<?php echo $key ?>"><?php echo $key ?></option>
	        <?php }  ?>
	    </select>
	    <input type="submit" value="Filter">
	</form><?php

	$i = 0;
	
	$get_user_by_username = wp_get_current_user();
	$userid = $get_user_by_username->ID;
	$user_meta = get_userdata($userid);
	$user_role = $user_meta->roles[0];

	if($user_role == 'employeur'){
	       if ( isset( $_GET['filter_city'] ) && ! empty( $_GET['filter_city'] ) ) {
    	        $get_jobs_args = array(
    	            'post_type' => 'emploi',
    	            'post_status'    => array('publish', 'draft', 'future'),
    	            'posts_per_page' => -1,
    	            'orderby'	     => 'date',
    	            'order'	=> 'DESC',
                    'meta_key'     => 'my_city_key',
                    'meta_value'   => sanitize_text_field( $_GET['filter_city'] )
    	        );
	       } else {
	           
	           $get_jobs_args = array(
    	            'post_type' => 'emploi',
    	            'post_status'    => array('publish', 'draft', 'future'),
    	            'posts_per_page' => -1,
    	            'orderby'	     => 'date',
    	            'order'	=> 'DESC'
    	        );
	           
	       }
        } else {
	       if ( isset( $_GET['filter_city'] ) && ! empty( $_GET['filter_city'] ) ) {
    	        $get_jobs_args = array(
    	            'post_type' => 'emploi',
    	            'post_status'    => array('publish'),
    	            'posts_per_page' => -1,
    	            'orderby'	     => 'date',
    	            'order'	=> 'DESC',
                    'meta_key'     => 'my_city_key',
                    'meta_value'   => sanitize_text_field( $_GET['filter_city'] )
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
        }
        
        $get_jobs = get_posts($get_jobs_args);

	if( ! empty( $get_jobs ) ){
		?><div><?php
			foreach ( $get_jobs as $p ){
			if(get_post_status($p->ID) == 'draft' || get_post_status($p->ID) == 'future') {	
					if(get_current_user_id() == $p->post_author) {
						if($user_role == 'employeur'){
			
					    		echo '<div style="display: block;">';
					    			echo '<a href="' . get_permalink( $p->ID ) .'">' . $p->ID . ' - ' . $p->post_title . '</a> - ';
								$author_id = $p->post_author;
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
									echo '<span class="autocompleteArrival_' . $i . '" style="display: none;">' . get_post_meta( $p->ID, 'my_code_postal_key', true ) . '</span>';
									echo ' - <span class="distance_' . $i . '"></span>';
								echo '</span>';
							}
							
							if(get_post_status($p->ID) == 'draft') {
								echo ' - Brouillon';
							} 
							if(get_post_status($p->ID) == 'future') {
								echo ' - Programmer';
							}
							
							echo ' - ' . get_post_meta( $p->ID, 'my_city_key', true );							
							$from = strtotime(get_the_date('Y-m-d H:i:s', $p->ID));
							$today = current_time('timestamp');
							$difference = $today - $from;
							$round_difference = round($difference / 60 / 60 / 24, 0);
							if($round_difference < 1){
								echo ' - ' . $round_difference . ' Jour';
							} else {
								echo ' - ' . $round_difference . ' Jours';
							}
							?></div><?php
							$i++;	
					
						}
					
					}
				
				} else {
					
			    		echo '<div style="display: block;"><a href="' . get_permalink( $p->ID ) .'">' . $p->ID . ' - ' . $p->post_title . '</a> - ';
						$author_id = $p->post_author;
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
							echo '<span class="autocompleteArrival_' . $i . '" style="display: none;">' . get_post_meta( $p->ID, 'my_code_postal_key', true ) . '</span>';
							echo ' - <span class="distance_' . $i . '"></span>';
						echo '</span>';
					}
					
					echo ' - ' . get_post_meta( $p->ID, 'my_city_key', true );
			
					$from = strtotime(get_the_date('Y-m-d H:i:s', $p->ID));
					$today = current_time('timestamp');
					$difference = $today - $from;
					$round_difference = round($difference / 60 / 60 / 24, 0);
					if($round_difference < 1){
						echo ' - ' . $round_difference . ' Jour';
					} else {
						echo ' - ' . $round_difference . ' Jours';
					}

					
					?></div><?php
					$i++;	
			
				}	    		
		    		
		    	}
		    ?></div><?php
		}

}
add_shortcode('monemploi-job-dashboard', 'monemploi_job_dashboard');