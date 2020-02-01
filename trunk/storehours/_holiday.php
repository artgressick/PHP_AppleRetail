<?
	include_once($BF.'storehours/components/add_functions.php');
	$table = 'StoreHoursSpecial'; # added so not to forget to change the insert AND audit

	db_query("DELETE FROM ". $table ." WHERE idHoliday=". $_POST['idHoliday'] ." AND idStore=".$_SESSION['idStore'],"deleting old events");	

	$q = "INSERT INTO ". $table ." (chrKEY,idHoliday,idStore,bClosed,idDayOfWeek,dDate,tOpening,tClosing,dtCreated) VALUES ";
	$i = 0;
	while($i <= $_POST['totalDays']) {
		$q .= " ('". makekey() ."','". $_POST['idHoliday'] ."','". $_SESSION['idStore'] ."','". ($_POST['bClosed'.$i]=='on'?1:0) ."','". $_POST['idDayOfWeek'.$i] ."','". $_POST['dDate'.$i] ."','". date('H:i',strtotime($_POST['tOpening'.$i])) .":00.0','". date('H:i',strtotime($_POST['tClosing'.$i])) .":00.0',now()),";
		$i++;
	}
	
	# if there database insertion is successful	
	if(db_query(substr($q,0,-1),"Insert into ". $table)) {
	
		$_SESSION['infoMessages'][] = "Holiday Hours for ".$_POST['chrHoliday']." has been updated successfully.";
		header("Location: ". $BF ."storehours/");
		die();
	} else {
		//if the database insertion failed, send them to the error page with a useful message
		errorPage('An error has occurred while trying to add this Holiday.');
	}
	
?>