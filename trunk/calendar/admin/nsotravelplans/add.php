<?
	include('_controller.php');
	
	function sitm() { 
		global $BF;
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
													<?=form_select($users,array('caption'=>'Traveling User','name'=>'idUser','value'=>$_SESSION['idUser']))?>

													<?=form_text(array('caption'=>'Destination','required'=>'true','name'=>'chrShortTitle','size'=>'25','maxlength'=>'50'))?>

												</td>
												<td class="tcgutter"></td>
												<td class="tcright">
													<?=form_text(array('caption'=>'Arrival Date','required'=>'Required) (MM/DD/YYYY','name'=>'dBegin','size'=>'17','maxlength'=>'20'))?>

													<?=form_text(array('caption'=>'Departure Date','required'=>'Required) (MM/DD/YYYY','name'=>'dEnd','size'=>'17','maxlength'=>'20'))?>
												</td>
											</tr>
											<tr>
												<td colspan='3'>
													<?=form_textarea(array('caption'=>'Notes','name'=>'txtNote','rows'=>'10','cols'=>'80'))?>
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