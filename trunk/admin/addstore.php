<?php
	$BF='../';
	require($BF. '_lib.php');

	// bSuperAdmin must be set to true, else show noaccess.php page
		if ($Security['bSuperAdmin'] == false) {
				header("Location: " . $BF . "noaccess.php");
				die();
		}
	// If a post occured
	if(isset($_POST['chrName'])) { // When doing isset, use a required field.  Faster than the php count funtion.
		$q = "INSERT INTO RetailStores SET 
			bShow ='".$_POST['bShow']."',
			chrStoreNum ='".encode($_POST['chrStoreNum'])."',
			chrName='". encode($_POST['chrName']) ."',
			chrEmail='". encode($_POST['chrEmail']) ."',
			idGeo='". $_POST['idGeo'] ."',
			chrPhone='". encode($_POST['chrPhone'])."',
			chrDivision='".encode($_POST['chrDivision'])."',
			chrRegion='".encode($_POST['chrRegion'])."',
			chrAddress='". encode($_POST['chrAddress'])."',
			chrAddress2='". encode($_POST['chrAddress2'])."',
			chrAddress3='". encode($_POST['chrAddress3'])."',
			chrCity='".encode($_POST['chrCity'])."',
			chrState = '".$_POST['chrState']."',
			chrPostalCode ='".encode($_POST['chrPostalCode'])."',
			chrCountry = '".$_POST['chrCountry']."'";
			
			database_query($q,"Insert into contacts");

		// This is the code for inserting the Audit Page
		// Type 1 means ADD NEW RECORD, change the TABLE NAME also
		global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
		$newID = mysqli_insert_id($mysqli_connection);

		$q = "INSERT INTO Audit SET 
			idType=1, 
			idRecord='". $newID ."',
			txtNewValue='". encode($_POST['chrName']) ."',
			dtDateTime=now(),
			chrTableName='RetailStores',
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

		total += ErrorCheck('chrName', "You must enter a Store Name.");
		total += ErrorCheck('chrAddress', "You must enter a Address.");
		total += ErrorCheck('chrPostalCode', "You must enter a Postal Code.");
		total += ErrorCheck('chrCountry', "You must select a Country.");	
		total += ErrorCheck('chrStoreNum', "You must enter a Store Number.");
		total += ErrorCheck('chrEmail', "You must enter a E-mail Address.");
		total += ErrorCheck('chrPhone', "You must enter a Phone Number.");
		total += ErrorCheck('chrDivision', "You must enter a Division.");
		total += ErrorCheck('idGeo', "You must select a Geo.");
		total += ErrorCheck('chrRegion', "You must enter a Region.");
		if(total == 0) { notice(document.getElementById('chrName').value, 'addstore.php', 'index.php'); }
	}
</script>
<?	

	//This is needed for the nav_menu on top. We are setting the focus on the first text box of the page.
	$bodyParams = "document.getElementById('chrName').focus()";

	//jms: Load Drop Down Menus and Load external files.
	$geos = database_query("SELECT * FROM Geos ORDER BY ID","Getting Geos");
	include($BF. 'components/states.php');	
	include($BF. 'components/countries.php');	


	include($BF. "includes/meta.php");
	include($BF. "includes/top.php");
	include($BF. "admin/includes/admin_nav.php");
	include($BF. 'includes/noticeadd.php');


?>

	<div class='listHeader'>Add Store</div>
	<div class='greenFilter'>To Add a Store, Add all information and click Add. Pay close attention to required fields.</div>
	<div class='emailbody'>
		<form enctype="multipart/form-data" action="" method="post" id="idForm">
		<!-- This is the main page form -->
			<div id="errors"></div>
			<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
				<tr>
					<td class="left">
						<div class='FormName'>Name <span class='FormRequired'>(Required)</span></div>
						<div class='FormField'><input class="FormTextBox" type="text" name="chrName" id="chrName" maxlength="200" value="" /></div>
						
						<div class='FormName'>Address <span class='FormRequired'>(Required)</span></div>
						<div class='FormField'><input class="FormTextBox" type="text" name="chrAddress" id="chrAddress" maxlength="200" value="" /></div>
						<div class='FormField'><input class="FormTextBox" type="text" name="chrAddress2" id="chrAddress2" maxlength="200" value="" /></div>
						<div class='FormField'><input class="FormTextBox" type="text" name="chrAddress3" id="chrAddress3" maxlength="200" value="" /></div>
						
						<div class='FormName'>City</div>
						<div class='FormField'><input class="FormTextBox" type="text" name="chrCity" id="chrCity" maxlength="90" value="" /></div>
						
						<div class='FormName'>State</div>
						<div class='FormField'>
							<select class='FormTextBox' id="chrState" name='chrState'>
									<option value="">Select from list</option>
								<?	foreach($states as $st => $name) { ?>
									<option value='<?=@$st?>'><?=$name?></option>
								<?	} ?>
							</select>						
						</div>
						
						<div class='FormName'>Zip/Postal Code <span class='FormRequired'>(Required)</span></div>
						<div class='FormField'><input class="FormTextBox" type="text" name="chrPostalCode" id="chrPostalCode" maxlength="50" value="" /></div>
						
						<div class='FormName'>Country <span class='FormRequired'>(Required)</span></div>
						<div class='FormField'>
							<select class='FormField' id="chrCountry" name='chrCountry'>
									<option value="">Select from list</option>
								<?	foreach($countries as $cy => $name) { ?>
									<option value='<?=@$cy?>'><?=$name?></option>
								<?	} ?>
							</select>
						</div>
					</td>
					<td class="gutter"></td>
					<td class="right">
						<div class='FormName'>Store Number <span class='FormRequired'>(Required)</span></div>
						<div class='FormField'><input class="FormTextBox" type="text" name="chrStoreNum" id="chrStoreNum" maxlength="5" value="" /></div>

						<div class='FormName'>E-mail Address <span class='FormRequired'>(Required)</span></div>
						<div class='FormField'><input class="FormTextBox" type="text" name="chrEmail" id="chrEmail" maxlength="80" value="" /></div>
	
						<div class='FormName'>Phone Number <span class='FormRequired'>(Required)</span></div>
						<div class='FormField'><input class="FormTextBox" type="text" name="chrPhone" id="chrPhone" maxlength="30" value="" /></div>
	
						<div class='FormName'>Division <span class='FormRequired'>(Required)</span></div>
						<div class='FormField'><input class="FormTextBox" type="text" name="chrDivision" id="chrDivision" maxlength="4" value="" /></div>
	
						<div class='FormName'>Geo <span class='FormRequired'>(Required)</span></div>
						<div class='FormField'>
							<select class='FormTextBox' id="idGeo" name='idGeo'>
									<option value="">Select from list</option>
								<?	while ($row = mysqli_fetch_assoc($geos)) {  ?>
									<option value='<?=$row['ID']?>'><?=$row['chrGeo']?></option>
								<?	} ?>
							</select>
						</div>
	
						<div class='FormName'>Region <span class='FormRequired'>(Required)</span></div>
						<div class='FormField'><input class="FormTextBox" type="text" name="chrRegion" id="chrRegion" maxlength="75" value="" /></div>
						
						<div class='FormName'>Show Store in List <span class='FormRequired'>(Required, Does not affect NSO/Remodel Section)</span></div>
						<div class='FormField'><input type="radio" name="bShow" id="bShow0" value='0' checked="checked" /> Hide&nbsp;&nbsp;&nbsp;<input type="radio" name="bShow" id="bShow1" value='1' /> Show</div>
						
					</td>
				</tr>				
			</table>
			<div class='FormButtons'>
				<input class='adminformbutton' type='button' value='Add' onclick="error_check();" />
				<input type='hidden' name='moveTo' id='moveTo' />
			</div>
		</form>
	</div>

		
<?
	include($BF. "admin/includes/bottom.php");
?>
