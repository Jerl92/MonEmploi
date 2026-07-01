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
        $employee_horaire_select = get_user_meta(get_current_user_id(), 'employee_horaire_select', true);
        $jobs_horaire_select = get_user_meta(get_current_user_id(), 'jobs_horaire_select', true);

                if($user_role == 'employer'){
                if($employee_replace != '' && $dayoff_status == 3){
                            $employee_horaire = 0;
                    }
                if(($employee_horaire == get_current_user_id() || ($employee_replace == get_current_user_id() && $dayoff_status == 3 )) && $employee_horaire_select == -1 && $jobs_horaire_select == -1){
                        echo '<li style="display: none;">'. $employee_horaire . '||' . $job_horaire . '||' . strtotime($datepickerstarthoraire) . '||' . $timestarthoraire . '||' . strtotime($datepickerendhoraire) . '||' . $timeendhoraire . '||' . $salaire . '</li>';
                        $horaires[$i] = array($post->ID, $employee_horaire, $job_horaire, $datepickerstarthoraire, $timestarthoraire, $datepickerendhoraire, $timeendhoraire, $salaire, $push_, $employee_replace, $dayoff_status);
                        $i++;
                }
        }
        if($user_role == 'employeur'){
		 if(($employee_horaire_select == 0 || $employee_horaire_select == '') && ($jobs_horaire_select == 0 || $jobs_horaire_select == '')){

	                    echo '<li style="display: none;">'. $employee_horaire . '||' . $job_horaire . '||' . strtotime($datepickerstarthoraire) . '||' . $timestarthoraire . '||' . strtotime($datepickerendhoraire) . '||' . $timeendhoraire . '||' . $salaire . '</li>';
	                    $horaires[$i] = array($post->ID, $employee_horaire, $job_horaire, $datepickerstarthoraire, $timestarthoraire, $datepickerendhoraire, $timeendhoraire, $salaire, $push_, $employee_replace, $dayoff_status);
	                    $i++;
	                     	  	               
	           }
	           
	           if(($employee_horaire_select == 0 || $employee_horaire_select == '') && ($jobs_horaire_select != 0 || $jobs_horaire_select != -1)){
	           
			if(intval($jobs_horaire_select) == intval($job_horaire)){

				echo '<li style="display: none;">'. $employee_horaire . '||' . $job_horaire . '||' . strtotime($datepickerstarthoraire) . '||' . $timestarthoraire . '||' . strtotime($datepickerendhoraire) . '||' . $timeendhoraire . '||' . $salaire . '</li>';
				$horaires[$i] = array($post->ID, $employee_horaire, $job_horaire, $datepickerstarthoraire, $timestarthoraire, $datepickerendhoraire, $timeendhoraire, $salaire, $push_, $employee_replace, $dayoff_status);
				$i++;
				
			}

                    }
                    
                    if(($employee_horaire_select != 0 || $employee_horaire_select != -1) && ($jobs_horaire_select == 0 || $jobs_horaire_select == '')){
	           
			if(intval($employee_horaire_select) == intval($employee_horaire)){

				echo '<li style="display: none;">'. $employee_horaire . '||' . $job_horaire . '||' . strtotime($datepickerstarthoraire) . '||' . $timestarthoraire . '||' . strtotime($datepickerendhoraire) . '||' . $timeendhoraire . '||' . $salaire . '</li>';
				$horaires[$i] = array($post->ID, $employee_horaire, $job_horaire, $datepickerstarthoraire, $timestarthoraire, $datepickerendhoraire, $timeendhoraire, $salaire, $push_, $employee_replace, $dayoff_status);
				$i++;
				
			}

                    }
                    
                    if(($employee_horaire_select != 0 || $employee_horaire_select != -1) && ($jobs_horaire_select != 0 || $jobs_horaire_select != -1)){
	           
			if(intval($employee_horaire_select) == intval($employee_horaire) && (intval($jobs_horaire_select) == intval($job_horaire))){

				echo '<li style="display: none;">'. $employee_horaire . '||' . $job_horaire . '||' . strtotime($datepickerstarthoraire) . '||' . $timestarthoraire . '||' . strtotime($datepickerendhoraire) . '||' . $timeendhoraire . '||' . $salaire . '</li>';
				$horaires[$i] = array($post->ID, $employee_horaire, $job_horaire, $datepickerstarthoraire, $timestarthoraire, $datepickerendhoraire, $timeendhoraire, $salaire, $push_, $employee_replace, $dayoff_status);
				$i++;
				
			}

                    }
                  
		}
		
}

echo '</ul>';

	echo '<div style="display: float; height: 50px">';
        if($user_role == 'employeur'){
	        $get_args_emploi = array( 
	                'post_type' => 'emploi',
	                'posts_per_page' => -1,
	                'post_status' => array('publish', 'draft', 'future'),
	                'author'        => get_current_user_id(),
	                'orderby' => 'date',
	                'order' => 'DESC'
	        ); 
	
	        $get_emplois = get_posts( $get_args_emploi );
	        echo '<select name="jobs_horaire_select" id="jobs_horaire_select" class="jobs_horaire_select" style="float: left;">';
	        echo '<option value="0">Tous les emplois</option>';
	                foreach($get_emplois as $emploi){
	                    	if(intval($jobs_horaire_select) === intval($emploi->ID)){
	                        	echo '<option value="'. $emploi->ID .'" selected>'. $emploi->post_name .' - ' . get_post_status ( $emploi->ID ) . '</option>';
	                        } else {
	                        	echo '<option value="'. $emploi->ID .'">'. $emploi->post_name .' - ' . get_post_status ( $emploi->ID ) . '</option>';
	                        }
	                }
	        echo '</select>';
        }

        if($user_role == 'employeur'){
                echo '<select name="employee_horaire_select" id="employee_horaire_select" class="employee_horaire_select" style="float: right;">';
                    echo '<option value="0">Tous les employers</option>';
                        foreach($my_employee as $employee){
                                $user_by_id = get_user_by('id', $employee);
                                $salary = get_user_meta( $employee, 'salary_key', true);
                                if($employee_horaire_select === $employee){
                                    echo '<option value="'.$employee.'" selected>'. $user_by_id->user_nicename .' - ' . $user_by_id->user_firstname . ' ' . $user_by_id->user_lastname . ' - ' . $salary . '$</option>';
                                } else {
                                    echo '<option value="'.$employee.'">'. $user_by_id->user_nicename .' - ' . $user_by_id->user_firstname . ' ' . $user_by_id->user_lastname . ' - ' . $salary . '$</option>';
                                }
                        }
                echo '</select>';
        }
        echo '</div>';

        for($i=1; $i <= 1560; $i++){
                $startofweek[$i] = date("m/d/Y", strtotime('+'.$i.' Sunday', strtotime('12/25/2017') ));
        }        

        $current_time = current_time( 'timestamp' );

    $thissunday = date("m/d/Y", strtotime('this sunday', $current_time));

        $p = 0;
        foreach($startofweek as $daystartweek){
                if($daystartweek == $thissunday){
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
                                $employee_horaire_select = get_user_meta(get_current_user_id(), 'employee_horaire_select', true);
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
                                                if($employee_horaire_select == $employee_horaire){
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
                                                } else if($employee_horaire_select == 0) {
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
                                $employee_horaire_select = get_user_meta(get_current_user_id(), 'employee_horaire_select', true);
                                $jobs_horaire_select = get_user_meta(get_current_user_id(), 'jobs_horaire_select', true);
                                if(strtotime($startofweek[$value]) <= strtotime($datepickerendhoraire.'T'.$timeendhoraire) && strtotime($startofweek[$value+1]) > strtotime($datepickerstarthoraire.'T'.$timestarthoraire)){
                                        if($user_role == 'employer'){
                                                if(($employee_horaire == get_current_user_id() || ($employee_replace == get_current_user_id() && $dayoff_status == 3)) || $employee_horaire_select == $employee_horaire){
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
                                                if($employee_horaire_select == $employee_horaire){
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
                                                } else if($employee_horaire_select == 0) {
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
        <?php if($user_role == 'employeur'){ ?>
                <button style="float: right;"><a href="<?php echo $current_url; ?>?dayoff=true">Demande de congé</a></button>
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
        $coworker_see_unsee = get_user_meta(get_current_user_id(), 'coworker_see_unsee', true);
        $employee_horaire_select = get_user_meta(get_current_user_id(), 'employee_horaire_select', true);
        $jobs_horaire_select = get_user_meta(get_current_user_id(), 'jobs_horaire_select', true);
        if($user_role == 'employer'){
                if($employee_replace != '' && $dayoff_status == 3){
                            $employee_horaire = 0;
                    }
                if(($employee_horaire == get_current_user_id() || ($employee_replace == get_current_user_id() && $dayoff_status == 3 )) && $employee_horaire_select == -1 && $coworker_see_unsee == 'false'){
                        echo '<li style="display: none;">'. $employee_horaire . '||' . $job_horaire . '||' . strtotime($datepickerstarthoraire) . '||' . $timestarthoraire . '||' . strtotime($datepickerendhoraire) . '||' . $timeendhoraire . '||' . $salaire . '</li>';
                        $horaires[$i] = array($post->ID, $employee_horaire, $job_horaire, $datepickerstarthoraire, $timestarthoraire, $datepickerendhoraire, $timeendhoraire, $salaire, $push_, $employee_replace, $dayoff_status);
                        $i++;
                }
        }
        if($user_role == 'employeur'){
                if($employee_horaire_select == 0 || $employee_horaire_select == ''){
                        if (in_array($employee_horaire, $my_employee)) {
                            echo '<li style="display: none;">'. $employee_horaire . '||' . $job_horaire . '||' . strtotime($datepickerstarthoraire) . '||' . $timestarthoraire . '||' . strtotime($datepickerendhoraire) . '||' . $timeendhoraire . '||' . $salaire . '</li>';
                                $horaires[$i] = array($post->ID, $employee_horaire, $job_horaire, $datepickerstarthoraire, $timestarthoraire, $datepickerendhoraire, $timeendhoraire, $salaire, $push_, $employee_replace, $dayoff_status);
                                $i++;
                        }
                    }
                    if($employee_horaire_select != 0 && $employee_horaire_select != -1){
                            if($employee_horaire_select == $employee_horaire){
                            if (in_array($employee_horaire, $my_employee)) {
                                            echo '<li style="display: none;">'. $employee_horaire . '||' . $job_horaire . '||' . strtotime($datepickerstarthoraire) . '||' . $timestarthoraire . '||' . strtotime($datepickerendhoraire) . '||' . $timeendhoraire . '||' . $salaire . '</li>';
                                            $horaires[$i] = array($post->ID, $employee_horaire, $job_horaire, $datepickerstarthoraire, $timestarthoraire, $datepickerendhoraire, $timeendhoraire, $salaire, $push_, $employee_replace, $dayoff_status);
                                            $i++;
                                    }
                            } 
                    }

            }
}

echo '</ul>';

        if($user_role == 'employeur'){
                echo '<select name="employee_horaire_select" id="employee_horaire_select" class="employee_horaire_select" style="float: right;">';
                    echo '<option value="0">Tous les employés</option>';
                        foreach($my_employee as $employee){
                                $user_by_id = get_user_by('id', $employee);
                                $salary = get_user_meta( $employee, 'salary_key', true);
                                if($employee_horaire_select === $employee){
                                    echo '<option value="'.$employee.'" selected>'. $user_by_id->user_nicename .' - ' . $user_by_id->user_firstname . ' ' . $user_by_id->user_lastname . ' - ' . $salary . '$</option>';
                                } else {
                                    echo '<option value="'.$employee.'">'. $user_by_id->user_nicename .' - ' . $user_by_id->user_firstname . ' ' . $user_by_id->user_lastname . ' - ' . $salary . '$</option>';
                                }
                        }
                echo '</select>';
                echo '<br>';
                echo '<br>';
        }

        if($user_role == 'employer'){
                if($coworker_see_unsee == 'false' || $coworker_see_unsee == ''){
                        echo '<input type="checkbox" id="coworker" name="coworker" value="">';
                } else {
                        echo '<input type="checkbox" id="coworker" name="coworker" value="" checked>';
                }
                echo '<label for="subscribe">Voire l&#39;horaire des collègues</label>';
                echo '<br>';
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
                                          $x = 0;
                                          for ($i = 1; $i <= 23; $i++) {
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
                                                                    $ifhoraires[$x][$i]['start'] = $i;
                                                            }
                                                            if($horaire[5] == $getdate && $arrayend[0] == intval($i)){
                                                                    $timeendhoraire = get_post_meta( $horaire[0], 'timeendhoraire_key', true);
                                                                    $ifhoraires[$x][$i]['end'] = $i;
                                                            }
                                                            $w = 0;
                                                            foreach ($horaire[8] as $push){
                                                                    $getdatepush = gmdate("m/d/Y", $push[1]);
                                                                    $gethourspush = gmdate("H", $push[1]);
                                                                    $getimepush = gmdate("H:i:s", $push[1]);
                                                                    if($getdatepush == $getdate && $gethourspush == intval($i)){
                                                                        $ifhoraires[$x][$i]['punch'] = $i;
                                                                    }
                                                                    $w++;
                                                            }
                                                }

                                        }
                                $x++;          

                for ($i = 1; $i <= 23; $i++) {
                        foreach ($ifhoraires as $ifhoraire){
                        if(intval($ifhoraire[$i]['start']) === intval($i) || intval($ifhoraire[$i]['end']) === intval($i) || intval($ifhoraire[$i]['punch']) === intval($i)) {
                        if(istoday() && intval($get_hours) === intval($i)){
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
                  <?php } ?>
            <?php } ?>
          </tbody>
        </table>
        <?php

}

if (isset($_GET['dayoff'])) {

    $args = array(
             'post_type' => 'horaire',
             'post_status'    => array('publish'),
              'orderby'       =>  'date',
              'order'         =>  'DESC',
              'posts_per_page' => -1
    );

    $posts = get_posts( $args );

    $user_meta = get_userdata(get_current_user_id());
        $user_role = $user_meta->roles[0];

        if($user_role == 'employeur'){

        foreach($posts as $post) {
            $dayoff_status = get_post_meta( $post->ID, 'dayoff_status_key', true );
            $dayoff_reason = get_post_meta( $post->ID, 'dayoff_reason_key', true );
            $employee_replace = get_post_meta( $post->ID, 'employee_replace_key', true );
            $employee_horaire = get_post_meta( $post->ID, 'employee_horaire_key', true );

           if($dayoff_reason != ''){
                echo '<a href="'.get_permalink($post->ID).'?dayoff=true">'.$post->ID.'</a>';
                echo '<br>';
                $getuserbyid = get_user_by('id', $employee_horaire);
                echo $getuserbyid->user_login;
                echo ' - ';
                echo $getuserbyid->user_firstname;
                echo ' ';
                echo $getuserbyid->user_lastname;
                echo '<br>';
                if($dayoff_reason == 1){
                        echo 'Congés annuels (Vacances)';
                }
                if($dayoff_reason == 2){
                        echo 'Congés de maladie ou médicaux';
                }
                if($dayoff_reason == 3){
                        echo 'Congés parentaux';
                }
                if($dayoff_reason == 4){
                        echo 'Congés familiaux ou de deuil';
                }
                if($dayoff_reason == 5){
                        echo 'Congés sans solde';
                }
                if($dayoff_reason == 6){
                        echo 'Faire une demande de remplacement';
                }
                echo '<br>';
            }
            if($dayoff_status != ''){
                if($dayoff_status  == 1){
                    echo 'En revue';
                }
                if($dayoff_status  == 2){
                    echo 'Refusé';
                }
                if($dayoff_status  == 3){
                    echo 'Accepté';
                }
                if($dayoff_status  == 4){
                    echo 'Manque d&#8216;information';
                }
                echo '<br>';
           }
           if($dayoff_reason != ''){
                   echo '<br>';
           }
        }
    }

}

if (isset($_GET['new_job'])) {

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

                $author_id = $post->post_author;
                $my_employee = get_user_meta( $author_id, 'my_employee_key', true);                

        }
        
        $employee_horaire_select = get_user_meta(get_current_user_id(), 'employee_horaire_select', true);
        $jobs_horaire_select = get_user_meta(get_current_user_id(), 'jobs_horaire_select', true);
       
        echo '<div style="display: float; height: 50px">';
        if($user_role == 'employeur'){
	        $get_args_emploi = array( 
	                'post_type' => 'emploi',
	                'posts_per_page' => -1,
	                'post_status' => array('publish', 'draft', 'future'),
	                'author'        => get_current_user_id(),
	                'orderby' => 'date',
	                'order' => 'DESC'
	        ); 
	
	        $get_emplois = get_posts( $get_args_emploi );
	        echo '<select name="jobs_summary_select" id="jobs_summary_select" class="jobs_summary_select" style="float: left;">';
	        echo '<option value="0">Tous les emplois</option>';
	                foreach($get_emplois as $emploi){
	                	if(intval($jobs_horaire_select) === intval($emploi->ID)){
	                        	echo '<option value="'. $emploi->ID .'" selected>'. $emploi->post_name .' - ' . get_post_status ( $emploi->ID ) . '</option>';
	                        } else {
	                        	echo '<option value="'. $emploi->ID .'">'. $emploi->post_name .' - ' . get_post_status ( $emploi->ID ) . '</option>';
	                        }
	                }
	        echo '</select>';
        }

        if($user_role == 'employeur'){
                echo '<select name="employee_horaire_select" id="employee_horaire_select" class="employee_horaire_select" style="float: right;">';
                    echo '<option value="0">Tous les employés</option>';
                        foreach($my_employee as $employee){
                                $user_by_id = get_user_by('id', $employee);
                                $salary = get_user_meta( $employee, 'salary_key', true);
                                if($employee_horaire_select === $employee){
                                    echo '<option value="'.$employee.'" selected>'. $user_by_id->user_nicename .' - ' . $user_by_id->user_firstname . ' ' . $user_by_id->user_lastname . ' - ' . $salary . '$</option>';
                                } else {
                                    echo '<option value="'.$employee.'">'. $user_by_id->user_nicename .' - ' . $user_by_id->user_firstname . ' ' . $user_by_id->user_lastname . ' - ' . $salary . '$</option>';
                                }
                        }
                echo '</select>';
        }
        echo '</div>';


        for ($m = 0; $m <= 600; $m++) {
            $startofmonth[$a] = date('m/d/Y', mktime(0, 0, 0, $m, 1, '2018'));
            $a++;
        }

$firstthursday = strtotime("01/01/2018");
for($i=0; $i<=1560; $i++){
        if ($i % 2 == 0) {
            $thursdayloop = strtotime('next Thursday', $firstthursday);
            $firstthursday = $thursdayloop;
        } else {
            $thursdayloop = strtotime('next Thursday', $firstthursday);
            $firstthursday = $thursdayloop;
            $startofpayrollthursday[$y] = date("m/d/Y", $firstthursday);
            $y++;
        }
}

for($i=0; $i<=1560; $i++){
        if ($i % 2 == 0) {
                // null;        
        } else {
                $startofworkdayonmonday[$x] = date("m/d/Y", strtotime('+'.$i.' Sunday', strtotime('01/01/2018') ));
                $x++;
        }
}

        for($i=0; $i<=1560; $i++){
                $startofweek[$i] = date("m/d/Y", strtotime('+'.$i.' Sunday', strtotime('01/01/2018') ));
        }        

        $p = 0;
        foreach($startofmonth as $month){
                $frist_day_of_month = date('m/d/Y', strtotime('first day of this month'));
                if(strtotime($month) <= strtotime($frist_day_of_month)){
                        $currwent_month = $p;
                }
                $p++;
        }

        $current_time = current_time( 'timestamp' );

    $thissunday = date("m/d/Y", strtotime('last sunday', $current_time));

        $p = 0;
        foreach($startofworkdayonmonday as $daystartwork){
                if(strtotime($daystartwork) <= strtotime($thissunday)){
                        $current_biweek = $p;
                }
                $p++;
        }

        $p = 0;
        foreach($startofweek as $daystartweek){
                if(strtotime($daystartweek) <= strtotime($thissunday)){
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
                echo 'Le prochain jour de paie est le <span class="biweekpayday">'.$startofpayrollthursday[$value+1].'</span>';
                echo '<br>';
        } else if(isset($_GET['month'])) {
                echo 'Le debut du mois est le <span class="startofmonth">'.$startofmonth[$value].'</span>';
                echo '<br>';
                echo 'La fin du mois est le <span class="endofmonth">'.date('m/d/Y', strtotime($startofmonth[$value+1])).'</span>';
                echo '<br>';
                $x = 0;
                $paydayinmonth = [];
                $startofthemonth = strtotime($startofmonth[$value]);
                $endofthemonth = strtotime($startofmonth[$value+1]);
                foreach($startofpayrollthursday as $payday){
                        if($startofthemonth <= strtotime($payday) && $endofthemonth >= strtotime($payday)){
                                $paydayinmonth[$x] = $payday;
                        }
                        $x++;
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
                echo 'Le prochain jour de paie est le <span class="biweekpayday">'.$startofpayrollthursday[$value+1].'</span>';
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
                $employee_horaire_select = get_user_meta(get_current_user_id(), 'employee_horaire_select', true);
                foreach($push_ as $punch) {
                        if($user_role == 'employer'){
                                if($employee_horaire == get_current_user_id() || ($employee_replace == get_current_user_id() && $dayoff_status == 3) && $employee_horaire_select == -1){
                                                if($punch[0] == 'entrer'){
                                                            if($employee_replace == get_current_user_id() && $dayoff_status == 3){
                                                                    $datetimestarts[$n]['userid'] = $employee_replace;
                                                            } else {
                                                                    $datetimestarts[$n]['userid'] = $employee_horaire;
                                                            }
                                                            $datetimestarts[$n]['time'] = $punch[1];
                                                            $datetimestarts[$n]['salary'] = $salary;
                                                            $n++;
                                                    }
                                                    if($punch[0] == 'sortie'){
                                                            $datetimeends[$l] = $punch[1];
                                                             $l++;
                                                    }
                                        }

                                }
                                if($user_role == 'employeur'){
                                                        if($punch[0] == 'entrer'){
                                                                if($employee_horaire_select == $punch[2]){
                                                                        $datetimestarts[$n]['userid'] = $punch[2];
                                                                        $datetimestarts[$n]['time'] = $punch[1];
                                                                        $datetimestarts[$n]['salary'] = $salary;
                                                                        $n++;
                                                                }else if($employee_replace == $punch[2] && $dayoff_status == 3){
                                                                        $datetimestarts[$n]['userid'] = $employee_replace;
                                                                        $datetimestarts[$n]['time'] = $punch[1];
                                                                        $datetimestarts[$n]['salary'] = $salary;
                                                                        $n++;
                                                                } else if ($employee_horaire_select == 0) {
                                                                        $datetimestarts[$n]['userid'] = $punch[2];
                                                                        $datetimestarts[$n]['time'] = $punch[1];
                                                                        $datetimestarts[$n]['salary'] = $salary;
                                                                        $n++;
                                                                }
                                                        }
                                                        if($punch[0] == 'sortie'){
                                if($employee_horaire_select == $punch[2]){
                                    $datetimeends[$l] = $punch[1];
                                                                         $l++;
                                }else if($employee_replace == $punch[2] && $dayoff_status == 3){
                                                                        $datetimeends[$l] = $punch[1];
                                                                         $l++;
                                } else if ($employee_horaire_select == 0) {
                                    $datetimeends[$l] = $punch[1];
                                                                         $l++;
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

        $o = 0;
        foreach($datetimestarts as $datetimestart){
                if(isset($_GET['week'])) {
                        if($datetimestart['time'] >= strtotime($startofweek[$value]) && $datetimeends[$z] < strtotime($startofweek[$value+1])) {
                                $timepays[$z]['start'] = $datetimestart['time'];
                                $timepays[$z]['end'] = $datetimeends[$z];
                                $timepays[$z]['time'] = $datetimeends[$z] - $datetimestart['time'];
                                $timepays[$z]['salary'] = $datetimestart['salary'];
                                $timepays[$z]['userid'] = $datetimestart['userid'];
                        } 
                }else if(isset($_GET['month'])) {
                        if($datetimestart['time'] >= strtotime($startofmonth[$value]) && $datetimeends[$z] < strtotime($startofmonth[$value+1])) {
                                $timepays[$z]['start'] = $datetimestart['time'];
                                $timepays[$z]['end'] = $datetimeends[$z];
                                $timepays[$z]['time'] = $datetimeends[$z] - $datetimestart['time'];
                                $timepays[$z]['salary'] = $datetimestart['salary'];
                                $timepays[$z]['userid'] = $datetimestart['userid'];
                        } 
                }else if(isset($_GET['biweek'])) {
                        if($datetimestart['time'] >= strtotime($startofworkdayonmonday[$value]) && $datetimeends[$z] < strtotime($startofworkdayonmonday[$value+1])) {
                                $timepays[$z]['start'] = $datetimestart['time'];
                                $timepays[$z]['end'] = $datetimeends[$z];
                                $timepays[$z]['time'] = $datetimeends[$z] - $datetimestart['time'];
                                $timepays[$z]['salary'] = $datetimestart['salary'];
                                $timepays[$z]['userid'] = $datetimestart['userid'];
                        } 
                } else  {
                        if($datetimestart['time'] >= strtotime($startofworkdayonmonday[$value]) && $datetimeends[$z] < strtotime($startofworkdayonmonday[$value+1])) {
                                $timepays[$z]['start'] = $datetimestart['time'];
                                $timepays[$z]['end'] = $datetimeends[$z];
                                $timepays[$z]['time'] = $datetimeends[$z] - $datetimestart['time'];
                                $timepays[$z]['salary'] = $datetimestart['salary'];
                                $timepays[$z]['userid'] = $datetimestart['userid'];
                        } 
                }
                $z++;
        }

        if(isset($_GET['week'])) {

                $i = 0;
                foreach ($timepays as $key => $timevalue) {
                        if($timevalue['start'] >= strtotime($startofweek[$value]) && $timevalue['end'] < strtotime($startofweek[$value+1])) {
                                $userid = $timevalue['userid'];

                                $timepaysum[$userid] = $timepaysum[$userid] + $timevalue['time'];

                                $salary = $timevalue['salary'];
                                $salary_ = ($salary/60);

                                if($timepaysum[$userid] > 144000) {
                                        $timepaysup = $timepaysum[$userid] - 144000;
                                        $hours = floor($timepaysup / 3600);
                                        $minutes = floor(($timepaysup / 60) % 60);
                                        $worktimesup = ($hours * 60) + $minutes;
                                        $timepaysuppay[$userid] = $worktimesup * ($salary_ * 1.5);
                                } else         {        
                                        $timepaynormale = $timepaysum[$userid];
                                        $hours = floor($timepaynormale / 3600);
                                        $minutes = floor(($timepaynormale / 60) % 60);
                                        $worktime = ($hours * 60) + $minutes;
                                        $timepaynormalepay[$userid] = $worktime * $salary_;
                                }
                                $paytime[$userid] = $timepaynormalepay[$userid] + $timepaysuppay[$userid];
                        }
                        $i++;
                }

        } else if(isset($_GET['month'])) {

                $startofmonthminus = strtotime($startofmonth[$value]) - 604800;
                $startofmonthplus = strtotime($startofmonth[$value+1]) + 604800;

                $start = date('m/d/Y', $startofmonthminus);
                $end = date('m/d/Y', $startofmonthplus);

                $allSundays = getSundaysBetweenDates($start, $end);

                $x = 0;
                foreach($allSundays as $allSunday) {
                        $i = 0;
                        foreach ($timepays as $key => $timevalue) {
                                if($timevalue['start'] >= strtotime($allSunday) && $timevalue['end'] < strtotime($allSundays[$x+1])) {
                                        $userid = $timevalue['userid'];

                                        $timepaysum[$x][$userid] = $timepaysum[$x][$userid] + $timevalue['time'];

                                        $salary = $timevalue['salary'];
                                        $salary_ = ($salary/60);

                                        if($timepaysum[$x][$userid] > 144000) {
                                                    $timepaysup[$x][$userid] = $timepaysum[$x][$userid] - 144000;
                                                $hours_[$x][$userid] = floor($timepaysup[$x][$userid] / 3600);
                                                $minutes_[$x][$userid] = floor(($timepaysup[$x][$userid] / 60) % 60);
                                                $worktimesup[$x][$userid] = ($hours_[$x][$userid] * 60) + $minutes_[$x][$userid];
                                                $timepaysuppay[$x][$userid] = $worktimesup[$x][$userid] * ($salary_ * 1.5);
                                        } else {
                                                $timepaysuppay[$x][$userid] = 0;
                                                $timepaynormale[$x][$userid] = $timepaysum[$x][$userid];
                                                $hours[$x][$userid] = floor($timepaynormale[$x][$userid] / 3600);
                                                $minutes[$x][$userid] = floor(($timepaynormale[$x][$userid] / 60) % 60);
                                                $worktime[$x][$userid] = ($hours[$x][$userid] * 60) + $minutes[$x][$userid];
                                                $timepaynormalepay[$x][$userid] = $worktime[$x][$userid] * $salary_;

                                        }                                
                                        $paytime[$x][$userid] = $timepaysuppay[$x][$userid] + $timepaynormalepay[$x][$userid];
                                }
                                $i++;
                        }
                        $x++;                
                }                

                $reindexed_array_normale = array_values($timepaynormalepay);
                $reindexed_array_sup = array_values($timepaysuppay);
                $reindexed_array_paytotal = array_values($paytime);

        } else if(isset($_GET['biweek'])) {

                $start = $startofworkdayonmonday[$value];
                $end = $startofworkdayonmonday[$value+1];

                $allSundays = getSundaysBetweenDates($start, $end);

                $i = 0;
                foreach ($timepays as $key => $timevalue) {
                        if($timevalue['start'] >= strtotime($allSundays[0]) && $timevalue['end'] < strtotime($allSundays[1])) {

                                $userid = $timevalue['userid'];

                                $timepaysumone[$userid] = $timepaysumone[$userid] + $timevalue['time'];

                                $salary = $timevalue['salary'];
                                $salary_ = ($salary/60);

                                if($timepaysumone[$userid] > 144000) {
                                        $timepaysup = $timepaysumone[$userid] - 144000;
                                        $hours = floor($timepaysup / 3600);
                                        $minutes = floor(($timepaysup / 60) % 60);
                                        $worktimesup = ($hours * 60) + $minutes;
                                        $timepaysuppayone[$userid] = $worktimesup * ($salary_ * 1.5);
                                } else {
                                        $timepaynormale = $timepaysumone[$userid];
                                        $hours = floor($timepaynormale / 3600);
                                        $minutes = floor(($timepaynormale / 60) % 60);
                                        $worktime = ($hours * 60) + $minutes;
                                        $timepaynormalepayone[$userid] = $worktime * $salary_;
                                }
                                $paytimeone[$userid] = $timepaynormalepayone[$userid] + $timepaysuppayone[$userid];
                        } 

                        if($timevalue['start'] >= strtotime($allSundays[1]) && $timevalue['end'] < strtotime($allSundays[2])) {

                                $userid = $timevalue['userid'];

                                $timepaysumtow[$userid] = $timepaysumtow[$userid] + $timevalue['time'];

                                $salary = $timevalue['salary'];
                                $salary_ = ($salary/60);

                                if($timepaysumtow[$userid] > 144000) {
                                        $timepaysup = $timepaysumtow[$userid] - 144000;
                                        $hours = floor($timepaysup / 3600);
                                        $minutes = floor(($timepaysup / 60) % 60);
                                        $worktimesup = ($hours * 60) + $minutes;
                                        $timepaysuppaytow[$userid] = $worktimesup * ($salary_ * 1.5);
                                } else {
                                        $timepaynormale = $timepaysumtow[$userid];
                                        $hours = floor($timepaynormale / 3600);
                                        $minutes = floor(($timepaynormale / 60) % 60);
                                        $worktime = ($hours * 60) + $minutes;
                                        $timepaynormalepaytow[$userid] = $worktime * $salary_;
                                }
                                $paytimetow[$userid] = $timepaynormalepaytow[$userid] + $timepaysuppaytow[$userid];
                        }
                        $i++;
                }

        } else {

                $start = $startofworkdayonmonday[$value];
                $end = $startofworkdayonmonday[$value+1];

                $allSundays = getSundaysBetweenDates($start, $end);

                $i = 0;
                foreach ($timepays as $key => $timevalue) {
                        if($timevalue['start'] >= strtotime($allSundays[0]) && $timevalue['end'] < strtotime($allSundays[1])) {

                                $userid = $timevalue['userid'];

                                $timepaysumone[$userid] = $timepaysumone[$userid] + $timevalue['time'];

                                $salary = $timevalue['salary'];
                                $salary_ = ($salary/60);

                                if($timepaysumone[$userid] < 144000) {
                                        $timepaynormale = $timepaysumone[$userid];
                                        $hours = floor($timepaynormale / 3600);
                                        $minutes = floor(($timepaynormale / 60) % 60);
                                        $worktime = ($hours * 60) + $minutes;
                                        $timepaynormalepayone[$userid] = $worktime * $salary_;
                                } else {
                                        $timepaysup = $timepaysumone[$userid] - 144000;
                                        $hours = floor($timepaysup / 3600);
                                        $minutes = floor(($timepaysup / 60) % 60);
                                        $worktimesup = ($hours * 60) + $minutes;
                                        $timepaysuppayone[$userid] = $worktimesup * ($salary_ * 1.5);
                                }
                                $paytimeone[$userid] = $timepaynormalepayone[$userid] + $timepaysuppayone[$userid];
                        } 

                        if($timevalue['start'] >= strtotime($allSundays[1]) && $timevalue['end'] < strtotime($allSundays[2])) {

                                $userid = $timevalue['userid'];

                                $timepaysumtow[$userid] = $timepaysumtow[$userid] + $timevalue['time'];

                                $salary = $timevalue['salary'];
                                $salary_ = ($salary/60);

                                if($timepaysumtow[$userid] < 144000) {
                                        $timepaynormale = $timepaysumtow[$userid];
                                        $hours = floor($timepaynormale / 3600);
                                        $minutes = floor(($timepaynormale / 60) % 60);
                                        $worktime = ($hours * 60) + $minutes;
                                        $timepaynormalepaytow[$userid] = $worktime * $salary_;
                                } else {
                                        $timepaysup = $timepaysumtow[$userid] - 144000;
                                        $hours = floor($timepaysup / 3600);
                                        $minutes = floor(($timepaysup / 60) % 60);
                                        $worktimesup = ($hours * 60) + $minutes;
                                        $timepaysuppaytow[$userid] = $worktimesup * ($salary_ * 1.5);
                                }
                                $paytimetow[$userid] = $timepaynormalepaytow[$userid] + $timepaysuppaytow[$userid];
                        }
                        $i++;
                }
        }

        $i = 0;                
        $y = 0;
        $loop = [];
        foreach($datetimestarts as $datetimestart){
                $timepays_[$i]['userid'] = $datetimestart['userid'];
                $timepays_[$i]['time'] = $datetimeends[$i] - $datetimestart['time'];
                $timepays_[$i]['start'] = $datetimestart['time'];
                $timepays_[$i]['end'] = $datetimeends[$i];
                $timepays_[$i]['salary'] = $datetimestart['salary'];
                $timestart = $datetimestart['time'];
                $thissunday = date("m/d/Y", strtotime('this sunday', $timestart));
                $timepays_[$i]['thissunday'] = $thissunday;
                $nextsunday = date("m/d/Y", strtotime('+1 week sunday', $timestart));
                $timepays_[$i]['nextsunday'] = $nextsunday;
                $i++;
        }

        $i = 0;
        foreach ($timepays_ as $timepay_){

        sort($vartimepaysum);
        sort($vartimepaysumone);
        sort($vartimepaysumtow);

        $salaryi_ = $timepay_['salary'];

        if(isset($_GET['week'])) {
            for($i = 0;$i <= count($startofweek); $i++){
                    $counttimepay = [];
                if($timepay_['start'] >= strtotime($startofweek[$i]) && $timepay_['end'] < strtotime($startofweek[$i+1])){
                                        $userid = $timepay_['userid'];
                                        $counttimepay[$userid] = $counttimepay[$userid] + $timepay_['time'];
                                        if($counttimepay[$userid] <= 144000){
                                                $hours_ = floor($timepay_['time'] / 3600);
                                                $minutes_ = floor(($timepay_['time'] / 60) % 60);
                                                $worktime_ = ($hours_ * 60) + $minutes_;
                                                $salary__ = ($salaryi_/60);
                                                $pay_normale['userid'] = $worktime_ * $salary__;
                                            } else {
                                                      $hours_ = floor($timepay_['time'] / 3600);
                                                $minutes_ = floor(($timepay_['time'] / 60) % 60);
                                                $worktime_ = ($hours_ * 60) + $minutes_;
                                                $salary__ = ($salaryi_/60) * 1.5;
                                                $pay_sup['userid'] = $worktime_ * $salary__;
                                            }
                                            $pay_ = $pay_normale['userid'] + $pay_sup['userid'];
                }
            }
                } else if(isset($_GET['month'])) {

            for($i=0; $i <= count($startofmonth); $i++){

                $startofmonthminus = strtotime($startofmonth[$i]);
                $startofmonthplus = strtotime($startofmonth[$i+1]);
                    $counttimepay = [];
                if($timepay_['start'] >= $startofmonthminus && $timepay_['end'] < $startofmonthplus){
                                        $userid = $timepay_['userid'];
                                        $counttimepay[$userid] = $counttimepay[$userid] + $timepay_['time'];
                                        if($counttimepay[$userid] <= 144000){
                                                $hours_ = floor($timepay_['time'] / 3600);
                                                $minutes_ = floor(($timepay_['time'] / 60) % 60);
                                                $worktime_ = ($hours_ * 60) + $minutes_;
                                                $salary__ = ($salaryi_/60);
                                                $pay_normale[$userid] = $worktime_ * $salary__;
                                            } else {
                                                      $hours_ = floor($timepay_['time'] / 3600);
                                                $minutes_ = floor(($timepay_['time'] / 60) % 60);
                                                $worktime_ = ($hours_ * 60) + $minutes_;
                                                $salary__ = ($salaryi_/60) * 1.5;
                                                $pay_sup[$userid] = $worktime_ * $salary__;
                                            }
                                            $pay_ = $pay_normale[$userid] + $pay_sup[$userid];
                }
            }

                } else if(isset($_GET['biweek'])) {
                    for($i=0;$i <= count($startofworkdayonmonday);$i++){
                    $counttimepay = [];
                  if($timepay_['start'] >= strtotime($startofworkdayonmonday[$i]) && $timepay_['end'] < strtotime($startofworkdayonmonday[$i+1])){
                                          $userid = $timepay_['userid'];
                                        $counttimepay[$userid] = $counttimepay[$userid] + $timepay_['time'];
                                        if($counttimepay[$userid] <= 144000){
                                                $hours_ = floor($timepay_['time'] / 3600);
                                                $minutes_ = floor(($timepay_['time'] / 60) % 60);
                                                $worktime_ = ($hours_ * 60) + $minutes_;
                                                $salary__ = ($salaryi_/60);
                                                $pay_normale[$userid] = $worktime_ * $salary__;
                                            } else {
                                                      $hours_ = floor($timepay_['time'] / 3600);
                                                $minutes_ = floor(($timepay_['time'] / 60) % 60);
                                                $worktime_ = ($hours_ * 60) + $minutes_;
                                                $salary__ = ($salaryi_/60) * 1.5;
                                                $pay_sup[$userid] = $worktime_ * $salary__;
                                            }
                                            $pay_ = $pay_normale[$userid] + $pay_sup[$userid];
                }
            }

                } else {

                for($i=0;$i <= count($startofworkdayonmonday);$i++){
                  $counttimepay = [];
                  if($timepay_['start'] >= strtotime($startofworkdayonmonday[$i]) && $timepay_['end'] < strtotime($startofworkdayonmonday[$i+1])){
                                          $userid = $timepay_['userid'];                  
                                        $counttimepay[$userid] = $counttimepay[$userid] + $timepay_['time'];
                                        if($counttimepay[$userid] <= 144000){
                                                $hours_ = floor($timepay_['time'] / 3600);
                                                $minutes_ = floor(($timepay_['time'] / 60) % 60);
                                                $worktime_ = ($hours_ * 60) + $minutes_;
                                                $salary__ = ($salaryi_/60);
                                                $pay_normale[$userid] = $worktime_ * $salary__;
                                            } else {
                                                      $hours_ = floor($timepay_['time'] / 3600);
                                                $minutes_ = floor(($timepay_['time'] / 60) % 60);
                                                $worktime_ = ($hours_ * 60) + $minutes_;
                                                $salary__ = ($salaryi_/60) * 1.5;
                                                $pay_sup[$userid] = $worktime_ * $salary__;
                                            }
                                            $pay_ = $pay_normale[$userid] + $pay_sup[$userid];
                }
            }

                }

                $x = 1;
                $payloop = 0;
                for($p=0; $p<=1670; $p++){
                                if( strtotime($startofworkdayonmonday[$p]) <= $timepay_['start'] && strtotime($startofworkdayonmonday[$p+1]) > $timepay_['end'] ){
                                        $payloop = $x;
                                }
                                $x++;
                }

                sort($payloop);

                $mypaycalender[$o] = array(date("m/d/Y", $timepay_['start']), $timepay_['end'], round($pay_, 2), $timepay_['userid'], $startofpayrollthursday[$payloop]);
                $i++;
                $y++;
                $o++;

        }

            sort($mypaycalender);

            echo '<ul class="gettimepay" style="display: none;">';

                foreach ($mypaycalender as $mypaycalender_){

                        echo '<li>'.$mypaycalender_[0].'||'.$mypaycalender_[1].'||'.$mypaycalender_[2].'||'.$mypaycalender_[3].'||'.$mypaycalender_[4].'</li>';

                }

        echo '</ul>';

             if(isset($_GET['week'])) {

                echo 'Votre montant brute pour le temps regulier pour cette semaine: ';
                    echo round(array_sum($timepaynormalepay), 2).'$';
                    echo '<br>';
                    echo 'Votre montant brute pour le temps suplementaire: ';
                    echo round(array_sum($timepaysuppay), 2).'$';
                    echo '<br>';
                    echo 'Votre montant brute pour le total de la semaie: ';
                    echo round(array_sum($paytime), 2).'$';
                    echo '<br>';

            } else if(isset($_GET['month'])) {
                $x = 0;
                $i = 1;
                foreach($reindexed_array_paytotal as $reindexed_array_paytotal_){
                        echo 'Votre montant brute pour le temps regulier pour la semaine numero '.$i.': ';
                            echo round(array_sum($reindexed_array_normale[$x]), 2).'$';
                            echo '<br>';
                            echo 'Votre montant brute pour le temps suplementaire pour la semaine numero '.$i.': ';
                            echo round(array_sum($reindexed_array_sup[$x]), 2).'$';
                            echo '<br>';
                            echo 'Votre montant brute pour le total de la semaie numero '.$i.': ';
                            echo round(array_sum($reindexed_array_paytotal_), 2).'$';
                            $paytotalmonthround[$x] = round(array_sum($reindexed_array_paytotal_), 2);
                            echo '<br>';
                            $x++;
                            $i++;
                    }
                    echo 'Votre montant total pour le mois avec le temps supplémentaire: ';
                    $paytimesum =  round(array_sum($paytotalmonthround), 2);
                    echo $paytimesum.'$';

            } else if(isset($_GET['biweek'])) {

                echo 'Votre montant brute pour le temps regulier pour la premiere semaine: ';
                    echo round(array_sum($timepaynormalepayone), 2).'$';
                    echo '<br>';
                    echo 'Votre montant brute pour le temps suplementaire de la premiere semaine: ';
                    echo round(array_sum($timepaysuppayone), 2).'$';
                    echo '<br>';
                    echo 'Votre montant brute pour le total de la premiere semaie: ';
                    echo round(array_sum($paytimeone), 2).'$';
                    echo '<br>';
                    echo 'Votre montant brute pour le temps regulier pour la seconde semaine: ';
                    echo round(array_sum($timepaynormalepaytow_), 2).'$';
                    echo '<br>';
                    echo 'Votre montant brute pour le temps suplementaire de la seconde semaine: ';
                    echo round(array_sum($timepaysuppaytow), 2).'$';
                    echo '<br>';
                    echo 'Votre montant brute pour le total de la seconde semaie: ';
                    echo round(array_sum($paytimetow), 2).'$';
                    echo '<br>';
                    echo 'Votre montant brute pour les deux semaines: ';
                    $paytimefull = array_sum($paytimeone) + array_sum($paytimetow);
                    echo round($paytimefull, 2).'$';
                    echo '<br>';

            } else  {

                echo 'Votre montant brute pour le temps regulier pour la premiere semaine: ';
                    echo round(array_sum($timepaynormalepayone), 2).'$';
                    echo '<br>';
                    echo 'Votre montant brute pour le temps suplementaire de la premiere semaine: ';
                    echo round(array_sum($timepaysuppayone), 2).'$';
                    echo '<br>';
                    echo 'Votre montant brute pour le total de la premiere semaie: ';
                    echo round(array_sum($paytimeone), 2).'$';
                    echo '<br>';
                    echo 'Votre montant brute pour le temps regulier pour la seconde semaine: ';
                    echo round(array_sum($timepaynormalepaytow), 2).'$';
                    echo '<br>';
                    echo 'Votre montant brute pour le temps suplementaire de la seconde semaine: ';
                    echo round(array_sum($timepaysuppaytow), 2).'$';
                    echo '<br>';
                    echo 'Votre montant brute pour le total de la seconde semaie: ';
                    echo round(array_sum($paytimetow), 2).'$';
                    echo '<br>';
                    echo 'Votre montant brute pour les deux semaines: ';
                    $paytimefull = array_sum($paytimeone) + array_sum($paytimetow);
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