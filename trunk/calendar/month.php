<?php
	include('_controller.php');
	
	function sitm() { 
		global $BF;

		list($intDate,$intMonth,$intYear,$firstDisplayDay,$daysThisMonth,$daysLastMonth) = get_dates($_REQUEST['dBegin']);
		
		$_SESSION['calSection'] = 'month';
		$_SESSION['calDate'] = $_REQUEST['dBegin'];

		if(isset($_SESSION['CalendarType']) && $_SESSION['CalendarType'] == 'travel') {
		
			$q = "SELECT NSOTravelPlans.ID,NSOTravelPlans.chrKEY,CONCAT(chrFirst,' ',chrLast,' - ',chrShortTitle) as chrDisplay, NSOTravelPlans.chrKEY as chrSeries,DAY(NSOTravelPlans.dBegin) AS dDay,  
					CA.chrColorText, CA.chrColorBG,CalendarTypes.ID AS idCalendarType,NSOTravelPlans.chrKEY as chrNSOKey, NSOTravelPlans.dBegin AS dDateBegin,NSOTravelPlans.dEnd AS dDateEnd
					FROM NSOTravelPlans
					JOIN Users ON Users.ID=NSOTravelPlans.idUser
					JOIN CalendarTypes ON CalendarTypes.ID=3
					JOIN CalendarAccess AS CA ON CA.idUser=Users.ID
					WHERE !NSOTravelPlans.bDeleted AND CA.bTravelAccess AND 
					  NSOTravelPlans.dBegin BETWEEN '".($intMonth-1 < 1 ? $intYear-1 : $intYear)."-".($intMonth-1 < 1?'12':$intMonth-1)."-".$daysLastMonth."' AND '".($intMonth+1 > 12 ? $intYear+1 : $intYear)."-".($intMonth+1 > 12 ? '1' : $intMonth+1)."-01'
					". ($_SESSION['idTravelUsers'] != "" ? " AND Users.ID IN (". $_SESSION['idTravelUsers'] .") " : '') ."  
					ORDER BY dBegin
				";
			$link = 'calendar/viewtravel.php';
			
			$results = db_query($q,"getting events");
			
			$events = array();
			$series = '~';
			$highest = array();
			$cnt=1;
	
			while($row = mysqli_fetch_assoc($results)) {
				$totalDays = (strtotime($row['dDateEnd']) - strtotime($row['dDateBegin']))/60/60/24;
				$i=0;
				$dCurrent = $row['dDateBegin'];
				while($i <= $totalDays) {	
	
					$dow = date('w',strtotime($dCurrent))==0 ? date('W',strtotime($dCurrent.' +1 day')) : date('W',strtotime($dCurrent));
				
					if(!isset($events[$dCurrent][0][$dow])) { $events[$dCurrent][0][$dow] = 0; $highest[$dow]=0; }
					if(($events[$dCurrent][0][$dow] + 1) > $highest[$dow]) { $highest[$dow] = $events[$dCurrent][0][$dow] + 1; } 
					
					$events[$dCurrent][0][$dow] = $highest[$dow];
					$events[$dow] = $highest[$dow];
					$events[0][$dow][$cnt]['displayed'] = false; 
					$events[$dCurrent][$cnt] = array('chrSeries' => $row['chrSeries'], 
													   'chrKEY' => $row['chrKEY'],
													   'chrCalendarEvent' => $row['chrDisplay'],
													   'chrNSOKey' => $row['chrNSOKey'],
													   'chrColorBG' => $row['chrColorBG'],
													   'chrColorText' => $row['chrColorText'],
													   'idType' => $row['idCalendarType'],
													   'dDateBegin' => $row['dDateBegin'],
													   'dDateEnd' => $row['dDateEnd'],
													   '1' => $row['chrDisplay'],
													   'dow' => $dow
													   );
					$dCurrent = date('Y-m-d',strtotime($row['dDateBegin']." + ".($i++ + 1)." days"));
				}				
				$cnt++;
			}
		} else {
			$q = "SELECT CalendarEvents.ID,CalendarEvents.chrKey,CONCAT(RetailStores.chrName,' / ',RetailStores.chrStoreNum) as chrDisplay,CalendarEvents.chrSeries,CalendarEvents.chrCalendarEvent,DAY(CalendarEvents.dBegin) as dDay,
					CalendarTypes.chrColorText,CalendarTypes.chrColorBG,NSOTypes.chrColorText AS chrNSOColorText,NSOTypes.chrColorBG AS chrNSOColorBG,idCalendarType,NSOs.chrKey as chrNSOKey, MIN(CalendarEvents.dBegin) as dDateBegin, MAX(CalendarEvents.dBegin) as dDateEnd,
					GROUP_CONCAT(CONCAT(CalendarEvents.dBegin,':::',WEEK(CalendarEvents.dBegin,1)) ORDER BY CalendarEvents.dBegin SEPARATOR ',') AS dDates, NSOs.dDate2, NSOs.dDate3, NSOs.dDate4
				FROM CalendarEvents
				JOIN NSOs ON NSOs.ID=CalendarEvents.idNSO
				JOIN CalendarTypes ON CalendarTypes.ID=CalendarEvents.idCalendarType
				JOIN NSOTypes ON NSOTypes.ID=NSOs.idNSOType
				JOIN NSOUserTitleAssoc AS UTA ON UTA.idNSO=NSOs.ID AND UTA.idUserTitle=2
				JOIN Users ON Users.ID=UTA.idUser
				JOIN RetailStores ON NSOs.idStore=RetailStores.ID 
				WHERE ".($_SESSION['bGlobal'] ? "":"NSOs.bShow AND ")."CalendarEvents.dBegin BETWEEN '". ($intMonth-1 < 1 ? $intYear-1 : $intYear)."-".($intMonth-1 < 1 ? '12' : $intMonth-1)."-".$daysLastMonth."' AND '". ($intMonth+1 > 12 ? $intYear+1 : $intYear)."-".($intMonth+1 > 12?'1':$intMonth+1)."-01' AND NSOs.dBegin < '". ($intMonth+1 > 12 ? $intYear+1 : $intYear)."-".($intMonth+1 > 12?'1':$intMonth+1)."-01' AND !CalendarEvents.bDeleted AND !NSOs.bDeleted
				". ($_SESSION['idCalTypes'] != "" ? " AND NSOTypes.ID IN (". $_SESSION['idCalTypes'] .") " : '') ."
				". ($_SESSION['idCalUsers'] != "" ? " AND Users.ID IN (". $_SESSION['idCalUsers'] .") " : '') ."
				". ($_SESSION['chrCalRegions'] != "" ? " AND (RetailStores.chrRegion IN (". $_SESSION['chrCalRegions'] .") " : '') ."
				". (preg_match("/(^|,)'US'(,|$)/",$_SESSION['chrCalRegions']) ? " OR RetailStores.chrCountry='US') " : ($_SESSION['chrCalRegions'] != "" ? ') ' : '')) ."
				GROUP BY chrSeries
				ORDER BY NSOs.dBegin,chrDisplay
			";
			
			$link = 'calendar/nso/landing.php';

			$results = db_query($q,"getting events");
				
			$events = array();
			$series = '~';
			$highest = array();
			$cnt=1;
	
			while($row = mysqli_fetch_assoc($results)) {
				if(isset($row['chrNSOColorText']) && $row['chrNSOColorText'] != '') { $row['chrColorText'] = $row['chrNSOColorText']; }
				if(isset($row['chrNSOColorBG']) && $row['chrNSOColorBG'] != '') { $row['chrColorBG'] = $row['chrNSOColorBG']; }
				$dates = explode(',',$row['dDates']);
				foreach ($dates AS $date) {
					$thisday = explode(':::',$date);
					if($thisday[1] < 10) { $thisday[1] = '0'.$thisday[1]; } 
					if(!isset($events[$thisday[0]][0][$thisday[1]])) { $events[$thisday[0]][0][$thisday[1]] = 0; $highest[$thisday[1]]=0; }
					if(($events[$thisday[0]][0][$thisday[1]] + 1) > $highest[$thisday[1]]) { $highest[$thisday[1]] = $events[$thisday[0]][0][$thisday[1]] + 1; } 
					
					$events[$thisday[0]][0][$thisday[1]] = $highest[$thisday[1]];
					$events[$thisday[1]] = $highest[$thisday[1]];
					$events[0][$thisday[1]][$cnt]['displayed'] = false; 
					$events[$thisday[0]][$cnt] = array('chrSeries' => $row['chrSeries'], 
													   'chrKEY' => $row['chrKey'],
													   'chrCalendarEvent' => $row['chrDisplay'],
													   'chrNSOKey' => $row['chrNSOKey'],
													   'chrColorBG' => $row['chrColorBG'],
													   'chrColorText' => $row['chrColorText'],
													   'idType' => $row['idCalendarType'],
													   'dDateBegin' => $row['dDateBegin'],
													   'dDateEnd' => $row['dDateEnd'],
													   'dDate2' => ($row['dDate2'] != '' && $row['dDate2'] != '0000-00-00' ? $row['dDate2'] : ''),
													   'dDate3' => ($row['dDate3'] != '' && $row['dDate3'] != '0000-00-00' ? $row['dDate3'] : ''),
													   'dDate4' => ($row['dDate4'] != '' && $row['dDate4'] != '0000-00-00' ? $row['dDate4'] : ''),
													   'dow' => $thisday[1],
													   '1' => $row['chrDisplay'],
													   '2' => $row['chrDisplay'].' - SWS Opens',
													   '3' => $row['chrDisplay'].' - Last Day SWS Open',
													   '4' => $row['chrDisplay'].' - Store Set Up'
													   );
				}				
				$cnt++;
			}
		}
		
		tmp_val('chrICalQuery','set',$q);
		
/*		echo "<pre>";
		print_r($events);
		echo "</pre>";
*/
?>
				<table cellpadding='0' cellspacing='0' class='calmonth'>
					<tr class="days">
						<th>Sunday</th>
						<th>Monday</th>
						<th>Tuesday</th>
						<th>Wednesday</th>
						<th>Thursday</th>
						<th>Friday</th>
						<th>Saturday</th>
					</tr>
					<tr>
<?	$weekDayInt = 0;
				$intMonthDay = 0;
	while($firstDisplayDay != 1) { ?>
						<td class='diffmonth'><div class='dom'><?=($daysLastMonth + $firstDisplayDay)?></div></td>
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
			$dThis = $intYear.'-'.$intMonth.'-'.($intMonthDay < 10 ? '0'.$intMonthDay : $intMonthDay);
?>
						<td class='daybox'>
							<div style='text-align:right;'>
								<?=linkto(array('address'=>'calendar/day.php?dBegin='.$intYear.$intMonth.($intMonthDay < 10 ? '0'.$intMonthDay : $intMonthDay),'display'=>$intMonthDay))?>
								
							</div>
<? 	
		if(isset($events[$dThis]) ) {
?>
							<table width='100%' cellpadding='0' cellspacing='0' border='0' style='table-layout: fixed;'>
							<col width=7><col width=100%><col width=7>
<?
			foreach ($events[0][(date('w',strtotime($dThis))==0 ? date('W',strtotime($dThis.' +1 day')) : date('W',strtotime($dThis)))] AS $k => $v) {
				$max = $k;
			}
			$cnt=1;
			while($cnt <= $max) {	
				if(isset($events[0][(date('w',strtotime($dThis))==0 ? date('W',strtotime($dThis.' +1 day')) : date('W',strtotime($dThis)))][$cnt])) {
?>
								<tr>
									<td style='height:3px;' colspan='3'><!-- Blank --></td>
								</tr>
<?
					if(isset($events[$dThis][$cnt])) {
						$distitle = 1;
						//Check to see if caps are needed
						$leftCap = $rightCap = 0;
						$leftCSS = $rightCSS =  ' url('.$BF.'calendar/images/calendar-cap-middle.png) repeat-x';
						if(!isset($events[date('Y-m-d',strtotime($dThis.' 12:00:00.0')-86400)][$cnt])) {
							$leftCap = 1;
						}
						if(!isset($events[date('Y-m-d',strtotime($dThis.' 12:00:00.0')+86400)][$cnt])) {
							$rightCap = 1;
						}
						if(isset($events[$dThis][$cnt]['dDate2'])) {
							if($events[$dThis][$cnt]['dDate2'] == $dThis && $events[$dThis][$cnt]['dDate2'] != '') { $leftCap = 1;
							} else if($events[$dThis][$cnt]['dDate3'] == $dThis && $events[$dThis][$cnt]['dDate3'] != '') { $leftCap = 1;
							} else if($events[$dThis][$cnt]['dDate4'] == $dThis && $events[$dThis][$cnt]['dDate4'] != '') { $leftCap = 1; 
							}
							$dNext = date('Y-m-d',strtotime($dThis.' 12:00:00.0')+86400);
							if($events[$dThis][$cnt]['dDate2'] == $dNext && $events[$dThis][$cnt]['dDate2'] != '') { $rightCap = 1;
							} else if($events[$dThis][$cnt]['dDate3'] == $dNext && $events[$dThis][$cnt]['dDate3'] != '') { $rightCap = 1;
							} else if($events[$dThis][$cnt]['dDate4'] == $dNext && $events[$dThis][$cnt]['dDate4'] != '') { $rightCap = 1; 
							}
							if($dThis >= $events[$dThis][$cnt]['dDate2'] && $events[$dThis][$cnt]['dDate2'] != '') { $distitle = 2;
							}
							if($dThis >= $events[$dThis][$cnt]['dDate3']  && $events[$dThis][$cnt]['dDate3'] != '') { $distitle = 3;
							}
							if($dThis >= $events[$dThis][$cnt]['dDate4']  && $events[$dThis][$cnt]['dDate4'] != '') { $distitle = 4;
							}
						}

							
						
						
						
?>
								<tr>
									<td style='cursor:pointer;background:<?=$events[$dThis][$cnt]['chrColorBG']?><?=$leftCSS?>;' onclick="location.href='<?=$BF.$link?>?key=<?=$events[$dThis][$cnt]['chrNSOKey']?>';" title='<?=$events[$dThis][$cnt][(isset($distitle)? $distitle : 1)]?>'><?=($leftCap?"<img src='".$BF."calendar/images/calendar-cap-left.png' alt='left cap' />":"<!-- BLANK -->")?></td>
									<td style='cursor:pointer;height:17px;vertical-align:middle;background:<?=$events[$dThis][$cnt]['chrColorBG']?> url(<?=$BF?>calendar/images/calendar-cap-middle.png) repeat-x;' onclick="location.href='<?=$BF.$link?>?key=<?=$events[$dThis][$cnt]['chrNSOKey']?>';" title='<?=$events[$dThis][$cnt][(isset($distitle)? $distitle : 1)]?>'>
<?								
								$cw = date('w',strtotime($dThis))==0 ? date('W',strtotime($dThis.' +1 day')) : date('W',strtotime($dThis));
								if(!$events[0][$cw][$cnt]['displayed']) {
									$events[0][$cw][$cnt]['displayed'] = true;
									
									$ebd = $events[date('Y-m-d',strtotime($dThis))][$cnt]['dDateBegin'];
									$eed = $events[date('Y-m-d',strtotime($dThis))][$cnt]['dDateEnd'];
									$ebw = date('w',strtotime($ebd))==0 ? date('W',strtotime($ebd.' +1 day')) : date('W',strtotime($ebd));
									$eew = date('w',strtotime($eed))==0 ? date('W',strtotime($eed.' +1 day')) : date('W',strtotime($eed));
									
									if($ebw == $eew) {  // Event begins and ends on same week
										if (ceil((strtotime($intYear.'-'.$intMonth.'-'.$daysThisMonth) - strtotime($dThis)) / (60 * 60 * 24) +1) < 7) {
											$days = ceil((strtotime($intYear.'-'.$intMonth.'-'.$daysThisMonth) - strtotime($dThis)) / (60 * 60 * 24) +1);
											$maxtextlength = 15 * $days;
										} else {
											$days = (strtotime($eed) - strtotime($ebd)) / (60 * 60 * 24) +1;
											$maxtextlength = 15 * $days;
										}
										//echo 1;
									} else if ($cw == $eew) { // Event ends on the current week
										$days = ceil((strtotime($eed) - strtotime($dThis)) / (60 * 60 * 24) +1);
										$maxtextlength = 15 * $days;
										//echo 2; 
									} else if (ceil((strtotime($intYear.'-'.$intMonth.'-'.$daysThisMonth) - strtotime($dThis)) / (60 * 60 * 24) +1) < 7) {
										$days = ceil((strtotime($intYear.'-'.$intMonth.'-'.$daysThisMonth) - strtotime($dThis)) / (60 * 60 * 24) +1);
										$maxtextlength = 15 * $days;
										//echo 3;
									} else { // Event doesn't end until future week
										$maxtextlength = 105 - ((date('w',strtotime($dThis))) * 15);
										//echo 4;
									}
?>
										<?=linkto(array('address'=>$link.'?key='.$events[$dThis][$cnt]['chrNSOKey'],'title'=>$events[$dThis][$cnt][(isset($distitle)? $distitle : 1)],'display'=>(strlen($events[$dThis][$cnt]['chrCalendarEvent']) > $maxtextlength ? substr($events[$dThis][$cnt]['chrCalendarEvent'],0,$maxtextlength).'..' : $events[$dThis][$cnt]['chrCalendarEvent']),'style'=>'margin-left:-3px;white-space:nowrap;overflow:hidden;color:'.$events[$dThis][$cnt]['chrColorText'].';'))?>
<?					
								} else {
?>
										&nbsp;
<?					
								}
?>
									</td>
									<td style='cursor:pointer; background:<?=$events[$dThis][$cnt]['chrColorBG']?><?=$rightCSS?>;' onclick="location.href='<?=$BF.$link?>?key=<?=$events[$dThis][$cnt]['chrNSOKey']?>';" title='<?=$events[$dThis][$cnt][(isset($distitle)? $distitle : 1)]?>'><?=($rightCap?"<img src='".$BF."calendar/images/calendar-cap-right.png' alt='right cap' />":"<!-- BLANK -->")?></td>
								</tr>
<?			
					} else {
?>
								<tr>
									<td style='height:17px;' colspan='3'>&nbsp;</td>
								</tr>
<?			
					}
				}
				$cnt++;

			}
?>
								<tr>
									<td style='height:3px;' colspan='3'><!-- Blank --></td>
								</tr>
							</table>
						</td>
<?
		}
	}
			
	$extraCnt = 1;
	while($weekDayInt++ < 7) { ?>
						<td class='diffmonth'><div class='dom'><?=$extraCnt++?></div></td>
<?	} ?>
						</td>
					</tr>
				</table>
<?
	}
?>