<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;
?>
									<form action="" method="post" id="idForm" onsubmit="return error_check()">
										<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
											<tr>
												<td class="tcleft" style='padding:10px;'>
													There are a total of <?=mysqli_num_rows($results)?> events with date changes.
												</td>
											</tr>
										</table>
										<div class='FormButtons'>
											<?=form_button(array('type'=>'submit','name'=>'sendnow','value'=>'Send Now'))?>
										</div>
									</form>
<?
	}
?>