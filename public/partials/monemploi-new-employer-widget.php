<?php

function monemploi_new_employers_widget() {
	register_widget( 'monemploi_new_employers_widgets');
}
add_action( 'widgets_init', 'monemploi_new_employers_widget' );

class monemploi_new_employers_widgets extends WP_Widget {
	public function __construct() {
		// Instantiate the parent object
		parent::__construct(

			// Base ID of your widget
			'monemploi_new_employers_widgets', 

			// Widget name will appear in UI
			__('monemploi_new_employers_widgets', 'monemploi'), 

			// Widget description
			array( 'description' => __( 'Sample widget based on WPBeginner Tutorial', 'sidr_widget_domain' ), ) 
		);

	}

	function widget( $args, $instance ) {

		$title = apply_filters( 'widget_title', $instance['title'] );
		
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];
				
		    $args = array(
		        'role__in' => array( 'employeur' ),
		        'orderby' => 'date',
		        'order'   => 'DESC'
		    );
		
		    $users = get_users( $args );
		    
		   
		   foreach ($users as $user) {
		   
		   echo '<div>'; 
	
			    $user_id = $user->ID; // Replace with the desired user ID
			    echo '<a href="'. get_site_url() .'/employeur/?user='. $user->user_nicename .'">' . $user->user_nicename . '</a>';
			    echo ' - ';
		 	    echo $user->user_firstname;
		 	    echo ' ';
			    echo $user->user_lastname;
			    echo ' - ';
			    echo get_user_meta($user_id, 'company_key', true);
			    echo ' - ';
			    echo get_user_meta($user_id, 'city_key', true);
			
		   echo '</div>'; 
	    		
	    }

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