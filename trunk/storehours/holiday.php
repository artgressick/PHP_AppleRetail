<?php
	include('_controller.php');
	
	function sitm() {
		global $BF,$info, $resultHours;
		
		$results = db_query("SELECT ID,idDayOfWeek,tOpening,tClosing,bClosed FROM StoreHours WHERE idStore=".$_SESSION['idStore'],"getting official hours");
		$originalDays = array();
		while($row = mysqli_fetch_assoc($results)) {
			$originalDays[$row['idDayOfWeek']]['tOpening'] = (!$row['bClosed'] ? date('g:i a', strtotime($row['tOpening'])) : 'Closed');
			$originalDays[$row['idDayOfWeek']]['tClosing'] = (!$row['bClosed'] ? date('g:i a', strtotime($row['tClosing'])) : 'Closed');
		}
		
		if(mysqli_num_rows($resultHours) > 0) {
			$holidayHours = array();
			while ($row = mysqli_fetch_assoc($resultHours)) {
				$holidayHours[$row['dDate']]['tOpening'] = date('g:i a', strtotime($row['tOpening']));
				$holidayHours[$row['dDate']]['tClosing'] = date('g:i a', strtotime($row['tClosing'])); 
				$holidayHours[$row['dDate']]['bClosed'] = $row['bClosed'];
			}
		} else { $holidayHours = 0; }
		
?>

									<form method='post' id='idForm' action=''  onsubmit='return error_check();'>
										<table width="100%" cellpadding="0" cellspacing="0" style='border: 1px solid #ccc; border-bottom: none;'>
											<tr>
												<td style='border-bottom: 1px solid grey; padding: 2px 4px; font-weight:bold;'>Date</td>
												<td style='border-bottom: 1px solid grey; padding: 2px 4px; font-weight:bold;'>Original Begin</td>
												<td style='border-bottom: 1px solid grey; padding: 2px 4px; font-weight:bold;'>New Begin</td>
												<td style='border-bottom: 1px solid grey; padding: 2px 4px; font-weight:bold;'>Original End</td>
												<td style='border-bottom: 1px solid grey; padding: 2px 4px; font-weight:bold;'>New End</td>
												<td style='border-bottom: 1px solid grey; padding: 2px 4px; font-weight:bold;'>Closed</td>
											</tr>
<?	
	$totalDays = (strtotime($info['dEnd']) - strtotime($info['dBegin']))/60/60/24;
	
	$i=0;
	$dCurrent = $info['dBegin'];
	while($i <= $totalDays) {
		$dow = date('w',strtotime($dCurrent));
		if(!isset($holidayHours[$dCurrent])) { $holidayHours[$dCurrent] = array('tOpening'=>' am','tClosing'=>' pm','bClosed'=>''); }
?>
											<tr>
												<td id='chrDateText<?=$i?>' style='border-bottom: 1px solid #ccc; padding: 2px 4px;'><?=date('l, F jS, Y',strtotime($dCurrent))?></td>
												<td style='border-bottom: 1px solid #ccc; padding: 2px 4px; color: #888;'><?=$originalDays[$dow]['tOpening']?></td>
												<td style='border-bottom: 1px solid #ccc; padding: 2px 4px;'><?=form_text(array('nocaption'=>'true','name'=>'tOpening'.$i,'value'=>(!$holidayHours[$dCurrent]['bClosed']?$holidayHours[$dCurrent]['tOpening']:''),'extra'=>($holidayHours[$dCurrent]['bClosed']?'disabled="disabled"':'')))?></td>
												<td style='border-bottom: 1px solid #ccc; padding: 2px 4px; color: #888'><?=$originalDays[$dow]['tClosing']?></td>
												<td style='border-bottom: 1px solid #ccc; padding: 2px 4px;'>
													<?=form_text(array('nocaption'=>'true','name'=>'tClosing'.$i,'value'=>(!$holidayHours[$dCurrent]['bClosed']?$holidayHours[$dCurrent]['tClosing']:''),'extra'=>($holidayHours[$dCurrent]['bClosed']?'disabled="disabled"':'')))?>
													<?=form_text(array('nocaption'=>'true','type'=>'hidden','name'=>'dDate'.$i,'value'=>$dCurrent))?>
													<?=form_text(array('nocaption'=>'true','type'=>'hidden','name'=>'idDayOfWeek'.$i,'value'=>$dow))?>
												</td>
												<td style='border-bottom: 1px solid #ccc; padding: 2px 4px;'>&nbsp; &nbsp;<?=form_checkbox(array('nocaption'=>'true','name'=>'bClosed'.$i,'title'=>'','extra'=>'onclick="closed('.$i.')"','checked'=>($holidayHours[$dCurrent]['bClosed']?'true':'false')))?></td>
											</tr>
<?		
		$dCurrent = date('Y-m-d',strtotime($info['dBegin']." + ".($i++ + 1)." days"));
	}
?>

										</table>

										<div class='FormButtons'>
											<?=form_button(array('type'=>'submit','value'=>'Update Information'))?>
											<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'idHoliday','value'=>$info['ID']))?>
											<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'key','value'=>$_REQUEST['key']))?>
											<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'totalDays','value'=>$totalDays))?>
											<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'chrHoliday','value'=>$info['chrHoliday']))?>
											
										</div>
									</form>

<?	} ?>