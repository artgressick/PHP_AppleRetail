<?
	$_SESSION['infoMessages'] = array();

		if(db_query("UPDATE NSONotifications SET bDefault=0","Deleting all NSONotifications")) {
			$deletesuccess=true;
		} else {
			$deletesuccess=false;
		}
	
		$q2 = implode(',',$_POST['nsonotifdefault']);
		
		$savesuccess=true;
		
		if ($q2 != "") {
			
			$q = "UPDATE NSONotifications SET bDefault=1 WHERE ID IN (".$q2.")";
			
			if(!db_query($q,"update defaults")) {
				$savesuccess=false;
			}
		} 

		if($deletesuccess && $savesuccess) {
			$_SESSION['infoMessages'][] = "All changes have been saved successfully.";
		
			header("Location: index.php");
			die();
			
		} else {
			errorPage('An error has occured while trying to save the changes.');
		}

?>