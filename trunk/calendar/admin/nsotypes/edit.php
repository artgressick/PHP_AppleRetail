<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
									<form action="" method="post" id="idForm" onsubmit="return error_check()">
										<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
											<tr>
												<td class="tcleft">

<? //form_select(array(1=>'New Store Opening',2=>'Re-Model'),array('caption'=>'NSO Category','name'=>'idNSOCategory','value'=>$info['idNSOCategory']))?>

													<?=form_text(array('caption'=>'NSO/Remodel Type Name','required'=>'true','name'=>'chrNSOType','size'=>'35','maxlength'=>'120','value'=>$info['chrNSOType']))?>

												</td>
												<td class="tcgutter"></td>
												<td class="tcright">

													<div class='FormName'>Text Color <span class='FormRequired'>(Required)</span></div>
													<div><?=form_text(array('nocaption'=>'true','caption'=>'Text Color','required'=>'true','name'=>'chrColorText','size'=>'10','maxlength'=>'20','value'=>$info['chrColorText'],'extra'=>'onchange="document.getElementById(\'preview\').style.color=this.value;"'))?> <a href='#' onclick="show_colorfind(document.getElementById('chrColorText'));"><img src='<?=$BF?>calendar/images/colorpallet.gif' /></a></div>
													<div class='FormName'>Background Color <span class='FormRequired'>(Required)</span></div>
													<div><?=form_text(array('nocaption'=>'true','caption'=>'Background Color','required'=>'true','name'=>'chrColorBG','size'=>'10','maxlength'=>'20','value'=>$info['chrColorBG'],'extra'=>'onchange="document.getElementById(\'preview\').style.backgroundColor=this.value;"'))?> <a href='#' onclick="show_colorfind(document.getElementById('chrColorBG'));"><img src='<?=$BF?>calendar/images/colorpallet.gif' /></a></div>
													<div id='preview' style='margin-top:10px; border:1px solid #000; text-align:center; width:100px; line-height:30px; background:<?=$info['chrColorBG']?>; color:<?=$info['chrColorText']?>;' >Preview</div>
												</td>
											</tr>
										</table>
										<div class='FormButtons'>
											<?=form_button(array('type'=>'submit','value'=>'Update Information'))?>
											<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'key','value'=>$_REQUEST['key']))?>
										</div>
									</form>
<?
	}
?>