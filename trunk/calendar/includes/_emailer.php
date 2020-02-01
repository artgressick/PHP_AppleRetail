<?php
	function emailer($to,$subject,$message,$from='',$attachment='') {
		if($from == '') {
			$from = "NSO and Remodel Team <nso_and_remodel_team@group.apple.com>";
		}
		// This is added so that the Pear module can differentiate between HTML emails and plain text emails.
		//dtn: This is added so that the Pear module can differentiate between HTML emails and plain text emails.
		$er = error_reporting(0); 		//dtn: This is added in so that we don't get spammed with PEAR::isError() messages in our tail -f ..
		include_once('Mail.php');
		include_once('Mail/mime.php');
	
		$crlf = "\n";
		mb_language('en');
		mb_internal_encoding('UTF-8');
		
		$mime = new Mail_mime($crlf);	
	
		$subject = decode($subject);
		$subject = mb_convert_encoding($subject, 'UTF-8',"AUTO");
		$subject = mb_encode_mimeheader($subject);
	
		$hdrs = array('From'    => $from,
					  'Subject' => $subject
				  );
		
		$mime->_build_params['text_encoding'] ='quoted-printable';
		$mime->_build_params['text_charset'] = "UTF-8";
		$mime->_build_params['html_charset'] = "UTF-8";
	
		$Message = decode($message);
			
		$mime->setHTMLBody($Message);
		if($attachment != '') { $mime->addAttachment($attachment); }
			
		$body = $mime->get();
		$hdrs = $mime->headers($hdrs);
		$body = mb_convert_encoding($body, "UTF-8", "UTF-8"); 
		
		$mail =& Mail::factory('mail');
		if($mail->send(decode($to), $hdrs, $body)) { return true; } else { return false; }
//		if($mail->send("jsummers@techitsolutions.com", $hdrs, $body)) { return true; } else { return false; } // Testing Address
	}
?>