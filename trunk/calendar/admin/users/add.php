<?
	include('_controller.php');
	
	function sitm() { 
		global $BF;
?>
									<form action="" method="post" id="idForm" onsubmit="return error_check()">
										<div><?=form_checkbox(array('type'=>'radio','name'=>'addType','title'=>'Add Existing User','value'=>'1'))?></div>
										<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0" style='border:1px solid #333;margin:5px;padding:5px;'>
											<tr>
												<td class="tcleft">
<?					
													$q = "SELECT ID, concat(chrLast,', ',chrFirst) AS chrRecord FROM Users WHERE !bDeleted AND Users.ID NOT IN (SELECT CA.idUser FROM CalendarAccess AS CA WHERE !CA.bDeleted) ORDER BY chrRecord";
													$results = db_query($q,"getting Station Types");
?>
													<?=form_select($results,array('caption'=>'User','required'=>'true','name'=>'idUser','extra'=>'onchange="document.getElementById(\'addType1\').checked=true;"'))?>
													
												</td>
												<td class="tcgutter"></td>
												<td class="tcright">
<?					
													$q = "SELECT ID, chrSecurity AS chrRecord FROM CalSecurity WHERE !bDeleted ORDER BY chrRecord";
													$results = db_query($q,"getting Security Options");
?>
													<?=form_select($results,array('caption'=>'Security Group','required'=>'true','name'=>'idSecurity','extra'=>'onchange="document.getElementById(\'addType1\').checked=true;"'))?>

													<div class='FormName'>Allow User to see Orange Events? <span class='FormRequired'>(Required)</span></div>
													<?=form_checkbox(array('type'=>'radio','name'=>'bShowOrangeEvents','title'=>'No','value'=>'0','checked'=>'true'))?>
													<?=form_checkbox(array('type'=>'radio','name'=>'bShowOrangeEvents','title'=>'Yes','value'=>'1','checked'=>'false'))?>

												</td>
											</tr>
										</table>
										<div style='padding-top:20px;'><?=form_checkbox(array('type'=>'radio','name'=>'addType','title'=>'Add New User','value'=>'2'))?></div>
										<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0" style='border:1px solid #333;margin:5px;padding:5px;'>
											<tr>
												<td class="tcleft">
												
													<?=form_text(array('caption'=>'First Name','required'=>'true','name'=>'chrFirst','maxlength'=>'100','extra'=>'onchange="document.getElementById(\'addType2\').checked=true;"'))?>

													<?=form_text(array('caption'=>'Last Name','required'=>'true','name'=>'chrLast','maxlength'=>'100','extra'=>'onchange="document.getElementById(\'addType2\').checked=true;"'))?>

													<?=form_text(array('caption'=>'Email Address','required'=>'true','name'=>'chrEmail','maxlength'=>'150','extra'=>'onchange="document.getElementById(\'addType2\').checked=true;"'))?>
													
												</td>
												<td class="tcgutter"></td>
												<td class="tcright">
												
													<?=form_text(array('type'=>'password','caption'=>'Password','required'=>'true','name'=>'chrPassword','extra'=>'onchange="document.getElementById(\'addType2\').checked=true;"'))?>
													<?=form_text(array('type'=>'password','caption'=>'Verify Password','required'=>'true','name'=>'chrPassword2','extra'=>'onchange="document.getElementById(\'addType2\').checked=true;"'))?>
<?					
													$q = "SELECT ID, chrSecurity AS chrRecord FROM CalSecurity WHERE !bDeleted ORDER BY chrRecord";
													$results = db_query($q,"getting Security Options");
?>
													<?=form_select($results,array('caption'=>'Security Group','required'=>'true','name'=>'idSecurity2','extra'=>'onchange="document.getElementById(\'addType2\').checked=true;"'))?>

												</td>
											</tr>
										</table>
										<div style='padding-top:20px; id='colors'><div style='padding-left:5px; font-weight:bold;'>Travel Calendar Access and Colors</div>
										<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0" style='border:1px solid #333;margin:5px;padding:5px;'>
											<tr>
												<td class="tcleft">
													<div class='FormName'>Travel Calendar User <span class='FormRequired'>(Required)</span></div>
													<?=form_checkbox(array('type'=>'radio','name'=>'bTravelAccess','title'=>'No','value'=>'0','checked'=>'true'))?>
													<?=form_checkbox(array('type'=>'radio','name'=>'bTravelAccess','title'=>'Yes','value'=>'1'))?>
												
													<div id='preview' style='margin-top:10px; border:1px solid #000; text-align:center; width:100px; line-height:30px; background:#CCCCCC; color:#333333;' >Preview</div>
												</td>
												<td class="tcgutter"></td>
												<td class="tcright">
													<div class='FormName'>Text Color</div>
													<div><?=form_text(array('nocaption'=>'true','caption'=>'Text Color','required'=>'true','name'=>'chrColorText','size'=>'10','maxlength'=>'20','value'=>'#333333','extra'=>'onchange="document.getElementById(\'preview\').style.color=this.value;"'))?> <a href='#' onclick="show_colorfind(document.getElementById('chrColorText'));"><img src='<?=$BF?>calendar/images/colorpallet.gif' /></a></div>

													<div class='FormName'>Background Color</div>
													<div><?=form_text(array('nocaption'=>'true','caption'=>'Background Color','required'=>'true','name'=>'chrColorBG','size'=>'10','maxlength'=>'20','value'=>'#CCCCCC','extra'=>'onchange="document.getElementById(\'preview\').style.backgroundColor=this.value;"'))?> <a href='#' onclick="show_colorfind(document.getElementById('chrColorBG'));"><img src='<?=$BF?>calendar/images/colorpallet.gif' /></a></div>
												</td>
											</tr>
										</table>
										</div>
										<div style='padding-top:20px; id='stores'><div style='padding-left:5px; font-weight:bold;'>Store Access</div>
<?
	$Stores = db_query("SELECT ID,chrName,chrStoreNum FROM RetailStores WHERE !bDeleted ORDER BY chrName","Getting All Stores");
	
	$div = ceil(mysqli_num_rows($Stores) / 3);
?>
										<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0" style='border:1px solid #333;margin:5px;padding:5px;'>
											<tr>
												<td class="tcleft">
<?
												$count = 1;
												$min = 9999;
												$max = 0;
												while($row = mysqli_fetch_assoc($Stores)) {
													if($row['ID'] < $min) { $min = $row['ID']; }
													if($row['ID'] > $max) { $max = $row['ID']; }
													if($count > $div) {
														$count = 1;
?>
												</td>
												<td class="tcright">
<?													
													}
?>
													<div><?=form_checkbox(array('name'=>'storeaccess','array'=>'true','title'=>$row['chrName'].' '.$row['chrStoreNum'],'value'=>$row['ID']))?></div>
<?
													$count++;
												}
?>
												</td>
											</tr>
											<tr>
												<td colspan='3'>
													<input type='button' value='Select All' onclick='selectallstores();' />&nbsp;&nbsp;&nbsp;<input type='button' value='DeSelect All' onclick='deselectallstores();' />
												</td>
											</tr>
										</table>
										</div>
										<div class='FormButtons'>
											<input type='hidden' id='minstore' value='<?=$min?>' /><input type='hidden' id='maxstore' value='<?=$max?>' />
											<?=form_button(array('type'=>'submit','value'=>'Add Another','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'add.php\';"'))?> &nbsp;&nbsp; <?=form_button(array('type'=>'submit','value'=>'Add and Continue','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'index.php\';"'))?>
											<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'moveTo'))?>
										</div>
									</form>
<?
	}
?>