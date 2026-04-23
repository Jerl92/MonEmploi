<?php

function monemploi_new_school_widget() {
	register_widget( 'monemploi_new_school_widgets');
}
add_action( 'widgets_init', 'monemploi_new_school_widget' );

class monemploi_new_school_widgets extends WP_Widget {
	public function __construct() {
		// Instantiate the parent object
		parent::__construct(

			// Base ID of your widget
			'monemploi_new_school_widgets', 

			// Widget name will appear in UI
			__('monemploi_new_school_widgets', 'monemploi'), 

			// Widget description
			array( 'description' => __( 'Sample widget based on WPBeginner Tutorial', 'sidr_widget_domain' ), ) 
		);

	}

	function widget( $args, $instance ) {

    $current_user = wp_get_current_user();
	$user_meta = get_userdata($current_user->ID);
	$user_role = $user_meta->roles[0];
	if($user_role == 'employeur' || $user_role == 'administrator'){

		?><div><?php 

		$title = apply_filters( 'widget_title', $instance['title'] );
		
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];
		
		    $args = array(
		        'role__in' => array( 'employer' ),
		        'orderby' => 'date',
		        'order'   => 'DESC'
		    );
		
		    $users = get_users( $args );
		   
		   foreach ($users as $user) {
		   
		     ?><div><?php 
		                   
                $unique_strings_school = get_user_meta( $user->ID, 'school_unique', true );
	    	    
	    	    $unique_strings_school_first = $unique_strings_school[array_key_first($unique_strings_school)];
	    	    
	    	    $date_school_end_strtotime = strtotime(get_user_meta( $user->ID, 'dateschoolend_'.$unique_strings_school_first, true));
	    	    
	    	    $school_title = get_user_meta( $user->ID, 'school_title_'.$unique_strings_school_first, true);
	    	    
	    	    $school_name = get_user_meta( $user->ID, 'school_name_'.$unique_strings_school_first, true);
	    	    
	    	    if($date_school_end_strtotime != '' && $school_title != '' && $school_name != ''){
	    	    
	    	        $school_array[$date_school_end_strtotime] = array($user->ID, $school_title, $school_name);
	    	    
	    	    }
	    	
	    	  ?></div><?php 
		   }
		   
		   $i = 0;
		   krsort($school_array);
		   
		   foreach ($school_array as $school_user) {
		       if($i <= 24) {
    		       $get_user_by_id = get_user_by('ID', $school_user[0]);
    		       echo '<a href="'. get_site_url() .'/employee/?user='. $get_user_by_id->user_nicename .'">' . $get_user_by_id->user_nicename. '</a>';
    		       echo ' - ';
    		       echo $get_user_by_id->user_firstname;
        		   echo ' ';
        		   echo $get_user_by_id->user_lastname;
    		       echo ' - ';
    		       echo $school_user[1];
    		       echo ' - ';
    		       echo $school_user[2];
    		       echo '</br>';
		       }
		       $i++;
		   }
	}
    		
    		?></div><?php 


	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		// Fields
		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
		return $instance;
	}

	// Widget form creation
	function form($instance) {
		$title = '';
	 	$link = '';
		$songinfo = '';

		// Check values
		if( $instance) {
			$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		} ?>
		 
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		
	<?php }
}