<?
	include_once($BF.'calendar/components/add_functions.php');
	$table = 'NSONotifications'; # added so not to forget to change the insert AND audit
	
	$info = db_query("SELECT ID FROM NSOs WHERE chrKEY='".$_REQUEST['key']."'","Getting ID",1);
	$key = makekey();
	$q = "INSERT INTO ". $table ." SET 
		chrKEY = '". $key ."',
		chrFirst = '". encode($_POST['chrFirst']) ."',
		chrLast = '". encode($_POST['chrLast']) ."',
		chrEmail = '". $_POST['chrEmail'] ."',
		chrCompany = '". encode($_POST['chrCompany']) ."'
	";
	
	# if there database insertion is successful	
	if(db_query($q,"Insert into ". $table)) {
		// This is the code for inserting the Audit Page
		// Type 1 means ADD NEW RECORD, change the TABLE NAME also
		global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
		$newID = mysqli_insert_id($mysqli_connection);

		if(db_query("INSERT INTO NSONotificationAssoc SET 
			idNSO='". $info['ID'] ."',
			idNSONotification='". $newID ."'"
		,"Adding Assoc")) {
		
		global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
		$newID2 = mysqli_insert_id($mysqli_connection);
		db_query("UPDATE NSOs SET dtUpdated=now() WHERE ID=".$info['ID'],"Updating Timestamp for NSO");
?>
		<script type='text/javascript'>
			window.opener.document.getElementById('NSONotificationAssoc').innerHTML += "<tr id='NSONotificationAssoctr<?=$newID2?>' class='ListOdd' onmouseover='RowHighlight(\"NSONotificationAssoctr<?=$newID2?>\");' onmouseout='UnRowHighlight(\"NSONotificationAssoctr<?=$newID2?>\");'>	<td><?=encode($_POST['chrLast'])?></td>	<td><?=encode($_POST['chrFirst'])?></td> <td><?=encode($_POST['chrEmail'])?></td> <td><span class='deleteImage'><a href=\"javascript:warning(<?=$newID2?>, '<?=$_POST['chrFirst'].' '.$_POST['chrLast']?>','<?=$key?>','NSONotificationAssoc');\" title=\"Delete: <?=$_POST['chrFirst'].' '.$_POST['chrLast']?>\"><img id='deleteButton<?=$newID2?>' src='../../images/button_delete.png' alt='delete button' onmouseover='this.src=\"../../images/button_delete_on.png\"' onmouseout='this.src=\"../../images/button_delete.png\"' /></a></span></td> </tr>";
			window.opener.repaint('NSONotificationAssoc');
			window.close();	
		</script>
<?
		}

	} 
?>