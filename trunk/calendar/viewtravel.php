<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
										<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0" style='padding:10px;border:1px solid #999;'>
											<tr>
												<td class="tcleft">
<?	$users = db_query("SELECT Users.ID,CONCAT(chrFirst,' ',chrLast) as chrRecord
		FROM Users
		JOIN CalendarAccess ON CalendarAccess.idUser=Users.ID
		WHERE !Users.bDeleted
	",'getting users');	?>													
													<?=form_select($users,array('caption'=>'Traveling User','value'=>$info['idUser'],'display'=>'true'))?>

													<?=form_text(array('caption'=>'Destination','display'=>'true','value'=>$info['chrShortTitle']))?>
													
													<?=form_text(array('caption'=>'Last Updated','display'=>'true','value'=>date('m/d/Y g:i a',strtotime($info['dBegin'])).' PST'))?>

													<table cellspacing="0" cellpadding="0" style='width: 400px;'>
														<tr>
															<td><?=form_text(array('caption'=>'Arrival Date','display'=>'true','value'=>date('m/d/Y',strtotime($info['dBegin']))))?></td>
														</tr>
														<tr>
															<td><?=form_text(array('caption'=>'Departure Date','display'=>'true','value'=>date('m/d/Y',strtotime($info['dEnd']))))?></td>
														</tr>
													</table>

													<?=form_text(array('caption'=>'Notes','display'=>'true','value'=>nl2br($info['txtNote'])))?>

												</td>
												<td class="tcgutter"></td>
												<td class="tcright">
													&nbsp;
												</td>
											</tr>
										</table>
<?
	}
?>