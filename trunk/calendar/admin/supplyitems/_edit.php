<?
	include_once($BF.'calendar/components/edit_functions.php');
	include($BF.'calendar/components/thumbnail_gen.php');
	// Set the basic values to be used.
	//   $table = the table that you will be connecting to to check / make the changes
	//   $mysqlStr = this is the "mysql string" that you are going to be using to update with.  This needs to be set to "" (empty string)
	//   $sudit = this is the "audit string" that you are going to be using to update with.  This needs to be set to "" (empty string)
	$table = 'SupplyItems';
	$mysqlStr = '';
	$audit = '';

	// "List" is a way for php to split up an array that is coming back.  
	// "set_strs" is a function (bottom of the _lib) that is set up to look at the old information in the DB, and compare it with
	//    the new information in the form fields.  If the information is DIFFERENT, only then add it to the mysql string to update.
	//    This will ensure that only information that NEEDS to be updated, is updated.  This means smaller and faster DB calls.
	//    ...  This also will ONLY add changes to the audit table if the values are different.
	list($mysqlStr,$audit) = set_strs($mysqlStr,'chrItem',$info['chrItem'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'txtDescription',$info['txtDescription'],$audit,$table,$info['ID']);
	$picupdated=false;
	if($_FILES['chrPicture']['name'] != '') { 
		$attName = strtolower(str_replace(" ","_",basename($_FILES['chrPicture']['name'])));  //dtn: Replace any spaces with underscores.

		$file = explode('.',$attName);
		$ext = $file[count($file) - 1];
		$thumbnail = basename($attName,'.'.$ext).'_tn.'.$ext;
		$medium = basename($attName,'.'.$ext).'_medium.'.$ext;
		
		//dtn: Update the EmailMessages DB with the file attachment info.
		db_query("UPDATE ". $table ." SET 
			dbFileSize = '". $_FILES['chrPicture']['size'] ."',
			chrFile = '". $info['ID'] ."-". $attName ."',
			chrFileType = '". $_FILES['chrPicture']['type'] ."',
			chrThumbnail = '". $info['ID'] .'-'. $thumbnail ."',
			chrMedium = '". $info['ID'] .'-'. $medium ."'
			WHERE ID=". $info['ID'] ."	
		","insert attachment");

		$uploaddir = $BF . 'calendar/nsosupply/'; 	//dtn: Setting up the directory name for where things go
		$uploadfile = $uploaddir . $info['ID'] .'-'. $attName;

		move_uploaded_file($_FILES['chrPicture']['tmp_name'], $uploadfile);  //dtn: move the file to where it needs to go.
		
		createtn($info['ID'] .'-'. $attName, $uploaddir);
		$picupdated=true;
	}		
	
	
	
	// if nothing has changed, don't do anything.  Otherwise update / audit.
	if($mysqlStr != '' || $picupdated) { 
		$_SESSION['infoMessages'][] = $_POST['chrItem']." has been successfully updated in the Database.";
		if($mysqlStr != '') {
			list($str,$aud) = update_record($mysqlStr, $audit, $table, $info['ID']);
		}
	 } else {
	 	$_SESSION['infoMessages'][] = "No Changes have been made to ".$_POST['chrItem'];
	 }
	
	header("Location: index.php");
	die();	
?>