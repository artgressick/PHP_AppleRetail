<?
	require('appleretail-conf.php');

	$connection = @mysql_connect($host, $user, $pass);
	mysql_select_db($db, $connection);
	unset($host, $user, $pass, $db);
	
	if(@$_REQUEST['postType'] == "delete") {
		if($_REQUEST['tbl'] == 'CalendarFiles2') { $_REQUEST['tbl'] = 'CalendarFiles'; } 
		$check = mysql_fetch_assoc(mysql_query("SELECT * FROM ". $_REQUEST['tbl'] ." WHERE ID=".$_REQUEST['id']));
		if(isset($check['idNSO'])) {
			mysql_query("UPDATE NSOs SET dtUpdated=now() WHERE ID=".$check['idNSO']);
		}
		
		echo $q = "UPDATE ". $_REQUEST['tbl'] ." SET bDeleted=1 WHERE ID=".$_REQUEST['id'];
		mysql_query($q);
	}

	if(@$_REQUEST['postType'] == "bcalaccess") {
		echo $q = "UPDATE ". $_REQUEST['tbl'] ." SET bCalAccess=0 WHERE ID=".$_REQUEST['id'];
		mysql_query($q);
	}

	if(@$_REQUEST['postType'] == "permDelete") {
		if($_REQUEST['tbl'] == 'CalendarFiles2') { $_REQUEST['tbl'] = 'CalendarFiles'; }
		$check = mysql_fetch_assoc(mysql_query("SELECT * FROM ". $_REQUEST['tbl'] ." WHERE ID=".$_REQUEST['id']));
		if(isset($check['idNSO'])) {
			mysql_query("UPDATE NSOs SET dtUpdated=now() WHERE ID=".$check['idNSO']);
		}
		echo $q = "DELETE FROM ". $_REQUEST['tbl'] ." WHERE ID=".$_REQUEST['id'];
		mysql_query($q);
	}

	if(@$_REQUEST['postType'] == "permDeleteSeries") {
		$q = "SELECT chrSeries FROM ". $_REQUEST['tbl'] ." WHERE ID=". $_REQUEST['id'];
		$series = mysql_fetch_assoc(mysql_query($q));

		echo $q = "DELETE FROM ". $_REQUEST['tbl'] ." WHERE chrSeries='".$series['chrSeries']."'";
		mysql_query($q);
	}
	
	if(@$_REQUEST['postType'] == "percentchange") {
		echo $q = "UPDATE NSOTaskAssoc SET intNSOTaskStatus='".$_REQUEST['value']."' WHERE ID=".$_REQUEST['id'];
		mysql_query($q);
		mysql_query("UPDATE NSOs SET dtUpdated=now() WHERE ID=".$_REQUEST['idNSO']);
	}

	if(@$_REQUEST['postType'] == "corppercentchange") {
		echo $q = "UPDATE NSOCorpTaskAssoc SET intNSOTaskStatus='".$_REQUEST['value']."' WHERE ID=".$_REQUEST['id'];
		mysql_query($q);
		mysql_query("UPDATE NSOs SET dtUpdated=now() WHERE ID=".$_REQUEST['idNSO']);
	}
	
	
	if(@$_REQUEST['postType'] == "supplychange") {
		echo $q = "UPDATE SupplyAssoc SET intQSent='".$_REQUEST['value']."' WHERE ID=".$_REQUEST['id'];
		mysql_query($q);
		mysql_query("UPDATE NSOs SET dtUpdated=now() WHERE ID=".$_REQUEST['idNSO']);
	}

	if(@$_REQUEST['postType'] == "supplyupdate") {
		echo $q = "UPDATE SupplyAssoc SET intQReceived='".$_REQUEST['value']."', dtUpdated=now() WHERE ID=".$_REQUEST['id'];
		mysql_query($q);
		mysql_query("UPDATE NSOs SET dtUpdated=now() WHERE ID=".$_REQUEST['idNSO']);
	}
	
	
	
	if(@$_REQUEST['postType'] == "updatebshow") {
		$q = "UPDATE NSOLearn SET bShow=".$_REQUEST['bShow']." WHERE ID=".$_REQUEST['id'];
		if(mysql_query($q)) {
			if($_REQUEST['bParent'] == 1) {
				$q = "UPDATE NSOLearn SET bPShow=".$_REQUEST['bShow']." WHERE idParent=".$_REQUEST['id'];
				mysql_query($q);			
			}
			echo "success";
		} else {
			echo "fail";
		}
	}
?>
