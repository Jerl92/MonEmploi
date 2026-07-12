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
            $x = 0;
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
            $timebrake = get_post_meta( $post->ID, 'timebrake_key', true );
    	  
    		if($employee_horaire == get_current_user_id()){
    			$push_ = get_post_meta( $post->ID, 'push_key', true );
    			$employee_horaire = get_post_meta( $post->ID, 'employee_horaire_key', true );
    			$employee_replace = get_post_meta( $post->ID, 'employee_replace_key', true );
    			$dayoff_status = get_post_meta( $post->ID, 'dayoff_status_key', true );
    			$fristpush = array_values($push_)[0];
    			$endpush = end($push_);
    			$fristpushalls[$u]['time'] = $fristpush[1];
    			$fristpushalls[$u]['inout'] = $fristpush[0];
    			$fristpushalls[$u]['date'] = $datestartstrtotime;
    			$fristpushalls[$u]['userid'] = $employee_horaire;
                	$punchs[$u] =  $push_;
    			$endpushallls[$u]['time'] = $endpush[1];
    			$endpushallls[$u]['inout'] = $endpush[0];
    			$endpushallls[$u]['date'] = $dateendstrtotime;
    			$timebrakes[$u] = $timebrake;
    			$u++;
    		}
    	
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
                	$timebrake = get_post_meta( $post->ID, 'timebrake_key', true );
    			
    			$push_ = get_post_meta( $post->ID, 'push_key', true );
    			$employee_horaire = get_post_meta( $post->ID, 'employee_horaire_key', true );
    			$employee_replace = get_post_meta( $post->ID, 'employee_replace_key', true );
    			$dayoff_status = get_post_meta( $post->ID, 'dayoff_status_key', true );
    			$fristpush = array_values($push_)[0];
    			$endpush = end($push_);
    			$fristpushalls[$u]['time'] = $fristpush[1];
    			$fristpushalls[$u]['inout'] = $fristpush[0];
    			$fristpushalls[$u]['date'] = $datestartstrtotime;
    			$fristpushalls[$u]['userid'] = $employee_horaire;
                	$punchs[$u] =  $push_;
    			$endpushallls[$u]['time'] = $endpush[1];
    			$endpushallls[$u]['inout'] = $endpush[0];
    			$endpushallls[$u]['date'] = $dateendstrtotime;
                	$timebrakes[$u] = $timebrake;
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
                	$timebrake = get_post_meta( $post->ID, 'timebrake_key', true );
        			if($employee_scores_select == $employee_horaire || $employee_horaire == get_current_user_id()){
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
    					$fristpushalls[$u]['userid'] = $employee_horaire;
                        		$punchs[$u] =  $push_;
                    			$endpushallls[$u]['time'] = $endpush[1];
    					$endpushallls[$u]['inout'] = $endpush[0];
    					$endpushallls[$u]['date'] = $dateendstrtotime;
                        		$timebrakes[$u] = $timebrake;
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
        usort($punchs, function($a, $b) {
            return $a[1] <=> $b[1];
        });
        		
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
        		$currentmonth_ = $currentmonth;
        		if(isset($_GET['month'])) {
    	    		$monthminus = $_GET['month'] - 1;
    	    		$monthplus = $_GET['month'] + 1;
    	    		$currentmonth = $_GET['month'];
        		} else {
    	    		$monthminus = $currentmonth - 1;
    	    		$monthplus = $currentmonth + 1;
    			}
        	echo '<a href="'.$current_url.'?month='.$monthminus.'">Précédent</a>';
    		echo ' - ';
    		echo '<a href="'.$current_url.'?month='.$currentmonth_.'">Aujourd&#39;hui</a>';
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
    			    echo 'Nombre total de minute en départ hâtif: <span class="mincalcsumhatifup"></span>';
    			    echo '<br>';
		    	    echo 'Nombre total de minute de pause restant: <span class="mincalcsumresteup"></span>';
    			    echo '<br>';
    			    echo 'Nombre total de minute de pause dépassé: <span class="mincalcsumdepaseup"></span>';
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
        			    if (strtotime($startofmonths[$currentmonth]) <= $fristpushall['time'] && strtotime($startofmonths[$currentmonth + 1]) > $endpushallls[$t]['time']) {
        			        echo '<br>';
        			        echo date('m/d/Y', $fristpushall['date']);
        			        if ($user_role == 'employeur') {
        			            echo ' - ';
        			            $getuserbyid = get_user_by('id', $fristpushall['userid']);
        			            echo $getuserbyid -> user_login;
        			            echo ' - ';
        			            echo $getuserbyid -> user_firstname;
        			            echo ' ';
        			            echo $getuserbyid -> user_lastname;
        			        }
        			        echo '<br>';
        			    }
        			    if ($fristpushall['inout'] == 'entrer') {
        			        if (strtotime($startofmonths[$currentmonth]) <= $fristpushall['time'] && strtotime($startofmonths[$currentmonth + 1]) > $endpushallls[$t]['time']) {
        			            if ($fristpushall['time'] <= $fristpushall['date']) {
        			                $endpushcalc = $fristpushall['date'] - $fristpushall['time'];
        			                $seconds = $endpushcalc;
        			                $min = intval($seconds / 60);
        			                if ($min != 0) {
        			                    $mincalcavance[$a] = $min;
        			                    echo 'Nombre de temps en avance: '.$min.':'.str_pad(($seconds % 60), 2, '0', STR_PAD_LEFT);
        			                    echo '<br>';
        			                    $a++;

        			                }
        			            }
        			        }
        			    }
        			    if ($fristpushall['inout'] == 'entrer') {
        			        if (strtotime($startofmonths[$currentmonth]) <= $fristpushall['time'] && strtotime($startofmonths[$currentmonth + 1]) > $endpushallls[$t]['time']) {
        			            if ($fristpushall['time'] >= $fristpushall['date']) {
        			                $endpushcalc = $fristpushall['time'] - $fristpushall['date'];
        			                $seconds = $endpushcalc;
        			                $min = intval($seconds / 60);
        			                if ($min != 0) {
        			                    $mincalclate[$b] = $min;
        			                    echo 'Nombre de temps en retard: '.$min.':'.str_pad(($seconds % 60), 2, '0', STR_PAD_LEFT);
        			                    echo '<br>';
        			                    $b++;

        			                }
        			            }
        			        }
        			    }
        			    if ($endpushallls[$t]['inout'] == 'sortie') {
        			        if (strtotime($startofmonths[$currentmonth]) <= $fristpushall['time'] && strtotime($startofmonths[$currentmonth + 1]) > $endpushallls[$t]['time']) {
        			            if ($endpushallls[$t]['time'] >= $endpushallls[$t]['date']) {
        			                $endpushcalc = $endpushallls[$t]['time'] - $endpushallls[$t]['date'];
        			                $seconds = $endpushcalc;
        			                $min = intval($seconds / 60);
        			                if ($min != 0) {
        			                    $mincalcsup[$c] = $min;
        			                    echo 'Nombre de temps en supplémentaire: '.$min.':'.str_pad(($seconds % 60), 2, '0', STR_PAD_LEFT);
        			                    echo '<br>';
        			                    $c++;

        			                }
        			            }
        			        }
        			    }
        			    if ($endpushallls[$t]['inout'] == 'sortie') {
        			        if (strtotime($startofmonths[$currentmonth]) <= $fristpushall['time'] && strtotime($startofmonths[$currentmonth + 1]) > $endpushallls[$t]['time']) {
        			            if ($endpushallls[$t]['time'] < $endpushallls[$t]['date']) {
        			                $endpushcalc = $endpushallls[$t]['date'] - $endpushallls[$t]['time'];
        			                $seconds = $endpushcalc;
        			                $min = intval($seconds / 60);
        			                if ($min != 0) {
        			                    $mincalchatif[$d] = $min;
        			                    echo 'Nombre de temps de depart hâtif: '.$min.':'.str_pad(($seconds % 60), 2, '0', STR_PAD_LEFT);
        			                    echo '<br>';
        			                    $d++;

        			                }
        			            }
        			        }

        			    }


        			    $i = 0;
        			    $x = 0;
        			    $entrer = [];
        			    $sortie = [];
        			    foreach($punchs[$t] as $push) {
        			        if ($push[0] == 'entrer') {
        			            if (strtotime($startofmonths[$currentmonth]) <= $push[1]) {
	        			                $entrer[$x] = $push[1];
	        			                $x++;
        			            }
        			        }
        			        if ($push[0] == 'sortie') {
        			            if (strtotime($startofmonths[$currentmonth + 1]) > $push[1]) {
	        			                $sortie[$i] = $push[1];
	        			                $i++;
        			            }

        			        }
        			    }
        			    $entrercount = count($entrer);
        			    $pausetime = [];
        			    for ($i = 0; $i <= $entrercount; $i++) {
        			        if (($entrer[$i] != 0 || $entrer[$i] != '') && ($sortie[$i - 1] != 0 || $sortie[$i - 1] != '')) {
        			            $pausetime[$i] = $pausetime[$i] + ($entrer[$i] - $sortie[$i - 1]);
        			        }
        			    }

        			    $seconds = array_sum($pausetime);
        			    $minutes = intval($seconds) / 60;
        			    if ($minutes > $timebrakes[$t]) {
        			        $timebrakecalc = $minutes - $timebrakes[$t];
        			        $timebrakecalcsum[$t] = $minutes - $timebrakes[$t];
        			        echo 'Vous avez dépassé le temps de pause de '.$timebrakecalc. ' minutes.';
        			        echo '<br>';
        			    }
        			    if ($minutes < $timebrakes[$t]) {
        			    	$timebrakecalc_ = $timebrakes[$t] - $minutes;
        			        if ($timebrakecalc_ != $timebrakes[$t]) {
        			            $timebrakecalcsum_[$t] = $timebrakes[$t] - $minutes;
        			            echo 'Il vous reste '.$timebrakecalc_. ' minutes de pause.';
        			            echo '<br>';
        			        }
        			    }

        			    $t++;

        			}
        
        echo '<br>';
      
        $mincalcsumavance = array_sum($mincalcavance);
        if($mincalcsumavance == ''){
        	$mincalcsumavance = 0;
        }
        $mincalcsumlate = array_sum($mincalclate);
        if($mincalcsumlate == ''){
        	$mincalcsumlate = 0;
        }
        $mincalcsumsup = array_sum($mincalcsup);
        if($mincalcsumsup == ''){
        	$mincalcsumsup = 0;
        }
        $mincalcsumhatif = array_sum($mincalchatif);
        if($mincalcsumhatif == ''){
        	$mincalcsumhatif = 0;
        }
        $mincalcsumdepase = array_sum($timebrakecalcsum);
        if($mincalcsumdepase == ''){
        	$mincalcsumdepase = 0;
        }
        $mincalcsumreste = array_sum($timebrakecalcsum_);
        if($mincalcsumreste == ''){
        	$mincalcsumreste = 0;
        }
        echo '<div style="display: none;">';
    	    echo 'Nombre total de minute en avance: <span class="mincalcsumavance">' . $mincalcsumavance . '</span>';
    	    echo '<br>';
    	    echo 'Nombre total de minute en retard: <span class="mincalcsumlate">' . $mincalcsumlate . '</span>';
    	    echo '<br>';
    	    echo 'Nombre total de minute en supplémentaire: <span class="mincalcsumsup">'. $mincalcsumsup . '</span>';
    	    echo '<br>';
    	    echo 'Nombre total de minute en depart hâtif: <span class="mincalcsumhatif">'. $mincalcsumhatif . '</span>';
    	    echo '<br>';
    	    echo 'Nombre total de minute de pause de plus: <span class="mincalcsumdepase">'. $mincalcsumdepase . '</span>';
    	    echo '<br>';
    	    echo 'Nombre total de minute de pause de moins: <span class="mincalcsumreste">'. $mincalcsumreste . '</span>';
    	    echo '<br>';
    	    $mincalcsumtotal = ($mincalcsumavance - $mincalcsumlate) + ($mincalcsumsup - $mincalcsumhatif) + ($mincalcsumreste - $mincalcsumdepase);
    	    echo 'Nombre total de points: ';
    	    echo '<span class="mincalcsumtotal">'.$mincalcsumtotal.'</span>';
        echo '</div>';
    
    
    }
    
    add_shortcode('monemploi-score', 'monemploi_score');
    
    ?>