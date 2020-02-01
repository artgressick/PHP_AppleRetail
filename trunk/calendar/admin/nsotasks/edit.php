<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
									<form action="" method="post" id="idForm" onsubmit="return error_check()">
										<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
											<tr>
												<td class="tcleft">

<?	$results = db_query("SELECT ID,chrNSOType as chrRecord FROM NSOTypes WHERE !bDeleted ORDER BY chrNSOType","getting Types"); ?>
<?=form_select($results,array('caption'=>'NSO Types','name'=>'idNSOType','required'=>'true','value'=>$info['idNSOType']))?>

													<div style='margin-top: 10px;'>
													<?=form_text(array('caption'=>'NSO Task Name','required'=>'true','name'=>'chrNSOTask','size'=>'35','maxlength'=>'120','value'=>$info['chrNSOTask']))?>
													</div>
												</td>
												<td class="tcgutter"></td>
												<td class="tcright">

													<?=form_text(array('caption'=>'Date Offset','required'=>'true','name'=>'intDateOffset','size'=>'35','maxlength'=>'11','value'=>$info['intDateOffset']))?>

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