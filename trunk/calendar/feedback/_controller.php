<?
	# This is the BASE FOLDER pointing back to the root directory
	$BF = '../../';
	
	$file_name = basename($_SERVER['SCRIPT_NAME']);
    $post_file = '_'.$file_name;

 	switch($file_name) {
		#################################################
		##	Index Page
		#################################################
		case 'index.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			auth_check('litm',6,2);
			include($BF.'calendar/components/formfields.php');

 			if(isset($_POST['txtFeedback'])) { include($post_file); }
			
			# The template to use (should be the last thing before the break)
			$title = "Feedback";	# Page Title
			$header_title = "Feedback from: ".$_SESSION['chrFirst']." ".$_SESSION['chrLast'];
			$directions = 'Please fill out the box below and click on the "Submit Information" button to send your feedback.';
								
			include($BF ."calendar/models/events.php");		
			
			break;

		#################################################
		##	Thank You Page
		#################################################
		case 'thanks.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			auth_check('litm',6,2);
			include($BF.'calendar/components/formfields.php');

			# The template to use (should be the last thing before the break)
			$title = "Thanks for the Feedback";	# Page Title
			$header_title = "Thanks for the Feedback";
								
			include($BF ."calendar/models/events.php");		
			
			break;

		
		#################################################
		##	Else show Error Page
		#################################################
		default:
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>