<?
	include_once($BF.'calendar/components/edit_functions.php');
	// Set the basic values to be used.
	//   $table = the table that you will be connecting to to check / make the changes
	//   $mysqlStr = this is the "mysql string" that you are going to be using to update with.  This needs to be set to "" (empty string)
	//   $sudit = this is the "audit string" that you are going to be using to update with.  This needs to be set to "" (empty string)
	$table = 'NSOs';
	$mysqlStr = '';
	$audit = '';
	$update = array();
	
	if(isset($_POST['idBeginStatus'])) { $_POST['dBegin'] = ''; } else { $_POST['idBeginStatus'] = 0; }
	if(isset($_POST['idDate2Status'])) { $_POST['dDate2'] = ''; } else { $_POST['idDate2Status'] = 0; }
	if(isset($_POST['idDate3Status'])) { $_POST['dDate3'] = ''; } else { $_POST['idDate3Status'] = 0; }
	if(isset($_POST['idDate4Status'])) { $_POST['dDate4'] = ''; } else { $_POST['idDate4Status'] = 0; }
	if(isset($_POST['idEndStatus'])) { $_POST['dEnd'] = ''; } else { $_POST['idEndStatus'] = 0; } 
	
	if($_POST['dEnd'] != '') { $_POST['dEnd'] = date('Y-m-d',strtotime($_POST['dEnd'])); } 
	if($_POST['dBegin'] != '') { $_POST['dBegin'] = date('Y-m-d',strtotime($_POST['dBegin'])); }
	if($_POST['dDate2'] != '') { $_POST['dDate2'] = date('Y-m-d',strtotime($_POST['dDate2'])); }
	if($_POST['dDate3'] != '') { $_POST['dDate3'] = date('Y-m-d',strtotime($_POST['dDate3'])); }
	if($_POST['dDate4'] != '') { $_POST['dDate4'] = date('Y-m-d',strtotime($_POST['dDate4'])); }
	
	// "List" is a way for php to split up an array that is coming back.  
	// "set_strs" is a function (bottom of the _lib) that is set up to look at the old information in the DB, and compare it with
	//    the new information in the form fields.  If the information is DIFFERENT, only then add it to the mysql string to update.
	//    This will ensure that only information that NEEDS to be updated, is updated.  This means smaller and faster DB calls.
	//    ...  This also will ONLY add changes to the audit table if the values are different.
	list($mysqlStr,$audit) = set_strs($mysqlStr,'idStore',$info['idStore'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'idResponsible',$info['idResponsible'],$audit,$table,$info['ID']);
	
	list($mysqlStr,$audit) = set_strs($mysqlStr,'idBeginStatus',$info['idBeginStatus'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'idDate2Status',$info['idDate2Status'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'idDate3Status',$info['idDate3Status'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'idDate4Status',$info['idDate4Status'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'idEndStatus',$info['idEndStatus'],$audit,$table,$info['ID']);
	
	$storeName = db_query('SELECT chrName FROM RetailStores WHERE ID='.$_POST['idStore'],'getting store name',1);
	if($mysqlStr != '') {
		$_POST['chrCalendarEvent'] = $storeName['chrName'];
		list($mysqlStr,$audit) = set_strs($mysqlStr,'chrCalendarEvent',$info['chrCalendarEvent'],$audit,$table,$info['ID']);
		$update['store'] = 1;
	}
	list($mysqlStr,$audit) = set_strs($mysqlStr,'idNSOType',$info['idNSOType'],$audit,$table,$info['ID']);
	if($_POST['idNSOType'] != $info['idNSOType']) { 
		$update['nsotype'] = 1; 
		db_query("DELETE FROM NSOTaskAssoc WHERE idNSO=".$info['ID'],"delete old tasks");
		$results = db_query("SELECT ID,intDateOffset FROM NSOTasks WHERE !bDeleted AND idNSOType=".$_POST['idNSOType'],"getting tasks");
		$q = "";
		while($row = mysqli_fetch_assoc($results)) { $q .= "('".$info['ID']."','".$row['ID']."','".$row['intDateOffset']."','".makekey()."'),"; }
		if($q != "") { db_query("INSERT INTO NSOTaskAssoc (idNSO,idNSOTask,intDateOffset,chrKEY) VALUES". substr($q,0,-1),"adding tasks"); }
	}
	
	if(count($update) > 0) { 
		db_query("UPDATE CalendarEvents SET 
			". ($update['store'] != '' ? " idStore=".$_POST['idStore'].",chrCalendarEvent='".$storeName['chrName']."'" : '') ."
			". ($update['nsotype'] != '' ? (count($update) == 2 ? ',' : ' ').'idNSOType='.$_POST['idNSOType'] : '') ."
			WHERE idNSO=".$info['ID']
		,'updating calendar events');
	}
	
	$titles = db_query('SELECT ID,chrFieldName FROM UserTitles WHERE !bDeleted','getting titles');
	if(mysqli_num_rows($titles) > 0) {
		db_query('DELETE FROM NSOUserTitleAssoc WHERE idNSO='.$info['ID'],'deleting nso user title assoc');
		$q1 = "INSERT INTO NSOUserTitleAssoc (idNSO,idUser,idUserTitle) VALUES ";
		$q1a = '';
		$q2 = "INSERT INTO NSOUserTitleAssoc (idNSO,chrRecord,idUserTitle) VALUES ";
		$q2a = '';
		while($row = mysqli_fetch_assoc($titles)) {
			if($_POST[$row['chrFieldName']] != '') { 
				if (substr($row['chrFieldName'],0,2) == 'id') {
					$q1a .= "('".$info['ID']."','".$_POST[$row['chrFieldName']]."','".$row['ID']."'),";
				} else if(substr($row['chrFieldName'],0,3) == 'chr') {
					$q2a .= "('".$info['ID']."','".encode($_POST[$row['chrFieldName']])."','".$row['ID']."'),";
				}
			}
		}
		if($q1a != '') {
			db_query(substr($q1.$q1a,0,-1),'insert all user titles');
		}
		if($q2a != '') {
			db_query(substr($q2.$q2a,0,-1),'insert all user titles');
		}
		
	}

	if((date('Y-m-d',strtotime($_POST['dBegin'])) != $info['dBegin']) || (date('Y-m-d',strtotime($_POST['dEnd'])) != $info['dEnd']) || ($_POST['idStore'] != $info['idStore'])) {
		$tmp = db_query("DELETE FROM CalendarEvents WHERE idNSO=".$info['ID']." AND idCalendarType=1","Delete Old Calendar Events");
		if($_POST['dBegin'] != '' && $_POST['dEnd'] != '' && $_POST['dBegin'] != '0000-00-00' && $_POST['dEnd'] != '0000-00-00') {
			$results = db_query("SELECT CONCAT(chrName,' / ',chrStoreNum) AS chrName FROM RetailStores WHERE ID=".$_POST['idStore'],"getting store name",1);
			$tmpDate = date('Y-m-d',strtotime($_POST['dBegin']));
			$endDate = strtotime($_POST['dEnd']);
			$series = makekey();
			$q = "";
			while(strtotime($tmpDate) <= $endDate) {
				
				$q .= "('". $info['ID'] ."','".makekey()."',1,'".$_SESSION['idUser']."',1,'". $_POST['idStore'] ."','". $_POST['idNSOType'] ."',
					'". $tmpDate ."','". $tmpDate ."',now(),'".$results['chrName']."','". $series ."'),";
					
				$tmpDate = date('Y-m-d',strtotime($tmpDate.' +1 day'));
			}
			
			$q = "INSERT INTO CalendarEvents (idNSO,chrKEY,bAllDay,idUser,idCalendarType,idStore,idNSOType,dBegin,dEnd,dtCreated,chrCalendarEvent,chrSeries) 
					VALUES ". substr($q,0,-1);
			db_query($q,"adding calendar events");
		}	
	}
	
	list($mysqlStr,$audit) = set_strs($mysqlStr,'txtHotel',$info['txtHotel'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'txtAirport',$info['txtAirport'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'bScope',$info['bScope'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'bShow',$info['bShow'],$audit,$table,$info['ID']);
	list($mysqlStr,$audit) = set_strs($mysqlStr,'txtScope',$info['txtScope'],$audit,$table,$info['ID']);
	
	
	list($mysqlStr,$audit) = set_strs_date($mysqlStr,'dBegin',$info['dBegin'],$audit,$table,$info['ID'],'false');
	list($mysqlStr,$audit) = set_strs_date($mysqlStr,'dEnd',$info['dEnd'],$audit,$table,$info['ID'],'false');
	list($mysqlStr,$audit) = set_strs_date($mysqlStr,'dDate2',$info['dDate2'],$audit,$table,$info['ID'],'false');
	list($mysqlStr,$audit) = set_strs_date($mysqlStr,'dDate3',$info['dDate3'],$audit,$table,$info['ID'],'false');
	list($mysqlStr,$audit) = set_strs_date($mysqlStr,'dDate4',$info['dDate4'],$audit,$table,$info['ID'],'false');
	
	// if nothing has changed, don't do anything.  Otherwise update / audit.
	if($mysqlStr != '' || $q1a != '' || $q2a != '') { 
		$_SESSION['infoMessages'][] = $storeName['chrName']." has been successfully updated in the Database.";
		if($mysqlStr != '') {
			list($str,$aud) = update_record($mysqlStr, $audit, $table, $info['ID']);
		}
		db_query("UPDATE NSOs SET dtUpdated=now() WHERE ID=".$info['ID'],"Updating Timestamp for NSO");
	 } else {
	 	$_SESSION['infoMessages'][] = "No Changes have been made to ".$storeName['chrName'];
	 }

	$datefields = array('Begin','End','Date2','Date3','Date4');
	foreach ($datefields AS $field) {
		if($_POST['d'.$field] != $info['d'.$field] || $info['id'.$field.'Status'] != $_POST['id'.$field.'Status']) {
			$original = ($info['id'.$field.'Status'] > 0?($info['id'.$field.'Status']==1?'TBD':'Canceled'):$info['d'.$field]);
			$new = ($_POST['id'.$field.'Status'] > 0?($_POST['id'.$field.'Status']==1?'TBD':'Canceled'):$_POST['d'.$field]);
		
			$tmp = db_query("INSERT INTO DateChangesQue SET
							 idNSO = '".$info['ID']."',
							 idUser = '".$_SESSION['idUser']."',
							 chrField = 'd".$field."',
							 dOrig = '".$original."',
							 dNew = '".$new."',
							 chrReason = '".$_POST['chrd'.$field.'Change']."'
							","Insert New Entry"); 
			//Insert to Audit table
			$tmp = db_query("INSERT INTO Audit SET 
							 idUser = '".$_SESSION['idUser']."', 
							 idType = 2, 
							 idRecord = '".$info['ID']."', 
							 chrTablename = '".$table."', 
							 chrColumnName = 'd".$field."', 
							 txtOldValue = '".$original."', 
							 txtNewValue = '".$new."'
							 ","INSERT AUDIT RECORD");
		}
	}

	header("Location: index.php");
	die();	
?>