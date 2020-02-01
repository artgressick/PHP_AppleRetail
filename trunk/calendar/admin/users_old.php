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
	if(!isset($_REQUEST['sortCol'])) { $_REQUEST['sortCol'] = "chrLast,chrFirst"; }

	if(count($_POST)) {

	}


	
	$q = "SELECT CalendarAccess.ID,Users.chrLast,chrFirst 
			FROM Users
			JOIN CalendarAccess on CalendarAccess.idUser=Users.ID
			WHERE !Users.bDeleted 
			ORDER BY ". $_REQUEST['sortCol'] ." ". $_REQUEST['ordCol'];
	$results = database_query($q, 'Getting Info');
	
?>
<script language="JavaScript" type='text/javascript' src="<?=$BF?>includes/overlaysnew.js"></script>
<?	
	
	include($BF. "includes/top.php");
	include($BF. "calendar/includes/admin_nav.php"); 
	$TableName = "CalendarAccess"; //dtn:  This is the Database table that you will be setting the bDeleted statuses on.
	$postType = "permDelete";
	include($BF. 'calendar/includes/overlay.php');
?>

	<form name='idForm' id='idForm' action='' method="post">

	<div class='listHeader'>Calendar Users</div>
	<div class='greenFilter'><input type='button' value='Add Calendar Users' onclick='window.location.href="adduser.php"' /></div>
		
	<table class='List' id='List' style='width: 100%;'  cellpadding="0" cellspacing="0">
		<tr>
			<? sortList('Last Name', 'chrLast'); ?>
			<? sortList('First Name', 'chrFirst'); ?>
			<th><img src="<?=$BF?>images/options.gif"></th>
		</tr>
<? $count=0;	
	while ($row = mysqli_fetch_assoc($results)) { 
		$link = 'location.href="edituser.php?id='.$row['ID'].'";'; 
?>
			<tr id='tr<?=$row['ID']?>' class='<?=($count++%2?'ListLineOdd':'ListLineEven')?>' 
			onmouseover='RowHighlight("tr<?=$row['ID']?>");' onmouseout='UnRowHighlight("tr<?=$row['ID']?>");'>
				<td style='cursor: pointer;' onclick='"<?=$link?>'><?=$row['chrLast']?></td>
				<td style='cursor: pointer;' onclick='"<?=$link?>'><?=$row['chrFirst']?></td>				
				<td class='options'><div class='deleteImage' onmouseover='document.getElementById("deleteButton<?=$row['ID']?>").src="<?=$BF?>images/button_delete_on.png"' onmouseout='document.getElementById("deleteButton<?=$row['ID']?>").src="<?=$BF?>images/button_delete.png"'>
				<a href="javascript:warning(<?=$row['ID']?>, '<?=addslashes($row['chrFirst'] ." ". $row['chrLast'])?>');"><img id='deleteButton<?=$row['ID']?>' src='<?=$BF?>images/button_delete.png' alt='delete button' /></a>
				</div></td>			
			</tr>
<?	} 
if($count == 0) { ?>
			<tr>
				<td align="center" colspan='3' height="20">No records to display</td>
			</tr>
<?	} ?>
	</table>
	</div>
	
	
	</td></tr></table></form>
<?
	include($BF."includes/bottom.php");
?>