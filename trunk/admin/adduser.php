<?php
	$BF='../';
	require($BF. '_lib.php');
	// bSuperAdmin must be set to true, else show noaccess.php page
		if ($Security['bSuperAdmin'] == false) {
				header("Location: " . $BF . "noaccess.php");
				die();
		}
	// If a post occured
	if(isset($_POST['chrEmail'])) { // When doing isset, use a required field.  Faster than the php count funtion.
	
		$q = "INSERT INTO Users SET 
			chrFirst ='".encode($_POST['chrFirst'])."',
			chrLast='". encode($_POST['chrLast']) ."',
			chrEmail='". encode($_POST['chrEmail']) ."',
			bSuperAdmin='". $_POST['bSuperAdmin'] ."',
			bCommtool='". $_POST['bCommtool'] ."',
			bPandP='". $_POST['bPandP'] ."',
			bStoreHours='". $_POST['bStoreHours'] ."',
			chrPassword='". sha1($_POST['chrPassword']) ."'";
			
		database_query($q,"Insert into users");

		// This is the code for inserting the Audit Page
		// Type 1 means ADD NEW RECORD, change the TABLE NAME also
		global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
		$newID = mysqli_insert_id($mysqli_connection);

		if($_POST['bNSORemodel'] == 1) {
			$q = "INSERT INTO CalendarAccess SET 
				chrKEY ='".makekey()."',
				idUser='". $newID ."',
				idAccessType='2'";
			database_query($q,"Insert user access");
			
			// Set Upload Directory
			$upload_dir = $BF. "userfiles/".$_POST['chrEmail'];
			// If the directory doesn't exist then create
			if (!is_dir($upload_dir)) {
				#chmod($BF ."userfiles/", 0777);
				mkdir($upload_dir, 0777);
			}
			//check if the directory is writable.
	        if (!is_writeable("$upload_dir")) { chmod($upload_dir, 0777); }
			
		}

		$q = "INSERT INTO Audit SET 
			idType=1, 
			idRecord='". $newID ."',
			txtNewValue='". encode($_POST['chrName']) ."',
			dtDateTime=now(),
			chrTableName='Users',
			idUser='". $_SESSION['idUser'] ."'
		";
		database_query($q,"Insert audit");
		//End the code for History Insert  


		header("Location: ". $_POST['moveTo']);
		die();
	}
	
	
//jms: This is where Javascript goes
?>
<script language="JavaScript" type='text/javascript' src="<?=$BF?>includes/noticeadd.js"></script>
<script language="JavaScript" src="<?=$BF?>includes/forms.js"></script>

<script language="javascript">
	function error_check(addy) {
		if(total != 0) { reset_errors(); }  

		var total=0;

		total += ErrorCheck('chrFirst', "You must enter a First Name.");
		total += ErrorCheck('chrLast', "You must enter a Last Name.");
		total += ErrorCheck('chrEmail', "You must enter a E-mail Address.");
		total += ErrorCheck('chrPassword', "You must enter a Password.");
		total += ErrorCheck('chrPassword2', "You must enter a Confirm Password.");
		total += matchPasswords('chrPassword', 'chrPassword2', "Your password and confirm password do not match.");

		if(total == 0) { notice(document.getElementById('chrFirst').value + ' ' + document.getElementById('chrLast').value, 'adduser.php', 'users.php'); }
	}
</script>
<?	
	//This is needed for the nav_menu on top. We are setting the focus on the first text box of the page.
	$bodyParams = "document.getElementById('chrFirst').focus()";
	include($BF. "includes/meta.php");
	include($BF. "includes/top.php");
	include($BF. "admin/includes/admin_nav.php");
	include($BF. 'includes/noticeadd.php');
?>

	<div class='listHeader'>Add User</div>
	<div class='greenFilter'>To Add a User, Add all information and click Add. Pay close attention to required fields.</div>
	<div class='emailbody'>
		<form enctype="multipart/form-data" action="" method="post" id="idForm">
		<!-- This is the main page form -->
			<div id="errors"></div>
			<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
				<tr>
					<td class="left">
						<div class='FormName'>First Name <span class='FormRequired'>(Required)</span></div>
						<div class='FormField'><input class="FormTextBox" type="text" name="chrFirst" id="chrFirst" maxlength="30" value="" /></div>
						
						<div class='FormName'>Last Name <span class='FormRequired'>(Required)</span></div>
						<div class='FormField'><input class="FormTextBox" type="text" name="chrLast" id="chrLast" maxlength="30" value="" /></div>
						
						<div class='FormName'>E-mail Address</div>
						<div class='FormField'><input class="FormTextBox" type="text" name="chrEmail" id="chrEmail" maxlength="80" value="" /></div>
						
						<div class='FormName'>Password <span class='FormRequired'>(Required)</span></div>
						<div class='FormField'><input class="FormTextBox" type="password" name="chrPassword" id="chrPassword" maxlength="30" value="" /></div>
						
						<div class='FormName'>Confirm Password <span class='FormRequired'>(Required)</span></div>
						<div class='FormField'><input class="FormTextBox" type="password" name="chrPassword2" id="chrPassword2" maxlength="30" value="" /></div>
						
					</td>
					<td class="gutter"></td>
					<td class="right">
						<div class='FormName'>Access Rights.</div>
						<div class='FormField'><input type="checkbox" name="bSuperAdmin" id="bSuperAdmin" value="1" /> Access Users/Stores Admin Section</div>
						<div class='FormField'><input type="checkbox" name="bCommtool" id="bCommtool" value="1" /> Access CommTool Admin Section</div>
						<div class='FormField'><input type="checkbox" name="bPandP" id="bPandP" value="1" /> Access P and P Admin Section</div>
						<div class='FormField'><input type="checkbox" name="bNSORemodel" id="bNSORemodel" value="1" /> Access NSO & Remodel Admin Section</div>
						<div class='FormField'><input type="checkbox" name="bStoreHours" id="bStoreHours" value="1" /> Access Store Hours Admin Section</div>
											

					</td>
				</tr>
			</table>
			<div class='FormButtons'>
				<input class='adminformbutton' type='button' value='Add User' onclick="error_check();" /> 
				<input type='hidden' name='moveTo' id='moveTo' />
			</div>
		</form>
	</div>

		
<?
	include($BF. "admin/includes/bottom.php");
?>
