<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
									<form action="" method="post" id="idForm" onsubmit="return error_check()">
										<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
											<tr>
												<td class="tcleft">

													<?=form_text(array('caption'=>'Picture Group Name','required'=>'true','name'=>'chrNSOPictureGroup','size'=>'35','maxlength'=>'120','value'=>$info['chrNSOPictureGroup']))?>

												</td>
												<td class="tcgutter"></td>
												<td class="tcright">
													&nbsp;
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