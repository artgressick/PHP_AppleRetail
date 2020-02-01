<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
									<form action="" method="post" id="idForm" onsubmit="return error_check()">
										<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
											<tr>
												<td class="tcleft">

													<?=form_text(array('caption'=>'First Name','required'=>'true','name'=>'chrFirst','maxlength'=>'100','value'=>$info['chrFirst']))?>

													<?=form_text(array('caption'=>'Last Name','required'=>'true','name'=>'chrLast','maxlength'=>'100','value'=>$info['chrLast']))?>

													<?=form_text(array('caption'=>'Email Address','required'=>'true','name'=>'chrEmail','maxlength'=>'150','value'=>$info['chrEmail']))?>

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