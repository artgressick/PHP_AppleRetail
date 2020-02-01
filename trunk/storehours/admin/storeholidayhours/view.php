<?php
	include('_controller.php');
	
	function sitm() {
		global $BF,$info, $resultHours;
		
		$results = db_query("SELECT ID,idDayOfWeek,tOpening,tClosing,bClosed FROM StoreHours WHERE idStore=".$info['idStore'],"getting official hours");
		$originalDays = array();
		while($row = mysqli_fetch_assoc($results)) {
			$originalDays[$row['idDayOfWeek']]['tOpening'] = (!$row['bClosed'] ? date('g:i a', strtotime($row['tOpening'])) : 'Closed');
			$originalDays[$row['idDayOfWeek']]['tClosing'] = (!$row['bClosed'] ? date('g:i a', strtotime($row['tClosing'])) : 'Closed');
		}
		
		if(mysqli_num_rows($resultHours) > 0) {
			$holidayHours = array();
			while ($row = mysqli_fetch_assoc($resultHours)) {
				$holidayHours[$row['dDate']]['tOpening'] = date('g:i a', strtotime($row['tOpening']));
				$holidayHours[$row['dDate']]['tClosing'] = date('g:i a', strtotime($row['tClosing'])); 
				$holidayHours[$row['dDate']]['bClosed'] = $row['bClosed'];
			}
		} else { $holidayHours = 0; }
		
?>

										<table width="100%" cellpadding="0" cellspacing="0" style='border: 1px solid #ccc; border-bottom: none;'>
											<tr>
												<td style='border-bottom: 1px solid grey; padding: 2px 4px; font-weight:bold;'>Date</td>
												<td style='border-bottom: 1px solid grey; padding: 2px 4px; font-weight:bold;'>Original Begin</td>
												<td style='border-bottom: 1px solid grey; padding: 2px 4px; font-weight:bold;'>New Begin</td>
												<td style='border-bottom: 1px solid grey; padding: 2px 4px; font-weight:bold;'>Original End</td>
												<td style='border-bottom: 1px solid grey; padding: 2px 4px; font-weight:bold;'>New End</td>
											</tr>
<?	
	$totalDays = (strtotime($info['dEnd']) - strtotime($info['dBegin']))/60/60/24;
	
	$i=0;
	$dCurrent = $info['dBegin'];
	while($i <= $totalDays) {
		$dow = date('w',strtotime($dCurrent));
?>
											<tr>
												<td id='chrDateText<?=$i?>' style='border-bottom: 1px solid #ccc; padding: 2px 4px;'><?=date('F jS, Y - l',strtotime($dCurrent))?></td>
												<td style='border-bottom: 1px solid #ccc; padding: 2px 4px; color: #888;'><?=$originalDays[$dow]['tOpening']?></td>
												<td style='border-bottom: 1px solid #ccc; padding: 2px 4px;'><?=(!$holidayHours[$dCurrent]['bClosed']?$holidayHours[$dCurrent]['tOpening']:'Closed')?></td>
												<td style='border-bottom: 1px solid #ccc; padding: 2px 4px; color: #888;'><?=$originalDays[$dow]['tClosing']?></td>
												<td style='border-bottom: 1px solid #ccc; padding: 2px 4px;'><?=(!$holidayHours[$dCurrent]['bClosed']?$holidayHours[$dCurrent]['tClosing']:'Closed')?></td>
											</tr>
<?		
		$dCurrent = date('Y-m-d',strtotime($info['dBegin']." + ".($i++ + 1)." days"));
	}
?>

										</table>

<?	} ?>