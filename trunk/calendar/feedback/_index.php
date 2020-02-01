<?php

	include_once($BF.'calendar/components/add_functions.php');
	$table = 'NSOFeedback'; # added so not to forget to change the insert AND audit
	
	$key = makekey();
	$q = "INSERT INTO ". $table ." SET
			chrKey='". $key ."',
			idUser='". $_SESSION['idUser'] ."',
			txtFeedback='". encode($_POST['txtFeedback']) ."',
			dtCreated=now()
		";		
	if(db_query($q,'inserting feedback')) {
		include_once($BF.'calendar/includes/_emailer.php');
		
		$message1 = '<p>Feedback has been provided on the NSO/Remodel website for you review.  Click the link below to view the evaluation.</p>
					 <p><a href="'.$PROJECT_ADDRESS.'calendar/admin/feedback/view.php?key='.$key.'">'.$PROJECT_ADDRESS.'calendar/admin/feedback/view.php?key='.$key.'</a></p>';
		$subject1 = 'NSO/Remodel Website Feedback';
		
		$to = 'NSO and Remodel Team <nso_and_remodel_team@group.apple.com>';
		
		emailer($to,$subject1,$message1);
	}

	header("Location: thanks.php");
	die();
?>