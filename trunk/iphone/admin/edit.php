<?
	$BF = "../../";
	require($BF. 'iphone/_lib.php');
	// Error Check

	// Get info to populate fields. Also ... If the old information is the same as the current, why update it?  Get the old information to test this against.
	$info = database_query("SELECT * FROM iPhoneRequest WHERE ID='". $_REQUEST['id']."'","getting title info",1);

	$_SESSION['chrReferName'] = $info['chrFirst']." ".$info['chrLast'];
	// If a post occured
	if(isset($_POST['chrSerial'])) { // When doing isset, use a required field.  Faster than the php count funtion.


		$update = database_query("UPDATE iPhoneRequest SET
									chrFirst='".encode($_POST['chrFirst'])."',
									chrLast='".encode($_POST['chrLast'])."',
									chrEmail='".$_POST['chrEmail']."',
									chrSerial='".encode($_POST['chrSerial'])."',
									chrEmpID='".$_POST['chrEmpID']."',
									chrDivision= UPPER('".$_POST['chrDivision']."')
									WHERE ID = ".$_POST['id'],"Updating Record");

		header("Location: index.php");
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
		total += ErrorCheck('chrSerial', "You must enter a Serial Number.");
		total += ErrorCheck('chrEmpID', "You must enter a Employee ID.");
		total += ErrorCheck('chrDivision', "You must enter a Store Number.");

		if(total == 0) { document.getElementById('idForm').submit(); }
	}
</script>
<?	

	//This is needed for the nav_menu on top. We are setting the focus on the first text box of the page.
	$bodyParams = "document.getElementById('chrFirst').focus()";
	include($BF. "includes/meta.php");
	include($BF. "includes/top.php");
?>
<table width="924" border="0" cellpadding="0" cellspacing="0" class="home_content">
	<tr>
		<td width="100%">
			<div class='listHeader'>Edit iPhone request for <?=$_SESSION['chrReferName']?></div>
			<div class='greenFilter'>To Edit a User, edit all information and click Update. Pay close attention to required fields.</div>
			<div class='emailbody'>
				<form enctype="multipart/form-data" action="" method="post" id="idForm">
				<!-- This is the main page form -->
					<div id="errors"></div>
					<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
						<tr>
							<td class="left">
								<div class='FormName'>First Name <span class='FormRequired'>(Required)</span></div>
								<div class='FormField'><input class="FormTextBox" type="text" name="chrFirst" id="chrFirst" maxlength="100" value="<?=$info['chrFirst']?>" style="width:200px;" /></div>
								
								<div class='FormName'>Last Name <span class='FormRequired'>(Required)</span></div>
								<div class='FormField'><input class="FormTextBox" type="text" name="chrLast" id="chrLast" maxlength="100" value="<?=$info['chrLast']?>" style="width:200px;" /></div>
								
								<div class='FormName'>E-mail Address <span class='FormRequired'>(Required)</span></div>
								<div class='FormField'><input class="FormTextBox" type="text" name="chrEmail" id="chrEmail" maxlength="100" value="<?=$info['chrEmail']?>" style="width:200px;" /></div>
								
								
							</td>
							<td class="gutter"></td>
							<td class="right">

								<div class='FormName'>Serial Number <span class='FormRequired'>(Required)</span></div>
								<div class='FormField'><input class="FormTextBox" type="text" name="chrSerial" id="chrSerial" maxlength="30" value="<?=$info['chrSerial']?>" style="width:200px;" /></div>
								
								<div class='FormName'>Employee ID <span class='FormRequired'>(Required)</span></div>
								<div class='FormField'><input class="FormTextBox" type="text" name="chrEmpID" id="chrEmpID" maxlength="100" value="<?=$info['chrEmpID']?>" style="width:200px;" /></div>
								
								<div class='FormName'>Store <span class='FormRequired'>(Required)</span></div>
								<div class='FormField'>
									<select name='chrDivision' style="width:200px;">
										<option value=''>-Select Store-</option>
<?
	$stores = database_query("SELECT chrStoreNum,chrName FROM RetailStores WHERE !bDeleted AND chrCountry='US' ORDER BY chrName","getting stores");
	while($row = mysqli_fetch_assoc($stores)) { ?>
										<option<?=($info['chrDivision'] == $row['chrStoreNum'] ? ' selected="selected"' : '')?> value='<?=$row['chrStoreNum']?>'><?=$row['chrName']?></option>
<?	} ?>
									</select>
								</div>
	
							</td>
						</tr>
					</table>
					<div class='FormButtons'>
					<input class='adminformbutton' type='button' value='Save/Update Information' onclick="error_check();" />
					<input type='hidden' name='id' value='<?=$_REQUEST['id']?>' >
					</div>
				</form>
			</div>
		</td>
	</tr>
</table>

		
<?
	include($BF. "includes/bottom.php");
?>
