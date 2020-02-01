<?php
	$BF='../../';
	$auth_not_required = true;
	require($BF. '_lib.php');
	include($BF. "includes/meta.php");

	$query ="SELECT EmailMessages.*, chrStatus, RFLEmails.chrRFLName as chrType, RFLEmails.chrPHPPage,chrStoreNum
	FROM EmailMessages
	JOIN RetailStores ON EmailMessages.idSender=RetailStores.ID
	JOIN EmailStatus ON EmailMessages.idStatus=EmailStatus.ID
	JOIN RFLEmails ON EmailMessages.idType=RFLEmails.ID
	WHERE EmailMessages.ID = '".$_REQUEST['id']."'";	
	$result = database_query($query, "Get email", 1);
		
	// Grab any comments left this far
	$q = "SELECT EmailComments.*, Users.chrFirst, Users.chrLast
			FROM EmailComments
			LEFT JOIN Users ON EmailComments.idUser=Users.ID
			WHERE idEmailMessage=".$_REQUEST['id']."
			ORDER BY dtStamp";
	$comments = database_query($q,"Getting Comments");
	
	if (isset($_POST['txtMessage']) && $_POST['txtMessage'] != "") {  // User Required Field

		if (isset($_POST['idStatus']) && $_POST['idStatus'] != "") {
			$q ="Update EmailMessages SET
					idStatus=".$_POST['idStatus']."
					WHERE EmailMessages.ID = '".$_REQUEST['id']."'";	
			database_query($q, 'Update Status');
		}
		
		$q = "INSERT INTO EmailComments SET
				idEmailMessage=".$_POST['id'].",
				idUser='".$_SESSION['idUser']."',
				dtStamp=now(),
				txtMessage='".encode($_POST['txtMessage'])."'";
		database_query($q, 'Insert Comment');

	//dtn: THis is the mail Mime includes
		$er = error_reporting(0); 		//dtn: This is added in so that we don't get spammed with PEAR::isError() messages in our tail -f ..
		include_once('Mail.php');		//dtn: This is the main mail addon so that we can use the mime emailer
		include_once('Mail/mime.php');	//dtn: This is the actual mime part of the emailer	

		$subject = "Status Changed for: ".decode($result['chrType']); //dtn: Subject goes here.  Decode it from the DB to make sure we get rid of any apostrophies or quotes
		$msg = "This E-mail is to advise you that the Status has been Updated, Please follow the link to check the current status, and Comments.<br /><br />

<a href='http://storeops.apple.com/commtool/status.php?d=".base64_encode("id=".$_REQUEST['id']."&special=011101")."'>http://storeops.apple.com/commtool/status.php?d=".base64_encode("id=".$_REQUEST['id']."&special=011101")."</a><br /><br />

Thank you<br /><br />

Store Ops.";
			
		$query ="SELECT chrEmail
			FROM RFLContacts
			JOIN RFLDistros ON RFLDistros.idRFLContact = RFLContacts.ID
			JOIN RFLEmails ON RFLDistros.idRFLEmail = RFLEmails.ID
			WHERE chrPHPPage = '".$result['chrPHPPage']."' AND !RFLContacts.bDeleted";	
		$emails = database_query($query, 'Get distro list');
	
         $list='';
         while ($row = mysqli_fetch_assoc($emails))
          {
           		$list .=$row['chrEmail'].", ";
          }
       $list=substr($list, 0, strlen($list)-2);	
			
		//dtn: This is added so that the Pear module can differentiate between HTML emails and plain text emails.
		$crlf = "\n";
		mb_language("ja"); //dtn: Set to japanese by default.  This may need to change
		mb_internal_encoding('UTF-8'); //dtn: internally encode the whole message to UTF-8

		$mime = new Mail_mime($crlf);	

		$subject = mb_convert_encoding($subject, 'ISO-2022-JP',"AUTO"); //dtn: Convert the subject.
		$subject = mb_encode_mimeheader($subject); //dtn: Convert all the mime headers

		$hdrs = array('From'    => 'storeops@apple.com', 							//dtn: This is the email address we are sending ,
					  'Cc'      => ($result['chrCC'] != "" ? $result['chrCC'] : ''),  //dtn: Set up the CC if one exists
					  'Subject' => $subject											//dtn: Subject here
				  );
		$hdrs2 = array('From'    => 'storeops@apple.com', 							//dtn: This is the email address we are sending ,
					  'Subject' => $subject											//dtn: Subject here
				  );

		$mime->_build_params['text_encoding'] ='quoted-printable';
		$mime->_build_params['text_charset'] = "UTF-8";
		$mime->_build_params['html_charset'] = "UTF-8";

		$Message = $msg;			//dtn: this is the message that we built above

		$mime->setHTMLBody($Message);   //dtn: This sets the body of the html document in html.
					
		$body = $mime->get();
		$hdrs = $mime->headers($hdrs);
		$hdrs2 = $mime->headers($hdrs2);		
		$body = mb_convert_encoding($body, "ISO-2022-JP", "UTF-8");  //dtn: Convert the body to whatever character type.

		$mail =& Mail::factory('mail');
		$to = $result['chrStoreNum']."@apple.com, ";
//		$to = "jsummers@techitsolutions.com, ";
		$mailresult = $mail->send($to, $hdrs, $body);  //dtn:  send ( email address,  headers,  body).  You should only have to change the email address.
		$mailresult = $mail->send($list, $hdrs2, $body);  //dtn:  send ( email address,  headers,  body).  You should only have to change the email address.

		header('Location: index.php');
		die();
	}
	
	
	// Get Drop Down Items
	$q = "SELECT * FROM EmailStatus WHERE ID != " . $result['idStatus'] . " ORDER BY ID";
	$status = database_query($q, 'Get Status');

?>
	<script language="JavaScript" src="<?=$BF?>/includes/forms.js"></script>

	<script language="javascript">
		function error_check() {
			if(total != 0) { reset_errors(); } 

			var total=0;

			total += ErrorCheck('txtMessage', "You must enter a Comment.");

			if(total == 0) { document.getElementById('idForm').submit(); }
		}

	</script>
<?

	include($BF. "includes/top.php");
	include($BF. "commtool/includes/admin_nav.php");
?>

	<form enctype="multipart/form-data" action="" method="post" id="idForm">


		<!-- This is the end of the section that is needed for the rest of the pages to keep intact -->
            	  <div id = "errors"></div>
           <div name = "base" id = "base">
            	  <div><strong>Type: <?=$result['chrType']?></strong></div>
		    	  <div>Sent on <?=date('l, F j, Y - g:i a', strtotime($result['dtStamp']))?></div>
	              <div style="border:#CCCCCC solid 1px; margin-top:5px; padding:5px;"><?=nl2br(str_replace("|||"," ",str_replace(":::"," ",$result['txtMessage'])))?></div>
				  <div style="margin-top:10px; margin-bottom:10px;">Current Status: <strong><?=$result['chrStatus']?></strong></div>
				  <div><strong>Comments:</strong></div>
				
			<?	$count=0;
			 while ($row = mysqli_fetch_assoc($comments)) {
					$count++; ?>
					<div style="border:#CCCCCC solid 1px; margin-top:5px;">
						<div style="padding:3px;"><strong>Date:</strong> <?=date('l, F j, Y - g:i a', strtotime($row['dtStamp']))?><?=($row['idUser'] != "" ? " <strong>By:</strong> ".$row['chrFirst']." ".$row['chrLast'] : "" )?></div>
						<div style="padding:3px;"><?=nl2br($row['txtMessage'])?></div>
					</div> <?
				}
			if ($count==0) { ?>
					<div style="border:#CCCCCC solid 1px; margin-top:5px;">
						<div style="padding:3px; text-align:center;">There are no comments.</div>
					</div> <?
				}


if ($result['idStatus'] != 3) { ?>
				  <div style="margin-top:10px;"><strong>Add Comment:</strong> <span class='FormRequired'>(Required)</span></div>
				  <div><textarea id="txtMessage" name="txtMessage" rows="10" style="width:100%;" wrap="virtual"></textarea>
					</div>
				  <div style="margin-top:10px;"><strong>Change Status to:</strong></div>
				  <div><select class='FormField' id="idStatus" name='idStatus'>
				  			<option value=''>Select from List</option>
					<? while ($row = mysqli_fetch_assoc($status)) { ?>
							<option value='<?=$row['ID']?>'><?=$row['chrStatus']?></option>
					<?	} ?>
						</select>
					</div>
					<div><input style="margin-top:15px;" type="button" value="Update" onclick='error_check();' /></div>
					<input type="hidden" name="id" id="id" value="<?=$_REQUEST['id']?>" />
<?	}	?>

		</div>

  	</form>
<?
	include($BF. "commtool/includes/bottom.php");
?>
