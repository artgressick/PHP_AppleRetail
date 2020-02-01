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
			auth_check('litm',14,1);
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
				$tableName = "CalendarAccess";
				include($BF ."calendar/includes/overlay.php");
			}
			
			$q = "SELECT CA.ID, CA.chrKEY, U.chrFirst, U.chrLast, CS.chrSecurity
					FROM CalendarAccess AS CA
					JOIN Users AS U ON CA.idUser=U.ID
					LEFT JOIN CalSecurity AS CS ON CA.idSecurity=CS.ID
					WHERE !CA.bDeleted AND !U.bDeleted
					ORDER BY chrLast, chrFirst
				";
				
			$results = db_query($q,"getting users with calendar access");
			
			# The template to use (should be the last thing before the break)
			$title = "Manage Site Users";						# Page Title
			$directions = 'Choose a User from the list below. Click on the column header to sort the list by that column.';
			$header_title = (access_check(14,2) ? linkto(array('address'=>'add.php','img'=>'/calendar/images/plus_add.png')) : '')." Calendar Users <span class='resultsShown'>(<span id='resultCount'>".mysqli_num_rows($results)."</span> results)</span>";
			include($BF ."calendar/models/admin.php");		

			break;
		

 		#################################################
		##	Add Page
		#################################################
		case 'add.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',14,2);
			include($BF.'calendar/components/formfields.php');

			if(isset($_POST['idUser'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
?>
	<script type='text/javascript'>var page = 'add';</script>
	<script type="text/javascript" src="colorfind.js"></script>
	<script type='text/javascript' src='error_check.js'></script>
	<script type='text/javascript'>
		function typeselecta(id) {
			document.getElementById('addType1').checked=true;
			if (id == 2) {
				document.getElementById('colors').style.display='none';
				document.getElementById('stores').style.display='';
				document.getElementById('idSecurity2').value='';
				document.getElementById('bTravelAccess0').checked = true;		
			} else {
				document.getElementById('colors').style.display='';
				document.getElementById('stores').style.display='none';				
				document.getElementById('idSecurity2').value='';
			} 
		}
		function typeselectb(id) {
			document.getElementById('addType2').checked=true;
			if (id == 2) {
				document.getElementById('colors').style.display='none';
				document.getElementById('stores').style.display='';
				document.getElementById('idSecurity').value='';
				document.getElementById('bTravelAccess0').checked = true;			
			} else {
				document.getElementById('colors').style.display='';
				document.getElementById('stores').style.display='none';				
				document.getElementById('idSecurity').value='';
			} 
		}

		function selectallstores() {
			var smin = document.getElementById('minstore').value;
			var smax = document.getElementById('maxstore').value;

			for (var x = smin; x <= smax; x++) {
				if(document.getElementById('storeaccess'+x)) {
					document.getElementById('storeaccess'+x).checked=true;
				}
			}
		}
		
		function deselectallstores() {
			var smin = document.getElementById('minstore').value;
			var smax = document.getElementById('maxstore').value;
			
			for (var x = smin; x <= smax; x++) {
				if(document.getElementById('storeaccess'+x)) {
					document.getElementById('storeaccess'+x).checked=false;
				}
			}
		}

	</script>
<?
			}

			# The template to use (should be the last thing before the break)
			$title = "Add Site User";	# Page Title
			$directions = 'You are adding a User to have access to the Calendar.';
			$header_title ='Add User Access to Calendar';
			include($BF ."calendar/models/admin.php");	
			
			break;


		#################################################
		##	Edit Page
		#################################################
		case 'edit.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',14,3);
			include($BF.'calendar/components/formfields.php');
			
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid User'); } // Check Required Field for Query

			$info = db_query("SELECT CalendarAccess.*, Users.chrFirst, Users.chrLast
								FROM CalendarAccess
								JOIN Users ON CalendarAccess.idUser=Users.ID
								WHERE CalendarAccess.chrKEY='". $_REQUEST['key'] ."'
			","getting info",1); // Get Info
				
			if($info['ID'] == "") { errorPage('Invalid User'); } // Did we get a result?
			
			if(isset($_POST['idSecurity'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
			
?>	<script type='text/javascript'>var page = 'edit';</script>
	<script type="text/javascript" src="colorfind.js"></script>
	<script type='text/javascript' src='error_check.js'></script>
	<script type='text/javascript'>
		function typeselect(id) {
			if (id == 2) {
				document.getElementById('colors').style.display='none';
				document.getElementById('stores').style.display='';
				document.getElementById('bTravelAccess0').checked = true;
			} else {
				document.getElementById('colors').style.display='';
				document.getElementById('stores').style.display='none';				
			} 
		}
		function selectallstores() {
			var smin = document.getElementById('minstore').value;
			var smax = document.getElementById('maxstore').value;
			for (var x = smin; x <= smax; x++) {
				if(document.getElementById('storeaccess'+x)) {
					document.getElementById('storeaccess'+x).checked=true;
				}
			}
		}
		function deselectallstores() {
			var smin = document.getElementById('minstore').value;
			var smax = document.getElementById('maxstore').value;
			
			for (var x = smin; x <= smax; x++) {
				if(document.getElementById('storeaccess'+x)) {
					document.getElementById('storeaccess'+x).checked=false;
				}
			}
		}
	</script>

<?
			}

			# The template to use (should be the last thing before the break)
			$title = "Edit Site User";	# Page Title
			$directions = 'Please update the information below and press the "Update Information" when you are done making changes.';
			$header_title ='Edit User Access: '.$info['chrFirst'].' '.$info['chrLast'];
			include($BF ."calendar/models/admin.php");		
			
			break;
						
			
		#################################################
		##	Else show Error Page
		#################################################
		default:
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>