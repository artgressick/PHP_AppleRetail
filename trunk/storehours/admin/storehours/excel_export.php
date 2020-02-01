<?php
	include('_controller.php');
	
	global $BF,$results;
		
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=Store_Hours(". date('m-d-Y') .").xls");
	header("Pragma: no-cache");
	header("Expires: 0");

?>

<style>
.Heading { font-weight:bold; text-align:center; font-size:12px; border-bottom: 1px solid #000000; border-right: 1px solid #000000; margin:2px; vertical-align:middle; height:25px; }

.Row { font-size:12px; border-bottom: 1px solid #000000; border-right: 1px solid #000000; margin:2px; vertical-align:middle; background-color:#FFFFFF;height:20px; }
</style>


	<table border="0">
		<tr>
			<td class="Heading" style="" colspan='2'>Standard Hours of Operation</td>
			<td class="Heading" style="" colspan='2'>Sunday</td>
			<td class="Heading" style="" colspan='2'>Monday</td>
			<td class="Heading" style="" colspan='2'>Tuesday</td>
			<td class="Heading" style="" colspan='2'>Wednesday</td>	
			<td class="Heading" style="" colspan='2'>Thursday</td>
			<td class="Heading" style="" colspan='2'>Friday</td>
			<td class="Heading" style="" colspan='2'>Saturday</td>
		</tr>
		<tr>
			<td class="Heading" style="">Store Number</td>
			<td class="Heading" style="">Store Name</td>
			<td class="Heading" style="">Opening Time</td>
			<td class="Heading" style="">Closing Time</td>
			<td class="Heading" style="">Opening Time</td>
			<td class="Heading" style="">Closing Time</td>
			<td class="Heading" style="">Opening Time</td>
			<td class="Heading" style="">Closing Time</td>
			<td class="Heading" style="">Opening Time</td>
			<td class="Heading" style="">Closing Time</td>
			<td class="Heading" style="">Opening Time</td>
			<td class="Heading" style="">Closing Time</td>
			<td class="Heading" style="">Opening Time</td>
			<td class="Heading" style="">Closing Time</td>
			<td class="Heading" style="">Opening Time</td>
			<td class="Heading" style="">Closing Time</td>
		</tr>
<?
		$count=0;

		while($row = mysqli_fetch_array($results)) {
?>
			<tr>
				<td class="Row"><?=decode($row['chrStoreNum'])?></td>
				<td class="Row"><?=decode($row['chrName'])?></td>

<?
			$i=0;
			$allTimes = explode(',',$row['txtTimes']);
			while ($i < 7) {
				$thisDay = explode('|',$allTimes[$i]);
?>
				<td class="Row"><?=(!$thisDay[3]?date('H:i',strtotime($thisDay[1])):'Closed')?></td>
				<td class="Row"><?=(!$thisDay[3]?date('H:i',strtotime($thisDay[2])):'Closed')?></td>
<?
				$i++;
			}	
?>
			</tr>
<?
		$count++;
		}

		if ($count == 0) {
?>
			<tr>
				<td class="Row" colspan="16" height="20" align="center" style="border-left: 1px solid #000000;">No Records Found</td>
			</tr>
<?
		}
?>
		</table>
