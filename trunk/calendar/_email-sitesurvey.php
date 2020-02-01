#!/usr/local/php5/bin/php
<?
	$BF = "/Library/WebServer/appleretail/";
//	$BF = "../";
	require($BF.'calendar/_lib.php');
	include_once($BF.'calendar/includes/_emailer.php');
	//Begin English
	
	$q = "SELECT N.ID, N.chrKEY, S.chrName, NUTA.chrRecord AS chrEmail, (SELECT NU.chrRecord FROM NSOUserTitleAssoc AS NU WHERE NU.idNSO=N.ID AND NU.idUserTitle=1) AS chrName
			FROM NSOs AS N
			JOIN RetailStores AS S ON N.idStore=S.ID
			JOIN NSOUserTitleAssoc AS NUTA ON NUTA.idNSO=N.ID AND NUTA.idUserTitle=16
			WHERE !N.bDeleted AND NUTA.chrRecord != '' AND (SELECT COUNT(SS.ID) FROM NSOSS AS SS WHERE SS.idNSO=N.ID) = 0 AND N.dBegin != '0000-00-00' AND N.dBegin > DATE_FORMAT(NOW(),'%Y-%m-%d') AND dBegin <= DATE_FORMAT(adddate(NOW(),42),'%Y-%m-%d') AND (dSSReminder = '0000-00-00' OR dSSReminder = DATE_FORMAT(adddate(NOW(),-3),'%Y-%m-%d')) AND N.idNSOType IN (1,11)
	";
		
	$results = db_query($q,"Gathering Records");
	
	//Process Each Row For English Rows
	while ($row = mysqli_fetch_assoc($results)) {

		$subject = 'NSO Site Survey Request';

		$message = '<p>Hello,</p>
					<p>Your New Store Opening is approaching.  To get things started, please complete the site survey for your location by clicking the link below.</p>
					<p>Feel free to contact your NSO Team Lead with any questions or concerns.<br />					
					Please note, you will be sent a reminder email every 3 days until the site survey is submitted.</p>
					<p>Thank you,</p>
					<p>NSO and Remodel Team</p>
					<p><a href="'.$PROJECT_ADDRESS.'calendar/sitesurveys/add.php?key='.$row['chrKEY'].'">'.$PROJECT_ADDRESS.'calendar/sitesurveys/add.php?key='.$row['chrKEY'].'</a></p>
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

		db_query("UPDATE NSOs SET dtUpdated='".$row['dtUpdated']."', dSSReminder=now() WHERE ID=".$row['ID'],"Update reminder Date");
		
		echo "E-mail sent to ".$row['chrName'].". ";
	}
	
?>