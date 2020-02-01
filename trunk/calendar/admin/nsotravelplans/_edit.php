<?
	include_once($BF.'calendar/components/edit_functions.php');
	// Set the basic values to be used.
	//   $table = the table that you will be connecting to to check / make the changes
	//   $mysqlStr = this is the "mysql string" that you are going to be using to update with.  This needs to be set to "" (empty string)
	//   $sudit = this is the "audit string" that you are going to be using to update with.  This needs to be set to "" (empty string)
	$table = 'NSOTravelPlans';
	$mysqlStr = '';
	$audit = '';

	// "List" is a way for php to split up an array that is coming back.  
	// "set_strs" is a function (bottom of the _lib) that is set up to look at the old information in the DB, and compare it with
	//    the new information in the form fields.  If the information is DIFFERENT, only then add it to the mysql string to update.
	//    This will ensure that only information that NEEDS to be updated, is updated.  This means smaller and faster DB calls.
	//    ...  This also will ONLY add changes to the audit table if the values are different.
	list($mysqlStr,$audit) = set_strs($mysqlStr,'idUser',$info['idUser'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs_date($mysqlStr,'dBegin',$info['dBegin'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs_date($mysqlStr,'dEnd',$info['dEnd'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'txtNote',$info['txtNote'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'chrShortTitle',$info['chrShortTitle'],$audit,$table,$info['ID']);
	

	
	if((date('Y-m-d',strtotime($_POST['dBegin'])) != $info['dBegin']) || (date('Y-m-d',strtotime($_POST['dEnd'])) != $info['dEnd'])
		|| ($_POST['idUser'] != $info['idUser']) || (encode($_POST['chrShortTitle']) != $info['chrShortTitle'])) {
		$tmp = db_query("DELETE FROM TravelEvents WHERE idTravel=".$info['ID'],"Delete Old Travel Events");
		$results = db_query("SELECT CONCAT(chrFirst,' ',chrLast) AS chrName FROM Users WHERE ID=".$_POST['idUser'],"getting User name",1);
		$tmpDate = date('Y-m-d',strtotime($_POST['dBegin']));
		$endDate = strtotime($_POST['dEnd']);
		$series = makekey();
		$q = "";
		while(strtotime($tmpDate) <= $endDate) {
			
			$q .= "('". $info['ID'] ."','".makekey()."',1,'".$_SESSION['idUser']."',3,
				'". $tmpDate ."','". $tmpDate ."',now(),'".$results['chrName'].' - '.encode($_POST['chrShortTitle'])."','". $series ."'),";
				
			$tmpDate = date('Y-m-d',strtotime($tmpDate.' +1 day'));
		}
		
		$q = "INSERT INTO TravelEvents (idTravel,chrKEY,bAllDay,idUser,idCalendarType,dBegin,dEnd,dtCreated,chrCalendarEvent,chrSeries) 
				VALUES ". substr($q,0,-1);
		db_query($q,"adding Travel events");
			
	}
	

	// if nothing has changed, don't do anything.  Otherwise update / audit.
	if($mysqlStr != '') { 
		$_SESSION['infoMessages'][] = "The Travel Plan has been successfully updated in the Database.";
		list($str,$aud) = update_record($mysqlStr, $audit, $table, $info['ID']);
	 } else {
	 	$_SESSION['infoMessages'][] = "No Changes have been made to The Travel Plan.";
	 }
	
	header("Location: index.php");
	die();	
?>