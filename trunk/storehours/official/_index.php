<?
	include_once($BF.'storehours/components/add_functions.php');
	$table = 'StoreHours'; # added so not to forget to change the insert AND audit
	$tmp = db_query("DELETE FROM ".$table." WHERE idStore=".$_SESSION['idStore'],"Removing any records for this store");
	$q = "INSERT INTO ". $table ." (chrKEY,idStore,idDayOfWeek,dtCreated,tOpening,tClosing,bClosed) VALUES ";
	$dow = 0;
	while($dow < 7) {
		$q .= "('".makekey()."','". $_SESSION['idStore'] ."','".$dow."',now(),'".date('H:i:s',strtotime($_POST['tOpening'.$dow]))."','".date('H:i:s',strtotime($_POST['tClosing'.$dow]))."','". (isset($_POST['bClosed'.$dow]) ? '1':'0') ."'),";
		$dow++;
	}
	
	# if there database insertion is successful	
	if(db_query(substr($q,0,-1),"Insert into ". $table)) {
		$_SESSION['infoMessages'][] = "Store Hours have been successfully updated.";
		header("Location: ". $BF ."storehours/");
		die();
	} else {
		# if the database insertion failed, send them to the error page with a useful message
		errorPage('An error has occurred while trying to update these Store Hours.');
	}
?>