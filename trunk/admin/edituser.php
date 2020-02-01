<?php
	$BF='../';
	require($BF. '_lib.php');
	// bSuperAdmin must be set to true, else show noaccess.php page
		if ($Security['bSuperAdmin'] == false) {
				header("Location: " . $BF . "noaccess.php");
				die();
		}
	// Error Check
	if(!isset($_REQUEST['id']) || !is_numeric($_REQUEST['id'])) { ErrorPage("admin/users.php","Invalid User"); }

	// Get info to populate fields. Also ... If the old information is the same as the current, why update it?  Get the old information to test this against.
	$info = database_query("SELECT *,(SELECT ID FROM CalendarAccess WHERE !bDeleted AND idUser='".$_REQUEST['id']."') as bNSORemodel
  			FROM Users 
  			WHERE ID=". $_REQUEST['id']
  		,"getting title info",1);
  		
	if($info['ID'] == "") { ErrorPage("admin/users.php","Invalid User"); }
	$_SESSION['chrReferName'] = $info['chrFirst']." ".$info['chrLast'];
	// If a post occured
	if(isset($_POST['chrEmail'])) { // When doing isset, use a required field.  Faster than the php count funtion.

		// Set the basic values to be used.
		//   $table = the table that you will be connecting to to check / make the changes
		//   $mysqlStr = this is the "mysql string" that you are going to be using to update with.  This needs to be set to "" (empty string)
		//   $sudit = this is the "audit string" that you are going to be using to update with.  This needs to be set to "" (empty string)
		$table = 'Users';
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
		list($mysqlStr,$audit) = set_strs($mysqlStr,'bSuperAdmin',$info['bSuperAdmin'],$audit,$table,$_POST['id']);	
		list($mysqlStr,$audit) = set_strs($mysqlStr,'bCommtool',$info['bCommtool'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'bPandP',$info['bPandP'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'bStoreHours',$info['bStoreHours'],$audit,$table,$_POST['id']);

		if($info['bNSORemodel'] == '' && $_POST['bNSORemodel'] == 1) {
			$userChk = database_query('SELECT ID FROM CalendarAccess WHERE idUser='. $_POST['id'],'checking for user');
			if(mysqli_num_rows($userChk) > 0) {
				database_query('UPDATE CalendarAccess SET bDeleted=0 WHERE idUser='. $_POST['id'],'re-add calendar access');
			} else {
				$q = "INSERT INTO CalendarAccess SET 
					chrKEY ='".makekey()."',
					idUser='". $_POST['id'] ."',
					idAccessType='2'";
				database_query($q,"Insert user access");
			}
			// Set Upload Directory
			$upload_dir = $BF. "userfiles/".$_POST['chrEmail'];
			// If the directory doesn't exist then create
			if (!is_dir($upload_dir)) {
				#chmod($BF ."userfiles/", 0777);
				mkdir($upload_dir, 0777);
			}
			//check if the directory is writable.
	        if (!is_writeable("$upload_dir")) { chmod($upload_dir, 0777); }
			
		} else if($info['bNSORemodel'] != '' && !isset($_POST['bNSORemodel'])) {
			database_query('UPDATE CalendarAccess SET bDeleted=1 WHERE idUser='. $_POST['id'],'delete calendar access');
		}

		if( $_POST['chrPassword'] != '')
		{
			if( $_POST['chrPassword'] == $_POST['chrPassword2'])
			{
				list($mysqlStr,$audit) = set_strs_password($mysqlStr,'chrPassword',$info['chrPassword'],$audit,$table,$_POST['id']);
			}
		}


		
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

		total += ErrorCheck('chrFirst', "You must enter a First Name.");
		total += ErrorCheck('chrLast', "You must enter a Last Name.");
		total += ErrorCheck('chrEmail', "You must enter a E-mail Address.");

		if( document.getElementById('chrPassword').value != '' || document.getElementById('chrPassword2').value != '')
		{			
			total += matchPasswords('chrPassword', 'chrPassword2', 'Passwords must match');
		}
		
		if(total == 0) { notice(document.getElementById('chrFirst').value + ' ' + document.getElementById('chrLast').value, 'users.php'); }
	}
</script>
<?	

	//This is needed for the nav_menu on top. We are setting the focus on the first text box of the page.
	$bodyParams = "document.getElementById('chrFirst').focus()";
	include($BF. "includes/meta.php");
	include($BF. "includes/top.php");
	include($BF. "admin/includes/admin_nav.php");
	include($BF. 'includes/noticeedit.php');


?>

	<div class='listHeader'>Edit User - <?=$_SESSION['chrReferName']?></div>
	<div class='greenFilter'>To Edit a User, edit all information and click Update. Pay close attention to required fields.</div>
	<div class='emailbody'>
		<form enctype="multipart/form-data" action="" method="post" id="idForm">
		<!-- This is the main page form -->
			<div id="errors"></div>
			<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
				<tr>
					<td class="left">
						<div class='FormName'>First Name <span class='FormRequired'>(Required)</span></div>
						<div class='FormField'><input class="FormTextBox" type="text" name="chrFirst" id="chrFirst" maxlength="30" value="<?=$info['chrFirst']?>" /></div>
						
						<div class='FormName'>Last Name <span class='FormRequired'>(Required)</span></div>
						<div class='FormField'><input class="FormTextBox" type="text" name="chrLast" id="chrLast" maxlength="30" value="<?=$info['chrLast']?>" /></div>
						
						<div class='FormName'>E-mail Address</div>
						<div class='FormField'><input class="FormTextBox" type="text" name="chrEmail" id="chrEmail" maxlength="80" value="<?=$info['chrEmail']?>" /></div>
						
						<div class='FormName'>Password <span class='FormRequired'>(Only if changing password)</span></div>
						<div class='FormField'><input class="FormTextBox" type="password" name="chrPassword" id="chrPassword" maxlength="30" value="" /></div>
												
						<div class='FormName'>Confirm Password <span class='FormRequired'>(Only if changing password)</span></div>
						<div class='FormField'><input class="FormTextBox" type="password" name="chrPassword2" id="chrPassword2" maxlength="30" value="" /></div>
						
					</td>
					<td class="gutter"></td>
					<td class="right">
						<div class='FormName'>Access Rights.</div>
						<div class='FormField'><input type="checkbox" name="bSuperAdmin" id="bSuperAdmin" value="1" <?=($info['bSuperAdmin'] == 1 ? "checked='checked'" : "" )?> /> Access Users/Stores Admin Section</div>
						<div class='FormField'><input type="checkbox" name="bCommtool" id="bCommtool" value="1" <?=($info['bCommtool'] == 1 ? "checked='checked'" : "" )?> /> Access CommTool Admin Section</div>
						<div class='FormField'><input type="checkbox" name="bPandP" id="bPandP" value="1" <?=($info['bPandP'] == 1 ? "checked='checked'" : "" )?> /> Access P and P Admin Section</div>
						<div class='FormField'><input type="checkbox" name="bNSORemodel" id="bNSORemodel" value="1" <?=($info['bNSORemodel'] != '' ? "checked='checked'" : "" )?> /> Access NSO & Remodel Admin Section</div>
						<div class='FormField'><input type="checkbox" name="bStoreHours" id="bStoreHours" value="1" <?=($info['bStoreHours'] == 1 ? "checked='checked'" : "" )?> /> Access Store Hours Admin Section</div>

					</td>
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
