<?php
	include('_controller.php');
	
	function sitm() { 
		global $BF,$fdow,$ldow;

		$_SESSION['calSection'] = 'week';
		$_SESSION['calDate'] = @$_REQUEST['dBegin'];

		if(isset($_SESSION['CalendarType']) && $_SESSION['CalendarType'] == 'travel') {
		
			$q = "SELECT NSOTravelPlans.dBegin,NSOTravelPlans.dEnd,NSOTravelPlans.chrKEY,CONCAT(chrFirst,' ',chrLast,' - ',chrShortTitle) as chrDisplay, CA.chrColorText, CA.chrColorBG
					FROM NSOTravelPlans
					JOIN Users ON Users.ID=NSOTravelPlans.idUser
					JOIN CalendarTypes ON CalendarTypes.ID=3
					JOIN CalendarAccess AS CA ON CA.idUser=Users.ID
					WHERE !NSOTravelPlans.bDeleted AND CA.bTravelAccess AND 
					  ((NSOTravelPlans.dBegin BETWEEN '".$fdow."' AND '".$ldow."') OR (NSOTravelPlans.dEnd BETWEEN '".$fdow."' AND '".$ldow."') OR ('".$fdow."' BETWEEN NSOTravelPlans.dBegin AND NSOTravelPlans.dEnd))
					". ($_SESSION['idTravelUsers'] != "" ? " AND Users.ID IN (". $_SESSION['idTravelUsers'] .") " : '') ."  
					ORDER BY dBegin,dEnd
				";
			$link = 'calendar/viewtravel.php';
		
		} else {
			
			$q = "SELECT NSOs.ID,NSOs.chrKEY,CONCAT(RetailStores.chrName,' / ',RetailStores.chrStoreNum) AS chrDisplay,NSOs.chrCalendarEvent,NSOs.dBegin,NSOs.dDate2,NSOs.dDate3,NSOs.dDate4,NSOs.dEnd,CalendarTypes.chrColorText,CalendarTypes.chrColorBG,NSOTypes.chrColorText AS chrNSOColorText,NSOTypes.chrColorBG AS chrNSOColorBG
					FROM NSOs
					JOIN NSOTypes ON NSOTypes.ID=NSOs.idNSOType
					JOIN CalendarTypes ON CalendarTypes.ID=NSOTypes.idNSOCategory
					JOIN NSOUserTitleAssoc AS UTA ON UTA.idNSO=NSOs.ID AND UTA.idUserTitle=2
					JOIN Users ON Users.ID=UTA.idUser
					JOIN RetailStores ON NSOs.idStore=RetailStores.ID 
					WHERE ".($_SESSION['bGlobal'] ? "":"NSOs.bShow AND ")."!NSOs.bDeleted AND
					  ((NSOs.dBegin BETWEEN '".$fdow."' AND '".$ldow."') OR (NSOs.dEnd BETWEEN '".$fdow."' AND '".$ldow."') OR ('".$fdow."' BETWEEN NSOs.dBegin AND NSOs.dEnd))
				  ". ($_SESSION['idCalTypes'] != "" ? " AND NSOTypes.ID IN (". $_SESSION['idCalTypes'] .") " : '') ."
				  ". ($_SESSION['idCalUsers'] != "" ? " AND Users.ID IN (". $_SESSION['idCalUsers'] .") " : '') ."
				  ". ($_SESSION['chrCalRegions'] != "" ? " AND (RetailStores.chrRegion IN (". $_SESSION['chrCalRegions'] .") " : '') ."
				  ". (preg_match("/(^|,)'US'(,|$)/",$_SESSION['chrCalRegions']) ? " OR RetailStores.chrCountry='US') " : ($_SESSION['chrCalRegions'] != "" ? ') ' : '')) ."
					ORDER BY dBegin,chrDisplay
				";
			$link = 'calendar/nso/landing.php';
		}		
		
		$event_results = db_query($q,"Getting Calendar Results");
?>

	<table cellspacing="0" cellpadding="0" style='width:100%;table-layout: fixed;' class='longviews'>
	<col width=6><col width=100%><col width=6><col width=6><col width=100%><col width=6><col width=6><col width=100%><col width=6><col width=6><col width=100%><col width=6><col width=6><col width=100%><col width=6><col width=6><col width=100%><col width=6><col width=6><col width=100%><col width=6>
		<tr>
			<td><!-- BLANK --></td><td><!-- BLANK --></td><td><!-- BLANK --></td>
			<td><!-- BLANK --></td><td><!-- BLANK --></td><td><!-- BLANK --></td>
			<td><!-- BLANK --></td><td><!-- BLANK --></td><td><!-- BLANK --></td>
			<td><!-- BLANK --></td><td><!-- BLANK --></td><td><!-- BLANK --></td>
			<td><!-- BLANK --></td><td><!-- BLANK --></td><td><!-- BLANK --></td>
			<td><!-- BLANK --></td><td><!-- BLANK --></td><td><!-- BLANK --></td>
			<td><!-- BLANK --></td><td><!-- BLANK --></td><td><!-- BLANK --></td>
		</tr>
 		<tr style=''>
<?
/*<td colspan='3' style='padding: 5px; width: 105px; text-align: center;  border: 1px solid black; background: #eee;'><a href='day.php?dBegin=<?=date('Ymd',strtotime($fdow))?>'><?=date('D, M jS',strtotime($fdow))?></a></td> */		
		$intthis = strtotime($fdow.' 12:00:00.0');
		$cnt=0;
		while($intthis <= strtotime($ldow.' 12:00:00.0')) {
?>
			<td colspan='3' style='padding: 5px 0px; text-align: center; border: 1px solid black; <?=($cnt++ > 0 ? 'border-left: 0;':'')?> background: #eee;'>
				<a href='day.php?dBegin=<?=date('Ymd',$intthis)?>'><?=date('D, M jS',$intthis)?></a>
			</td>

<?
			$intthis = $intthis + 86400;
		}
?>
		</tr>
		<tr>
			<td style='height:3px;' colspan='21'><!-- Blank --></td>
		</tr>
	
<?
		$capleft = "<img src='".$BF."calendar/images/calendar-cap-left.png' alt='left cap' style='float: left;' />"; 		
		$capright = "<img src='".$BF."calendar/images/calendar-cap-right.png' alt='right cap' />";
		while($row = mysqli_fetch_assoc($event_results)) {
			$fulldisplay = $row['chrDisplay'];
			if(isset($row['chrNSOColorText']) && $row['chrNSOColorText'] != '') { $row['chrColorText'] = $row['chrNSOColorText']; }
			if(isset($row['chrNSOColorBG']) && $row['chrNSOColorBG'] != '') { $row['chrColorBG'] = $row['chrNSOColorBG']; }
			$tdstyle='white-space:nowrap; background:'.$row['chrColorBG'].' url('.$BF.'calendar/images/calendar-cap-middle.png) repeat-x; height: 17px; vertical-align:middle;cursor:pointer;color:'.$row['chrColorText'].';';
			$tdonclick='location.href="'.$BF.$link.'?key='.$row['chrKEY'].'";';
?>
			<tr>
<?
			$intthis = strtotime($fdow.' 12:00:00.0');
			$display = false;
			$fulltitle = $fulldisplay;
			while($intthis <= strtotime($ldow.' 12:00:00.0')) {
				$dThis = date('Y-m-d',$intthis);
				$nextThis = date('Y-m-d',$intthis + 86400);
				$showleft = false; $showright = false;
				if($row['dBegin'] == $dThis) { $showleft = true; }
				if($row['dEnd'] == $dThis) { $showright = true; }
				if(isset($row['dDate2'])) {

					if($row['dDate2'] == $dThis) { $fulltitle = $fulldisplay.' - SWS Opens'; $showleft = true;
					} else if ($row['dDate3'] == $dThis) { $fulltitle = $fulldisplay.' - Last Day SWS Open'; $showleft = true;
					} else if ($row['dDate4'] == $dThis) { $fulltitle = $fulldisplay.' - Store Set Up'; $showleft = true; 
					}
					
					if($row['dDate2'] == $nextThis) { $fulltitle = $fulldisplay; $showright = true;
					} else if($row['dDate3'] == $nextThis) { $fulltitle = $fulldisplay; $showright = true;
					} else if($row['dDate4'] == $nextThis) { $fulltitle = $fulldisplay; $showright = true;
					}
				}
				if($dThis >= $row['dBegin'] && $dThis <= $row['dEnd']) {
					if(!$display) {
						if($ldow <= $row['dEnd']) {
							$end = strtotime($ldow.' 12:00:00.0');
						} else {
							$end = strtotime($row['dEnd'].' 12:00:00.0');
						}
						$days = (($end - $intthis) / 86400) + 1;
						
						if(strlen($row['chrDisplay']) > (15 * $days)) { $row['chrDisplay'] = substr($row['chrDisplay'],0,(15 * $days)).'..'; }
						$display = true;
					} else {
						$row['chrDisplay'] = '&nbsp;';
					}
?>
				<td style='<?=$tdstyle?>' onclick='<?=$tdonclick?>' title='<?=$fulltitle?>'><?=($showleft ? $capleft : '&nbsp;')?></td>
				<td style='<?=$tdstyle?>' onclick='<?=$tdonclick?>' title='<?=$fulltitle?>'><?=$row['chrDisplay']?></td>
				<td style='<?=$tdstyle?>' onclick='<?=$tdonclick?>' title='<?=$fulltitle?>'><?=($showright ? $capright : '&nbsp;')?></td>
<?	
				} else {
?>
				<td colspan='3'>&nbsp;</td>
<?
				}
				$intthis = $intthis + 86400;
			}
?>
			</tr>
			<tr>
				<td style='height:3px;' colspan='21'><!-- Blank --></td>
			</tr>
<?
		}
?>
	</table>
<?


	}
?>