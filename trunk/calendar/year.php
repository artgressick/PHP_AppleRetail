<?php
	include('_controller.php');
	function sitm() { 
		global $BF;
		$_SESSION['calSection'] = 'year';
		$_SESSION['calDate'] = @$_REQUEST['dBegin'];
	
		# Setting up initial variables to be used
		list($intDate,$intMonth,$intYear,$firstDisplayDay,$daysThisMonth,$daysLastMonth) = get_dates($_REQUEST['dBegin']);

		$results = db_query("SELECT CalendarEvents.ID,CalendarEvents.chrCalendarEvent,CalendarEvents.dBegin
			FROM CalendarEvents 
			JOIN NSOs ON NSOs.ID=CalendarEvents.idNSO
			JOIN NSOUserTitleAssoc AS UTA ON UTA.idNSO=NSOs.ID AND UTA.idUserTitle=2
			JOIN Users ON Users.ID=UTA.idUser
			JOIN CalendarTypes ON CalendarTypes.ID=CalendarEvents.idCalendarType
			JOIN NSOTypes ON NSOTypes.ID=NSOs.idNSOType
			JOIN RetailStores ON NSOs.idStore=RetailStores.ID 
			WHERE ".($_SESSION['bGlobal'] ? "":"NSOs.bShow AND ")."CalendarEvents.dBegin BETWEEN '". $intYear."-01-01' AND '". $intYear."-12-31' AND !CalendarEvents.bDeleted AND !NSOs.bDeleted
			". ($_SESSION['idCalTypes'] != "" ? " AND NSOTypes.ID IN (". $_SESSION['idCalTypes'] .") " : '') ."
			". ($_SESSION['idCalUsers'] != "" ? " AND Users.ID IN (". $_SESSION['idCalUsers'] .") " : '') ."
			". ($_SESSION['chrCalRegions'] != "" ? " AND (RetailStores.chrRegion IN (". $_SESSION['chrCalRegions'] .") " : '') ."
			". (preg_match("/(^|,)'US'(,|$)/",$_SESSION['chrCalRegions']) ? " OR RetailStores.chrCountry='US') " : ($_SESSION['chrCalRegions'] != "" ? ') ' : '')) ."
			GROUP BY CalendarEvents.ID
			ORDER BY CalendarEvents.dBegin,tBegin,tEnd
		","getting events");
		$events = array();
		while($row = mysqli_fetch_assoc($results)) {
			$events[$row['dBegin']] = 1;
		} ?>
	<table style='width: 100%;'>
		<tr>
			<td><?=miniMonth($intYear."0101",$events)?></td>
			<td><?=miniMonth($intYear."0201",$events)?></td>
			<td><?=miniMonth($intYear."0301",$events)?></td>
		</tr>
		<tr>
			<td><?=miniMonth($intYear."0401",$events)?></td>
			<td><?=miniMonth($intYear."0501",$events)?></td>
			<td><?=miniMonth($intYear."0601",$events)?></td>
		</tr>
		<tr>
			<td><?=miniMonth($intYear."0701",$events)?></td>
			<td><?=miniMonth($intYear."0801",$events)?></td>
			<td><?=miniMonth($intYear."0901",$events)?></td>
		</tr>
		<tr>
			<td><?=miniMonth($intYear."1001",$events)?></td>
			<td><?=miniMonth($intYear."1101",$events)?></td>
			<td><?=miniMonth($intYear."1201",$events)?></td>
		</tr>
	</table>
<?	} 
	
	function miniMonth($date,&$events) {
		global $BF;
		$intDay = date('d',strtotime($date));
		$intMonth = date('m',strtotime($date));
		$intYear = date('Y',strtotime($date));
		
		$firstWeekday = idate('w', mktime(0, 0, 0, $intMonth, 1, $intYear));
		$firstDisplayDay = 1-$firstWeekday;
		$daysThisMonth = idate('t', mktime(0, 0, 0, $intMonth, 1, $intYear));
		$daysLastMonth = idate('t', mktime(0, 0, 0, ($intMonth-1), 1, $intYear));

?>
		<table cellpadding='0' cellspacing='0' class='calminiyear' style=''>
			<tr>
				<th colspan='7'>
					<?=linkto(array('address'=>'calendar/month.php?dBegin='.$intYear.$intMonth.'01','style'=>'color:white;','display'=>date('F',strtotime($date)).' '.$intYear))?>
					
				</th>
			</tr>
			<tr>
				<td class="days">Sun</td>
				<td class="days">Mon</td>
				<td class="days">Tue</td>
				<td class="days">Wed</td>
				<td class="days">Thu</td>
				<td class="days">Fri</td>
				<td class="days">Sat</td>
			</tr>
			<tr>
<?
	$weekDayInt = 0;
	$intMonthDay = 0;
	while($firstDisplayDay != 1) { 
?>
				<td class='diffmonth'>
					<div class='dom'>
						<?=($daysLastMonth + $firstDisplayDay)?>
						
					</div>
				</td>
<?			$weekDayInt++;
			$firstDisplayDay += 1;
	}
	
	while($intMonthDay < $daysThisMonth) { 
	
		if($weekDayInt == 7) { $weekDayInt = 0; 
?>
			</tr>
			<tr>
<?
		} 
		$weekDayInt++; 
		$intMonthDay++; 
?>
				<td class='daybox'>
					<div class="fright">
						<?=linkto(array('address'=>'calendar/day.php?dBegin='.$intYear.$intMonth.($intMonthDay < 10 ? '0'.$intMonthDay : $intMonthDay).'&from=y','display'=>$intMonthDay))?>
						
					</div>
<?
		if(isset($events[$intYear.'-'.$intMonth.'-'.($intMonthDay < 10 ? '0'.$intMonthDay : $intMonthDay)])) { ?>
					<div class='clear'>
						<?=linkto(array('address'=>'calendar/day.php?dBegin='.$intYear.$intMonth.($intMonthDay < 10 ? '0'.$intMonthDay : $intMonthDay).'&from=y','img'=>'calendar/images/yearevent.jpg'))?>
						
					</div>
<?
		} ?>
				</td>
<?	}

	$extraCnt = 1;
	while($weekDayInt++ < 7) {
?>
				<td class='diffmonth'>
					<div class='dom'>
						<?=$extraCnt++?>
						
					</div>
				</td>
<?	} ?>
			</tr>
		</table>
<?	} ?>