<?
	require('appleretail-conf.php');

	$connection = @mysql_connect($host, $user, $pass);
	mysql_select_db($db, $connection);
	unset($host, $user, $pass, $db);
	
	if(@$_REQUEST['postType'] == "delete") {
		echo $q = "UPDATE ". $_REQUEST['tbl'] ." SET bDeleted=1 WHERE ID=".$_REQUEST['id'];
		mysql_query($q);
	}

	if(@$_REQUEST['postType'] == "bcalaccess") {
		echo $q = "UPDATE ". $_REQUEST['tbl'] ." SET bCalAccess=0 WHERE ID=".$_REQUEST['id'];
		mysql_query($q);
	}

	if(@$_REQUEST['postType'] == "permDelete") {
		echo $q = "DELETE FROM ". $_REQUEST['tbl'] ." WHERE ID=".$_REQUEST['id'];
		mysql_query($q);
	}

	if(@$_REQUEST['postType'] == "permDeleteSeries") {
		$q = "SELECT chrSeries FROM ". $_REQUEST['tbl'] ." WHERE ID=". $_REQUEST['id'];
		$series = mysql_fetch_assoc(mysql_query($q));

		echo $q = "DELETE FROM ". $_REQUEST['tbl'] ." WHERE chrSeries='".$series['chrSeries']."'";
		mysql_query($q);
	}

	echo " - 1";
?>
