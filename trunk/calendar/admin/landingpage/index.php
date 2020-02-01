<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
									<form action="" method="post" id="idForm" onsubmit="return error_check()">
										<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
											<tr>
												<td class="tcleft">

													<?=form_textarea(array('caption'=>'Landing Page','required'=>'true','name'=>'txtHTML','rows'=>'35','style'=>'width:100%;','value'=>$info['txtHTML']))?>

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