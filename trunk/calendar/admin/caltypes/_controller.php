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
			auth_check('litm',15,1);
			include($BF.'calendar/components/formfields.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
				?><script type='text/javascript' src='<?=$BF?>includes/overlaysnew.js'></script>
				<script type='text/javascript'>
					
				</script><?
				include($BF .'calendar/components/list/sortlistjs.php');
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
				$tableName = "CalendarTypes";
				include($BF ."calendar/includes/overlay.php");
			}

			$q = "SELECT ID,chrKEY,chrCalendarType FROM CalendarTypes WHERE !bDeleted ORDER BY chrCalendarType";
			$results = db_query($q,"getting Calendar Types");
	
			# The template to use (should be the last thing before the break)
			$title = "Calendar Types";						# Page Title
			$directions = 'Choose a Calendar Type from the list below. Click on the column header to sort the list by that column.';
			$header_title = (access_check(15,2) ? linkto(array('address'=>'add.php','img'=>'/calendar/images/plus_add.png')):'').' Calendar Types <span class="resultsShown">(<span id="resultCount">'.mysqli_num_rows($results).'</span> results)</span>';
			include($BF ."calendar/models/admin.php");		

			break;
		

 		#################################################
		##	Add Page
		#################################################
		case 'add.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',15,2);
			include($BF.'calendar/components/formfields.php');

			if(isset($_POST['chrCalendarType'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
?>	<script type='text/javascript'>var page = 'add';</script>
	<script type='text/javascript' src='error_check.js'></script>
	<script type="text/javascript" src="colorfind.js"></script>
<?

			}

			# The template to use (should be the last thing before the break)
			$title = "Add Calendar Type";	# Page Title
			$directions = 'You are adding a Calendar Type to the database.';
			$header_title = 'Add Calendar Type';
			include($BF ."calendar/models/admin.php");	
			
			break;


		#################################################
		##	Edit Page
		#################################################
		case 'edit.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',15,3);
			include($BF.'calendar/components/formfields.php');
			
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Calendar Type'); } // Check Required Field for Query

			$info = db_query("SELECT *
								FROM CalendarTypes
								WHERE chrKEY='". $_REQUEST['key'] ."'
			","getting info",1); // Get Info
				
			if($info['ID'] == "") { errorPage('Invalid Calendar Type'); } // Did we get a result?
			
			if(isset($_POST['chrCalendarType'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
			
?>	<script type='text/javascript'>var page = 'edit';</script>
	<script type='text/javascript' src='error_check.js'></script>
	<script type="text/javascript" src="colorfind.js"></script>
<?
			}

			# The template to use (should be the last thing before the break)
			$title = "Edit Calendar Type";	# Page Title
			$directions = 'Please update the information below and press the "Update Information" when you are done making changes.';
			$header_title = 'Edit Calendar Type: '. $info['chrCalendarType'];
			include($BF ."calendar/models/admin.php");		
			
			break;
						
			
		#################################################
		##	Else show Error Page
		#################################################
		default:
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>