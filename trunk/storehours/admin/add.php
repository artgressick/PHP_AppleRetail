<?
	include('_controller.php');
	
	function sitm() { 
		global $BF;
?>
									<form action="" method="post" id="idForm" onsubmit="return error_check()">
										<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
											<tr>
												<td class="tcleft">

													<?=form_text(array('caption'=>'Holiday Name','required'=>'true','name'=>'chrHoliday'))?>

													<?=form_checkbox(array('nocaption'=>'true','name'=>'bVisible','label'=>'Show this Holiday'))?>

												</td>
												<td class="tcgutter"></td>
												<td class="tcright">

													<?=form_text(array('caption'=>'Begin Date','required'=>'true','name'=>'dBegin'))?>

													<?=form_text(array('caption'=>'End Date','required'=>'true','name'=>'dEnd'))?>

												</td>
											</tr>
										</table>
										<div class='FormButtons'>
											<?=form_button(array('type'=>'submit','value'=>'Add Another','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'add.php\';"'))?> &nbsp;&nbsp; <?=form_button(array('type'=>'submit','value'=>'Add and Continue','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'index.php\';"'))?>
											<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'moveTo'))?>
										</div>
									</form>
<?
	}
?>