<?	
	function sortList($table, $hash, $query, $linkto, $style='') { 
		global $BF;
		
		
?>
	<table id='<?=$table?>' class='List sortable' style='<?=(isset($style) ? $style : 'width: 100%')?>' cellpadding="0" cellspacing="0">
		<thead>
		<tr>
<?	foreach($hash as $k => $v) { 
		if(is_array($v) && !preg_match('/^opt_/',$k)) { 
			if(isset($hash[$k]['default'])) { 
				$sortDir = strtolower($hash[$k]['default']);
?>			<th class='ListHeadSortOn <?=($sortDir != 'desc' ? 'sorttable_sorted' : 'sorttable_sorted_reverse')?>' <?=(isset($hash[$k]['style']) ? ' style="'. $hash[$k]['style'] .'"' : '')?>><?=$hash[$k]['displayName']?>&nbsp;<img src='<?=$BF?>components/list/column_sorted_<?=($sortDir != 'desc' ? 'asc' : 'desc')?>.gif' alt='sorted' style='vertical-align: bottom;' /><span id='<?=($sortDir != 'desc' ? 'sorttable_sortfwdind' : 'sorttable_sortrevind')?>'></span></th>
<?			} else {
				if(!isset($hash[$k]['nosort'])) { 
?>			<th<?=(isset($hash[$k]['style']) ? ' style="'. $hash[$k]['style'] .'"' : '')?><?=(isset($hash[$k]['class']) ? ' class="'. $hash[$k]['class'] .'"' : '')?>><?=$hash[$k]['displayName']?>&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>

<?				} else {
?>			<th<?=(isset($hash[$k]['style']) ? ' style="'. $hash[$k]['style'] .'"' : '')?> class="sorttable_nosort<?=(isset($hash[$k]['class']) ? ' '. $hash[$k]['class'] .'"' : '')?>"><?=$hash[$k]['displayName']?></th>
<?				}
			}
		} else { 
?>			<th<?=(preg_match('/^opt_/',$k) ? ' class="options sorttable_nosort"' : '')?>><img src='<?=$BF?>images/options.gif' alt='options' /></th>
<?		}
	} ?>
		</tr>
		</thead>
<?		$count = 0;
		if(mysqli_num_rows($query)) { 
			
			$linktype = (preg_match('/(\?|\&)key\=/',$linkto) ? 'chrKEY' : 'ID');
			
			while($row = mysqli_fetch_assoc($query)) { 
?>		<tr id='<?=$table?>tr<?=$row['ID']?>' class='<?=($count++%2 ? 'ListOdd' : 'ListEven')?>' 
			onmouseover='RowHighlight("<?=$table?>tr<?=$row['ID']?>");' onmouseout='UnRowHighlight("<?=$table?>tr<?=$row['ID']?>");'>
<?	foreach($hash as $k => $v) { 
		if(is_array($v) && !preg_match('/^opt_/',$k)) { 
?>			<td onclick='window.location.href="<?=$linkto?><?=$row[$linktype]?>"'><?=$row[$k]?></td>
<?		} else { 
			if(preg_match('/^opt_del$/',$k)) { 
				if(preg_match('/,/',$v)) { 
					$tmpVal = explode(',',$v);
					$displayVal = "";
					foreach($tmpVal as $val) { $displayVal .= $row[$val]." "; }
					$displayVal = substr($displayVal,0,-1);
				} else {
					$displayVal = $row[$v];
				}
			?>			<td><? deleteButton($row['ID'],$displayVal,$row['chrKEY'],$table); ?></td> 		<?
			} else if (preg_match('/^opt_other$/',$k)) { 
				if ($v == 'order') {
?>					<td><?=orderBoxes($row['ID'],$row['dOrder'])?></td>
<?
				}
			} else if (preg_match('/^opt_link$/',$k)) { 
				$v['address'].=(preg_match('/(\?|\&)key\=/',$linkto) ? $row['chrKEY'] : $row['ID']);
?>				<td style='white-space:nowrap;'><?=linkto($v)?></td>
<?	
			} else if (preg_match('/^opt_popup$/',$k)) {
?>				<td style='white-space:nowrap;'><?=miniPopup($v['path'].$row[$v['column']],$v['display'])?></td>
<?	
			} else { 
?>				<td><?=$v?></td>
<?			}
		}
	} 
?>		
		</tr>
<?			}
		} else {
?>			<tr><td colspan='<?=count($hash)?>' style='text-align: center; height: 20px;'>No records found in the database.</td></tr> 	
<?		} ?>
	</table>

<?	} 


function deleteButton($id,$message,$chrKEY,$table) {
	global $BF;
	?><span class='deleteImage'><a href="javascript:warning(<?=$id?>, '<?=str_replace("&","&amp;",$message)?>','<?=$chrKEY?>','<?=$table?>');" title="Delete: <?=$message?>"><img id='deleteButton<?=$id?>' src='<?=$BF?>images/button_delete.png' alt='delete button' onmouseover='this.src="<?=$BF?>images/button_delete_on.png"' onmouseout='this.src="<?=$BF?>images/button_delete.png"' /></a></span><?
}

function orderBoxes($id,$value) {
	?><input type="text" size="3" name="dOrder<?=$id?>" id="dOrder<?=$id?>" value="<?=$value?>" /><?
}
global $BF;
?>


	<style type='text/css'>
	
		.List { border: 1px solid #999; padding: 0; margin: 0; }
		.List th { font-size: 10px; background: url(<?=$BF?>components/list/list_head.gif) repeat-x; height: 13px; border-bottom: 1px solid #999; padding: 3px 5px; font-weight: bold; text-align: left; white-space: nowrap; }
		.List td { padding: 0 5px; font-size: 11px; cursor: pointer; }
		.List th a { color: #333; text-decoration: none; }
		.List td a { color: black; text-decoration: none; }
		.List th.ListHeadSortOn { font-size: 10px; background: url(<?=$BF?>components/list/list_head_sortedby.gif); height: 13px; border-bottom: 1px solid #999; padding: 3px 5px; font-weight: bold; }
		.List .ListOdd { font-size: 10px; background-color: #FFF; line-height: 20px; height: 20px; padding-left: 5px; }
		.List .ListEven { font-size: 10px; background-color: #EEE; line-height: 20px; height: 20px; padding-left: 5px; }
		.List .options { width: 0.1in; white-space: nowrap; text-align: center; } 
		.List .options a { text-decoration: underline; color: green; } 
		
		.List .options a { text-decoration: underline; color: green; } 
	</style>
<?

# This is the Listing section, all Javascript that affect Listing pages go in the area.

?>
	<script type='text/javascript' src='<?=$BF?>calendar/components/list/_sorttable.js'></script>
	<script type='text/javascript'>
	
	var highlightTmp = "";
		function RowHighlight(row) {
			highlightTmp = (document.getElementById(row).style.backgroundColor != "" ? document.getElementById(row).style.backgroundColor : '');
			document.getElementById(row).style.backgroundColor = '#AFCCFF';
		}
		function UnRowHighlight(row) {
			document.getElementById(row).style.backgroundColor = (highlightTmp == '' ? '' : highlightTmp);
		}
		// This function re-paints the list tables
		function repaint(tblName) {
			var menuitems = document.getElementById(tblName).getElementsByTagName("tr");
			var j = 0;
			for (var i=1; i<menuitems.length; i++) {
				if(menuitems[i].style.display != "none") {
					((j%2) == 0 ? menuitems[i].className = "ListEven" : menuitems[i].className = "ListOdd");
					j += 1;
				}		
			}
		}
	</script>
