<?php
	include('_controller.php');
	
	function sitm() { 
		global $BF;

			$_SESSION['calSection'] = 'day';
			$_SESSION['calDate'] = @$_REQUEST['dBegin'];
		
			# Setting up initial variables to be used
			list($intDate,$intMonth,$intYear,$firstDisplayDay,$daysThisMonth,$daysLastMonth) = get_dates($_REQUEST['dBegin']);
			
			$_REQUEST['dBegin'] = date('Y-m-d',strtotime($_REQUEST['dBegin']));
			
			
		if(isset($_SESSION['CalendarType']) && $_SESSION['CalendarType'] == 'travel') {
		
			$q = "SELECT NSOTravelPlans.dBegin,NSOTravelPlans.dEnd,NSOTravelPlans.chrKEY,CONCAT(chrFirst,' ',chrLast,' - ',chrShortTitle) as chrDisplay, CA.chrColorText, CA.chrColorBG
					FROM NSOTravelPlans
					JOIN Users ON Users.ID=NSOTravelPlans.idUser
					JOIN CalendarTypes ON CalendarTypes.ID=3
					JOIN CalendarAccess AS CA ON CA.idUser=Users.ID
					WHERE !NSOTravelPlans.bDeleted AND CA.bTravelAccess AND 
					  ((NSOTravelPlans.dBegin = '".$_REQUEST['dBegin']."') OR (NSOTravelPlans.dEnd = '".$_REQUEST['dBegin']."') OR ('".$_REQUEST['dBegin']."' BETWEEN NSOTravelPlans.dBegin AND NSOTravelPlans.dEnd))
					". ($_SESSION['idTravelUsers'] != "" ? " AND Users.ID IN (". $_SESSION['idTravelUsers'] .") " : '') ."  
					ORDER BY dBegin
				";
			$link = 'calendar/viewtravel.php';
		
		} else {
			
			$q = "SELECT NSOs.ID,NSOs.chrKEY,CONCAT(RetailStores.chrName,' / ',RetailStores.chrStoreNum) AS chrDisplay,NSOs.chrCalendarEvent,NSOs.dBegin,NSOs.dEnd,CalendarTypes.chrColorText,
					CalendarTypes.chrColorBG,NSOTypes.chrColorText AS chrNSOColorText,NSOTypes.chrColorBG AS chrNSOColorBG,NSOs.dDate2,NSOs.dDate3,NSOs.dDate4
					FROM NSOs
					JOIN NSOTypes ON NSOTypes.ID=NSOs.idNSOType
					JOIN CalendarTypes ON CalendarTypes.ID=NSOTypes.idNSOCategory
					JOIN NSOUserTitleAssoc AS UTA ON UTA.idNSO=NSOs.ID AND UTA.idUserTitle=2
					JOIN Users ON Users.ID=UTA.idUser
					JOIN RetailStores ON NSOs.idStore=RetailStores.ID 
					WHERE ".($_SESSION['bGlobal'] ? "":"NSOs.bShow AND ")."!NSOs.bDeleted AND
					  ((NSOs.dBegin = '". $_REQUEST['dBegin'] ."') OR (NSOs.dEnd = '". $_REQUEST['dBegin'] ."') OR ('". $_REQUEST['dBegin'] ."' BETWEEN NSOs.dBegin AND NSOs.dEnd))
				  ". ($_SESSION['idCalTypes'] != "" ? " AND NSOTypes.ID IN (". $_SESSION['idCalTypes'] .") " : '') ."
		  		  ". ($_SESSION['idCalUsers'] != "" ? " AND Users.ID IN (". $_SESSION['idCalUsers'] .") " : '') ."
		  		  ". ($_SESSION['chrCalRegions'] != "" ? " AND (RetailStores.chrRegion IN (". $_SESSION['chrCalRegions'] .") " : '') ."
		  		  ". (preg_match("/(^|,)'US'(,|$)/",$_SESSION['chrCalRegions']) ? " OR RetailStores.chrCountry='US') " : ($_SESSION['chrCalRegions'] != "" ? ') ' : '')) ."
					ORDER BY dBegin,chrDisplay
				";
			$link = 'calendar/nso/landing.php';
		}		
		$results = db_query($q,"Getting Calendar Results");
			

		# Used later to find the total number of concurrent events

?>
	<table cellspacing="0" cellpadding="0" style='width: 100%; table-layout: fixed;' class='longviews'>
	<col width=7><col width=100%><col width=7>
<?
		$intDow = strtotime($_REQUEST['dBegin']);
		$dThis = date('Y-m-d',$intDow);
		$dNext = date('Y-m-d', $intDow + 86400);
		$capright = "<img src='".$BF."calendar/images/calendar-cap-right.png' alt='right cap' />";
		$capleft = "<img src='".$BF."calendar/images/calendar-cap-left.png' alt='left cap' style='float: left;' />";
		while($row = mysqli_fetch_assoc($results)) {
			$fulldisplay = $row['chrDisplay'];
			$fulltitle = $fulldisplay;
			$showright = false; $showleft = false;		
			if(isset($row['chrNSOColorText']) && $row['chrNSOColorText'] != '') { $row['chrColorText'] = $row['chrNSOColorText']; }
			if(isset($row['chrNSOColorBG']) && $row['chrNSOColorBG'] != '') { $row['chrColorBG'] = $row['chrNSOColorBG']; }
			$tmpBegin = strtotime($row['dBegin']);
			$tmpEnd = strtotime($row['dEnd']);
			if(isset($row['dDate2'])) {
				if($row['dDate2'] == $dNext) { $fulltitle = $fulldisplay; $showright = true;
				} else if ($row['dDate3'] == $dNext) { $fulltitle = $fulldisplay; $showright = true;
				} else if ($row['dDate4'] == $dNext) { $fulltitle = $fulldisplay; $showright = true; 
				}

				if($row['dDate2'] == $dThis) { $fulltitle = $fulldisplay.' - SWS Opens'; $showleft = true;
				} else if ($row['dDate3'] == $dThis) { $fulltitle = $fulldisplay.' - Last Day SWS Open'; $showleft = true;
				} else if ($row['dDate4'] == $dThis) { $fulltitle = $fulldisplay.' - Store Set Up'; $showleft = true; 
				}
			}
			
			if($intDow == $tmpEnd) {
				$showright = true;
			}
			if($intDow == $tmpBegin) {
				$showleft = true;
			}
			if(strlen($row['chrDisplay']) > 105) { $row['chrDisplay'] = substr($row['chrDisplay'],0,105).'..'; }
			
			$tdstyle='background:'.$row['chrColorBG'].' url('.$BF.'calendar/images/calendar-cap-middle.png) repeat-x; height: 17px; vertical-align:middle;cursor:pointer;color:'.$row['chrColorText'].';';
			$tdonclick='location.href="'.$BF.$link.'?key='.$row['chrKEY'].'";';
?>			
			<tr>
				<td style='<?=$tdstyle?>' onclick='<?=$tdonclick?>' title='<?=$fulltitle?>'><?=($showleft ? $capleft : '&nbsp;')?></td>
				<td style='<?=$tdstyle?>' onclick='<?=$tdonclick?>' title='<?=$fulltitle?>'><?=$row['chrDisplay']?></td>
				<td style='<?=$tdstyle?>' onclick='<?=$tdonclick?>' title='<?=$fulltitle?>'><?=($showright ? $capright : '&nbsp;')?></td>
			</tr>
			<tr>
				<td style='height:3px;' colspan='3'><!-- Blank --></td>
			</tr>
						
			
<?			
		} 
?>

	</table>


<?	} ?>