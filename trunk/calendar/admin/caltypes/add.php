<?
	include('_controller.php');
	
	function sitm() { 
		global $BF;
?>
									<form action="" method="post" id="idForm" onsubmit="return error_check()">
										<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
											<tr>
												<td class="tcleft">

													<?=form_text(array('caption'=>'Calendar Type Name','required'=>'true','name'=>'chrCalendarType','size'=>'35','maxlength'=>'120'))?>

												</td>
												<td class="tcgutter"></td>
												<td class="tcright">
													<div class='FormName'>Text Color <span class='FormRequired'>(Required)</span></div>
													<div><?=form_text(array('nocaption'=>'true','caption'=>'Text Color','required'=>'true','name'=>'chrColorText','size'=>'10','maxlength'=>'20','value'=>'#333333','extra'=>'onchange="document.getElementById(\'preview\').style.color=this.value;"'))?> <a href='#' onclick="show_colorfind(document.getElementById('chrColorText'));"><img src='<?=$BF?>calendar/images/colorpallet.gif' /></a></div>
													<div class='FormName'>Background Color <span class='FormRequired'>(Required)</span></div>
													<div><?=form_text(array('nocaption'=>'true','caption'=>'Background Color','required'=>'true','name'=>'chrColorBG','size'=>'10','maxlength'=>'20','value'=>'#CCCCCC','extra'=>'onchange="document.getElementById(\'preview\').style.backgroundColor=this.value;"'))?> <a href='#' onclick="show_colorfind(document.getElementById('chrColorBG'));"><img src='<?=$BF?>calendar/images/colorpallet.gif' /></a></div>
													<div id='preview' style='margin-top:10px; border:1px solid #000; text-align:center; width:100px; line-height:30px; background:#CCCCCC; color:#333333;' >Preview</div>
												</td>
											</tr>
										</table>
										<div class='FormButtons' style='padding-top:20px;'>
											<?=form_button(array('type'=>'submit','value'=>'Add Another','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'add.php\';"'))?> &nbsp;&nbsp; <?=form_button(array('type'=>'submit','value'=>'Add and Continue','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'index.php\';"'))?>
											<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'moveTo'))?>
										</div>
									</form>
<?
	}
?>