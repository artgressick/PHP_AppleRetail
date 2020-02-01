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
			$title = "Store Hours";	# Page Title
			# Adding in the lib file
			include($BF .'storehours/_lib.php');
			include_once($BF.'storehours/components/formfields.php');

			$results = db_query("SELECT ID,tOpening,tClosing,idDayOfWeek,bClosed FROM StoreHours WHERE idStore=". $_SESSION['idStore']." ORDER BY idDayOfWeek","Gettings Times");
			if(mysqli_num_rows($results) == 0) { 
				header('Location:'. $BF ."storehours/official/");
				die();
			}
 			
			# Stuff In The Header
			function sith() { 
				global $BF;
				include($BF .'calendar/components/list/sortlistjs.php');
			}

			$q = "SELECT chrHoliday,ID,chrKEY,DATE_FORMAT(dBegin,'%M %D, %Y') as dBegin,DATE_FORMAT(dEnd,'%M %D, %Y') as dEnd
				FROM Holidays
				WHERE !bDeleted AND bVisible 
				ORDER BY chrHoliday";
			$holiday_results = db_query($q,"getting Holidays");


			$title = "Store Hours";	# Page Title
			$header_title = "Store Hours";
			$directions = 'Please choose one of the Holiday that you wish to alter or update the times to.';

			# The template to use (should be the last thing before the break)
			include($BF ."storehours/models/template.php");		
			
			break;


		#################################################
		##	Holiday Page
		#################################################
		case 'holiday.php':
			$title = "Holiday Hours";	# Page Title
			# Adding in the lib file
			include($BF .'storehours/_lib.php');
			include_once($BF.'storehours/components/formfields.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
?>	<script type='text/javascript'>var page = 'edit';</script>
	<script type='text/javascript' src='error_check.js'></script>
	<script type='text/javascript'>
		function closed(day) {
			if(document.getElementById('bClosed'+day).checked==true) {
				document.getElementById('tOpening'+day).disabled=true;
				document.getElementById('tClosing'+day).disabled=true;
			} else {
				document.getElementById('tOpening'+day).disabled=false;
				document.getElementById('tClosing'+day).disabled=false;
			}
		
		}
	</script>
<?
			}
			
			$q = "SELECT chrHoliday,ID,chrKEY,dBegin,dEnd
				FROM Holidays
				WHERE !bDeleted AND chrKEY='". $_REQUEST['key'] ."'";
			$info = db_query($q,"getting Holidays",1);

			if($info['ID'] == "") { errorPage('Invalid Holiday'); } // Did we get a result?
			
			if(count($_POST)) { include($post_file); }

			$resultHours = db_query("SELECT * FROM StoreHoursSpecial WHERE idStore=".$_SESSION['idStore']." AND idHoliday=".$info['ID']." ORDER BY dDate","Getting Store Hours");
			
			
			$title = "Holiday Hours";	# Page Title
			$header_title = "Holiday Hours for ".$info['chrHoliday'];
			$directions = 'Please update the hours the store will be keeping during each day of this Holiday. <strong>Valid Time Formats: 9:00am or 14:00</strong>';

			# The template to use (should be the last thing before the break)
			include($BF ."storehours/models/template.php");		
			
			break;

				
		#################################################
		##	Error Page
		#################################################
		case 'error.php':
			$title = "Error Page";	# Page Title
			# Adding in the lib file
			include($BF .'storehours/_lib.php');
			include_once($BF.'storehours/formfields.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
			}

			# The template to use (should be the last thing before the break)
			include($BF ."storehours/models/template.php");		
			
			break;
		#################################################
		##	Else show Error Page
		#################################################
		default:
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}
	
	


?>