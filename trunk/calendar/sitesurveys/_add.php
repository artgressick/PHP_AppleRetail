<?
	include_once($BF.'calendar/components/edit_functions.php');
	include($BF.'calendar/components/thumbnail_gen.php');
	
	$key = makekey();
	$q = "INSERT INTO NSOSS SET
		  chrKEY = '".$key."',
		  idUser = '".$_SESSION['idUser']."',
		  dtStamp = now(),
		  idSS = '".$info['idSS']."',
		  idNSO = '".$info['idNSO']."'
		 ";
	if(db_query($q,"Insert into NSOSS")) {
		global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
		$newID = mysqli_insert_id($mysqli_connection);

		$q = "";
		while($row = mysqli_fetch_assoc($results)) {
			# if it's just a basic sentance or paragraph
			if($row['idSSType'] == 1 || $row['idSSType'] == 2) {
				$q .= "('". $newID ."','". $row['ID'] ."','". encode($_POST[$row['ID']]) ."'),";
			} else if($row['idSSType'] == 3) {
				$tmp_options = explode('|||',$row['txtOptions']);
				$i = 0;
				$len = count($tmp_options);
				$ans = "";
				while($i < $len) {
					if($_POST[$row['ID']] != '' && $i == $_POST[$row['ID']]) { $ans = $tmp_options[$i]; break; }
					$i++;
				}
				$q .= "('". $newID ."','". $row['ID'] ."','". $ans ."'),";
			} else {
				$tmp_options = explode('|||',$row['txtOptions']);
				$i = 0;
				$len = count($tmp_options);
				$ans = "";
				while($i < $len) {
					if(in_array($i,$_POST[$row['ID']])) { $ans .= $tmp_options[$i].","; }
					$i++;
				}
				$q .= "('". $newID ."','". $row['ID'] ."','". substr($ans,0,-1) ."'),";
			} 
		}
	
		if(db_query("INSERT INTO SSAnswers (idNSOSS,idSSQuestion,txtAnswer) VALUES ".substr($q,0,-1),"insert answers")) {
	
			# Files section.
			$table = "NSOFiles";
			$i = 0;
			while ($i++ <  $_POST['intFiles']) {
				if($_FILES['chrFilesFile'.$i]['name'] != '') {
					$q = "INSERT INTO ". $table ." SET  
						chrKEY = '". makekey() ."',
						idUser = '". $_SESSION['idUser'] ."',
						idType = 1,
						idReference = '". $newID ."',
						dtCreated=now()
					";
			
				# if there database insertion is successful	
					if(db_query($q,"Insert into ". $table)) {
						global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
						$newID2 = mysqli_insert_id($mysqli_connection);
		
						$attName = strtolower(str_replace(" ","_",basename($_FILES['chrFilesFile'.$i]['name'])));  //dtn: Replace any spaces with underscores.
						
						$filetype = explode('/',$_FILES['chrFilesFile'.$i]['type']);
						$uploaddir = $BF . 'calendar/files/'; 	//dtn: Setting up the directory name for where things go
					
						if(in_array($filetype[1],array('jpg','png','jpeg'))) {
							
							$attName = str_replace('jpeg','jpg',$attName);
							
							$file = explode('.',$attName);
							$ext = $file[count($file) - 1];		
							
							$thumbnail = basename($attName,'.'.$ext).'_tn.'.$ext;
							$medium = basename($attName,'.'.$ext).'_medium.'.$ext;
	
							//dtn: Update the EmailMessages DB with the file attachment info.
							db_query("UPDATE ". $table ." SET 
								dbFileSize = '". $_FILES['chrFilesFile'.$i]['size'] ."',
								chrFile = '". $newID2 ."-". $attName ."',
								chrFileType = '". $_FILES['chrFilesFile'.$i]['type'] ."',
								chrThumbnail = '". $newID2 .'-'. $thumbnail ."',
								chrMedium = '". $newID2 .'-'. $medium ."'
								WHERE ID=". $newID2 ."	
							","insert attachment");
	
							$uploadfile = $uploaddir . $newID2 .'-'. $attName;
						
							move_uploaded_file($_FILES['chrFilesFile'.$i]['tmp_name'], $uploadfile);  //dtn: move the file to where it needs to go.
							
							createtn($newID2 .'-'. $attName, $uploaddir);
						
						} else {
						
							//dtn: Update the EmailMessages DB with the file attachment info.
							db_query("UPDATE ". $table ." SET 
								dbFileSize = '". $_FILES['chrFilesFile'.$i]['size'] ."',
								chrFile = '". $newID2 ."-". $attName ."',
								chrFileType = '". $_FILES['chrFilesFile'.$i]['type'] ."'
								WHERE ID=". $newID2 ."	
							","insert attachment");
				
							
							$uploadfile = $uploaddir . $newID2 .'-'. $attName;
						
							move_uploaded_file($_FILES['chrFilesFile'.$i]['tmp_name'], $uploadfile);  //dtn: move the file to where it needs to go.
						}
					}			
				}
			}
		} else {
			errorPage('An error has occurred while trying to save your Site Survey.');
		}
	} else {
		errorPage('An error has occurred while trying to save your Site Survey.');
	}
	
		include_once($BF.'calendar/includes/_emailer.php');
	
		$message1 = '<p>Hi All,</p>
					 <p>The site survey for '.$info['chrName'].' is now available.  Click the link below to view the survey.</p>
					 <p>Thanks,</p>
					 <p>NSO and Remodel Team</p>
					 <p><a href="'.$PROJECT_ADDRESS.'calendar/sitesurveys/view.php?key='.$key.'">'.$PROJECT_ADDRESS.'calendar/sitesurveys/view.php?key='.$key.'</a></p>';
		$subject1 = 'Site Survey for '.$info['chrName'].' '.$info['chrStoreNum'].' Complete';

		$teamLead = db_query("SELECT chrFirst, chrLast, chrEmail
							  FROM Users
							  JOIN NSOUserTitleAssoc AS NUTA ON Users.ID=NUTA.idUser
							  WHERE !Users.bDeleted AND NUTA.idUserTitle=2 AND NUTA.idNSO=".$info['idNSO'],"Get Team Lead",1);

		if($teamLead['chrEmail'] != '' ) {
			emailer($teamLead['chrFirst'].' '.$teamLead['chrLast'].' <'.$teamLead['chrEmail'].'>',$subject1,$message1);
		}
		
		
		$distro = db_query("SELECT CONCAT(NSONotifications.chrFirst,' ',NSONotifications.chrLast,'<',NSONotifications.chrEmail,'>') AS chrEmailAddress 
							FROM NSOSiteSurveyAssoc 
							JOIN NSONotifications ON NSONotifications.ID=NSOSiteSurveyAssoc.idNSONotification 
							WHERE !NSONotifications.bDeleted AND idNSO=".$info['idNSO'], "Getting Distro List");

		while($email = mysqli_fetch_assoc($distro)) {
			emailer($email['chrEmailAddress'],$subject1,$message1); 
		}


		
	header("Location: thanks.php");
	die();	
?>