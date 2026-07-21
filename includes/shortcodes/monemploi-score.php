<?php

    if ( ! defined( 'ABSPATH' ) ) {
    	exit;
    }
    
    
    function monemploi_score() {
    
        $n = 0;
        $l = 0;
        
        for ($m = 0; $m <= 1200; $m++) {
    	    $startofmonths[$m] = date('m/d/Y', mktime(0, 0, 0, $m, 1, '2018'));
    	}
    
    	$user_meta = get_userdata(get_current_user_id());
    	$user_role = $user_meta->roles[0];
    	    	
        if($user_role == 'employeur'){
        	$args = array(
        		 'post_type' => 'horaire',
        		 'post_status'    => array('publish', 'draft', 'future'),
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
        
        if($user_role == 'employeur'){
       		$my_employees = get_user_meta( get_current_user_id(), 'my_employee_key', true);
       	}
        
	if($user_role == 'employer'){
		$x = 0;
		$blogusers = get_users( array( 'role__in' => array( 'employeur' ) ) );
		foreach ( $blogusers as $user ) {
			$userid_employeurs[$x] = $user->ID;
			$x++;
		}
		
		foreach ( $userid_employeurs as $userid_employeur ) {
			$my_employees[$userid_employeur] = get_user_meta( $userid_employeur, 'my_employee_key', true);
		}
		
		foreach ( $my_employees as $key => $value ) {
			if (in_array(get_current_user_id(), $value)) {
				$my_employeur = $key;
			}
		}
	}
        
        $posts = get_posts($args);
	        $x = 0;
	        $u = 0;
        	foreach ( $posts as $post ) {
        	
	        $employee_horaire_select = get_user_meta(get_current_user_id(), 'employee_horaire_select', true);
	        $jobs_horaire_select = get_user_meta(get_current_user_id(), 'jobs_horaire_select', true);
	        
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
            	$job_horaire = get_post_meta( $post->ID, 'job_horaire_key', true );
            	$employee_horaire = get_post_meta( $post->ID, 'employee_horaire_key', true );
    	  
    		if($employee_horaire == get_current_user_id() && $jobs_horaire_select == $job_horaire){
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
    			$fristpushalls[$u]['postid'] = $post->ID;
                	$punchs[$u] =  $push_;
    			$endpushallls[$u]['time'] = $endpush[1];
    			$endpushallls[$u]['inout'] = $endpush[0];
    			$endpushallls[$u]['date'] = $dateendstrtotime;
    			$timebrakes[$u] = $timebrake;
    			$u++;
    		}
    		
    		if($employee_horaire == get_current_user_id() && $jobs_horaire_select == 0){
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
    			$fristpushalls[$u]['postid'] = $post->ID;
                	$punchs[$u] =  $push_;
    			$endpushallls[$u]['time'] = $endpush[1];
    			$endpushallls[$u]['inout'] = $endpush[0];
    			$endpushallls[$u]['date'] = $dateendstrtotime;
    			$timebrakes[$u] = $timebrake;
    			$u++;
    		}
    	
    	}
        	    
        	if($user_role == 'employeur'){
        	
        		$job_horaire = get_post_meta( $post->ID, 'job_horaire_key', true );
            		$employee_horaire = get_post_meta( $post->ID, 'employee_horaire_key', true );
    	      
        	    	if($employee_horaire_select == 0 && $jobs_horaire_select == 0){
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
	    			$fristpushalls[$u]['postid'] = $post->ID;
	                	$punchs[$u] =  $push_;
	    			$endpushallls[$u]['time'] = $endpush[1];
	    			$endpushallls[$u]['inout'] = $endpush[0];
	    			$endpushallls[$u]['date'] = $dateendstrtotime;
	                	$timebrakes[$u] = $timebrake;
	    			$u++;
    	        
    	      		}
    	      		
    	      		if($employee_horaire_select == 0 && $jobs_horaire_select != 0){
    	      			if($jobs_horaire_select == $job_horaire){
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
		    			$fristpushalls[$u]['postid'] = $post->ID;
		                	$punchs[$u] =  $push_;
		    			$endpushallls[$u]['time'] = $endpush[1];
		    			$endpushallls[$u]['inout'] = $endpush[0];
		    			$endpushallls[$u]['date'] = $dateendstrtotime;
		                	$timebrakes[$u] = $timebrake;
		    			$u++;
	    			}
    	        
    	      		}
    	      		
    	      		if($employee_horaire_select != 0 && $jobs_horaire_select != 0){
    	      			if($jobs_horaire_select == $job_horaire && $employee_horaire_select == $employee_horaire){
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
		    			$fristpushalls[$u]['postid'] = $post->ID;
		                	$punchs[$u] =  $push_;
		    			$endpushallls[$u]['time'] = $endpush[1];
		    			$endpushallls[$u]['inout'] = $endpush[0];
		    			$endpushallls[$u]['date'] = $dateendstrtotime;
		                	$timebrakes[$u] = $timebrake;
		    			$u++;
	    			}
    	        
    	      		}
    	      		
    	      		if($employee_horaire_select != 0 && $jobs_horaire_select == 0){
    	      			if($employee_horaire_select == $employee_horaire){
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
		    			$fristpushalls[$u]['postid'] = $post->ID;
		                	$punchs[$u] =  $push_;
		    			$endpushallls[$u]['time'] = $endpush[1];
		    			$endpushallls[$u]['inout'] = $endpush[0];
		    			$endpushallls[$u]['date'] = $dateendstrtotime;
		                	$timebrakes[$u] = $timebrake;
		    			$u++;	    			
	    			}
    	        
    	      		}
    	      		
    	      		if($employee_horaire_select == -2 && $jobs_horaire_select == 0){
    	      				$current_time = null;
		    			$datepickerstarthoraire = null;
		    			$timestarthoraire = null;
		    			$datepickerendhoraire = null;
		    			$timeendhoraire = null; 	
		    			$employee_horaire = null;
		    			$datestartstrtotime = null;
		    			$dateendstrtotime = null;
		                	$timebrake = null;
		    			
		    			$push_ = null;
		    			$employee_horaire = null;
		    			$employee_replace = null;
		    			$dayoff_status = null;
		    			$fristpush = null;
		    			$endpush = null;
		    			$fristpushalls[$u]['time'] = 0;
		    			$fristpushalls[$u]['inout'] = null;
		    			$fristpushalls[$u]['date'] = 0;
		    			$fristpushalls[$u]['userid'] = 0;
		    			$fristpushalls[$u]['postid'] = 0;
		                	$punchs[$u] =  null;
		    			$endpushallls[$u]['time'] = 0;
		    			$endpushallls[$u]['inout'] = null;
		    			$endpushallls[$u]['date'] = 0;
		                	$timebrakes[$u] = null;
		    			$u++;	
    	      		}
    
    		}
    
        }
        
       	echo '<div style="display: float; height: 75px">';
        if($user_role == 'employeur'){
	        $get_args_emploi = array( 
	                'post_type' => 'emploi',
	                'posts_per_page' => -1,
	                'post_status' => array('publish', 'draft', 'future'),
	                'author'        => get_current_user_id(),
	                'orderby' => 'date',
	                'order' => 'DESC'
	        ); 
	}
	if($user_role == 'employer'){
		$get_args_emploi = array( 
	                'post_type' => 'emploi',
	                'posts_per_page' => -1,
	                'post_status' => array('publish'),
	                'author'        =>   $my_employeur,
	                'orderby' => 'date',
	                'order' => 'DESC'
	        ); 
	}

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
                
    	if($user_role == 'employeur'){
    		echo '<select name="employee_horaire_select" id="employee_horaire_select" class="employee_horaire_select" style="float: right;">';
                    	$count_employees = count($my_employees);
                    	if($count_employees <= 0 && $user_role == 'employeur'){
                    		echo '<option value="-2">Pas d&#39;employer</option>';
                    	} else {
                    	        echo '<option value="0">Tous les employers</option>';
	                        foreach($my_employees as $employee){
	                                $user_by_id = get_user_by('id', $employee);
	                                $salary = get_user_meta( $employee, 'salary_key', true);
	                                if($employee_horaire_select === $employee){
	                                    echo '<option value="'.$employee.'" selected>'. $user_by_id->user_nicename .' - ' . $user_by_id->user_firstname . ' ' . $user_by_id->user_lastname . ' - ' . $salary . '$</option>';
	                                } else {
	                                    echo '<option value="'.$employee.'">'. $user_by_id->user_nicename .' - ' . $user_by_id->user_firstname . ' ' . $user_by_id->user_lastname . ' - ' . $salary . '$</option>';
	                                }
	                        }
                        }
    		echo '</select>';
    	}
    	echo '</div>';
        
        sort($fristpushalls);
        sort($endpushallls);
        usort($punchs, function($a, $b) {
            return $a[1] <=> $b[1];
        });
        		
        		$y = 0;
        		$current_time = current_time( 'timestamp' );
        		foreach($startofmonths as $startofmonth) {
	    	    		if(strtotime($startofmonth) <= $current_time && strtotime($startofmonths[$y+1]) > $current_time ) {
	    	    			$currentmonth = $y;	    		
	    	    		}
	    	    		$y++;
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
    			if($_GET['month']){
        			echo date('m/Y', strtotime($startofmonths[$_GET['month']]));
        			echo '<br>';
        		} else {
        			echo date('m/Y', strtotime($startofmonths[$currentmonth_]));
        			echo '<br>';
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
        			        $postid = intval($fristpushall['postid']);
        			        $job_horaire = get_post_meta( $postid, 'job_horaire_key', true );
        			        echo get_the_title($job_horaire);
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
