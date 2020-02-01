<?
	include_once($BF.'calendar/components/add_functions.php');
	include($BF.'calendar/components/thumbnail_gen.php');
	$table = 'SupplyItems'; # added so not to forget to change the insert AND audit

	$q = "INSERT INTO ". $table ." SET 
		chrKEY = '". makekey() ."',
		chrItem = '". encode($_POST['chrItem']) ."',
		txtDescription = '". encode($_POST['txtDescription']) ."'
	";
	
	# if there database insertion is successful	
	if(db_query($q,"Insert into ". $table)) {

		// This is the code for inserting the Audit Page
		// Type 1 means ADD NEW RECORD, change the TABLE NAME also
		global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
		$newID = mysqli_insert_id($mysqli_connection);
		if($_FILES['chrPicture']['name'] != '') { 
			$attName = strtolower(str_replace(" ","_",basename($_FILES['chrPicture']['name'])));  //dtn: Replace any spaces with underscores.
	
			$file = explode('.',$attName);
			$ext = $file[count($file) - 1];
			$thumbnail = basename($attName,'.'.$ext).'_tn.'.$ext;
			$medium = basename($attName,'.'.$ext).'_medium.'.$ext;
			
			//dtn: Update the EmailMessages DB with the file attachment info.
			db_query("UPDATE ". $table ." SET 
				dbFileSize = '". $_FILES['chrPicture']['size'] ."',
				chrFile = '". $newID ."-". $attName ."',
				chrFileType = '". $_FILES['chrPicture']['type'] ."',
				chrThumbnail = '". $newID .'-'. $thumbnail ."',
				chrMedium = '". $newID .'-'. $medium ."'
				WHERE ID=". $newID ."	
			","insert attachment");
	
			$uploaddir = $BF . 'calendar/nsosupply/'; 	//dtn: Setting up the directory name for where things go
			$uploadfile = $uploaddir . $newID .'-'. $attName;
	
			move_uploaded_file($_FILES['chrPicture']['tmp_name'], $uploadfile);  //dtn: move the file to where it needs to go.
			
			createtn($newID .'-'. $attName, $uploaddir);
		}		
		
		$q = "INSERT INTO Audit SET 
			idType=1, 
			idRecord='". $newID ."',
			txtNewValue='". encode($_POST['chrItem']) ."',
			dtDateTime=now(),
			chrTableName='". $table ."',
			idUser='". $_SESSION['idUser'] ."'
		";
		db_query($q,"Insert audit");
		//End the code for History Insert 
	
		$_SESSION['infoMessages'][] = "Supply Item: ".$_POST['chrItem']." has been added successfully.";
		header("Location: ". $_POST['moveTo']);
		die();
	} else {
		# if the database insertion failed, send them to the error page with a useful message
		errorPage('An error has occurred while trying to add this Supply Item.');
	}
?>