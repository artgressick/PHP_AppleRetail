<?
	# This is the BASE FOLDER pointing back to the root directory
	$BF = '../../';

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
			auth_check('litm','1,2');
			include($BF.'calendar/components/formfields.php');
			include($BF .'calendar/components/miniPopup.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
				?><script type='text/javascript' src='<?=$BF?>includes/overlaysnew.js'></script><?
				include($BF .'calendar/components/list/sortlistjs.php');
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
			}
			
			$results = db_query('SELECT CalendarFiles.ID,CalendarFiles.chrKEY,chrFileTitle,chrCalendarFile,chrThumbnail,chrName as chrStore,
					DATE_FORMAT(CalendarFiles.dtCreated,"%c/%e/%Y - %l:%i %p") AS dtCreated
				FROM CalendarFiles
				JOIN NSOs ON NSOs.ID=CalendarFiles.idNSO
				JOIN RetailStores ON RetailStores.ID=NSOs.idStore
				WHERE idCalendarFileType=1
				'. (isset($_REQUEST['idStore']) && is_numeric($_REQUEST['idStore']) ? ' AND RetailStores.ID='.$_REQUEST['idStore'] : '') .'
				ORDER BY chrCalendarFile
			','getting Pictures');

			# The template to use (should be the last thing before the break)
			$custom_directions = true; 		# In the need to have filters, set this option and write out the custom filters on the page
			$title = "NSO/Remodel Pictures";						# Page Title
			$directions = 'Please choose the preview option from below to see a thumbnail of the image.';

			$header_title = "NSO & Remodel Pictures <span class='resultsShown'>(<span id='resultCount'>".mysqli_num_rows($results)."</span> results)</span>";
			include($BF ."calendar/models/nso.php");		

			break;
	
	
		#################################################
		##	Else show Error Page
		#################################################
		default:
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>