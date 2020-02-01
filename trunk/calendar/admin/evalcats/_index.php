<?php
	$table = 'EvalCats';
	$count = 0;
	while($row = mysqli_fetch_assoc($results)) { 
		if($row['intOrder'] != $_POST['dOrder'.$row['ID']]) {
			if(is_numeric($_POST['dOrder'.$row['ID']])) { 
				if(db_query("UPDATE ".$table." SET intOrder='".$_POST['dOrder'.$row['ID']]."' WHERE ID=".$row['ID'],"Updating Parent Order")) { $count++; }
			} else {
				$_SESSION['errorMessages'][] = "You must enter a number for the order of ".$row['chrCat'].". Order value reset.";
			}
		}
	}
	if($count > 0 && count($_SESSION['errorMessages']) == 0) {
		$_SESSION['infoMessages'][] = "Order Changes Updated Successfully.";
	} else if ($count == 0 && count($_SESSION['errorMessages']) == 0) {
		$_SESSION['infoMessages'][] = "No Changes to the order have been made.";
	}
	
	header("Location: index.php");
	die();?>