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
			auth_check('litm',32,1);
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
				$tableName = "CalSecurity";
				include($BF ."calendar/includes/overlay.php");
			}

			$q = "SELECT ID,chrKEY,chrSecurity FROM CalSecurity WHERE !bDeleted ORDER BY chrSecurity";
			$results = db_query($q,"getting NSO File Groups");
	
			# The template to use (should be the last thing before the break)
			$title = "Security Groups";						# Page Title
			$directions = 'Choose a Security Group from the list below. Click on the column header to sort the list by that column.';
			$header_title = (access_check(32,2) ? linkto(array('address'=>'add.php','img'=>'/calendar/images/plus_add.png')) : '').' Security Groups <span class="resultsShown">(<span id="resultCount">'.mysqli_num_rows($results).'</span> results)</span>';
			include($BF ."calendar/models/admin.php");		

			break;
		

 		#################################################
		##	Add Page
		#################################################
		case 'add.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',32,2);
			include($BF.'calendar/components/formfields.php');

			if(isset($_POST['chrSecurity'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
?>	<script type='text/javascript'>var page = 'add';</script>
	<script type='text/javascript' src='error_check.js'></script>
	<script type='text/javascript'>
		function globalselect(value) {
			if (value == 1) {
				document.getElementById('securityoptions').style.display='none';
			} else {
				document.getElementById('securityoptions').style.display='';
			} 
		}
	</script>
<?
			}

			# The template to use (should be the last thing before the break)
			$title = "Add Security Group";	# Page Title
			$directions = 'You are adding a Security Group to the database.';
			$header_title = 'Add Security Group';
			include($BF ."calendar/models/admin.php");	
			
			break;


		#################################################
		##	Edit Page
		#################################################
		case 'edit.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',32,3);
			include($BF.'calendar/components/formfields.php');
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Security Group'); } // Check Required Field for Query

			$info = db_query("SELECT ID,chrKEY, chrSecurity, bGlobal
								FROM CalSecurity
								WHERE chrKEY='". $_REQUEST['key'] ."'
			","getting info",1); // Get Info
				
			if($info['ID'] == "") { errorPage('Invalid Security Group'); } // Did we get a result?
			
			if(isset($_POST['chrSecurity'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
			
?>	<script type='text/javascript'>var page = 'edit';</script>
	<script type='text/javascript' src='error_check.js'></script>
	<script type='text/javascript'>
		function globalselect(value) {
			if (value == 1) {
				document.getElementById('securityoptions').style.display='none';
			} else {
				document.getElementById('securityoptions').style.display='';
			} 
		}
	</script>

<?
			}

			# The template to use (should be the last thing before the break)
			$title = "Edit Security Group";	# Page Title
			$directions = 'Please update the information below and press the "Update Information" when you are done making changes.';
			$header_title = 'Edit Security Group: '. $info['chrSecurity'];
			include($BF ."calendar/models/admin.php");		
			
			break;
						
			
		#################################################
		##	Else show Error Page
		#################################################
		default:
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>