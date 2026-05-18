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
    
$base_url = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 'https' : 'http' ) . '://' .  $_SERVER['HTTP_HOST'];
$url = $base_url . $_SERVER["REQUEST_URI"];

echo '<div class="current-url" style="display: none;">'. $url . '</div>';

echo '<div class="calendartest"></div>';

$url = $_SERVER['REQUEST_URI'];
$queryString = parse_url($url, PHP_URL_QUERY);
parse_str($queryString, $params);

if (implode($params) == ''){
    
$i = 0;
	
$args = array(
	 'post_type' => 'horaire',
	 'author'        =>  -1, 
	 'post_status'    => array('publish'),
	  'orderby'       =>  'date',
	  'order'         =>  'DESC',
	  'posts_per_page' => -1
);
			
$posts = get_posts( $args );

echo '<ul class="horaire-job" style="display: none;">';
foreach($posts as $post) {
	
	$employee_horaire = get_post_meta( $post->ID, 'employee_horaire_key', true );
	$job_horaire = get_post_meta( $post->ID, 'job_horaire_key', true );
	$datepickerstarthoraire = get_post_meta( $post->ID, 'datepickerstarthoraire_key', true );
    $timestarthoraire = get_post_meta( $post->ID, 'timestarthoraire_key', true );
    $datepickerendhoraire = get_post_meta( $post->ID, 'datepickerendhoraire_key', true );
    $timeendhoraire = get_post_meta( $post->ID, 'timeendhoraire_key', true );
    $salaire = get_post_meta( $post->ID, 'salaire_key', true );
	if($employee_horaire == get_current_user_id()){
		echo '<li>'. $employee_horaire . '||' . $job_horaire . '||' . strtotime($datepickerstarthoraire) . '||' . $timestarthoraire . '||' . strtotime($datepickerendhoraire) . '||' . $timeendhoraire . '||' . $salaire . '</li>';
	}
    if($employee_horaire == get_current_user_id()){
	    $horaires[$i] = array($post->ID, $employee_horaire, $job_horaire, $datepickerstarthoraire, $timestarthoraire, $datepickerendhoraire, $timeendhoraire, $salaire);
        $i++;
    }
}
echo '</ul>';
	
?>

	<?php $user_meta = get_userdata(get_current_user_id());
	$user_role = $user_meta->roles[0];
	if($user_role == 'employeur'){ ?>
		<button><a href="<?php echo $url; ?>?new_job=true">Ajouter un horaire</a></button>
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
				<tbody id="calendar_tbody"></tbody>
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
	$employee_horaire = get_post_meta( $post->ID, 'employee_horaire_key', true );
	$job_horaire = get_post_meta( $post->ID, 'job_horaire_key', true );
	$datepickerstarthoraire = get_post_meta( $post->ID, 'datepickerstarthoraire_key', true );
    $timestarthoraire = get_post_meta( $post->ID, 'timestarthoraire_key', true );
    $datepickerendhoraire = get_post_meta( $post->ID, 'datepickerendhoraire_key', true );
    $timeendhoraire = get_post_meta( $post->ID, 'timeendhoraire_key', true );
    $salaire = get_post_meta( $post->ID, 'salaire_key', true );
    if($employee_horaire == get_current_user_id()){
	    $horaires[$i] = array($post->ID, $employee_horaire, $job_horaire, $datepickerstarthoraire, $timestarthoraire, $datepickerendhoraire, $timeendhoraire, $salaire);
        $i++;
    }
}

	$current_time = current_time( 'timestamp' );
	$get_hours = gmdate("H", $current_time);
	echo '<h2>'.gmdate("m/d/Y", $_GET['daytime']).'</h2>';
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
	  <?php if(istoday() && $get_hours == intval("00")){ ?>
		<tr class="hours-shaddow is-today"><td>00:<span class="minutes-update">00</span></td><td>
		
	<?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("00")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("00")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
		
		</td></tr>
	  <?php } else { ?>
	  	<tr><td>00:00</td><td>
	 
	<?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("00")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("00")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>    
	  	</td></tr>
	  <?php } ?>
	  	  <?php if(istoday() && $get_hours == intval("01")){ ?>
		<tr class="hours-shaddow is-today"><td>01:<span class="minutes-update">00</span></td><td>
	<?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("01")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("01")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
		    
		</td></tr>
	  <?php } else { ?>
	  	<tr><td>01:00</td><td>
	  	    
	<?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("01")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("01")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
	  	    
	  	</td></tr>
	  <?php } ?>
	  	  <?php if(istoday() && $get_hours == intval("02")){ ?>
		<tr class="hours-shaddow is-today"><td>02:<span class="minutes-update">00</span></td><td>
		
	<?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("02")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("02")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
		
		</td></tr>
	  <?php } else { ?>
	  	<tr><td>02:00</td><td>
	  	
	  <?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("02")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("02")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
	  	
	  	</td></tr>
	  <?php } ?>
	  	  <?php if(istoday() && $get_hours == intval("03")){ ?>
		<tr class="hours-shaddow is-today"><td>03:<span class="minutes-update">00</span></td><td>
		
	<?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("03")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("03")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
		
		</td></tr>
	  <?php } else { ?>
	  	<tr><td>03:00</td><td>
	  	
	 <?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("03")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("03")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
	  	
	  	</td></tr>
	  <?php } ?>
	  	  <?php if(istoday() && $get_hours == intval("04")){ ?>
		<tr class="hours-shaddow is-today"><td>04:<span class="minutes-update">00</span></td><td>
		
	<?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("04")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("04")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
		
		</td></tr>
	  <?php } else { ?>
	  	<tr><td>04:00</td><td>
	  	
	  <?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("04")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("04")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
	  	
	  	</td></tr>
	  <?php } ?>
	  	  <?php if(istoday() && $get_hours == intval("05")){ ?>
		<tr class="hours-shaddow is-today"><td>05:<span class="minutes-update">00</span></td><td>
		
	<?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("05")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("05")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
		
		</td></tr>
	  <?php } else { ?>
	  	<tr><td>05:00</td><td>
	  	
	  <?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("05")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("05")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
	  	
	  	</td></tr>
	  <?php } ?>
	  	  <?php if(istoday() && $get_hours == intval("06")){ ?>
		<tr class="hours-shaddow is-today"><td>06:<span class="minutes-update">00</span></td><td>
		
	<?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("06")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("06")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
		
		</td></tr>
	  <?php } else { ?>
	  	<tr><td>06:00</td><td>
	  	
	  <?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("06")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("06")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
	  	
	  	</td></tr>
	  <?php } ?>
	  	  <?php if(istoday() && $get_hours == intval("07")){ ?>
		<tr class="hours-shaddow is-today"><td>07:<span class="minutes-update">00</span></td><td>
		
	<?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("07")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("07")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
		
		</td></tr>
	  <?php } else { ?>
	  	<tr><td>07:00</td><td>
	  	
	  <?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("07")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("07")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
	  	
	  	</td></tr>
	  <?php } ?>
	  	  <?php if(istoday() && $get_hours == intval("08")){ ?>
		<tr class="hours-shaddow is-today"><td>08:<span class="minutes-update">00</span></td><td>
		
	<?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("08")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("08")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
		
		</td></tr>
	  <?php } else { ?>
	  	<tr><td>08:00</td><td>
	  	
	  <?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("08")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("08")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
	  	
	  	</td></tr>
	  <?php } ?>
	  	  <?php if(istoday() && $get_hours == intval("09")){ ?>
		<tr class="hours-shaddow is-today"><td>09:<span class="minutes-update">00</span></td><td>
		
	<?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("09")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("09")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
		
		</td></tr>
	  <?php } else { ?>
	  	<tr><td>09:00</td><td>
	  	
	  <?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("09")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("09")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
	  	
	  	</td></tr>
	  <?php } ?>
	  	  <?php if(istoday() && $get_hours == intval("10")){ ?>
		<tr class="hours-shaddow is-today"><td>10:<span class="minutes-update">00</span></td><td>
		    
	<?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("10")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("10")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
		    
		</td></tr>
	  <?php } else { ?>
	  	<tr><td>10:00</td><td>
	  	    
	<?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("10")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("10")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
	  	    
	  	</td></tr>
	  <?php } ?>
	  	  <?php if(istoday() && $get_hours == intval("11")){ ?>
		<tr class="hours-shaddow is-today"><td>11:<span class="minutes-update">00</span></td><td>
		
	<?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("11")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("11")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
		
		</td></tr>
	  <?php } else { ?>
	  	<tr><td>11:00</td><td>
	  	
	  <?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("11")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("11")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
	  	
	  	</td></tr>
	  <?php } ?>
	  	  <?php if(istoday() && $get_hours == intval("12")){ ?>
		<tr class="hours-shaddow is-today"><td>12:<span class="minutes-update">00</span></td><td>
		
	<?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("12")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("12")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
		
		</td></tr>
	  <?php } else { ?>
	  	<tr><td>12:00</td><td>
	  	
	  <?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("12")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("12")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
	  	
	  	</td></tr>
	  <?php } ?>
	  	  <?php if(istoday() && $get_hours == intval("13")){ ?>
		<tr class="hours-shaddow is-today"><td>13:<span class="minutes-update">00</span></td><td>
		
	<?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("13")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("13")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
		
		</td></tr>
	  <?php } else { ?>
	  	<tr><td>13:00</td><td>
	  	
	  <?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("13")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("13")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
	  	
	  	</td></tr>
	  <?php } ?>
	  	  <?php if(istoday() && $get_hours == intval("14")){ ?>
		<tr class="hours-shaddow is-today"><td>14:<span class="minutes-update">00</span></td><td>
		
	<?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("14")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("14")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
		
		</td></tr>
	  <?php } else { ?>
	  	<tr><td>14:00</td><td>
	  	
	  <?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("14")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("14")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
	  	
	  	</td></tr>
	  <?php } ?>
	  	  <?php if(istoday() && $get_hours == intval("15")){ ?>
		<tr class="hours-shaddow is-today"><td>15:<span class="minutes-update">00</span></td><td>
		
	<?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("15")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("15")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
		
		</td></tr>
	  <?php } else { ?>
	  	<tr><td>15:00</td><td>
	  	
	  <?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("15")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("15")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
	  	
	  	</td></tr>
	  <?php } ?>
	  	  <?php if(istoday() && $get_hours == intval("16")){ ?>
		<tr class="hours-shaddow is-today"><td>16:<span class="minutes-update">00</span></td><td>
		
	<?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("16")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("16")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
		
		</td></tr>
	  <?php } else { ?>
	  	<tr><td>16:00</td><td>
	  	
	  <?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("16")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("16")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
	  	
	  	</td></tr>
	  <?php } ?>
	  	  <?php if(istoday() && $get_hours == intval("17")){ ?>
		<tr class="hours-shaddow is-today"><td>17:<span class="minutes-update">00</span></td><td>
		
	<?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("17")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("17")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
		
		</td></tr>
	  <?php } else { ?>
	  	<tr><td>17:00</td><td>
	  	
	  <?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("17")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("17")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
	  	
	  	</td></tr>
	  <?php } ?>
	  	  <?php if(istoday() && $get_hours == intval("18")){ ?>
		<tr class="hours-shaddow is-today"><td>18:<span class="minutes-update">00</span></td><td>
		
	  <?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("18")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("18")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
		
		</td></tr>
	  <?php } else { ?>
	  	<tr><td>18:00</td><td>
	  	
	  <?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("18")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("18")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>	  	
	  	</td></tr>
	  <?php } ?>
	  	  <?php if(istoday() && $get_hours == intval("19")){ ?>
		<tr class="hours-shaddow is-today"><td>19:<span class="minutes-update">00</span></td><td>
		
	<?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("19")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("19")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
		
		</td></tr>
	  <?php } else { ?>
	  	<tr><td>19:00</td><td>
	  	
	  <?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("19")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("19")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
	  	
	  	</td></tr>
	  <?php } ?>
	  	  <?php if(istoday() && $get_hours == intval("20")){ ?>
		<tr class="hours-shaddow is-today"><td>20:<span class="minutes-update">00</span></td><td>
		
	<?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("20")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("20")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
		
		</td></tr>
	  <?php } else { ?>
	  	<tr><td>20:00</td><td>
	  	
	  <?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("20")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("20")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
	  	
	  	</td></tr>
	  <?php } ?>
	  	  <?php if(istoday() && $get_hours == intval("21")){ ?>
		<tr class="hours-shaddow is-today"><td>21:<span class="minutes-update">00</span></td><td>
		
	<?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("21")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("21")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
		
		</td></tr>
	  <?php } else { ?>
	  	<tr><td>21:00</td><td>
	  	
	  <?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("21")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("21")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
	  	
	  	</td></tr>
	  <?php } ?>
	  	  <?php if(istoday() && $get_hours == intval("22")){ ?>
		<tr class="hours-shaddow is-today"><td>22:<span class="minutes-update">00</span></td><td>
		
	<?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("22")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("22")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
		
		</td></tr>
	  <?php } else { ?>
	  	<tr><td>22:00</td><td>
	  	
	  <?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("22")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("22")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
	  	
	  	</td></tr>
	  <?php } ?>
	  	 <?php if(istoday() && $get_hours == intval("23")){ ?>
		<tr class="hours-shaddow is-today"><td>23:<span class="minutes-update">00</span></td><td>
		
	<?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("23")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("23")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
		
		</td></tr>
	  <?php } else { ?>
	  	<tr><td>23:00</td><td>
	  	
	  <?php
		foreach ($horaires as $horaire){
			$arraystart = explode(":", $horaire[4]);
			$arrayend = explode(":", $horaire[6]);
			$getdate = gmdate("m/d/Y", $_GET['daytime']);
		    	if($horaire[3] == $getdate && $arraystart[0] == intval("23")){
		    		$employee_horaire = get_post_meta( $horaire[0], 'employee_horaire_key', true);
		    		$get_user_by_id = get_user_by('ID', $employee_horaire);
		    		$job_horaire = get_post_meta( $horaire[0], 'job_horaire_key', true);
		    		$timestarthoraire = get_post_meta( $horaire[0], 'timestarthoraire_key', true);
		    		$timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
		    		echo '<div id="horaire-start"><a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - ' . $timestarthoraire . '-' . $timeendhoraire . '</div>';
		    	}
		    	if($horaire[5] == $getdate && $arrayend[0] == intval("23")){
		    		echo '<div id="horaire-end">Fin de la journée</div>';
		    	}
		}
	?>
	  	
	  	</td></tr>
	  <?php } ?>
	  </tbody>
	</table>
	<?php

}

if ($_GET['new_job'] == true) {

echo '<form action="'. $_SERVER['REQUEST_URI'] .'" method="post">';
	echo '<h4>Ajouter un horaire.</h4>';
	$my_employees = get_user_meta( get_current_user_id(), 'my_employee_key', true);
	echo '<select name="employee_horaire" id="employee_horaire">';
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
	echo '<select name="job_horaire" id="job_horaire">';
		foreach($get_emplois as $emploi){
			echo '<option value="'. $emploi->ID .'">'. $emploi->post_name .' - ' . get_post_status ( $emploi->ID ) . '</option>';
		}
	echo '</select>';
	
	echo '<br>';
	echo '<br>';
	echo '<span>Début de lhoraire</span>';
	echo '<br>';
	echo '<input type="text" id="datepickerstarthoraire" class="datepickerstarthoraire" name="datepickerstarthoraire" data-toggle="datepickerstarthoraire">';
	echo '<input type="time" id="timestarthoraire" name="timestarthoraire">';
	
	echo '<br>';
	echo '<br>';
	echo '<span>Fin de lhoraire</span>';
	echo '<br>';
	echo '<input type="text" id="datepickerendhoraire" class="datepickerendhoraire" name="datepickerendhoraire" data-toggle="datepickerendhoraire">';
	echo '<input type="time" id="timeendhoraire" name="timeendhoraire">';
	
	echo '<br>';
	echo '<br>';
	echo '<span>Salaire de l&#8216;heure</span>';
	echo '<br>';
	echo '<input type="number" class="salaire" name="salaire" id="salaire">';
	
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