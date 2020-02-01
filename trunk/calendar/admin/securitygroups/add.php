<?
	include('_controller.php');
	
	function sitm() { 
		global $BF;
?>
									<form action="" method="post" id="idForm" onsubmit="return error_check()">
										<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
											<tr>
												<td class="tcleft">

													<?=form_text(array('caption'=>'Security Group Name','required'=>'true','name'=>'chrSecurity','size'=>'35','maxlength'=>'200'))?>

												</td>
												<td class="tcgutter"></td>
												<td class="tcright">

													<div class='FormName'>Global Access <span class='FormRequired'>(Required)</span></div>
													<?=form_checkbox(array('type'=>'radio','name'=>'bGlobal','title'=>'No','value'=>'0','extra'=>'onchange="javascript:globalselect(this.value)"','checked'=>'true'))?>
													<?=form_checkbox(array('type'=>'radio','name'=>'bGlobal','title'=>'Yes','value'=>'1','extra'=>'onchange="javascript:globalselect(this.value)"'))?>

												</td>
											</tr>
										</table>
										<div class='FormButtons'>
											<?=form_button(array('type'=>'submit','value'=>'Add Another','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'add.php\';"'))?> &nbsp;&nbsp; <?=form_button(array('type'=>'submit','value'=>'Add and Continue','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'index.php\';"'))?>
											<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'moveTo'))?>
										</div>
										<div style='padding-top:10px;' id='securityoptions'>
<?

	$access[1] = 'View';
	$access[2] = 'Add';
	$access[3] = 'Edit';
	$access[4] = 'Delete';
	$access[5] = 'Search';
	$access[6] = 'View Archived';

	$q = "SELECT F.ID, F.chrOptions, F.chrDescription, G.chrGroup
			FROM CalSecFiles AS F
			JOIN CalSecGroups AS G ON F.idGroup=G.ID
			WHERE !F.bDeleted AND !G.bDeleted
			ORDER BY G.ID, F.dOrder
			";

	$files = db_query($q, "Getting Files");
	
	$group = '';
	while($row = mysqli_fetch_assoc($files)) {
		if($group != $row['chrGroup']) {
?>
											<div style='font-weight:bold; font-size:14px;padding-top:15px;'><?=$row['chrGroup']?></div>
<?
			$group = $row['chrGroup'];
		}
?>
											<table cellpadding='5' cellspacing='0' style='width:100%;'>
												<tr>
													<td style='width:200px;' class='FormName'>
														<?=$row['chrDescription']?>
													</td>
<?
		$options = explode(',',$row['chrOptions']);
		foreach($options AS $k => $v) {
?>
													<td style='width:60px; white-space:nowrap;'>
														<?=form_checkbox(array('name'=>'secure'.$row['ID'].'[]','title'=>$access[$v],'value'=>$v))?>
													</td>
<?
		}
?>
													<td>&nbsp;</td>
												</tr>
											</table>
<?
	}	
?>
											<div class='FormButtons'>
												<?=form_button(array('type'=>'submit','value'=>'Add Another','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'add.php\';"'))?> &nbsp;&nbsp; <?=form_button(array('type'=>'submit','value'=>'Add and Continue','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'index.php\';"'))?>
											</div>
										</div>
									</form>
<?
	}
?>