<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;
?>
	<form action="" method="post" id="idForm">
		<table id='NSOLearn' class='List sortable' style='width: 100%' cellpadding="0" cellspacing="0">
			<thead>
				<tr>
<?
				if(access_check(17,3)) {
?>
					<th class="options sorttable_nosort">&nbsp;</th>
<?
				}
?>
					<th class='ListHeadSortOn sorttable_sorted sorttable_alpha' >Article Name&nbsp;<img src='<?=$BF?>components/list/column_sorted_asc.gif' alt='sorted' style='vertical-align: bottom;' /><span id='sorttable_sortfwdind'></span></th>
					<th class='sorttable_alpha'>Last Updated&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
<?
				if(access_check(17,3)) {
?>
					<th class="options sorttable_nosort">Parent Order</th>
					<th class="options sorttable_nosort">Child Order</th>
<?
				}
				if(access_check(17,4)) {
?>
					<th class="options sorttable_nosort"><img src='<?=$BF?>images/options.gif' alt='options' /></th>
<?
				}
?>
				</tr>
			</thead>
			<tbody>
<?
		$count = 0;
		while($row = mysqli_fetch_assoc($results)) { 
			if(!isset($parentschildren[$row['idParent']])) { $parentschildren[$row['idParent']] = array(); }
			if(!$row['bParent']) { $parentschildren[$row['idParent']][] = $row['ID']; }
			$link = (access_check(17,3) ? 'window.location.href="edit.php?key='.$row['chrKEY'].'"' : '');
			if($row['bPShow'] == 0 && !$row['bParent']) {
				$showimage = $BF.'images/parentoff.png';
				$showtitle = 'Parent is not visible.';
				$showstyle = 'cursor:auto;';
				$showlink = '';
			} else if ($row['bShow'] == 1) {
				$showimage = $BF.'images/on.png';
				$showtitle = 'This article is visible.';
				$showstyle = 'cursor:pointer;';
				$showlink = 'javascript:sh_article('.$row['ID'].');';
			} else {
				$showimage = $BF.'images/off.png';
				$showtitle = 'This article is not visible.';
				$showstyle = 'cursor:pointer;';
				$showlink = 'javascript:sh_article('.$row['ID'].');';
			}
?>
				<tr id='NSOLearntr<?=$row['ID']?>' class='<?=(($count++ % 2) == 0 ? 'ListEven' : 'ListOdd')?>' 
					onmouseover='RowHighlight("NSOLearntr<?=$row['ID']?>");' onmouseout='UnRowHighlight("NSOLearntr<?=$row['ID']?>");'>
<?
				if(access_check(17,3)) {
?>
					<td id='showtd<?=$row['ID']?>' onclick='<?=$showlink?>' style='<?=$showstyle?>' title='<?=$showtitle?>'>
						<img id='showimage<?=$row['ID']?>' src='<?=$showimage?>' alt='<?=$showtitle?>' />
						<input type='hidden' id='parent<?=$row['ID']?>' value='<?=$row['bParent']?>' />
						<input type='hidden' id='show<?=$row['ID']?>' value='<?=($row['bParent'] ? $row['bPShow'] : $row['bShow'])?>' />
					</td>
<?
				}
?>
					<td onclick='<?=$link?>' style='<?=($row['bParent']?'font-weight:bold;':'padding-left:20px;')?>'><?=$row['chrTitle']?></td>
					<td onclick='<?=$link?>'><?=date('n/j/Y - g:i a',strtotime($row['dtUpdated'])).' by '.$row['chrUpdater']?></td>
<?
				if(access_check(17,3)) {
?>
					<td style='text-align:center;cursor:default;'><?=($row['bParent'] ? "<input type='text' name='dOrder".$row['ID']."' value='".$row['dOrder']."' size='3' maxlength='6' />" : '&nbsp;')?></td>
					<td style='text-align:center;cursor:default;'><?=(!$row['bParent'] ? "<input type='text' name='dOrderChild".$row['ID']."' value='".$row['dOrderChild']."' size='3' maxlength='6' />" : '&nbsp;')?></td>
<?
				}
				if(access_check(17,4)) {
?>
					<td><span class='deleteImage'><a href="javascript:warning(<?=$row['ID']?>, '<?=$row['chrTitle']?>','<?=$row['chrKEY']?>','NSOLearn');" title="Delete: <?=$row['chrTitle']?> "><img id='deleteButton<?=$row['ID']?>' src='<?=$BF?>images/button_delete.png' alt='delete button' onmouseover='this.src="<?=$BF?>images/button_delete_on.png"' onmouseout='this.src="<?=$BF?>images/button_delete.png"' /></a></span></td> 				
<?
				}
?>
				</tr>
<?
		}
		if($count==0) { 
?>
				<tr>
					<td colspan='5' style='padding:5px;text-align:center;'>No Articles found in the database.</td>
				</tr>
<?
		}
?>
			</tbody>
		</table>
<?
		if(access_check(17,3)) {
?>
		<div class='FormButtons'>
			<?=form_button(array('type'=>'submit','name'=>'saveorder','value'=>'Save Order'))?>
		</div>
<?
		}
?>

	</form>

<?
		foreach ($parentschildren AS $id => $children) {
?>
		<input type='hidden' id='<?=$id?>children' value='<?=implode(',',$parentschildren[$id])?>' />
<?
		}
	}
?>