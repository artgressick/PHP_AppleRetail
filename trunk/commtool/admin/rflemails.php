<?php
	$BF='../../';
	require($BF. '_lib.php');
		// bCommtool must be set to true, else show noaccess.php page
		if ($Security['bCommtool'] == false) {
				header("Location: " . $BF . "commtool/noaccess.php");
				die();
		}
	
	
	
	include($BF. "includes/meta.php");
	
	if (isset($_POST['id']) && $_POST['id'] != "") {
		$set = database_query("
			UPDATE RFLEmails 
			SET bVisable='".$_POST['bVisable']."'
			WHERE ID='".$_POST['id']."'","Updating bVisable Value");
	}

	
	
	//dtn: This is for the sorting of the rows and columns.  We must set the default order and name
	include($BF. 'components/list/sortList.php'); 
	if(!isset($_REQUEST['sortCol'])) { $_REQUEST['sortCol'] = "chrRFLName"; } //dtn: This sets the default column order.  Asc by default.

	$result = database_query("SELECT RFLEmails.ID, chrGeo, chrRFLName, bVisable, chrPHPPage
		FROM RFLEmails 
		JOIN Geos ON RFLEmails.idGeo = Geos.ID
		WHERE !bDeleted
		ORDER BY ". $_REQUEST['sortCol'] ." ". $_REQUEST['ordCol']
		,"Getting RFLEmails");

	/*dtn:  This is where any javascript should go */
?>
<script language="JavaScript" type='text/javascript' src="<?=$BF?>includes/overlays.js"></script>
<?
	include($BF. "includes/top.php");
	include($BF. "commtool/includes/admin_nav.php");

	//dtn: This is the include file for the overlay to show that the delete is working.
	$TableName = "RFLEmails"; //dtn:  This is the Database table that you will be setting the bDeleted statuses on.
	include($BF. 'includes/overlay.php');
?>
	<form name='idForm' id='idForm' action='' method="post">
	<div class='listHeader'>Email Templates</div>
	<div class='greenFilter'><input type='button' value='Add Email Template' onclick='window.location.href="addrflemail.php"' /></div>
		
	<table class='List' id='List' style='width: 100%;'  cellpadding="0" cellspacing="0">
		<tr>
			<th width="5"></th>
			<? sortList('Email Template', 'chrRFLName'); ?>
			<? sortList('GEO', 'chrGeo'); ?>
			<th>Preview</th>
			<th><img src="<?=$BF?>images/options.gif"></th>
		</tr>
<? $count=0;	
	while ($row = mysqli_fetch_assoc($result)) { 
		$link = "editrflemail.php?id=".$row['ID']; 
		$visablelink = "onclick=\"
						document.getElementById('id').value='" . $row['ID'] . "';
						document.getElementById('bVisable').value='" . ($row['bVisable'] == 1 ? "0" : "1") . "'; 
						document.getElementById('idForm').submit();\" style='cursor:pointer;'";
			
		if ( $row['bVisable'] == 1 ) { $imagename = "on"; } else { $imagename = "off"; }
?>
			<tr id='tr<?=$row['ID']?>' class='<?=($count++%2?'ListLineOdd':'ListLineEven')?>' 
			onmouseover='RowHighlight("tr<?=$row['ID']?>");' onmouseout='UnRowHighlight("tr<?=$row['ID']?>");'>
				
				<td class='options' width="5">
					<img id='visiblebutton<?=$row['ID']?>' src='<?=$BF?>images/<?=$imagename?>.png' alt='Page is <?=($row['bVisable'] == 1 ? "" : "Not ")?>Visible' <?=$visablelink?> />
				</td>	
							
				<td style='cursor: pointer;' onclick='location.href="<?=$link?>";'><?=$row['chrRFLName']?></td>
				
				<td style='cursor: pointer;' onclick='location.href="<?=$link?>";'><?=$row['chrGeo']?></td>
				
				<td style='cursor: pointer;' onclick="window.open('<?=$BF?>commtool/<?=$row['chrPHPPage']?>?id=<?=$row['ID']?>&bPreview=1');">Preview Link</td>
				
				<td class='options'><div class='deleteImage' onmouseover='document.getElementById("deleteButton<?=$row['ID']?>").src="<?=$BF?>images/button_delete_on.png"' onmouseout='document.getElementById("deleteButton<?=$row['ID']?>").src="<?=$BF?>images/button_delete.png"'>
				<a href="javascript:warning(<?=$row['ID']?>, '<?=addslashes($row['chrRFLName'])?>');"><img id='deleteButton<?=$row['ID']?>' src='<?=$BF?>images/button_delete.png' alt='delete button' /></a>
				</div></td>			
			</tr>
<?	} 
if($count == 0) { ?>
			<tr>
				<td align="center" colspan='3' height="20">No RFL Emails to display</td>
			</tr>
<?	} ?>
	</table>
	<input type="hidden" name="bVisable" id="bVisable" />
	<input type="hidden" name="id" id="id" />
	</form>

		
<?
	include($BF. "commtool/includes/bottom.php");
?>
