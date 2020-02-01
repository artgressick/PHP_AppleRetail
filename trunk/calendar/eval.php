<?
	include('_controller.php');

	function sitm() { 
		global $BF,$info;
?>

		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td width="50%"><?=form_text(array('caption'=>'Store Name/Store Number','required'=>'true','name'=>'chrStoreName','value'=>'','extra'=>'disabled="disabled"'))?></td>
				<td width="50%"><?=form_text(array('caption'=>'Completed By','required'=>'true','name'=>'chrUser','size'=>'30','extra'=>'disabled="disabled"','value'=>$_SESSION['chrFirst'].' '.$_SESSION['chrLast']))?></td>
			</tr>
			<tr>
				<td width="50%"><?=form_text(array('caption'=>'Date','name'=>'dDate','size'=>'20','value'=>date('m/d/Y'),'extra'=>'disabled="disabled"'))?></td>
				<td width="50%"><?=form_text(array('caption'=>'NSO/Remodel Lead','required'=>'true','name'=>'chrLead','size'=>'40','value'=>'','extra'=>'disabled="disabled"'))?></td>
			</tr>
		</table>
		<hr />
		<div style=" font-size:14px; font-weight:bold;">NSO/Remodel Team</div>
				
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
<?				$options = array('5'=>'5- Strongly Agree','4'=>'4- Agree','3'=>'3- Neutral','2'=>'2- Disagree','1'=>'1- Strongly Disagree'); ?>
				<td style='padding-bottom:10px;'><?=form_select($options,array('caption'=>'The NSO/Remodel team kept me informed of relevant information at all times.','name'=>'Q1','forcetitle'=>'- Select Option -'))?></td>
			</tr>
			<tr>
				<td style='padding-bottom:10px;'><?=form_select($options,array('caption'=>'The verbal communication was clear and concise.','name'=>'Q2','forcetitle'=>'- Select Option -'))?></td>
			</tr>
			<tr>
				<td style='padding-bottom:10px;'><?=form_select($options,array('caption'=>'The written materials were received in enough time to prepare for the NSO/Remodel.','name'=>'Q3','forcetitle'=>'- Select Option -'))?></td>
			</tr>
			<tr>
				<td style='padding-bottom:10px;'><?=form_select($options,array('caption'=>'The written materials were valuable to properly assist in executing my NSO/Remodel.','name'=>'Q4','forcetitle'=>'- Select Option -'))?></td>
			</tr>
			<tr>
				<td style='padding-bottom:10px;'><?=form_select($options,array('caption'=>'The NSO/Remodel team interacted well with my team and myself.','name'=>'Q5','forcetitle'=>'- Select Option -'))?></td>
			</tr>
			<tr>
				<td style='padding-bottom:10px;'><?=form_select($options,array('caption'=>'I found the regular staff meeting (am, noon and pm) fun, motivational and helpful.','name'=>'Q6','forcetitle'=>'- Select Option -'))?></td>
			</tr>
			<tr>
				<td style='padding-bottom:10px;'><?=form_select($options,array('caption'=>'The NSO/Remodel team addressed my concerns and executed in a timely manner.','name'=>'Q7','forcetitle'=>'- Select Option -'))?></td>
			</tr>
			<tr>
				<td style='padding-bottom:10px;'><?=form_select($options,array('caption'=>'My team and I were effectively utilized during the NSO/Remodel process.','name'=>'Q8','forcetitle'=>'- Select Option -'))?></td>
			</tr>
			<tr>
				<td style='padding-bottom:10px;'><?=form_select($options,array('caption'=>'I was satisfied w/ the condition of my store upon the departure of my NSO/Remodel Team Lead.','name'=>'Q9','forcetitle'=>'- Select Option -'))?></td>
			</tr>
			<tr>
				<td style='padding-bottom:10px;'><?=form_select($options,array('caption'=>'I was left with a clear punch list of remaining items I field I could easily follow up on.','name'=>'Q10','forcetitle'=>'- Select Option -'))?></td>
			</tr>
			<tr>
				<? $options2 = array('5'=>'5- Exceptional','4'=>'4- Above Average','3'=>'3- Average','2'=>'2- Below Average','1'=>'1- Unacceptable'); ?>
				<td style='padding-bottom:10px;'><?=form_select($options2,array('caption'=>'Rate your overall NSO/Remodel Experience.','name'=>'Q111','forcetitle'=>'- Select Option -'))?></td>
			</tr>
			<tr>
				<td style='padding-bottom:10px;'><?=form_textarea(array('caption'=>'Explain.','name'=>'','required'=>'Include examples','rows'=>'10','style'=>'width:100%;','value'=>''))?></td>
			</tr>
		</table>	
		<hr />
		<div style=" font-size:14px; font-weight:bold;">Construction</div>
		
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td style='padding-bottom:10px;'><?=form_select($options,array('caption'=>'I was satisfied with the condition of my store upon arrival.','name'=>'Q12','forcetitle'=>'- Select Option -'))?></td>
			</tr>
			<tr>
				<td style='padding-bottom:10px;'><?=form_select($options,array('caption'=>'I was satisfied with my BOH layout and felt it was an efficient use of the space.','name'=>'Q13','forcetitle'=>'- Select Option -'))?></td>
			</tr>
			<tr>
				<td style='padding-bottom:10px;'><?=form_textarea(array('caption'=>'Explain.','name'=>'','rows'=>'5','style'=>'width:100%;','value'=>''))?></td>
			</tr>
		</table>
		<hr />
		<div style=" font-size:14px; font-weight:bold;">Visual</div>
		
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td style='padding-bottom:10px;'><?=form_textarea(array('caption'=>'Other departments that want to be included.','name'=>'','rows'=>'8','style'=>'width:100%;','value'=>''))?></td>
			</tr>
		</table>
		<hr />
		<div style=" font-size:14px; font-weight:bold;">Additional Comments and Concerns <span class='FormRequired'>(please include specifics)</span></div>
		
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td style='padding-bottom:10px;'><?=form_textarea(array('nocaption'=>'true','name'=>'','rows'=>'5','style'=>'width:100%;','value'=>''))?></td>
			</tr>
			<tr>
				<td style='padding-top:10px;'>
					<?=form_button(array('type'=>'submit','name'=>'submit','value'=>'Submit'))?>
				</td>
			</tr>
		</table>
<?
	}
?>