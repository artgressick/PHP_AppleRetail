<?php
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info,$results;
?>
	<div style='margin: 10px;'>
				<table id='SupplyAssoc' class='List sortable' style='width: 100%' cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th class="sorttable_nosort" style='width:20px;'>&nbsp;</th>
							<th class='ListHeadSortOn sorttable_sorted sorttable_alpha'>Supply Item&nbsp;<img src='<?=$BF?>components/list/column_sorted_asc.gif' alt='sorted' style='vertical-align: bottom;' /><span id='sorttable_sortfwdind'></span></th>
							<th class='sorttable_alpha'>Date Added&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
							<th class='sorttable_alpha'>Date Updated&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
							<th class='sorttable_alpha'>Quantity Sent&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
							<th class='sorttable_alpha'>Quantity Received&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
						</tr>
					</thead>
					<tbody>
<?
			$count = 0;
			while($row = mysqli_fetch_assoc($results)) {
?>
						<tr id='SupplyAssoctr<?=$row['ID']?>' class='<?=(($count++ % 2) == 0 ? 'ListEven' : 'ListOdd')?>' 
							onmouseover='RowHighlight("SupplyAssoctr<?=$row['ID']?>");' onmouseout='UnRowHighlight("SupplyAssoctr<?=$row['ID']?>");'>
							<td style='cursor:auto;'><?=($row['chrThumbnail']!='' ? miniPopup($BF.'calendar/nso/popupsupply.php?image='.$row['chrThumbnail'],'View') : '')?></td>
							<td style='cursor:auto;'><?=$row['chrItem']?></td>
							<td style='cursor:auto;'><span style='display:none;'><?=$row['dtCreated']?></span><?=date('n/d/Y g:i a',strtotime($row['dtCreated']))?></td>
							<td style='cursor:auto;'><span style='display:none;'><?=$row['dtUpdated']?></span><?=date('n/d/Y g:i a',strtotime($row['dtUpdated']))?></td>
							<td style='cursor:auto;'><?=$row['intQSent']?></td>
							<td style='cursor:auto;'><? if(access_check(40,3)) { ?><input type='text' size='4' maxlength='5' name='intSupply<?=$row['ID']?>' id='intSupply<?=$row['ID']?>' value='<?=$row['intQReceived']?>' onkeyup='updatesupply("<?=$row['ID']?>","<?=$info['ID']?>")' /><? } else { ?><?=$row['intQReceived']?><? } ?></td>
						</tr>
<?
			}
?>
					</tbody>
			 	</table>
	</div>		
	<iframe id='miniPopupWindow' style='position: absolute; display: none; background: #ccc; border: 1px solid gray;'></iframe>
<?
	}
?>