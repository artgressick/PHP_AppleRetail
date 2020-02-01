<?
	$BF = '../../../';
	$NON_HTML_PAGE=1;
	include($BF . 'calendar/_lib.php');
	include_once($BF.'calendar/components/add_functions.php');

	if($_REQUEST['postType'] == "tasks") {
		
		$q = "INSERT INTO NSOTaskAssoc SET 
			chrKEY='".makekey()."',
			idNSOTask='". $_REQUEST['idNSOTask'] ."',
			intDateOffset='". $_REQUEST['intDateOffset'] ."',
			idNSO='". $_REQUEST['idNSO'] ."'
		";
		if(db_query($q,'insert task')) {
			db_query("UPDATE NSOs SET dtUpdated=now() WHERE ID=".$_REQUEST['idNSO'],"Updating Timestamp for NSO");
			echo "PASS";
		} else {
			echo "FAIL: ". $q;
		}
	}

	if($_REQUEST['postType'] == "corptasks") {
		
		$q = "INSERT INTO NSOCorpTaskAssoc SET 
			chrKEY='".makekey()."',
			idNSOCorpTask='". $_REQUEST['idNSOCorpTask'] ."',
			intDateOffset='". $_REQUEST['intDateOffset'] ."',
			idNSO='". $_REQUEST['idNSO'] ."'
		";
		if(db_query($q,'insert corp task')) {
			db_query("UPDATE NSOs SET dtUpdated=now() WHERE ID=".$_REQUEST['idNSO'],"Updating Timestamp for NSO");
			echo "PASS";
		} else {
			echo "FAIL: ". $q;
		}
	}
	
	
	if($_REQUEST['postType'] == "supply") {
		$test = db_query("SELECT ID FROM SupplyAssoc WHERE !bDeleted AND idNSO='".$_REQUEST['idNSO']."' AND idSupplyItem='".$_REQUEST['idSupplyItem']."'","Checking for item",1);
		if($test['ID'] == '') {
			$q = "INSERT INTO SupplyAssoc SET 
				chrKEY='".makekey()."',
				idSupplyItem='". $_REQUEST['idSupplyItem'] ."',
				idNSO='". $_REQUEST['idNSO'] ."',
				dtCreated=now(),
				dtUpdated=now()
			";
			if(db_query($q,'insert supply item')) {
				db_query("UPDATE NSOs SET dtUpdated=now() WHERE ID=".$_REQUEST['idNSO'],"Updating Timestamp for NSO");
				echo "PASS";
			} else {
				echo "FAIL: ". $q;
			}
		} else { echo "FAIL"; }
	}

	if($_REQUEST['postType'] == "users") {
		
		$q = "INSERT INTO NSOUserAssoc SET 
			idNSOUser='". $_REQUEST['idNSOUser'] ."',
			idNSO='". $_REQUEST['idNSO'] ."'
		";
		if(db_query($q,'insert task')) {
			db_query("UPDATE NSOs SET dtUpdated=now() WHERE ID=".$_REQUEST['idNSO'],"Updating Timestamp for NSO");
			echo "PASS";
		} else {
			echo "FAIL: ". $q;
		}
	}

	if($_REQUEST['postType'] == "notifications") {
		$info = db_query("SELECT NSOs.ID,NNA.idNSONotification
						  FROM NSOs
						  LEFT JOIN NSONotificationAssoc AS NNA ON NNA.idNSO=NSOs.ID AND NNA.idNSONotification=".$_REQUEST['idNSONotification']."
						  WHERE NSOs.chrKEY='".$_REQUEST['key']."'
						 ", "Getting NSO ID and test to see if user is added",1); 
	
		if($info['idNSONotification'] == '') {
			$q = "INSERT INTO NSONotificationAssoc SET 
				idNSONotification='". $_REQUEST['idNSONotification'] ."',
				idNSO='". $info['ID'] ."'
			";
			if(db_query($q,'insert notification')) {
				global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
				echo mysqli_insert_id($mysqli_connection);
				db_query("UPDATE NSOs SET dtUpdated=now() WHERE ID=".$info['ID'],"Updating Timestamp for NSO");
			} else {
				echo "FAIL";
			}
		} else {
			echo "FAIL";
		}
	}

	if($_REQUEST['postType'] == "sitesurveys") {
		$info = db_query("SELECT NSOs.ID,NNA.idNSONotification
						  FROM NSOs
						  LEFT JOIN NSOSiteSurveyAssoc AS NNA ON NNA.idNSO=NSOs.ID AND NNA.idNSONotification=".$_REQUEST['idNSONotification']."
						  WHERE NSOs.chrKEY='".$_REQUEST['key']."'
						 ", "Getting NSO ID and test to see if user is added",1); 
	
		if($info['idNSONotification'] == '') {
			$q = "INSERT INTO NSOSiteSurveyAssoc SET 
				idNSONotification='". $_REQUEST['idNSONotification'] ."',
				idNSO='". $info['ID'] ."'
			";
			if(db_query($q,'insert notification')) {
				global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
				echo mysqli_insert_id($mysqli_connection);
				db_query("UPDATE NSOs SET dtUpdated=now() WHERE ID=".$info['ID'],"Updating Timestamp for NSO");
			} else {
				echo "FAIL";
			}
		} else {
			echo "FAIL";
		}
	}
	
	if($_REQUEST['postType'] == "evaluations") {
		$info = db_query("SELECT NSOs.ID,NNA.idNSONotification
						  FROM NSOs
						  LEFT JOIN NSOEvaluationsAssoc AS NNA ON NNA.idNSO=NSOs.ID AND NNA.idNSONotification=".$_REQUEST['idNSONotification']."
						  WHERE NSOs.chrKEY='".$_REQUEST['key']."'
						 ", "Getting NSO ID and test to see if user is added",1); 
	
		if($info['idNSONotification'] == '') {
			$q = "INSERT INTO NSOEvaluationsAssoc SET 
				idNSONotification='". $_REQUEST['idNSONotification'] ."',
				idNSO='". $info['ID'] ."'
			";
			if(db_query($q,'insert notification')) {
				global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
				echo mysqli_insert_id($mysqli_connection);
				db_query("UPDATE NSOs SET dtUpdated=now() WHERE ID=".$info['ID'],"Updating Timestamp for NSO");
			} else {
				echo "FAIL";
			}
		} else {
			echo "FAIL";
		}
	}
	
?>