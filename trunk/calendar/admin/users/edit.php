<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
									<form action="" method="post" id="idForm" onsubmit="return error_check()">
										<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
											<tr>
												<td class="tcleft">
												
<?					
													$q = "SELECT ID, concat(chrLast,', ',chrFirst) AS chrRecord FROM Users WHERE !bDeleted ORDER BY chrRecord";
													$results = db_query($q,"getting Station Types");
?>
													<?=form_select($results,array('caption'=>'User','value'=>$info['idUser'],'display'=>'true'))?>
													
												</td>
												<td class="tcgutter"></td>
												<td class="tcright">
<?					
													$q = "SELECT ID, chrSecurity AS chrRecord FROM CalSecurity WHERE !bDeleted ORDER BY chrRecord";
													$results = db_query($q,"getting Security Options");
?>
													<?=form_select($results,array('caption'=>'Security Group','required'=>'true','name'=>'idSecurity','value'=>$info['idSecurity']))?>

													<div class='FormName'>Allow User to see Orange Events? <span class='FormRequired'>(Required)</span></div>
													<?=form_checkbox(array('type'=>'radio','name'=>'bShowOrangeEvents','title'=>'No','value'=>'0','checked'=>(!$info['bShowOrangeEvents']?'true':'false')))?>
													<?=form_checkbox(array('type'=>'radio','name'=>'bShowOrangeEvents','title'=>'Yes','value'=>'1','checked'=>($info['bShowOrangeEvents']?'true':'false')))?>
													
												</td>
											</tr>
										</table>
										<div style='padding-top:20px; id='colors'><div style='padding-left:5px; font-weight:bold;'>Travel Calendar Access and Colors</div>
										<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0" style='border:1px solid #333;margin:5px;padding:5px;'>
											<tr>
												<td class="tcleft">

													<div class='FormName'>Travel Calendar User <span class='FormRequired'>(Required)</span></div>
													<?=form_checkbox(array('type'=>'radio','name'=>'bTravelAccess','title'=>'No','value'=>'0','checked'=>(!$info['bTravelAccess']?'true':'false')))?>
													<?=form_checkbox(array('type'=>'radio','name'=>'bTravelAccess','title'=>'Yes','value'=>'1','checked'=>($info['bTravelAccess']?'true':'false')))?>
												
													<div id='preview' style='margin-top:10px; border:1px solid #000; text-align:center; width:100px; line-height:30px; background:<?=$info['chrColorBG']?>; color:<?=$info['chrColorText']?>;' >Preview</div>
												</td>
												<td class="tcgutter"></td>
												<td class="tcright">
													<div class='FormName'>Text Color</div>
													<div><?=form_text(array('nocaption'=>'true','caption'=>'Text Color','required'=>'true','name'=>'chrColorText','size'=>'10','maxlength'=>'20','value'=>$info['chrColorText'],'extra'=>'onchange="document.getElementById(\'preview\').style.color=this.value;"'))?> <a href='#' onclick="show_colorfind(document.getElementById('chrColorText'));"><img src='<?=$BF?>calendar/images/colorpallet.gif' /></a></div>

													<div class='FormName'>Background Color</div>
													<div><?=form_text(array('nocaption'=>'true','caption'=>'Background Color','required'=>'true','name'=>'chrColorBG','size'=>'10','maxlength'=>'20','value'=>$info['chrColorBG'],'extra'=>'onchange="document.getElementById(\'preview\').style.backgroundColor=this.value;"'))?> <a href='#' onclick="show_colorfind(document.getElementById('chrColorBG'));"><img src='<?=$BF?>calendar/images/colorpallet.gif' /></a></div>
												</td>
											</tr>
										</table>
										</div>
										<div style='padding-top:20px; id='stores'><div style='padding-left:5px; font-weight:bold;'>Store Access</div>
<?
	$txtStoreAccess = explode(',',$info['txtStoreAccess']);
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
													<div><?=form_checkbox(array('name'=>'storeaccess','array'=>'true','title'=>$row['chrName'].' '.$row['chrStoreNum'],'value'=>$row['ID'],'checked'=>(in_array($row['ID'],$txtStoreAccess) ? 'true' : 'false')))?></div>
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
											<?=form_button(array('type'=>'submit','value'=>'Update Information'))?>
											<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'key','value'=>$_REQUEST['key']))?>
										</div>
									</form>
<?
	}
?>