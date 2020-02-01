<?php
	$BF='../';
	$auth_not_required = true;
	require($BF. '_lib.php');
	
	// Before we show the page do we have some Session Store Information?
if (!isset($_SESSION['idStore']) || $_SESSION['idStore'] == "" || $_SESSION['idStore'] == 0) {
	$_SESSION['refer'] = $_SERVER['REQUEST_URI'];
	header('Location: '.$BF.'stores.php');
	die();
}	
	
	include($BF. "includes/meta.php");
	
	$q = "SELECT txtContent FROM CMSPages WHERE ID=1";
	$info = mysqli_fetch_assoc(mysqli_query($mysqli_connection, $q));
	include($BF. 'components/list/sortList.php'); 
	
	$query = "SELECT ID 
	FROM PNPPages
	WHERE !bDeleted AND bVisable AND bPVisable AND idGeo=".$_SESSION['idGeo']." 
	ORDER BY dOrder,!bParent,dOrderChild,chrTitle";
	
	$defaultpage = database_query($query, 'Getting First Page',1);
	
	
	if ( !isset($_REQUEST['page']) || $_REQUEST['page'] == "" ) { $_REQUEST['page'] = $defaultpage['ID']; }
	
	
	$query = "SELECT chrTitle, txtContent
			FROM PNPPages
			WHERE ID = '".$_REQUEST['page']. "' AND !bDeleted AND bVisable AND bPVisable";
	$pageinfo = database_query($query, 'Retrieving the Page',1);


	include($BF. "includes/top.php");
	include("includes/nav.php"); 
?>


	<div class='listHeader'><?=$pageinfo['chrTitle']?></div>

	<table class='List' id='List' style="border:none;" style='width: 100%;'  cellpadding="0" cellspacing="0">
		<tr>
			<td><?=decode($pageinfo['txtContent'])?></td>
		</tr>
	</table>


</tr></td></table>

<?
include ($BF. "includes/bottom.php");
?>