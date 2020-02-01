<?
	include('_controller.php');
	
	function sitm() { 
		global $BF;
?>
									<form action="" method="post" id="idForm" onsubmit="return error_check()">
										<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
											<tr>
												<td class="tcleft">

													<?=form_text(array('caption'=>'First Name','required'=>'true','name'=>'chrFirst','maxlength'=>'100'))?>

													<?=form_text(array('caption'=>'Last Name','required'=>'true','name'=>'chrLast','maxlength'=>'100'))?>

													<?=form_text(array('caption'=>'Email Address','required'=>'true','name'=>'chrEmail','maxlength'=>'150'))?>


												</td>
												<td class="tcgutter"></td>
												<td class="tcright">
													&nbsp;
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