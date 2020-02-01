<?
	include('_controller.php');
	
	function sitm() { 
		global $BF;
?>
									<form action="" method="post" id="idForm" onsubmit="return error_check()">
										<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
											<tr>
												<td class="tcleft">
												
<?	$results = db_query("SELECT ID,chrNSOType as chrRecord FROM NSOTypes WHERE !bDeleted ORDER BY chrNSOType","getting Types"); ?>
<?=form_select($results,array('caption'=>'NSO Types','name'=>'idNSOType','required'=>'true','value'=>$info['idNSOType']))?>

													<div style='margin-top: 10px;'>
													<?=form_text(array('caption'=>'NSO Task Name','required'=>'true','name'=>'chrNSOTask','size'=>'35','maxlength'=>'120'))?>
													</div>
												</td>
												<td class="tcgutter"></td>
												<td class="tcright">

													<?=form_text(array('caption'=>'Date Offset','required'=>'true','name'=>'intDateOffset','size'=>'35','maxlength'=>'11'))?>

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