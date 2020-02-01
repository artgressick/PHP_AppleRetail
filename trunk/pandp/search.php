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

//jms: This is where Javascript goes
?>
<script language="JavaScript" src="<?=$BF?>includes/forms.js"></script>
<script language="javascript">
	function error_check(addy) {
		if(total != 0) { reset_errors(); }  

		var total=0;

		total += ErrorCheck('chrSearch', "You must enter something to Search for.");

		if(total == 0) { document.getElementById('idForm').submit(); }
	}
</script>
<?	
		
	include($BF. "includes/top.php");
	include("includes/nav.php"); 

if (!isset($_REQUEST['chrSearch'])) {
?>

	<div class='listHeader'>Search</div>
	<div class='greenFilter'>Enter in text into the field provided to search P and P for that word.</div>
	<div class='emailbody'>
		<form enctype="multipart/form-data" action="" method="post" id="idForm">
		<!-- This is the main page form -->
			<div id="errors"></div>
			<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
				<tr>
					<td class="left">
						<div class='FormName'>Search For <span class='FormRequired'>(Required)</span></div>
						<div class='FormField'><input class="FormTextBox" type="text" name="chrSearch" id="chrSearch" maxlength="255" style="width:50%;" /></div>
					</td>
				</tr>
			</table>
			<div class='FormButtons'>
				<input class='adminformbutton' type='button' value='Search' onclick="error_check();" />
			</div>
		</form>
	</div>
<?
} else {

	//dtn: This is for the sorting of the rows and columns.  We must set the default order and name
	include($BF. 'components/list/sortList.php'); 
	if(!isset($_REQUEST['sortCol'])) { $_REQUEST['sortCol'] = "chrTitle"; } //dtn: This sets the default column order.  Asc by default.

	$q = "SELECT DISTINCT ID, chrTitle FROM PNPPages WHERE !bDeleted AND bVisable AND bPVisable AND idGeo=".$_SESSION['idGeo']." AND (txtContent LIKE '%".encode($_POST['chrSearch'])."%' OR chrTitle LIKE '%".encode($_POST['chrSearch'])."%') ORDER BY ". $_REQUEST['sortCol'] ." ". $_REQUEST['ordCol'];
	
	$results = database_query($q, "Getting any results");
?>
	<div class='listHeader'>Search Results For: <?=$_POST['chrSearch']?></div>

	<table class='List' id='List' style='width: 100%;'  cellpadding="0" cellspacing="0">
		<tr>
			<? sortList('Title', 'chrTitle','','chrSearch='.$_REQUEST['chrSearch']); ?>
		</tr>
<? $count=0;	
	while ($row = mysqli_fetch_assoc($results)) { 
		$link = "index.php?page=".$row['ID']; 
?>
			<tr id='tr<?=$row['ID']?>' class='<?=($count++%2?'ListLineOdd':'ListLineEven')?>' 
			onmouseover='RowHighlight("tr<?=$row['ID']?>");' onmouseout='UnRowHighlight("tr<?=$row['ID']?>");'>
				<td style='cursor: pointer;' onclick='location.href="<?=$link?>";'><?=$row['chrTitle']?></td>
			</tr>
<?	} 
if($count == 0) { ?>
			<tr>
				<td align="center" colspan='1' height="20">No Results Found to display <a href="search.php">Click here for New Search</a></td>
			</tr>
<?	} ?>
	</table>
<?
}
?>
	
</tr></td></table>
	
<?
include ($BF. "includes/bottom.php");
?>