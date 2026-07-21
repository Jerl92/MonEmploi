<?php

function monemploi_user_edit_info() {

	$errors = new WP_Error();
	$userdata = wp_get_current_user();

	$cover_photo = get_user_meta($userdata->ID, 'cover_photo', true);
	$user_avatar = get_user_meta($userdata->ID, 'user_avatar', true);
	
	$user_nicename = $userdata->user_nicename;
	$user_firstname = $userdata->first_name;
	$user_lastname = $userdata->last_name;
	$user_email = $userdata->user_email;

	$company_key = get_user_meta($userdata->ID, 'company_key', true);
	$adresse_key = get_user_meta($userdata->ID, 'adresse_key', true);
	$city_key = get_user_meta($userdata->ID, 'city_key', true);
	$province_key = get_user_meta($userdata->ID, 'province_key', true);
	$country_key = get_user_meta($userdata->ID, 'country_key', true);
	$postal_code_key = get_user_meta($userdata->ID, 'postal_code_key', true);
	$phone_key = get_user_meta($userdata->ID, 'phone_key', true);
	$poste_key = get_user_meta($userdata->ID, 'poste_key', true);
	
	$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . strtok($_SERVER['REQUEST_URI'], '?');
	
	if(isset($_GET['new_email'])) {
        	$all_users = get_users();
        	foreach ($all_users as $user) {
        		$uniquekey = get_user_meta($user->ID, 'unique_email_key', true);
			if($_GET['new_email'] === $uniquekey){
				$newemail = get_user_meta($user->ID, 'new_email_key', true);
				$user_data = array(
					'ID'         => $user->ID,
					'user_email' => $newemail,
				);				
				$updated_user_email = wp_update_user( $user_data );
				if($updated_user_email){
					if(!is_user_logged_in() || get_current_user_id() != $user->ID){
						wp_clear_auth_cookie();
						wp_set_current_user($user->ID, $user->user_login);
						wp_set_auth_cookie($user->ID);
						do_action('wp_login', $user->user_login, $user);
					}
					if($_GET['refresh'] == 0){
						$pathInfo = parse_url($_SERVER['REQUEST_URI']);
						$queryString = $pathInfo['query'];
						$array = explode("&", $queryString);
						$array[1] = '&refresh=1';
						implode($array);
						header("Refresh:0;url=" . $current_url . "?" . implode($array) . "");
					} else {
                        update_user_meta($user->ID, 'unique_email_key', null);
                        update_user_meta($user->ID, 'new_email_key', null);
					}
            	}
			}
		}
        }
	
	if(is_user_logged_in()){

	        echo '<div class="user-info" style="display: flex;">';
		        echo '<div class="user-info-menu" style="padding-right: 15px; width: 20%; text-align: center;">';
		        
		        	$user = wp_get_current_user();
				$user_avatar = get_user_meta($user->ID, 'user_avatar', true);
				$image_url = wp_get_attachment_url($user_avatar);
				
				if ( $image_url ) {
				    echo '<img src="' . esc_url( $image_url ) . '" style="border-radius: 50%; width: 150px; height: 150px; object-fit: cover; object-position: center; border: 2.5px solid black;">';
				} else {
					//
				}
				echo '</br>';
				echo $user->user_firstname. ' ' .$user->user_lastname;
				echo '</br>';
				$user_meta = get_userdata($user->ID);
				$user_role = $user_meta->roles[0];
				if($user_role == 'employeur'){
					echo '<a href="'. trailingslashit( get_home_url() ) .'employeur/?user='. $user->user_nicename .'">Voir Profile</a>';
				}
				
				if($user_role == 'employer'){
					echo '<a href="'. trailingslashit( get_home_url() ) .'employee/?user='. $user->user_nicename .'">Voir Profile</a>';
				}
				
				echo '</br>';
				echo '</br>';
		        
		        echo '<div class="main-user-edit-info-menu">';
					echo '<a href="'. $current_url .'">Accueille</a>';
				echo '</div>';
				echo '<div class="user-edit-info-menu">';
					echo '<a href="'. $current_url .'?edit=true">Editer les informations</a>';
				echo '</div>';
				echo '<div class="user-change-password-menu">';
					echo '<a href="'. $current_url .'?password_change=true">Changer le mot de passe</a>';
				echo '</div>';
				echo '<div class="user-privacy-menu">';
					echo '<a href="'. $current_url .'?privacy=true">Confidentialité</a>';
				echo '</div>';
				echo '<div class="user-delete-account-menu">';
					echo '<a href="'. $current_url .'?delete_account=true">Supprimer votre compte</a>';
				echo '</div>';
			echo '</div>';
			
			$url = $_SERVER['REQUEST_URI'];
    		$queryString = parse_url($url, PHP_URL_QUERY);
    		parse_str($queryString, $params);
			
			if (implode($params) == ''){
			    
			    echo '<div class="main-user-edit-info" style="width: 75%;">';
			    
			        echo '<h2>Accueille</h2>';
			    
			        echo '<p>C&#8216;est ici que la magie commence.</p>';
			        
			        $i = 1;
			        
			        $login_infos = get_user_meta($user->ID, 'login_info', true);
			        
			        foreach($login_infos as $login_info){
			            if($i <= 25){
			                echo $i;
			                echo ' - ';
                            echo date("Y-m-d H:i:s", $login_info[0]);
                            echo ' - ';
                            echo $login_info[1];
                            echo ' - ';
                            echo $login_info[2];
                            echo '<br/>';
			            }
			            $i++;
			        }
			    
			    echo '</div>';
			    
			}
		
			if ($_GET['edit'] == true || $_GET['edit_update'] == true || $_GET['edit_update_email'] == true || $_GET['new_email'] == true) {
				echo '<div class="user-edit-info" style="width: 80%;">';
				
				    echo '<h2>Editer les informations</h2>';
				
				     if ($_GET['edit_update'] == true) {
						echo "<p>Tout les informations du compte on ete sauvegarder.</p>";
					}
					
					if ($_GET['edit_update_email'] == true) {
						echo "<p>Un email avec un lien de confirmation est envoyé au nouveau email.</p>";
					}
					
					if(isset($_GET['new_email'])) {
					    echo "<p>La confirmation du nouveau couriel est fait.</p>";
					}
				
	                if(isset($_GET['delete_attachment'])) {
					    echo '<p>La suppression de l&#8216;attachment #'. $_GET['delete_attachment'] . ' est confirmé.</p>';
					}
				
					echo '<div class="attachment-wrapper" style="display: flex;">';

						echo '<div class="cover-photo">';
						echo 'Photo de couverture:';
						echo '</br>';
						echo wp_get_attachment_image( $cover_photo, 'thumbnail' );
						if(wp_get_attachment_image( $cover_photo, 'thumbnail' )){
						echo '</br>';
                            echo '<form action="'. $_SERVER['REQUEST_URI'] .'" method="post">';
		                        echo '<input type="hidden" name="attachmentid" value="' . $cover_photo . '" />';
		                        echo '<input type="hidden" name="action" value="delete_image_attachment" />';
		                        echo '<button type="submit" name="submit" style="padding: 0; margin: 0;">';
		                        	echo '<i class="material-icons">';
		            				echo 'delete';
		            			echo '</i>';
		            		echo '</button>';
	                        echo '</form>';
						}
						echo '</div>';
						

						echo '<div class="user-avatar">';
						echo 'Photo d&#8216;avatar:';
						echo '</br>';
						echo wp_get_attachment_image( $user_avatar, 'thumbnail' );
						if(wp_get_attachment_image( $user_avatar, 'thumbnail' )){
						echo '</br>';
                            echo '<form action="'. $_SERVER['REQUEST_URI'] .'" method="post">';
    		                        echo '<input type="hidden" name="attachmentid" value="' . $user_avatar . '" />';
		                        echo '<input type="hidden" name="action" value="delete_image_attachment" />';
		                        echo '<button type="submit" name="submit" style="padding: 0; margin: 0;">';
		                        	echo '<i class="material-icons">';
		            				echo 'delete';
		            			echo '</i>';
		            		echo '</button>';
	                        echo '</form>';
						}
						echo '</div>';
					echo '</div>';
					
					echo '<form action="'. $_SERVER['REQUEST_URI'] .'" method="post" enctype="multipart/form-data">';
					            echo '<br>';
						    echo '<span>Photo de couverture</span>';
						    echo '<br>';
						    echo '<input type="file" name="cover_photo_file" id="cover_photo_file" />';
						    echo '<br>';
						    echo '<span>Photo d&#8216;avatar</span>';
						    echo '<br>';
						    echo '<input type="file" name="user_avatar_file" id="user_avatar_file" />';
						    echo '<br>';
						    echo '<br>';
					
						echo '<input type="text" id="user_nicename" name="user_nicename" placeholder="Votre nom d&#8216;utilisateur" value="' . $user_nicename . '" disabled style="width: 100%;">';
						echo '<br>';
						echo '<input type="text" id="user_firstname" name="user_firstname" placeholder="Votre prenom" value="' . $user_firstname . '" style="width: 100%;">';
						echo '<br>';
						echo '<input type="text" id="user_lastname" name="user_lastname" placeholder="Votre nom de famille" value="' . $user_lastname . '" style="width: 100%;">';
						echo '<br>';
						echo '<input type="text" id="user_email" name="user_email" placeholder="Votre E-Mail" value="' . $user_email . '" style="width: 100%;">';
						echo '<br>';
						echo '<input type="text" id="company_key" name="company_key" placeholder="Votre nom de compagnie" value="' . $company_key . '" style="width: 100%;">';
						echo '<br>';
						echo '<input type="text" id="adresse_key" name="adresse_key" placeholder="Votre adresse" value="' . $adresse_key . '" style="width: 100%;">';
						echo '<br>';
						echo '<input type="text" id="city_key" name="city_key" placeholder="Votre ville" value="' . $city_key . '" style="width: 100%;">';
						echo '<br>';
						echo '<input type="text" id="province_key" name="province_key" placeholder="Votre province" value="' . $province_key . '" style="width: 100%;">';
						echo '<br>';
						echo '<input type="text" id="country_key" name="country_key" placeholder="Votre pays" value="' . $country_key . '" style="width: 100%;">';
						echo '<br>';
						echo '<input type="text" id="postal_code_key" name="postal_code_key" placeholder="Votre code postal" value="' . $postal_code_key . '" style="width: 100%;">';
						echo '<br>';
						echo '<input type="text" id="phone_key" name="phone_key" placeholder="Votre numero de téléphone" value="' . $phone_key . '" style="width: 100%;">';
						echo '<br>';
						echo '<input type="text" id="poste_key" name="poste_key" placeholder="Votre numero de poste" value="' . $poste_key . '" style="width: 100%;">';
						echo '<br>';
						echo '<input type="submit" value="Mettre a jour" />';
						echo '<input type="hidden" name="action" value="update_user_info_action" />';
					echo '</form>';
				echo '</div>';
			}
			
			if ($_GET['password_change'] == true || $_GET['password_change_successfully'] == true) {
			
				echo '<div class="password-change" style="width: 75%;">';
				
				    echo '<h2>Changer le mot de passe</h2>';
				
					if ($_GET['password_change_successfully'] == true) {
						echo '<p>Le mot de passe a été changé avec succès.</p>';
					}
				
					echo '<form action="'. $_SERVER['REQUEST_URI'] .'" method="post">';
						echo '<input type="password" id="old_password" name="old_password" placeholder="Votre mot de passe actuel" style="width: 100%;">';
						echo '<br>';
						echo '<input type="password" id="new_password" name="new_password" placeholder="Votre nouveau mot de passe" style="width: 100%;">';
						echo '<br>';
						echo '<input type="password" id="retype_new_password" name="retype_new_password" placeholder="Retaper votre nouveau mot de passe" style="width: 100%;">';
						echo '<br>';
						echo '<input type="hidden" name="userid" value="'. $userdata->ID .'" />';
						echo '<input type="hidden" name="action" value="update_user_password_action" />';
						echo '<input type="submit" value="Mettre a jour le mot de passe" />';
					echo '</form>';
					
				echo '</div>';
				
			}
			
            if ($_GET['privacy'] == true) {
			
				echo '<div class="privacy" style="width: 80%;">';
				
				    echo '<h2>Confidentialité</h2>';
				    
					    if ($_GET['privacy_update'] == true) {
						echo '<p>Les paramètres ont été sauvegardé avec succès.</p>';
					    }
									
					echo '<form action="'. $_SERVER['REQUEST_URI'] .'" method="post">';
					
					echo '<p>General</p>';
					
					$hide_search = get_user_meta( $userdata->ID, 'hide_search_key', true);
					echo '<span>Masquer mon profile de la recherche.</span><br>';
					
					if($hide_search == 1){
						echo '<input type="radio" id="hide-search" name="hide-search" value="1" checked>';
					} else {
			                	echo '<input type="radio" id="hide-search" name="hide-search" value="1">';
			                } 
			                echo '<label for="html">Oui</label>';
								
					if($hide_search == 0 || $hide_search == ''){
						echo '<input type="radio" id="hide-search" name="hide-search" value="0" checked>';
					} else {
			                	echo '<input type="radio" id="hide-search" name="hide-search" value="0">';
			                } 
			                echo '<label for="html">Non</label>';
			                    
			                echo '<br>';
			                $hide_dashboard = get_user_meta( $userdata->ID, 'hide_dashboard_key', true);
			                echo '<span>Masquer mon profile du tableaux de bord.</span><br>';
					
					if($hide_dashboard == 1){
			                	echo '<input type="radio" id="hide-dashboard" name="hide-dashboard" value="1" checked>';
			                } else {
			                	echo '<input type="radio" id="hide-dashboard" name="hide-dashboard" value="1">';
			                }
			                echo '<label for="html">Oui</label>';
								
					if($hide_dashboard == 0 || $hide_dashboard == ''){
			                	echo '<input type="radio" id="hide-dashboard" name="hide-dashboard" value="0" checked>';
			                } else {
			                	echo '<input type="radio" id="hide-dashboard" name="hide-dashboard" value="0">';
			                }
			                echo '<label for="html">Non</label>';
			                
			                echo '<br>';
			                $hide_adresse = get_user_meta( $userdata->ID, 'hide_adresse_key', true);
			        	echo '<span>Masquer mon adresse dans mon profile.</span><br>';
					
					if($hide_adresse == 1){
			                echo '<input type="radio" id="hide-adresse" name="hide-adresse" value="1" checked>';
			                } else {
			                echo '<input type="radio" id="hide-adresse" name="hide-adresse" value="1">';
			                } 
			                echo '<label for="html">Oui</label>';
								
					if($hide_adresse == 0 || $hide_adresse == ''){
			                echo '<input type="radio" id="hide-adresse" name="hide-adresse" value="0" checked>';
			                } else {
			                echo '<input type="radio" id="hide-adresse" name="hide-adresse" value="0">';
			                } 
			                echo '<label for="html">Non</label>';
			                
			                echo '<br>';
			                $hide_contact = get_user_meta( $userdata->ID, 'hide_contact_key', true);
			              	echo '<span>Masquer les informations de contact dans mon profile.</span><br>';
					
					if($hide_contact == 1){
			                	echo '<input type="radio" id="hide-contact" name="hide-contact" value="1" checked>';
			                } else {
			                	echo '<input type="radio" id="hide-contact" name="hide-contact" value="1">';
			                }
			                echo '<label for="html">Oui</label>';
								
					if($hide_contact == 0 || $hide_contact == ''){
			                	echo '<input type="radio" id="hide-contact" name="hide-contact" value="0" checked>';
			                } else {
			                	echo '<input type="radio" id="hide-contact" name="hide-contact" value="0">';
			                }
			                echo '<label for="html">Non</label>';
			                
			                echo '<br>';
			                
			                $hide_adresse_replace = get_user_meta( $userdata->ID, 'hide_adresse_replace_key', true);
			         	echo '<span>Masquer l&#8216;adress du remplaçant.</span><br>';
					
					if($hide_adresse_replace == 1){
			                	echo '<input type="radio" id="hide-adress-replace" name="hide-adress-replace" value="1" checked>';
			                } else {
			                	echo '<input type="radio" id="hide-adress-replace" name="hide-adress-replace" value="1">';
			                }
			                echo '<label for="html">Oui</label>';
								
					if($hide_adresse_replace == 0 || $hide_adresse_replace == ''){
			                	echo '<input type="radio" id="hide-adress-replace" name="hide-adress-replace" value="0" checked>';
			                } else {
			                	echo '<input type="radio" id="hide-adress-replace" name="hide-adress-replace" value="0">';
			                }
			                echo '<label for="html">Non</label>';
			                
			                echo '<br>';
			                
		   			$hide_contact_replace = get_user_meta( $userdata->ID, 'hide_contact_replace_key', true);
					echo '<span>Masquer les informations de contact du remplaçant.</span><br>';
					
					if($hide_contact_replace == 1){
			                	echo '<input type="radio" id="hide-contact-replace" name="hide-contact-replace" value="1" checked>';
			                } else {
			                	echo '<input type="radio" id="hide-contact-replace" name="hide-contact-replace" value="1">';
			                }
			                echo '<label for="html">Oui</label>';
								
					if($hide_contact_replace == 0 || $hide_contact_replace == ''){
			                	echo '<input type="radio" id="hide-contact-replace" name="hide-contact-replace" value="0" checked>';
			                } else {
			                	echo '<input type="radio" id="hide-contact-replace" name="hide-contact-replace" value="0">';
			                }
			                echo '<label for="html">Non</label>';
			                
			                echo '<p>Widget<p>';
			                			          	
			          	$hide_widget_finish_job = get_user_meta( $userdata->ID, 'hide_widget_finish_job_key', true);
			          	echo '<span>Masquer la derniere emplois des emplois recent des widgets.</span><br>';
					
					if($hide_widget_finish_job == 1){
			                	echo '<input type="radio" id="hide-widget-finish-job" name="hide-widget-finish-job" value="1" checked>';
			                } else {
			                	echo '<input type="radio" id="hide-widget-finish-job" name="hide-widget-finish-job" value="1">';
			                }
			                echo '<label for="html">Oui</label>';
								
					if($hide_widget_finish_job == 0 || $hide_widget_finish_job == ''){
			                	echo '<input type="radio" id="hide-widget-finish-job" name="hide-widget-finish-job" value="0" checked>';
			                } else {
			                	echo '<input type="radio" id="hide-widget-finish-job" name="hide-widget-finish-job" value="0">';
			                }
			                echo '<label for="html">Non</label>';
			                
			          	echo '<br>';
			          	
			          	$hide_widget_school = get_user_meta( $userdata->ID, 'hide_widget_school_key', true);
			       		echo '<span>Masquer la derniere école des écoles récente dans les widgets.</span><br>';
					
					if($hide_widget_school == 1){
			                	echo '<input type="radio" id="hide-widget-school" name="hide-widget-school" value="1" checked>';
			                } else {
			                	echo '<input type="radio" id="hide-widget-school" name="hide-widget-school" value="1">';
			                }
			                echo '<label for="html">Oui</label>';
								
					if($hide_widget_school == 0 || $hide_widget_school == ''){
			                	echo '<input type="radio" id="hide-widget-school" name="hide-widget-school" value="0" checked>';
			                } else {
			                	echo '<input type="radio" id="hide-widget-school" name="hide-widget-school" value="0">';
			                }
			                echo '<label for="html">Non</label>';
			                
			          	echo '<br>';
			          	
			          	$hide_widget_new_employee = get_user_meta( $userdata->ID, 'hide_widget_new_employee_key', true);
			          	echo '<span>Masquer le compte du widget des nouveaux employer.</span><br>';
					
					if($hide_widget_new_employee == 1){
			                	echo '<input type="radio" id="hide-new-employee" name="hide-new-employee" value="1" checked>';
			                } else {
			                	echo '<input type="radio" id="hide-new-employee" name="hide-new-employee" value="1">';
			                }
			                echo '<label for="html">Oui</label>';
								
					if($hide_widget_new_employee == 0 || $hide_widget_new_employee == ''){
			                	echo '<input type="radio" id="hide-new-employee" name="hide-new-employee" value="0" checked>';
			                } else {
			                	echo '<input type="radio" id="hide-new-employee" name="hide-new-employee" value="0">';
			                }
			                echo '<label for="html">Non</label>';
			                
			          	echo '<br>';
			                
			                echo '<p>Chat</p>';
			                $disable_chat = get_user_meta( $userdata->ID, 'disable_chat_key', true);
			              	echo '<span>Désactiver le chat.</span><br>';
					
					if($disable_chat == 1){
			                	echo '<input type="radio" id="disable-chat" name="disable-chat" value="1" checked>';
			                } else {
			                	echo '<input type="radio" id="disable-chat" name="disable-chat" value="1">';
			                }
			                echo '<label for="html">Oui</label>';
								
					if($disable_chat == 0 || $disable_chat == ''){
			                	echo '<input type="radio" id="disable-chat" name="disable-chat" value="0" checked>';
			                } else {
			                	echo '<input type="radio" id="disable-chat" name="disable-chat" value="0">';
			                }
			                echo '<label for="html">Non</label>';
			                
			                echo '<br>';
			                
			               	$hide_online = get_user_meta( $userdata->ID, 'hide_online_key', true);
			              	echo '<span>Masquer le status en ligne/hors-ligne.</span><br>';
					
					if($hide_online == 1){
			                	echo '<input type="radio" id="hide-online" name="hide-online" value="1" checked>';
			                } else {
			                	echo '<input type="radio" id="hide-online" name="hide-online" value="1">';
			                }
			                echo '<label for="html">Oui</label>';
								
					if($hide_online == 0 || $hide_online == ''){
			                	echo '<input type="radio" id="hide-online" name="hide-online" value="0" checked>';
			                } else {
			                	echo '<input type="radio" id="hide-online" name="hide-online" value="0">';
			                }
			                echo '<label for="html">Non</label>';
			                
			                echo '<br>';
			                
			               	$hide_seen = get_user_meta( $userdata->ID, 'hide_seen_key', true);
			              	echo '<span>Masquer le status vue/non-vue des messages.</span><br>';
					
					if($hide_seen == 1){
			                	echo '<input type="radio" id="hide-seen" name="hide-seen" value="1" checked>';
			                } else {
			                	echo '<input type="radio" id="hide-seen" name="hide-seen" value="1">';
			                }
			                echo '<label for="html">Oui</label>';
								
					if($hide_seen == 0 || $hide_seen == ''){
			                	echo '<input type="radio" id="hide-seen" name="hide-seen" value="0" checked>';
			                } else {
			                	echo '<input type="radio" id="hide-seen" name="hide-seen" value="0">';
			                }
			                echo '<label for="html">Non</label>';
			                
					$userid = $userdata->ID;
					$user_meta = get_userdata($userid);
					$user_role = $user_meta->roles[0];
					if($user_role == 'employeur'){
			                
			                	echo '<p>Employeur</p>';
			                
				                $hide_adresse_job = get_user_meta( $userdata->ID, 'hide_adresse_job_key', true);
				        	echo '<span>Masquer mon adresse dans les annonces.</span><br>';
						
						if($hide_adresse_job == 1){
				                echo '<input type="radio" id="hide-adresse-job" name="hide-adresse-job" value="1" checked>';
				                } else {
				                echo '<input type="radio" id="hide-adresse-job" name="hide-adresse-job" value="1">';
				                } 
				                echo '<label for="html">Oui</label>';
									
						if($hide_adresse_job == 0 || $hide_adresse_job == ''){
				                echo '<input type="radio" id="hide-adresse-job" name="hide-adresse-job" value="0" checked>';
				                } else {
				                echo '<input type="radio" id="hide-adresse-job" name="hide-adresse-job" value="0">';
				                } 
				                echo '<label for="html">Non</label>';
				                
				                echo '<br>';
				                $hide_contact_job = get_user_meta( $userdata->ID, 'hide_contact_job_key', true);
				              	echo '<span>Masquer les informations de contact dans les annonces.</span><br>';
						
						if($hide_contact_job == 1){
				                	echo '<input type="radio" id="hide-contact-job" name="hide-contact-job" value="1" checked>';
				                } else {
				                	echo '<input type="radio" id="hide-contact-job" name="hide-contact-job" value="1">';
				                }
				                echo '<label for="html">Oui</label>';
				                
				                if($hide_contact_job == 0 || $hide_contact_job == ''){
				                	echo '<input type="radio" id="hide-contact-job" name="hide-contact-job" value="0" checked>';
				                } else {
				                	echo '<input type="radio" id="hide-contact-job" name="hide-contact-job" value="0">';
				                }
				                echo '<label for="html">Non</label>';
				                
				                echo '<br>';
				                
				          	$hide_adresse_candidacy = get_user_meta( $userdata->ID, 'hide_adresse_candidacy_key', true);
				        	echo '<span>Masquer mon adresse dans les pages de candidature.</span><br>';
						
						if($hide_adresse_candidacy == 1){
				                echo '<input type="radio" id="hide-adresse-candidacy" name="hide-adresse-candidacy" value="1" checked>';
				                } else {
				                echo '<input type="radio" id="hide-adresse-candidacy" name="hide-adresse-candidacy" value="1">';
				                } 
				                echo '<label for="html">Oui</label>';
									
						if($hide_adresse_candidacy == 0 || $hide_adresse_candidacy == ''){
				                echo '<input type="radio" id="hide-adresse-candidacy" name="hide-adresse-candidacy" value="0" checked>';
				                } else {
				                echo '<input type="radio" id="hide-adresse-candidacy" name="hide-adresse-candidacy" value="0">';
				                } 
				                echo '<label for="html">Non</label>';
				                
				                echo '<br>';
				                $hide_contact_candidacy = get_user_meta( $userdata->ID, 'hide_contact_candidacy_key', true);
				              	echo '<span>Masquer les informations de contact dans les pages de candidature.</span><br>';
						
						if($hide_contact_candidacy == 1){
				                	echo '<input type="radio" id="hide-contact-candidacy" name="hide-contact-candidacy" value="1" checked>';
				                } else {
				                	echo '<input type="radio" id="hide-contact-candidacy" name="hide-contact-candidacy" value="1">';
				                }
				                echo '<label for="html">Oui</label>';
				                
				                if($hide_contact_candidacy == 0 || $hide_contact_candidacy == ''){
				                	echo '<input type="radio" id="hide-contact-candidacy" name="hide-contact-candidacy" value="0" checked>';
				                } else {
				                	echo '<input type="radio" id="hide-contact-candidacy" name="hide-contact-candidacy" value="0">';
				                }
				                echo '<label for="html">Non</label>';
				           
				                echo '<br>';
				                
				                $hide_jobs_search = get_user_meta( $userdata->ID, 'hide_jobs_search_key', true);
				              	echo '<span>Masquer les annonces de la page de recherche.</span><br>';
						
						if($hide_jobs_search == 1){
				                	echo '<input type="radio" id="hide-jobs-search" name="hide-jobs-search" value="1" checked>';
				                } else {
				                	echo '<input type="radio" id="hide-jobs-search" name="hide-jobs-search" value="1">';
				                }
				                echo '<label for="html">Oui</label>';
				                
				                if($hide_jobs_search == 0 || $hide_jobs_search == ''){
				                	echo '<input type="radio" id="hide-jobs-search" name="hide-jobs-search" value="0" checked>';
				                } else {
				                	echo '<input type="radio" id="hide-jobs-search" name="hide-jobs-search" value="0">';
				                }
				                echo '<label for="html">Non</label>';
				                
				                echo '<br>';
				                
				                $hide_widget_new_jobs = get_user_meta($userdata->ID, 'hide_widget_new_jobs_key', true);
				    		echo '<span>Masquer les nouvelles offres d&#8216;emplois des widgets de l&#8216;employeur.</span><br>';
						
						if($hide_widget_new_jobs == 1){
				                	echo '<input type="radio" id="hide-widget-new-jobs" name="hide-widget-new-jobs" value="1" checked>';
				                } else {
				                	echo '<input type="radio" id="hide-widget-new-jobs" name="hide-widget-new-jobs" value="1">';
				                }
				                echo '<label for="html">Oui</label>';
									
						if($hide_widget_new_jobs == 0 || $hide_widget_new_jobs == ''){
				                	echo '<input type="radio" id="hide-widget-new-jobs" name="hide-widget-new-jobs" value="0" checked>';
				                } else {
				                	echo '<input type="radio" id="hide-widget-new-jobs" name="hide-widget-new-jobs" value="0">';
				                }
				                echo '<label for="html">Non</label>';
				                
				          	echo '<br>';
				          	
				          	$hide_widget_new_employeur = get_user_meta($userdata->ID, 'hide_widget_new_employeur_key', true);
				    		echo '<span>Masquer l&#8216;employeur du widget des nouveaux employeurs.</span><br>';
						
						if($hide_widget_new_employeur == 1){
				                	echo '<input type="radio" id="hide_widget_new_employeur" name="hide_widget_new_employeur" value="1" checked>';
				                } else {
				                	echo '<input type="radio" id="hide_widget_new_employeur" name="hide_widget_new_employeur" value="1">';
				                }
				                echo '<label for="html">Oui</label>';
									
						if($hide_widget_new_employeur == 0 || $hide_widget_new_employeur == ''){
				                	echo '<input type="radio" id="hide_widget_new_employeur" name="hide_widget_new_employeur" value="0" checked>';
				                } else {
				                	echo '<input type="radio" id="hide_widget_new_employeur" name="hide_widget_new_employeur" value="0">';
				                }
				                echo '<label for="html">Non</label>';
				                
				          	echo '<br>';
			                
			                }
			                
			                echo '<br>';
			                echo '<br>';
								
			                echo '<input type="hidden" name="userid" value="'. $userdata->ID .'" />';
					echo '<input type="hidden" name="action" value="privacy_action" />';
					echo '<input type="submit" value="Sauvegarder" />';
					
				    
                    echo '</form>';
			
			    echo '</div>';
			    
            }
			
			if ($_GET['delete_account'] == true) {
			
				echo '<div class="delete-account" style="width: 80%;">';
					
				if ($_GET['delete_account_wrong_password'] == true) {
					echo '<p>Le mot de passe n&#8216;est pas valide.</p>';
				}
				
				if ($_GET['delete_account_password_empty'] == true) {
					echo '<p>Le mot de passe est vide.</p>';
				}
				
				    echo '<h2>Supprimer votre compte</h2>';
				
					echo '<form action="'. $_SERVER['REQUEST_URI'] .'" method="post">';
						echo '<span>Êtes-vous sûr de vouloir supprimer votre compte ?</span>';
						echo '<br>';
						echo '<span>Cette action effacera toutes les données de votre compte sur le site.</span>';
						echo '<br>';
						echo '<span>Pour supprimer votre compte, veuillez saisir votre mot de passe ci-dessous.</span>';
						echo '<br>';
						echo '<input type="password" id="password" name="password" placeholder="Votre mot de passe" style="width: 100%;">';
						echo '<br>';
						echo '<input type="hidden" name="userid" value="'. $userdata->ID .'" />';
						echo '<input type="hidden" name="action" value="delete_account_action" />';
						echo '<input type="submit" value="Supprimer votre compte" />';
					echo '</form>';
					
				echo '</div>';
			
			}
		echo '</div>';
	
	} else {
	
		echo '<h2>Vous n&#8216;avez pas l&#8216;autorisation pour consulter cette page</h2>';
	
	} 
	
}
add_shortcode('monemploi-user-edit-info', 'monemploi_user_edit_info');

?>