<?php
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>

	<div style='margin: 10px;'>
		<form action="" method="post" id="idForm" onsubmit="return error_check()">
			<div style='border: 1px solid gray; padding: 10px; text-align: center;'>
				<div align="center" style='margin: 0 auto; width: 320px; border: 1px solid red; text-align: center; padding: 10px;'>	
					<div style='font-size: 20px; background: red; color: white; margin: -10px -10px 10px; padding: 5px;'>!! WARNING !!</div>
						<div>You are about to permanently erase the event:</div>
						<br />
						<?=form_text(array('caption'=>'Name','display'=>'true','value'=>$info['chrCalendarEvent']))?>
						<?=form_text(array('caption'=>'Date','display'=>'true','value'=>date('m/d/Y',strtotime($info['dBegin']))))?>
					</div>
		<?	if($info['chrSeries'] != "") { ?>
					<div style='margin-top: 10px;'><span style="color: blue;">This is a Multi-Day Event.</span></div>
					<div><?=form_checkbox(array('title'=>'Delete all Events in Series','name'=>'bDeleteAll'))?></div>
		<?	} ?>
					<div class='FormButtons'>
						<?=form_button(array('type'=>'submit','name'=>'SubmitAddSection','value'=>'Delete Event'.($info['chrSeries'] != "" ? '(s)' : '')))?>
						<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'key','value'=>$_REQUEST['key']))?>
						<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'from','value'=>$_REQUEST['from']))?>
					</div>				
				</div>
			</div>
		</form>
	</div>		
<?
	}
?>