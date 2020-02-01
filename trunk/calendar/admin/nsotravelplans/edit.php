<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
									<form action="" method="post" id="idForm" onsubmit="return error_check()">
										<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
											<tr>
												<td class="tcleft">
<?	$users = db_query("SELECT Users.ID,CONCAT(chrFirst,' ',chrLast) as chrRecord
		FROM Users
		JOIN CalendarAccess ON CalendarAccess.idUser=Users.ID
		WHERE !Users.bDeleted AND bTravelAccess
	",'getting users');	?>													
													<?=form_select($users,array('caption'=>'Traveling User','name'=>'idUser','value'=>$info['idUser']))?>

													<?=form_text(array('caption'=>'Destination','required'=>'true','name'=>'chrShortTitle','size'=>'25','maxlength'=>'50','value'=>$info['chrShortTitle']))?>
													
													<?=form_text(array('caption'=>'Last Updated','display'=>'true','value'=>date('m/d/Y g:i a',strtotime($info['dBegin'])).' PST'))?>
												</td>
												<td class="tcgutter"></td>
												<td class="tcright">
													<?=form_text(array('caption'=>'Arrival Date','required'=>'Required) (MM/DD/YYYY','name'=>'dBegin','size'=>'17','maxlength'=>'20','value'=>date('m/d/Y',strtotime($info['dBegin']))))?>
													
													<?=form_text(array('caption'=>'Departure Date','required'=>'Required) (MM/DD/YYYY','name'=>'dEnd','size'=>'17','maxlength'=>'20','value'=>date('m/d/Y',strtotime($info['dEnd']))))?>
												</td>
											</tr>
											<tr>
												<td colspan='3'>
													<?=form_textarea(array('caption'=>'Notes','name'=>'txtNote','rows'=>'10','cols'=>'80','value'=>$info['txtNote']))?>
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