<?php
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
	<form action="" method="post" id="idForm" onsubmit="return error_check()">
		<table class='OneColumn' cellspacing="0" cellpadding="0">
			<tr>
				<td style='padding: 5px;'>
					
					<?=form_text(array('caption'=>'Event Name','required'=>'true','name'=>'chrCalendarEvent','size'=>'40','maxlength'=>'80','value'=>$info["chrCalendarEvent"]))?>

					<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
						<tr>
							<td class="tcleft">
							
								<?=form_text(array('caption'=>'Begin Date','required'=>'Required) (MM/DD/YYYY','name'=>'dBegin','size'=>'10','maxlength'=>'10','value'=>date('m/d/Y',strtotime($info['dBegin'])),'extra'=>'onchange="changedate();"'))?>
								<?=form_text(array('caption'=>'Begin Time','required'=>'Required) (ie. 14:00','name'=>'tBegin','size'=>'10','maxlength'=>'10','value'=>($info['tBegin'] != '00:00:00' ? date('H:i',strtotime($info['tBegin'])) : '')))?>

							</td>
							<td class="tcgutter"></td>
							<td class="tcright">

								<?=form_text(array('caption'=>'End Date','required'=>'Required) (MM/DD/YYYY','name'=>'dEnd','size'=>'10','maxlength'=>'10','value'=>date('m/d/Y',strtotime($info['dEnd']))))?>
								<?=form_text(array('caption'=>'End Time','required'=>'Required) (ie. 14:00','name'=>'tEnd','size'=>'10','maxlength'=>'10','value'=>($info['tEnd'] != '00:00:00' ? date('H:i',strtotime($info['tEnd'])) : '')))?>

							</td>
						</tr>
					</table>
					
					<div><?=form_checkbox(array('title'=>'All Day Event','name'=>'bAllDay','checked'=>($info['bAllDay'] == 1 ? 'true' : 'false')))?></div>
					<div><?=form_checkbox(array('title'=>'Re-Occurring Event','name'=>'bReoccur','extra'=>'onchange="showreoccuring();"','checked'=>($info['chrSeries'] != "" ? 'true' : 'false')))?></div>
					<div id='reoccur' style='display: <?=($info['chrSeries'] != "" ? 'normal' : 'none')?>'>
						<?=form_select(array('day'=>'Daily','week'=>'Weekly','month'=>'Monthly','year'=>'Yearly'),array('caption'=>'Repeat Frequency','name'=>'chrReoccur','id'=>'chrReocurr','value'=>$info['chrReoccur']))?>
						<?=form_text(array('caption'=>'Repeat Until','required'=>'MM/DD/YYYY','name'=>'dRepeatEnd','size'=>'10','maxlength'=>'10','value'=>date('m/d/Y',strtotime($info['dEnd']))))?>
					</div>

					<?=form_select(db_query("SELECT ID,chrCalendarType as chrRecord FROM CalendarTypes WHERE !bDeleted AND ID != 1 ORDER BY chrCalendarType","getting calendar types"),array('caption'=>'Calendar Type','name'=>'idCalendarType','required'=>'true','value'=>$info['idCalendarType']))?>

					<?=form_textarea(array('caption'=>'Page Information','name'=>'txtContent','cols'=>'100','rows'=>'30','extra'=>'wrap="virtual"','value'=>$info['txtContent']))?>

					<div class='FormButtons'>
						<?=form_button(array('type'=>'submit','name'=>'SubmitAddSection','value'=>'Save New Section'))?>
						<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'key','value'=>$_REQUEST['key']))?>
						<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'id','value'=>$info['ID']))?>
						<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'from','value'=>$_REQUEST['from']))?>
					</div>
				</td>
			</tr>
		</table>
	</form>
<?
	}
?>