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
			auth_check('litm',28,3);
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
				$tableName = "SSs";
				include($BF ."calendar/includes/overlay.php");
			}

			$q = "SELECT SSs.ID,SSs.chrKEY,SSs.dtCreated,CONCAT(chrLast,', ',chrFirst) as chrName
				FROM SSs
				JOIN Users ON Users.ID=SSs.idUser
				WHERE !SSs.bDeleted
				ORDER BY ID DESC";
			$results = db_query($q,"getting NSO File Groups");
	
			# The template to use (should be the last thing before the break)
			$title = "Site Surveys";						# Page Title
			$directions = 'Choose an Site Survey from the list below. Click on the column header to sort the list by that column.';
			$header_title = 'Site Surveys <span class="resultsShown">(<span id="resultCount">'.mysqli_num_rows($results).'</span> results)</span>';
			include($BF ."calendar/models/admin.php");		

			break;
		

		#################################################
		##	Edit Page
		#################################################
		case 'edit.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',28,3);
			include($BF.'calendar/components/formfields.php');
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Site Survey'); } // Check Required Field for Query
			
			if(count($_POST)) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
			
?>	<script type='text/javascript'>var page = 'edit';</script>
	<script type='text/javascript' src='error_check.js'></script>
	<script type='text/javascript' src='autogen.js'></script>
	<link href="<?=$BF?>calendar/includes/evals.css" rel="stylesheet" type="text/css" />	
<?
			}

			# The template to use (should be the last thing before the break)
			$title = "Edit Site Survey";	# Page Title
			$directions = 'Please update the information below and press the "Update Information" when you are done making changes.';
			$header_title = 'Edit Site Survey';
			include($BF ."calendar/models/admin.php");		
			
			break;
						
			
		#################################################
		##	Else show Error Page
		#################################################
		default:
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>