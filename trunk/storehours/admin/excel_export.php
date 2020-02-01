<?php
	include('_controller.php');
	
	$q = "SELECT RS.ID, RS.chrName, RS.chrStoreNum, 
			GROUP_CONCAT(CONCAT(SHS.dDate,'|', tOpening,'|', tClosing,'|', bClosed) ORDER BY dDate SEPARATOR ',') AS txtTimes
			FROM Holidays
			JOIN StoreHoursSpecial AS SHS ON SHS.idHoliday=Holidays.ID 
			JOIN RetailStores AS RS ON SHS.idStore=RS.ID
			WHERE Holidays.ID = ".$info['ID']."
			GROUP BY RS.ID
			ORDER BY chrName
	";
	
	$results = db_query($q,"Getting Results");
	
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".str_replace(' ','_',$info['chrHoliday'])."_Hours(". date('m-d-Y') .").xls");
	header("Pragma: no-cache");
	header("Expires: 0");

?>

<style>
.Heading { font-weight:bold; text-align:center; font-size:12px; border-bottom: 1px solid #000000; border-right: 1px solid #000000; margin:2px; vertical-align:middle; height:25px; }

.Row { font-size:12px; border-bottom: 1px solid #000000; border-right: 1px solid #000000; margin:2px; vertical-align:middle; background-color:#FFFFFF;height:20px; }
</style>


	<table border="0">
		<tr>
			<td class="Heading" style="" colspan='2'><?=decode($info['chrHoliday'])?> Hours of Operation</td>
<?
	$totalDays = (strtotime($info['dEnd']) - strtotime($info['dBegin']))/60/60/24;
	
	$i=0;
	$dCurrent = $info['dBegin'];
	while($i <= $totalDays) {
?>
			<td class="Heading" style="" colspan='2'><?=date('m/d/Y', strtotime($dCurrent))?></td>
<?
		$dCurrent = date('Y-m-d',strtotime($info['dBegin']." + ".($i++ + 1)." days"));
	}
?>
		</tr>
		<tr>
			<td class="Heading" style="">Store Number</td>
			<td class="Heading" style="">Store Name</td>
<?
	$i=0;
	while($i <= $totalDays) {
?>
			<td class="Heading" style="">Opening Time</td>
			<td class="Heading" style="">Closing Time</td>
<?
		$i++;
	}
?>
		</tr>
<?
		$count=0;

		while($row = mysqli_fetch_array($results)) {
?>
			<tr>
				<td class="Row"><?=decode($row['chrStoreNum'])?></td>
				<td class="Row"><?=decode($row['chrName'])?></td>

<?
			$allTimes = explode(',',$row['txtTimes']);
			foreach($allTimes as $k => $v) {
				$thisDay = explode('|',$v);
?>
				<td class="Row"><?=(!$thisDay[3]?date('H:i',strtotime($thisDay[1])):'Closed')?></td>
				<td class="Row"><?=(!$thisDay[3]?date('H:i',strtotime($thisDay[2])):'Closed')?></td>
<?
			}	
?>
			</tr>
<?
		$count++;
		}

		if ($count == 0) {
?>
			<tr>
				<td class="Row" colspan="<?=($totalDays *2)+2?>" height="20" align="center" style="border-left: 1px solid #000000;">No Records Found</td>
			</tr>
<?
		}

?>
		</table>
