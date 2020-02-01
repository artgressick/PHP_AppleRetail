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
			auth_check('litm','1');
			include($BF.'calendar/components/formfields.php');

			if(isset($_POST['submit']) && $_POST['submit'] == "Save/Update") { include($post_file); }
			# Stuff In The Header
			function sith() { 
				global $BF;
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
				$tableName = "NSOTasks";
				include($BF ."calendar/includes/overlay.php");
			}

			$q = "SELECT Users.ID, Users.chrFirst, chrLast, NSOUsers.idUser AS idInvolved
					FROM CalendarAccess
					JOIN Users ON CalendarAccess.idUser=Users.ID
					LEFT JOIN NSOUsers ON NSOUsers.idUser=Users.ID
					WHERE !Users.bDeleted AND !CalendarAccess.bDeleted AND CalendarAccess.idAccessType=2
					ORDER BY chrLast,chrFirst";
			
			$results = db_query($q,"getting NSO Users");
			
			# The template to use (should be the last thing before the break)
			$title = "NSO/Remodel People Involved";						# Page Title
			$directions = 'Check the boxes next to the People Involved. Uncheck to remove a person, click Save/Update to save changes.';
			$header_title = "NSO/Remodel People Involved <span class='resultsShown'>(<span id='resultCount'>".mysqli_num_rows($results)."</span> results)</span>";
			include($BF ."calendar/models/admin.php");		

			break;
		#################################################
		##	Else show Error Page
		#################################################
		default:
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>