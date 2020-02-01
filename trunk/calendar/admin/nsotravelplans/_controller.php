<?
	# This is the BASE FOLDER pointing back to the root directory
	$BF = '../../../';

	$file_name = basename($_SERVER['SCRIPT_NAME']);
    $post_file = '_'.$file_name;

	switch($file_name) {
		#################################################
		##	Index Page
		#################################################
		case 'index.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',24,1);
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
				$tableName = "NSOTravelPlans";
				include($BF ."calendar/includes/overlay.php");
			}

			$q = "SELECT NSOTravelPlans.ID,NSOTravelPlans.chrKEY,NSOTravelPlans.dBegin,NSOTravelPlans.dEnd,NSOs.chrCalendarEvent,chrFirst,chrLast,
				  DATE_FORMAT(NSOTravelPlans.dBegin,'%M %D, %Y') AS dBeginFormated,DATE_FORMAT(NSOTravelPlans.dEnd,'%M %D, %Y') AS dEndFormated
				FROM NSOTravelPlans
				LEFT JOIN NSOs ON NSOs.ID=NSOTravelPlans.idNSO
				JOIN Users ON Users.ID=NSOTravelPlans.idUser
				JOIN CalendarAccess AS CA ON Users.ID=CA.idUser
				WHERE !NSOTravelPlans.bDeleted AND NSOTravelPlans.dEnd >= DATE_FORMAT(now(),'%Y-%m-%d') AND CA.bTravelAccess
				ORDER BY NSOTravelPlans.dBegin,NSOTravelPlans.dEnd
			";
			$results = db_query($q,"getting travel plans");
			
			# The template to use (should be the last thing before the break)
			$title = "NSO/Remodel Travel Plans";						# Page Title
			$directions = 'Choose a NSO/Remodel Travel Plans from the list below. Click on the column header to sort the list by that column.';
			$header_title = (access_check(24,2) ? linkto(array('address'=>'add.php','img'=>'/calendar/images/plus_add.png')) : '')." NSO/Remodel Travel Plans <span class='resultsShown'>(<span id='resultCount'>".mysqli_num_rows($results)."</span> results)</span>";
			include($BF ."calendar/models/admin.php");		

			break;
		

 		#################################################
		##	Add Page
		#################################################
		case 'add.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',24,2);
			include($BF.'calendar/components/formfields.php');

			if(isset($_POST['dBegin'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
?>	<script type='text/javascript'>var page = 'add';</script>
	<script type='text/javascript' src='error_check.js'></script>
<?
			}

			# The template to use (should be the last thing before the break)
			$title = "Add NSO/Remodel Travel Plans";	# Page Title
			$directions = 'You are adding NSO/Remodel Travel Plans to the database.';
			$header_title = 'Add NSO/Remodel Travel Plans';
			include($BF ."calendar/models/admin.php");	
			
			break;


		#################################################
		##	Edit Page
		#################################################
		case 'edit.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',24,3);
			include($BF.'calendar/components/formfields.php');
			
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid NSO Travel Plan'); } // Check Required Field for Query

			$info = db_query("SELECT *
								FROM NSOTravelPlans 
								WHERE chrKEY='". $_REQUEST['key'] ."'
			","getting info",1); // Get Info
				
			if($info['ID'] == "") { errorPage('Invalid NSO Travel Plan'); } // Did we get a result?
			
			if(isset($_POST['dBegin'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
			
?>	<script type='text/javascript'>var page = 'edit';</script>
	<script type='text/javascript' src='error_check.js'></script>
<?
			}

			# The template to use (should be the last thing before the break)
			$title = "Edit NSO/Remodel Travel Plans";	# Page Title
			$directions = 'Please update the information below and press the "Update Information" when you are done making changes.';
			$header_title = 'Edit NSO/Remodel Travel Plans';
			include($BF ."calendar/models/admin.php");		
			
			break;
						
			
		#################################################
		##	Else show Error Page
		#################################################
		default:
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>