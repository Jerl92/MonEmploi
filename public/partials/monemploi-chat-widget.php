<?php

function monemploi_chat_widget() {
	register_widget( 'monemploi_chat_widgets');
}
add_action( 'widgets_init', 'monemploi_chat_widget' );

function is_user_online_widget( $user_id ) {
    $online_users = get_user_meta($user_id, 'online_status_', true);
    
    if($online_users == true){
        return 'En ligne';
    } else {
        return 'Hors ligne';
    }
    
    
}

class monemploi_chat_widgets extends WP_Widget {
	public function __construct() {
		// Instantiate the parent object
		parent::__construct(

			// Base ID of your widget
			'monemploi_chat_widgets', 

			// Widget name will appear in UI
			__('monemploi_chat_widgets', 'monemploi'), 

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
					
			$disable_chat = get_user_meta( get_current_user_id(), 'disable_chat_key', true);
			if($disable_chat == 0 || $disable_chat == ''){		
			echo '<div class="chat-menu-widget">';
				$i = 0;
				$if_chat = 0;
			        $users = get_users( array( 'fields' => array( 'ID' ) ) );
				foreach($users as $user){
					$userids[$i] = $user->ID;
					$i++;
				}
		        	
		        	$get_args = array( 
					'post_type' => 'chat',
					'posts_per_page' => -1,
					'orderby' => 'modified',
					'order' => 'DESC',
				); 
				
				$get_chats_ = get_posts( $get_args );
				
				$user_send_menu = null;
				$user_recive_menu = null;
				
				foreach($get_chats_ as $chat_menu){
                    		$get_chat_author_menu = get_post_meta($chat_menu->ID, 'my_author_id_key', true);
				    foreach ($userids as $userid_menu){
                        		if(get_current_user_id() != $userid_menu){
	                            		$user_array_menu = [$userid_menu, get_current_user_id()];
	                            		if (count(array_intersect($user_array_menu, $get_chat_author_menu)) === count($user_array_menu)) {
	                       				$get_chat_menu = get_post_meta($chat_menu->ID, 'my_chat_history_key', true);
							$user_by_id = get_user_by('ID', $userid_menu);
							if($get_chat_menu != null){
							$end_chat_menu = end($get_chat_menu);
								if($end_chat_menu[1] == 0 && $end_chat_menu[3] != get_current_user_id()) {
									echo '<div style="border-bottom: 0.05px solid rgba(0,0,0,0.25);">';
							                    	echo '<a href="' . get_site_url() .'/chat/?username=' . $user_by_id->user_nicename . '">' . $user_by_id->user_firstname . ' ' . $user_by_id->user_lastname . '</a> - ' . is_user_online_widget($userid_menu);
										echo '<div style="display: flex;">';
											echo '<div style="width: 50%; text-align: left;">';
											echo '<span style="font-weight: bold;">' . substr($end_chat_menu[4], 0, 55). '</span>';
											echo '</div>';
											echo '<div style="margin-left: auto;">';
											echo date('Y-m-d H:i:s', $end_chat_menu[2]);
											echo '</div>';
										echo '</div>';
									echo '</div>';
									$if_chat = 1;
								}
								
							}

                            			}
				        }
				    }
				
				}
				if($if_chat == 0) {
				
					echo '<h4 style="text-align: center;">Pas de nouveaux chat</h4>';
				
				}
			echo '</div>';	
			
			} else {
			
				echo '<h4 style="text-align: center;">Le chat est désactivé.</h4>';
				
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