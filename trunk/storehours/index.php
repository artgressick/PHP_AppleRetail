<?php
	include('_controller.php');
	
	function sitm() {
		global $BF,$results,$holiday_results;
?>

		<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0" style='margin-top: 10px;'>
			<tr>
				<td class="tcleft" style='width: 40%;'>
					<table width='350' cellpadding='0' cellspacing='0' border='0' style='padding-bottom:5px;'>
						<tr>
							<td class='header4'>Official Hours</td>
							<td style='text-align:right;'><?=form_button(array('name'=>'update','value'=>'Update','extra'=>'onclick="window.location=\''.$BF.'storehours/official/\'"'))?></td>
						</tr>
					</table>
					
					<table cellspacing="0" cellpadding="0" style='width: 350px; border: 1px solid #ccc; border-bottom: none;'>
						<tr>
							<td style='border-bottom: 1px solid grey; padding: 2px 4px;'><strong>Day Of The Week</strong></td>
							<td style='border-bottom: 1px solid grey; padding: 2px 4px;'><strong>Opening Time</strong></td>
							<td style='border-bottom: 1px solid grey; padding: 2px 4px;'><strong>Closing Time</strong></td>
						</tr>
<?		$dow = 0;
		while($row = mysqli_fetch_assoc($results)) {
?>
						<tr>
							<td style='border-bottom: 1px solid #ccc; padding: 2px 4px;'><?=day_of_week($row['idDayOfWeek'])?></td>
							<td style='border-bottom: 1px solid #ccc; padding: 2px 4px;'><?=(!$row['bClosed'] ? date('h:i a',strtotime($row['tOpening'])) : 'Closed')?></td>
							<td style='border-bottom: 1px solid #ccc; padding: 2px 4px;'><?=(!$row['bClosed'] ? date('h:i a',strtotime($row['tClosing'])) : 'Closed')?></td>
						</tr>
<?			
			$dow++;
		}
?>
					</table>

				</td>
				<td class="tcgutter"></td>
				<td class="tcright" style='width: 60%;'>

					<table width='350' cellpadding='0' cellspacing='0' border='0' style='padding-bottom:5px;'>
						<tr>
							<td class='header4'>Holiday Hours</td>
						</tr>
					</table>

<?
				$tableHeaders = array(
					'chrHoliday'	=> array('displayName' => 'Holiday Name','default' => 'asc'),
					'dBegin'		=> array('displayName' => 'Begin Date'),
					'dEnd'			=> array('displayName' => 'End Date')
				);
				
				sortList('Holidays',				# Table Name
					$tableHeaders,					# Table Name
					$holiday_results,						# Query results
					'holiday.php?key=',				# The linkto page when you click on the row
					'width: 100%;', 	# Additional header CSS here
					''
				);

?>

				</td>
			</tr>
		</table>

		

<?	} ?>