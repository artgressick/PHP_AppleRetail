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
			auth_check('litm',21,1);
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
				$tableName = "NSOTasks";
				include($BF ."calendar/includes/overlay.php");
			}

			$q = "SELECT NSOTasks.ID,NSOTasks.chrKEY,NSOTasks.chrNSOTask,chrNSOType,intDateOffset,dOrder
				FROM NSOTasks 
				JOIN NSOTypes ON NSOTypes.ID=NSOTasks.idNSOType
				WHERE !NSOTasks.bDeleted 
				". (isset($_REQUEST['idNSOType']) && is_numeric($_REQUEST['idNSOType']) ? ' AND NSOTypes.ID='.$_REQUEST['idNSOType'] : '') ."
				ORDER BY dOrder,intDateOffset,chrNSOTask";
			$results = db_query($q,"getting NSO Tasks");

			if(isset($_POST['submit'])) { include($post_file); }
						
			# The template to use (should be the last thing before the break)
			$custom_directions = true; 		# In the need to have filters, set this option and write out the custom filters on the page
			$title = "Calendar NSO/Remodel Tasks";						# Page Title
			$directions = 'Choose a NSO/Remodel Task from the list below. Click on the column header to sort that column.';
			$header_title = (access_check(21,2) ? linkto(array('address'=>'add.php','img'=>'/calendar/images/plus_add.png')) : '')." NSO/Remodel Tasks <span class='resultsShown'>(<span id='resultCount'>".mysqli_num_rows($results)."</span> results)</span>";
			include($BF ."calendar/models/admin.php");		

			break;
		

 		#################################################
		##	Add Page
		#################################################
		case 'add.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',21,2);
			include($BF.'calendar/components/formfields.php');

			if(isset($_POST['chrNSOTask'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
?>	<script type='text/javascript'>var page = 'add';</script>
	<script type='text/javascript' src='error_check.js'></script>
<?
			}

			# The template to use (should be the last thing before the break)
			$title = "Add NSO/Remodel Task";	# Page Title
			$directions = 'You are adding a NSO/Remodel Task to the database.';
			$header_title = 'Add NSO/Remodel Task';
			include($BF ."calendar/models/admin.php");	
			
			break;


		#################################################
		##	Edit Page
		#################################################
		case 'edit.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',21,3);
			include($BF.'calendar/components/formfields.php');
			
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid NSO Task'); } // Check Required Field for Query

			$info = db_query("SELECT *
								FROM NSOTasks
								WHERE chrKEY='". $_REQUEST['key'] ."'
			","getting info",1); // Get Info
				
			if($info['ID'] == "") { errorPage('Invalid NSO Task'); } // Did we get a result?
			
			if(isset($_POST['chrNSOTask'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
			
?>	<script type='text/javascript'>var page = 'edit';</script>
	<script type='text/javascript' src='error_check.js'></script>
<?
			}

			# The template to use (should be the last thing before the break)
			$title = "Edit NSO/Remodel Task";	# Page Title
			$directions = 'Please update the information below and press the "Update Information" when you are done making changes.';
			$header_title = 'Edit NOS/Remodel Task: '.$info['chrNSOTask'];
			include($BF ."calendar/models/admin.php");		
			
			break;
						
			
		#################################################
		##	Else show Error Page
		#################################################
		default:
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>