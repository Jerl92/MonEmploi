<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function monemploi_login() {
    
    if (isset($_GET['key'])) {
        
        $user_ids = get_users( array( 'fields' => array( 'ID' ) ) );
    
        foreach ( $user_ids as $user ) {
            $uniquekey = get_user_meta($user->ID, 'unique_key', true);
            $ifactivated = get_user_meta($user->ID, 'account_status', true);
            if($_GET['key'] == $uniquekey){
                if($ifactivated == 0){
                    update_user_meta($user->ID, 'account_status', 'approved');
                    echo "<p>La confirmation du compte est fait, vous pouvez vous connectez.</p>";
                } else if ($ifactivated == 'approved'){
                    echo "<p>Votre lien est expirer, votre compte est deja activé.";
                }
            }
        }
    }
    
	if(!is_user_logged_in()) {
	   
		?><form action="" method="post">
			<div>
				<input name="log" type="text" placeholder="Nom d'utilisateur ou E-Mail"/>
			</div>
			<div>
				<input name="pwd" type="password" placeholder="Mot de passe"/>
			</div>
			<div>
				<input type="submit" value="Connexion" />
				<input type="hidden" name="action" value="my_login_action" />
			</div>
		</form><?php
	
	} else {
	
		$current_user = wp_get_current_user();
        $user_info = get_userdata($current_user->ID);
        $user_roles = $user_info->roles;
        if(implode($user_roles) == 'employeur'){
    		if ( $current_user->exists() ) {
    			header("Location: " . get_home_url() . "/employeur/?user=" . $current_user->user_login. "");
    		}
        }
        if(implode($user_roles) == 'employer'){
    		if ( $current_user->exists() ) {
    			header("Location: " . get_home_url() . "/employee/?user=" . $current_user->user_login. "");
    		}
        }
        if(implode($user_roles) == 'administrator'){
    		if ( $current_user->exists() ) {
    			header("Location: " . get_home_url() . "");
    		}
        }
	
	}
    	
}
add_shortcode('monemploi-login', 'monemploi_login');

?>