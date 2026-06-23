<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


function monemploi_score() {

    $n = 0;
    $l = 0;
    $a = 0;
    
    for ($m = 1; $m <= 600; $m++) {
	    $startofmonths[$a] = date('m/d/Y', mktime(0, 0, 0, $m, 1, '2018'));
	    $a++;
	}

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
    
    $posts = get_posts($args);
    
        $u = 0;
    	foreach ( $posts as $post ) {
    	
    	    	$author_id = $post->post_author;
		$my_employee = get_user_meta( $author_id, 'my_employee_key', true);
		$employee_scores_select = get_user_meta(get_current_user_id(), 'employee_scores_select', true);
		
	if($user_role == 'employer'){
	
		$current_time = current_time( 'timestamp' );
		$datepickerstarthoraire = get_post_meta( $post->ID, 'datepickerstarthoraire_key', true );
		$timestarthoraire = get_post_meta( $post->ID, 'timestarthoraire_key', true );
		$datepickerendhoraire = get_post_meta( $post->ID, 'datepickerendhoraire_key', true );
		$timeendhoraire = get_post_meta( $post->ID, 'timeendhoraire_key', true );	
		$employee_horaire = get_post_meta( $post->ID, 'employee_horaire_key', true);
		$datestartstrtotime = strtotime($datepickerstarthoraire.'T'.$timestarthoraire);
		$dateendstrtotime = strtotime($datepickerendhoraire.'T'.$timeendhoraire);
		
		$push_ = get_post_meta( $post->ID, 'push_key', true );
		$employee_horaire = get_post_meta( $post->ID, 'employee_horaire_key', true );
		$employee_replace = get_post_meta( $post->ID, 'employee_replace_key', true );
		$dayoff_status = get_post_meta( $post->ID, 'dayoff_status_key', true );
		$fristpush = array_values($push_)[0];
		$endpush = end($push_);
		$fristpushalls[$u]['time'] = $fristpush[1];
		$fristpushalls[$u]['inout'] = $fristpush[0];
		$fristpushalls[$u]['date'] = $datestartstrtotime;
		$endpushallls[$u]['time'] = $endpush[1];
		$endpushallls[$u]['inout'] = $endpush[0];
		$endpushallls[$u]['date'] = $dateendstrtotime;
		$u++;
	
	}
    	    
    	    if($user_role == 'employeur'){
    	    	if($employee_scores_select == 0 || $employee_scores_select == ''){
			$current_time = current_time( 'timestamp' );
			$datepickerstarthoraire = get_post_meta( $post->ID, 'datepickerstarthoraire_key', true );
			$timestarthoraire = get_post_meta( $post->ID, 'timestarthoraire_key', true );
			$datepickerendhoraire = get_post_meta( $post->ID, 'datepickerendhoraire_key', true );
			$timeendhoraire = get_post_meta( $post->ID, 'timeendhoraire_key', true );	
			$employee_horaire = get_post_meta( $post->ID, 'employee_horaire_key', true);
			$datestartstrtotime = strtotime($datepickerstarthoraire.'T'.$timestarthoraire);
			$dateendstrtotime = strtotime($datepickerendhoraire.'T'.$timeendhoraire);
			
			$push_ = get_post_meta( $post->ID, 'push_key', true );
			$employee_horaire = get_post_meta( $post->ID, 'employee_horaire_key', true );
			$employee_replace = get_post_meta( $post->ID, 'employee_replace_key', true );
			$dayoff_status = get_post_meta( $post->ID, 'dayoff_status_key', true );
			$fristpush = array_values($push_)[0];
			$endpush = end($push_);
			$fristpushalls[$u]['time'] = $fristpush[1];
			$fristpushalls[$u]['inout'] = $fristpush[0];
			$fristpushalls[$u]['date'] = $datestartstrtotime;
			$endpushallls[$u]['time'] = $endpush[1];
			$endpushallls[$u]['inout'] = $endpush[0];
			$endpushallls[$u]['date'] = $dateendstrtotime;
			$u++;
	        
	      }
	      
	      if($employee_scores_select != 0 && $employee_scores_select != -1){
	      		$current_time = current_time( 'timestamp' );
			$datepickerstarthoraire = get_post_meta( $post->ID, 'datepickerstarthoraire_key', true );
			$timestarthoraire = get_post_meta( $post->ID, 'timestarthoraire_key', true );
			$datepickerendhoraire = get_post_meta( $post->ID, 'datepickerendhoraire_key', true );
			$timeendhoraire = get_post_meta( $post->ID, 'timeendhoraire_key', true );	
			$employee_horaire = get_post_meta( $post->ID, 'employee_horaire_key', true);
			$datestartstrtotime = strtotime($datepickerstarthoraire.'T'.$timestarthoraire);
			$dateendstrtotime = strtotime($datepickerendhoraire.'T'.$timeendhoraire);
    			if($employee_scores_select == $employee_horaire){
    				if (in_array($employee_horaire, $my_employee)) {
	    				$push_ = get_post_meta( $post->ID, 'push_key', true );
					$employee_horaire = get_post_meta( $post->ID, 'employee_horaire_key', true );
					$employee_replace = get_post_meta( $post->ID, 'employee_replace_key', true );
					$dayoff_status = get_post_meta( $post->ID, 'dayoff_status_key', true );
					$fristpush = array_values($push_)[0];
					$endpush = end($push_);
					$fristpushalls[$u]['time'] = $fristpush[1];
					$fristpushalls[$u]['inout'] = $fristpush[0];
					$fristpushalls[$u]['date'] = $datestartstrtotime;
					$endpushallls[$u]['time'] = $endpush[1];
					$endpushallls[$u]['inout'] = $endpush[0];
					$endpushallls[$u]['date'] = $dateendstrtotime;
					$u++;
				
				}
    			
    			}
    			
    		}

	}

    }
            
	if($user_role == 'employeur'){
		echo '<select name="employee_scores_select" id="employee_scores_select" class="employee_scores_select" style="float: right;">';
		    echo '<option value="0">Tous les employés</option>';
			foreach($my_employee as $employee){
				$user_by_id = get_user_by('id', $employee);
				$salary = get_user_meta( $employee, 'salary_key', true);
				if($employee_scores_select === $employee){
				    echo '<option value="'.$employee.'" selected>'. $user_by_id->user_nicename .' - ' . $user_by_id->user_firstname . ' ' . $user_by_id->user_lastname . ' - ' . $salary . '$</option>';
				} else {
				    echo '<option value="'.$employee.'">'. $user_by_id->user_nicename .' - ' . $user_by_id->user_firstname . ' ' . $user_by_id->user_lastname . ' - ' . $salary . '$</option>';
				}
			}
		echo '</select>';
		echo '<br>';
		echo '<br>';
	}
    
    sort($fristpushalls);
    sort($endpushallls);
    		
    		$y = 0;
    		foreach($startofmonths as $startofmonth) {
	    		if(strtotime($startofmonths[$y]) <= $current_time && strtotime($startofmonths[$y+1]) > $current_time ) {
	    			$currentmonth = $y;	    		
	    		}
	    	$y++;
    		}
    		
    		if($_GET['month']){
    			echo date('m/Y', strtotime($startofmonths[$_GET['month']]));
    			echo '<br>';
    		} else {
    			echo date('m/Y', strtotime($startofmonths[$currentmonth]));
    			echo '<br>';
    		} 
    		if(isset($_GET['month'])) {
	    		$monthminus = $_GET['month'] - 1;
	    		$monthplus = $_GET['month'] + 1;
	    		$currentmonth = $_GET['month'];
    		} else  {
	    		$monthminus = $currentmonth - 1;
	    		$monthplus = $currentmonth + 1;
		}
    		echo '<a href="'.$current_url.'?month='.$monthminus.'">Précédent</a>';
		echo ' - ';
		echo '<a href="'.$current_url.'?month='.$currentmonth.'">Aujourd&#39;hui</a>';
		echo ' - ';
		echo '<a href="'.$current_url.'?month='.$monthplus.'">Suivant</a>';
		echo '<br>';
		echo '<br>';		
		
		    echo '<div>';
			    echo 'Nombre total de minute en avance: <span class="mincalcsumavanceup"></span>';
			    echo '<br>';
			    echo 'Nombre total de minute en retard: <span class="mincalcsumlateup"></span>';
			    echo '<br>';
			    echo 'Nombre total de minute en supplémentaire: <span class="mincalcsumsupup"></span>';
			    echo '<br>';
			    echo 'Nombre total de minute en depart hâtif: <span class="mincalcsumhatifup"></span>';
			    echo '<br>';
			    echo 'Nombre total de points: ';
			    echo '<span class="mincalcsumtotalup"></span>';
		    echo '</div>';
    		
    			$a = 0;
    			$b = 0;
    			$c = 0;
    			$d = 0;
    		    	$t = 0;
	    		foreach($fristpushalls as $fristpushall) {
		                    if(strtotime($startofmonths[$currentmonth]) <= $fristpushall['time'] && strtotime($startofmonths[$currentmonth+1]) > $endpushallls[$t]['time']){
			                    echo '<br>';
			                    echo date('m/d/Y', $fristpushall['time']);
			                    echo '<br>';
		                    }
	                	    if($fristpushall['inout'] == 'entrer'){
	                	        if(strtotime($startofmonths[$currentmonth]) <= $fristpushall['time'] && strtotime($startofmonths[$currentmonth+1]) > $endpushallls[$t]['time']){
	                    	    	if($fristpushall['time'] <= $fristpushall['date']){
	                    		    	$endpushcalc = $fristpushall['date'] - $fristpushall['time'];
	                    	           	$seconds = $endpushcalc;
	                                    $min = intval($seconds / 60);
	                                    if($min != 0){
	                                        $mincalcavance[$a] = $min;
	                                        echo 'Nombre de temps en avance.';
	                        		    	echo '<br>';
	                                        echo $min . ':' . str_pad(($seconds % 60), 2, '0', STR_PAD_LEFT);
	                        		    	echo '<br>';
	                        		    	$a++;
	                        		    
	                                    }
	                    	    	}
	                	        }
	                	    }
	                	    if($fristpushall['inout'] == 'entrer'){
	                	        if(strtotime($startofmonths[$currentmonth]) <= $fristpushall['time'] && strtotime($startofmonths[$currentmonth+1]) > $endpushallls[$t]['time']){
	                    	    	if($fristpushall['time'] >= $fristpushall['date']){
	                    		    	$endpushcalc = $fristpushall['time'] -  $fristpushall['date'];
	                    	           	$seconds = $endpushcalc;
	                                    $min = intval($seconds / 60);
	                                    if($min != 0){
	                                        $mincalclate[$b] = $min;
	                                        echo 'Nombre de temps en retard.';
	                        		    	echo '<br>';
	                                        echo $min . ':' . str_pad(($seconds % 60), 2, '0', STR_PAD_LEFT);
	                        		    	echo '<br>';
	                        		    	$b++;
	                        		    	
	                                    }
	                    	    	}
	                	    	}
	                	    }
	                        if($endpushallls[$t]['inout'] == 'sortie'){
	                            if(strtotime($startofmonths[$currentmonth]) <= $fristpushall['time'] && strtotime($startofmonths[$currentmonth+1]) > $endpushallls[$t]['time']){
	                              	if($endpushallls[$t]['time'] >= $endpushallls[$t]['date']){
	                    		    	$endpushcalc = $endpushallls[$t]['time'] - $endpushallls[$t]['date'];
	                    	           	$seconds = $endpushcalc;
	                                    $min = intval($seconds / 60);
	                                    if($min != 0){
	                                        $mincalcsup[$c] = $min;
	                                        echo 'Nombre de temps en supplémentaire.';
	                        		    	echo '<br>';
	                                        echo $min . ':' . str_pad(($seconds % 60), 2, '0', STR_PAD_LEFT);
	                        		    	echo '<br>';
	                        		    	$c++;
	                        		    	
	                                    }
	                    	    	}
	                	    	}
	                	    }
	                        if($endpushallls[$t]['inout'] == 'sortie'){
	                            if(strtotime($startofmonths[$currentmonth]) <= $fristpushall['time'] && strtotime($startofmonths[$currentmonth+1]) > $endpushallls[$t]['time']){
	                              	if($endpushallls[$t]['time'] < $endpushallls[$t]['date']){
	                    		    	$endpushcalc = $endpushallls[$t]['date'] - $endpushallls[$t]['time'];
	                    	           	$seconds = $endpushcalc;
	                                    $min = intval($seconds / 60);
	                                    if($min != 0){
	                                        $mincalchatif[$d] = $min;
	                                        echo 'Nombre de temps de depart hâtif.';
	                        		    	echo '<br>';
	                                        echo $min . ':' . str_pad(($seconds % 60), 2, '0', STR_PAD_LEFT);
	                        		    	echo '<br>';
	                        		    	$d++;
	                        		    	
	                                    }
	                    	    	}
	                	    }
			}
			$t++;
			
	        }
    
    echo '<br>';
    $mincalcsumavance = array_sum($mincalcavance);
    $mincalcsumlate = array_sum($mincalclate);
    $mincalcsumsup = array_sum($mincalcsup);
    $mincalcsumhatif = array_sum($mincalchatif);
    echo '<div style="display: none;">';
	    echo 'Nombre total de minute en avance: <span class="mincalcsumavance">' . $mincalcsumavance . '</span>';
	    echo '<br>';
	    echo 'Nombre total de minute en retard: <span class="mincalcsumlate">' . $mincalcsumlate . '</span>';
	    echo '<br>';
	    echo 'Nombre total de minute en supplémentaire: <span class="mincalcsumsup">'. $mincalcsumsup . '</span>';
	    echo '<br>';
	    echo 'Nombre total de minute en depart hâtif: <span class="mincalcsumhatif">'. $mincalcsumhatif . '</span>';
	    echo '<br>';
	    $mincalcsumtotal = ($mincalcsumavance - $mincalcsumlate) + ($mincalcsumsup - $mincalcsumhatif);
	    echo 'Nombre total de points: ';
	    echo '<span class="mincalcsumtotal">'.$mincalcsumtotal.'</span>';
    echo '</div>';


}

add_shortcode('monemploi-score', 'monemploi_score');

?>