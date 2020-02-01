<?	
	$BF = '../../';  //dtn: Base folder for the root of the project.  This needs to be set on all pages.

	$title = 'Add Contact';      //dtn: Title to display at the top of the browser window.
	
	require($BF. '_lib.php');
	include($BF. 'includes/meta.php');
		// bCommtool must be set to true, else show noaccess.php page
		if ($Security['bCommtool'] == false) {
				header("Location: " . $BF . "commtool/noaccess.php");
				die();
		}
	if(isset($_POST['chrLast'])) { // When doing isset, use a required field.  Faster than the php count funtion.

		$q = "INSERT INTO RFLContacts SET 
			chrLast='". $_POST['chrLast'] ."',
			chrFirst='". $_POST['chrFirst'] ."',
			chrEmail='". $_POST['chrEmail'] ."'
		";
		database_query($q,"Insert into contacts");
		
		// This is the code for inserting the Audit Page
		// Type 1 means ADD NEW RECORD, change the TABLE NAME also
		global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
		$newID = mysqli_insert_id($mysqli_connection);
				
		$q = "INSERT INTO Audit SET 
			idType=1, 
			idRecord='". $newID ."',
			txtNewValue='". $_POST['chrLast'] ."',
			dtDateTime=now(),
			chrTableName='RFLContacts',
			idUser='". $_SESSION['idUser'] ."'
		";
		database_query($q,"Insert audit");
		//End the code for History Insert
		
		header("Location: ". $_POST['moveTo']);
		die();
	}

	// The Forms js is for all the error checking that is involved with the forms Add / Edit Pages
	
	//This is needed for the nav_menu on top. We are setting the focus on the first text box of the page.
	$bodyParams = "document.getElementById('chrFirst').focus()";

?>
<script language="JavaScript" type='text/javascript' src="<?=$BF?>includes/noticeadd.js"></script>
<script language="JavaScript" src="<?=$BF?>includes/forms.js"></script>

<script language="javascript">
	function error_check(addy) {
		if(total != 0) { reset_errors(); }  

		var total=0;

		total += ErrorCheck('chrLast', "You must enter a Last Name.");
		total += ErrorCheck('chrFirst', "You must enter a First Name.");
		total += ErrorCheck('chrEmail', "You must enter an Email Address.");

		if(total == 0) { notice(document.getElementById('chrFirst').value + ' ' + document.getElementById('chrLast').value, 'addcontact.php', 'addressbook.php'); }
	}
</script>
<?
	include($BF. "includes/top.php");
	include($BF. "commtool/includes/admin_nav.php");
	include($BF. 'includes/noticeadd.php');
?>

<form name='idForm' id='idForm' action='' method="post">

	<div class='listHeader'>Edit Contact</div>
	<div class='greenInstructions'>Contacts are used in the distribution list.</div>
	
		<div id='errors'></div>
		
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td width="50%" valign="top">
					<div class='FormName'>First Name <span class='FormRequired'>(Required)</span></div>
					<div class='FormField'><input type='text' name='chrFirst' id='chrFirst' size='35' /></div>
					
					<div class='FormName'>Last Name <span class='FormRequired'>(Required)</span></div>
					<div class='FormField'><input type='text' name='chrLast' id='chrLast' size='35' /></div>
				</td>
				<td width="50%" valign="top">
					<div class='FormName'>Email Address <span class='FormRequired'>(Required)</span></div>
					<div class='FormField'><input type='text' name='chrEmail' id='chrEmail' size='35' /></div>
				</td>
			</tr>
		</table>
		<input class='FormButtons' type='button' value='Add Contact' onclick="error_check();" />
		<input type='hidden' name='moveTo' id='moveTo' />

	</div>

</form>
<?
	include($BF. 'commtool/includes/bottom.php');
?>
