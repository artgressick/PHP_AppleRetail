<?
	include_once($BF.'calendar/components/add_functions.php');
	$table = 'NSOLearn'; # added so not to forget to change the insert AND audit

	$q = "INSERT INTO ". $table ." SET 
		chrKEY = '". makekey() ."',
		bParent = '".$_POST['bParent']."',
		chrTitle = '". encode($_POST['chrTitle']) ."',
		idCreator = '".$_SESSION['idUser']."',
		idUpdater = '".$_SESSION['idUser']."',
		dtCreated = now(),
		idType=1,
		txtContent = '".encode($_POST['txtContent'])."'
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
			txtNewValue='". encode($_POST['chrTitle']) ."',
			dtDateTime=now(),
			chrTableName='". $table ."',
			idUser='". $_SESSION['idUser'] ."'
		";
		db_query($q,"Insert audit");
		//End the code for History Insert 
		
		if($_POST['bParent']) { // Parent
		
			$highest = db_query("SELECT dOrder FROM ".$table." WHERE bParent AND idType=1 AND ID != '".$newID."' AND !bDeleted ORDER BY dOrder DESC","Getting Highest Number",1);
			$nextdOrder = ($highest['dOrder']==''?'0':$highest['dOrder']) + 1;
		
			db_query("UPDATE ".$table." SET
					  bPShow = '".$_POST['bShow']."',
					  bShow = '".$_POST['bShow']."',
					  dOrder = '".$nextdOrder."',
					  dOrderChild = 0,
					  idParent = '".$newID."',
					  bParent = 1
					  WHERE ID = ".$newID."
					 ","Updating Parent Information");
			
		} else { // Child

			$parentinfo = db_query("SELECT bPShow, dOrder FROM NSOLearn WHERE bParent AND idType=1 AND ID=".$_POST['idParent'],"Getting Parent Information",1);
			$highest = db_query("SELECT dOrderChild FROM ".$table." WHERE !bParent AND idType=1 AND idParent='".$_POST['idParent']."' AND ID != '".$newID."' AND !bDeleted ORDER BY dOrderChild DESC","Getting Highest Number",1);
			$nextdOrderChild = ($highest['dOrderChild']==''?'0':$highest['dOrderChild']) + 1;
		
			db_query("UPDATE ".$table." SET
					  bShow = '".$_POST['bShow']."',
					  bPShow = '".$parentinfo['bPShow']."',
					  dOrder = '".$parentinfo['dOrder']."',
					  dOrderChild = '".$nextdOrderChild."',
					  idParent = '".$_POST['idParent']."',
					  bParent = 0
					  WHERE ID = ".$newID."
					 ","Updating Child Information");

		}
	
		$_SESSION['infoMessages'][] = "New Article: ".$_POST['chrTitle']." has been added successfully.";		
		header("Location: ". $_POST['moveTo']);
		die();
	} else {
		# if the database insertion failed, send them to the error page with a useful message
		errorPage('An error has occurred while trying to add this article.');
	}
?>