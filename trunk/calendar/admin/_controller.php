<?
	# This is the BASE FOLDER pointing back to the root directory
	$BF = '../../';
	
	preg_match('/(\w)+\.php$/',$_SERVER['SCRIPT_NAME'],$file_name);
    $post_file = '_'.$file_name[0];

	switch($file_name[0]) {
		#################################################
		##	Index Page
		#################################################
		case 'index.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			auth_check('litm',13,1);
			include_once($BF.'calendar/components/formfields.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
			}

			# The template to use (should be the last thing before the break)
			$title = "NSO Calendar";	# Page Title
			$directions = "Choose from one of the links on the left to continue.";
			$header_title = "Calendar Admin Home";
			include($BF ."calendar/models/admin.php");		
			
			break;
		#################################################
		##	Else show Error Page
		#################################################
		default:
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>