<?php

function monemploi_profile_widget() {
	register_widget( 'monemploi_profile_widgets');
}
add_action( 'widgets_init', 'monemploi_profile_widget' );

class monemploi_profile_widgets extends WP_Widget {
	public function __construct() {
		// Instantiate the parent object
		parent::__construct(

			// Base ID of your widget
			'monemploi_profile_widgets', 

			// Widget name will appear in UI
			__('monemploi_profile_widgets', 'monemploi'), 

			// Widget description
			array( 'description' => __( 'Sample widget based on WPBeginner Tutorial', 'sidr_widget_domain' ), ) 
		);

	}

	function widget( $args, $instance ) {

		if(is_user_logged_in()){

		?><div><?php 

			$title = apply_filters( 'widget_title', $instance['title'] );
			
			echo $args['before_widget'];
			if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
	   
		        ?><div><?php 
		            $user_id = get_current_user_id();
    			    $user = get_user_by('ID', $user_id);
		            $user_info = get_userdata($user_id);
			    $user_roles = $user_info->roles;
			    
				echo '<div class="container-image-cover-widget">';
					$cover_photo = get_user_meta($user_id, 'cover_photo', true);
					$cover_url = wp_get_attachment_url($cover_photo);
					if($cover_url){
						echo '<img src="'. $cover_url .'" class="image-fond-widget">';
					} else {
						//
					}
					
					// Get the URL of the profile picture with a specific size (e.g., 150x150 pixels)
					$user_avatar = get_user_meta($user_id, 'user_avatar', true);
					$image_url = wp_get_attachment_url($user_avatar);
					
					if ( $image_url ) {
					   	echo '<img src="' . esc_url( $image_url ) . '" class="image-dessus-widget">';
					} else {
						//
					}
					
				echo '</div>';
				    
				   echo '<div class="user-info-widget">';
		    			   if(implode($user_roles) == 'employer'){
		    			    	echo '<a href="'. get_site_url() .'/employee/?user='. $user->user_nicename .'">' . $user->user_nicename. '</a>';
		    			    }
		    			   if(implode($user_roles) == 'employeur'){
		    			    	echo '<a href="'. get_site_url() .'/employeur/?user='. $user->user_nicename .'">' . $user->user_nicename. '</a>';
		    			    }
		    			    echo ' - ';
		    		 	    echo $user->user_firstname;
		    		 	    echo ' ';
		    			    echo $user->user_lastname;
		    			    $company_key = get_user_meta($user_id, 'company_key', true);
		    			    if($company_key != ''){
		    			        echo ' - ';
		    			        echo $company_key;
		    			    }
		    			    echo ' - ';
		    			    echo get_user_meta($user_id, 'city_key', true);
	    			    echo '</div>';
	    			    
	    			    echo '<div class="candidacy-info-widget">';
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
				
					$get_candidacys = get_posts( $get_args );
					
					echo 'Candidatures';
					echo '<br>';
					
					echo count($get_candidacys) . ' - ';
					
					$candidacy_status_0 = 0;
					$candidacy_status_1 = 0;
					$candidacy_status_2 = 0;
					$candidacy_status_3 = 0;
					
					foreach($get_candidacys as $get_candidacy){
					
						$get_status = get_post_meta($get_candidacy->ID, 'candidacy_status_', true);
						
						if($get_status == '' || $get_status == 0){
							$candidacy_status_0 += 1;
						}
						
						if($get_status == 1){
							$candidacy_status_1 += 1;
						}
						
						if($get_status == 2){
							$candidacy_status_2 += 1;
						}
						
						if($get_status == 3){
							$candidacy_status_3 += 1;
						}
					
					}
					
					echo 'En attente:';
					echo $candidacy_status_0;
					echo ' - ';
					
					echo 'Refuser:';
				    	echo $candidacy_status_1;
				    	echo ' - ';
				    	
				    	echo 'Entrevu accepté:';
				    	echo $candidacy_status_2;
				    	echo ' - ';
				    	
				    	echo 'Embauché:';
				    	echo $candidacy_status_3;
				   echo '</div>';
			    
			    ?></div><?php 
			    
			    
			    	$current_user = wp_get_current_user();
				$user_meta = get_userdata($current_user->ID);
				$user_role = $user_meta->roles[0];
			
				if($user_role == 'employeur'){
					echo '<div class="jobs-info-widget">';
					
					echo 'Emplois';
					echo '<br>';
			
					$get_jobs_args = array( 
						'post_type' => 'emploi',
						'posts_per_page' => -1,
						'post_status' => array('publish', 'draft', 'future'),
						'orderby' => 'date',
						'order' => 'DESC'
					); 
				
					$get_jobs = new WP_Query($get_jobs_args);
					
					echo $get_jobs->found_posts . ' - ';
					
					$emploi_draft = 0;
					$emploi_future = 0;
					$emploi_publish = 0;
					
					if ( $get_jobs->have_posts() ) : while ( $get_jobs->have_posts() ) : $get_jobs->the_post();
					
						if(get_post_status(get_the_ID()) == 'draft'){
							$emploi_draft += 1;
						}
						
						if(get_post_status(get_the_ID()) == 'future'){
							$emploi_future += 1;
						}
						
						if(get_post_status(get_the_ID()) == 'publish'){
							$emploi_publish += 1;
						}
					
					endwhile; endif;
					
					echo 'Publier:';
					echo $emploi_publish;
					echo ' - ';
					echo 'Programmer:';
					echo $emploi_future;
					echo ' - ';
					echo 'Brouillon:';
					echo $emploi_draft;
				
				echo '</div>';
			}
			    
	 	?></div><?php 
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