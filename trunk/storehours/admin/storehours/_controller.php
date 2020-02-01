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
			# Stuff In The Header
			function sith() { 
				global $BF;
				include($BF .'storehours/components/list/sortlistjs.php');
			}
			
			$q = "SELECT RetailStores.ID, RetailStores.chrName,
				  if((SELECT ID FROM StoreHours WHERE StoreHours.idStore=RetailStores.ID LIMIT 1)!='','Complete','Not Complete') as chrComplete
				FROM RetailStores
				ORDER BY chrComplete DESC,RetailStores.chrName";
			$results = db_query($q,"getting Hours");


			# The template to use (should be the last thing before the break)
			$title = "Store Hours";	# Page Title
			$directions = "Choose a Store. Click on the row to view Store hours.";
			$header_title = 'Store Hours <span class="resultsShown">(<span id="resultCount">'.mysqli_num_rows($results).'</span> results)</span>';
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
			
			$q = "SELECT bClosed,idDayOfWeek,tOpening,tClosing
				FROM StoreHours
				WHERE idStore='". $_REQUEST['id'] ."'";
			$results = db_query($q,"getting hours");
			
			$info = db_query("SELECT chrName FROM RetailStores WHERE ID=".$_REQUEST['id'],"",1);

			$title = "Store Hours";	# Page Title
			$header_title = "Store Hours for: ".$info['chrName'];
			$directions = 'These are the default store hours currently defined by the store.';

			# The template to use (should be the last thing before the break)
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
			
			$q = "SELECT RetailStores.ID, RetailStores.chrName, chrStoreNum,
				  GROUP_CONCAT(CONCAT(StoreHours.idDayOfWeek,'|',StoreHours.tOpening,'|',StoreHours.tClosing,'|',StoreHours.bClosed) ORDER BY idDayOfWeek SEPARATOR ',') AS txtTimes
				FROM RetailStores
				JOIN StoreHours ON StoreHours.idStore=RetailStores.ID
				WHERE !RetailStores.bDeleted
				GROUP BY StoreHours.idStore
				ORDER BY RetailStores.chrName";
			$results = db_query($q,"getting Hours");

			break;
			
		#################################################
		##	Else show Error Page
		#################################################
		default:
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>