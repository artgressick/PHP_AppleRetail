<?php
	$BF='../../';

	require($BF. '_lib.php');
	include($BF. "includes/meta.php");
		// bPandP must be set to true, else show noaccess.php page
		if ($Security['bPandP'] == false) {
				header("Location: " . $BF . "pandp/noaccess.php");
				die();
		}
				
		if (!isset($_REQUEST['id']) || !is_numeric($_REQUEST['id'])) {
			ErrorPage("pandp/admin/index.php","Invalid Page");
		}
		
	$tmp = database_query("SELECT chrTitle FROM PNPPages WHERE ID=".$_REQUEST['id']." LIMIT 1", "Getting Page Title", 1);
	($tmp['chrTitle'] == "" ? ErrorPage("commtool/categories.php","Invalid Page") : "");
	$_SESSION['chrReferName'] = $tmp['chrTitle'];
	

	//dtn: This is for the sorting of the rows and columns.  We must set the default order and name
	include($BF. 'components/list/sortList.php'); 
	if(!isset($_REQUEST['sortCol'])) { $_REQUEST['sortCol'] = "dtDatetime"; } //dtn: This sets the default column order.  Asc by default.


	$query = "SELECT Audit.ID, idType, idRecord, idUser, dtDatetime, chrColumnName, txtOldValue, txtNewValue, chrFirst, chrLast
	FROM Audit
	JOIN Users ON Audit.idUser=Users.ID
	WHERE chrTableName='PNPPages' AND idRecord=".$_REQUEST['id']."
	ORDER BY ". $_REQUEST['sortCol'] ." ". $_REQUEST['ordCol'];
	
	$results = database_query($query, 'Getting History');
	
	$geos = database_query("SELECT ID, chrGeo FROM Geos ORDER BY ID", "Getting all Geos");
	
	while ($row = mysqli_fetch_assoc($geos)) {
	
		$Geos[$row['ID']] = $row['chrGeo'];
	
	}
	
	$parents = database_query("SELECT ID, chrTitle FROM PNPPages WHERE bParent ORDER BY ID","Getting ALL Parents");

	while ($row = mysqli_fetch_assoc($parents)) {
	
		$Parents[$row['ID']] = $row['chrTitle'];
	
	}

	include($BF. "includes/top.php");
	include($BF. "pandp/includes/admin_nav.php"); 
?>

	<div class='listHeader'>History of Changes For Page: <?=$_SESSION['chrReferName']?></div>
	<div class='greenFilter'>
	<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td>
				This is a list of all changes for the selected Page.	
			</td>
		</tr>
	</table>
	</div>
		
	<table class='List' id='List' style='width: 100%;'  cellpadding="0" cellspacing="0">
		<tr>	
<?
	sortList('Date', 'dtDatetime', '', 'id='.$_REQUEST['id']);
	sortList('User', 'chrLast', '', 'id='.$_REQUEST['id']);
?>
			<th>Type</th>
<?
	sortList('Applied To', 'chrColumnName', '', 'id='.$_REQUEST['id']);
	sortList('Old Value', 'txtOldValue', '', 'id='.$_REQUEST['id']);
	sortList('New Value', 'txtNewValue', '', 'id='.$_REQUEST['id']);
?>
		</tr>

<?
	$count=0;	
	while ($row = mysqli_fetch_assoc($results)) { 
		$link = ""; 
?>
			<tr id='tr<?=$row['ID']?>' class='<?=($count++%2?'ListLineOdd':'ListLineEven')?>' onmouseover='RowHighlight("tr<?=$row['ID']?>");' onmouseout='UnRowHighlight("tr<?=$row['ID']?>");'>
				<td style='cursor: pointer; vertical-align:top;' onclick='location.href="<?=$link?>";'><?=date('M j, Y - g:i a',strtotime($row['dtDatetime']))?></td>
				<td style='cursor: pointer; vertical-align:top;' onclick='location.href="<?=$link?>";'><?=$row['chrLast']?>, <?=$row['chrFirst']?></td>
				<td style='cursor: pointer; vertical-align:top;' onclick='location.href="<?=$link?>";'><?=($row['idType'] == 1 ? "New" : ($row['idType'] == 2 ? "Change" : "Delete" ))?></td>
				<td style='cursor: pointer; vertical-align:top;' onclick='location.href="<?=$link?>";'><?=$row['chrColumnName']?></td>
				<td style='cursor: pointer; vertical-align:top;' onclick='location.href="<?=$link?>";'>
<?
		if ($row['chrColumnName'] == "idGeo") {
			echo $Geos[$row['txtOldValue']];
		} else if ($row['chrColumnName'] == "idParent") {
			echo $Parents[$row['txtOldValue']];
		} else if ($row['txtOldValue'] == "" || $row['txtOldValue'] == NULL) {
			echo "<i>Empty</i>";
		} else {
			echo nl2br($row['txtOldValue']);
		}
?>
				</td>
				<td style='cursor: pointer; vertical-align:top;' onclick='location.href="<?=$link?>";'>
<?
		if ($row['chrColumnName'] == "idGeo") {
			echo $Geos[$row['txtNewValue']];
		} else if ($row['chrColumnName'] == "idParent") {
			echo $Parents[$row['txtNewValue']];
		} else if ($row['txtNewValue'] == "" || $row['txtNewValue'] == NULL) {
			echo "<i>Empty</i>";
		} else {
			echo nl2br($row['txtNewValue']);
		}
?>
				</td>
			</tr>
<?
	} 
if($count == 0) { ?>
			<tr>
				<td align="center" colspan='6' height="20">No History To Display</td>
			</tr>
<?	} ?>
	</table>
	</td></tr></table>
<?
	include($BF."includes/bottom.php");
?>