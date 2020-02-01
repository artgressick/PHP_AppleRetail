<?
	# This is the BASE FOLDER pointing back to the root directory
	$BF = '../../';
	
	$file_name = basename($_SERVER['SCRIPT_NAME']);
    $post_file = '_'.$file_name;

 	switch($file_name) {
		#################################################
		##	Index Page, List Site Survey
		#################################################
		case 'index.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			auth_check('litm',2,1);
			include($BF.'calendar/components/formfields.php');

			if(!isset($_REQUEST['key']) || strlen($_REQUEST['key']) != 40) {
				$info = db_query("SELECT ID,chrKEY 
									FROM NSOLearn
									WHERE !bDeleted AND bShow AND bPShow AND idType=1
									ORDER BY dOrder,!bParent,dOrderChild,chrTitle
									","get first learn article",1);

				if($info['ID'] == "") { errorPage('Invalid Article'); } // Did we get a result?
				
				$_REQUEST['key'] = $info['chrKEY'];
			}
			
			$query = "SELECT ID, chrTitle, txtContent
			FROM NSOLearn
			WHERE chrKEY = '".$_REQUEST['key']. "' AND !bDeleted AND bShow AND bPShow AND idType=1";
			
			$pageinfo = db_query($query, 'Getting Article',1);
			
//			if($pageinfo['ID'] == "") { errorPage('Invalid Article'); } // Did we get a result?
			
			# Stuff In The Header
			function sith() { 
				global $BF,$results;
				
			}

			# The template to use (should be the last thing before the break)
			$title = $pageinfo['chrTitle'];	# Page Title
			$header_title = $pageinfo['chrTitle'];
			$custom_directions = '';
								
			include($BF ."calendar/models/learnmore.php");		
			
			break;

		#################################################
		##	Search Page
		#################################################
		case 'search.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			auth_check('litm',2,5);
			include($BF.'calendar/components/formfields.php');
			

			# Stuff In The Header
			function sith() { 
				global $BF,$results;
				if(!isset($_POST['chrSearch'])) {
					?><script type='text/javascript' src='error_check.js'></script><?
				} else { 
					include($BF .'calendar/components/list/sortlistjs.php');
				}
			}

			# The template to use (should be the last thing before the break)
			$title = (!isset($_POST['chrSearch']) ? "Search Articles" : 'Search Results for "'.$_POST['chrSearch'].'"');	# Page Title
			$header_title = (!isset($_POST['chrSearch']) ? "Search Articles" : 'Search Results for "'.$_POST['chrSearch'].'"');
			$directions = (!isset($_POST['chrSearch']) ? 'Enter in text into the field provided to search the articles.' : 'Select a article from the list to view');
								
			include($BF ."calendar/models/learnmore.php");		
			
			break;

		#################################################
		##	Else show Error Page
		#################################################
		default:
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}
?>