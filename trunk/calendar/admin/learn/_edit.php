<?
	include_once($BF.'calendar/components/edit_functions.php');
	// Set the basic values to be used.
	//   $table = the table that you will be connecting to to check / make the changes
	//   $mysqlStr = this is the "mysql string" that you are going to be using to update with.  This needs to be set to "" (empty string)
	//   $sudit = this is the "audit string" that you are going to be using to update with.  This needs to be set to "" (empty string)
	$table = 'NSOLearn';
	$mysqlStr = '';
	$audit = '';

	// "List" is a way for php to split up an array that is coming back.  
	// "set_strs" is a function (bottom of the _lib) that is set up to look at the old information in the DB, and compare it with
	//    the new information in the form fields.  If the information is DIFFERENT, only then add it to the mysql string to update.
	//    This will ensure that only information that NEEDS to be updated, is updated.  This means smaller and faster DB calls.
	//    ...  This also will ONLY add changes to the audit table if the values are different.
	list($mysqlStr,$audit) = set_strs($mysqlStr,'chrTitle',$info['chrTitle'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'txtContent',$info['txtContent'],$audit,$table,$info['ID']);
	
	if($info['bParent']) {
		$_POST['bPShow'] = $_POST['bShow']; 
		if($_POST['bPShow'] != $info['bPShow']) {
			db_query("UPDATE ".$table." SET bPShow = '". $_POST['bPShow'] ."' WHERE idParent=".$info['ID'],"Updating Parent Show");
			list($mysqlStr,$audit) = set_strs($mysqlStr,'bPShow',$info['bPShow'],$audit,$table,$info['ID']);
			list($mysqlStr,$audit) = set_strs($mysqlStr,'bShow',$info['bShow'],$audit,$table,$info['ID']);
		}
	
	} else {

		if($_POST['idParent'] != $info['idParent']) {
			list($mysqlStr,$audit) = set_strs($mysqlStr,'idParent',$info['idParent'],$audit,$table,$info['ID']);
			$newparent = db_query("SELECT dOrder, bPShow FROM NSOLearn WHERE idType=1 AND bParent AND ID=".$_POST['idParent'],"Getting New Parent Information",1);
			$_POST['bPShow'] = $newparent['bPShow'];
			$_POST['dOrder'] = $newparent['dOrder']; 
			$highest = db_query("SELECT dOrderChild FROM ".$table." WHERE !bParent AND idType=1 AND idParent='".$_POST['idParent']."' AND ID != '".$info['ID']."' AND !bDeleted ORDER BY dOrderChild DESC","Getting Highest Number",1);
			$_POST['dOrderChild'] = ($highest['dOrderChild']==''?'0':$highest['dOrderChild']) + 1;
			list($mysqlStr,$audit) = set_strs($mysqlStr,'bPShow',$info['bPShow'],$audit,$table,$info['ID']);
			list($mysqlStr,$audit) = set_strs($mysqlStr,'dOrder',$info['dOrder'],$audit,$table,$info['ID']);
			list($mysqlStr,$audit) = set_strs($mysqlStr,'dOrderChild',$info['dOrderChild'],$audit,$table,$info['ID']);
		}
		list($mysqlStr,$audit) = set_strs($mysqlStr,'bShow',$info['bShow'],$audit,$table,$info['ID']);
	
	}
	
	// if nothing has changed, don't do anything.  Otherwise update / audit.
	if($mysqlStr != '') { 
		$_SESSION['infoMessages'][] = $_POST['chrTitle']." has been successfully updated in the Database.";
		list($str,$aud) = update_record($mysqlStr, $audit, $table, $info['ID']);
	 } else {
	 	$_SESSION['infoMessages'][] = "No Changes have been made to ".$_POST['chrTitle'];
	 }
	
	header("Location: index.php");
	die();	
?>