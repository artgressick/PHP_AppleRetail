<?php
	$BF='../../';

	require($BF. '_lib.php');
	include($BF. "includes/meta.php");
		
	if (!isset($_REQUEST['idGeo']) && !isset($_SESSION['idAdminGeo']) || !is_numeric($_REQUEST['idGeo'])) { 
		$_REQUEST['idGeo'] = 1;
	} else if(!isset($_REQUEST['idGeo']) && isset($_SESSION['idAdminGeo'])) {
		$_REQUEST['idGeo'] = $_SESSION['idAdminGeo'];
	}
	
	$_SESSION['idAdminGeo'] = $_REQUEST['idGeo'];	
	include($BF. 'components/list/sortList.php'); 

	if(count($_POST)) {

	}


	
	$q = "";
	$results = database_query($q, 'Getting Info');
	
	$q = "SELECT * FROM Geos ORDER BY ID";
	$geos = database_query($q, "Getting all Geos");
	
	include($BF. "includes/top.php");
	include($BF. "calendar/includes/admin_nav.php"); 
	$TableName = "PNPPages"; //dtn:  This is the Database table that you will be setting the bDeleted statuses on.
	include($BF. 'includes/overlay.php');
?>

	<form name='idForm' id='idForm' action='' method="post">
	<div class='listHeader'>Information Here</div>
	<div class='greenFilter'>
	<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td style="text-align:left;"><input type='button' value='Add Child' onclick='window.location.href="add.php?id=0"' /></td>
			<td style="text-align:right;">
				<select id="idGeo" name="idGeo" onchange='location.href="index.php?idGeo="+this.value'>
					<option value=''>Select Geo</option>
				<? while ($row = mysqli_fetch_assoc($geos)) { ?>
					<option value='<?=$row['ID']?>'<?=($row['ID'] == $_REQUEST['idGeo'] ? ' selected="selected"' : "" )?>><?=$row['chrGeo']?></option>
				<?	} ?>
				</select>			
			</td>
		</tr>
	</table>
	</div>
	
	
	</td></tr></table></form>
<?
	include($BF."includes/bottom.php");
?>