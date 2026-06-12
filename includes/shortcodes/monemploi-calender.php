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

function getSundaysBetweenDates($startDateString, $endDateString) {
    $sundays = [];
    
    $start = new DateTime($startDateString);
    $end = new DateTime($endDateString);
    
    // Ensure the end date is inclusive during the check
    $end->modify('+1 day'); 

    // Move start date to the first Sunday if it isn't already one
    if ($start->format('N') != 7) {
        $start->modify('next sunday');
    }

    // Interval of 1 week (7 days)
    $interval = new DateInterval('P1W');
    $period = new DatePeriod($start, $interval, $end);

    foreach ($period as $date) {
        $sundays[] = $date->format('m/d/Y');
    }

    return $sundays;
}

function monemploi_calender() {
    
$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . strtok($_SERVER['REQUEST_URI'], '?');

echo '<div class="current-url" style="display: none;">'. $current_url . '</div>';

echo '<div class="test"></div>';

$url = $_SERVER['REQUEST_URI'];
$queryString = parse_url($url, PHP_URL_QUERY);
parse_str($queryString, $params);

if (implode($params) == '' || isset($_GET['delete']) || isset($_GET['horaireweek'])){

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

	for($i=1; $i<=1560; $i++){
		$startofweek[$i] = date("m/d/Y", strtotime('+'.$i.' Sunday', strtotime('12/25/2017') ));
	}	
	
	$p = 0;
	foreach($startofweek as $daystartweek){
		$monday = date('m/d/Y', strtotime('sunday'));
		if(strtotime($daystartweek) <= strtotime($monday)){
			$current_week = $p;
		}
		$p++;
	}
	
	$value_week = $current_week;
	if(isset($_GET['horaireweek'])) {
		$value = $_GET['horaireweek'];
		$calc_minus = $_GET['horaireweek'] - 1;
		$calc_plus = $_GET['horaireweek'] + 1; 
	} else {
		$value = $current_week;
		$calc_minus = $current_week - 1;
		$calc_plus = $current_week + 1; 
	}
	
	if(isset($_GET['week'])) {
		echo '<a href="'.$current_url.'?horaireweek='.$calc_minus.'">Précédent</a>';
		echo ' - ';
		echo '<a href="'.$current_url.'?horaireweek='.$current_week.'">Aujourd&#39;hui</a>';
		echo ' - ';
		echo '<a href="'.$current_url.'?horaireweek='.$calc_plus.'">Suivant</a>';
		echo '<br>';
	} else  {
		echo '<a href="'.$current_url.'?horaireweek='.$calc_minus.'">Précédent</a>';
		echo ' - ';
		echo '<a href="'.$current_url.'?horaireweek='.$current_week.'">Aujourd&#39;hui</a>';
		echo ' - ';
		echo '<a href="'.$current_url.'?horaireweek='.$calc_plus.'">Suivant</a>';
		echo '<br>';
	}
	
	if(isset($_GET['horaireweek'])) {
		echo  'Le debut de la semaine de travaille est le <span class="startweekmonday">'.$startofweek[$value].'</span>';
		echo '<br>';
		echo  'La fin de la semaine de travaille est le <span class="endweekmonday">'.$startofweek[$value+1].'</span>';
		echo '<br>';
		
		$user_meta = get_userdata(get_current_user_id());
		$user_role = $user_meta->roles[0];
					
		if($user_role == 'employeur'){
			$args = array(
				 'post_type' => 'horaire',
				 'post_status'    => array('publish'),
				  'orderby'       =>  'date',
				  'author'        =>   get_current_user_id(),
				  'order'         =>  'DESC',
				  'posts_per_page' => -1
			);
		}
		
		if($user_role == 'employer'){
			$args = array(
				 'post_type' => 'horaire',
				 'post_status'    => array('publish'),
				  'orderby'       =>  'date',
				  'order'         =>  'DESC',
				  'posts_per_page' => -1
			);
		}
						
			$posts = get_posts( $args );
			
			
			foreach($posts as $post) {
				$salary = get_post_meta( $post->ID, 'salaire_key', true );
				$push_ = get_post_meta( $post->ID, 'push_key', true );
				$employee_horaire = get_post_meta( $post->ID, 'employee_horaire_key', true );
			        $employee_replace = get_post_meta( $post->ID, 'employee_replace_key', true );
			        $dayoff_status = get_post_meta( $post->ID, 'dayoff_status_key', true );
			        $author_id = $post->post_author;
			        $my_employee = get_user_meta( $author_id, 'my_employee_key', true);
			        $datepickerstarthoraire = get_post_meta( $post->ID, 'datepickerstarthoraire_key', true );
				$timestarthoraire = get_post_meta( $post->ID, 'timestarthoraire_key', true );
				$datepickerendhoraire = get_post_meta( $post->ID, 'datepickerendhoraire_key', true );
				$timeendhoraire = get_post_meta( $post->ID, 'timeendhoraire_key', true );
				$datepickerstartpause = get_post_meta( $post->ID, 'datepickerstartpause_key', true );
				$timestartpause = get_post_meta( $post->ID, 'timestartpause_key', true );
				$datepickerendpause = get_post_meta( $post->ID, 'datepickerendpause_key', true );
				$timeendpause = get_post_meta( $post->ID, 'timeendpause_key', true );
				if(strtotime($startofweek[$value]) <= strtotime($datepickerendhoraire.'T'.$timeendhoraire) && strtotime($startofweek[$value+1]) > strtotime($datepickerstarthoraire.'T'.$timestarthoraire)){
					if($user_role == 'employer'){
						if($employee_horaire == get_current_user_id() || ($employee_replace == get_current_user_id() && $dayoff_status == 3)){
							$horairetimecalc = strtotime($datepickerendhoraire.'T'.$timeendhoraire) - strtotime($datepickerstarthoraire.'T'.$timestarthoraire);
							$horairecalc[] = $horairetimecalc - (strtotime($datepickerendpause.'T'.$timeendpause) - strtotime($datepickerstartpause.'T'.$timestartpause));
							$i = 0;
							$datetimes = [];
							if($push_ == ''){
								$push_ = [];
							}
							foreach($push_ as $push){
								if($push[0] == 'entrer'){
									$datetimes[$i] = $push[1];
								}
								if($push[0] == 'sortie'){
									$datetimes[$i] = $push[1];
								}
								$i++;
							}
							
							$datetimescount = count($datetimes);
							for ($i = 0; $i < $datetimescount; $i++) {
								if ($i % 2 == 0) {
									if($datetimes[$i] != '' && $datetimes[$i+1] != ''){
									    $diffdatetime = $datetimes[$i+1] - $datetimes[$i];
									    $secondswork[] = $diffdatetime;
								    	}
								} else {
									if($datetimes[$i] != '' && $datetimes[$i+1] != ''){
									    $diffdatetime = $datetimes[$i+1] - $datetimes[$i];
									    $secondoff[] = $diffdatetime;
								    	}
								}
							}
						}
					}
					if($user_role == 'employeur'){
						$horairetimecalc = strtotime($datepickerendhoraire.'T'.$timeendhoraire) - strtotime($datepickerstarthoraire.'T'.$timestarthoraire);
						$horairecalc[] = $horairetimecalc - (strtotime($datepickerendpause.'T'.$timeendpause) - strtotime($datepickerstartpause.'T'.$timestartpause));
						$i = 0;
						$datetimes = [];
						if($push_ == ''){
							$push_ = [];
						}
						foreach($push_ as $push){
							if($push[0] == 'entrer'){
								$datetimes[$i] = $push[1];
							}
							if($push[0] == 'sortie'){
								$datetimes[$i] = $push[1];
							}
							$i++;
						}
						
						$datetimescount = count($datetimes);
						    for ($i = 0; $i < $datetimescount; $i++) {
						    	if ($i % 2 == 0) {
								if($datetimes[$i] != '' && $datetimes[$i+1] != ''){
								    $diffdatetime = $datetimes[$i+1] - $datetimes[$i];
								    $secondswork[] = $diffdatetime;
							    	}
							} else {
								if($datetimes[$i] != '' && $datetimes[$i+1] != ''){
								    $diffdatetime = $datetimes[$i+1] - $datetimes[$i];
								    $secondoff[] = $diffdatetime;
							    	}
							}
						    }
	
					}
				
				}

			}
			
			$horairecalcsum = array_sum($horairecalc);
			echo 'Le nombre d&#8216;heure céduler pour cette semaine: ';
                	$seconds = $horairecalcsum;
		        $hours = floor($seconds / 3600);
		        $minutes = floor(($seconds / 60) % 60);
		        $secs = $seconds % 60;
		        echo sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
			echo '<br>';
			$secondsworksum = array_sum($secondswork);
			echo 'Le nombre d&#8216;heure travailler pour cette semaine: ';
                	$seconds = $secondsworksum;
		        $hours = floor($seconds / 3600);
		        $minutes = floor(($seconds / 60) % 60);
		        $secs = $seconds % 60;
		        echo sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
			echo '<br>';
			$secondoffsum = array_sum($secondoff);
			echo 'Le nombre de temps passé en pause pour cette semaine: ';
                	$seconds = $secondoffsum;
		        $hours = floor($seconds / 3600);
		        $minutes = floor(($seconds / 60) % 60);
		        $secs = $seconds % 60;
		        echo sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
			echo '<br>';
		
	} else {
		echo  'Le debut de la semaine de travaille est le <span class="startweekmonday">'.$startofweek[$value].'</span>';
		echo '<br>';
		echo  'La fin de la semaine de travaille est le <span class="endweekmonday">'.$startofweek[$value+1].'</span>';
		echo '<br>';
		
		$user_meta = get_userdata(get_current_user_id());
		$user_role = $user_meta->roles[0];
					
		if($user_role == 'employeur'){
			$args = array(
				 'post_type' => 'horaire',
				 'post_status'    => array('publish'),
				  'orderby'       =>  'date',
				  'author'        =>   get_current_user_id(),
				  'order'         =>  'DESC',
				  'posts_per_page' => -1
			);
		}
		
		if($user_role == 'employer'){
			$args = array(
				 'post_type' => 'horaire',
				 'post_status'    => array('publish'),
				  'orderby'       =>  'date',
				  'order'         =>  'DESC',
				  'posts_per_page' => -1
			);
		}
						
			$posts = get_posts( $args );
			
			
			foreach($posts as $post) {
				$salary = get_post_meta( $post->ID, 'salaire_key', true );
				$push_ = get_post_meta( $post->ID, 'push_key', true );
				$employee_horaire = get_post_meta( $post->ID, 'employee_horaire_key', true );
			        $employee_replace = get_post_meta( $post->ID, 'employee_replace_key', true );
			        $dayoff_status = get_post_meta( $post->ID, 'dayoff_status_key', true );
			        $author_id = $post->post_author;
			        $my_employee = get_user_meta( $author_id, 'my_employee_key', true);
			        $datepickerstarthoraire = get_post_meta( $post->ID, 'datepickerstarthoraire_key', true );
				$timestarthoraire = get_post_meta( $post->ID, 'timestarthoraire_key', true );
				$datepickerendhoraire = get_post_meta( $post->ID, 'datepickerendhoraire_key', true );
				$timeendhoraire = get_post_meta( $post->ID, 'timeendhoraire_key', true );
				$datepickerstartpause = get_post_meta( $post->ID, 'datepickerstartpause_key', true );
				$timestartpause = get_post_meta( $post->ID, 'timestartpause_key', true );
				$datepickerendpause = get_post_meta( $post->ID, 'datepickerendpause_key', true );
				$timeendpause = get_post_meta( $post->ID, 'timeendpause_key', true );
				if(strtotime($startofweek[$value]) <= strtotime($datepickerendhoraire.'T'.$timeendhoraire) && strtotime($startofweek[$value+1]) > strtotime($datepickerstarthoraire.'T'.$timestarthoraire)){
					if($user_role == 'employer'){
						if($employee_horaire == get_current_user_id() || ($employee_replace == get_current_user_id() && $dayoff_status == 3)){
							$horairetimecalc = strtotime($datepickerendhoraire.'T'.$timeendhoraire) - strtotime($datepickerstarthoraire.'T'.$timestarthoraire);
							$horairecalc[] = $horairetimecalc - (strtotime($datepickerendpause.'T'.$timeendpause) - strtotime($datepickerstartpause.'T'.$timestartpause));
							$i = 0;
							$datetimes = [];
							if($push_ == ''){
								$push_ = [];
							}
							foreach($push_ as $push){
								if($push[0] == 'entrer'){
									$datetimes[$i] = $push[1];
								}
								if($push[0] == 'sortie'){
									$datetimes[$i] = $push[1];
								}
								$i++;
							}
							
							$datetimescount = count($datetimes);
							for ($i = 0; $i < $datetimescount; $i++) {
								if ($i % 2 == 0) {
									if($datetimes[$i] != '' && $datetimes[$i+1] != ''){
									    $diffdatetime = $datetimes[$i+1] - $datetimes[$i];
									    $secondswork[] = $diffdatetime;
								    	}
								} else {
									if($datetimes[$i] != '' && $datetimes[$i+1] != ''){
									    $diffdatetime = $datetimes[$i+1] - $datetimes[$i];
									    $secondoff[] = $diffdatetime;
								    	}
								}
							}
						}
					}
					if($user_role == 'employeur'){
						$horairetimecalc = strtotime($datepickerendhoraire.'T'.$timeendhoraire) - strtotime($datepickerstarthoraire.'T'.$timestarthoraire);
						$horairecalc[] = $horairetimecalc - (strtotime($datepickerendpause.'T'.$timeendpause) - strtotime($datepickerstartpause.'T'.$timestartpause));
						$i = 0;
						$datetimes = [];
						if($push_ == ''){
							$push_ = [];
						}
						foreach($push_ as $push){
							if($push[0] == 'entrer'){
								$datetimes[$i] = $push[1];
							}
							if($push[0] == 'sortie'){
								$datetimes[$i] = $push[1];
							}
							$i++;
						}
						
						$datetimescount = count($datetimes);
						    for ($i = 0; $i < $datetimescount; $i++) {
						    	if ($i % 2 == 0) {
								if($datetimes[$i] != '' && $datetimes[$i+1] != ''){
								    $diffdatetime = $datetimes[$i+1] - $datetimes[$i];
								    $secondswork[] = $diffdatetime;
							    	}
							} else {
								if($datetimes[$i] != '' && $datetimes[$i+1] != ''){
								    $diffdatetime = $datetimes[$i+1] - $datetimes[$i];
								    $secondoff[] = $diffdatetime;
							    	}
							}
						    }
	
					}
				
				}

			}
			
			$horairecalcsum = array_sum($horairecalc);
			echo 'Le nombre d&#8216;heure céduler pour cette semaine: ';
                	$seconds = $horairecalcsum;
		        $hours = floor($seconds / 3600);
		        $minutes = floor(($seconds / 60) % 60);
		        $secs = $seconds % 60;
		        echo sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
			echo '<br>';
			$secondsworksum = array_sum($secondswork);
			echo 'Le nombre d&#8216;heure travailler pour cette semaine: ';
                	$seconds = $secondsworksum;
		        $hours = floor($seconds / 3600);
		        $minutes = floor(($seconds / 60) % 60);
		        $secs = $seconds % 60;
		        echo sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
			echo '<br>';
			$secondoffsum = array_sum($secondoff);
			echo 'Le nombre de temps passé en pause pour cette semaine: ';
                	$seconds = $secondoffsum;
		        $hours = floor($seconds / 3600);
		        $minutes = floor(($seconds / 60) % 60);
		        $secs = $seconds % 60;
		        echo sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
			echo '<br>';
		
	}
	
	$user_meta = get_userdata(get_current_user_id());
	$user_role = $user_meta->roles[0];
	if($user_role == 'employeur'){ ?>
		<button><a href="<?php echo $current_url; ?>?new_job=true">Ajouter un horaire</a></button>
	<?php } ?>
	<button><a href="<?php echo $current_url; ?>?summary=true">Somaire des paies</a></button>
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
					    				echo '<div>'.$horaire[0].' - <a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - Pointer de départ: '.$getimepush.'</div>'; 
					    			}
					    			if($push[0] == 'sortie'){
					    				echo '<div>'.$horaire[0].' - <a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - Pointer de fin: '.$getimepush.'</div>'; 
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
						    				echo '<div>'.$horaire[0].' - <a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - Pointer de départ: '.$getimepush.'</div>'; 
						    			}
						    			if($push[0] == 'sortie'){
						    				echo '<div>'.$horaire[0].' - <a href="'.get_permalink($horaire[0]).'">'.get_the_title($job_horaire).'</a> - ' . $get_user_by_id->user_firstname . ' ' . $get_user_by_id->user_lastname . ' - Pointer de fin: '.$getimepush.'</div>'; 
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
			$salary = get_user_meta( $employee, 'salary_key', true);
			echo '<option value="'.$employee.'">'. $user_by_id->user_nicename .' - ' . $user_by_id->user_firstname . ' ' . $user_by_id->user_lastname . ' - ' . $salary . '$</option>';
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
	echo '<span>Debut de pause</span>';
	echo '<br>';
	echo '<input type="text" id="datepickerstartpause" class="datepickerstartpause" name="datepickerstartpause" data-toggle="datepickerstartpause" required>';
	echo '<input type="time" id="timestartpause" name="timestartpause" required>';
	
	echo '<br>';
	echo '<br>';
	echo '<span>Fin de pause</span>';
	echo '<br>';
	echo '<input type="text" id="datepickerendpause" class="datepickerendpause" name="datepickerendpause" data-toggle="datepickerendpause" required>';
	echo '<input type="time" id="timeendpause" name="timeendpause" required>';
	
	echo '<br>';
	echo '<br>';
	echo '<span>Fin de lhoraire</span>';
	echo '<br>';
	echo '<input type="text" id="datepickerendhoraire" class="datepickerendhoraire" name="datepickerendhoraire" data-toggle="datepickerendhoraire" required>';
	echo '<input type="time" id="timeendhoraire" name="timeendhoraire" required>';
	
	echo '<br>';
	echo '<br>';
	echo '<input type="hidden" name="action" value="new_horaire" />';
	echo '<button class="ns_submit" type="submit" name="submit">';
		esc_html_e( 'Ajouter à l&#39;horaire', 'monemploi' );
	echo '</button>';
	
echo '</form>';

}

if ($_GET['summary'] == true) {
    
    echo '<div class="test"></div>';

    echo '<div class="test2"></div>';

	$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . strtok($_SERVER['REQUEST_URI'], '?');
	
	$url = $_SERVER['REQUEST_URI'];

	// Extract the query component (e.g., "name=John&age=30...")
	$queryString = parse_url($url, PHP_URL_QUERY);
	
	// Parse the query string into a resulting array
	parse_str($queryString, $params);

	$i = 0;
	$x = 0;
	$y = 0;
	$a = 0;
	$w = null;
	$salaire = null;
	
	
	for ($m = 1; $m <= 600; $m++) {
	    $startofmonth[$a] = date('m/d/Y', mktime(0, 0, 0, $m, 1, '2018'));
	    $a++;
	}
	
	echo '<ul class="Thursdayofpayroll" style="display: none;">';
	$firstthursday = strtotime("01/09/2018");
	for($i=1; $i<=760; $i++){
		if ($i % 2 == 0) {
            $thursdayloop = strtotime('next Thursday', $firstthursday);
            $firstthursday = $thursdayloop;
            $startofpayrollthursday[$y] = date("m/d/Y", $firstthursday);
            echo '<li>'.$firstthursday.'</li>';
			$y++;
		} else {
            $thursdayloop = strtotime('next Thursday', $firstthursday);
            $firstthursday = $thursdayloop;
		}
	}
	echo '</ul>';
	
	echo '<ul class="startonmonday" style="display: none;">';
	for($i=1; $i<=780; $i++){
		if ($i % 2 == 0) {
			// null;	
		} else {
			$startofworkdayonmonday[$x] = date("m/d/Y", strtotime('+'.$i.' Sunday', strtotime('12/25/2017') ));
			echo '<li>'.strtotime($startofworkdayonmonday[$x]).'</li>';
			$x++;
		}
	}
	echo '</ul>';
	
	for($i=1; $i<=1560; $i++){
		$startofweek[$i] = date("m/d/Y", strtotime('+'.$i.' Sunday', strtotime('12/25/2017') ));
	}	
	
	$p = 0;
	foreach($startofmonth as $month){
		$frist_day_of_month = date('m/d/Y', strtotime('first day of this month'));
		if(strtotime($month) <= strtotime($frist_day_of_month)){
			$currwent_month = $p;
		}
		$p++;
	}
	
	$p = 0;
	foreach($startofworkdayonmonday as $daystartwork){
		$monday = date('m/d/Y', strtotime('sunday'));
		if(strtotime($daystartwork) <= strtotime($monday)){
			$current_biweek = $p;
		}
		$p++;
	}
	$p = 0;
	foreach($startofweek as $daystartweek){
		$monday = date('m/d/Y', strtotime('sunday'));
		if(strtotime($daystartweek) <= strtotime($monday)){
			$current_week = $p;
		}
		$p++;
	}
	
	$value_week = $current_week;
	$value_biweek = $current_biweek;
	$value_month = $currwent_month;
	if(isset($_GET['week'])) {
		$value = $_GET['week'];
		$calc_minus = $_GET['week'] - 1;
		$calc_plus = $_GET['week'] + 1; 
	} elseif(isset($_GET['biweek'])) {
		$value = $_GET['biweek'];
		$calc_minus = $_GET['biweek'] - 1;
		$calc_plus = $_GET['biweek'] + 1; 
	} elseif(isset($_GET['month'])) {
		$value = $_GET['month'];
		$calc_minus = $_GET['month'] - 1;
		$calc_plus = $_GET['month'] + 1; 
	} else {
		$value = $current_biweek;
		$calc_minus = $current_biweek - 1;
		$calc_plus = $current_biweek + 1; 
	}
	
	echo '<a href="'.$current_url.'?summary='. $params[summary] .'&week='.$value_week.'">Semaine</a>';
	echo ' - ';
	echo '<a href="'.$current_url.'?summary='. $params[summary] .'&biweek='.$value_biweek.'">Bi-semaine</a>';
	echo ' - ';
	echo '<a href="'.$current_url.'?summary='. $params[summary] .'&month='.$value_month.'">Mois</a>';
	echo '<br>';
	if(isset($_GET['week'])) {
		echo '<a href="'.$current_url.'?summary='. $params[summary] .'&week='.$calc_minus.'">Précédent</a>';
		echo ' - ';
		echo '<a href="'.$current_url.'?summary='. $params[summary] .'&week='.$current_week.'">Aujourd&#39;hui</a>';
		echo ' - ';
		echo '<a href="'.$current_url.'?summary='. $params[summary] .'&week='.$calc_plus.'">Suivant</a>';
		echo '<br>';
	} elseif(isset($_GET['biweek'])) {
		echo '<a href="'.$current_url.'?summary='. $params[summary] .'&biweek='.$calc_minus.'">Précédent</a>';
		echo ' - ';
		echo '<a href="'.$current_url.'?summary='. $params[summary] .'&biweek='.$current_biweek.'">Aujourd&#39;hui</a>';
		echo ' - ';
		echo '<a href="'.$current_url.'?summary='. $params[summary] .'&biweek='.$calc_plus.'">Suivant</a>';
		echo '<br>';
	} elseif(isset($_GET['month'])) {
		echo '<a href="'.$current_url.'?summary='. $params[summary] .'&month='.$calc_minus.'">Précédent</a>';
		echo ' - ';
		echo '<a href="'.$current_url.'?summary='. $params[summary] .'&month='.$currwent_month.'">Aujourd&#39;hui</a>';
		echo ' - ';
		echo '<a href="'.$current_url.'?summary='. $params[summary] .'&month='.$calc_plus.'">Suivant</a>';
		echo '<br>';
	} else  {
		echo '<a href="'.$current_url.'?summary='. $params[summary] .'&biweek='.$calc_minus.'">Précédent</a>';
		echo ' - ';
		echo '<a href="'.$current_url.'?summary='. $params[summary] .'&biweek='.$current_biweek.'">Aujourd&#39;hui</a>';
		echo ' - ';
		echo '<a href="'.$current_url.'?summary='. $params[summary] .'&biweek='.$calc_plus.'">Suivant</a>';
		echo '<br>';
	}
	
	if(isset($_GET['week'])) {
		echo  'Le debut de la semaine de travaille est le <span class="startweekmonday">'.$startofweek[$value].'</span>';
		echo '<br>';
		echo  'La fin de la semaine de travaille est le <span class="endweekmonday">'.$startofweek[$value+1].'</span>';
		echo '<br>';		
	} else if(isset($_GET['biweek'])) {
		echo  'Le debut du deux semaine de travaille est le <span class="startbiweekmonday">'.$startofworkdayonmonday[$value].'</span>';
		echo '<br>';
		echo  'La fin du deux semaine de travaille est le <span class="endbiweekmonday">'.$startofworkdayonmonday[$value+1].'</span>';
		echo '<br>';
		echo 'Le prochain jour de paie est le <span class="biweekpayday">'.$startofpayrollthursday[$value].'</span>';
		echo '<br>';
	} else if(isset($_GET['month'])) {
		echo 'Le debut du mois est le <span class="startofmonth">'.$startofmonth[$value].'</span>';
		echo '<br>';
		echo 'La fin du mois est le <span class="endofmonth">'.date('m/d/Y', strtotime($startofmonth[$value+1])).'</span>';
		echo '<br>';
		$startofthemonth = strtotime($startofmonth[$value]);
		$endofthemonth = strtotime(date('m/d/Y', strtotime('-1 day', strtotime($startofmonth[$value+1]))));
		foreach($startofpayrollthursday as $payday){
			if($startofthemonth <= strtotime($payday) && $endofthemonth >= strtotime($payday)){
				$paydayinmonth[] = $payday;
			}
		}	
		echo 'Les jours de paye dans le mois sont ';
		foreach($paydayinmonth as $paydaymonth){
			echo '<span class="paydayinmonth">'.$paydaymonth.'</span> ';
		}
		echo '<br>';
	} else {
		echo  'Le debut du deux semaine de travaille est le <span class="startbiweekmonday">'.$startofworkdayonmonday[$value].'</span>';
		echo '<br>';
		echo  'La fin du deux semaine de travaille est le <span class="endbiweekmonday">'.$startofworkdayonmonday[$value+1].'</span>';
		echo '<br>';
		echo 'Le prochain jour de paie est le <span class="biweekpayday">'.$startofpayrollthursday[$value].'</span>';
		echo '<br>';
	}
	
	
	$n = 0;
	$l = 0;
	
    $user_meta = get_userdata(get_current_user_id());
	$user_role = $user_meta->roles[0];
	
	
if($user_role == 'employeur'){
	$args = array(
		 'post_type' => 'horaire',
		 'post_status'    => array('publish'),
		  'author'        =>   get_current_user_id(),
		  'orderby'       =>  'modified',
		  'order'         =>  'ASC',
		  'posts_per_page' => -1
	);
}

if($user_role == 'employer'){
	$args = array(
		 'post_type' => 'horaire',
		 'post_status'    => array('publish'),
		  'orderby'       =>  'modified',
		  'order'         =>  'ASC',
		  'posts_per_page' => -1
	);
}
				
	$posts = get_posts( $args );
	
	foreach($posts as $post) {
		$salary = get_post_meta( $post->ID, 'salaire_key', true );
		$push_ = get_post_meta( $post->ID, 'push_key', true );
		$employee_horaire = get_post_meta( $post->ID, 'employee_horaire_key', true );
	        $employee_replace = get_post_meta( $post->ID, 'employee_replace_key', true );
	        $dayoff_status = get_post_meta( $post->ID, 'dayoff_status_key', true );
	        $author_id = $post->post_author;
	        $my_employee = get_user_meta( $author_id, 'my_employee_key', true);
		foreach($push_ as $punch) {
			if($user_role === 'employer'){
				if($employee_horaire === get_current_user_id() || ($employee_replace == get_current_user_id() && $dayoff_status === 3)){
    					    if($punch[0] === 'entrer'){
    							if($employee_replace === get_current_user_id() && $dayoff_status === 3){
    								$datetimestarts[$n]['userid'] = $employee_replace;
    							} else {
    								$datetimestarts[$n]['userid'] = $employee_horaire;
    							}
    							$datetimestarts[$n]['time'] = $punch[1];
    							$datetimestarts[$n]['salary'] = $salary;
    							$n++;
    						}
    						if($punch[0] === 'sortie'){
    							$datetimeends[$l] = $punch[1];
    							 $l++;
    						}
					}
				
				}
				if($user_role === 'employeur'){
					foreach($my_employee as $employee){
					
				
							if($punch[0] === 'entrer'){
								if($employee_replace === $employee && $dayoff_status === 3){
									$datetimestarts[$n]['userid'] = $employee_replace;
									$datetimestarts[$n]['time'] = $punch[1];
									$datetimestarts[$n]['salary'] = $salary;
									$n++;
								} else if ($employee_horaire === $employee) {
									$datetimestarts[$n]['userid'] = $employee_horaire;
									$datetimestarts[$n]['time'] = $punch[1];
									$datetimestarts[$n]['salary'] = $salary;
									$n++;
								}
							}
							if($punch[0] === 'sortie'){
								if($employee_replace === $employee && $dayoff_status === 3){
									$datetimeends[$l] = $punch[1];
									 $l++;
								} else if ($employee_horaire === $employee) {
									$datetimeends[$l] = $punch[1];
									 $l++;
								}
							}
						}
					
					}
			}
		}
		
	$t = 0;
	$z = 0;
	$n = 0;
	
	usort($datetimestarts, function ($a, $b) {
		return $a[$n]['time'] <=> $b[$n]['time'];
		$n++;
	});
	
	usort($datetimeends, function ($a, $b) {
		return $a['time'] <=> $b['time'];
	});
	
	foreach($datetimestarts as $datetimestart){
		if(isset($_GET['week'])) {
			if($datetimestart['time'] >= strtotime($startofweek[$value]) && $datetimeends[$z] < strtotime($startofweek[$value+1])) {
				$timepays[$z]['start'] = $datetimestart['time'];
				$timepays[$z]['end'] = $datetimeends[$z];
				$timepays[$z]['time'] = $datetimeends[$z] - $datetimestart['time'];
				$timepays[$z]['salary'] = $datetimestart['salary'];
			} 
		}else if(isset($_GET['month'])) {
			if($datetimestart['time'] >= strtotime($startofmonth[$value]) && $datetimeends[$z] < strtotime($startofmonth[$value+1])) {
				$timepays[$z]['start'] = $datetimestart['time'];
				$timepays[$z]['end'] = $datetimeends[$z];
				$timepays[$z]['time'] = $datetimeends[$z] - $datetimestart['time'];
				$timepays[$z]['salary'] = $datetimestart['salary'];
			} 
		}else if(isset($_GET['biweek'])) {
			if($datetimestart['time'] >= strtotime($startofworkdayonmonday[$value]) && $datetimeends[$z] < strtotime($startofworkdayonmonday[$value+1])) {
				$timepays[$z]['start'] = $datetimestart['time'];
				$timepays[$z]['end'] = $datetimeends[$z];
				$timepays[$z]['time'] = $datetimeends[$z] - $datetimestart['time'];
				$timepays[$z]['salary'] = $datetimestart['salary'];
			} 
		} else  {
			if($datetimestart['time'] >= strtotime($startofworkdayonmonday[$value]) && $datetimeends[$z] < strtotime($startofworkdayonmonday[$value+1])) {
				$timepays[$z]['start'] = $datetimestart['time'];
				$timepays[$z]['end'] = $datetimeends[$z];
				$timepays[$z]['time'] = $datetimeends[$z] - $datetimestart['time'];
				$timepays[$z]['salary'] = $datetimestart['salary'];
			} 
		}
		$z++;
	}
	
	if(isset($_GET['week'])) {
		
		$timepaysum = 0;
		foreach ($timepays as $key => $timevalue) {
			if($timevalue['start'] >= strtotime($startofweek[$value]) && $timevalue['end'] < strtotime($startofweek[$value+1])) {
				$timepaysum = $timepaysum + $timevalue['time'];
				
				if($timepaysum > 144000) {
					$vartimepaysum[$i] = $i;
				}
				
				$salary = $timevalue['salary'];
				$salary_ = ($salary/60);
				
				if($timepaysum > 144000) {
					$timepaynormale = 144000;
					$hours = floor($timepaynormale / 3600);
					$minutes = floor(($timepaynormale / 60) % 60);
					$worktime = ($hours * 60) + $minutes;
					$timepaynormalepay = $worktime * $salary_;
					$timepaysup = $timepaysum - 144000;
					$hours = floor($timepaysup / 3600);
					$minutes = floor(($timepaysup / 60) % 60);
					$worktimesup = ($hours * 60) + $minutes;
					$timepaysuppay = $worktimesup * ($salary_ * 1.5);
					$paytime = $timepaynormalepay + $timepaysuppay;
				} else 	{	
					$timepaysuppay = 0;
					$timepaynormale = $timepaysum;
					$hours = floor($timepaynormale / 3600);
					$minutes = floor(($timepaynormale / 60) % 60);
					$worktime = ($hours * 60) + $minutes;
					$timepaynormalepay = $worktime * $salary_;
					$paytime = $timepaynormalepay;
				}
				
			}
		}
		
	} else if(isset($_GET['month'])) {
	
		$startofmonthminus = strtotime($startofmonth[$value]) - 604800;
		$startofmonthplus = strtotime($startofmonth[$value+1]) + 604800;
	
		$start = date('m/d/Y', $startofmonthminus);
		$end = date('m/d/Y', $startofmonthplus);
		
		$allSundays = getSundaysBetweenDates($start, $end);
	
		$x = 0;
		foreach($allSundays as $allSunday) {
			$timepaysum[$x] = 0;
			foreach ($timepays as $key => $timevalue) {
				if($timevalue['start'] >= strtotime($allSunday) && $timevalue['end'] < strtotime($allSundays[$x+1])) {
					$timepaysum[$x] = $timepaysum[$x] + $timevalue['time'];
								
					if($timepaysum[$x] > 144000) {
						$vartimepaysum[$x][$i] = $i;
					}
					
					$salary = $timevalue['salary'];
					$salary_ = ($salary/60);
					
					if($timepaysum[$x] > 144000) {
						$timepaynormale[$x] = 144000;
						$hours[$x] = floor($timepaynormale[$x] / 3600);
						$minutes[$x] = floor(($timepaynormale[$x] / 60) % 60);
						$worktime[$x] = ($hours[$x] * 60) + $minutes[$x];
						$timepaynormalepay[$x] = $worktime[$x] * $salary_;
					    	$timepaysup[$x] = $timepaysum[$x] - 144000;
						$hours[$x] = floor($timepaysup[$x] / 3600);
						$minutes[$x] = floor(($timepaysup[$x] / 60) % 60);
						$worktimesup[$x] = ($hours[$x] * 60) + $minutes[$x];
						$timepaysuppay[$x] = $worktimesup[$x] * ($salary_ * 1.5);
						$paytime[$x] = $timepaynormalepay[$x] + $timepaysuppay[$x];
					} else {
						$timepaysuppay[$x] = 0;
						$timepaynormale[$x] = $timepaysum[$x];
						$hours[$x] = floor($timepaynormale[$x] / 3600);
						$minutes[$x] = floor(($timepaynormale[$x] / 60) % 60);
						$worktime[$x] = ($hours[$x] * 60) + $minutes[$x];
						$timepaynormalepay[$x] = $worktime[$x] * $salary_;
						$paytime[$x] = $timepaynormalepay[$x];
					}
				}
			}
			$x++;		
		}

	} else if(isset($_GET['biweek'])) {
	
		$start = $startofworkdayonmonday[$value];
		$end = $startofworkdayonmonday[$value+1];
		
		$allSundays = getSundaysBetweenDates($start, $end);
		
		$timepaysumone = 0;
		$timepaysumtow = 0;	
		foreach ($timepays as $key => $timevalue) {
			if($timevalue['start'] >= strtotime($allSundays[0]) && $timevalue['end'] < strtotime($allSundays[1])) {

				$timepaysumone = $timepaysumone + $timevalue['time'];
			
				if($timepaysumone > 144000) {
					$vartimepaysumone[$i] = $i;
				}
				
				$salary = $timevalue['salary'];
				$salary_ = ($salary/60);
				
				if($timepaysumone > 144000) {
					$timepaynormale = 144000;
					$hours = floor($timepaynormale / 3600);
					$minutes = floor(($timepaynormale / 60) % 60);
					$worktime = ($hours * 60) + $minutes;
					$timepaynormalepayone = $worktime * $salary_;
					$timepaysup = $timepaysumone - 144000;
					$hours = floor($timepaysup / 3600);
					$minutes = floor(($timepaysup / 60) % 60);
					$worktimesup = ($hours * 60) + $minutes;
					$timepaysuppayone = $worktimesup * ($salary_ * 1.5);
					$paytimeone = $timepaynormalepayone + $timepaysuppayone;
				} else {
					$timepaysuppayone = 0;
					$timepaynormale = $timepaysumone;
					$hours = floor($timepaynormale / 3600);
					$minutes = floor(($timepaynormale / 60) % 60);
					$worktime = ($hours * 60) + $minutes;
					$timepaynormalepayone = $worktime * $salary_;
					$paytimeone = $timepaynormalepayone;
				}
									
			} 

			if($timevalue['start'] >= strtotime($allSundays[1]) && $timevalue['end'] < strtotime($allSundays[2])) {
							
				$timepaysumtow = $timepaysumtow + $timevalue['time'];
				
				if($timepaysumtow > 144000) {
					$vartimepaysumtow[$i] = $i;
				}
				
				$salary = $timevalue['salary'];
				$salary_ = ($salary/60);
								
				if($timepaysumtow > 144000) {
					$timepaynormale = 144000;
					$hours = floor($timepaynormale / 3600);
					$minutes = floor(($timepaynormale / 60) % 60);
					$worktime = ($hours * 60) + $minutes;
					$timepaynormalepaytow = $worktime * $salary_;
					$timepaysup = $timepaysumtow - 144000;
					$hours = floor($timepaysup / 3600);
					$minutes = floor(($timepaysup / 60) % 60);
					$worktimesup = ($hours * 60) + $minutes;
					$timepaysuppaytow = $worktimesup * ($salary_ * 1.5);
					$paytimetow = $timepaynormalepaytow + $timepaysuppaytow;
				} else {
					$timepaysuppaytow = 0;
					$timepaynormale = $timepaysumtow;
					$hours = floor($timepaynormale / 3600);
					$minutes = floor(($timepaynormale / 60) % 60);
					$worktime = ($hours * 60) + $minutes;
					$timepaynormalepaytow = $worktime * $salary_;
					$paytimetow = $timepaynormalepaytow;
				}
			}
		
		}
				
	} else {
	
		$start = $startofworkdayonmonday[$value];
		$end = $startofworkdayonmonday[$value+1];
		
		$allSundays = getSundaysBetweenDates($start, $end);
		
		$timepaysumone = 0;
		$timepaysumtow = 0;
		foreach ($timepays as $key => $timevalue) {
			if($timevalue['start'] >= strtotime($allSundays[0]) && $timevalue['end'] < strtotime($allSundays[1])) {

				$timepaysumone = $timepaysumone + $timevalue['time'];
				
				if($timepaysumone > 144000) {
					$vartimepaysumone[$i] = $i;
				}
				
				$salary = $timevalue['salary'];
				$salary_ = ($salary/60);
				
				if($timepaysumone > 144000) {
					$timepaynormale = 144000;
					$hours = floor($timepaynormale / 3600);
					$minutes = floor(($timepaynormale / 60) % 60);
					$worktime = ($hours * 60) + $minutes;
					$timepaynormalepayone = $worktime * $salary_;
					$timepaysup = $timepaysumone - 144000;
					$hours = floor($timepaysup / 3600);
					$minutes = floor(($timepaysup / 60) % 60);
					$worktimesup = ($hours * 60) + $minutes;
					$timepaysuppayone = $worktimesup * ($salary_ * 1.5);
					$paytimeone = $timepaynormalepayone + $timepaysuppayone;
				} else {
					$timepaysuppayone = 0;
					$timepaynormale = $timepaysumone;
					$hours = floor($timepaynormale / 3600);
					$minutes = floor(($timepaynormale / 60) % 60);
					$worktime = ($hours * 60) + $minutes;
					$timepaynormalepayone = $worktime * $salary_;
					$paytimeone = $timepaynormalepayone;
				}
									
			} 

			if($timevalue['start'] >= strtotime($allSundays[1]) && $timevalue['end'] < strtotime($allSundays[2])) {
							
				$timepaysumtow = $timepaysumtow + $timevalue['time'];
				
				if($timepaysumtow > 144000) {
					$vartimepaysumtow[$i] = $i;
				}
				
				$salary = $timevalue['salary'];
				$salary_ = ($salary/60);
								
				if($timepaysumtow > 144000) {
					$timepaynormale = 144000;
					$hours = floor($timepaynormale / 3600);
					$minutes = floor(($timepaynormale / 60) % 60);
					$worktime = ($hours * 60) + $minutes;
					$timepaynormalepaytow = $worktime * $salary_;
					$timepaysup = $timepaysumtow - 144000;
					$hours = floor($timepaysup / 3600);
					$minutes = floor(($timepaysup / 60) % 60);
					$worktimesup = ($hours * 60) + $minutes;
					$timepaysuppaytow = $worktimesup * ($salary_ * 1.5);
					$paytimetow = $timepaynormalepaytow + $timepaysuppaytow;
				} else {
					$timepaysuppaytow = 0;
					$timepaynormale = $timepaysumtow;
					$hours = floor($timepaynormale / 3600);
					$minutes = floor(($timepaynormale / 60) % 60);
					$worktime = ($hours * 60) + $minutes;
					$timepaynormalepaytow = $worktime * $salary_;
					$paytimetow = $timepaynormalepaytow;
				}
			}
		
		}
	}
	
	$i = 0;		
	foreach($datetimestarts as $datetimestart){
	
		$salaryi_ = $datetimestart['salary'];
		
		sort($vartimepaysum);
		sort($vartimepaysumone);
		sort($vartimepaysumtow);
		
		$timepays_[$i] = $datetimeends[$i] - $datetimestart['time'];
		
		if(isset($_GET['week'])) {
			foreach ($vartimepaysum[0] as $vartimesum){
				if($vartimesum == $i){
			        	$hours_ = floor($timepays_[$i] / 3600);
					$minutes_ = floor(($timepays_[$i] / 60) % 60);
					$worktime_ = ($hours_ * 60) + $minutes_;
					$salary__ = ($salaryi_/60) * 1.5;
					$pay_ = $worktime_ * $salary__;
					$i = null;
				}
			}
			
			if($i != null){
			    	$hours_ = floor($timepays_[$i] / 3600);
				$minutes_ = floor(($timepays_[$i] / 60) % 60);
				$worktime_ = ($hours_ * 60) + $minutes_;
				$salary__ = $salaryi_/60;
				$pay_ = $worktime_ * $salary__;
			}
		} else if(isset($_GET['month'])) {
		
			foreach ($vartimepaysum as $vartimepaysum_){
				foreach ($vartimepaysum_[0] as $vartimesum){
					if($vartimesum == $i){
				        	$hours_ = floor($timepays_[$i] / 3600);
						$minutes_ = floor(($timepays_[$i] / 60) % 60);
						$worktime_ = ($hours_ * 60) + $minutes_;
						$salary__ = ($salaryi_/60) * 1.5;
						$pay_ = $worktime_ * $salary__;
						$i = null;
					}
				}
			}
					
			if($i != null){
			    	$hours_ = floor($timepays_[$i] / 3600);
				$minutes_ = floor(($timepays_[$i] / 60) % 60);
				$worktime_ = ($hours_ * 60) + $minutes_;
				$salary__ = $salaryi_/60;
				$pay_ = $worktime_ * $salary__;
			}
		
		} else if(isset($_GET['biweek'])) {
		
			foreach ($vartimepaysumone[0] as $vartimesum){
				if($vartimesum == $i){
			        	$hours_ = floor($timepays_[$i] / 3600);
					$minutes_ = floor(($timepays_[$i] / 60) % 60);
					$worktime_ = ($hours_ * 60) + $minutes_;
					$salary__ = ($salaryi_/60) * 1.5;
					$pay_ = $worktime_ * $salary__;
					$i = null;
				}
			}
			
			foreach ($vartimepaysumtow[0] as $vartimesum){
				if($vartimesum == $i){
			        	$hours_ = floor($timepays_[$i] / 3600);
					$minutes_ = floor(($timepays_[$i] / 60) % 60);
					$worktime_ = ($hours_ * 60) + $minutes_;
					$salary__ = ($salaryi_/60) * 1.5;
					$pay_ = $worktime_ * $salary__;
					$i = null;
				}
			}
			
			if($i != null){
			    	$hours_ = floor($timepays_[$i] / 3600);
				$minutes_ = floor(($timepays_[$i] / 60) % 60);
				$worktime_ = ($hours_ * 60) + $minutes_;
				$salary__ = $salaryi_/60;
				$pay_ = $worktime_ * $salary__;
			}
		
		} else {
		
			foreach ($vartimepaysumone[0] as $vartimesum){
				if($vartimesum == $i){
			        	$hours_ = floor($timepays_[$i] / 3600);
					$minutes_ = floor(($timepays_[$i] / 60) % 60);
					$worktime_ = ($hours_ * 60) + $minutes_;
					$salary__ = ($salaryi_/60) * 1.5;
					$pay_ = $worktime_ * $salary__;
					$i = null;
				}
			}
			
			foreach ($vartimepaysumtow[0] as $vartimesum){
				if($vartimesum == $i){
			        	$hours_ = floor($timepays_[$i] / 3600);
					$minutes_ = floor(($timepays_[$i] / 60) % 60);
					$worktime_ = ($hours_ * 60) + $minutes_;
					$salary__ = ($salaryi_/60) * 1.5;
					$pay_ = $worktime_ * $salary__;
					$i = null;
				}
			}
			
			if($i != null){
			    	$hours_ = floor($timepays_[$i] / 3600);
				$minutes_ = floor(($timepays_[$i] / 60) % 60);
				$worktime_ = ($hours_ * 60) + $minutes_;
				$salary__ = $salaryi_/60;
				$pay_ = $worktime_ * $salary__;
			}
		
		}
	
		$payloop = 0;
		$x = 0;
		for($w=1; $w<=780; $w++){
			if ($w % 2 == 0) {
				// null;
			} else {
				$startofworkdayonmonday[$x] = date("m/d/Y", strtotime('+'.$w.' Monday', strtotime('01/01/2018') ));
				if($datetimestart['time'] >= strtotime($startofworkdayonmonday[$x]) && $datetimeends[$i] < strtotime($startofworkdayonmonday[$x+2])){
					$payloop = $x;
				}
				$x++;
			}
		}
	
		$mypaycalender[$i] = array(date("m/d/Y", $datetimestart['time']), $datetimeends[$i], round($pay_, 2), $datetimestart['userid'], $startofpayrollthursday[$payloop]);
		$i++;
		
	}
        
    	sort($mypaycalender);
        
    	echo '<ul class="gettimepay" style="display: none;">';
    
	        foreach ($mypaycalender as $mypaycalender_){
	
	        	echo '<li>'.$mypaycalender_[0].'||'.$mypaycalender_[1].'||'.$mypaycalender_[2].'||'.$mypaycalender_[3].'||'.$mypaycalender_[4].'</li>';
	        
	        }
	
	echo '</ul>';
	
	     if(isset($_GET['week'])) {
	
	        echo 'Votre montant brute pour le temps regulier pour cette semaine: ';
	    	echo round($timepaynormalepay, 2).'$';
	    	echo '<br>';
	    	echo 'Votre montant brute pour le temps suplementaire: ';
	    	echo round($timepaysuppay, 2).'$';
	    	echo '<br>';
	    	echo 'Votre montant brute pour le total de la semaie: ';
	    	echo round($paytime, 2).'$';
	    	echo '<br>';
	        
	    } else if(isset($_GET['month'])) {
	        $x = 0;
	        $i = 1;
	        foreach($timepaynormalepay as $timepaynormalepay_){
		        echo 'Votre montant brute pour le temps regulier pour la semaine numero '.$i.': ';
		    	echo round($timepaynormalepay_, 2).'$';
		    	echo '<br>';
		    	echo 'Votre montant brute pour le temps suplementaire pour la semaine numero '.$i.': ';
		    	echo round($timepaysuppay[$x], 2).'$';
		    	echo '<br>';
		    	echo 'Votre montant brute pour le total de la semaie numero '.$i.': ';
		    	echo round($paytime[$x], 2).'$';
		    	echo '<br>';
		    	$x++;
		    	$i++;
	    	}
	    	echo 'Votre montant total pour le mois avec le temps supplémentaire: ';
	    	$paytimesum = array_sum($paytime);
	    	echo round($paytimesum, 2).'$';
	        
	    } else if(isset($_GET['biweek'])) {
	        
	        echo 'Votre montant brute pour le temps regulier pour la premiere semaine: ';
	    	echo round($timepaynormalepayone, 2).'$';
	    	echo '<br>';
	    	echo 'Votre montant brute pour le temps suplementaire de la premiere semaine: ';
	    	echo round($timepaysuppayone, 2).'$';
	    	echo '<br>';
	    	echo 'Votre montant brute pour le total de la premiere semaie: ';
	    	echo round($paytimeone, 2).'$';
	    	echo '<br>';
	    	echo 'Votre montant brute pour le temps regulier pour la seconde semaine: ';
	    	echo round($timepaynormalepaytow, 2).'$';
	    	echo '<br>';
	    	echo 'Votre montant brute pour le temps suplementaire de la seconde semaine: ';
	    	echo round($timepaysuppaytow, 2).'$';
	    	echo '<br>';
	    	echo 'Votre montant brute pour le total de la seconde semaie: ';
	    	echo round($paytimetow, 2).'$';
	    	echo '<br>';
	    	echo 'Votre montant brute pour les deux semaines: ';
	    	$paytimefull = $paytimeone + $paytimetow;
	    	echo round($paytimefull, 2).'$';
	    	echo '<br>';
	        
	    } else  {
	        
	        echo 'Votre montant brute pour le temps regulier pour la premiere semaine: ';
	    	echo round($timepaynormalepayone, 2).'$';
	    	echo '<br>';
	    	echo 'Votre montant brute pour le temps suplementaire de la premiere semaine: ';
	    	echo round($timepaysuppayone, 2).'$';
	    	echo '<br>';
	    	echo 'Votre montant brute pour le total de la premiere semaie: ';
	    	echo round($paytimeone, 2).'$';
	    	echo '<br>';
	    	echo 'Votre montant brute pour le temps regulier pour la seconde semaine: ';
	    	echo round($timepaynormalepaytow, 2).'$';
	    	echo '<br>';
	    	echo 'Votre montant brute pour le temps suplementaire de la seconde semaine: ';
	    	echo round($timepaysuppaytow, 2).'$';
	    	echo '<br>';
	    	echo 'Votre montant brute pour le total de la seconde semaie: ';
	    	echo round($paytimetow, 2).'$';
	    	echo '<br>';
	    	echo 'Votre montant brute pour les deux semaines: ';
	    	$paytimefull = $paytimeone + $paytimetow;
	    	echo round($paytimefull, 2).'$';
	    	echo '<br>';
	    }	
    
        ?><div class="container">
			<div class="calendar_header">
				<i class="prev-month-pay fa fa-chevron-left fa-3x"></i>
				<i class="next-month-pay fa fa-chevron-right fa-3x"></i>
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
				<tbody class="calendarpaieroll_tbody"></tbody>
			</table>
		</div>
	    
<?php
}

}

add_shortcode('monemploi-calender', 'monemploi_calender');

?>