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
			include($BF .'storehours/_lib.php');
			auth_check('litm','1');
			$Security = db_query("SELECT bStoreHours FROM Users WHERE ID=".$_SESSION['idUser'],".lib File: Get Users Security",1);
			if ($Security['bStoreHours'] == false) {
				header("Location: " . $BF . "noaccess.php");
				die();
			}
			include_once($BF.'storehours/components/formfields.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
				?><script type='text/javascript' src='<?=$BF?>includes/overlays.js'></script><?
				include($BF .'storehours/components/list/sortlistjs.php');
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
				$tableName = "Holidays";
				include($BF ."storehours/includes/overlay.php");
			}

			$q = "SELECT ID,chrKEY,chrHoliday,IF(bVisible,'Visible','Hidden') as bVisible,DATE_FORMAT(dBegin,'%M %D, %Y') as dBegin,DATE_FORMAT(dEnd,'%M %D, %Y') as dEnd
				FROM Holidays
				WHERE !bDeleted 
				ORDER BY chrHoliday";
			$results = db_query($q,"getting Holidays");


			# The template to use (should be the last thing before the break)
			$title = "Holidays";	# Page Title
			$directions = "Choose a holiday from the list below to make edits to it.";
			$header_title = linkto(array('address'=>'add.php','img'=>'/calendar/images/plus_add.png')).' Store Holidays <span class="resultsShown">(<span id="resultCount">'.mysqli_num_rows($results).'</span> results)</span>';
			include($BF ."storehours/models/admin.php");		
			
			break;
			
 		#################################################
		##	Add Page
		#################################################
		case 'add.php':
			# Adding in the lib file
			include($BF .'storehours/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1');
			$Security = db_query("SELECT bStoreHours FROM Users WHERE ID=".$_SESSION['idUser'],".lib File: Get Users Security",1);
			if ($Security['bStoreHours'] == false) {
				header("Location: " . $BF . "noaccess.php");
				die();
			}
			include($BF.'storehours/components/formfields.php');

			if(isset($_POST['chrHoliday'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
?>	<script type='text/javascript'>var page = 'add';</script>
	<script type='text/javascript' src='error_check.js'></script>
<?
			}

			# The template to use (should be the last thing before the break)
			$title = "Add Store Holiday";	# Page Title
			$directions = 'You are adding a Store Holiday to the database.';
			$header_title = 'Add Store Holiday';
			include($BF ."storehours/models/admin.php");	
			
			break;


		#################################################
		##	Edit Page
		#################################################
		case 'edit.php':
			# Adding in the lib file
			include($BF .'storehours/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1');
			$Security = db_query("SELECT bStoreHours FROM Users WHERE ID=".$_SESSION['idUser'],".lib File: Get Users Security",1);
			if ($Security['bStoreHours'] == false) {
				header("Location: " . $BF . "noaccess.php");
				die();
			}
			include($BF.'storehours/components/formfields.php');
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid File Group'); } // Check Required Field for Query

			$info = db_query("SELECT ID,chrKEY,chrHoliday,bVisible,DATE_FORMAT(dBegin,'%m/%d/%Y') as dBegin,DATE_FORMAT(dEnd,'%m/%d/%Y') as dEnd
								FROM Holidays
								WHERE chrKEY='". $_REQUEST['key'] ."'
			","getting info",1); // Get Info
				
			if($info['ID'] == "") { errorPage('Invalid File Group'); } // Did we get a result?
			
			if(isset($_POST['chrHoliday'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
			
?>	<script type='text/javascript'>var page = 'edit';</script>
	<script type='text/javascript' src='error_check.js'></script>
<?
			}

			# The template to use (should be the last thing before the break)
			$title = "Edit Store Holiday";	# Page Title
			$directions = 'Please update the information below and press the "Update Information" when you are done making changes.';
			$header_title = 'Edit Store Holiday: '. $info['chrHoliday'];
			include($BF ."storehours/models/admin.php");		
			
			break;
		#################################################
		##	Download Excel
		#################################################
		case 'excel_export.php':
			# Adding in the lib file
			$NON_HTML_PAGE=1;
			include($BF .'storehours/_lib.php');
			auth_check('litm','1');
			$Security = db_query("SELECT bStoreHours FROM Users WHERE ID=".$_SESSION['idUser'],".lib File: Get Users Security",1);
			if ($Security['bStoreHours'] == false) {
				header("Location: " . $BF . "noaccess.php");
				die();
			}
			# Stuff In The Header
			function sith() { 
				global $BF;
			}
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Holiday'); } // Check Required Field for Query
			
			$q = "SELECT ID,chrHoliday,dBegin,dEnd FROM Holidays WHERE !bDeleted AND chrKEY = '".$_REQUEST['key']."'
				";
			$info = db_query($q,"getting Holiday",1);
			
			if($info['ID'] == "") { errorPage('Invalid Holiday'); } // Did we get a result?
			
			
			break;
			
			
			
		#################################################
		##	Else show Error Page
		#################################################
		default:
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>