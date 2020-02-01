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
			auth_check('litm',30,1);
			include($BF.'calendar/components/formfields.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
				include($BF .'calendar/components/list/sortlistjs.php');
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
			}

			$q = "SELECT ID,chrKEY,chrField
			 FROM NSOPeopleAssoc
			 ORDER BY ID";
			$results = db_query($q,"getting People Association Fields");
			
			# The template to use (should be the last thing before the break)
			$title = "Event People Associations";						# Page Title
			$directions = 'Choose a Event People Association from the list below. Click on the column header to sort the list by that column.';
			$header_title = "Event People Association";
			include($BF ."calendar/models/admin.php");		

			break;
		

		#################################################
		##	Edit Page
		#################################################
		case 'edit.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',30,1);
			include($BF.'calendar/components/formfields.php');
			
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Association'); } // Check Required Field for Query

			$info = db_query("SELECT *
								FROM NSOPeopleAssoc 
								WHERE chrKEY='". $_REQUEST['key'] ."'
			","getting info",1); // Get Info
				
			if($info['ID'] == "") { errorPage('Invalid Association'); } // Did we get a result?
			if($info['txtUsers'] != '') {
				$info['idUsers'] = explode(',',$info['txtUsers']);
			} else { $info['idUsers'] = array(); }
			
			if(count($_POST)) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
			
?>	<script type='text/javascript'>var page = 'edit';</script>
	<script type='text/javascript' src='error_check.js'></script>
<?
			}

			# The template to use (should be the last thing before the break)
			$title = "Edit Event People Association";	# Page Title
			$directions = 'Please select the people you would like to be shown in the Add/Edit Event Pages for "'.$info['chrField'].'" and press the "Update Information" when finished.';
			$header_title = 'Edit Event People Association: '.$info['chrField'];
			include($BF ."calendar/models/admin.php");		
			
			break;
						
			
		#################################################
		##	Else show Error Page
		#################################################
		default:
			include($BF .'calendar/_lib.php');
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>