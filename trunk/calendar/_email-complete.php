#!/usr/local/php5/bin/php
<?
	$BF = "/Library/WebServer/appleretail/";
//	$BF = "../";
	require($BF.'calendar/_lib.php');
	include_once($BF.'calendar/includes/_emailer.php');
	//Begin English
	
	$q = "SELECT N.ID, N.chrKEY, RS.chrName, RS.chrStoreNum, N.dtUpdated, N.idNSOType
		  FROM NSOs AS N
		  JOIN RetailStores AS RS ON N.idStore=RS.ID
		  JOIN NSOTaskAssoc ON NSOTaskAssoc.idNSO=N.ID
		  WHERE !N.bDeleted AND N.ID IN (SELECT TA.idNSO FROM NSOTaskAssoc AS TA JOIN NSOTasks AS NT ON TA.idNSOTask=NT.ID WHERE !NT.bDeleted AND NT.chrNSOTask='Send Complete Notification E-mail' AND TA.intNSOTaskStatus='100' GROUP BY TA.idNSO) AND !N.bCompleteSent
		  GROUP BY N.ID
	";
		
	$results = db_query($q,"Gathering Records");
	
	//Process Each Row For English Rows
	while ($row = mysqli_fetch_assoc($results)) {

		$subject = ($row['idNSOType'] == 1 || $row['idNSOType'] == 11 ? 'NSO':'Remodel').' for '.$row['chrName'].' '.$row['chrStoreNum'].' Complete';

		$message = '<p>Hi All,</p>
					<p>The '.($row['idNSOType'] == 1 || $row['idNSOType'] == 11 ? 'NSO':'Remodel').' for '.$row['chrName'].' is now complete.  Click the following link for more information.</p>
					<p>Thank you,</p>
					<p>NSO and Remodel Team</p>
					<p><a href="'.$PROJECT_ADDRESS.'calendar/nso/landing.php?key='.$row['chrKEY'].'">'.$PROJECT_ADDRESS.'calendar/nso/landing.php?key='.$row['chrKEY'].'</a></p>
		';
	
		//To NSO Team
		emailer('nso_and_remodel_team@group.apple.com',$subject,$message);

		//landing page entered people
		$people = db_query("SELECT chrRecord, idUserTitle FROM NSOUserTitleAssoc WHERE idNSO='".$row['ID']."' AND idUserTitle IN (16,17,18,19,20,21)","Get Landing Page E-mails");
		while($row2 = mysqli_fetch_assoc($people)) {
			if($row2['idUserTitle'] == 16) {
				$idName = 1;
			} else if($row2['idUserTitle'] == 17) {
				$idName = 9;
			} else if($row2['idUserTitle'] == 18) {
				$idName = 10;
			} else if($row2['idUserTitle'] == 19) {
				$idName = 11;
			} else if($row2['idUserTitle'] == 20) {
				$idName = 12;
			} else if($row2['idUserTitle'] == 21) {
				$idName = 13;
			}
		
			$name = db_query("SELECT chrRecord FROM NSOUserTitleAssoc WHERE idNSO='".$row['ID']."' AND idUserTitle=".$idName,"Get Name",1);
			if($row2['chrRecord'] != '') {
				if($name['chrRecord'] != '') {
					emailer($name['chrRecord'].' <'.$row2['chrRecord'].'>',$subject,$message);
				} else {
					emailer($row2['chrRecord'],$subject,$message);
				}
			}
		}
		//Drop Down people for Landing Page
		$people = db_query("SELECT Users.chrFirst,Users.chrLast, Users.chrEmail
			FROM UserTitles
			JOIN NSOUserTitleAssoc ON NSOUserTitleAssoc.idUserTitle=UserTitles.ID AND idNSO='".$row['ID']."'
			JOIN Users ON Users.ID=NSOUserTitleAssoc.idUser
			WHERE !UserTitles.bDeleted AND UserTitles.ID NOT IN (16,17,18,19,20,21,1,9,10,11,12,13)
			ORDER BY dOrder
		","Getting Users and Tasks");

		while($row2 = mysqli_fetch_assoc($people)) {
			emailer($row2['chrFirst'].' '.$row2['chrLast'].' <'.$row2['chrEmail'].'>',$subject,$message);
//			emailer('jsummers@techitsolutions.com',$subject,$message);
		}
		
		db_query("UPDATE NSOs SET bCompleteSent=1, dtUpdated='".$row['dtUpdated']."' WHERE ID=".$row['ID'],"Update indicator");
		echo "E-mails sent for ".$row['chrName'].". ";
	}
	
?>