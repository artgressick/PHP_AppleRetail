<?
	include('_controller.php');
	
	function sitm() { 
		global $BF;
?>
									<form action="" method="post" id="idForm" onsubmit="return error_check()" enctype="multipart/form-data">
										<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
											<tr>
												<td class="tcleft">

													<?=form_text(array('caption'=>'Supply Item Name','required'=>'true','name'=>'chrItem','size'=>'35','maxlength'=>'200'))?>
													
													<?=form_textarea(array('caption'=>'Description','name'=>'txtDescription','rows'=>'10','cols'=>'32','style'=>'width:100%;'))?>

												</td>
												<td class="tcgutter" style='width:20px;'></td>
												<td class="tcright" style='width:50%;'>
													
													<?=form_text(array('caption'=>'Upload Picture','type'=>'file','name'=>'chrPicture'))?>
													
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