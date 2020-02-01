<?
	include_once($BF.'calendar/components/add_functions.php');
	$table = 'NSOTravelPlans'; # added so not to forget to change the insert AND audit

	$q = "INSERT INTO ". $table ." SET 
		chrKEY = '". makekey() ."',
		idUser = '". $_POST['idUser'] ."',
		chrShortTitle = '". encode($_POST['chrShortTitle']) ."',
		dBegin = '".date('Y-m-d',strtotime($_POST['dBegin']))."',
		dEnd = '".date('Y-m-d',strtotime($_POST['dEnd']))."',
		txtNote = '". encode($_POST['txtNote']) ."'
	";
	
	# if there database insertion is successful	
	if(db_query($q,"Insert into ". $table)) {

		// This is the code for inserting the Audit Page
		// Type 1 means ADD NEW RECORD, change the TABLE NAME also
		global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
		$newID = mysqli_insert_id($mysqli_connection);

		$results = db_query("SELECT CONCAT(chrFirst,' ',chrLast) as chrName FROM Users WHERE ID=".$_POST['idUser'],"getting User name",1);
		
		$tmpDate = date('Y-m-d',strtotime($_POST['dBegin']));
		$endDate = strtotime($_POST['dEnd']);
		$series = makekey();
		$q2 = "";
		while(strtotime($tmpDate) <= $endDate) {
			
			$q2 .= "('". $newID ."','".makekey()."',1,'".$_SESSION['idUser']."',3,
				'". $tmpDate ."','". $tmpDate ."',now(),'".$results['chrName'].' - '.encode($_POST['chrShortTitle'])."','". $series ."'),";
				
			$tmpDate = date('Y-m-d',strtotime($tmpDate.' +1 day'));
		}
		
		$q = "INSERT INTO TravelEvents (idTravel,chrKEY,bAllDay,idUser,idCalendarType,dBegin,dEnd,dtCreated,chrCalendarEvent,chrSeries) 
				VALUES ". substr($q2,0,-1);
		if($q2 != '') { db_query($q,"adding Travel events"); }
		
		
		
		$q = "INSERT INTO Audit SET 
			idType=1, 
			idRecord='". $newID ."',
			txtNewValue='". encode($_POST['idUser']) ."',
			dtDateTime=now(),
			chrTableName='". $table ."',
			idUser='". $_SESSION['idUser'] ."'
		";
		db_query($q,"Insert audit");
		//End the code for History Insert 
	
		$_SESSION['infoMessages'][] = "NSO Travel Plan has been added successfully.";
		header("Location: ". $_POST['moveTo']);
		die();
	} else {
		# if the database insertion failed, send them to the error page with a useful message
		errorPage('An error has occurred while trying to add this NSO Travel Plan.');
	}
?>