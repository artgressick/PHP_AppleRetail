<?php
	include_once($BF.'calendar/components/add_functions.php');
	$table = 'CalendarEvents'; # added so not to forget to change the insert AND audit
	if(($_POST['dBegin'] == $_POST['dEnd']) && $_POST['bReoccur'] != "on") {
		$q = "INSERT INTO ". $table ." SET
				bAllDay='". ($_POST['bAllDay'] == "on" ? 1 : 0) ."',
				chrCalendarEvent='" . encode($_POST['chrCalendarEvent']) . "',
				dBegin='" . date('Y-m-d',strtotime($_POST['dBegin'])) . "',
				dEnd='" . date('Y-m-d',strtotime($_POST['dBegin']. " + 1 day")) . "',
				tBegin='" . ($_POST['tBegin'] != "" ? date('H:i:s',strtotime($_POST['tBegin'])) : '') . "',
				tEnd='" . ($_POST['tEnd'] != "" ? date('H:i:s',strtotime($_POST['tEnd'])) : '') . "',
				idCalendarType='". $_POST['idCalendarType'] ."',
				txtContent='" . encode($_POST['txtContent']) . "',
				dtCreated=now(),
				chrKey='". makekey() ."',
				idUser='". $_SESSION['idUser'] ."'
			";		
		if (db_query($q, "insert calendar event")) {
			$_SESSION['infoMessages'][] = "Calendar Event: ".$_POST['chrCalendarEvent']." has been added successfully.";
		} else {
			errorPage('An error has occurred while trying to add this Calendar Event.');
		}
			
	} else if(($_POST['dBegin'] != $_POST['dEnd']) && $_POST['bReoccur'] != "on") {
		$q = "INSERT INTO ". $table ." SET
				bAllDay='". ($_POST['bAllDay'] == "on" ? 1 : 0) ."',
				chrCalendarEvent='" . encode($_POST['chrCalendarEvent']) . "',
				dBegin='" . date('Y-m-d',strtotime($_POST['dBegin'])) . "',
				dEnd='" . date('Y-m-d',strtotime($_POST['dEnd'])) . "',
				tBegin='" . ($_POST['tBegin'] != "" ? date('H:i:s',strtotime($_POST['tBegin'])) : '') . "',
				tEnd='" . ($_POST['tEnd'] != "" ? date('H:i:s',strtotime($_POST['tEnd'])) : '') . "',
				idCalendarType='". $_POST['idCalendarType'] ."',
				txtContent='" . encode($_POST['txtContent']) . "',
				dtCreated=now(),
				chrKey='". makekey() ."',
				idUser='". $_SESSION['idUser'] ."'
			";		
		if (db_query($q, "insert calendar event")) {
			$_SESSION['infoMessages'][] = "Calendar Event: ".$_POST['chrCalendarEvent']." has been added successfully.";
		} else {
			errorPage('An error has occurred while trying to add this Calendar Event.');
		}
	} else {
		# make sure the begin date is first... 
		if(strtotime($_POST['dBegin']) < strtotime($_POST['dRepeatEnd'])) {
			$fday = strtotime($_POST['dBegin']);
			$lday = strtotime($_POST['dRepeatEnd']);
		} else {
			$lday = strtotime($_POST['dBegin']);
			$fday = strtotime($_POST['dRepeatEnd']);
		}
		$timeframe = $_POST['chrReoccur'];
		
		$series = makekey();
		$q2 = "";
		while($fday <= $lday) {
			$q2 .= "('". (isset($_POST['bAllDay']) && $_POST['bAllDay'] == "on" ? 1 : 0) ."','" . encode($_POST['chrCalendarEvent']) . "','" . date('Y-m-d',$fday) . "','" . date('Y-m-d',$fday) . "','" . ($_POST['tBegin'] != "" ? date('H:i:s',strtotime($_POST['tBegin'])) : '') . "','" . ($_POST['tEnd'] != "" ? date('H:i:s',strtotime($_POST['tEnd'])) : '') . "','". $_POST['idCalendarType'] ."','" . encode($_POST['txtContent']) . "','" . $_POST['chrReoccur'] . "','". $series ."',now(),'". makekey() ."','". $_SESSION['idUser'] ."'),";
			echo $fday = date('Y-m-d',$fday);
			$fday = strtotime($fday ." + 1 ". $timeframe);
		}
		$q2 = substr($q2, 0,-1);
		$q = "INSERT INTO ".$table." (bAllDay,chrCalendarEvent,dBegin,dEnd,tBegin,tEnd,idCalendarType,txtContent,chrReoccur,chrSeries,dtCreated,chrKey,idUser) VALUES ".$q2;
		
		if (db_query($q, "insert calendar events")) {
			$_SESSION['infoMessages'][] = "Calendar Event: ".$_POST['chrCalendarEvent']." has been added successfully.";
		} else {
			errorPage('An error has occurred while trying to add this Calendar Event.');
		}
	}

	header("Location: ".$BF."calendar/". $_SESSION['calSection'] .".php?dBegin=". $_SESSION['calDate']);
	die();
?>