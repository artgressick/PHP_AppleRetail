#!/usr/local/php5/bin/php
<?
	$BF = "/Library/WebServer/appleretail/";
//	$BF = "../";
	require($BF.'calendar/_lib.php');
	include_once($BF.'calendar/includes/_emailer.php');
	//Begin English
	
	$q = "SELECT U.ID, GROUP_CONCAT(CONCAT(RS.chrName,':::',NT.chrNSOTask,':::',NTA.intDateOffset,':::',N.dBegin) ORDER BY DATE_FORMAT(adddate(N.dBegin,NTA.intDateOffset),'%Y-%m-%d'), RS.chrName SEPARATOR '|||') AS txtTasks, 
		 	U.chrFirst, U.chrLast, U.chrEmail
		  FROM NSOs AS N
 		  JOIN NSOTaskAssoc AS NTA ON NTA.idNSO=N.ID  
		  JOIN NSOTasks AS NT ON NT.ID = NTA.idNSOTask
		  JOIN NSOUserTitleAssoc AS NUTA ON N.ID=NUTA.idNSO AND idUserTitle=2
		  JOIN Users AS U ON NUTA.idUser=U.ID AND !U.bDeleted
		  JOIN RetailStores AS RS ON N.idStore=RS.ID
		  JOIN NSOTypes AS T ON T.ID=N.idNSOType
		  WHERE !N.bDeleted AND !NT.bDeleted AND NTA.intNSOTaskStatus != 100 AND (DATE_FORMAT(adddate(N.dBegin,NTA.intDateOffset),'%Y-%m-%d') BETWEEN DATE_FORMAT(NOW(),'%Y-%m-%d') AND DATE_FORMAT(adddate(NOW(),7),'%Y-%m-%d') || DATE_FORMAT(adddate(N.dBegin,NTA.intDateOffset),'%Y-%m-%d') < DATE_FORMAT(NOW(),'%Y-%m-%d'))
		  GROUP BY U.ID
		  ORDER BY U.ID
	";
		
	$results = db_query($q,"Gathering Records");
	
	//Process Each Row For English Rows
	while ($row = mysqli_fetch_assoc($results)) {
		$thisweek = "";
		$overdue = "";
		
		$tasks = explode('|||',$row['txtTasks']);
		foreach($tasks AS $k => $v) {
			$task = explode(':::',$v);
			$offset = $task[2];
			if($task[1] != 'NSO Setup') {
				$dow = date('N', strtotime($task[3] . ' +'.$offset.' Days'));
				if($dow == 6) { // Saturday
					$offset = $offset - 1;
				} else if ($dow == 7) { // Sunday
					$offset = $offset - 2;
				}
			}
			$TaskDate = date('Y-m-d', strtotime($task[3] . ' +'.$offset.' Days'));
			
			if($TaskDate >= date('Y-m-d')) {
				$thisweek .= "
		<tr>
			<td>".$task[0]."</td>
			<td>".$task[1]."</td>
		</tr>
							";
			} else {
				$overdue .= "
		<tr>
			<td>".$task[0]."</td>
			<td>".$task[1]."</td>
		</tr>
							";
			}
		}
		
		$message1 = '<p>Good morning '.$row['chrFirst'].' '.$row['chrLast'].',</p>';

		if($thisweek != '') {
			$message1 .= '
	<p>Here are your upcoming tasks for this week:<br />
		<table style="" cellpadding="5">
			<tr>
				<td style="font-weight:bold;width:150px;text-decoration:underline;">Store</td>
				<td style="font-weight:bold;text-decoration:underline;">Task</td>
			</tr>'.$thisweek.'
		</table>
	</p>
	';
		}
		if($overdue != '') {
			$message1 .= '
	<p>These tasks are now past due:<br />
		<table style="" cellpadding="5">
			<tr>
				<td style="font-weight:bold;width:150px;text-decoration:underline;">Store</td>
				<td style="font-weight:bold;text-decoration:underline;">Task</td>
			</tr>'.$overdue.'
		</table>
	</p>
	';
		}

		
		$subject1 = 'PM Task List Reminder';

		if(emailer($row['chrFirst'].' '.$row['chrLast'].' <'.$row['chrEmail'].'>',$subject1,$message1)) { 
			echo "Email sent to ".$row['chrEmail']." successfully./n";
		} else {
			echo "An Error was encountered while sending this e-mail to ".$row['chrTo']."./n";
		}


	}
	
?>