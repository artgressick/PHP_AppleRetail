<?php
	// Possible id type bug, check to see if post did not convert
	if(!is_numeric($_POST['id']) || $_POST['id'] == "0") { 
	
		$tmpmsg = "Store Ops is reporting a bad post value for idType in the file: bottom_email.php. 
POST[id]: ".$_POST['id']." and REQUEST[id]: ".$_REQUEST['id']."

To insert this entry we have used the REQUEST value as the backup. Here is the rest of the information that may be useful:

MSG Inserted: ".$msg."

Store ID: ".$_SESSION['idStore'];

	mail("programmers@techitsolutions.com", "BUGFIX STORE OPS ALERT!!! 0 or Blank Post Value Reported", $tmpmsg);

		$_POST['id'] = $_REQUEST['id'];

	}
	
	//dtn: Do the insert into the DB
	$q = "INSERT INTO EmailMessages SET 
		idSender='". $_SESSION['idStore'] ."',
		idType='". $_POST['id'] ."',
		txtMessage='". $msg ."',
		dtStamp=now(),
		chrCC = '".$_POST['chrCC']."'
		";
		
	$result = database_query($q,"Insert into email messages");

	//dtn: This whole section is used to check for attachment.  It will rename and move the file, and update the EmailMessages with the attachment info.

	global $mysqli_connection;
	$newID = mysqli_insert_id($mysqli_connection);  // Getting the last inserted ID, used later to update the Email Messages.

$msg .= "<hr><br />
If this issue has been resolved or you would like to update its status please click the link below or copy and paste it into your browser.<br /><br />

<a href='http://storeops.apple.com/commtool/status.php?d=".base64_encode("id=".$newID."&special=011101")."'>http://storeops.apple.com/commtool/status.php?d=".base64_encode("id=".$newID."&special=011101")."</a><br /><br />

Thank you<br /><br />

Store Ops.";

	//Remove the ||| and the ::: from the message before sending.
	$msg = str_replace("|||","",$msg);
	$msg = str_replace(":::","",$msg);	

	if(is_uploaded_file($_FILES['chrAttachment']['tmp_name'])) {
		$attName = str_replace(" ","_",basename($_FILES['chrAttachment']['name']));  //dtn: Replace any spaces with underscores.
		
		//dtn: Update the EmailMessages DB with the file attachment info.
		database_query("UPDATE EmailMessages SET 
			intAttachmentSize=". $_FILES['chrAttachment']['size'] .",
			chrAttachmentName='". $newID ."-". $attName ."',
			chrAttachmentType='". $_FILES['chrAttachment']['type'] ."'
			WHERE ID=". $newID ."
			","insert attachment");

		$uploaddir = $BF . 'commtool/emailattachments/'; 	//dtn: Setting up the directory name for where things go
		$uploadfile = $uploaddir . $newID .'-'. $attName;
		
		$isattachment = true;
		
		move_uploaded_file($_FILES['chrAttachment']['tmp_name'], $uploadfile);  //dtn: move the file to where it needs to go.
	} else { $isattachment = false; }

	
	//dtn: If the insert into the DB worked, send the email.
	if(isset($newID) && $newID != "") {
		
		//dtn: This is added so that the Pear module can differentiate between HTML emails and plain text emails.
		$crlf = "\n";
		mb_language("ja"); //dtn: Set to japanese by default.  This may need to change
		mb_internal_encoding('UTF-8'); //dtn: internally encode the whole message to UTF-8

		$mime = new Mail_mime($crlf);	

		$subject = mb_convert_encoding($subject, 'ISO-2022-JP',"AUTO"); //dtn: Convert the subject.
		$subject = mb_encode_mimeheader($subject); //dtn: Convert all the mime headers

		$hdrs = array('From'    => (isset($_SESSION['chrFromEmail']) && $_SESSION['chrFromEmail'] != "" ? $_SESSION['chrFromEmail'] : 'storeops@apple.com'), 							//dtn: This is the email address we are sending ,
					  'Cc'      => ($_POST['chrCC'] != "" ? $_POST['chrCC'] : ''),  //dtn: Set up the CC if one exists
					  'Subject' => $subject											//dtn: Subject here
				  );

		$mime->_build_params['text_encoding'] ='quoted-printable';
		$mime->_build_params['text_charset'] = "UTF-8";
		$mime->_build_params['html_charset'] = "UTF-8";

		$Message = $msg;			//dtn: this is the message that we built above

		$mime->setHTMLBody($Message);   //dtn: This sets the body of the html document in html.
		if($isattachment == true) { $mime->addAttachment($uploadfile); } //dtn:  This is the attachment that was added above... assuming there was an attachment.
					
		$body = $mime->get();
		$hdrs = $mime->headers($hdrs);
		$body = mb_convert_encoding($body, "ISO-2022-JP", "UTF-8");  //dtn: Convert the body to whatever character type.

		$mail =& Mail::factory('mail');
		$to = $_SESSION['chrStoreNum']."@apple.com, ";
//		$to = "jsummers@techitsolutions.com";
		$mailresult = $mail->send($to, $hdrs, $body);  //dtn:  send ( email address,  headers,  body).  You should only have to change the email address.
		
				//dtn: This is added so that the Pear module can differentiate between HTML emails and plain text emails.
		$crlf = "\n";
		mb_language("ja"); //dtn: Set to japanese by default.  This may need to change
		mb_internal_encoding('UTF-8'); //dtn: internally encode the whole message to UTF-8

		$mime = new Mail_mime($crlf);	

		$subject = mb_convert_encoding($subject, 'ISO-2022-JP',"AUTO"); //dtn: Convert the subject.
		$subject = mb_encode_mimeheader($subject); //dtn: Convert all the mime headers

		$hdrs = array('From'    => (isset($_SESSION['chrFromEmail']) && $_SESSION['chrFromEmail'] != "" ? $_SESSION['chrFromEmail'] : 'storeops@apple.com'), 							//dtn: This is the email address we are sending ,
					  'Subject' => $subject											//dtn: Subject here
				  );

		$mime->_build_params['text_encoding'] ='quoted-printable';
		$mime->_build_params['text_charset'] = "UTF-8";
		$mime->_build_params['html_charset'] = "UTF-8";

		$Message = $msg;			//dtn: this is the message that we built above

		$mime->setHTMLBody($Message);   //dtn: This sets the body of the html document in html.
		if($isattachment == true) { $mime->addAttachment($uploadfile); } //dtn:  This is the attachment that was added above... assuming there was an attachment.
					
		$body = $mime->get();
		$hdrs = $mime->headers($hdrs);
		$body = mb_convert_encoding($body, "ISO-2022-JP", "UTF-8");  //dtn: Convert the body to whatever character type.

		$mail =& Mail::factory('mail');
		$mailresult = $mail->send($list, $hdrs, $body);  //dtn:  send ( email address,  headers,  body).  You should only have to change the email address.	
//		$mailresult = $mail->send("jsummers@techitsolutions.com", $hdrs, $body);  //dtn:  send ( email address,  headers,  body).  You should only have to change the email address.	
		
		if($mailresult) {
			$_SESSION['mailNotice'] = "Your E-mail was Sent Successfully.";
		} else {
			$_SESSION['mailNotice'] = "Your E-mail Failed to Send, Please Re-Send.";
		}
	
		header("Location: ". $pgnam."?id=".$_REQUEST['id']);
		die();
	

	}