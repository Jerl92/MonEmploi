<?php

function monemploi_new_jobs_widgets() {
	register_widget( 'monemploi_new_jobs_widget');
}
add_action( 'widgets_init', 'monemploi_new_jobs_widgets' );

class monemploi_new_jobs_widget extends WP_Widget {
	public function __construct() {
		// Instantiate the parent object
		parent::__construct(

			// Base ID of your widget
			'monemploi_new_jobs_widget', 

			// Widget name will appear in UI
			__('monemploi_new_jobs_widget', 'McPlayer'), 

			// Widget description
			array( 'description' => __( 'Sample widget based on WPBeginner Tutorial', 'sidr_widget_domain' ), )
		);

	}

	function widget( $args, $instance ) {
	
		echo '<div>';

		$title = apply_filters( 'widget_title', $instance['title'] );
		
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];
		
		$i = 0;
		
		$current_user = wp_get_current_user();
		$user_meta = get_userdata($current_user->ID);
		$user_role = $user_meta->roles[0];
	
		if($user_role == 'employeur' || $user_role == 'administrator') {

		        $get_jobs_args = array(
		            'post_type' => 'emploi',
		            'post_status'    => array('publish', 'draft', 'future'),
		            'posts_per_page' => 10        
		        );
		        
		} else {
		
			$get_jobs_args = array(
		            'post_type' => 'emploi',
		            'post_status'    => array('publish'),
		            'posts_per_page' => 10        
		        );
		
		}
	        
	        $get_jobs = get_posts($get_jobs_args);
	
		if( ! empty( $get_jobs ) ){
			foreach ( $get_jobs as $p ){
			$get_user_by_username = wp_get_current_user();
			$userid = $get_user_by_username->ID;
			$user_meta = get_userdata($userid);
			$user_role = $user_meta->roles[0];
			if(get_post_status($p->ID) == 'draft' || get_post_status($p->ID) == 'future') {	
					if(get_current_user_id() == $p->post_author) {
						if($user_role == 'employeur' || $user_role == 'administrator'){
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
									echo '<span class="completeDeparture">';
										echo '<div class="completeDeparture_'.  $i . '" style="display:none;">'. implode($field_data) . '</div>';
										echo '<div class="completeArrival_' . $i . '" style="display: none;">' . get_post_meta( $p->ID, 'my_code_postal_key', true ) . '</div>';
										echo ' - <span class="widgetdistance_' . $i . '"></span>';
									echo '</span>';
								}
								
								echo ' - ';
								echo get_post_meta( $p->ID, 'my_city_key', true );
			
								if(get_post_status($p->ID) == 'draft') {
									echo ' - Brouillon';
									echo '</br>';
								} elseif (get_post_status($p->ID) == 'future') {
									echo ' - Programmer';
									echo '</br>';
								}  else {
									echo '</br>';
								}
				
							 echo '</div>';
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
							echo '<span class="completeDeparture">';
								echo '<div class="completeDeparture_'.  $i . '" style="display:none;">'. implode($field_data) . '</div>';
								echo '<div class="completeArrival_' . $i . '" style="display: none;">' . get_post_meta( $p->ID, 'my_code_postal_key', true ) . '</div>';
								echo ' - <span class="widgetdistance_' . $i . '"></span>';
							echo '</span>';
						}
						
						echo ' - ';
						echo get_post_meta( $p->ID, 'my_city_key', true );
						
						echo '</br>';
		
					 echo '</div>';
					 $i++;
				
				}
			}
		}
		echo '</div>';

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
?>