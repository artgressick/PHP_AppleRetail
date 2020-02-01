<?
	include_once($BF.'calendar/components/add_functions.php');
	$table = 'CalendarAccess'; # added so not to forget to change the insert AND audit

	if($_POST['addType']=='1') { // Add Existing User
		$q = "INSERT INTO ". $table ." SET 
			chrKEY = '". makekey() ."',
			idUser = '". $_POST['idUser'] ."',
			chrColorText = '". $_POST['chrColorText'] ."',
			chrColorBG = '". $_POST['chrColorBG'] ."',
			idSecurity = '".$_POST['idSecurity']."',
			bTravelAccess = '". $_POST['bTravelAccess'] ."',
			bShowOrangeEvents = '". $_POST['bShowOrangeEvents'] ."',
			txtStoreAccess = '".(isset($_POST['storeaccess']) && count($_POST['storeaccess']) > 0 ? implode(',',$_POST['storeaccess']) : '')."'
		";

		# if there database insertion is successful	
		if(db_query($q,"Insert into ". $table)) {
			global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
			$newID = mysqli_insert_id($mysqli_connection);
		
			// Lets create this user a upload folder for events
			// Grab the E-mail address for this user
			$tmp = db_query("SELECT chrEmail FROM Users WHERE ID=". $_POST['idUser'],"getting useremail",1);
			// Set Upload Directory
			$upload_dir = $BF. "userfiles/".$tmp['chrEmail'];
			// If the directory doesn't exist then create
			if (!is_dir($upload_dir)) {
				#chmod($BF ."userfiles/", 0777);
				mkdir($upload_dir, 0777);
			}
			//check if the directory is writable.
	        if (!is_writeable("$upload_dir")) { chmod($upload_dir, 0777); }
		
			// This is the code for inserting the Audit Page
			// Type 1 means ADD NEW RECORD, change the TABLE NAME also
			
			$q = "INSERT INTO Audit SET 
				idType=1, 
				idRecord='". $newID ."',
				txtNewValue='". $_POST['idUser'] ."',
				dtDateTime=now(),
				chrTableName='". $table ."',
				idUser='". $_SESSION['idUser'] ."'
			";
			db_query($q,"Insert audit");
			//End the code for History Insert 
		
			$_SESSION['infoMessages'][] = "User access has been added successfully.";
			header("Location: ". $_POST['moveTo']);
			die();
		} else {
			# if the database insertion failed, send them to the error page with a useful message
			errorPage('An error has occurred while trying to grant this user access.');
		}
	} else { // Add New User
		$q = "INSERT INTO Users SET
				chrFirst = '". encode($_POST['chrFirst']) ."',
				chrLast = '". encode($_POST['chrLast']) ."',
				chrEmail = '". $_POST['chrEmail'] ."',
				chrPassword = '". sha1($_POST['chrPassword']) ."'
		";
		if(db_query($q,"Insert into Users")) {
			global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
			$newID = mysqli_insert_id($mysqli_connection);

			$q = "INSERT INTO Audit SET 
				idType=1, 
				idRecord='". $newID ."',
				txtNewValue='". encode($_POST['chrFirst'].' '.$_POST['chrLast']) ."',
				dtDateTime=now(),
				chrTableName='Users',
				idUser='". $_SESSION['idUser'] ."'
			";
			db_query($q,"Insert audit");
			
			$q = "INSERT INTO ". $table ." SET 
				chrKEY = '". makekey() ."',
				idUser = '". $newID ."',
				chrColorText = '". $_POST['chrColorText'] ."',
				chrColorBG = '". $_POST['chrColorBG'] ."',
				idSecurity = '".$_POST['idSecurity2']."',
				bTravelAccess = '". $_POST['bTravelAccess'] ."',
				txtStoreAccess = '".(isset($_POST['storeaccess']) && count($_POST['storeaccess']) > 0 ? implode(',',$_POST['storeaccess']) : '')."'
			";
			
			if(db_query($q,"Insert into ". $table)) {
				global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
				$newID1 = mysqli_insert_id($mysqli_connection);
				// Lets create this user a upload folder for events
				// Set Upload Directory
				$upload_dir = $BF. "userfiles/".$_POST['chrEmail'];
				// If the directory doesn't exist then create
				if (!is_dir($upload_dir)) {
					#chmod($BF ."userfiles/", 0777);
					mkdir($upload_dir, 0777);
				}
				//check if the directory is writable.
		        if (!is_writeable("$upload_dir")) { chmod($upload_dir, 0777); }
		
				$q = "INSERT INTO Audit SET 
					idType=1, 
					idRecord='". $newID1 ."',
					txtNewValue='". $newID ."',
					dtDateTime=now(),
					chrTableName='". $table ."',
					idUser='". $_SESSION['idUser'] ."'
				";
				db_query($q,"Insert audit");
				//End the code for History Insert 
			
				$_SESSION['infoMessages'][] = "User access has been added successfully.";
				header("Location: ". $_POST['moveTo']);
				die();
			} else {
				# if the database insertion failed, send them to the error page with a useful message
				errorPage('An error has occurred while trying to grant this user access.');
			}
		} else {
			# if the database insertion failed, send them to the error page with a useful message
			errorPage('An error has occurred while trying to add this User to the Database.');
		}
	
	}
?>