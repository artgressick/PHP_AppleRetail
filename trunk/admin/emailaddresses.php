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
	
	//jms: Query for List View
	$q = "SELECT ID, chrGeo, chrEmailAddress FROM Geos ORDER BY ID";
	$results = database_query($q, "Getting Stores List");

	/*dtn:  This is where any javascript should go */
?>
<script language="JavaScript" type='text/javascript' src="<?=$BF?>includes/overlays.js"></script>
<?	
	include($BF. "includes/meta.php");
	include($BF. "includes/top.php");
	include($BF. "admin/includes/admin_nav.php");	


	//jms: Start HTML Below
?>

	<div class='listHeader'>Geo "FROM" Email Addresses</div>
	<div class='greenFilter'>Select the Geo to edit the default E-mail Address.</div>
		
	<table class='List' id='List' style='width: 100%;'  cellpadding="0" cellspacing="0">
		<tr>
			<th>Geo</th>
			<th>E-mail Address</th>
		</tr>
<? $count=0;	
	while ($row = mysqli_fetch_assoc($results)) { 
		$link = "editemailaddress.php?id=".$row['ID']; 
?>
			<tr id='tr<?=$row['ID']?>' class='<?=($count++%2?'ListLineOdd':'ListLineEven')?>' 
			onmouseover='RowHighlight("tr<?=$row['ID']?>");' onmouseout='UnRowHighlight("tr<?=$row['ID']?>");'>
				<td style='cursor: pointer;' onclick='location.href="<?=$link?>";'><?=$row['chrGeo']?></td>
				<td style='cursor: pointer;' onclick='location.href="<?=$link?>";'><?=$row['chrEmailAddress']?></td>				
			</tr>
<?	} 
if($count == 0) { ?>
			<tr>
				<td align="center" colspan='3' height="20">No Email Addresses to display</td>
			</tr>
<?	} ?>
	</table>

<?
	include($BF. "admin/includes/bottom.php");
?>