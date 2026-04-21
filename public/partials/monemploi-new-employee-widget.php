<?php

function monemploi_new_employees_widget() {
	register_widget( 'monemploi_new_employees_widgets');
}
add_action( 'widgets_init', 'monemploi_new_employees_widget' );

class monemploi_new_employees_widgets extends WP_Widget {
	public function __construct() {
		// Instantiate the parent object
		parent::__construct(

			// Base ID of your widget
			'monemploi_new_employees_widgets', 

			// Widget name will appear in UI
			__('monemploi_new_employees_widgets', 'monemploi'), 

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
		                   
			    $user_id = $user->ID;
			    echo '<a href="'. get_site_url() .'/employee/?user='. $user->user_nicename .'">' . $user->user_nicename. '</a>';
			    echo ' - ';
		 	    echo $user->user_firstname;
		 	    echo ' ';
			    echo $user->user_lastname;

	    	}
	    	
	    	  ?></div><?php 
	    
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