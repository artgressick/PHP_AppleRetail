<?
	include('_controller.php');
	
	function sitm() { 
		global $BF;

		list($intDate,$intMonth,$intYear,$firstDisplayDay,$daysThisMonth,$daysLastMonth) = get_dates($_REQUEST['dBegin']);
?>
	<form action="" method="post" id="idForm" onsubmit="return error_check()">
		<table class='OneColumn' cellspacing="0" cellpadding="0">
			<tr>
				<td style='padding: 5px;'>
					
					<?=form_text(array('caption'=>'Event Name','required'=>'true','name'=>'chrCalendarEvent','size'=>'40','maxlength'=>'80'))?>

					<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
						<tr>
							<td class="tcleft">
							
								<?=form_text(array('caption'=>'Begin Date','required'=>'Required) (MM/DD/YYYY','name'=>'dBegin','size'=>'10','maxlength'=>'10','value'=>date('m/d/Y',strtotime($_REQUEST['dBegin'])),'extra'=>'onchange="changedate();"'))?>
								<?=form_text(array('caption'=>'Begin Time','required'=>'Required) (ie. 14:00','name'=>'tBegin','size'=>'10','maxlength'=>'10','value'=>(isset($_REQUEST['tBegin']) && $_REQUEST['tBegin'] != "" ? date('H:i',strtotime($_REQUEST['tBegin'])) : '')))?>

							</td>
							<td class="tcgutter"></td>
							<td class="tcright">

								<?=form_text(array('caption'=>'End Date','required'=>'Required) (MM/DD/YYYY','name'=>'dEnd','size'=>'10','maxlength'=>'10','value'=>date('m/d/Y',strtotime($_REQUEST['dBegin']))))?>
								<?=form_text(array('caption'=>'End Time','required'=>'Required) (ie. 14:00','name'=>'tEnd','size'=>'10','maxlength'=>'10','value'=>(isset($_REQUEST['tBegin']) && $_REQUEST['tBegin'] != "" ? date('H:i',strtotime($_REQUEST['tBegin']." + 1 hour")) : '')))?>

							</td>
						</tr>
					</table>
					
					<div><?=form_checkbox(array('title'=>'All Day Event','name'=>'bAllDay','checked'=>'true'))?></div>
					<div><?=form_checkbox(array('title'=>'Re-Occurring Event','name'=>'bReoccur','extra'=>'onchange="showreoccuring();"'))?></div>
					<div id='reoccur' style='display: none;'>
						<?=form_select(array('day'=>'Daily','week'=>'Weekly','month'=>'Monthly','year'=>'Yearly'),array('caption'=>'Repeat Frequency','name'=>'chrReoccur','id'=>'chrReocurr'))?>
						<?=form_text(array('caption'=>'Repeat Until','required'=>'MM/DD/YYYY','name'=>'dRepeatEnd','size'=>'10','maxlength'=>'10'))?>
					</div>

					<?=form_select(db_query("SELECT ID,chrCalendarType as chrRecord FROM CalendarTypes WHERE !bDeleted AND ID != 1 ORDER BY chrCalendarType","getting calendar types"),array('caption'=>'Calendar Type','name'=>'idCalendarType','required'=>'true'))?>

					<?=form_textarea(array('caption'=>'Page Information','name'=>'txtContent','cols'=>'100','rows'=>'30','extra'=>'wrap="virtual"'))?>

					<div class='FormButtons'>
						<?=form_button(array('type'=>'submit','name'=>'SubmitAddSection','value'=>'Save New Section'))?>
						<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'from','value'=>$_REQUEST['from']))?>
						<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'dBegin','value'=>$_REQUEST['dBegin']))?>
					</div>
				</td>
			</tr>
		</table>
	</form>
<?	} ?>