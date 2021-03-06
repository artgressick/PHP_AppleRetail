<?
	include_once($BF.'calendar/components/edit_functions.php');
	// Set the basic values to be used.
	//   $table = the table that you will be connecting to to check / make the changes
	//   $mysqlStr = this is the "mysql string" that you are going to be using to update with.  This needs to be set to "" (empty string)
	//   $sudit = this is the "audit string" that you are going to be using to update with.  This needs to be set to "" (empty string)
	$table = 'CalendarFiles';
	$mysqlStr = '';
	$audit = '';

	// "List" is a way for php to split up an array that is coming back.  
	// "set_strs" is a function (bottom of the _lib) that is set up to look at the old information in the DB, and compare it with
	//    the new information in the form fields.  If the information is DIFFERENT, only then add it to the mysql string to update.
	//    This will ensure that only information that NEEDS to be updated, is updated.  This means smaller and faster DB calls.
	//    ...  This also will ONLY add changes to the audit table if the values are different.
	list($mysqlStr,$audit) = set_strs($mysqlStr,'idNSOFileGroup',$info['idNSOFileGroup'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'chrFileTitle',$info['chrFileTitle'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'txtFileDescription',$info['txtFileDescription'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs_checkbox($mysqlStr,'bPrimary',$info['bPrimary'],$audit,$table,$info['ID']);
	
	if($_POST['bPrimary'] == 'on' && $info['bPrimary'] != 1) {
		db_query("UPDATE CalendarFiles SET bPrimary=0 WHERE bPrimary=1 AND idNSO=". $info['idNSO'],"changing the primary");
	}
	
	// if nothing has changed, don't do anything.  Otherwise update / audit.
	if($mysqlStr != '') { 
		$_SESSION['infoMessages'][] = ($_POST['chrFileTitle'] != '' ? $_POST['chrFileTitle'] : $info['chrCalendarFile'])." has been successfully updated in the Database.";
		list($str,$aud) = update_record($mysqlStr, $audit, $table, $info['ID']);
		db_query("UPDATE NSOs SET dtUpdated=now() WHERE ID=".$info['idNSO'],"Updating Timestamp for NSO");
	 } else {
	 	$_SESSION['infoMessages'][] = "No Changes have been made to ".($_POST['chrFileTitle'] != '' ? $_POST['chrFileTitle'] : $info['chrCalendarFile']);
	 }
	
	header("Location: ../view.php?key=".$info['chrEventKEY']);
	die();	
?>