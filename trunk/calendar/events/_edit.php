<?php

		include_once($BF.'calendar/components/edit_functions.php');
		$table = 'CalendarEvents';
		$mysqlStr = '';
		$audit = '';

		// "List" is a way for php to split up an array that is coming back.  
		// "set_strs" is a function (bottom of the _lib) that is set up to look at the old information in the DB, and compare it with
		//    the new information in the form fields.  If the information is DIFFERENT, only then add it to the mysql string to update.
		//    This will ensure that only information that NEEDS to be updated, is updated.  This means smaller and faster DB calls.
		//    ...  This also will ONLY add changes to the audit table if the values are different.
		list($mysqlStr,$audit) = set_strs($mysqlStr,'chrCalendarEvent',$info['chrCalendarEvent'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs_date($mysqlStr,'dBegin',$info['dBegin'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs_time($mysqlStr,'tBegin',$info['tBegin'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs_time($mysqlStr,'tEnd',$info['tEnd'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'idCalendarType',$info['idCalendarType'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'txtContent',$info['txtContent'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs_checkbox($mysqlStr,'bAllDay',$info['bAllDay'],$audit,$table,$_POST['id']);

		// if nothing has changed, don't do anything.  Otherwise update / audit.
		if($mysqlStr != '') { 
			$_POST['dtModified'] = date('Y-m-d H:i:s');
			list($mysqlStr,$audit) = set_strs_datetime($mysqlStr,'dtModified',$info['dtModified'],$audit,$table,$info['ID']);
			$_SESSION['infoMessages'][] = $_POST['chrCalendarEvent']." has been successfully updated in the Database.";
			list($str,$aud) = update_record($mysqlStr, $audit, $table, $info['ID']);
	 	} else {
	 		$_SESSION['infoMessages'][] = "No Changes have been made to ".$_POST['chrCalendarEvent'];
		}

		/*
		if($info['chrReoccur'] != "" && $_POST['bReoccur'] != "on") {
			database_query("DELETE FROM CalendarEvents 
				WHERE dBegin > '". $info['dBegin'] ."' AND chrSeries='". $info['chrSeries'] ."'
			","delete all but first");
			database_query("UPDATE CalendarEvents SET chrSeries='',chrReoccur='' WHERE chrSeries='". $info['chrSeries'] ."'","update");
		} else if($info['dEnd'] != date('Y-m-d',strtotime($_POST['dRepeatEnd']))) {
		
			if($info['dEnd'] > date('Y-m-d',strtotime($_POST['dRepeatEnd']))) {
				database_query("DELETE FROM CalendarEvents 
					WHERE dBegin > '". date('Y-m-d',strtotime($_POST['dRepeatEnd'])) ."' AND chrSeries='". $info['chrSeries'] ."'
				","delete all but first");
			} else if($info['dEnd'] < date('Y-m-d',strtotime($_POST['dRepeatEnd']))) {

				$day = fetch_database_query("SELECT MAX(dBegin) as dBegin WHERE chrSeries='". $info['chrSeries'] ."' AND dBegin <= '". date('Y-m-d',strtotime($_POST['dRepeatEnd'])) ."'","getting max day");

				$lday = strtotime(date('Y-m-d',strtotime($_POST['dRepeatEnd'])));
				$fday = strtotime($day['dBegin']);
			
				while($fday <= $lday) {
					$q = "INSERT INTO CalendarEvents SET
							bAllDay='". ($_POST['bAllDay'] == "on" ? 1 : 0) ."',
							chrCalendarEvent='" . encode($_POST['chrCalendarEvent']) . "',
							dBegin='" . date('Y-m-d',$fday) . "',
							dEnd='" . date('Y-m-d',$lday) . "',
							tBegin='" . date('H:i:s',strtotime($_POST['tBegin'])) . "',
							tEnd='" . date('H:i:s',strtotime($_POST['tEnd'])) . "',
							idCalendarType='". $_POST['idCalendarType'] ."',
							txtContent='" . encode($_POST['txtContent']) . "',
							chrReoccur='" . $_POST['chrReoccur'] . "',
							chrSeries='". $series ."',
							dtCreated=now(),
							chrKey='". makekey() ."',
							idUser='". $_SESSION['idUser'] ."'
						";		
					database_query($q, "insert calendar event");
					echo $fday = date('Y-m-d',$fday);
					$fday = strtotime($fday ." + 1 ". $timeframe);
				}

			}

		}
		*/

		header("Location: ".$BF."calendar/". $_SESSION['calSection'] .".php?dBegin=". $_SESSION['calDate']);
		die();



?>