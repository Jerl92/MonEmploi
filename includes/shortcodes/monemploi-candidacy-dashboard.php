<?php

function monemploi_candidacy_dashboard() {

	$current_user = wp_get_current_user();
	$user_meta = get_userdata($current_user->ID);
	$user_role = $user_meta->roles[0];

	if($user_role == 'employeur'){
	
		$get_args = array( 
			'post_type' => 'candidacy',
			'posts_per_page' => -1,
			'meta_key' => 'my_author_id_key',
			'meta_value' => get_current_user_id(),
			'orderby' => 'date',
			'order' => 'DESC',
		); 
		
	} elseif ($user_role == 'employer'){
	
		$get_args = array( 
			'post_type' => 'candidacy',
			'posts_per_page' => -1,
			'author'         => get_current_user_id(),
			'orderby' => 'date',
			'order' => 'DESC',
		); 
		
	}

	$i = 0;

	$get_candidacys = get_posts( $get_args );
	
	foreach($get_candidacys as $get_candidacy){
	echo '<ul style="padding-bottom: 15px;">';
		echo '<li>';
			echo '<a href="' . get_permalink( $get_candidacy->ID ) . '">' . $get_candidacy->ID . '</a>';
		echo '</li>';
		echo '<li>';
			echo '<a href="' . get_permalink( get_post_meta($get_candidacy->ID, 'my_postid_key', true) ) . '">' . $get_candidacy->post_title . '</a>';
		echo '</li>';
		echo '<li>';
			if($user_role == 'employeur'){
				$author = get_post_field( 'post_author', $get_candidacy->ID );
				$meta_data_rma_author = get_userdata( $author );
				echo $meta_data_rma_author->first_name;
				echo ' ';
				echo $meta_data_rma_author->last_name;
			}
			if ($user_role == 'employer'){
			
				um_fetch_user( get_post_meta($get_candidacy->ID, 'my_author_id_key', true) );
				echo um_user('first_name');
				echo ' ';
				echo um_user('last_name');
				echo ' - ';
				echo um_user('name_org');
				um_reset_user();
			
			}
		echo '</li>';
		echo '<li>';
			echo get_the_date( "l F j, Y", $get_candidacy->ID) . ' - ' . get_the_time( '', $get_candidacy->ID );
		echo '</li>';
	        $published_date= get_the_date('c', $get_candidacy->ID);
	        $modified_date = get_the_modified_date('c', $get_candidacy->ID);
		if(strtotime($published_date)+1 < strtotime($modified_date)) {  
	        	echo '<li>';
				echo get_the_modified_date( "l F j, Y", $get_candidacy->ID) . ' - ' . get_the_modified_time( '', $get_candidacy->ID );
			echo '</li>';
		}
			$get_cv_ = get_post_meta($get_candidacy->ID, 'my_cv_key', true);
			if($get_cv_){
				foreach( $get_cv_ as $get_cv ) {
					echo '<li>';
					echo '<a href="' . wp_get_attachment_url($get_cv) . '">'. basename(wp_get_attachment_url($get_cv)) .'</a>';
					echo '</li>';
				}
			}
		echo '<li>';
        
        		$get_status = get_post_meta($get_candidacy->ID, 'candidacy_status_', true);
                
			if($get_status == null || $get_status == 0){
			echo 'En attente';
			} elseif($get_status == 1 ){
			    echo 'Refuser';
			 } elseif($get_status == 2){
			 echo 'Entrevu accepté';
			 } elseif($get_status == 3){
			 echo 'Embauché';
			}
			
		echo '</li>';
	echo '</ul>';
	$i++;
	}
	
}
add_shortcode('monemploi-candidacy-dashboard', 'monemploi_candidacy_dashboard');