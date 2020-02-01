<?
	include_once($BF.'calendar/components/add_functions.php');
	$table = 'CalSecurity'; # added so not to forget to change the insert AND audit

	$q = "INSERT INTO ". $table ." SET 
		chrKEY = '". makekey() ."',
		bGlobal = '".$_POST['bGlobal']."',
		chrSecurity = '". encode($_POST['chrSecurity']) ."'
	";
	
	# if there database insertion is successful	
	if(db_query($q,"Insert into ". $table)) {

		// This is the code for inserting the Audit Page
		// Type 1 means ADD NEW RECORD, change the TABLE NAME also
		global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
		$newID = mysqli_insert_id($mysqli_connection);

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
					$q2 .= "('".$newID."','".$row['ID']."','".implode(',',$_POST['secure'.$row['ID']])."'),";
				}
			}
			
			if($q2 != '') {
				db_query("INSERT INTO CalSecuritySelections (idCalSecurity,idCalSecFile,chrLevels) VALUES ".substr($q2,0,-1),"Insert Security Values");
			}
		}
		
		$q = "INSERT INTO Audit SET 
			idType=1, 
			idRecord='". $newID ."',
			txtNewValue='". encode($_POST['chrSecurity']) ."',
			dtDateTime=now(),
			chrTableName='". $table ."',
			idUser='". $_SESSION['idUser'] ."'
		";
		db_query($q,"Insert audit");
		//End the code for History Insert 
	
		$_SESSION['infoMessages'][] = "Security Group: ".$_POST['chrSecurity']." has been added successfully.";
		header("Location: ". $_POST['moveTo']);
		die();
	} else {
		# if the database insertion failed, send them to the error page with a useful message
		errorPage('An error has occurred while trying to add this Security Group.');
	}
?>