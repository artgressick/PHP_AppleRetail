<?
	# This is the BASE FOLDER pointing back to the root directory
	$BF = '../../../';

	preg_match('/(\w)+\.php$/',$_SERVER['SCRIPT_NAME'],$file_name);
    $post_file = '_'.$file_name[0];

	switch($file_name[0]) {
 		#################################################
		##	Add Page index
		#################################################
		case 'index.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',9,2);
			include($BF.'calendar/components/formfields.php');

			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid NSO Event'); } // Check Required Field for Query

			$info = db_query("SELECT ID, chrKEY, chrCalendarEvent
								FROM NSOs 
								WHERE chrKEY='". $_REQUEST['key'] ."'
			","getting info",1); // Get Info
				
			if($info['ID'] == "") { errorPage('Invalid NSO Event'); } // Did we get a result?			
			
			if(isset($_POST['chrFileTitle'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
?>	<script type='text/javascript'>var page = 'add';</script>
	<script type='text/javascript' src='error_check.js'></script>
<?
			}

			# The template to use (should be the last thing before the break)
			$title = "Add NSO Picture";	# Page Title
			$directions = 'You are adding a NSO Picture to the database.';
			$header_title = 'Add NSO Picture to: '.$info['chrCalendarEvent'];
			include($BF ."calendar/models/nso.php");	
			
			break;


		#################################################
		##	Edit Page
		#################################################
		case 'edit.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',9,3);
			include($BF.'calendar/components/formfields.php');
			
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid NSO Picture'); } // Check Required Field for Query

			$info = db_query("SELECT CalendarFiles.*, NSOs.chrKEY AS chrEventKEY
								FROM CalendarFiles
								JOIN NSOs ON CalendarFiles.idNSO=NSOs.ID
								WHERE CalendarFiles.idCalendarFileType=1 AND CalendarFiles.chrKEY='". $_REQUEST['key'] ."'
			","getting info",1); // Get Info
				
			#if($info['ID'] == "") { errorPage('Invalid NSO Picture'); } // Did we get a result?
			
			if(isset($_POST['chrFileTitle'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
			
?>	<script type='text/javascript'>var page = 'edit';</script>
	<script type='text/javascript' src='error_check.js'></script>
<?
			}

			# The template to use (should be the last thing before the break)
			$title = "Edit NSO Picture";	# Page Title
			$directions = 'Please update the information below and press the "Update Information" when you are done making changes.';
			$header_title = 'Edit NSO Picture: '.($info['chrFileTitle'] != '' ? $info['chrFileTitle'] : $info['chrCalendarFile']);
			include($BF ."calendar/models/nso.php");		
			
			break;
						
			
		#################################################
		##	Else show Error Page
		#################################################
		default:
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>