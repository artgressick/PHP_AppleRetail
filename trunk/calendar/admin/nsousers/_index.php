<?
	$_SESSION['infoMessages'] = array();

		if(db_query("TRUNCATE NSOUsers","Deleting all NSOs")) {
			$deletesuccess=true;
		} else {
			$deletesuccess=false;
		}
		
		
		$q2 = "";
		
		if(isset($_POST['nsoinvolved'])) {
			foreach ($_POST['nsoinvolved'] as $id) {
				if($q2 != "") { $q2 .= ","; }
				
				$q2 .= "(".$id.")";
			}
		}
		
		$savesuccess=true;
		
		if ($q2 != "") {
			
			$q = "INSERT INTO NSOUsers (idUser) VALUES ".$q2;
			
			if(!db_query($q,"Inserting User")) {
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