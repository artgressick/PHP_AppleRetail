<?
	include_once($BF.'calendar/components/add_functions.php');
	$table = 'PunchCats'; # added so not to forget to change the insert AND audit

	$next = db_query("SELECT dOrder FROM PunchCats WHERE !bDeleted ORDER by dOrder DESC","Getting Next dOrder",1);
	
	$nextOrder = ($next['dOrder'] == '' ? 0 : $next['dOrder']) + 1;
	
	$q = "INSERT INTO ". $table ." SET 
		chrKEY = '". makekey() ."',
		dOrder = '".$nextOrder."',
		chrPunchCat = '". encode($_POST['chrPunchCat']) ."'
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
			txtNewValue='". encode($_POST['chrPunchCat']) ."',
			dtDateTime=now(),
			chrTableName='". $table ."',
			idUser='". $_SESSION['idUser'] ."'
		";
		db_query($q,"Insert audit");
		//End the code for History Insert 
	
		$_SESSION['infoMessages'][] = "Punch List Category: ".$_POST['chrPunchCat']." has been added successfully.";
		header("Location: ". $_POST['moveTo']);
		die();
	} else {
		# if the database insertion failed, send them to the error page with a useful message
		errorPage('An error has occurred while trying to add this category.');
	}
?>