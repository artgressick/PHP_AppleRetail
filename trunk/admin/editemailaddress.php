<?php
	$BF='../';
	require($BF. '_lib.php');
	// bSuperAdmin must be set to true, else show noaccess.php page
		if ($Security['bSuperAdmin'] == false) {
				header("Location: " . $BF . "noaccess.php");
				die();
		}
	// Error Check
	if(!isset($_REQUEST['id']) || !is_numeric($_REQUEST['id'])) { ErrorPage("admin/emailaddresses.php","Invalid Geo"); }

	// Get info to populate fields. Also ... If the old information is the same as the current, why update it?  Get the old information to test this against.
	$info = database_query("SELECT * FROM Geos WHERE ID=". $_REQUEST['id'],"getting Geo info",1);
	if($info['ID'] == "") { ErrorPage("admin/emailaddresses.php","Invalid Geo"); }
	// If a post occured
	if(isset($_POST['chrEmailAddress'])) { // When doing isset, use a required field.  Faster than the php count funtion.

		// Set the basic values to be used.
		//   $table = the table that you will be connecting to to check / make the changes
		//   $mysqlStr = this is the "mysql string" that you are going to be using to update with.  This needs to be set to "" (empty string)
		//   $sudit = this is the "audit string" that you are going to be using to update with.  This needs to be set to "" (empty string)
		$table = 'Geos';
		$mysqlStr = '';
		$audit = '';

		// "List" is a way for php to split up an array that is coming back.  
		// "set_strs" is a function (bottom of the _lib) that is set up to look at the old information in the DB, and compare it with
		//    the new information in the form fields.  If the information is DIFFERENT, only then add it to the mysql string to update.
		//    This will ensure that only information that NEEDS to be updated, is updated.  This means smaller and faster DB calls.
		//    ...  This also will ONLY add changes to the audit table if the values are different.
		list($mysqlStr,$audit) = set_strs($mysqlStr,'chrEmailAddress',$info['chrEmailAddress'],$audit,$table,$_POST['id']);
		
		// if nothing has changed, don't do anything.  Otherwise update / audit.
		if($mysqlStr != '') { list($str,$aud) = update_record($mysqlStr, $audit, $table, $_POST['id']); }

	header("Location: ". $_POST['moveTo']);
	die();
	}
	
	
//jms: This is where Javascript goes
?>
<script language="JavaScript" type='text/javascript' src="<?=$BF?>includes/noticeedit.js"></script>
<script language="JavaScript" src="<?=$BF?>includes/forms.js"></script>

<script language="javascript">
	function error_check(addy) {
		if(total != 0) { reset_errors(); }  

		var total=0;

		total += ErrorCheck('chrEmailAddress', "You must enter a E-mail Address.");
		
		if(total == 0) { notice(document.getElementById('chrEmailAddress').value, 'emailaddresses.php'); }
	}
</script>
<?	

	//This is needed for the nav_menu on top. We are setting the focus on the first text box of the page.
	$bodyParams = "document.getElementById('chrEmailAddress').focus()";
	include($BF. "includes/meta.php");
	include($BF. "includes/top.php");
	include($BF. "admin/includes/admin_nav.php");
	include($BF. 'includes/noticeedit.php');
?>
	<div class='listHeader'>Edit "FROM" E-mail Address for <?=$info['chrGeo']?></div>
	<div class='greenFilter'>Update the E-mail address below and click "Update Information."</div>
	<div class='emailbody'>
		<form enctype="multipart/form-data" action="" method="post" id="idForm">
		<!-- This is the main page form -->
			<div id="errors"></div>
			<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
				<tr>
					<td class="left">

						<div class='FormName'>E-mail Address <span class='FormRequired'>(Required)</span></div>
						<div class='FormField'><input class="FormTextBox" type="text" name="chrEmailAddress" id="chrEmailAddress" maxlength="100" value="<?=$info['chrEmailAddress']?>" /></div>
						
					</td>
					<td class="gutter"></td>
					<td class="right">&nbsp;</td>
				</tr>
			</table>
			<div class='FormButtons'>
			<input class='adminformbutton' type='button' value='Update Information' onclick="error_check();" />
			<input type='hidden' name='id' value='<?=$_REQUEST['id']?>' >
			<input type='hidden' name='moveTo' id='moveTo' />
			</div>
		</form>
	</div>

		
<?
	include($BF. "admin/includes/bottom.php");
?>
