<?	
	$BF = '../../';
	require($BF. '_lib.php');
	include($BF. 'includes/meta.php');	
		// bCommtool must be set to true, else show noaccess.php page
		if ($Security['bCommtool'] == false) {
				header("Location: " . $BF . "commtool/noaccess.php");
				die();
		}
	(!isset($_REQUEST['id']) || !is_numeric($_REQUEST['id']) ? ErrorPage("commtool/admin/rflemails.php","Invalid Page") : "");
	// Get info to populate fields. Also ... If the old information is the same as the current, why update it?  Get the old information to test this against.
	$info = database_query("SELECT * FROM RFLEmails WHERE ID=". $_REQUEST['id'],"getting RFL Email Information",1);
	($info['ID'] == "" ? ErrorPage("commtool/admin/rflemails.php","Invalid Page") : "");

	// If a post occured
	if(isset($_POST['chrRFLName'])) { // When doing isset, use a required field.  Faster than the php count funtion.

		// Set the basic values to be used.
		//   $table = the table that you will be connecting to to check / make the changes
		//   $mysqlStr = this is the "mysql string" that you are going to be using to update with.  This needs to be set to "" (empty string)
		//   $sudit = this is the "audit string" that you are going to be using to update with.  This needs to be set to "" (empty string)
		$table = 'RFLEmails';
		$mysqlStr = '';
		$audit = '';

		// "List" is a way for php to split up an array that is coming back.  
		// "set_strs" is a function (bottom of the _lib) that is set up to look at the old information in the DB, and compare it with
		//    the new information in the form fields.  If the information is DIFFERENT, only then add it to the mysql string to update.
		//    This will ensure that only information that NEEDS to be updated, is updated.  This means smaller and faster DB calls.
		//    ...  This also will ONLY add changes to the audit table if the values are different.
		list($mysqlStr,$audit) = set_strs($mysqlStr,'idGeo',$info['idGeo'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'chrRFLName',$info['chrRFLName'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'chrPHPPage',$info['chrPHPPage'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'idRFLCategory',$info['idRFLCategory'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'txtInstructions',$info['txtInstructions'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'bVisable',$info['bVisable'],$audit,$table,$_POST['id']);

		// if nothing has changed, don't do anything.  Otherwise update / audit.
		if($mysqlStr != '') { list($str,$aud) = update_record($mysqlStr, $audit, $table, $_POST['id']); }

		database_query("DELETE FROM RFLDistros WHERE idRFLEmail='" . $_POST['id'] . "'", 'delete contacts');
		if($_POST['idContacts'] != '') {		
			$ids = $_POST['idContacts']=='' ? array() : explode(',', $_POST['idContacts']);
			$prod = "INSERT INTO EventProducts (idEvent,idProduct,intEventSeries) VALUES ";
			$contacts = "";
			foreach($ids as $id) { $contacts .= ($contacts == "" ? '' : ',')."('". $id ."','". $_POST['id'] ."')";  }
			database_query("INSERT INTO RFLDistros (idRFLContact,idRFLEmail) VALUES ". $contacts,"inserting contacts");
		}

		header("Location: ". $_POST['moveTo']);
		die();
	}


	//dtn: This is for the sorting of the rows and columns.  We must set the default order and name
	include($BF. 'components/list/sortList.php'); 
	if(!isset($_REQUEST['sortCol'])) { $_REQUEST['sortCol'] = ""; } //dtn: This sets the default column order.  Asc by default.


?>
<script language="JavaScript" type='text/javascript' src="<?=$BF?>includes/noticeedit.js"></script>
<script language="JavaScript" src="<?=$BF?>includes/listadd.js"></script>
<script language="JavaScript" src="<?=$BF?>includes/forms.js"></script>

<script language="javascript">
	function error_check(addy) {
		if(total != 0) { reset_errors(); }  

		var total=0;
		
		total += ErrorCheck('idGeo', "You must choose a Geo.");
		total += ErrorCheck('idRFLCategory', "You must choose a Category.");
		total += ErrorCheck('chrRFLName', "You must enter a RFL Email Name.");
		total += ErrorCheck('chrPHPPage', "You must enter a PHP Template.");
		if(!document.forms[0].bVisable[0].checked && !document.forms[0].bVisable[1].checked) {
			total += CustomError("Must choose visible");
		}

		if(total == 0) { notice(document.getElementById('chrRFLName').value, 'rflemails.php'); }
	}
</script>
<?
	
	//This is needed for the nav_menu on top. We are setting the focus on the first text box of the page.
	$bodyParams = "document.getElementById('chrRFLName').focus()";

	include($BF. "includes/top.php");
	include($BF. "commtool/includes/admin_nav.php");
	include($BF. 'includes/noticeedit.php');
?>
<form name='idForm' id='idForm' action='' method="post">

	<div class='listHeader'>Edit Email Template</div>
	<div class='greenInstructions'>Update the information below and click on update.</div>
	
		<div id='errors'></div>
		
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td width="50%" valign="top">
					<div class='FormName'>Available to Geo <span class='FormRequired'>(Required)</span></div>
					<div class='FormField'>
						<select id='idGeo' name='idGeo'>
							<option value=''>- Select Geo -</option>
<?
	$userRights = database_query("SELECT ID,chrGeo FROM Geos", "getting Geo");
	while($row = mysqli_fetch_assoc($userRights)) {
?>
							<option<?=($info['idGeo'] == $row["ID"] ? ' selected ' : '')?> value='<?=$row["ID"]?>'><?=$row['chrGeo']?></option>
<?
	}
?>
						</select>
					</div>
                    
                    <div class='FormName'>Email Category <span class='FormRequired'>(Required)</span></div>
					<div class='FormField'>
						<select id='idRFLCategory' name='idRFLCategory'>
							<option value=''>- Select Email Category -</option>
<?
	$userRights = database_query("SELECT ID,chrEmailCategory FROM RFLCategories", "getting Categories");
	while($row = mysqli_fetch_assoc($userRights)) {
?>
							<option<?=($info['idRFLCategory'] == $row["ID"] ? ' selected ' : '')?> value='<?=$row["ID"]?>'><?=$row['chrEmailCategory']?></option>
<?
	}
?>
						</select>
					</div>
					
					<div class='FormName'>Email Template Name <span class='FormRequired'>(Required)</span></div>
					<div class='FormField'><input type='text' name='chrRFLName' id='chrRFLName' size='35' value='<?=$info['chrRFLName']?>' /></div>
					
					<div class='FormName'>Visible</div>
					<div class='FormField'><input type="radio" name="bVisable" id="bVisable" value="0" <?=($info['bVisable'] == 0 ? 'checked="checked"' : "")?> />No &nbsp;&nbsp; <input type="radio" name="bVisable" id = "bVisable" value="1" <?=($info['bVisable'] == 1 ? 'checked="checked"' : "")?> />Yes</div>
									
					<div class='FormName'>PHP Template <span class='FormRequired'>(Required)</span></div>
					<div class='FormField'><input type='text' name='chrPHPPage' id='chrPHPPage' size='35' value='<?=$info['chrPHPPage']?>' /></div>
					
				</td>
				<td width="50%" valign="top">

				<div>Select the contacts who should be included in this email.</div>
					<input type='button' value='Add...' onclick='newwin = window.open("popup-contacts.php?d=<?=urlencode(base64_encode('functioncall=contacts_add'))?>","new","width=425,height=400,resizable=1,scrollbars=1"); newwin.focus();'/>

					<table class='List' id='Contacts' style='width: 100%;' cellspacing="0" cellpadding="0">
						<tr>
							<th class='alignleft'>Contact</th>
							<th style='width: 1%;'>Remove</th>
						</tr>
<?	$results = database_query("SELECT RFLContacts.ID,chrFirst,chrLast 
					FROM RFLDistros 
					JOIN RFLContacts ON RFLContacts.ID=RFLDistros.idRFLContact 
					WHERE !RFLContacts.bDeleted AND !RFLDistros.bDeleted AND idRFLEmail=".$_REQUEST['id']
				,"getting contacts");	

	$ids = array();
	$chrs = array();
	$count = 0;
	while($row = mysqli_fetch_assoc($results)) {
		$ids[] = $row['ID'];
		$chrs[] = $row['chrFirst'] ." ". $row['chrLast'];
?>
							<tr id='tr<?=$row["ID"]?>' class='<?=($count++%2?'ListLineOdd':'ListLineEven')?>'>
								<td><?=$row['chrFirst'] ." ". $row['chrLast']?></td>
								<td class='alignright'>
									<div class='deleteImage' onmouseover='document.getElementById("deleteButton<?=$row['ID']?>").src="<?=$BF?>images/button_delete_on.png"' onmouseout='document.getElementById("deleteButton<?=$row['ID']?>").src="<?=$BF?>images/button_delete.png"'  onclick="list_remove('Contacts', 'idContacts', 'chrContacts', <?=$row['ID']?>, this);repaint('Contacts')">
<img id='deleteButton<?=$row['ID']?>' src='<?=$BF?>images/button_delete.png' alt='delete button' /></div>
									

							</tr>
<?			} ?>
						</table>

						<input type='hidden' id='idContacts' name='idContacts' value='<?=implode(',',$ids)?>' />
						<input type='hidden' id='chrContacts' name='chrContacts' value='<?=implode(',',$chrs)?>' />


<script type="text/javascript">//<![CDATA[
function contacts_add(id, chr) 
{ 
list_add('Contacts', 'idContacts', 'chrContacts', id, chr); 
repaint('Contacts');
}
// ]]></script>

		<!-- End of the section -->
				</div>
			</div>



				</td>
			</tr>
			<tr>
				<td colspan='2'>
					<div class='FormName'>Email Template Instructions</div>
					<div class='FormField'><textarea cols='90' rows='3' id='txtInstructions' name='txtInstructions'><?=$info['txtInstructions']?></textarea></div>
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