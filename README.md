### MonEmploi

MonEmploi is a job board.

<img style="max-width: 100%;" src="https://i.imgur.com/4Kz2UeT.png" /><br>

## Description

MonEmploi is a job board to make a job searching web site with user dashboard and candidacy private page.<br>

## Installation

1. Upload `monemploi` to the `/wp-content/plugins/` directory.<br>
1. Activate the plugin through the 'Plugins' menu in WordPress.<br>

## Shortcode

[monemploi-job-dashboard]<br>
Is to show jobs dashboard.<br>

[monemploi-resume-from]<br>
Is to show front page media uplaod and show all the document uploded and delete them if needed.<br>

[monemploi-newjob-from]<br>
Is to show from to add new job.<br>

[monemploi-city]<br>
Is to show jobs filter by city.<br>

[monemploi-candidacy-dashboard]<br>
Is to show candidacy dashboard.<br>

[monemploi-user-dashboard]<br>
Is to show user profile info about work, certification and school.<br>

[employee-dashboard]<br>
Is to show employee list.<br>

[employeur-dashboard]<br>
Is to show employeur list.<br>

[monemploi-home]<br>
Is to show statistique.<br>

## Ultimate Member ##

In order to make the registration page you need to install Ultimate Member and here the meta key for the user info.<br>

name_org<br>
first_name<br>
last_name<br>
user_email<br>
Adresse<br>
Province<br>
Pays<br>
Code_postal<br>
number_phone<br>
poste<br>

In the registration from you need to add a radio boutton selection.<br>
In edit choices you need to add "Employer" and "Employeur", with the meta key "Status".<br>

```js
function my_custom_after_registration_action( $user_id, $args ) {
    if ( empty( $user_id ) || is_wp_error( $user_id ) ) {
        return;
    }
    
  $user = new WP_User( $user_id );
	$meta_for_user = get_user_meta( $user_id, 'status', true ); 
	$meta_user_status = $meta_for_user[0];
	if($meta_user_status == 'Employeur'){    
		$user->set_role( 'employeur' );
	}
	if($meta_user_status == 'Employer'){ 
		$user->set_role( 'employer' );
	}
}
add_action( 'um_registration_set_extra_data', 'my_custom_after_registration_action', 10, 2 );
```

## Frequently Asked Questions 

Do you make money with this plugin.<br>
The answer is no.<br>

## Screenshots 

<img style="max-width: 100%;" src="https://i.imgur.com/TzqPF9S.png" /><br>
<img style="max-width: 100%;" src="https://i.imgur.com/0Dq7Grb.png" /><br>
<img style="max-width: 100%;" src="https://i.ibb.co/v4pT5CYZ/monemploi20.png" /><br>

## Changelog 

0.2 - Add the ability to edit the jobs in the front-end with the new job form.<br>
0.1 - Init version with basic function, everything work but there a lack of CSS optimisation.<br>

## Special Thanks 

Special thanks to Jean-Alexandre Cayouette<br>
linkedin.com/in/jean-alexandre-cayouette-163760108?originalSubdomain=ca<br>
