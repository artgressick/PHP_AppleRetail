<?
	include_once($BF.'calendar/components/add_functions.php');
	$table = 'NSOCorpTasks'; # added so not to forget to change the insert AND audit

	$q = "INSERT INTO ". $table ." SET 
		chrKEY = '". makekey() ."',
		chrNSOCorpTask = '". encode($_POST['chrNSOCorpTask']) ."',
		idNSOType = '". $_POST['idNSOType'] ."',
		intDateOffset = '". $_POST['intDateOffset'] ."'
	";
	
	# if there database insertion is successful	
	if(db_query($q,"Insert into ". $table)) {

		// This is the code for inserting the Audit Page
		// Type 1 means ADD NEW RECORD, change the TABLE NAME also
		global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
		$newID = mysqli_insert_id($mysqli_connection);
	
		$q = "INSERT INTO Audit SET 
			idType=1, 
			idRecord='". $newID ."',
			txtNewValue='". encode($_POST['chrNSOCorpTask']) ."',
			dtDateTime=now(),
			chrTableName='". $table ."',
			idUser='". $_SESSION['idUser'] ."'
		";
		db_query($q,"Insert audit");
		//End the code for History Insert 
	
		$_SESSION['infoMessages'][] = "Corporate Partner Task: ".$_POST['chrNSOCorpTask']." has been added successfully.";
		header("Location: ". $_POST['moveTo']);
		die();
	} else {
		# if the database insertion failed, send them to the error page with a useful message
		errorPage('An error has occurred while trying to add this Corporate Partner Task.');
	}
?>