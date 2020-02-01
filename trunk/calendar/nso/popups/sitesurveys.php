<?
	include('_controller.php');

	function sitm() { 
		global $BF,$results;

		if(mysqli_num_rows($results) > 0) { 
?>
			<table id='Notification' class='List sortable' style='width: 100%' cellpadding="0" cellspacing="0">
				<thead><tr>
					<th class='ListHeadSortOn sorttable_sorted' >Last Name&nbsp;<img src='<?=$BF?>components/list/column_sorted_asc.gif' alt='sorted' style='vertical-align: bottom;' /><span id='sorttable_sortfwdind'></span></th>
					<th>First Name&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='unsorted' style='vertical-align: bottom;' /></th>
					<th>Email Address&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='unsorted' style='vertical-align: bottom;' /></th>
				</tr></thead>
<?
			$count = 0;
			while($row = mysqli_fetch_assoc($results)) { 
?>
				<tr id='Notificationstr<?=$row['ID']?>' class='<?=(($count++ % 2) == 0 ? 'ListEven' : 'ListOdd')?>' 
					onmouseover='RowHighlight("Notificationstr<?=$row['ID']?>");' onmouseout='UnRowHighlight("Notificationstr<?=$row['ID']?>");'>
					<td onclick="associate(<?=$row['ID']?>,'<?=$row['chrKEY']?>','<?=$row['chrLast']?>','<?=$row['chrFirst']?>','<?=$row['chrEmail']?>')"><?=$row['chrLast']?></td>
					<td onclick="associate(<?=$row['ID']?>,'<?=$row['chrKEY']?>','<?=$row['chrLast']?>','<?=$row['chrFirst']?>','<?=$row['chrEmail']?>')"><?=$row['chrFirst']?></td>
					<td onclick="associate(<?=$row['ID']?>,'<?=$row['chrKEY']?>','<?=$row['chrLast']?>','<?=$row['chrFirst']?>','<?=$row['chrEmail']?>')"><?=$row['chrEmail']?></td>
				</tr>
<?
			} 
?>
			</table>
	
<?
		} else { 
?>
			No Records to add
<?
		} 
?>
			<div align="center" style='margin: 10px auto;'><input type='button' onclick='javascript:window.close();' value='Close Window' /></div>
<?
	}
?>