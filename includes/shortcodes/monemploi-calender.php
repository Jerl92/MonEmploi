<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function istoday(){

	$current_time = current_time( 'timestamp' );
	$today = gmdate("Y-m-d", $current_time);
	$argtime = gmdate("Y-m-d", $_GET['daytime']);
	
	if($today == $argtime){
		return true;
	} else {
		return false;
	}
}

function monemploi_calender() {
    
$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . strtok($_SERVER['REQUEST_URI'], '?');

echo '<div class="current-url" style="display: none;">'. $current_url . '</div>';

$url = $_SERVER['REQUEST_URI'];
$queryString = parse_url($url, PHP_URL_QUERY);
parse_str($queryString, $params);

if (implode($params) == '' || isset($_GET['delete'])){

if (isset($_GET['delete'])) {
	echo '<h3>L&#39;horaire #'.$_GET['delete'].' à bien été supprimer</h3>';
}
    
$i = 0;
	
$args = array(
	 'post_type' => 'horaire',
	 'post_status'    => array('publish'),
	  'orderby'       =>  'date',
	  'order'         =>  'DESC',
	  'posts_per_page' => -1
);
			
$posts = get_posts( $args );

echo '<ul class="horaire-job" style="display: none;">';
foreach($posts as $post) {
	$user_meta = get_userdata(get_current_user_id());
	$user_role = $user_meta->roles[0];
	
	$author_id = $post->post_author;
	$my_employee = get_user_meta( $author_id, 'my_employee_key', true);
	
	$employee_horaire = get_post_meta( $post->ID, 'employee_horaire_key', true );
	$job_horaire = get_post_meta( $post->ID, 'job_horaire_key', true );
	$datepickerstarthoraire = get_post_meta( $post->ID, 'datepickerstarthoraire_key', true );
	$timestarthoraire = get_post_meta( $post->ID, 'timestarthoraire_key', true );
	$datepickerendhoraire = get_post_meta( $post->ID, 'datepickerendhoraire_key', true );
	$timeendhoraire = get_post_meta( $post->ID, 'timeendhoraire_key', true );
	$salaire = get_post_meta( $post->ID, 'salaire_key', true );
	$push_ = get_post_meta( $post->ID, 'push_key', true );
	$employee_replace = get_post_meta( $post->ID, 'employee_replace_key', true );
	$dayoff_status = get_post_meta( $post->ID, 'dayoff_status_key', true );
	if($user_role == 'employer'){
	        if($employee_replace != '' && $dayoff_status == 3){
            		$employee_horaire = 0;
            	}
		if($employee_horaire == get_current_user_id() || ($employee_replace == get_current_user_id() && $dayoff_status == 3 )){
			echo '<li style="display: none;">'. $employee_horaire . '||' . $job_horaire . '||' . strtotime($datepickerstarthoraire) . '||' . $timestarthoraire . '||' . strtotime($datepickerendhoraire) . '||' . $timeendhoraire . '||' . $salaire . '</li>';
			$horaires[$i] = array($post->ID, $employee_horaire, $job_horaire, $datepickerstarthoraire, $timestarthoraire, $datepickerendhoraire, $timeendhoraire, $salaire, $push_, $employee_replace, $dayoff_status);
			$i++;
		}
	}
	if($user_role == 'employeur'){
		if (in_array($employee_horaire, $my_employee)) {
			echo '<li style="display: none;">'. $employee_horaire . '||' . $job_horaire . '||' . strtotime($datepickerstarthoraire) . '||' . $timestarthoraire . '||' . strtotime($datepickerendhoraire) . '||' . $timeendhoraire . '||' . $salaire . '</li>';
			$horaires[$i] = array($post->ID, $employee_horaire, $job_horaire, $datepickerstarthoraire, $timestarthoraire, $datepickerendhoraire, $timeendhoraire, $salaire, $push_, $employee_replace, $dayoff_status);
			$i++;
		}
	}
}
echo '</ul>';
	
	$user_meta = get_userdata(get_current_user_id());
	$user_role = $user_meta->roles[0];
	if($user_role == 'employeur'){ ?>
		<button><a href="<?php echo $current_url; ?>?new_job=true">Ajouter un horaire</a></button>
	<?php } ?>
	<div class="container">
			<div class="calendar_header">
				<i class="prev-month fa fa-chevron-left fa-3x"></i>
				<i class="next-month fa fa-chevron-right fa-3x"></i>
				<div class="month-year text-center"></div>
			</div>
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>D</th>
						<th>L</th>
						<th>M</th>
						<th>M</th>
						<th>J</th>
						<th>V</th>
						<th>S</th>
					</tr>
				</thead>
				<tbody class="calendar_tbody"></tbody>
			</table>
		</div>
	    
	<?php
} 

if (isset($_GET['daytime'])) {

$i = 0;
$args = array(
	 'post_type' => 'horaire',
	 'post_status'    => array('publish'),
	  'orderby'       =>  'date',
	  'order'         =>  'DESC',
	  'posts_per_page' => -1
);
			
$posts = get_posts( $args );

foreach($posts as $post) {
	$user_meta = get_userdata(get_current_user_id());
	$user_role = $user_meta->roles[0];
	
	$author_id = $post->post_author;
	$my_employee = get_user_meta( $author_id, 'my_employee_key', true);
	
	$employee_horaire = get_post_meta( $post->ID, 'employee_horaire_key', true );
	$job_horaire = get_post_meta( $post->ID, 'job_horaire_key', true );
	$datepickerstarthoraire = get_post_meta( $post->ID, 'datepickerstarthoraire_key', true );
	$timestarthoraire = get_post_meta( $post->ID, 'timestarthoraire_key', true );
	$datepickerendhoraire = get_post_meta( $post->ID, 'datepickerendhoraire_key', true );
	$timeendhoraire = get_post_meta( $post->ID, 'timeendhoraire_key', true );
	$salaire = get_post_meta( $post->ID, 'salaire_key', true );
	$push_ = get_post_meta( $post->ID, 'push_key', true );
	$employee_replace = get_post_meta( $post->ID, 'employee_replace_key', true );
	$dayoff_status = get_post_meta( $post->ID, 'dayoff_status_key', true );
	if($user_role == 'employer'){
	        if($employee_replace != '' && $dayoff_status == 3){
            		$employee_horaire = 0;
            	}
		if($employee_horaire == get_current_user_id() || ($employee_replace == get_current_user_id() && $dayoff_status == 3 )){
			echo '<li style="display: none;">'. $employee_horaire . '||' . $job_horaire . '||' . strtotime($datepickerstarthoraire) . '||' . $timestarthoraire . '||' . strtotime($datepickerendhoraire) . '||' . $timeendhoraire . '||' . $salaire . '</li>';
			$horaires[$i] = array($post->ID, $employee_horaire, $job_horaire, $datepickerstarthoraire, $timestarthoraire, $datepickerendhoraire, $timeendhoraire, $salaire, $push_, $employee_replace, $dayoff_status);
			$i++;
		}
	}
	if($user_role == 'employeur'){
		if (in_array($employee_horaire, $my_employee)) {
			echo '<li style="display: none;">'. $employee_horaire . '||' . $job_horaire . '||' . strtotime($datepickerstarthoraire) . '||' . $timestarthoraire . '||' . strtotime($datepickerendhoraire) . '||' . $timeendhoraire . '||' . $salaire . '</li>';
			$horaires[$i] = array($post->ID, $employee_horaire, $job_horaire, $datepickerstarthoraire, $timestarthoraire, $datepickerendhoraire, $timeendhoraire, $salaire, $push_, $employee_replace, $dayoff_status);
			$i++;
		}
	}
}

	$current_time = current_time( 'timestamp' );
	$get_hours = gmdate("H", $current_time);
	$timestamp = $_GET['daytime'];
	$daysFr = [
	    'Dimanche', 'Lundi', 'Mardi', 'Mercredi', 
	    'Jeudi', 'Vendredi', 'Samedi'
	];
	$dayName = $daysFr[date('w', $timestamp)];
	$yesterday_timestamp = strtotime(gmdate("m/d/Y", $_GET['daytime']) . " -1 day");
	$towmorrow_timestamp = strtotime(gmdate("m/d/Y", $_GET['daytime']) . " +1 day");
	$today_timestamp = strtotime(gmdate("m/d/Y", $current_time));
	echo '<div style="text-align: center;">';
	echo '<a href="'. $current_url .'?daytime='. $today_timestamp .'">Aujourd&#39;hui</a>';
	echo '</div>';
	echo '<div style="display: flex; justify-content: space-between; width: 100%;">';
	echo '<a href="'. $current_url .'?daytime='. $yesterday_timestamp .'" style="margin-right: auto;">Précédent</a>';
	echo '<h2 style="text-align: center;">'.gmdate("m/d/Y", $_GET['daytime']).' - ' . $dayName . '</h2>';
	echo '<a href="'. $current_url .'?daytime='. $towmorrow_timestamp .'" style="margin-left: auto;">Suivant</a>';
	echo '</div>';
	?>
	<div class="test1"></div>
	<table border="1">
	  <thead>
	    <tr>
	      <th>Heure</th>
	      <th>Evenement</th>
	    </tr>
	  </thead>
	  <tbody>
	  <?php
		for ($i = 0; $i <= 23; $i++) {
			if(istoday() && $get_hours == intval($i)){
				?><tr class="hours-shaddow is-today"><td><?php echo $i; ?>:<span class="minutes-update">00</span></td><td><?php
				
					foreach ($horaires as $horaire){
						$arraystart = explode(":", $horaire[4]);
						$arrayend = explode(":", $horaire[6]);
						$getdate = gmdate("m/d/Y", $_GET['daytime']);
						
						if($horaire[9] != '' && $horaire[10] == 3){ 
							$employee_replace = get_post_meta( $horaire[0], 'employee_replace_key', true);
					    		$get_user_by_id = get_user_by('ID', $employee_replace);
					    	} else { 
					    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
					    		$get_user_by_id = get_user_by('ID', $employee_horaire);
					    	} 
					    	$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
					    	if($horaire[3] == $getdate && $arraystart[0] == intval("00")){
					    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
					    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
					    		echo '<div id="horaire-start">'. $horaire[0] .' - <a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
					    	}
					    	if($horaire[5] == $getdate && $arrayend[0] == intval($i)){
					    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
					    		echo '<div id="horaire-end">'. $horaire[0] .' - Fin de la journée - ' . $timeendhoraire .'</div>';
					    	}
					    	foreach ($horaire[8] as $push){
					    		$getdatepush = gmdate("m/d/Y", $push[1]);
					    		$gethourspush = gmdate("H", $push[1]);
					    		$getimepush = gmdate("H:i:s", $push[1]);
					    		if($getdatepush == $getdate && $gethourspush == intval($i)){
					    			if($push[0] == 'entrer'){
					    				echo '<div><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - Pointer de départ: '.$getimepush.'</div>'; 
					    			}
					    			if($push[0] == 'sortie'){
					    				echo '<div><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - Pointer de fin: '.$getimepush.'</div>'; 
					    			}
					    		}
			    			}
					}
				
				?></td></tr>
				<?php } else { ?>
	  			<tr><td><?php echo $i; ?>:00</td><td><?php
	  			
	  					foreach ($horaires as $horaire){
							$arraystart = explode(":", $horaire[4]);
							$arrayend = explode(":", $horaire[6]);
							$getdate = gmdate("m/d/Y", $_GET['daytime']);
							
							if($horaire[9] != '' && $horaire[10] == 3){ 
								$employee_replace = get_post_meta( $horaire[0], 'employee_replace_key', true);
						    		$get_user_by_id = get_user_by('ID', $employee_replace);
						    	} else { 
						    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
						    		$get_user_by_id = get_user_by('ID', $employee_horaire);
						    	} 
						    	$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
						    	if($horaire[3] == $getdate && $arraystart[0] == intval($i)){
						    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
						    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
						    		echo '<div id="horaire-start">'. $horaire[0] .' - <a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
						    	}
						    	if($horaire[5] == $getdate && $arrayend[0] == intval($i)){
						    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
						    		echo '<div id="horaire-end">'. $horaire[0] .' - Fin de la journée - ' . $timeendhoraire .'</div>';
						    	}
						    	foreach ($horaire[8] as $push){
						    		$getdatepush = gmdate("m/d/Y", $push[1]);
						    		$gethourspush = gmdate("H", $push[1]);
						    		$getimepush = gmdate("H:i:s", $push[1]);
						    		if($getdatepush == $getdate && $gethourspush == intval($i)){
						    			if($push[0] == 'entrer'){
						    				echo '<div><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - Pointer de départ: '.$getimepush.'</div>'; 
						    			}
						    			if($push[0] == 'sortie'){
						    				echo '<div><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - Pointer de fin: '.$getimepush.'</div>'; 
						    			}
						    		}
						    	}
						}
	  		?></td></tr>
	  	<?php } ?>
	<?php } ?>
	  </tbody>
	</table>
	<?php

}

if ($_GET['new_job'] == true) {

echo '<form action="'. $_SERVER['REQUEST_URI'] .'" method="post">';
	echo '<h4>Ajouter un horaire.</h4>';
	$my_employees = get_user_meta( get_current_user_id(), 'my_employee_key', true);
	echo '<select name="employee_horaire" id="employee_horaire" required>';
	echo '<option value="">Sélectionner un employé</option>';
		foreach($my_employees as $employee){
			$user_by_id = get_user_by('id', $employee);
			echo '<option value="'.$employee.'">'. $user_by_id->user_nicename .' - ' . $user_by_id->user_firstname . ' ' . $user_by_id->user_lastname . '</option>';
		}
	echo '</select>';
	
	echo '<br>';
	echo '<br>';
	
	$get_args_emploi = array( 
		'post_type' => 'emploi',
		'posts_per_page' => -1,
		'post_status' => array('publish', 'draft', 'future'),
		'author'        => get_current_user_id(),
		'orderby' => 'date',
		'order' => 'DESC'
	); 
	
	$get_emplois = get_posts( $get_args_emploi );
	echo '<select name="job_horaire" id="job_horaire" required>';
	echo '<option value="">Sélectionner un poste</option>';
		foreach($get_emplois as $emploi){
			echo '<option value="'. $emploi->ID .'">'. $emploi->post_name .' - ' . get_post_status ( $emploi->ID ) . '</option>';
		}
	echo '</select>';
	
	echo '<br>';
	echo '<br>';
	echo '<span>Début de lhoraire</span>';
	echo '<br>';
	echo '<input type="text" id="datepickerstarthoraire" class="datepickerstarthoraire" name="datepickerstarthoraire" data-toggle="datepickerstarthoraire" required>';
	echo '<input type="time" id="timestarthoraire" name="timestarthoraire" required>';
	
	echo '<br>';
	echo '<br>';
	echo '<span>Fin de lhoraire</span>';
	echo '<br>';
	echo '<input type="text" id="datepickerendhoraire" class="datepickerendhoraire" name="datepickerendhoraire" data-toggle="datepickerendhoraire" required>';
	echo '<input type="time" id="timeendhoraire" name="timeendhoraire" required>';
	
	echo '<br>';
	echo '<br>';
	echo '<span>Temps de pause</span>';
	echo '<br>';
	echo '<input type="number" class="timebrake" name="timebrake" id="timebrake" required>';
	
	echo '<br>';
	echo '<br>';
	echo '<span>Salaire de l&#8216;heure</span>';
	echo '<br>';
	echo '<input type="number" class="salaire" name="salaire" id="salaire" step=".01" required>';
	
	echo '<br>';
	echo '<br>';
	echo '<input type="hidden" name="action" value="new_horaire" />';
	echo '<button class="ns_submit" type="submit" name="submit">';
		esc_html_e( 'Ajouter à l&#39;horaire', 'monemploi' );
	echo '</button>';
	
echo '</form>';

}

}

add_shortcode('monemploi-calender', 'monemploi_calender');

?>