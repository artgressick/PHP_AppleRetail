<?php
		if($_POST['bDeleteAll'] == "on") {
			db_query("UPDATE CalendarEvents SET bDeleted=1 WHERE chrSeries='". $info['chrSeries'] ."'","mass delete");
		} else {
			db_query("UPDATE CalendarEvents SET bDeleted=1 WHERE chrKEY='". $_POST['key'] ."'","delete single");
		}
			
		header("Location: ".$BF."calendar/". $_SESSION['calSection'] .".php?dDate=". $_SESSION['calDate']);
		die();
?>