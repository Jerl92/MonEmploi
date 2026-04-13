<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function monemploi_register() {
    
    if ($_GET['new_user'] == true) {
        echo "<p>La demande pour crée un nouveau compte est fait, vous allez recevoire un lien de confirmation, sous peut.</p>";
    }
    
    if (is_user_logged_in()) { ?>
    
        <p>Vous êtes déjà connecté et n&#8216;avez pas besoin de créer de profil utilisateur.</p>
        
    <?php } else {
    
    $username = sanitize_user($_POST['username']);
    $email = sanitize_email($_POST['email']);
    $password = sanitize_text_field($_POST['password']);
    $verifypassword = sanitize_text_field($_POST['verifypassword']);
    
    $firstname = sanitize_text_field($_POST['firstname']);
    $lastname = sanitize_text_field($_POST['lastname']);
    
    $company = sanitize_text_field($_POST['company']);
    $adresse = sanitize_text_field($_POST['adresse']);
    $city = sanitize_text_field($_POST['city']);
    $province = sanitize_text_field($_POST['province']);
    $country = sanitize_text_field($_POST['country']);
    $postal_code = $_POST['postalcode'];
    $phone = $_POST['phone'];
    $poste = $_POST['poste'];
    
    $status = $_POST['status'];
    
    ?>
    
    <div id="page-<?php the_ID(); ?>">    
        <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
             <div class="username">
                <input name="username"
                        id="username"
                        value="<?php echo esc_attr($username) ?>"
                        placeholder="Nom d&#8216;utilisateur">
            </div>
            <div class="email">
                <input name="email"
                        id="email"
                        value="<?php echo esc_attr($email) ?>"
                        placeholder="E-Mail">
            </div>
            <div class="password">
                <input name="password"
                        id="password"
                        value="<?php echo esc_attr($password) ?>"
                        placeholder="Mot de passe"
                        type="password">
            </div>
            <div id="passwordStrength"></div>
            <div class="verifypassword">
                <input name="verifypassword"
                        id="verifypassword"
                        value="<?php echo esc_attr($verifypassword) ?>"
                        placeholder="Verifier le mot de passe"
                        type="password">
            </div>
            <div id="verifyPasswordCheck"></div>
            <div class="firstname">
                <input name="firstname"
                        id="firstname"
                        value="<?php echo esc_attr($firstname) ?>"
                        placeholder="Prenom">
            </div>
            <div class="lastname">
                <input name="lastname"
                        id="lastname"
                        value="<?php echo esc_attr($lastname) ?>"
                        placeholder="Nom de famille">
            </div>
	    <div class="company">
                <input name="company"
                        id="company"
                        value="<?php echo esc_attr($company) ?>"
                        placeholder="Entreprise">
           </div>
            <div class="adresse">
                <input name="adresse"
                        id="adresse"
                        value="<?php echo esc_attr($adresse) ?>"
                        placeholder="Adresse">
           </div>
            <div class="city">
                <input name="city"
                        id="city"
                        value="<?php echo esc_attr($city) ?>"
                        placeholder="Ville">
           </div>
           <div class="province">
                <input name="province"
                        id="province"
                        value="<?php echo esc_attr($province) ?>"
                        placeholder="Province">
           </div>
            <div class="country">
                <input name="country"
                        id="country"
                        value="<?php echo esc_attr($country) ?>"
                        placeholder="Pays">
           </div>
            <div class="postalcode">
                <input name="postalcode"
                        id="postalcode"
                        value="<?php echo esc_attr($postalcode) ?>"
                        placeholder="Code Postal">
           </div>
            <div class="phone">
                <input name="phone"
                        id="phone"
                        value="<?php echo esc_attr($phone) ?>"
                        placeholder="Numero de téléphone">
           </div>
            <div class="poste">
                <input name="poste"
                        id="poste"
                        value="<?php echo esc_attr($poste) ?>"
                        placeholder="Numero du poste">
           </div>
           <?php if($status == 'employeur') {
           	echo '<input type="radio" id="employeur" class="employeur" name="status" value="employeur" checked>';
           } else {
           	echo '<input type="radio" id="employeur" class="employeur" name="status" value="employeur">';
           }
	   echo '<label for="employeur">Employeur</label>';
	   echo '</br>';
	   if($status == 'employer') {
	   	echo '<input type="radio" id="employer" class="employer" name="status" value="employer" checked>';
	   } else { 
	   	echo '<input type="radio" id="employer" class="employer" name="status" value="employer">';
	   } 
	   echo '<label for="employer">Employer</label>';
	   ?>
	   </br>
           <input type="submit" value="Inscription" />
	   <input type="hidden" name="action" value="my_register_action" />
        </form>
    </div>
        
    <?php } ?>
<?php
}
add_shortcode('monemploi-register', 'monemploi_register');
?>