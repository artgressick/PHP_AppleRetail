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
			include($BF .'storehours/_lib.php');
			include_once($BF.'storehours/components/formfields.php');
 			
			# Stuff In The Header
			function sith() { 
				global $BF;
?>	<script type='text/javascript'>var page = 'edit';</script>
	<script type='text/javascript' src='error_check.js'></script>
	<script type='text/javascript'>
		function checkday(day) {
			if(document.getElementById('bClosed'+day).checked) {
				document.getElementById('tOpening'+day).disabled = true;
				document.getElementById('tClosing'+day).disabled = true;
			} else {
				document.getElementById('tOpening'+day).disabled = false;
				document.getElementById('tClosing'+day).disabled = false;
			}
		}
	</script>
<?
			}

			if(count($_POST)) { include($post_file); }

			$info = db_query("SELECT * FROM StoreHours WHERE idStore=".$_SESSION['idStore']." ORDER BY idDayOfWeek","Getting Store Hours");
			
			$title = (mysqli_num_rows($info) > 0 ? 'Update' : 'Initial')." Store Hours";	# Page Title
			$header_title = (mysqli_num_rows($info) > 0 ? 'Update' : 'Initial')." Store Hours";
			$directions = 'Please fill out your store hours and click the "Update Information" at the bottom of the screen.';

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