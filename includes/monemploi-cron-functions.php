<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	function myprefix_custom_cron_schedule( $schedules ) {
	    $schedules['every_one_minute'] = array(
	        'interval' => 60,
	        'display'  => __( 'Every minute' ),
	    );
	    return $schedules;
	}
	add_filter( 'cron_schedules', 'myprefix_custom_cron_schedule' );
	
	function fifteen_custom_cron_schedule( $schedules ) {
	    $schedules['every_fifteen_minute'] = array(
	        'interval' => 900,
	        'display'  => __( 'Every fifteen minute' ),
	    );
	    return $schedules;
	}
	add_filter( 'cron_schedules', 'fifteen_custom_cron_schedule' );
	
	add_action( 'init', function () {
	
	    ///Hook into that action that'll fire every six hours
	    add_action( 'myfunc_cron_hook', 'myprefix_cron_function' );
	
	    //Schedule an action if it's not already scheduled
	    if ( ! wp_next_scheduled( 'myfunc_cron_hook' ) ) {
	        wp_schedule_event( time(), 'every_one_minute', 'myfunc_cron_hook' );
	    }
	});
	
	//create your function, that runs on cron
	function myprefix_cron_function() {
	
		$get_jobs_args = array(
	            'post_type' => 'emploi',
	            'post_status'    => 'publish',
	            'posts_per_page' => -1        
	        );
	        
	        $get_jobs = get_posts($get_jobs_args);
	
		if( ! empty( $get_jobs ) ){
		
			foreach ( $get_jobs as $post ){
						
				$end_job_scheduled = get_post_meta( $post->ID, 'my_end_job_scheduled_key', true);
				$strtotime_now = current_time('timestamp');
				
				if($end_job_scheduled != null || $end_job_scheduled != '') {
	
					if($strtotime_now >= $end_job_scheduled){
									
						if ( get_post_status( $post->ID ) == 'publish' ) {
							// Prepare the post data array for updating
							$postdata = array(
							    'ID'          => $post->ID,
							    'post_status' => 'draft' // Set the status to 'draft'
							);
							
							// Update the post in the database
							wp_update_post( $postdata );
							
							$author = get_userdata( $post->post_author );
							$author_email = $author->user_email;
							$post_title = get_the_title( $post->ID );
							$post_url = get_permalink( $post->ID );
							
							$subject = 'Your scheduled post "' . $post_title . '" has been draft!';
							$message = '
							<p>Hello ' . $author->display_name . ',</p>
							<p>Your post "' . $post_title . '" has been draft on the website.</p>
							<p>View it here: <a href="' . $post_url . '">' . $post_url . '</a></p>
							';
							$headers = array('Content-Type: text/html; charset=UTF-8');
							
							// Send the email
							wp_mail( $author_email, $subject, $message, $headers );
														
						}
					
					}
					
				}
			
			}
		
		}
		
	}
	
	add_action( 'init', function () {
	
	    ///Hook into that action that'll fire every six hours
	    add_action( 'future_to_publish_cron_hook', 'future_to_publish_cron_function' );
	
	    //Schedule an action if it's not already scheduled
	    if ( ! wp_next_scheduled( 'future_to_publish_cron_hook' ) ) {
	        wp_schedule_event( time(), 'every_one_minute', 'future_to_publish_cron_hook' );
	    }
	});
	
	//create your function, that runs on cron
	function future_to_publish_cron_function() {
	
		$get_jobs_args = array(
	            'post_type' => 'emploi',
	            'post_status'    => 'future',
	            'posts_per_page' => -1        
	        );
	        
	        $get_jobs = get_posts($get_jobs_args);
	
		if( ! empty( $get_jobs ) ){
		
			foreach ( $get_jobs as $post ){
						
				$start_job_scheduled = strtotime(get_the_date('Y-m-d H:i:s', $post->ID));
				$strtotime_now = current_time('timestamp');
							
				if($start_job_scheduled != null || $start_job_scheduled != '') {
	
					if($strtotime_now >= $start_job_scheduled){
					
						if ( get_post_status( $post->ID ) == 'future' ) {
							// Prepare the post data array for updating
							$postdata = array(
							    'ID'          => $post->ID,
							    'post_status' => 'publish' // Set the status to 'draft'
							);
							
							// Update the post in the database
							wp_update_post( $postdata );													
						}
					
					}
					
				}
			
			}
		
		}
		
	}
	
		add_action( 'init', function () {
	
	    ///Hook into that action that'll fire every six hours
	    add_action( 'online_status_cron_hook', 'online_status_cron_function' );
	
	    //Schedule an action if it's not already scheduled
	    if ( ! wp_next_scheduled( 'online_status_cron_hook' ) ) {
	        wp_schedule_event( time(), 'every_fifteen_minute', 'online_status_cron_hook' );
	    }
	});
	
	//create your function, that runs on cron
	function online_status_cron_function() {
	
		    $args = array(
		        'orderby' => 'date',
		        'order'   => 'DESC'
		    );
		
		    $users = get_users( $args );
	
	        foreach ($users as $user) {
		        $current_time = current_time('timestamp');
	                $offline_time = get_user_meta($user->ID, 'offline_time_', true);
	                if($offline_time == 0) {
	                	update_user_meta($user->ID, 'online_status_', false);
	                	update_user_meta($user->ID, 'offline_time_', $current_time);
	                }
	        }
		
	}
	
	
        add_action( 'init', function () {
	
	    ///Hook into that action that'll fire every six hours
	    add_action( 'punch_in_cron_hook', 'punch_in_cron_function' );
	
	    //Schedule an action if it's not already scheduled
	    if ( ! wp_next_scheduled( 'punch_in_cron_hook' ) ) {
	        wp_schedule_event( time(), 'every_fifteen_minute', 'punch_in_cron_hook' );
	    }
	});
	
	//create your function, that runs on cron
	function punch_in_cron_function() {
	
            $args = array(
		        'orderby' => 'date',
		        'order'   => 'DESC'
		    );
		
		    $users = get_users( $args );
	
	        foreach ($users as $user) {
	            
	            $current_time = current_time( 'timestamp' );
	
                $all_horaires = get_posts(array(
        		    'post_type' => 'horaire',
        		    'numberposts' => -1,
        		    'author'	=> $user->ID,
        		    'post_status' => 'any',
        		));
        		
        		foreach ($all_horaires as $post) {
                    $push_ = get_post_meta( $postid, 'push_key', true );
                    $punchcount = count($push_);
                    $lastpunch = $push_[$punchcount-1];
                    $lastpunchwith12hplus = $lastpunch[1] + 43200;
                    if ($current_time >= $lastpunchwith12hplus && $lastpunch[0] == 'entrer' ) {
            				update_post_meta( $post->ID, 'push_in_out_key', 0 );
            				$push_[$lastpunch] = array('sortie', $current_time);
            				update_post_meta( $post->ID, 'push_key', $push_ );
                    }
        		}
        		
	        }
        		    
	}
	
?>