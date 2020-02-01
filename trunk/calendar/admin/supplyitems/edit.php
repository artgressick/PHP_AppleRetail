<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
									<form action="" method="post" id="idForm" onsubmit="return error_check()">
										<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
											<tr>
												<td class="tcleft">

													<?=form_text(array('caption'=>'Supply Item Name','required'=>'true','name'=>'chrItem','size'=>'35','maxlength'=>'200','value'=>$info['chrItem']))?>
													
													<?=form_textarea(array('caption'=>'Description','name'=>'txtDescription','rows'=>'10','cols'=>'32','style'=>'width:100%;','value'=>$info['txtDescription']))?>

												</td>
												<td class="tcgutter" style='width:20px;'></td>
												<td class="tcright" style='width:50%;'>
													
													<?=form_text(array('caption'=>'Upload Picture','type'=>'file','name'=>'chrPicture'))?>
<?
												if($info['chrThumbnail'] != '') {
?>													
													<div><img src='<?=$BF?>calendar/nsosupply/<?=$info['chrThumbnail']?>' alt='Item Image' /></div>
<?
												}
?>													
													
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