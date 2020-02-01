<?php
	include('_controller.php');
	
	function sitm() {
		global $BF,$results,$info;
?>
		<form id='idForm' method='post' action='' onsubmit='return error_check();'>
		<table cellspacing="0" cellpadding="0" style='width: 700px;'>
			<tr>
				<th>Day Of The Week</th>
				<th>Opening Time</th>
				<th>Closing Time</th>
				<th>Closed</th>
			</tr>
<?
		if(mysqli_num_rows($info) > 0) {
			while($row = mysqli_fetch_assoc($info)) {
?>
			<tr>
				<td><?=day_of_week($row['idDayOfWeek'])?></td>
				<td><?=form_text(array('nocaption'=>'true','name'=>'tOpening'.$row['idDayOfWeek'],'value'=>(!$row['bClosed'] ? date('G:i',strtotime($row['tOpening'])) : ''),'extra'=>($row['bClosed']?'disabled="disabled"':'')))?> <span class='FormRequired'>(Example: 9:00am)</span></td>
				<td><?=form_text(array('nocaption'=>'true','name'=>'tClosing'.$row['idDayOfWeek'],'value'=>(!$row['bClosed'] ? date('G:i',strtotime($row['tClosing'])) : ''),'extra'=>($row['bClosed']?'disabled="disabled"':'')))?> <span class='FormRequired'>(Example: 4:30pm)</span></td>
				<td>&nbsp; &nbsp;<?=form_checkbox(array('nocaption'=>'true','name'=>'bClosed'.$row['idDayOfWeek'],'title'=>'','checked'=>($row['bClosed']?'true':'false'),'extra'=>'onclick="checkday('.$row['idDayOfWeek'].')"'))?></td>
			</tr>
<?			
			}
		} else {

			$dow = 0;
			while($dow < 7) {
?>
			<tr>
				<td><?=day_of_week($dow)?></td>
				<td><?=form_text(array('nocaption'=>'true','name'=>'tOpening'.$dow,'value'=>' am'))?> <span class='FormRequired'>(Example: 9:00am)</span></td>
				<td><?=form_text(array('nocaption'=>'true','name'=>'tClosing'.$dow,'value'=>' pm'))?> <span class='FormRequired'>(Example: 4:30pm)</span></td>
				<td>&nbsp; &nbsp;<?=form_checkbox(array('nocaption'=>'true','name'=>'bClosed'.$dow,'title'=>'','extra'=>'onclick="closed('.$dow.')"'))?></td>
			</tr>
<?			
				$dow++;
			}
		}
?>
		</table>

		<?=form_button(array('type'=>'submit','value'=>'Update Information','style'=>'margin-top: 10px;'))?>
		</form>

<?	} ?>