<?
	include_once($BF.'calendar/components/add_functions.php');
	include($BF.'calendar/components/thumbnail_gen.php');
	$table = 'NSOGlobalFiles'; # added so not to forget to change the insert AND audit

	if($_POST['intFiles'] > 0 && count($_POST['nsoid']) > 0) {
		$i=1;
		$fileuploadcount = 0;
		while ($i <= $_POST['intFiles']) {
			if($_FILES['chrFilesFile'.$i]['name'] != '') {
				
				$idType = 2;  // Defaults to Document
				if($_POST['chrFilesType'.$i] == 'pic') { $idType = 1; } // Changes to Type Picture if that option was selected

				$q = "INSERT INTO ". $table ." SET 
						chrKEY = '". makekey() ."',
						idUser = '". $_SESSION['idUser'] ."',
						idType=".$idType."
				";
				# if there database insertion is successful	
				if(db_query($q,"Insert into ". $table)) {
					global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
					$newID = mysqli_insert_id($mysqli_connection);
				
					if($idType == 1) {  // If Picture
					
						$attName = strtolower(str_replace(" ","_",basename($_FILES['chrFilesFile'.$i]['name'])));  //dtn: Replace any spaces with underscores.
						$file = explode('.',$attName);
						$ext = $file[count($file) - 1];
						$thumbnail = basename($attName,'.'.$ext).'_tn.'.$ext;
						$medium = basename($attName,'.'.$ext).'_medium.'.$ext;

						//dtn: Update the DB with the file attachment info.
						db_query("UPDATE ". $table ." SET 
							dtCreated=now(),
							dbFileSize = '". $_FILES['chrFilesFile'.$i]['size'] ."',
							chrFile = 'g-". $newID ."-". $attName ."',
							chrFileType = '". $_FILES['chrFilesFile'.$i]['type'] ."',
							chrThumbnail = 'g-". $newID .'-'. $thumbnail ."',
							chrMedium = 'g-". $newID .'-'. $medium ."'
							WHERE ID=". $newID ."	
						","insert attachment");
				
						$uploaddir = $BF . 'calendar/nsopictures/'; 	//dtn: Setting up the directory name for where things go
						$uploadfile = $uploaddir .'g-'. $newID .'-'. $attName;
						
						move_uploaded_file($_FILES['chrFilesFile'.$i]['tmp_name'], $uploadfile);  //dtn: move the file to where it needs to go.
		
						createtn($newID .'-'. $attName, $uploaddir);
						
						$q = "INSERT INTO Audit SET 
							idType=1, 
							idRecord='". $newID ."',
							txtNewValue='g-". $newID ."-". $attName ."',
							dtDateTime=now(),
							chrTableName='". $table ."',
							idUser='". $_SESSION['idUser'] ."'
						";
						db_query($q,"Insert audit");
						//End the code for History Insert 

							
						
						$q = "INSERT INTO CalendarFiles (chrKEY,idUser,idNSO,idCalendarFileType,dtCreated,chrFileTitle,txtFileDescription,dbFileSize,chrCalendarFile,chrFileType,chrThumbnail,chrMedium,idNSOFileGroup) VALUES ";
						$q2 = "";
						foreach($_POST['nsoid'] AS $k => $v) {
							$q2 .= "('".makekey()."','".$_SESSION['idUser']."','".$v."','".$idType."',NOW(),'".encode($_POST['chrFilesTitle'.$i])."','".encode($_POST['txtFilesDesc'.$i])."','".$_FILES['chrFilesFile'.$i]['size']."','g-". $newID ."-". $attName ."','".$_FILES['chrFilesFile'.$i]['type']."','g-". $newID .'-'. $thumbnail ."','g-". $newID .'-'. $medium ."','".$_POST['chrFilesGroup'.$i]."'),";
							db_query("UPDATE NSOs SET dtUpdated=now() WHERE ID=".$v,"Updating Timestamp for NSO");
						}
						
						if($q2 != '') {
							db_query($q.substr($q2,0,-1),"Inserting Into NSOs");
						}

					} else { // Else Document
					
						$attName = strtolower(str_replace(" ","_",basename($_FILES['chrFilesFile'.$i]['name'])));  //dtn: Replace any spaces with underscores.

						//dtn: Update the DB with the file attachment info.
						db_query("UPDATE ". $table ." SET 
							dbFileSize = '". $_FILES['chrFilesFile'.$i]['size'] ."',
							chrFile = 'g-". $newID ."-". $attName ."',
							chrFileType = '". $_FILES['chrFilesFile'.$i]['type'] ."'
							WHERE ID=". $newID ."	
						","insert attachment");
				
						$uploaddir = $BF . 'calendar/nsodocuments/'; 	//dtn: Setting up the directory name for where things go
						$uploadfile = $uploaddir .'g-'. $newID .'-'. $attName;
						
						move_uploaded_file($_FILES['chrFilesFile'.$i]['tmp_name'], $uploadfile);  //dtn: move the file to where it needs to go.
		
						$q = "INSERT INTO Audit SET 
							idType=1, 
							idRecord='". $newID ."',
							txtNewValue='g-". $newID ."-". $attName ."',
							dtDateTime=now(),
							chrTableName='". $table ."',
							idUser='". $_SESSION['idUser'] ."'
						";
						db_query($q,"Insert audit");
						//End the code for History Insert 

						$q = "INSERT INTO CalendarFiles (chrKEY,idUser,idNSO,idCalendarFileType,dtCreated,chrFileTitle,txtFileDescription,dbFileSize,chrCalendarFile,chrFileType,idNSOFileGroup) VALUES ";
						$q2 = "";
						foreach($_POST['nsoid'] AS $k => $v) {
							$q2 .= "('".makekey()."','".$_SESSION['idUser']."','".$v."','".$idType."',NOW(),'".encode($_POST['chrFilesTitle'.$i])."','".encode($_POST['txtFilesDesc'.$i])."','".$_FILES['chrFilesFile'.$i]['size']."','g-". $newID ."-". $attName ."','".$_FILES['chrFilesFile'.$i]['type']."','".$_POST['chrFilesGroup'.$i]."'),";
							db_query("UPDATE NSOs SET dtUpdated=now() WHERE ID=".$v,"Updating Timestamp for NSO");
						}
						
						if($q2 != '') {
							db_query($q.substr($q2,0,-1),"Inserting Into NSOs");
						}
					
					}
				
					$fileuploadcount++;  // Keep a count of files that where uploaded
				} else {
					# if the database insertion failed, send them to the error page with a useful message
					errorPage('An error has occurred while trying to add this file type.');
				}
			
			}
		 $i++;
		}
		$_SESSION['infoMessages'][] = $fileuploadcount." file(s) has been uploaded successfully.";
		header("Location: index.php");
		die();
	} else {
		$_SESSION['infoMessages'][] = "No Files have been uploaded.";
		header("Location: index.php");
		die();
	}
?>