#!/usr/local/php5/bin/php
<?
	$BF = "/Library/WebServer/appleretail/";
//	$BF = "../";
	require($BF.'calendar/_lib.php');
	include_once($BF.'calendar/includes/_emailer.php');
	//Begin English
	
	$q = "SELECT N.ID, N.chrKEY, N.idNSOType, S.chrName, NUTA.chrRecord AS chrEmail, (SELECT NU.chrRecord FROM NSOUserTitleAssoc AS NU WHERE NU.idNSO=N.ID AND NU.idUserTitle=1) AS chrName, dtUpdated
			FROM NSOs AS N
			JOIN RetailStores AS S ON N.idStore=S.ID
			JOIN NSOUserTitleAssoc AS NUTA ON NUTA.idNSO=N.ID AND NUTA.idUserTitle=16
			WHERE N.ID != 45 AND !N.bDeleted AND NUTA.chrRecord != '' AND (SELECT COUNT(Evals.ID) FROM NSOEvals AS Evals WHERE !Evals.bDeleted AND Evals.idNSO=N.ID) = 0 AND N.dEnd != '0000-00-00' AND dEnd <= DATE_FORMAT(adddate(NOW(),-7),'%Y-%m-%d') AND (dEvalReminder = '0000-00-00' OR dEvalReminder = DATE_FORMAT(adddate(NOW(),-3),'%Y-%m-%d'))
	";
		
	$results = db_query($q,"Gathering Records");
	
	//Process Each Row For English Rows
	while ($row = mysqli_fetch_assoc($results)) {

		$subject = ($row['idNSOType'] == 1 || $row['idNSOType'] == 11 ? 'NSO' : 'Remodel').' Evaluation Request';

		$message = '<p>Hello,</p>
					<p>Now that your '.($row['idNSOType'] == 1 || $row['idNSOType'] == 11  ? 'New Store Opening' : 'Remodel').' is complete, we would like to hear from you.</p>
					<p>Please click the link below to complete an evaluation.<br />
					Note, you will be sent a reminder email every 3 days until an evaluation is submitted.</p>
					<p>Thank you,</p>
					<p>NSO and Remodel Team</p>
					<p><a href="'.$PROJECT_ADDRESS.'calendar/evals/add.php?key='.$row['chrKEY'].'">'.$PROJECT_ADDRESS.'calendar/evals/add.php?key='.$row['chrKEY'].'</a></p>
		';

		$teamLead = db_query("SELECT chrFirst, chrLast, chrEmail
							  FROM Users
							  JOIN NSOUserTitleAssoc AS NUTA ON Users.ID=NUTA.idUser
							  WHERE !Users.bDeleted AND NUTA.idUserTitle=2 AND NUTA.idNSO=".$row['ID'],"Get Team Lead",1);

		if($teamLead['chrEmail'] != '' ) {
			emailer($teamLead['chrFirst'].' '.$teamLead['chrLast'].' <'.$teamLead['chrEmail'].'>',$subject,$message);
		}
		
		emailer(($row['chrName'] != '' ? $row['chrName'] : 'Store Manager').' <'.$row['chrEmail'].'>',$subject,$message);
//		emailer('jsummers@techitsolutions.com',$subject,$message);
		echo "E-mail sent to ".$row['chrName'].". ";
		
		db_query("UPDATE NSOs SET dtUpdated='".$row['dtUpdated']."', dEvalReminder=now() WHERE ID=".$row['ID'],"Update reminder Date");
	}
	
?>