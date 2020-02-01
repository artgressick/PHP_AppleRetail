<?
	include_once($BF.'calendar/components/edit_functions.php');
	// Set the basic values to be used.
	//   $table = the table that you will be connecting to to check / make the changes
	//   $mysqlStr = this is the "mysql string" that you are going to be using to update with.  This needs to be set to "" (empty string)
	//   $sudit = this is the "audit string" that you are going to be using to update with.  This needs to be set to "" (empty string)
	$table = 'CalSecurity';
	$mysqlStr = '';
	$audit = '';

	// "List" is a way for php to split up an array that is coming back.  
	// "set_strs" is a function (bottom of the _lib) that is set up to look at the old information in the DB, and compare it with
	//    the new information in the form fields.  If the information is DIFFERENT, only then add it to the mysql string to update.
	//    This will ensure that only information that NEEDS to be updated, is updated.  This means smaller and faster DB calls.
	//    ...  This also will ONLY add changes to the audit table if the values are different.
	list($mysqlStr,$audit) = set_strs($mysqlStr,'chrSecurity',$info['chrSecurity'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'bGlobal',$info['bGlobal'],$audit,$table,$info['ID']);
	
	db_query("DELETE FROM CalSecuritySelections WHERE idCalSecurity=".$info['ID'],"Delete old Records");
	$updated = false;
	if($_POST['bGlobal'] == 0) {
		$q = "SELECT F.ID
				FROM CalSecFiles AS F
				JOIN CalSecGroups AS G ON F.idGroup=G.ID
				WHERE !F.bDeleted AND !G.bDeleted
				ORDER BY G.ID, F.ID
				";
	
		$files = db_query($q, "Getting Files");
		$q2 = '';
		while($row = mysqli_fetch_assoc($files)) {
			if(isset($_POST['secure'.$row['ID']])) {
				$q2 .= "('".$info['ID']."','".$row['ID']."','".implode(',',$_POST['secure'.$row['ID']])."'),";
			}
		}
		
		if($q2 != '') {
			db_query("INSERT INTO CalSecuritySelections (idCalSecurity,idCalSecFile,chrLevels) VALUES ".substr($q2,0,-1),"Insert Security Values");
			$updated = true;
		}
	}
	
	
	// if nothing has changed, don't do anything.  Otherwise update / audit.
	if($mysqlStr != '' || $updated) { 
		$_SESSION['infoMessages'][] = $_POST['chrSecurity']." has been successfully updated in the Database.";
		if($mysqlStr != '') {
			list($str,$aud) = update_record($mysqlStr, $audit, $table, $info['ID']);
		}
	 } else {
	 	$_SESSION['infoMessages'][] = "No Changes have been made to ".$_POST['chrSecurity'];
	 }
	
	header("Location: index.php");
	die();	
?>