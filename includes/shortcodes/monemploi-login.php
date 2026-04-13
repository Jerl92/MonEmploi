<?php

function monemploi_login() {

	if(!is_user_logged_in()) {
	   
		?><form action="" method="post">
			<div>
				<input name="log" type="text" placeholder="Username ou E-Mail"/>
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

		if ( $current_user->exists() ) {
			header("Location: " . get_home_url() . "/employee/?user=" . $current_user->user_login. "");
		}
	
	}
    	
}
add_shortcode('monemploi-login', 'monemploi_login');

?>