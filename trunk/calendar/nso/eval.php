<?
	include('_controller.php');

	function sitm() { 
		global $BF,$info;
?>

		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td width="50%"><?=form_text(array('caption'=>'Store Name/Store Number','name'=>'chrStoreName','value'=>'','display'=>'true'))?></td>
				<td width="50%"><?=form_text(array('caption'=>'Completed By','name'=>'chrUser','size'=>'30','display'=>'true','value'=>$_SESSION['chrFirst'].' '.$_SESSION['chrLast']))?></td>
			</tr>
			<tr>
				<td width="50%"><?=form_text(array('caption'=>'Date','name'=>'dDate','size'=>'20','value'=>date('m/d/Y'),'display'=>'true'))?></td>
				<td width="50%"><?=form_text(array('caption'=>'NSO/Remodel Lead','name'=>'chrLead','size'=>'40','value'=>'','display'=>'true'))?></td>
			</tr>
		</table>
		<hr />
		<div style=" font-size:14px; font-weight:bold;">NSO/Remodel Team</div>
				
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td><?=form_text(array('caption'=>'The NSO/Remodel team kept me informed of relevant information at all times.','value'=>($info['Q1']==5?'Strongly Agree':($info['Q1']==4?'Agree':($info['Q1']==3?'Neutral':($info['Q1']==2?'Disagree':'Strongly Disagree')))),'display'=>'true'))?></td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'The verbal communication was clear and concise.','value'=>($info['Q2']==5?'Strongly Agree':($info['Q2']==4?'Agree':($info['Q2']==3?'Neutral':($info['Q2']==2?'Disagree':'Strongly Disagree')))),'display'=>'true'))?></td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'The written materials were received in enough time to prepare for the NSO/Remodel.','value'=>($info['Q3']==5?'Strongly Agree':($info['Q3']==4?'Agree':($info['Q3']==3?'Neutral':($info['Q3']==2?'Disagree':'Strongly Disagree')))),'display'=>'true'))?></td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'The written materials were valuable to properly assist in executing my NSO/Remodel.','value'=>($info['Q4']==5?'Strongly Agree':($info['Q4']==4?'Agree':($info['Q4']==3?'Neutral':($info['Q4']==2?'Disagree':'Strongly Disagree')))),'display'=>'true'))?></td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'The NSO/Remodel team interacted well with my team and myself.','value'=>($info['Q5']==5?'Strongly Agree':($info['Q5']==4?'Agree':($info['Q5']==3?'Neutral':($info['Q5']==2?'Disagree':'Strongly Disagree')))),'display'=>'true'))?></td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'I found the regular staff meeting (am, noon and pm) fun, motivational and helpful.','value'=>($info['Q6']==5?'Strongly Agree':($info['Q6']==4?'Agree':($info['Q6']==3?'Neutral':($info['Q6']==2?'Disagree':'Strongly Disagree')))),'display'=>'true'))?></td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'The NSO/Remodel team addressed my concerns and executed in a timely manner.','value'=>($info['Q7']==5?'Strongly Agree':($info['Q7']==4?'Agree':($info['Q7']==3?'Neutral':($info['Q7']==2?'Disagree':'Strongly Disagree')))),'display'=>'true'))?></td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'My team and I were effectively utilized during the NSO/Remodel process.','value'=>($info['Q8']==5?'Strongly Agree':($info['Q8']==4?'Agree':($info['Q8']==3?'Neutral':($info['Q8']==2?'Disagree':'Strongly Disagree')))),'display'=>'true'))?></td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'I was satisfied w/ the condition of my store upon the departure of my NSO/Remodel Team Lead.','value'=>($info['Q9']==5?'Strongly Agree':($info['Q9']==4?'Agree':($info['Q9']==3?'Neutral':($info['Q9']==2?'Disagree':'Strongly Disagree')))),'display'=>'true'))?></td>
		</tr>
			<tr>
				<td><?=form_text(array('caption'=>'I was left with a clear punch list of remaining items I field I could easily follow up on.','value'=>($info['Q10']==5?'Strongly Agree':($info['Q10']==4?'Agree':($info['Q10']==3?'Neutral':($info['Q10']==2?'Disagree':'Strongly Disagree')))),'display'=>'true'))?></td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'Rate your overall NSO/Remodel Experience.','value'=>($info['Q3']==5?'Exceptional':($info['Q3']==4?'Above Average':($info['Q3']==3?'Average':($info['Q3']==2?'Below Average':'Unacceptable')))),'display'=>'true'))?></td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'Explain.','value'=>nl2br(''),'display'=>'true'))?></td>
			</tr>
		</table>	
		<hr />
		<div style=" font-size:14px; font-weight:bold;">Construction</div>
		
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td><?=form_text(array('caption'=>'I was satisfied with the condition of my store upon arrival.','value'=>($info['Q11']==5?'Strongly Agree':($info['Q11']==4?'Agree':($info['Q11']==3?'Neutral':($info['Q11']==2?'Disagree':'Strongly Disagree')))),'display'=>'true'))?></td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'I was satisfied with my BOH layout and felt it was an efficient use of the space.','value'=>($info['Q12']==5?'Strongly Agree':($info['Q12']==4?'Agree':($info['Q12']==3?'Neutral':($info['Q12']==2?'Disagree':'Strongly Disagree')))),'display'=>'true'))?></td>
			</tr>
			<tr>
				<td><?=form_text(array('caption'=>'Explain.','name'=>'','value'=>nl2br(''),'display'=>'true'))?></td>
			</tr>
		</table>
		<hr />
		<div style=" font-size:14px; font-weight:bold;">Visual</div>
		
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td><?=form_text(array('caption'=>'Other departments that want to be included.','value'=>nl2br(''),'display'=>'true'))?></td>
			</tr>
		</table>
		<hr />
		<div style=" font-size:14px; font-weight:bold;">Additional Comments and Concerns <span class='FormRequired'>(please include specifics)</span></div>
		
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td style='padding-bottom:10px;'><?=form_text(array('nocaption'=>'true','value'=>nl2br(''),'display'=>'true'))?></td>
			</tr>
		</table>
<?
	}
?>