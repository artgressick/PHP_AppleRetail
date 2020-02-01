<?
	# This is the BASE FOLDER pointing back to the root directory
	$BF = '../../../';

	preg_match('/(\w)+\.php$/',$_SERVER['SCRIPT_NAME'],$file_name);
    $post_file = '_'.$file_name[0];

	switch($file_name[0]) {
		#################################################
		##	Index Page
		#################################################
		case 'index.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',19,1);
			include($BF.'calendar/components/formfields.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
				?><script type='text/javascript' src='<?=$BF?>includes/overlaysnew.js'></script><?
				include($BF .'calendar/components/list/sortlistjs.php');
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
				$tableName = "NSOFeedback";
				include($BF ."calendar/includes/overlay.php");
			}

			$q = "SELECT NSOFeedback.ID,NSOFeedback.chrKEY,chrFirst,chrLast,txtFeedback,CONCAT('<span style=\'display:none;\'>',NSOFeedback.dtCreated,'</span>',DATE_FORMAT(NSOFeedback.dtCreated,'%M %D, %Y - %l:%i %p')) as dtCreated
				FROM NSOFeedback 
				JOIN Users ON Users.ID=NSOFeedback.idUser
				WHERE !NSOFeedback.bDeleted 
				ORDER BY dtCreated";
			$results = db_query($q,"getting NSO Feedback");
			
			# The template to use (should be the last thing before the break)
			$custom_directions = true; 		# In the need to have filters, set this option and write out the custom filters on the page
			$title = "NSO/Remodel Feedback";						# Page Title
			$directions = 'Choose NSO/Remodel Feedback from the list below. Click on the column header to sort that column.';
			$header_title = "NSO/Remodel Tasks <span class='resultsShown'>(<span id='resultCount'>".mysqli_num_rows($results)."</span> results)</span>";
			include($BF ."calendar/models/admin.php");		
			break;
		

 		#################################################
		##	View Feedback Page
		#################################################
		case 'view.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',19,1);
			include($BF.'calendar/components/formfields.php');

			$info = db_query("SELECT txtFeedback,chrFirst,chrLast,DATE_FORMAT(NSOFeedback.dtCreated,'%M %D, %Y - %l:%i %p') as dtCreated
				FROM NSOFeedback
				JOIN Users ON Users.ID=NSOFeedback.idUser
				WHERE NSOFeedback.chrKEY='".$_REQUEST['key'] ."'"
			,"getting feedback",1);

			# The template to use (should be the last thing before the break)
			$title = "View NSO/Remodel Feedback";	# Page Title
			$directions = 'You are viewing NSO/Remodel Feedback from the database.';
			$header_title = 'View NSO/Remodel Feedback';
			include($BF ."calendar/models/admin.php");	
			
			break;

			
		#################################################
		##	Else show Error Page
		#################################################
		default:
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>