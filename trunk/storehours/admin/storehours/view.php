<?php
	include('_controller.php');
	
	function sitm() {
		global $BF,$info, $results;
		
		if(mysqli_num_rows($results) > 0) {
?>

										<table width="100%" cellpadding="0" cellspacing="0" style='border: 1px solid #ccc; border-bottom: none;'>
											<tr>
												<td style='border-bottom: 1px solid grey; padding: 2px 4px; font-weight:bold;'>Day of Week</td>
												<td style='border-bottom: 1px solid grey; padding: 2px 4px; font-weight:bold;'>Begin Time</td>
												<td style='border-bottom: 1px solid grey; padding: 2px 4px; font-weight:bold;'>End Time</td>
											</tr>
<?
			while($row = mysqli_fetch_assoc($results)) {
?>
											<tr>
												<td style='padding: 2px 4px; border-bottom: 1px solid #ccc;'><?=day_of_week($row['idDayOfWeek'])?></td>
												<td style='padding: 2px 4px; border-bottom: 1px solid #ccc;'><?=date('h:i a',strtotime($row['tOpening']))?></td>
												<td style='padding: 2px 4px; border-bottom: 1px solid #ccc;'><?=date('h:i a',strtotime($row['tClosing']))?></td>
											</tr>
<?
			}
?>
										</table>

<?		} else { ?>
										<div>No Hours have been set up by this store.</div>
<?		}
	} ?>