<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function is_user_online( $user_id ) {
    $online_users = get_user_meta($user_id, 'online_status_', true);
    
    if($online_users == true){
        return 'En ligne';
    } else {
        return 'Hors ligne';
    }
    
    
}

function secondsToTime($seconds) {
  // Create DateTime objects representing the start and end timestamps
  $dt1 = new DateTime("@0");
  $dt2 = new DateTime("@$seconds");
  
  // Calculate the difference between the two timestamps
  $diff = $dt1->diff($dt2);
  
  if($seconds > 0) {
 	return ' - ' . $diff->format('%a jours, %h:%i:%s');
  } else {
  	return '';
  }
}

function monemploi_chat() {

    if(is_user_logged_in()){
    
	        echo '<div class="user-chat" style="display: flex;">';
		        echo '<div class="user-chat-menu" style="padding-right: 15px; width: 25%;">';
				echo '<div class="chat-menu-wrapper"></div>';	
			echo '</div>';
			
			$url = $_SERVER['REQUEST_URI'];
	    		$queryString = parse_url($url, PHP_URL_QUERY);
	    		parse_str($queryString, $params);
			
			if (implode($params) == ''){

				
			    
			}
			
			if(isset($_GET['delete'])){
				$author_id = get_post_meta($_GET['delete'], 'my_author_id_key', true);
				if (in_array(get_current_user_id(), $author_id)) {
					wp_delete_post( $_GET['delete'], false);
					echo '<div>Le chat #' . $_GET['delete'] . ' à bien été supprimer</div>';
				}
			}
			
			if(isset($_GET['username'])){
				echo '<div class="user-chat-history" style="width: 75%;">';
					$get_args = array( 
						'post_type' => 'chat',
						'posts_per_page' => -1,
						'orderby' => 'modified',
						'order' => 'DESC'
					); 
					
					$get_chats = get_posts( $get_args );
					
					$user_by_username = get_user_by('login', $_GET['username']);
					$user_id_by_username = $user_by_username->ID;
					$user_roles = $user_by_username->roles;
					$current_time = current_time('timestamp');
					$offline_time = get_user_meta($user_id_by_username, 'offline_time_', true);
					if($offline_time > 0) {
						$offline_calc = $current_time - $offline_time;
					} else {
						$offline_calc = 0;
					}
					$user_send = null;
					$user_recive = null;
					$chatid = 0;
					$x = 0;
										
					if(implode($user_roles) == 'employer'){
				    		echo '<h3><a href="'. get_site_url() .'/employee/?user='. $user_by_username->user_nicename .'">' . $user_by_username->user_nicename. '</a> - ' . $user_by_username->user_firstname . ' ' . $user_by_username->user_lastname. ' - <span class="online-status">' . is_user_online($user_id_by_username) . '</span><span class="offline-time">' . secondsToTime($offline_calc) . '</span></h3>';
				    	}
				    	if(implode($user_roles) == 'employeur'){
				    		echo '<h3><a href="'. get_site_url() .'/employeur/?user='. $user_by_username->user_nicename .'">' . $user_by_username->user_nicename. '</a> - '  . $user_by_username->user_firstname . ' ' . $user_by_username->user_lastname. ' - <span class="online-status">' . is_user_online($user_id_by_username) . '</span><span class="offline-time">' . secondsToTime($offline_calc) . '</span></h3>';
				    	}
					
					$if_found = 0;
					
					foreach($get_chats as $chat){
						
							$get_chat_author = get_post_meta($chat->ID, 'my_author_id_key', true);
										
                       				 	$user_array = [$user_id_by_username, get_current_user_id()];
                        				if (count(array_intersect($user_array, $get_chat_author)) === count($user_array)) {
                        				$if_found = 1;
							$chatid = $chat->ID;
							echo '<div class="chat-id" style="display: none;">' . $chatid . '</div>';
							echo '<div class="user-id" style="display: none;">' . $user_id_by_username . '</div>';
                            				$get_chat_history = get_post_meta($chatid, 'my_chat_history_key', true);
				    			    echo '<div id="user-chat-history-wrapper"  class="user-chat-history-wrapper">';
								foreach($get_chat_history as $chat_history){
									$userid_chat = $chat_history[3];
									$get_user_by_id_chat = get_user_by('ID', $userid_chat);
									echo '<div class="message-id" style="display: none;">' . $chat_history[0] . '</div>';
									if($user_id_by_username == $userid_chat){
										echo '<div style="width: 100%;">';
									}
									if(get_current_user_id() == $userid_chat){
										echo '<div style="display: inline-block; text-align: right; width: 100%">';
									}
									echo '<span style="font-weight: bold;">';
										if($chat_history[1] == 0){
											echo 'Non vue';
										}
										if($chat_history[1] == 1){
											echo 'Vue';
										}
										echo ' - ';
										echo date('Y-m-d H:i:s', $chat_history[2]);
										echo ' - ';
										echo $get_user_by_id_chat->user_firstname . ' ' . $get_user_by_id_chat->user_lastname;
									echo '</span>';
									echo '<br>';
									echo $chat_history[4];
									echo '</div>';
						        }
						        echo '</div>';
                       				 }
						
					}
					
				 	if($if_found == 0) {
               				 
               				 	$my_post = array(
							  'post_title'    => 'Chat',
							  'post_type'    =>  'chat',
							  'post_status'   => 'publish',
							  'post_author'   => get_current_user_id(),
							);
							
						$chatid = wp_insert_post( $my_post );
						
						$author_array = array($user_id_by_username, get_current_user_id());
						
						update_post_meta($chatid, 'my_author_id_key', $author_array);
						
						update_post_meta($chatid, 'my_chat_history_key', null);
													
						$my_post = array(
							'ID' => $chatid
						);
						
						wp_update_post( $my_post );
						
						header("Refresh:0");
               				 
               				 }
					
					?>
					<div style="display: flex">
					<input name="message-chat" type="text" id="message-chat" class="message-chat" placeholder="Écrivez votre message..." style="width: calc(100% - 175px);" required />
					<button class="chat-message-send" style="margin-left: auto;">Envoyer</button>
					</div>
					<?php
				echo '</div>';
			}
		echo '</div>';
	}

}
add_shortcode('monemploi-chat', 'monemploi_chat');