<?	
	$BF = '../../';
	require($BF. '_lib.php');
	include($BF. 'includes/meta.php');	
		// bCommtool must be set to true, else show noaccess.php page
		if ($Security['bCommtool'] == false) {
				header("Location: " . $BF . "commtool/noaccess.php");
				die();
		}
	(!isset($_REQUEST['id']) || !is_numeric($_REQUEST['id']) ? ErrorPage("commtool/admin/addressbook.php","Invalid Page") : "");
	// Get info to populate fields. Also ... If the old information is the same as the current, why update it?  Get the old information to test this against.
	$info = database_query("SELECT * FROM RFLContacts WHERE ID=". $_REQUEST['id'],"getting RFL Contacts",1);
	($info['ID'] == "" ? ErrorPage("commtool/admin/addressbook.php","Invalid Page") : "");
	// If a post occured
	if(isset($_POST['chrLast'])) { // When doing isset, use a required field.  Faster than the php count funtion.

		// Set the basic values to be used.
		//   $table = the table that you will be connecting to to check / make the changes
		//   $mysqlStr = this is the "mysql string" that you are going to be using to update with.  This needs to be set to "" (empty string)
		//   $sudit = this is the "audit string" that you are going to be using to update with.  This needs to be set to "" (empty string)
		$table = 'RFLContacts';
		$mysqlStr = '';
		$audit = '';

		// "List" is a way for php to split up an array that is coming back.  
		// "set_strs" is a function (bottom of the _lib) that is set up to look at the old information in the DB, and compare it with
		//    the new information in the form fields.  If the information is DIFFERENT, only then add it to the mysql string to update.
		//    This will ensure that only information that NEEDS to be updated, is updated.  This means smaller and faster DB calls.
		//    ...  This also will ONLY add changes to the audit table if the values are different.
		list($mysqlStr,$audit) = set_strs($mysqlStr,'chrFirst',$info['chrFirst'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'chrLast',$info['chrLast'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'chrEmail',$info['chrEmail'],$audit,$table,$_POST['id']);

		// if nothing has changed, don't do anything.  Otherwise update / audit.
		if($mysqlStr != '') { list($str,$aud) = update_record($mysqlStr, $audit, $table, $_POST['id']); }

		header("Location: ". $_POST['moveTo']);
		die();
	}


?>
<script language="JavaScript" type='text/javascript' src="<?=$BF?>includes/noticeedit.js"></script>
<script language="JavaScript" src="<?=$BF?>includes/forms.js"></script>

<script language="javascript">
	function error_check(addy) {
		if(total != 0) { reset_errors(); }  

		var total=0;
		
		total += ErrorCheck('chrLast', "You must enter a Last Name.");
		total += ErrorCheck('chrFirst', "You must enter a First Name.");
		total += ErrorCheck('chrEmail', "You must enter an Email Address.");

		if(total == 0) { notice(document.getElementById('chrFirst').value + ' ' + document.getElementById('chrLast').value, 'addressbook.php'); }
	}
</script>
<?
	
	//This is needed for the nav_menu on top. We are setting the focus on the first text box of the page.
	$bodyParams = "document.getElementById('chrFirst').focus()";

	include($BF. "includes/top.php");
	include($BF. "commtool/includes/admin_nav.php");
	include($BF. 'includes/noticeedit.php');
?>
<form name='idForm' id='idForm' action='' method="post">

	<div class='listHeader'>Edit Contact</div>
	<div class='greenInstructions'>Update the information below and click on update.</div>
	
		<div id='errors'></div>
		
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td width="50%" valign="top">
					<div class='FormName'>First Name <span class='FormRequired'>(Required)</span></div>
					<div class='FormField'><input type='text' name='chrFirst' id='chrFirst' size='35' value='<?=$info['chrFirst']?>' /></div>
					
					<div class='FormName'>Last Name <span class='FormRequired'>(Required)</span></div>
					<div class='FormField'><input type='text' name='chrLast' id='chrLast' size='35' value='<?=$info['chrLast']?>' /></div>
				</td>
				<td width="50%" valign="top">
					<div class='FormName'>Email Address <span class='FormRequired'>(Required)</span></div>
					<div class='FormField'><input type='text' name='chrEmail' id='chrEmail' size='35' value='<?=$info['chrEmail']?>' /></div>
				</td>
			</tr>
		</table>	

			<input class='adminformbutton' type='button' value='Update Information' onclick="error_check();" />
			<input type='hidden' name='id' value='<?=$_REQUEST['id']?>' >
			<input type='hidden' name='moveTo' id='moveTo' />

	</div>

</form>
<?php
	include($BF. "commtool/includes/bottom.php");
?>