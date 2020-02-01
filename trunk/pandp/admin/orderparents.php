<?php
	$BF='../../';

	require($BF. '_lib.php');
	include($BF. "includes/meta.php");
		// bPandP must be set to true, else show noaccess.php page
		if ($Security['bPandP'] == false) {
				header("Location: " . $BF . "pandp/noaccess.php");
				die();
		}
		
	if ((!isset($_REQUEST['idGeo']) || !is_numeric($_REQUEST['idGeo'])) && !isset($_SESSION['idAdminGeo'])) { 
		$_REQUEST['idGeo'] = 1;
	} else if(!isset($_REQUEST['idGeo']) && isset($_SESSION['idAdminGeo'])) {
		$_REQUEST['idGeo'] = $_SESSION['idAdminGeo'];
	}	

	if (count($_POST)) {

		$q = "SELECT ID, dOrder FROM PNPPages WHERE !bDeleted and bParent=1 AND idGeo='".$_REQUEST['idGeo']."' 
		ORDER BY dOrder, chrTitle";
		
		$tmp = database_query($q, "Getting All Parents");
			
		while ($row = mysqli_fetch_assoc($tmp)) {
		
			$tmpresult = database_query("UPDATE PNPPages SET dOrder='".$_POST['dOrder'.$row['ID']]."' WHERE idParent=".$row['ID'], "Update Order");

		}

		header("Location: index.php?idGeo=".$_SESSION['idAdminGeo']);
		die();

	}


	include($BF. 'components/list/sortList.php'); 
	
	$query = "SELECT PNPPages.*, chrGeo
	FROM PNPPages
	LEFT JOIN Geos ON PNPPages.idGeo=Geos.ID
	WHERE !bDeleted AND bParent=1 AND idGeo='".$_REQUEST['idGeo']."' 
	ORDER BY dOrder, chrTitle";
	
	$results = database_query($query, 'Getting All Parents');
	
	$q = "SELECT * FROM Geos ORDER BY ID";
	$geos = database_query($q, "Getting all Geos");
	

	include($BF. "includes/top.php");
	include($BF. "pandp/includes/admin_nav.php"); 
?>

	
	<div class='listHeader'>Order Policies & Procedure Parent Pages</div>
	<div class='greenFilter' style="text-align:right;">Show Geo: 
		
		<select id="idGeo" name="idGeo" onchange='location.href="orderparents.php?idGeo="+this.value'>
			<option value=''>Select Geo</option>
		<? while ($row = mysqli_fetch_assoc($geos)) { ?>
			<option value='<?=$row['ID']?>'<?=($row['ID'] == $_REQUEST['idGeo'] ? ' selected="selected"' : "")?>><?=$row['chrGeo']?></option>
		<?	} ?>
		</select>
	
	</div>
	<form enctype="multipart/form-data" action="" method="post" id="idForm">		
	<table class='List' id='List' style='width: 100%;'  cellpadding="0" cellspacing="0">
		<tr>	
			<th>Page Name</th>
			<th>Geo</th>
			<th>Page Order</th>
		</tr>
		



<? $count=0;	
	while ($row = mysqli_fetch_assoc($results)) { 
		$link = ""; 
?>
			<tr id='tr<?=$row['ID']?>' class='<?=($count++%2?'ListLineOdd':'ListLineEven')?>' 
			onmouseover='RowHighlight("tr<?=$row['ID']?>");' onmouseout='UnRowHighlight("tr<?=$row['ID']?>");'>
				<td style='cursor: pointer;'><?=$row['chrTitle']?></td>
				<td style='cursor: pointer;'><?=$row['chrGeo']?></td>		
				<td style='cursor: pointer;'><input type="text" name="dOrder<?=$row['ID']?>" id="dOrder<?=$row['ID']?>" size="5" value="<?=$row['dOrder']?>" /></td>
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