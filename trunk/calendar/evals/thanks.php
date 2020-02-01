<?php
	include('_controller.php');
	
	function sitm() { 
		global $BF;
?>
		
		<div class='header3'>Thank you for participating!</div>
		<div>Your evaluation will greatly help the administration team to continue to do work!  Your help is much appreciated.</div>
		
		<div style='margin-top: 10px;'><?=form_button(array('type'=>'button','value'=>'Return To NSO/Remodel List','extra'=>'onclick="window.location.href=\''.$BF.'calendar/nso/\'"'))?></div>
	

<?	} ?>