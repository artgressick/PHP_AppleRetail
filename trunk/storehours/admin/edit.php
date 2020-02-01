<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
									<form action="" method="post" id="idForm" onsubmit="return error_check()">
										<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
											<tr>
												<td class="tcleft">

													<?=form_text(array('caption'=>'Holiday Name','required'=>'true','name'=>'chrHoliday','value'=>$info['chrHoliday']))?>

													<?=form_checkbox(array('nocaption'=>'true','name'=>'bVisible','label'=>'Show this Holiday','checked'=>($info['bVisible']==1?'true':'else')))?>

												</td>
												<td class="tcgutter"></td>
												<td class="tcright">

													<?=form_text(array('caption'=>'Begin Date','required'=>'true','name'=>'dBegin','value'=>$info['dBegin']))?>

													<?=form_text(array('caption'=>'End Date','required'=>'true','name'=>'dEnd','value'=>$info['dEnd']))?>

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