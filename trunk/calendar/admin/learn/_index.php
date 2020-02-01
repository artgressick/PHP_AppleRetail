<?php
	$table = 'NSOLearn';
	$count = 0;
	while($row = mysqli_fetch_assoc($results)) { 
		if($row['bParent']) {
			if($row['dOrder'] != $_POST['dOrder'.$row['ID']]) {
				if(is_numeric($_POST['dOrder'.$row['ID']])) { 
					if(db_query("UPDATE ".$table." SET dOrder='".$_POST['dOrder'.$row['ID']]."' WHERE idType=1 AND idParent=".$row['ID'],"Updating Parent Order")) { $count++; }
				} else {
					$_SESSION['errorMessages'][] = "You must enter a number for the order of ".$row['chrTitle'].". Order value reset.";
				}
			}
		} else {
			if($row['dOrderChild'] != $_POST['dOrderChild'.$row['ID']]) {
				if(is_numeric($_POST['dOrderChild'.$row['ID']])) { 
					if(db_query("UPDATE ".$table." SET dOrderChild='".$_POST['dOrderChild'.$row['ID']]."' WHERE idType=1 AND ID=".$row['ID'],"Updating Child Order")) { $count++; }
				} else {
					$_SESSION['errorMessages'][] = "You must enter a number for the order of ".$row['chrTitle'].". Order value reset.";
				}
			}
		}
	}
	if($count > 0 && count($_SESSION['errorMessages']) == 0) {
		$_SESSION['infoMessages'][] = "Order Changes Updated Successfully.";
	} else if ($count == 0 && count($_SESSION['errorMessages']) == 0) {
		$_SESSION['infoMessages'][] = "No Changes to the order have been made.";
	}
	
	header("Location: index.php");
	die();	
?>