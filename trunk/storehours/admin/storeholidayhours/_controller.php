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
				include($BF .'storehours/components/list/sortlistjs.php');
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
			}
			$filter = "";
			if(isset($_REQUEST['idStore']) && is_numeric($_REQUEST['idStore'])) {
				$filter .= " AND SHS.idStore='".($_REQUEST['idStore'])."' ";
			} else { $_REQUEST['idStore'] = ''; }

			if(isset($_REQUEST['idHoliday']) && is_numeric($_REQUEST['idHoliday'])) {
				$filter .= " AND SHS.idHoliday='".($_REQUEST['idHoliday'])."' ";
			} else { $_REQUEST['idHoliday'] = ''; }
			
			//This next line is to ensure they select something to filter by first, to narrow down the list.
			if($filter == '') { $filter = ' AND 1=0 '; }
			
			$q = "SELECT SHS.ID,SHS.chrKEY,RS.chrName as chrStore, H.chrHoliday, SHS.dtCreated
				FROM StoreHoursSpecial AS SHS
				JOIN RetailStores AS RS ON SHS.idStore=RS.ID
				JOIN Holidays AS H ON SHS.idHoliday=H.ID
				WHERE !H.bDeleted AND !RS.bDeleted ".$filter."
				GROUP BY SHS.idHoliday
				ORDER BY chrStore, chrHoliday";
			$results = db_query($q,"getting Holidays");


			# The template to use (should be the last thing before the break)
			$title = "Store Holiday Hours";	# Page Title
			$directions = "Choose a Store or Holiday to view the list. Click on row to view Store hours for that Holiday.";
			$header_title = 'Store Holiday Hours <span class="resultsShown">(<span id="resultCount">'.mysqli_num_rows($results).'</span> results)</span>';
			include($BF ."storehours/models/admin.php");		
			
			break;
			
 		#################################################
		##	View Page
		#################################################
		case 'view.php':
			$title = "Holiday Hours";	# Page Title
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
			}
			
			$q = "SELECT SHS.ID, RS.chrName as chrStore, H.chrHoliday,H.dBegin,H.dEnd,SHS.idStore,SHS.idHoliday
				FROM StoreHoursSpecial AS SHS
				JOIN RetailStores AS RS ON SHS.idStore=RS.ID
				JOIN Holidays AS H ON SHS.idHoliday=H.ID
				WHERE !RS.bDeleted AND !H.bDeleted AND SHS.chrKEY='". $_REQUEST['key'] ."'";
			$info = db_query($q,"getting Holidays",1);

//			if($info['ID'] == "") { errorPage('Invalid Selection'); } // Did we get a result?
			
			if(count($_POST)) { include($post_file); }

			$resultHours = db_query("SELECT * FROM StoreHoursSpecial WHERE idStore=".$info['idStore']." AND idHoliday=".$info['idHoliday']." ORDER BY dDate","Getting Store Hours");
			
			
			$title = "Holiday Hours";	# Page Title
			$header_title = "Holiday Hours for store ".$info['chrStore'].", Holiday: ".$info['chrHoliday'];
			$directions = '';

			# The template to use (should be the last thing before the break)
			include($BF ."storehours/models/admin.php");		
			
			break;
		
		#################################################
		##	Else show Error Page
		#################################################
		default:
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>