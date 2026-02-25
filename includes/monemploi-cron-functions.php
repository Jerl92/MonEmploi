<?php

	function myprefix_custom_cron_schedule( $schedules ) {
	    $schedules['every_one_hour'] = array(
	        'interval' => 7200,
	        'display'  => __( 'Every one hour' ),
	    );
	    return $schedules;
	}
	add_filter( 'cron_schedules', 'myprefix_custom_cron_schedule' );
	
	add_action( 'init', function () {
	
	    ///Hook into that action that'll fire every six hours
	    add_action( 'myfunc_cron_hook', 'myprefix_cron_function' );
	
	    //Schedule an action if it's not already scheduled
	    if ( ! wp_next_scheduled( 'myfunc_cron_hook' ) ) {
	        wp_schedule_event( time(), 'every_one_hour', 'myfunc_cron_hook' );
	    }
	});
	
	//create your function, that runs on cron
	function myprefix_cron_function() {
	
		$get_jobs_args = array(
	            'post_type' => 'emploi',
	            'post_status'    => array('publish'),
	            'posts_per_page' => -1        
	        );
	        
	        $get_jobs = get_posts($get_jobs_args);
	
		if( ! empty( $get_jobs ) ){
		
			foreach ( $get_jobs as $post ){
			
				$end_job_scheduled = get_post_meta( $post->id, 'my_end_job_scheduled_key', true);
				$strtotime_now = strtotime(date("Y-m-d H:i:s"));
				
				if($end_job_scheduled != null) {
					if($strtotime_now >= $end_job_scheduled){
					
						if ( get_post_status( $post->id ) == 'publish' ) {
							// Prepare the post data array for updating
							$postdata = array(
							    'ID'          => $post->id,
							    'post_status' => 'draft' // Set the status to 'draft'
							);
							
							// Update the post in the database
							wp_update_post( $postdata );
						}
					
					}
					
				}
			
			}
		
		}
		
	}
	

?>
