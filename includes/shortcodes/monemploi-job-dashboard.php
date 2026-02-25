<?php

function monemploi_job_dashboard() {

	$i = 0;

        $get_jobs_args = array(
            'post_type' => 'emploi',
            'post_status'    => array('publish', 'draft'),
            'posts_per_page' => -1        
        );
        
        $get_jobs = get_posts($get_jobs_args);

	if( ! empty( $get_jobs ) ){
		?><div><?php
		foreach ( $get_jobs as $p ){
		    	if ( get_post_status ( $p->ID ) == 'draft' ) {
		    		if(get_current_user_id() == $p->post_author) {
		    		
		    		echo '<div style="display: block;"><a href="' . get_permalink( $p->ID ) .'">' . $p->post_title . '</a> - ';
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
						echo ' - <span class="distance_' . $i . '"></span> - ';
					echo '</span>';
				}
				
				echo get_post_meta( $p->ID, 'my_city_key', true );
				
				echo ' - Brouillon';
			 ?></div><?php
			 $i++;		    		
		    		
		    		}
		    	} else {
				echo '<div style="display: block;"><a href="' . get_permalink( $p->ID ) .'">' . $p->post_title . '</a> - ';
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
							echo ' - <span class="distance_' . $i . '"></span> - ';
						echo '</span>';
					}
					
					echo get_post_meta( $p->ID, 'my_city_key', true );
	
				 ?></div><?php
				 $i++;
			}
		}
		?></div><?php
	}

}
add_shortcode('monemploi-job-dashboard', 'monemploi_job_dashboard');