<?php
	$BF = "../../";
	require_once($BF. '_lib.php');
	include($BF. "includes/meta.php");
		// bCommtool must be set to true, else show noaccess.php page
		if ($Security['bCommtool'] == false) {
				header("Location: " . $BF . "commtool/noaccess.php");
				die();
		}

	//dtn: This is for the sorting of the rows and columns.  We must set the default order and name
	include($BF. 'components/list/sortList.php'); 
	if(!isset($_REQUEST['sortCol'])) { $_REQUEST['sortCol'] = "chrLast,chrFirst"; } //dtn: This sets the default column order.  Asc by default.

	$q = "SELECT ID,chrFirst,chrLast,chrEmail 
		FROM RFLContacts 
		WHERE !bDeleted ";
		if(@$_REQUEST['chrSearch'] != '') { 
			$q .= " AND ((chrFirst LIKE '%" . $_REQUEST['chrSearch'] . "%') OR
			(chrLast LIKE '%" . $_REQUEST['chrSearch'] . "%') OR
			(chrEmail LIKE '%" . $_REQUEST['chrSearch'] . "%'))";
		}
		
	$q .= "ORDER BY " . $_REQUEST['sortCol'] . " " . $_REQUEST['ordCol'];
	$results = database_query($q, 'get contacts');

	// parse the popup data
	parse_str(base64_decode($_REQUEST['d']), $data);

function insert_body_params()
{
	if(isset($_REQUEST['idSelected'])) {
		?> onload="associate('<?=$_REQUEST['idSelected']?>', '<?=$_REQUEST['chrSelected']?>');" <?
	} else {
		?> onload="defaultOnLoad();" <?
	}
}

?>
<script type="text/javascript">
	function associate(id, entryname)
	{

		dad = window.opener.document;
<?		if(isset($data['functioncall'])) { ?>
			window.opener.<?=$data['functioncall']?>(id, entryname);
<?		} ?>
	}
</script>

<?

	$title = "Add Person Poppup";
	include($BF. "includes/top_popup.php");
?>

	<fieldset>
	<form action='' method='get'>
		Search: <input type='text' name='chrSearch' id='DocLoadFocus' value='<?=@$_REQUEST['chrSearch']?>' />
		<input type='hidden' name='d' value='<?=@$_REQUEST['d']?>' />
		<input type='submit' value='Go' />
		</form>
	</fieldset>


	<table class='List' id='List' style='width: 100%;'  cellpadding="0" cellspacing="0">
		<tr>
			<? sortList('Last Name', 'chrLast'); ?>
			<? sortList('First Name', 'chrFirst'); ?>
			<? sortList('Email', 'chrLast'); ?>
			<th><img src="<?=$BF?>images/options.gif"></th>
		</tr>
<? $count=0;	
	while ($row = mysqli_fetch_assoc($results)) { 
			$link = "associate('". $row['ID'] ."','". $row['chrFirst'] ." ". $row['chrLast'] ."')";
?>
		<tr id='tr<?=$row['ID']?>' class='<?=($count++%2?'ListLineOdd':'ListLineEven')?>' 
			onmouseover='RowHighlight("tr<?=$row['ID']?>");' onmouseout='UnRowHighlight("tr<?=$row['ID']?>");'>
			<td style='cursor: pointer;' onclick="<?=$link?>"><?=$row['chrLast']?></td>
			<td style='cursor: pointer;' onclick="<?=$link?>"><?=$row['chrFirst']?></td>
			<td style='cursor: pointer;' onclick="<?=$link?>"><?=$row['chrEmail']?></td>
		</tr>
<?	} 
if($count == 0) { ?>
			<tr>
				<td align="center" colspan='3' height="20">No categories to display</td>
			</tr>
<?	} ?>
	</table>

<?
	include($BF. "includes/bottom_popup.php");
?>
