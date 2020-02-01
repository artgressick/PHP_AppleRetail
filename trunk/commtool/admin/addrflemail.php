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
	
	if(isset($_POST['chrRFLName'])) { // When doing isset, use a required field.  Faster than the php count funtion.

		$q = "INSERT INTO RFLEmails SET 
			idGeo='". $_POST['idGeo'] ."',
			chrRFLName='". $_POST['chrRFLName'] ."',
			chrPHPPage='". $_POST['chrPHPPage'] ."',
			txtInstructions='". $_POST['txtInstructions'] ."',
			idRFLCategory='". $_POST['idRFLCategory'] ."',
			bVisable='". $_POST['bVisable'] . "'
		";
		database_query($q,"Insert into RFLEmails");
		
		//dtn: mysqli_connection is the value in the _lib file that mysqli needs in various functions to connect to the correct DB
		//dtn: newID is the ID that was added when the insert above was completed
		global $mysqli_connection;
		$newID = mysqli_insert_id($mysqli_connection);
				
		if($_POST['idContacts'] != '') {
			$ids = $_POST['idContacts']== '' ? array() : explode(',', $_POST['idContacts']);
			$contacts = "";
			foreach($ids as $id) { $contacts .= ($contacts == "" ? '' : ',')."('". $id ."','". $newID ."')";  }
			database_query("INSERT INTO RFLDistros (idRFLContact,idRFLEmail) VALUES ". $contacts,"inserting contacts");
		}
		
		// This is the code for inserting the Audit Page
		// Type 1 means ADD NEW RECORD, change the TABLE NAME also
		global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
		$newID = mysqli_insert_id($mysqli_connection);
				
		$q = "INSERT INTO Audit SET 
			idType=1, 
			idRecord='". $newID ."',
			txtNewValue='". $_POST['chrRFLName'] ."',
			dtDateTime=now(),
			chrTableName='RFLEmails',
			idUser='". $_SESSION['idUser'] ."'
		";
		database_query($q,"Insert audit");
		//End the code for History Insert
		
		header("Location: ". $_POST['moveTo']);
		die();
	}

	//dtn: This is for the sorting of the rows and columns.  We must set the default order and name
	include($BF. 'components/list/sortList.php'); 
	if(!isset($_REQUEST['sortCol'])) { $_REQUEST['sortCol'] = ""; } //dtn: This sets the default column order.  Asc by default.
	
	
	//This is needed for the nav_menu on top. We are setting the focus on the first text box of the page.
	$bodyParams = "document.getElementById('chrRFLName').focus()";


	// The Forms js is for all the error checking that is involved with the forms Add / Edit Pages
?>
<script language="JavaScript" type='text/javascript' src="<?=$BF?>includes/noticeadd.js"></script>
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

		if(total == 0) { notice(document.getElementById('chrRFLName').value, 'addrflemail.php', 'rflemails.php'); }
	}
</script>
<?
	include($BF. "includes/top.php");
	include($BF. "commtool/includes/admin_nav.php");
	include($BF. 'includes/noticeadd.php');
?>

<form name='idForm' id='idForm' action='' method="post">

	<div class='listHeader'>Add Email Template</div>
	<div class='greenInstructions'>Contacts are used in the distribution list.</div>
	
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
							<option value='<?=$row["ID"]?>'><?=$row['chrGeo']?></option>
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
	$userRights = database_query("SELECT ID,chrEmailCategory FROM RFLCategories WHERE !bDeleted ORDER BY chrEmailCategory", "getting Categories");
	while($row = mysqli_fetch_assoc($userRights)) {
?>
							<option value='<?=$row["ID"]?>'><?=$row['chrEmailCategory']?></option>
<?
	}
?>
						</select>
					</div>
					
					<div class='FormName'>Email Template Name <span class='FormRequired'>(Required)</span></div>
					<div class='FormField'><input type='text' name='chrRFLName' id='chrRFLName' size='35' /></div>
					
					<div class='FormName'>Visible</div>
					<div class='FormField'><input type="radio" name="bVisable" id="bVisable" value="0" checked="checked" />No &nbsp;&nbsp; <input type="radio" name="bVisable" id = "bVisable" value="1" /> />Yes</div>
					
					<div class='FormName'>PHP Template <span class='FormRequired'>(Required)</span></div>
					<div class='FormField'><input type='text' name='chrPHPPage' id='chrPHPPage' size='35' /></div>
				</td>
				<td width="50%" valign="top">

					<div>Select the contacts who should be included in this email.</div>
							<input type='button' value='Add...' onclick='newwin = window.open("popup-contacts.php?d=<?=urlencode(base64_encode('functioncall=contacts_add'))?>","new","width=600,height=400,resizable=1,scrollbars=1"); newwin.focus();'/>

							<input type='hidden' id='idContacts' name='idContacts' />
							<input type='hidden' id='chrContacts' name='chrContacts' />

							<table class='List' id='Contacts' style='width: 100%;' cellspacing="0" cellpadding="0">
								<thead>
									<tr>
										<th class='alignleft'>Contact</th>
										<th style='width: 1%;'></th>
										</tr>
									</thead>
									<tbody id='contactBody' name='contactBody'>
									</tbody>
								</table>

<script type="text/javascript">//<![CDATA[
function contacts_add(id, chr) 
{ 
	list_add('Contacts', 'idContacts', 'chrContacts', id, chr); 
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
					<div class='FormField'><textarea cols='90' rows='3' id='txtInstructions' name='txtInstructions'>Please fill out this form and send the email by pressing the button at the bottom of the page.</textarea></div>
				</td>
			</tr>
		</table>
		<input class='FormButtons' type='button' value='Add Email' onclick="error_check();" />
		<input type='hidden' name='moveTo' id='moveTo' />

	</div>

</form>
<?
	include($BF. 'commtool/includes/bottom.php');
?>
