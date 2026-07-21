<?php

function monemploi_new_employeur_avis_widgets() {
	register_widget( 'monemploi_new_employeur_avis_widgets');
}
add_action( 'widgets_init', 'monemploi_new_employeur_avis_widgets' );

class monemploi_new_employeur_avis_widgets extends WP_Widget {
	public function __construct() {
		// Instantiate the parent object
		parent::__construct(

			// Base ID of your widget
			'monemploi_new_employeur_avis_widgets', 

			// Widget name will appear in UI
			__('monemploi_new_employeur_avis_widgets', 'monemploi'), 

			// Widget description
			array( 'description' => __( 'Sample widget based on WPBeginner Tutorial', 'sidr_widget_domain' ), ) 
		);

	}

	function widget( $args, $instance ) {

		?><div><?php 

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
		    
		    $hide_wdget_new_employees_avis_userids = array();
		   
		   foreach ($users as $user) {
		   
		            $hide_widget_new_employeur_avis = get_user_meta( $user->ID, 'hide_widget_new_employeur_avis_key', true);
		            if($hide_widget_new_employeur_avis == 0 || $hide_widget_new_employeur_avis == '') {
		   		
		   		$hide_wdget_new_employeur_avis_userids[$user->ID] = 0;
			    
			    } else {
		   		
		   		$hide_wdget_new_employeur_avis_userids[$user->ID] = 1;
			    
			    }

	    	}
	    	
	    		echo '<div>'; 

                    	$args = array( 
							'post_type' => 'avis',
							'posts_per_page' => 15,
							'orderby' => 'date',
							'order' => 'DESC'
						); 
					
						$get_avis = get_posts( $args );
						
						if($get_avis){
					
							foreach($get_avis as $avis){
							    
							    $avis_role = get_user_meta( $avis->ID, 'role_key', true);
							    
							    if($avis_role == 'employeur'){ 
							    
							    	foreach($hide_wdget_new_employeur_avis_userids as $key => $value){ 
							    	
							    		$authorid = get_user_meta( $avis->ID, 'authorid_key', true);
							    	
							    		if($key == $authorid && $value == 0){							    	
										
										$userid = get_user_meta( $avis->ID, 'userid_key', true);
										$get_user = get_user_by('id', $authorid);
										$get_userid = get_user_by('id', $userid);
										?>
										<?php $user_meta = get_userdata($get_user->ID); ?>
										<?php $user_role = $user_meta->roles[0]; ?>
					    					<?php $nicename = get_user_meta( $avis->ID, 'nicename_key', true); ?>
										<?php if($user_role == 'employeur'){ ?>
											<a href="<?php get_site_url(); ?>/employeur/?user=<?php echo $get_user->user_login ?>"><?php echo $get_user->user_firstname .' '. $get_user->user_lastname;; ?></a>
										<?php } elseif($user_role == 'employer'){ ?>
											<a href="<?php get_site_url(); ?>/employee/?user=<?php echo $get_user->user_login ?>"><?php echo $get_user->user_firstname .' '. $get_user->user_lastname;; ?></a>
										<?php } ?>
										<?php echo ' - '; ?>
										<?php $user_meta_userid = get_userdata($get_userid->ID); ?>
										<?php $user_role_userid = $user_meta_userid->roles[0]; ?>
										<?php if($user_role_userid == 'employeur'){ ?>
											<a href="<?php get_site_url(); ?>/employeur/?user=<?php echo $get_userid->user_login ?>"><?php echo $nicename; ?></a>
										<?php } elseif($user_role_userid == 'employer'){ ?>
											<a href="<?php get_site_url(); ?>/employee/?user=<?php echo $get_userid->user_login ?>"><?php echo $nicename; ?></a>
										<?php } ?>
										<?php echo '<br>';
										echo date( 'd M Y h:iA', strtotime( $avis->post_date ) );
										echo '<br>';
									    	echo $avis->post_content;
									    	echo '<br>';
										echo 'Horaire:';			
										echo get_user_meta( $avis->ID, 'horaire_key', true);
										echo ' - ';
										echo 'Superieur:';
										echo get_user_meta( $avis->ID, 'superieur_key', true);
										echo ' - ';
										echo 'Paie:';
										echo get_user_meta( $avis->ID, 'paie_key', true);
								    	
								    }
							    	
							    	}
							
							
							    }

			   
			    
			}
			    
		}
		
		echo '</div>'; 
    		
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

?>