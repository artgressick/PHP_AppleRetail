<?php
	$BF='../';
	require($BF. '_lib.php');
	// bSuperAdmin must be set to true, else show noaccess.php page
		if ($Security['bSuperAdmin'] == false) {
				header("Location: " . $BF . "noaccess.php");
				die();
		}
	// Error Check
	if(!isset($_REQUEST['id']) || !is_numeric($_REQUEST['id'])) { ErrorPage("admin/index.php","Invalid Store"); }
	
	// Get info to populate fields. Also ... If the old information is the same as the current, why update it?  Get the old information to test this against.
	$info = database_query("SELECT * FROM RetailStores WHERE ID=". $_REQUEST['id'],"getting title info",1);
	if($info['ID'] == "") { ErrorPage("admin/index.php","Invalid Store"); }
	$_SESSION['chrReferName'] = $info['chrName'];
	// If a post occured
	if(isset($_POST['chrName'])) { // When doing isset, use a required field.  Faster than the php count funtion.

		// Set the basic values to be used.
		//   $table = the table that you will be connecting to to check / make the changes
		//   $mysqlStr = this is the "mysql string" that you are going to be using to update with.  This needs to be set to "" (empty string)
		//   $sudit = this is the "audit string" that you are going to be using to update with.  This needs to be set to "" (empty string)
		$table = 'RetailStores';
		$mysqlStr = '';
		$audit = '';

		// "List" is a way for php to split up an array that is coming back.  
		// "set_strs" is a function (bottom of the _lib) that is set up to look at the old information in the DB, and compare it with
		//    the new information in the form fields.  If the information is DIFFERENT, only then add it to the mysql string to update.
		//    This will ensure that only information that NEEDS to be updated, is updated.  This means smaller and faster DB calls.
		//    ...  This also will ONLY add changes to the audit table if the values are different.
		list($mysqlStr,$audit) = set_strs($mysqlStr,'chrName',$info['chrName'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'chrAddress',$info['chrAddress'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'chrAddress2',$info['chrAddress2'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'chrAddress3',$info['chrAddress3'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'chrCity',$info['chrCity'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'chrState',$info['chrState'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'chrPhone',$info['chrPhone'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'chrPostalCode',$info['chrPostalCode'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'chrCountry',$info['chrCountry'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'chrStoreNum',$info['chrStoreNum'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'idGeo',$info['idGeo'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'chrDivision',$info['chrDivision'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'chrRegion',$info['chrRegion'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'chrEmail',$info['chrEmail'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'bShow',$info['bShow'],$audit,$table,$_POST['id']);
		
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
		if(total == 0) { notice(document.getElementById('chrName').value, 'index.php'); }
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
	include($BF. 'includes/noticeedit.php');


?>

	<div class='listHeader'>Edit Store - <?=$_SESSION['chrReferName']?></div>
	<div class='greenFilter'>To Edit a Store, edit all information and click Update. Pay close attention to required fields.</div>
	<div class='emailbody'>
		<form enctype="multipart/form-data" action="" method="post" id="idForm">
		<!-- This is the main page form -->
			<div id="errors"></div>
			<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
				<tr>
					<td class="left">
						<div class='FormName'>Name <span class='FormRequired'>(Required)</span></div>
						<div class='FormField'><input class="FormTextBox" type="text" name="chrName" id="chrName" maxlength="200" value="<?=$info['chrName']?>" /></div>
						
						<div class='FormName'>Address <span class='FormRequired'>(Required)</span></div>
						<div class='FormField'><input class="FormTextBox" type="text" name="chrAddress" id="chrAddress" maxlength="200" value="<?=$info['chrAddress']?>" /></div>
						<div class='FormField'><input class="FormTextBox" type="text" name="chrAddress2" id="chrAddress2" maxlength="200" value="<?=$info['chrAddress2']?>" /></div>
						<div class='FormField'><input class="FormTextBox" type="text" name="chrAddress3" id="chrAddress3" maxlength="200" value="<?=$info['chrAddress3']?>" /></div>
						
						<div class='FormName'>City</div>
						<div class='FormField'><input class="FormTextBox" type="text" name="chrCity" id="chrCity" maxlength="90" value="<?=$info['chrCity']?>" /></div>
						
						<div class='FormName'>State</div>
						<div class='FormField'>
							<select class='FormTextBox' id="chrState" name='chrState'>
									<option value="">Select from list</option>
								<?	foreach($states as $st => $name) { ?>
									<option value='<?=@$st?>'<?=($st==$info['chrState'] ? " selected='selected'" : "" )?>><?=$name?></option>
								<?	} ?>
							</select>						
						</div>
						
						<div class='FormName'>Zip/Postal Code <span class='FormRequired'>(Required)</span></div>
						<div class='FormField'><input class="FormTextBox" type="text" name="chrPostalCode" id="chrPostalCode" maxlength="50" value="<?=$info['chrPostalCode']?>" /></div>
						
						<div class='FormName'>Country <span class='FormRequired'>(Required)</span></div>
						<div class='FormField'>
							<select class='FormField' id="chrCountry" name='chrCountry'>
									<option value="">Select from list</option>
								<?	foreach($countries as $cy => $name) { ?>
									<option value='<?=@$cy?>'<?=($cy==$info['chrCountry'] ? " selected='selected'" : "" )?>><?=$name?></option>
								<?	} ?>
							</select>
						</div>
					</td>
					<td class="gutter"></td>
					<td class="right">
						<div class='FormName'>Store Number <span class='FormRequired'>(Required)</span></div>
						<div class='FormField'><input class="FormTextBox" type="text" name="chrStoreNum" id="chrStoreNum" maxlength="5" value="<?=$info['chrStoreNum']?>" /></div>
	
						<div class='FormName'>E-mail Address <span class='FormRequired'>(Required)</span></div>
						<div class='FormField'><input class="FormTextBox" type="text" name="chrEmail" id="chrEmail" maxlength="80" value="<?=$info['chrEmail']?>" /></div>
	
						<div class='FormName'>Phone Number <span class='FormRequired'>(Required)</span></div>
						<div class='FormField'><input class="FormTextBox" type="text" name="chrPhone" id="chrPhone" maxlength="30" value="<?=$info['chrPhone']?>" /></div>
	
						<div class='FormName'>Division <span class='FormRequired'>(Required)</span></div>
						<div class='FormField'><input class="FormTextBox" type="text" name="chrDivision" id="chrDivision" maxlength="4" value="<?=$info['chrDivision']?>" /></div>
	
						<div class='FormName'>Geo <span class='FormRequired'>(Required)</span></div>
						<div class='FormField'>
							<select class='FormTextBox' id="idGeo" name='idGeo'>
									<option value="">Select from list</option>
								<?	while ($row = mysqli_fetch_assoc($geos)) {  ?>
									<option value='<?=$row['ID']?>'<?=($row['ID']==$info['idGeo'] ? " selected='selected'" : "" )?>><?=$row['chrGeo']?></option>
								<?	} ?>
							</select>
						</div>
	
						<div class='FormName'>Region <span class='FormRequired'>(Required)</span></div>
						<div class='FormField'><input class="FormTextBox" type="text" name="chrRegion" id="chrRegion" maxlength="75" value="<?=$info['chrRegion']?>" /></div>

						<div class='FormName'>Show Store in List <span class='FormRequired'>(Required, Does not Affect NSO/Remodel Section)</span></div>
						<div class='FormField'><input type="radio" name="bShow" id="bShow0" value='0' <?=($info['bShow']==0?'checked="checked"':'')?> /> Hide&nbsp;&nbsp;&nbsp;<input type="radio" name="bShow" id="bShow1" value='1' <?=($info['bShow']==1?'checked="checked"':'')?> /> Show</div>

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