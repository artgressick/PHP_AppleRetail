<?php
	include('_controller.php');
	
	function sitm() { 
		global $BF,$fdow,$ldow;

		$_SESSION['calSection'] = 'week';
		$_SESSION['calDate'] = @$_REQUEST['dBegin'];

		$travel_results = db_query("SELECT dBegin,dEnd,chrKEY,CONCAT(chrFirst,' ',chrLast,' - ',chrShortTitle) as chrDisplay
			FROM NSOTravelPlans
			JOIN Users ON Users.ID=NSOTravelPlans.idUser
			WHERE !NSOTravelPlans.bDeleted AND
			  ((NSOTravelPlans.dBegin BETWEEN '".$fdow."' AND '".$ldow."') OR (NSOTravelPlans.dEnd BETWEEN '".$fdow."' AND '".$ldow."') OR ('".$fdow."' BETWEEN NSOTravelPlans.dBegin AND NSOTravelPlans.dEnd))
			". ($_SESSION['idCalUsers'] != "" ? " AND Users.ID IN (". $_SESSION['idCalUsers'] .") " : '') ."  
			ORDER BY dBegin,dEnd
		","getting travel stuff");
	
?>


<div style='margin: 10px 0 5px; font-size: 14px;'>Travel Information</div>
	<table cellspacing="0" cellpadding="0" style='width: 735px;' class='longviews'>
		<tr style='background: url(<?=$BF?>calendar/images/weekbg.png) repeat-y;'>
			<td style='padding: 5px; width: 105px; text-align: center;  border: 1px solid black; background: #eee;'><a href='day.php?dBegin=<?=$_REQUEST['dBegin']?>'><?=date('D, M jS',strtotime($fdow))?></a></td>
<?		
		$i = 1;
		while($i < 7) {
			?><td style='padding: 5px; width: 105px; text-align: center;  border: 1px solid black; border-left: 0; background: #eee;'><a href='day.php?dBegin=<?=$_REQUEST['dBegin']?>'><?=date('D, M jS',strtotime($fdow.' +'.$i.' days'))?></a></td><?
			++$i;
		}
?>
		</tr>

		<tr><td colspan='7' style='height: 2px;'></td></tr>
<?	


		$intFdow = strtotime($fdow);
		$intLdow = strtotime($ldow);
		while($row = mysqli_fetch_assoc($travel_results)) {
			$tmpBegin = strtotime($row['dBegin']);
			$tmpEnd = strtotime($row['dEnd']);
			
			if(($tmpBegin < $intFdow) && ($tmpEnd > $intLdow)) {
			# Event first day is BEFORE the first day of this week, and event last day is AFTER last day of this week. Ex: xxx
				?>		<tr><td colspan='7' style='height: 16px; border-top: 1px solid black; border-bottom: 1px solid black; padding: 0 7px; overflow:hidden;'><div style='height:15px; white-space:nowrap; overflow:hidden;'><?=linkto(array('address'=>'calendar/nso/landing.php?key='.$row['chrKEY'],'display'=>$row['chrFirst']." ".$row['chrLast']." - ".$row['chrShortTitle']))?></div></td></tr> <?


			} else if(($tmpBegin >= $intFdow) && ($tmpEnd > $intLdow)) {
			# Event first day occurs within the week, and event last day is AFTER last day of this week.  Ex: ==(xxx
				$firstOffset = (($tmpBegin - $intFdow)/60/60/24);
				?>		<tr><?=(str_repeat('<td>&nbsp;</td>',$firstOffset))?><td colspan='<?=(7-$firstOffset)?>'><img src='<?=$BF?>calendar/images/calendar-cap-left.png' alt='left cap' style='float: left;' /><div style='height: 15px; border-top: 1px solid black; border-bottom: 1px solid black; white-space:nowrap; overflow:hidden;'><?=linkto(array('address'=>'calendar/nso/landing.php?key='.$row['chrKEY'],'display'=>$row['chrFirst']." ".$row['chrLast']." - ".$row['chrShortTitle']))?></div></td></tr> <?


			} else if(($tmpBegin < $intFdow) && ($tmpEnd <= $intLdow)) {
			# Event first day occurs BEFORE first day of week, and event last day before last day of this week. Ex: xxx)==
				$lastOffset = (($intLdow - $tmpEnd)/60/60/24);
				?>		<tr><td colspan='<?=(7-$lastOffset)?>'><table cellspacing="0" cellpadding="0" style='width: 100%;'><tr><td style='padding-left: 7px; width: 100%; height: 15px; border-top: 1px solid black; border-bottom: 1px solid black;'><div style='height:15px; white-space:nowrap; overflow:hidden;'><?=linkto(array('address'=>'calendar/nso/landing.php?key='.$row['chrKEY'],'display'=>$row['chrFirst']." ".$row['chrLast']." - ".$row['chrShortTitle']))?></div></td><td style='text-align: right;'><img src='<?=$BF?>calendar/images/calendar-cap-right.png' alt='right cap' /></td></tr></table></td><?=(str_repeat('<td>&nbsp;</td>',$lastOffset))?></tr> <?

			} else {
			# Event first day occurs BEFORE first day of week, and event last day before last day of this week. Ex: ==(xxx)==
				$lastOffset = (($intLdow - $tmpEnd)/60/60/24);
				$firstOffset = (($tmpBegin - $intFdow)/60/60/24);
				?>		<tr><?=(str_repeat('<td>&nbsp;</td>',$firstOffset))?><td colspan='<?=((7-$firstOffset)-$lastOffset)?>'><table cellspacing="0" cellpadding="0" style='width: 100%;'><tr><td><img src='<?=$BF?>calendar/images/calendar-cap-left.png' alt='left cap' /></td><td style='width: 100%; height: 15px; border-top: 1px solid black; border-bottom: 1px solid black;'><div style='height:15px; white-space:nowrap; overflow:hidden;'><?=linkto(array('address'=>'calendar/nso/landing.php?key='.$row['chrKEY'],'display'=>$row['chrFirst']." ".$row['chrLast']." - ".$row['chrShortTitle']))?></div><td><img src='<?=$BF?>calendar/images/calendar-cap-right.png' alt='right cap' /></tr></table></td><?=(str_repeat('<td>&nbsp;</td>',$lastOffset))?></tr> <?

			
			}
			echo "<tr><td colspan='7' style='height: 2px;'></td></tr>";
		} 
?>
	</table>

	<div style='margin-top: 10px;'><?=form_button(array('type'=>'button','value'=>'Back to Week Calendar','extra'=>'onclick="window.location.href=\'week.php?dBegin='.$_REQUEST['dBegin'].'\'"'))?></div>

<?
	}
?>