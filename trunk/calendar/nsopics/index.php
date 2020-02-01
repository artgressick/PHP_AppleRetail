<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results,$directions;
	
	
		$_REQUEST['idStore'] = (!isset($_REQUEST['idStore']) ? '' : $_REQUEST['idStore']); 
		$filter_results = db_query("SELECT ID,chrName as chrRecord FROM RetailStores WHERE !bDeleted ORDER BY chrName","Getting Stores");
?>
			<table cellspacing="0" cellpadding="0" class='instructions' style='width: 100%;'>
				<tr>
					<td><?=$directions?></td>
					<td style='text-align: right;'>
						<?=form_select($filter_results,array('nocaption'=>'true',
									'name'=>'idStore',
									'caption'=>'- All Stores -',
									'value'=>$_REQUEST['idStore'],
									'extra'=>' onchange="window.location.href=\'?idStore=\'+this.value"'))?>
					</td>
				</tr>
			</table>

<?
		messages();
?>
		<table id='CalendarFiles' class='List sortable' style='width: 100%;border-top: 0;' cellpadding="0" cellspacing="0">
			<thead>
			<tr>
				<th class="sorttable_nosort" style='width:20px;'>&nbsp;</th>
				<th class='ListHeadSortOn sorttable_sorted'>Picture Name&nbsp;<img src='<?=$BF?>components/list/column_sorted_asc.gif' alt='sorted' style='vertical-align: bottom;' /><span id='sorttable_sortfwdind'></span></th>
				<th>Store Name</th>
				<th>Date Added&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
			</tr>
		</thead>
<?	$count = 0;
	while($row = mysqli_fetch_assoc($results)) {
	$link = 'onclick=\'window.location.href="nsopictures/edit.php?key='.$row['chrKEY'].'";\''; 
	?>
		<tr id='CalendarFilestr<?=$row['ID']?>' class='<?=(($count++ % 2) == 0 ? 'ListEven' : 'ListOdd')?>' 
			onmouseover='RowHighlight("CalendarFilestr<?=$row['ID']?>");' onmouseout='UnRowHighlight("CalendarFilestr<?=$row['ID']?>");'>
			<td><?=miniPopup($BF.'calendar/nso/popup.php?image='.$row['chrThumbnail'],'View')?></td>
			<td <?=$link?>><?=($row['chrFileTitle'] != '' ? $row['chrFileTitle'] : $row['chrCalendarFile'])?></td>
			<td <?=$link?>><?=$row['chrStore']?></td>
			<td <?=$link?>><?=$row['dtCreated']?></td>
		</tr>
<?	} 
	if($count == 0) {  ?>
		<tr>
			<td colspan='2' style='padding: 4px;text-align: center;'>No Pictures found in the database.</td>
		</tr>
<?	} ?>
	</table>


	<iframe id='miniPopupWindow' style='position: absolute; display: none; background: #ccc; border: 1px solid gray;'></iframe>
<?
	}
?>