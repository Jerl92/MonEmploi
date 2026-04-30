<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function monemploi_chat() {

    if(is_user_logged_in()){

	        echo '<div class="user-chat" style="display: flex;">';
		        echo '<div class="user-chat-menu" style="padding-right: 15px; width: 25%;">';
		        
		        	$i = 0;
			        $users = get_users( array( 'fields' => array( 'ID' ) ) );
				foreach($users as $user){
					$userids[$i] = $user->ID;
					$i++;
				}
		        	
		        	$get_args = array( 
					'post_type' => 'chat',
					'posts_per_page' => -1,
					'orderby' => 'date',
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
	                       				$get_chat_history = get_post_meta($chat_menu->ID, 'my_chat_history_key', true);
							$user_by_id = get_user_by('ID', $userid_menu);
							echo '<a href="' . get_site_url() .'/chat/?username=' . $user_by_id->user_nicename . '">' . $user_by_id->user_firstname . ' ' . $user_by_id->user_lastname . '</a>';
							echo '<br>';
							$end_chat_history = end($get_chat_history);
							echo $end_chat_history[2];
							echo '<br>';
                            			}
				        }
				    }
				
				}
		        	
			echo '</div>';
			
			$url = $_SERVER['REQUEST_URI'];
	    		$queryString = parse_url($url, PHP_URL_QUERY);
	    		parse_str($queryString, $params);
			
			if (implode($params) == ''){

				
			    
			}
			
			if(isset($_GET['username'])){
				echo '<div class="user-chat-history" style="width: 75%;">';
					$get_args = array( 
						'post_type' => 'chat',
						'posts_per_page' => -1,
						'orderby' => 'date',
						'order' => 'DESC',
					); 
					
					$get_chats = get_posts( $get_args );
					
					$user_by_username = get_user_by('login', $_GET['username']);
					$user_id_by_username = $user_by_username->ID;
										
					$user_send = null;
					$user_recive = null;
					$chatid = 0;
					$x = 0;
					
					foreach($get_chats as $chat){
						
						$get_chat_author = get_post_meta($chat->ID, 'my_author_id_key', true);
										
                       				 	$user_array = [$user_id_by_username, get_current_user_id()];
                        				if (count(array_intersect($user_array, $get_chat_author)) === count($user_array)) {
							$chatid = $chat->ID;
                            				$get_chat_history = get_post_meta($chatid, 'my_chat_history_key', true);
								foreach($get_chat_history as $chat_history){
									echo $chat_history[0];
									echo ' - ';
									echo $chat_history[1];
									echo ' - ';
									echo $chat_history[2];
									echo '<br>';
						        }
                       				 }
						
					}
					
					echo '<div class="test-chat"></div>'
				
					?><form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
					
					<input name="message-chat" type="text" id="message-chat" class="message-chat" placeholder="Écrivez votre message..." style="width: calc(100% - 100px);" />
					
					<input type="submit" value="Envoyer" />
					<input type="hidden" name="recive_userid" value="<?php echo $user_id_by_username; ?>" />
					<input type="hidden" name="send_userid" value="<?php echo get_current_user_id(); ?>" />
					<input type="hidden" name="chatid" value="<?php echo $chatid; ?>" />
					<input type="hidden" name="action" value="my_chat_send_action" />
					</form><?php
				echo '</div>';
			}
		echo '</div>';
	}

}
add_shortcode('monemploi-chat', 'monemploi_chat');