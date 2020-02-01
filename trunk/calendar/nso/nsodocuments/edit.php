<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
									<form action="" method="post" id="idForm" onsubmit="return error_check()">
										<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
											<tr>
												<td class="tcleft">

													<?=form_text(array('caption'=>'Document Title','required'=>'true','name'=>'chrFileTitle','size'=>'35','maxlength'=>'200','value'=>$info['chrFileTitle']))?>

													<?	$results = db_query("SELECT ID,chrNSOFileGroup as chrRecord FROM NSOFileGroups WHERE !bDeleted ORDER BY chrNSOFileGroup","getting NSOFileGroups"); ?>			
													<?=form_select($results,array('caption'=>'File Group','required'=>'true','name'=>'idNSOFileGroup','value'=>$info['idNSOFileGroup']))?>


												</td>
												<td class="tcgutter"></td>
												<td class="tcright">
													<?=linkto(array('address'=>'/calendar/nsodocuments/'.$info['chrCalendarFile'],'display'=>'Download File: '.$info['chrCalendarFile']))?>
												</td>
											</tr>
										</table>
										
										<?=form_textarea(array('caption'=>'Document Description','name'=>'txtFileDescription','cols'=>'100','rows'=>'30','extra'=>'wrap="virtual"','value'=>$info['txtFileDescription']))?>
										
										<div class='FormButtons'>
											<?=form_button(array('type'=>'submit','value'=>'Update Information'))?>
											<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'key','value'=>$_REQUEST['key']))?>
										</div>
									</form>
<?
	}
?>