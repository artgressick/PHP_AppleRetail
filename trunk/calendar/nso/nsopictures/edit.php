<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
									<form action="" method="post" id="idForm" onsubmit="return error_check()">
										<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
											<tr>
												<td class="tcleft">

													<?=form_text(array('caption'=>'Picture Title','name'=>'chrFileTitle','size'=>'35','maxlength'=>'200','value'=>$info['chrFileTitle']))?>
													<?=form_checkbox(array('name'=>'bPrimary','nodisplay'=>'true','checked'=>($info['bPrimary']==1 ? 'true' : 'false')))?> <label for='bPrimary'>Use this picture to represent the store.</label>
													
													<?	$results = db_query("SELECT ID,chrNSOPictureGroup as chrRecord FROM NSOPictureGroups WHERE !bDeleted ORDER BY chrNSOPictureGroup","getting NSOFileGroups"); ?>			
													<?=form_select($results,array('caption'=>'Picture Group','required'=>'true','name'=>'idNSOFileGroup','value'=>$info['idNSOFileGroup']))?>
													

												</td>
												<td class="tcgutter"></td>
												<td class="tcright">
													<?=linkto(array('address'=>'/calendar/nsopictures/'.$info['chrCalendarFile'],'img'=>'/calendar/nsopictures/'.$info['chrThumbnail'],'extra'=>'target="_blank"'))?>
												</td>
											</tr>
										</table>
										
										<?=form_textarea(array('caption'=>'Picture Description','name'=>'txtFileDescription','cols'=>'100','rows'=>'30','extra'=>'wrap="virtual"','value'=>$info['txtFileDescription']))?>
										
										<div class='FormButtons'>
											<?=form_button(array('type'=>'submit','value'=>'Update Information'))?>
											<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'key','value'=>$_REQUEST['key']))?>
										</div>
									</form>
<?
	}
?>