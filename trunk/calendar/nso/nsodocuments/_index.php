<?
	include_once($BF.'calendar/components/add_functions.php');
	$table = 'CalendarFiles'; # added so not to forget to change the insert AND audit

		$q = "INSERT INTO ". $table ." SET 
			chrKEY = '". makekey() ."',
			idUser = '". $_SESSION['idUser'] ."',
			idNSO = '". $info['ID'] ."',
			idCalendarFileType=2,
			dtCreated=now(),
			chrFileTitle = '". encode($_POST['chrFileTitle']) ."',
			idNSOFileGroup = '". $_POST['idNSOFileGroup'] ."',
			txtFileDescription = '". encode($_POST['txtFileDescription']) ."'
		";
	
	# if there database insertion is successful	
	if(db_query($q,"Insert into ". $table)) {
	
		// This is the code for inserting the Audit Page
		// Type 1 means ADD NEW RECORD, change the TABLE NAME also
		global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
		$newID = mysqli_insert_id($mysqli_connection);
		
		$attName = strtolower(str_replace(" ","_",basename($_FILES['chrAttachment']['name'])));  //dtn: Replace any spaces with underscores.
		
		//dtn: Update the EmailMessages DB with the file attachment info.
		db_query("UPDATE ". $table ." SET 
			dbFileSize = '". $_FILES['chrAttachment']['size'] ."',
			chrCalendarFile = '". $newID ."-". $attName ."',
			chrFileType = '". $_FILES['chrAttachment']['type'] ."'
			WHERE ID=". $newID ."	
		","insert attachment");

		$uploaddir = $BF . 'calendar/nsodocuments/'; 	//dtn: Setting up the directory name for where things go
		$uploadfile = $uploaddir . $newID .'-'. $attName;
	
		move_uploaded_file($_FILES['chrAttachment']['tmp_name'], $uploadfile);  //dtn: move the file to where it needs to go.
		
		
		$q = "INSERT INTO Audit SET 
			idType=1, 
			idRecord='". $newID ."',
			txtNewValue='". encode($_POST['chrFileTitle']) ."',
			dtDateTime=now(),
			chrTableName='". $table ."',
			idUser='". $_SESSION['idUser'] ."'
		";
		db_query($q,"Insert audit");
		//End the code for History Insert 
	
		db_query("UPDATE NSOs SET dtUpdated=now() WHERE ID=".$info['ID'],"Updating Timestamp for NSO");
		
		$_SESSION['infoMessages'][] = "Document: ".$_POST['chrFileTitle']." has been added successfully.";
		header("Location: ". $_POST['moveTo']."?key=".$_POST['key']);
		die();
	} else {
		# if the database insertion failed, send them to the error page with a useful message
		errorPage('An error has occurred while trying to add/upload this Document.');
	}
?>