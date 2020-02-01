<?php
	$BF='../';
	require($BF. '_lib.php');
	// bSuperAdmin must be set to true, else show noaccess.php page
		if ($Security['bSuperAdmin'] == false) {
				header("Location: " . $BF . "noaccess.php");
				die();
		}

	//dtn: This is for the sorting of the rows and columns.  We must set the default order and name
	include($BF. 'components/list/sortList.php'); 
	if(!isset($_REQUEST['sortCol'])) { $_REQUEST['sortCol'] = "chrName"; } //dtn: This sets the default column order.  Asc by default.
	
	
	//jms: Query for List View
	$q = "SELECT ID, chrStoreNum, chrName FROM RetailStores WHERE !bDeleted ORDER BY ". $_REQUEST['sortCol'] ." ". $_REQUEST['ordCol'];
	$stores = database_query($q, "Getting Stores List");

	/*dtn:  This is where any javascript should go */
?>
<script language="JavaScript" type='text/javascript' src="<?=$BF?>includes/overlays.js"></script>
<?	
	include($BF. "includes/meta.php");
	include($BF. "includes/top.php");
	include($BF. "admin/includes/admin_nav.php");	


	//dtn: This is the include file for the overlay to show that the delete is working.
	$TableName = "RetailStores"; //dtn:  This is the Database table that you will be setting the bDeleted statuses on.
	include($BF. 'includes/overlay.php');

	//jms: Start HTML Below
?>

	<div class='listHeader'>Stores</div>
	<div class='greenFilter'><input type='button' value='Add Store' onclick='window.location.href="addstore.php"' /></div>
		
	<table class='List' id='List' style='width: 100%;'  cellpadding="0" cellspacing="0">
		<tr>
			<? sortList('Store Name', 'chrName'); ?>
			<? sortList('Store Number', 'chrStoreNum'); ?>
			<th><img src="<?=$BF?>images/options.gif"></th>
		</tr>
<? $count=0;	
	while ($row = mysqli_fetch_assoc($stores)) { 
		$link = "editstore.php?id=".$row['ID']; 
?>
			<tr id='tr<?=$row['ID']?>' class='<?=($count++%2?'ListLineOdd':'ListLineEven')?>' 
			onmouseover='RowHighlight("tr<?=$row['ID']?>");' onmouseout='UnRowHighlight("tr<?=$row['ID']?>");'>
				<td style='cursor: pointer;' onclick='location.href="<?=$link?>";'><?=$row['chrName']?></td>
				<td style='cursor: pointer;' onclick='location.href="<?=$link?>";'><?=$row['chrStoreNum']?></td>				
				<td class='options'><div class='deleteImage' onmouseover='document.getElementById("deleteButton<?=$row['ID']?>").src="<?=$BF?>images/button_delete_on.png"' onmouseout='document.getElementById("deleteButton<?=$row['ID']?>").src="<?=$BF?>images/button_delete.png"'>
				<a href="javascript:warning(<?=$row['ID']?>, '<?=addslashes($row['chrName'])?>');"><img id='deleteButton<?=$row['ID']?>' src='<?=$BF?>images/button_delete.png' alt='delete button' /></a>
				</div></td>			
			</tr>
<?	} 
if($count == 0) { ?>
			<tr>
				<td align="center" colspan='3' height="20">No stores to display</td>
			</tr>
<?	} ?>
	</table>

<?
	include($BF. "admin/includes/bottom.php");
?>