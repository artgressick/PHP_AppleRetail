<?
	include_once($BF.'calendar/components/edit_functions.php');
	// Set the basic values to be used.
	//   $table = the table that you will be connecting to to check / make the changes
	//   $mysqlStr = this is the "mysql string" that you are going to be using to update with.  This needs to be set to "" (empty string)
	//   $audit = this is the "audit string" that you are going to be using to update with.  This needs to be set to "" (empty string)
	$table = 'NSOTaskAssoc';
	$mysqlStr = '';
	$audit = '';
	
	// "List" is a way for php to split up an array that is coming back.  
	// "set_strs" is a function (bottom of the _lib) that is set up to look at the old information in the DB, and compare it with
	//    the new information in the form fields.  If the information is DIFFERENT, only then add it to the mysql string to update.
	//    This will ensure that only information that NEEDS to be updated, is updated.  This means smaller and faster DB calls.
	//    ...  This also will ONLY add changes to the audit table if the values are different.
	list($mysqlStr,$audit) = set_strs($mysqlStr,'intNSOTaskStatus',$info['intNSOTaskStatus'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'intDateOffset',$info['intDateOffset'],$audit,$table,$info['ID']);
	$_POST['idUser'] = $_SESSION['idUser'];
	list($mysqlStr,$audit) = set_strs($mysqlStr,'idUser',$info['idUser'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'txtNote',$info['txtNote'],$audit,$table,$info['ID']);

	// if nothing has changed, don't do anything.  Otherwise update / audit.
	if($mysqlStr != '') { 
		$_SESSION['infoMessages'][] = $info['chrNSOTask']." has been successfully updated in the Database.";
		list($str,$aud) = update_record($mysqlStr, $audit, $table, $info['ID']);
		db_query("UPDATE NSOs SET dtUpdated=now() WHERE ID=".$info['idNSO'],"Updating Timestamp for NSO");
		
	 } else {
	 	$_SESSION['infoMessages'][] = "No Changes have been made to ".$info['chrNSOTask'];
	 }
	
//	header("Location: view.php?key=".$_POST['oldkey']);
//	die();	
?>