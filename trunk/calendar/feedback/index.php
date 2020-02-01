<?php
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
	<form method='post' action=''>
	<div style='margin: 10px;'>
		<table class='OneColumn' cellspacing="0" cellpadding="0">
			<tr>
				<td style='padding: 5px;'>
				
					<?=form_textarea(array('caption'=>'Feedback','name'=>'txtFeedback','rows'=>'15','cols'=>'100'))?>

				</td>
			</tr>
		</table>

		<?=form_button(array('type'=>'submit','value'=>'Submit Information'))?>

	</div>
	</form>
<?
	}
?>