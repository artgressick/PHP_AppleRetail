#!/usr/local/php5/bin/php
<?
	$BF = "/Library/WebServer/appleretail/";
//	$BF = "../";
	require($BF.'calendar/_lib.php');
	include_once($BF.'calendar/includes/_emailer.php');

	
	$results = db_query("SELECT DCQ.idNSO, DCQ.chrField, DCQ.dOrig, DCQ.dNew, DCQ.chrReason, S.chrName
						 FROM DateChangesQue AS DCQ
						 JOIN NSOs ON DCQ.idNSO = NSOs.ID
						 JOIN RetailStores AS S ON NSOs.idStore = S.ID
						 WHERE !NSOs.bDeleted
						", "Get Date Changes");
	if(mysqli_num_rows($results) > 0) {
		$datechanges = array();
		while($row = mysqli_fetch_assoc($results)) {
			// Convert Field
			if($row['chrField'] == 'dBegin') {
				$field = 'Project Start Date';
			} else if($row['chrField'] == 'dDate2') {
				$field = 'SWS Opens';
			} else if($row['chrField'] == 'dDate3') {
				$field = 'Last Day SWS Open';
			} else if($row['chrField'] == 'dDate4') {
				$field = 'Store Set Up';
			} else if($row['chrField'] == 'dEnd') {
				$field = 'Store Opens';
			}
			//Convert Original Entry
			if($row['dOrig'] != 'TBD' && $row['dOrig'] != 'Canceled') {
				if($row['dOrig'] != '' && $row['dOrig'] != '0000-00-00' && $row['dOrig'] != '1969-12-31') { $row['dOrig'] = date('m/d/y',strtotime($row['dOrig'])); }
				else { $row['dOrig'] = 'N/A'; }
			}
			//Convert New Entry
			if($row['dNew'] != 'TBD' && $row['dNew'] != 'Canceled') {
				if($row['dNew'] != '' && $row['dNew'] != '0000-00-00' && $row['dNew'] != '1969-12-31') { $row['dNew'] = date('m/d/y',strtotime($row['dNew'])); }
				else { $row['dNew'] = 'N/A'; }
			}
			
			$datechanges[$row['idNSO']][] = array(
												  'Store'=>$row['chrName'],
												  'Field'=>$field,
												  'Orig'=>$row['dOrig'],
												  'New'=>$row['dNew'],
												  'Reason'=>$row['chrReason']
												 );	
		}

	$emails = array();
	
	// Landing Page E-mails
	foreach($datechanges AS $idNSO => $data) {

		//Normal Distro List
		$results = db_query("SELECT NSONotifications.chrFirst,NSONotifications.chrLast,NSONotifications.chrEmail 
							FROM NSONotificationAssoc 
							JOIN NSONotifications ON NSONotifications.ID=NSONotificationAssoc.idNSONotification 
							WHERE !NSONotifications.bDeleted AND idNSO=".$idNSO, "Getting Normal Distro List");
		
		while($row = mysqli_fetch_assoc($results)) {
			if(!isset($emails[$row['chrEmail']])) {
				$emails[$row['chrEmail']]['Name'] = $row['chrFirst'].' '.$row['chrLast'];
				$emails[$row['chrEmail']]['NSOs'][] = $idNSO; 
			} else if(!in_array($idNSO, $emails[$row['chrEmail']]['NSOs'])) {
				$emails[$row['chrEmail']]['NSOs'][] = $idNSO; 
			}
		}
	}

	// Lets start making the e-mails per e-mail address

	foreach($emails AS $chrEmail => $info) {


		//Loop through first to see if there is need for the Reason Column
		$reasonscol = false;
		foreach($info['NSOs'] as $k => $idNSO) {
			foreach($datechanges[$idNSO] as $i => $data) {
				if($data['Reason'] != '') {
					$reasonscol = true;
				}
			}
		}

		if($info['Name'] != '') {
			$to = $info['Name'].' <'.$chrEmail.'>';
		} else {
			$to = $chrEmail;
		}

		$subject = "**HOT** NSO/Remodel Date Change(s)";
		
		$message = '
<p>Hi All,</p>
<p>The following updates have been made to one or more stores on the NSO/Remodel Calendar.</p>
<p>The below stores have been affected:</p><br />
<table style="" cellpadding="0" cellspacing="0">
	<tr>
		<td style="white-space:nowrap;font-weight:bold;width:150px;text-decoration:underline;padding:0 5px;">Store</td>
		<td style="white-space:nowrap;font-weight:bold;width:100px;text-decoration:underline;padding:0 5px;">Change</td>
		<td align="center" style="white-space:nowrap;font-weight:bold;width:100px;text-decoration:underline;padding:0 5px;">Previous Date</td>
		<td align="center" style="white-space:nowrap;font-weight:bold;width:100px;text-decoration:underline;padding:0 5px;">New Date</td>'.
		($reasonscol ? '<td style="white-space:nowrap;font-weight:bold;text-decoration:underline;padding:0 5px;">Notes</td>' : '').'
	</tr>
';
		$oldStore = '';
		foreach($info['NSOs'] as $k => $idNSO) {
			foreach($datechanges[$idNSO] as $i => $data) {
				if($oldStore != $data['Store']) {
					$paddingtop = 10;
				} else { 
					$paddingtop = 2;
				}
				$oldStore = $data['Store'];
				$message .= '
	<tr>
		<td style="white-space:nowrap;padding:0 5px; padding-top:'.$paddingtop.';">'.decode($data['Store']).'</td>
		<td style="white-space:nowrap;padding:0 5px; padding-top:'.$paddingtop.';">'.decode($data['Field']).'</td>
		<td align="center" style="white-space:nowrap;padding:0 5px; padding-top:'.$paddingtop.';">'.decode($data['Orig']).'</td>
		<td align="center" style="white-space:nowrap;padding:0 5px; padding-top:'.$paddingtop.';">'.decode($data['New']).'</td>'.
		($reasonscol ? '<td style="white-space:nowrap;padding:0 5px; padding-top:'.$paddingtop.';">'.decode($data['Reason']).'</td>' :'').'
	</td>	
';
			}
		}
		
		$message .= '
</table><br />
<p>The store teams have been notified of the change. Please click the following link to view the website for more information.</p>
<p>Thank you,</p>
<p>NSO and Remodel Team</p>
<p><a href="http://storeops.apple.com/calendar/nso/">http://storeops.apple.com/calendar/nso/</a></p>
';

/*
		//Send the E-mail
		if(emailer($to,$subject,$message)) { 
//		if(emailer('jsummers@techitsolutions.com',$subject,$message)) {
			echo "Email sent to ".$to." successfully./n";
		} else {
			echo "An Error was encountered while sending this e-mail to ".$to."./n";
		}
*/
	}
		$tmp = db_query("DELETE FROM DateChangesQue","Removing Entries");
	}
?>