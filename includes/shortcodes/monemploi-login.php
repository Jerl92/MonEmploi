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
		        if($ifactivated == 'pending'){
		            update_user_meta($user->ID, 'account_status', 'approved');
		            echo "<p>La confirmation du compte est fait, vous pouvez maintenant vous connecter.</p>";
		        } else if ($ifactivated == 'approved'){
		            echo "<p>Votre lien est expirer, votre compte est deja activé.</p>";
		        }
		    }
		}
	}
	
	if(isset($_GET['forget_password_key']) && $_GET['forget_password_key'] != ''){
		if(is_user_logged_in){
			wp_logout();
		}
	}
	
	if(!is_user_logged_in()) {
        $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . strtok($_SERVER['REQUEST_URI'], '?');

        if ($_GET['forget_password_new'] == true) {
            echo '<p>Votre mot de passe à été mis a jour.</p>';
        }
        
        if ($_GET['forget_password_send'] == true) {
            echo '<p>Un email de confirmation à ete envoyé a votre courriel pour valider la demande du nouveau mot de passe.</p>';
        }
        
         if($_GET['forget_password'] != true && !isset($_GET['forget_password_key']) && $_GET['forget_password_empty'] != true) {
		?><form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
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
		
		echo '<button><a href="' . $current_url . '?forget_password=true">Mot de passe oublié</a></button>';
		
         }
		
        if ($_GET['forget_password'] == true || $_GET['forget_password_empty'] == true) {
            if($_GET['forget_password_empty'] == true){
                echo '<p>Le champ est vide.</p>';
            }
            echo '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">';
	        echo '<div>';
				echo '<input name="login" type="text" placeholder="Nom dutilisateur ou E-Mail"/>';
			echo '</div>';
			echo '<div>';
				echo '<input type="submit" value="Réinisialiser" />';
				echo '<input type="hidden" name="action" value="my_forget_password_action" />';
			echo '</div>';
            echo '</form>';
        }
        
        if (isset($_GET['forget_password_key'])) {
            
            $x = 0;
            $user_ids = get_users( array( 'fields' => array( 'ID' ) ) );
        
            foreach ( $user_ids as $user ) {
                $uniquestring = get_user_meta($user->ID, 'unique_string', true);
                if($_GET['forget_password_key'] == $uniquestring && $_GET['forget_password_key'] != ''){
                    echo '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">';
        	        echo '<div>';
        				echo '<input name="password" type="password" placeholder="Nouveaux mot de passe"/>';
        			echo '</div>';
                    echo '<div>';
        				echo '<input name="retype_password" type="password" placeholder="Nouveaux mot de passe encore"/>';
        			echo '</div>';
        			echo '<div>';
        				echo '<input type="submit" value="Mettre a jour le mot de passe" />';
        				echo '<input type="hidden" name="action" value="my_forget_password_new_action" />';
                        echo '<input type="hidden" name="userid" value="'. $user->ID .'" />';
        			echo '</div>';
                    echo '</form>';
                    $x = $uniquestring;
                }
            }
            if($_GET['forget_password_key'] != $x || $_GET['forget_password_key'] == '' || $x == ''){
                echo '<p>Votre lien est expiré.</p>';
            }
        }
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