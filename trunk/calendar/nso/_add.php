<?
	include_once($BF.'calendar/components/add_functions.php');
	$table = 'NSOs'; # added so not to forget to change the insert AND audit

	//Get the name of the store so that we can use it as the chrCalendarEvent
	$result = db_query("SELECT CONCAT(chrName,' ',chrStoreNum) as chrName FROM RetailStores WHERE ID=".$_POST['idStore'],"getting store name",1);
	if(isset($_POST['idBeginStatus'])) { $_POST['dBegin'] = ''; } else { $_POST['idBeginStatus'] = 0; }
	if(isset($_POST['idDate2Status'])) { $_POST['dDate2'] = ''; } else { $_POST['idDate2Status'] = 0; }
	if(isset($_POST['idDate3Status'])) { $_POST['dDate3'] = ''; } else { $_POST['idDate3Status'] = 0; }
	if(isset($_POST['idDate4Status'])) { $_POST['dDate4'] = ''; } else { $_POST['idDate4Status'] = 0; }
	if(isset($_POST['idEndStatus'])) { $_POST['dEnd'] = ''; } else { $_POST['idEndStatus'] = 0; } 
	
	$q = "INSERT INTO NSOs SET 
		chrKEY = '". makekey() ."',
		idStore = '". $_POST['idStore'] ."',
		idResponsible = '". $_POST['idResponsible'] ."',
		idNSOType = '". $_POST['idNSOType'] ."',
		idUser = '". $_SESSION['idUser'] ."',
		chrCalendarEvent = '". encode($results['chrName']) ."',
		txtHotel = '". encode($_POST['txtHotel']) ."',
		txtAirport = '". encode($_POST['txtAirport']) ."',
		bScope = '". $_POST['bScope'] ."',
		bShow = '". $_POST['bShow'] ."',
		txtScope = '". encode($_POST['txtScope']) ."',
		idBeginStatus = '". $_POST['idBeginStatus'] ."',
		idDate2Status = '". $_POST['idDate2Status'] ."',
		idDate3Status = '". $_POST['idDate3Status'] ."',
		idDate4Status = '". $_POST['idDate4Status'] ."',
		idEndStatus = '". $_POST['idEndStatus'] ."',
		dBegin = '". ($_POST['dBegin'] != '' ? date('Y-m-d',strtotime($_POST['dBegin'])) : '') ."',
		dDate2 = '". ($_POST['dDate2'] != '' ? date('Y-m-d',strtotime($_POST['dDate2'])) : '') ."',
		dDate3 = '". ($_POST['dDate3'] != '' ? date('Y-m-d',strtotime($_POST['dDate3'])) : '') ."',
		dDate4 = '". ($_POST['dDate4'] != '' ? date('Y-m-d',strtotime($_POST['dDate4'])) : '') ."',
		dEnd = '". ($_POST['dEnd'] != '' ? date('Y-m-d',strtotime($_POST['dEnd'])) : '') ."'
	";
	
	# if there database insertion is successful	
	if(db_query($q,"Insert into ". $table)) {

	// This is the code for inserting the Audit Page
		// Type 1 means ADD NEW RECORD, change the TABLE NAME also
		global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
		$newID = mysqli_insert_id($mysqli_connection);

	
		if($_POST['dBegin'] != '' && $_POST['dEnd'] != ''){
			$tmpDate = date('Y-m-d',strtotime($_POST['dBegin']));
			$endDate = strtotime($_POST['dEnd']);
			$series = makekey();
			$q = "";
			while(strtotime($tmpDate) <= $endDate) {
				
				$q .= "('". $newID ."','".makekey()."',1,'".$_SESSION['idUser']."',1,'". $_POST['idStore'] ."','". $_POST['idNSOType'] ."',
					'". $tmpDate ."','". $tmpDate ."',now(),'".$result['chrName']."','". $series ."'),";
					
				$tmpDate = date('Y-m-d',strtotime($tmpDate.' +1 day'));
			}
			
			$q = "INSERT INTO CalendarEvents (idNSO,chrKEY,bAllDay,idUser,idCalendarType,idStore,idNSOType,dBegin,dEnd,dtCreated,chrCalendarEvent,chrSeries) 
					VALUES ". substr($q,0,-1);
			db_query($q,"adding calendar events");
		}

		$results = db_query("SELECT ID,intDateOffset FROM NSOTasks WHERE !bDeleted AND idNSOType=".$_REQUEST['idNSOType'],"getting tasks");
		$q = "";
		while($row = mysqli_fetch_assoc($results)) { $q .= "('".$newID."','".$row['ID']."','".$row['intDateOffset']."','".makekey()."'),"; }
		if($q != "") { db_query("INSERT INTO NSOTaskAssoc (idNSO,idNSOTask,intDateOffset,chrKEY) VALUES". substr($q,0,-1),"adding tasks"); }

		$results = db_query("SELECT ID FROM NSONotifications WHERE !bDeleted AND bDefault","getting notifications");
		$q = "";
		while($row = mysqli_fetch_assoc($results)) { $q .= "('".$newID."','".$row['ID']."'),"; }
		if($q != "") { db_query("INSERT INTO NSONotificationAssoc (idNSO,idNSONotification) VALUES". substr($q,0,-1),"adding notifications"); }

		$results = db_query("SELECT ID FROM NSOUsers","getting users");
		$q = "";
		while($row = mysqli_fetch_assoc($results)) { $q .= "('".$newID."','".$row['ID']."'),"; }
		if($q != "") { db_query("INSERT INTO NSOUserAssoc (idNSO,idNSOUser) VALUES". substr($q,0,-1),"adding Users"); }
	
		$titles = db_query('SELECT ID,chrFieldName FROM UserTitles WHERE !bDeleted','getting titles');
		if(mysqli_num_rows($titles) > 0) {
			$q1 = "INSERT INTO NSOUserTitleAssoc (idNSO,idUser,idUserTitle) VALUES ";
			$q1a = '';
			$q2 = "INSERT INTO NSOUserTitleAssoc (idNSO,chrRecord,idUserTitle) VALUES ";
			$q2a = '';
			while($row = mysqli_fetch_assoc($titles)) {
				if($_POST[$row['chrFieldName']] != '') { 
					if (substr($row['chrFieldName'],0,2) == 'id') {
						$q1a .= "('".$newID."','".$_POST[$row['chrFieldName']]."','".$row['ID']."'),";
					} else if(substr($row['chrFieldName'],0,3) == 'chr') {
						$q2a .= "('".$newID."','".encode($_POST[$row['chrFieldName']])."','".$row['ID']."'),";
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
	
		$q = "INSERT INTO Audit SET 
			idType=1, 
			idRecord='". $newID ."',
			txtNewValue='". encode($result['chrName']) ."',
			dtDateTime=now(),
			chrTableName='". $table ."',
			idUser='". $_SESSION['idUser'] ."'
		";
		db_query($q,"Insert audit");
		//End the code for History Insert 
	
		$_SESSION['infoMessages'][] = "NSO Event has been added successfully.";
		header("Location: ". $_POST['moveTo']);
		die();
	} else {
		# if the database insertion failed, send them to the error page with a useful message
		errorPage('An error has occurred while trying to add this NSO event.');
	}
?>