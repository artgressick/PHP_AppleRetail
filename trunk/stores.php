<?php
	$BF='';
	$auth_not_required = true;
	require($BF. '_lib.php');


	if(isset($_POST['idStore']) && is_numeric($_POST['idStore'])) { // When doing isset, use a required field.  Faster than the php count funtion.
		unset($_SESSION['refer']);
		$storeinfo = database_query("SELECT RetailStores.ID, RetailStores.chrName, RetailStores.chrStoreNum, RetailStores.chrEmail, RetailStores.idGeo, Geos.chrEmailAddress 
									FROM RetailStores 
									JOIN Geos ON RetailStores.idGeo=Geos.ID
									WHERE RetailStores.ID=".$_POST['idStore'], "Getting Store Information Needed",1);
		
		$_SESSION['idStore'] = $storeinfo['ID'];
		$_SESSION['chrStoreName'] = $storeinfo['chrName'];
		$_SESSION['chrStoreNum'] = $storeinfo['chrStoreNum'];
		$_SESSION['chrStoreEmail'] = $storeinfo['chrEmail'];
		$_SESSION['idGeo'] = $storeinfo['idGeo'];
		$_SESSION['chrFromEmail'] = $storeinfo['chrEmailAddress'];
		$_SESSION['refer'] = "";
		
		if ($_POST['refer'] == "") { $_POST['refer'] = $BF."index.php"; }
		
		header('Location: '.$_POST['refer']);
		die();

	}

	include($BF. "includes/meta.php");
	// Lets get all the stores
	$q = "SELECT ID, chrName FROM RetailStores WHERE !bDeleted AND bShow ORDER BY chrName";
	$stores = database_query($q, "Getting All Stores");

	/*dtn:  This is where any javascript should go */
?>
	<script language="JavaScript" src="<?=$BF?>includes/forms.js"></script>
	<script language="javascript">
		function error_check() {
			if(total != 0) { reset_errors(); } 

			var total=0;

			total += ErrorCheck('idStore', "You must Select a Store.");

			if(total == 0) { document.getElementById('idForm').submit(); }
		}

	</script>
<?
	include($BF. "includes/top.php");

		// Show Selection Page
?>
<table width="924" border="0" cellpadding="0" cellspacing="0" class="home_content">
	<tr>
		<td width="100%">
			<div>
		<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0"  style="height:100%;">
        	<tr>
          		<td align="left" background="<?=$BF?>images/ebuilder_topbg.gif"><img src="<?=$BF?>images/ebuilder_topleft.gif" width="9" height="55" /></td>
          		<td width="100%" align="center" valign="top" background="<?=$BF?>images/ebuilder_topbg.gif"><div style="margin-top:6px; font-size:12px;">Store Selection</div></td>
          		<td align="right" background="<?=$BF?>images/ebuilder_topbg.gif"><img src="<?=$BF?>images/ebuilder_topright.gif" width="9" height="55" /></td>
        	</tr>

			<tr>
				<td colspan="3" class="greenEmailInstructions">To continue you must select a Store from the list</td>
			</tr>
			<tr>			
				<td colspan="3" class="emailbody" style="height:100%;" >
				<div class='innerbody' style="text-align:center;">
					<form enctype="multipart/form-data" action="" method="post" id="idForm">
					<!-- This is the main page form -->
						<div id="errors"></div>
						<table width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="left">
									<div class='FormName'>Please Select your Store from the list <span class='FormRequired'>(Required)</span></div>
									<div class='FormField'>	
									<select class='FormField' id="idStore" name='idStore' style="width:200px;" >
											<option value=''>Select Store</option>					
										<? while ($row = mysqli_fetch_assoc($stores)) { ?>
											<option value='<?=$row['ID']?>'><?=$row['chrName']?></option>
										<?	} ?>
									</select>
									</div>
								</td>
							</tr>
						</table>
						<div class='FormButtons'><input style="margin-top:15px;" type="button" value="Submit" onclick='error_check();' /></div>
						<input type="hidden" id="refer" name="refer" value="<?=@$_SESSION['refer']?>" />
					</div>
					</form>
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
<?
	include($BF. "includes/bottom.php");
?>
