<?php
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
	<div style='margin: 10px;'>
		<table class='OneColumn' cellspacing="0" cellpadding="0">
			<tr>
				<td style='padding: 5px;'>
				
					<?=form_text(array('caption'=>'Event Name','display'=>'true','value'=>$info['chrCalendarEvent']))?>
					<?=form_text(array('caption'=>'Date','display'=>'true','value'=>date('m/d/Y',strtotime($info['dBegin']))))?>

<? if($info['bAllDay']) { ?>
					<div class='FormName'>This is an All Day Event</div>
					<br />
<? } else { ?>
					<?=form_text(array('caption'=>'Begin Time','display'=>'true','value'=>date('H:i',strtotime($info['tBegin']))))?>
					<?=form_text(array('caption'=>'End Time','display'=>'true','value'=>date('H:i',strtotime($info['tEnd']))))?>
<? } ?>

<? if($info['dBegin'] != $info['dEnd']) { ?>
					<div class='FormName'>This is an Multiple Day Event</div>
<? } ?>
					<?=form_select(db_query("SELECT ID,chrCalendarType as chrRecord FROM CalendarTypes WHERE !bDeleted ORDER BY chrCalendarType","getting calendar types"),array('caption'=>'Calendar Type','display'=>'true','value'=>$info['idCalendarType']))?>

					<div class='FormName'>Page Information</div>
					<div class='FormDisplay'><?=decode($info['txtContent'])?></div>
		
				</td>
			</tr>
		</table>
	</div>		
<?
	}
?>