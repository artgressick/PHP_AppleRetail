<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;

		$tableName = 'NSOTravelPlans';
?>
	<table id='<?=$tableName?>' class='List sortable' style='width: 100%; border-top: 0;' cellpadding="0" cellspacing="0">
		<thead>
		<tr>
			<th class='ListHeadSortOn sorttable_sorted'>Arrival Date&nbsp;<img src='<?=$BF?>components/list/column_sorted_asc.gif' alt='sorted' style='vertical-align: bottom;' /><span id='sorttable_sortfwdind'></span></th>
			<th>Departure Date&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='unsorted' style='vertical-align: bottom;' /></th>
			<th>User&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='unsorted' style='vertical-align: bottom;' /></th>
			<?=(access_check(24,4) ? '<th class="options sorttable_nosort"><img src="'.$BF.'images/options.gif" alt="options" /></th>' : '' )?>
		</tr>
		</thead>

<?		$count = 0;
		while($row = mysqli_fetch_assoc($results)) {
			$link = (access_check(24,3) ? 'window.location.href="edit.php?key='.$row['chrKEY'].'"' : ''); 
?>
		<tr id='<?=$tableName?>tr<?=$row['ID']?>' class='<?=($count++%2 ? 'ListEven' : 'ListOdd')?>' 
			onmouseover='RowHighlight("<?=$tableName?>tr<?=$row['ID']?>");' onmouseout='UnRowHighlight("<?=$tableName?>tr<?=$row['ID']?>");'>
			
			<td onclick='<?=$link?>'><span style='display: none;'><?=$row['dBegin']?></span><?=$row['dBeginFormated']?></td>
			<td onclick='<?=$link?>'><span style='display: none;'><?=$row['dEnd']?></span><?=$row['dEndFormated']?></td>
			<td onclick='<?=$link?>'><?=$row['chrFirst']." ".$row['chrLast']?></td>
<?
		if(access_check(24,4)) {
?>
			<td><span class='deleteImage'><a href="javascript:warning(<?=$row['ID']?>, '<?=$row['chrFirst']." ".$row['chrLast']?>','<?=$row['chrKEY']?>','<?=$tableName?>');" title="Delete: <?=$row['dBeginFormated']?>"><img id='deleteButton<?=$row['ID']?>' src='<?=$BF?>images/button_delete.png' alt='delete button' onmouseover='this.src="<?=$BF?>images/button_delete_on.png"' onmouseout='this.src="<?=$BF?>images/button_delete.png"' /></a></span></td>
<?
		}
?>
		</tr>
<?		}
		if($count==0) {
?>
		<tr>
			<td colspan='4' style='padding:5px; text-align:center;'>No Records found in database</td>
		</tr>
<?
		}
?>

	</table>

<?
	}