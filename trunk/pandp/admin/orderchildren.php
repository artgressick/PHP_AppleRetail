<?php
	$BF='../../';

	require($BF. '_lib.php');
	include($BF. "includes/meta.php");
		// bPandP must be set to true, else show noaccess.php page
		if ($Security['bPandP'] == false) {
				header("Location: " . $BF . "pandp/noaccess.php");
				die();
		}
		
	if (!isset($_REQUEST['idParent'])) { $_REQUEST['idParent'] = 1; }		

	if (count($_POST)) {

		$q = "SELECT ID, dOrder FROM PNPPages WHERE !bDeleted AND bParent != 1 AND idParent='".$_REQUEST['idParent']."' 
		ORDER BY dOrder, chrTitle";
		
		$tmp = database_query($q, "Getting All Children for Parent");
			
		while ($row = mysqli_fetch_assoc($tmp)) {
		
			$tmpresult = database_query("UPDATE PNPPages SET dOrderChild='".$_POST['dOrderChild'.$row['ID']]."' WHERE ID=".$row['ID'], "Update Order");

		}

		header("Location: index.php?idGeo=".$_SESSION['idAdminGeo']);
		die();

	}


	include($BF. 'components/list/sortList.php'); 
	
	$query = "SELECT PNPPages.*
	FROM PNPPages
	WHERE !bDeleted AND bParent != 1 AND idParent='".$_REQUEST['idParent']."' 
	ORDER BY dOrderChild, chrTitle";
	
	$results = database_query($query, 'Getting All Children');
	
	$q = "SELECT PNPPages.ID, chrTitle, chrGeo FROM PNPPages JOIN Geos ON PNPPages.idGeo=Geos.ID WHERE !bDeleted and bParent=1 ".(isset($_SESSION['idAdminGeo']) && is_numeric($_SESSION['idAdminGeo'])?" AND Geos.ID=".$_SESSION['idAdminGeo']." ":"")."ORDER BY dOrder, chrTitle";
	$dropmenu = database_query($q, "Getting all Parents");
	

	include($BF. "includes/top.php");
	include($BF. "pandp/includes/admin_nav.php"); 
?>

	
	<div class='listHeader'>Order Policies & Procedure Children Pages</div>
	<div class='greenFilter' style="text-align:right;">Show Children for Parent: 
		
		<select id="idParent" name="idParent" onchange='location.href="orderchildren.php?idParent="+this.value'>
			<option value=''>Select Parent</option>
		<? while ($row = mysqli_fetch_assoc($dropmenu)) { ?>
			<option value='<?=$row['ID']?>'<?=($row['ID'] == $_REQUEST['idParent'] ? ' selected="selected"' : "" )?>><?=$row['chrTitle']?> (<?=$row['chrGeo']?>)</option>
		<?	} ?>
		</select>
	
	</div>
	<form enctype="multipart/form-data" action="" method="post" id="idForm">		
	<table class='List' id='List' style='width: 100%;'  cellpadding="0" cellspacing="0">
		<tr>	
			<th>Page Name</th>
			<th>Page Order</th>
		</tr>
		



<? $count=0;	
	while ($row = mysqli_fetch_assoc($results)) { 
		$link = ""; 
?>
			<tr id='tr<?=$row['ID']?>' class='<?=($count++%2?'ListLineOdd':'ListLineEven')?>' 
			onmouseover='RowHighlight("tr<?=$row['ID']?>");' onmouseout='UnRowHighlight("tr<?=$row['ID']?>");'>
				<td style='cursor: pointer;'><?=$row['chrTitle']?></td>
				<td style='cursor: pointer;'><input type="text" name="dOrderChild<?=$row['ID']?>" id="dOrderChild<?=$row['ID']?>" size="5" value="<?=$row['dOrderChild']?>" /></td>
			</tr>
<?	} 
if($count == 0) { ?>
			<tr>
				<td align="center" colspan='3' height="20">No Pages To Display</td>
			</tr>
<?	} ?>
	</table>
	<div class='FormButtons'><input style="margin-top:15px;" type="Submit" value="Save Order" /></div>
	</form>






	
	</td></tr></table>
	<?
	include($BF."includes/bottom.php");
?>