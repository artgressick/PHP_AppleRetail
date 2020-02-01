<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
									<form action="" method="post" id="idForm" onsubmit="return error_check()">
										<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
											<tr>
												<td class="tcleft">
												
													<div style='padding-bottom:5px;'><?=form_checkbox(array('type'=>'radio','caption'=>'Status','title'=>'Active','required'=>'true','name'=>'bShow','value'=>'1','checked'=>($info['bShow'] ? 'true' : 'false')))?><?=form_checkbox(array('type'=>'radio','title'=>'De-Active','name'=>'bShow','value'=>'0','checked'=>'true','checked'=>(!$info['bShow'] ? 'true' : 'false')))?></div>

													<?=form_textarea(array('caption'=>'Announcement','name'=>'txtAnnoucement','rows'=>'35','style'=>'width:100%;','value'=>$info['txtAnnoucement']))?>

												</td>
											</tr>
										</table>
										<div class='FormButtons'>
											<?=form_button(array('type'=>'submit','value'=>'Update Information'))?>
										</div>
									</form>
<?
	}
?>