<?
	# This is the BASE FOLDER pointing back to the root directory
	$BF = '../';
	
	$file_name = basename($_SERVER['SCRIPT_NAME']);
    $post_file = '_'.$file_name;

 	switch($file_name) {
		#################################################
		##	Index Page
		#################################################
		case 'index.php':
			$title = "NSO Calendar";	# Page Title
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			auth_check('litm',1,1);
 			
			# Stuff In The Header
			function sith() { 
				global $BF;
			}

			# The template to use (should be the last thing before the break)
			include($BF ."calendar/models/template.php");		
			
			break;
		#################################################
		## First Login Page
		#################################################
		case 'firstlogin.php':
			$title = "NSO Calendar";	# Page Title
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			auth_check('litm',1,1);
 			
			# Stuff In The Header
			function sith() { 
				global $BF;
			}

			# The template to use (should be the last thing before the break)
			include($BF ."calendar/models/template.php");		
			
			break;

			
		#################################################
		##	Day Grid
		#################################################
		case 'day.php':
			if(!isset($_REQUEST['dBegin']) || $_REQUEST['dBegin'] == '') { 
				header("Location: day.php?dBegin=".idate('Y').(idate('m') < 10 ? '0'.idate('m') : idate('m')).(idate('d') < 10 ? '0'.idate('d') : idate('d')));	
				die(); 
			}			

			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			auth_check('litm',10,1);
			
			if(isset($_REQUEST['type'])) {
				$_SESSION['CalendarType'] = $_REQUEST['type'];
			}
			
			include_once($BF.'calendar/components/formfields.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
?>				<link href="<?=$BF?>calendar/includes/calendar.css" rel="stylesheet" type="text/css" /> <?
			}

			# The template to use (should be the last thing before the break)
			$title = "Day Grid";	# Page Title
			$display_date = date('l, F jS, Y',strtotime($_REQUEST['dBegin']));
			include($BF ."calendar/models/".(isset($_SESSION['CalendarType']) && $_SESSION['CalendarType'] == 'travel' ? "travel" : "cal")."grid.php");		
			
			break;			

		#################################################
		##	Week Grid
		#################################################
		case 'week.php':
			#header("Location: ". $BF ."calendar/"); die();

			if(!isset($_REQUEST['dBegin']) || $_REQUEST['dBegin'] == '') { 
				header("Location: week.php?dBegin=".idate('Y').(idate('m') < 10 ? '0'.idate('m') : idate('m')).(idate('d') < 10 ? '0'.idate('d') : idate('d')));	
				die(); 
			}			

			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			auth_check('litm',11,1);
			
			if(isset($_REQUEST['type'])) {
				$_SESSION['CalendarType'] = $_REQUEST['type'];
			}
			
			include_once($BF.'calendar/components/formfields.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
?>				<link href="<?=$BF?>calendar/includes/calendar.css" rel="stylesheet" type="text/css" /> <?
			}

			# The template to use (should be the last thing before the break)
			$title = "Week Grid";	# Page Title
			$tmp = idate('w',strtotime($_REQUEST['dBegin']));
			$fdow = date('Y-m-d',strtotime($_REQUEST['dBegin']." - ". $tmp ." days"));	# First day of week - sunday
			$ldow = date('Y-m-d',strtotime($fdow." + 6 days"));						 	# Last day of week - saturday

			$display_date = date('F jS, Y',strtotime($fdow)).' - '.date('F jS, Y',strtotime($ldow));
			include($BF ."calendar/models/".(isset($_SESSION['CalendarType']) && $_SESSION['CalendarType'] == 'travel' ? "travel" : "cal")."grid.php");
			
			break;			

		#################################################
		##	Month Grid
		#################################################
		case 'month.php':

			if(!isset($_REQUEST['dBegin']) || $_REQUEST['dBegin'] == '') { 
				header("Location: month.php?dBegin=".idate('Y').(idate('m') < 10 ? '0'.idate('m') : idate('m')).(idate('d') < 10 ? '0'.idate('d') : idate('d')));	
				die(); 
			}			
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			auth_check('litm',33,1);
			
			if(isset($_REQUEST['type'])) {
				$_SESSION['CalendarType'] = $_REQUEST['type'];
			}
			
			
			include_once($BF.'calendar/components/formfields.php');
			# Stuff In The Header
			function sith() { 
				global $BF;
?>				<link href="<?=$BF?>calendar/includes/calendar.css" rel="stylesheet" type="text/css" /> <?
			}

			# The template to use (should be the last thing before the break)
			$title = "Month Grid";	# Page Title
			$display_date = date('F Y',strtotime($_REQUEST['dBegin']));
			include($BF ."calendar/models/".(isset($_SESSION['CalendarType']) && $_SESSION['CalendarType'] == 'travel' ? "travel" : "cal")."grid.php");	
			
			break;			
		#################################################
		##	Year Grid
		#################################################
		case 'year.php':

			if(!isset($_REQUEST['dBegin']) || $_REQUEST['dBegin'] == '') { 
				header("Location: year.php?dBegin=".idate('Y').(idate('m') < 10 ? '0'.idate('m') : idate('m')).(idate('d') < 10 ? '0'.idate('d') : idate('d')));	
				die(); 
			}			

			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			auth_check('litm',12,1);
			$_SESSION['CalendarType'] = 'nso';
			include_once($BF.'calendar/components/formfields.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
?>
		<link href="<?=$BF?>calendar/includes/calendar.css" rel="stylesheet" type="text/css" /> 
<?
			}

			# The template to use (should be the last thing before the break)
			$title = "Year Grid";	# Page Title
			$display_date = date('Y',strtotime($_REQUEST['dBegin']));	
			include($BF ."calendar/models/calgrid.php");	
			
			break;			

		#################################################
		##	Travel Grid
		#################################################
		case 'travel.php':
			#header("Location: ". $BF ."calendar/"); die();

			if(!isset($_REQUEST['dBegin']) || $_REQUEST['dBegin'] == '') { 
				header("Location: week.php?dBegin=".idate('Y').(idate('m') < 10 ? '0'.idate('m') : idate('m')).(idate('d') < 10 ? '0'.idate('d') : idate('d')));	
				die(); 
			}			

			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			auth_check('litm','1');
			include_once($BF.'calendar/components/formfields.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
?>				<link href="<?=$BF?>calendar/includes/calendar.css" rel="stylesheet" type="text/css" /> <?
			}

			# The template to use (should be the last thing before the break)
			$title = "Travel Grid";	# Page Title
			$tmp = idate('w',strtotime($_REQUEST['dBegin']));
			$fdow = date('Y-m-d',strtotime($_REQUEST['dBegin']." - ". $tmp ." days"));	# First day of week - sunday
			$ldow = date('Y-m-d',strtotime($fdow." + 6 days"));						 	# Last day of week - saturday

			$display_date = date('F jS, Y',strtotime($fdow)).' - '.date('F jS, Y',strtotime($ldow));
			include($BF ."calendar/models/calgrid.php");		
			
			break;			

		#################################################
		##	viewtravel Page
		#################################################
		case 'viewtravel.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',1,1);
			include($BF.'calendar/components/formfields.php');
			
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid NSO Travel Plan'); } // Check Required Field for Query

			$info = db_query("SELECT NSOTravelPlans.*, Users.chrFirst, Users.chrLast
								FROM NSOTravelPlans 
								JOIN Users ON NSOTravelPlans.idUser=Users.ID
								WHERE NSOTravelPlans.chrKEY='". $_REQUEST['key'] ."'
			","getting info",1); // Get Info
				
			if($info['ID'] == "") { errorPage('Invalid NSO Travel Plan'); } // Did we get a result?
			

			# Stuff In The Header
			function sith() { 
				global $BF;
			}

			# The template to use (should be the last thing before the break)
			$title = "View NSO/Remodel Travel Plans";	# Page Title
			$directions = 'You are viewing Travel Plans for '.$info['chrFirst'].' '.$info['chrLast'];
			$header_title = 'View NSO/Remodel Travel Plans';
			include($BF ."calendar/models/template.php");		
			
			break;				
		#################################################
		##	Log Out Page
		#################################################
		case 'logout.php':
			$title = "Logged Off";	# Page Title
			# Adding in the lib file
			include($BF .'calendar/_lib.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
			}

			# The template to use (should be the last thing before the break)
			include($BF ."calendar/models/template.php");		
			
			break;
		#################################################
		##	Error Page
		#################################################
		case 'error.php':
			$title = "Error Page";	# Page Title
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			include_once($BF.'calendar/components/formfields.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
			}

			# The template to use (should be the last thing before the break)
			include($BF ."calendar/models/template.php");		
			
			break;
		#################################################
		##	No Access Page
		#################################################
		case 'noaccess.php':
			$title = "Access Denied!";	# Page Title
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			include_once($BF.'calendar/components/formfields.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
			}

			# The template to use (should be the last thing before the break)
			include($BF ."calendar/models/template.php");		
			
			break;

		#################################################
		##	Survey Page
		#################################################
		case 'sitesurvey.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2');
			include($BF.'calendar/components/formfields.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
				include($BF .'calendar/components/list/sortlistjs.php');
				include($BF .'calendar/components/miniPopup.php');
?>	<script type='text/javascript' src='<?=$BF?>includes/overlaysnew.js'></script>
	<script type='text/javascript' src='<?=$BF?>includes/showHide.js'></script>
	<style type='text/css'>
		.headerBlock { background: #ccc; width: 150px; padding: 2px 4px; }
		.infoBlock { width: 300px; padding: 2px 4px; }
		.showHideTitle { font-size: 14px; background: #99C0DF; padding: 5px 10px; margin-top: 10px; cursor: pointer; }
		.showHideBody { padding: 5px; border: 1px solid #99C0DF; }
	</style>
<?
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
				$tableName = "";
				$postType = 'permDelete';
				include($BF ."calendar/includes/overlay.php");
			}


			# Check for KEY, if not Error, Get $info, Error if no results
/*			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid NSO Calendar Event'); }

			$info = db_query("SELECT CalendarEvents.ID,CalendarEvents.chrKEY,chrCalendarEvent,dBegin,
					RetailStores.chrName as chrStore,NSOTypes.chrNSOType,
					(SELECT DATE_FORMAT(MAX(CE.dEnd),'%m/%d/%Y') FROM CalendarEvents as CE WHERE CE.chrSeries=CalendarEvents.chrSeries) as dEnd
				FROM CalendarEvents
				JOIN RetailStores ON RetailStores.ID=CalendarEvents.idStore
				JOIN NSOTypes ON NSOTypes.ID=CalendarEvents.idNSOType
				WHERE CalendarEvents.chrKEY='".$_REQUEST['key'] ."'"
			,'getting calendar event',1);
				
			if($info['ID'] == "") { errorPage('Invalid NSO Calendar Event'); } // Did we get a result?
*/
			# The template to use (should be the last thing before the break)
			$title = "Site Survey: ". $info['chrCalendarEvent'];	# Page Title
			$directions = 'This is the site survey page for this Remodel/NSO.';
			$header_title = 'Site Survey: '. $info['chrCalendarEvent'];
			include($BF ."calendar/models/nso.php");	
			
			break;
			
		#################################################
		##	NSO Evaluation Page
		#################################################
		case 'eval.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2');
			include($BF.'calendar/components/formfields.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
				include($BF .'calendar/components/list/sortlistjs.php');
				include($BF .'calendar/components/miniPopup.php');
?>	<script type='text/javascript' src='<?=$BF?>includes/overlaysnew.js'></script>
	<script type='text/javascript' src='<?=$BF?>includes/showHide.js'></script>
	<style type='text/css'>
		.headerBlock { background: #ccc; width: 150px; padding: 2px 4px; }
		.infoBlock { width: 300px; padding: 2px 4px; }
		.showHideTitle { font-size: 14px; background: #99C0DF; padding: 5px 10px; margin-top: 10px; cursor: pointer; }
		.showHideBody { padding: 5px; border: 1px solid #99C0DF; }
	</style>
<?
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
				$tableName = "";
				$postType = 'permDelete';
				include($BF ."calendar/includes/overlay.php");
			}


			# Check for KEY, if not Error, Get $info, Error if no results
/*			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid NSO Calendar Event'); }

			$info = db_query("SELECT CalendarEvents.ID,CalendarEvents.chrKEY,chrCalendarEvent,dBegin,
					RetailStores.chrName as chrStore,NSOTypes.chrNSOType,
					(SELECT DATE_FORMAT(MAX(CE.dEnd),'%m/%d/%Y') FROM CalendarEvents as CE WHERE CE.chrSeries=CalendarEvents.chrSeries) as dEnd
				FROM CalendarEvents
				JOIN RetailStores ON RetailStores.ID=CalendarEvents.idStore
				JOIN NSOTypes ON NSOTypes.ID=CalendarEvents.idNSOType
				WHERE CalendarEvents.chrKEY='".$_REQUEST['key'] ."'"
			,'getting calendar event',1);
				
			if($info['ID'] == "") { errorPage('Invalid NSO Calendar Event'); } // Did we get a result?
*/
			# The template to use (should be the last thing before the break)
			$title = "Site Evaluation: ". $info['chrCalendarEvent'];	# Page Title
			$directions = 'This is the site evaluation page for this Remodel/NSO.';
			$header_title = 'Site Evaluation: '. $info['chrCalendarEvent'];
			include($BF ."calendar/models/nso.php");	
			
			break;
			
		#################################################
		##	Make iCal Page
		#################################################
		case 'makeical.php':
			$title = "iCal Subscribe";
			include($BF .'calendar/_lib.php');
			auth_check('litm','1');
			include($BF.'calendar/components/add_functions.php');
			# Stuff In The Header
			function sith() { 
				global $BF;
			}

			$key = makekey();
			
			if(isset($_SESSION['CalendarType']) && $_SESSION['CalendarType'] == 'travel') {

				$query = "SELECT TravelEvents.ID,TravelEvents.chrKey,TravelEvents.chrCalendarEvent,DAY(TravelEvents.dBegin) as dDay, chrColorText,chrColorBG,
					  TravelEvents.dBegin,TravelEvents.dEnd,TravelEvents.tBegin,TravelEvents.tEnd,chrSeries,bAllDay,chrReoccur,
					  (SELECT MAX(dEnd) FROM TravelEvents as TE WHERE !bDeleted AND TravelEvents.chrSeries=TE.chrSeries) as dMaxDate
					FROM TravelEvents
					JOIN CalendarTypes ON CalendarTypes.ID=TravelEvents.idCalendarType
					WHERE !TravelEvents.bDeleted
					". ($_SESSION['idTravelUsers'] != "" ? " AND TravelEvents.idUser IN (". $_SESSION['idTravelUsers'] .") " : '') ." 
					ORDER BY chrSeries,dBegin,tBegin,chrCalendarEvent
				";
			
			
			} else {
			
				$query = "SELECT CalendarEvents.ID,CalendarEvents.chrKey,CONCAT(RetailStores.chrName,' / ',RetailStores.chrStoreNum) AS chrCalendarEvent,DAY(CalendarEvents.dBegin) as dDay, chrColorText,chrColorBG,
					  CalendarEvents.dBegin,CalendarEvents.dEnd,CalendarEvents.tBegin,CalendarEvents.tEnd,chrSeries,bAllDay,chrReoccur,
					  (SELECT MAX(dEnd) FROM CalendarEvents as CE WHERE !bDeleted AND CalendarEvents.chrSeries=CE.chrSeries) as dMaxDate
					FROM CalendarEvents
					JOIN CalendarTypes ON CalendarTypes.ID=CalendarEvents.idCalendarType
					JOIN NSOs ON CalendarEvents.idNSO=NSOs.ID AND CalendarEvents.idCalendarType=1
					JOIN NSOUserTitleAssoc AS UTA ON UTA.idNSO=NSOs.ID AND UTA.idUserTitle=2
					JOIN Users ON Users.ID=UTA.idUser 
					JOIN RetailStores ON NSOs.idStore=RetailStores.ID 
					WHERE !CalendarEvents.bDeleted
					". ($_SESSION['idCalTypes'] != "" ? " AND idCalendarType IN (". $_SESSION['idCalTypes'] .") " : '') ."
					". ($_SESSION['idCalUsers'] != "" ? " AND Users.ID IN (". $_SESSION['idCalUsers'] .") " : '') ."
					". ($_SESSION['chrCalRegions'] != "" ? " AND RetailStores.chrRegion IN (". $_SESSION['chrCalRegions'] .") " : '') ."
					ORDER BY chrSeries,dBegin,tBegin,chrCalendarEvent
				";
			
			}
			
			$q = "INSERT INTO CalendarQueries SET 
				chrKEY='". $key ."',
				dtCreated=now(),
				idUser='". $_SESSION['idUser'] ."',
				chrCalendarQuery='". encode($query) ."'
			";
			if(db_query($q,"Inserting Query into Database")) {
			//	$ICAL_ADDRESS = $PROJECT_ADDRESS;
				$ICAL_ADDRESS = str_replace('http://','webcal://',$PROJECT_ADDRESS);	
				header("Location: ".$ICAL_ADDRESS."calendar/ical.php?k=". $key);
			}
			
			
			# The template to use (should be the last thing before the break)
			$title = 'iCal Subscribe';
			$directions = '';
			$header_title = 'iCal Subscribe';
			include($BF ."calendar/models/template.php");		
			
			break;

		#################################################
		##	User Popup Page
		#################################################
		case 'newfeatures.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');

	
			break;	
			
		#################################################
		##	Else show Error Page
		#################################################
		default:
			include($BF .'calendar/_lib.php');
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}
	
	function get_dates($var) {
		
		$intDay = date('d',strtotime($var));
		$intMonth = date('m',strtotime($var));
		$intYear = date('Y',strtotime($var));
		
		return(array(
				$intDay,
				$intMonth,
				$intYear,
				1-(idate('w', mktime(0, 0, 0, $intMonth, 1, $intYear))),
				idate('t', mktime(0, 0, 0, $intMonth, 1, $intYear)),
				idate('t', mktime(0, 0, 0, ($intMonth-1), 1, $intYear))
		));
	}



?>