<?
	# This is the BASE FOLDER pointing back to the root directory
	$BF = '../../../';

	preg_match('/(\w)+\.php$/',$_SERVER['SCRIPT_NAME'],$file_name);
    $post_file = '_'.$file_name[0];

	switch($file_name[0]) {
		#################################################
		##	index Page
		#################################################
		case 'index.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',31,1);
			include($BF.'calendar/components/formfields.php');
			
			
			$results = db_query("SELECT N.ID, N.chrKEY, S.chrName AS chrStore, S.chrStoreNum, T.chrNSOType, 
				GROUP_CONCAT(CONCAT(DCQ.chrField,':::',DCQ.dOrig,':::',DCQ.dNew,':::',DCQ.dtStamp,':::',U.chrFirst,' ',U.chrLast,':::',DCQ.chrReason) ORDER BY DCQ.dtStamp,DCQ.chrField SEPARATOR '|||') AS txtChanges
				FROM DateChangesQue AS DCQ
				JOIN NSOs AS N ON DCQ.idNSO=N.ID
				JOIN Users AS U ON DCQ.idUser=U.ID
				JOIN RetailStores AS S ON N.idStore=S.ID
				JOIN NSOTypes AS T ON N.idNSOType=T.ID
				GROUP BY N.ID
				ORDER BY N.ID","Gathering Records for Date Change E-mailer"); // Get Info
				
			
			if(isset($_POST['sendnow'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
			}

			# The template to use (should be the last thing before the break)
			$title = "Events Date Changes Auto E-mailer";	# Page Title
			$directions = 'An e-mail will be sent out daily at 6:00 pm PST if there was any date changes to NSO/Remodel Events, To over-ride and send now, click "Send Now"';
			$header_title = 'Events Date Changes Auto E-mailer';
			include($BF ."calendar/models/admin.php");		
			
			break;
						
			
		#################################################
		##	Else show Error Page
		#################################################
		default:
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>