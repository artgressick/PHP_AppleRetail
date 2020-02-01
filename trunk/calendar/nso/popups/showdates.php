<?
	include('_controller.php');

	function sitm() { 
		global $BF,$results;
?>
				<table id='DateHistory' class='List sortable' style='width: 100%' cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th class='ListHeadSortOn sorttable_sorted sorttable_alpha'>Date/Time Of Change&nbsp;<img src='<?=$BF?>components/list/column_sorted_desc.gif' alt='sorted' style='vertical-align: bottom;' /><span id='sorttable_sortfwdind'></span></th>
							<th class='sorttable_alpha'>Date Changed&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
							<th class='sorttable_alpha'>Old Date&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
							<th class='sorttable_alpha'>New Date&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
							<th class='sorttable_alpha'>Changed By&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
						</tr>
					</thead>
					<tbody>
<?
			$count = 0;
			while($row = mysqli_fetch_assoc($results)) {
				if($row['chrColumnName'] == 'dBegin') {
					$row['chrColumnName'] = 'Project Start Date';
				} else if($row['chrColumnName'] == 'dEnd') {
					$row['chrColumnName'] = 'Store Opens/Reopens';
				} else if($row['chrColumnName'] == 'dDate2') {
					$row['chrColumnName'] = 'SWS Opens';
				} else if($row['chrColumnName'] == 'dDate3') {
					$row['chrColumnName'] = 'Last Day SWS Open';
				} else if($row['chrColumnName'] == 'dDate4') {
					$row['chrColumnName'] = 'Store Set Up';
				}
				
				if($row['txtOldValue'] != 'TBD' && $row['txtOldValue'] != 'Canceled') {
					if($row['txtOldValue'] != '' && $row['txtOldValue'] != '0000-00-00' && $row['txtOldValue'] != '1969-12-31') { $row['txtOldValue'] = date('n/j/Y',strtotime($row['txtOldValue'])); }
					else { $row['txtOldValue'] = 'N/A'; }
				}
				if($row['txtNewValue'] != 'TBD' && $row['txtNewValue'] != 'Canceled') {
					if($row['txtNewValue'] != '' && $row['txtNewValue'] != '0000-00-00' && $row['txtNewValue'] != '1969-12-31') { $row['txtNewValue'] = date('n/j/Y',strtotime($row['txtNewValue'])); }
					else { $row['txtNewValue'] = 'N/A'; }
				}
				
?>
						<tr id='DateHistorytr<?=$row['ID']?>' class='<?=(($count++ % 2) == 0 ? 'ListEven' : 'ListOdd')?>' 
							onmouseover='RowHighlight("DateHistorytr<?=$row['ID']?>");' onmouseout='UnRowHighlight("DateHistorytr<?=$row['ID']?>");'>
							<td style='cursor:auto;'><span style='display:none;'><?=$row['dtDateTime']?></span><?=date('n/j/Y g:i a',strtotime($row['dtDateTime']))?></td>
							<td style='cursor:auto;'><?=$row['chrColumnName']?></td>
							<td style='cursor:auto;'><span style='display:none;'><?=$row['txtOldValue']?></span><?=$row['txtOldValue']?></td>
							<td style='cursor:auto;'><span style='display:none;'><?=$row['txtNewValue']?></span><?=$row['txtNewValue']?></td>
							<td style='cursor:auto;'><span style='display:none;'><?=$row['chrFirst'].' '.$row['chrLast']?></span><a href='mailto:<?=$row['chrEmail']?>'><?=$row['chrFirst'].' '.$row['chrLast']?></a></td>
						</tr>
<?
			} 
?>
			</table>
			<div align="center" style='margin: 10px auto;'><input type='button' onclick='javascript:window.close();' value='Close Window' /></div>
<?
	}
?>