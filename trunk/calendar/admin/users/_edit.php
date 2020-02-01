<?
	include_once($BF.'calendar/components/edit_functions.php');
	// Set the basic values to be used.
	//   $table = the table that you will be connecting to to check / make the changes
	//   $mysqlStr = this is the "mysql string" that you are going to be using to update with.  This needs to be set to "" (empty string)
	//   $sudit = this is the "audit string" that you are going to be using to update with.  This needs to be set to "" (empty string)
	$table = 'CalendarAccess';
	$mysqlStr = '';
	$audit = '';

	// "List" is a way for php to split up an array that is coming back.  
	// "set_strs" is a function (bottom of the _lib) that is set up to look at the old information in the DB, and compare it with
	//    the new information in the form fields.  If the information is DIFFERENT, only then add it to the mysql string to update.
	//    This will ensure that only information that NEEDS to be updated, is updated.  This means smaller and faster DB calls.
	//    ...  This also will ONLY add changes to the audit table if the values are different.
	
	$_POST['txtStoreAccess'] = (isset($_POST['storeaccess']) && count($_POST['storeaccess']) > 0 ? implode(',',$_POST['storeaccess']) : '');
	
	list($mysqlStr,$audit) = set_strs($mysqlStr,'bTravelAccess',$info['bTravelAccess'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'chrColorText',$info['chrColorText'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'chrColorBG',$info['chrColorBG'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'idSecurity',$info['idSecurity'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'txtStoreAccess',$info['txtStoreAccess'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'bShowOrangeEvents',$info['bShowOrangeEvents'],$audit,$table,$info['ID']);
	
	// if nothing has changed, don't do anything.  Otherwise update / audit.
	if($mysqlStr != '') { 
		$_SESSION['infoMessages'][] = $info['chrFirst']." ".$info['chrLast']." has been successfully updated in the Database.";
		list($str,$aud) = update_record($mysqlStr, $audit, $table, $info['ID']);
	 } else {
	 	$_SESSION['infoMessages'][] = "No Changes have been made to ".$info['chrFirst']." ".$info['chrLast'];
	 }
	
	header("Location: index.php");
	die();	
?>