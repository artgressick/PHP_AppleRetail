<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
		
		$results = db_query("SELECT ID,chrFirst,chrLast FROM Users WHERE !bDeleted ORDER by chrFirst,chrLast","Get All Users");
?>
									<form action="" method="post" id="idForm" onsubmit="return error_check()">
										<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
											<tr>
												<td class="tcleft" style='width:34%;'>

<?
								$cols = 3;
								$rpc = ceil(mysqli_num_rows($results) / $cols)-1;
								$count = 0;
									while($row = mysqli_fetch_assoc($results)) {
										if($count++ > $rpc) {
?>
												</td>
												<td class="tcgutter"></td>
												<td class="tcright" style='width:33%;'>
<?
											$count=1;
										}
?> 
													<?=form_checkbox(array('array'=>'true','name'=>'idUsers','value'=>$row['ID'],'title'=>$row['chrFirst'].' '.$row['chrLast'],'checked'=>(in_array($row['ID'],$info['idUsers'])?'true':'false')))?><br />
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