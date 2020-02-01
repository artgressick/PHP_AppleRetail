<?
	include_once($BF.'calendar/components/add_functions.php');
	$table = 'NSONotes'; # added so not to forget to change the insert AND audit

	if($_POST['txtNote'] != "") { 
	
		$key = makekey();
		$q = "INSERT INTO ". $table ." SET 
			chrKEY = '". $key ."',
			dtCreated = now(),
			txtNote = '". encode($_POST['txtNote']) ."',
			idUser = '". $_SESSION['idUser'] ."',
			idNSO = '". $_POST['id'] ."'
		";
		
		# if there database insertion is successful	
		if(db_query($q,"Insert into ". $table)) {
	
			// This is the code for inserting the Audit Page
			// Type 1 means ADD NEW RECORD, change the TABLE NAME also
			global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
			$newID = mysqli_insert_id($mysqli_connection);

			db_query("UPDATE NSOs SET dtUpdated=now() WHERE ID=".$_POST['id'],"Updating Timestamp for NSO");
?>
<script type='text/javascript'>


//	var tags = window.opener.document.getElementById('NSONotes').getElementsByTagName('tr');
//	if(tags.length == 2) {
//		if(tags[1].childNodes[0].innerHTML == 'No records found in the database.') {
//			tags[1].style.display='none';
//		}
//	}

//	if(window.opener.document.getElementById('nonotes')) {
//		window.opener.document.getElementById('nonotes').display = "none";
//	}
		
//	window.opener.document.getElementById('NSONotes').innerHTML += "<tr id='NSONotestr<?=$newID?>' class='ListOdd' onmouseover='RowHighlight(\"NSONotestr<?=$newID?>\");' onmouseout='UnRowHighlight(\"NSONotestr<?=$newID?>\");'>	<td window.location.href=\"edit.php?id=<?=$newID?>\"><?=(strlen($_POST['txtNote']) > 150 ? substr($_POST['txtNote'],0,150).'...' : $_POST['txtNote'])?></td> <td><span class='deleteImage'><a href=\"javascript:warning(<?=$newID?>, '<?=(strlen($_POST['txtNote']) > 50 ? substr($_POST['txtNote'],0,50).'...' : $_POST['txtNote'])?>','<?=$key?>','NSONotes');\" title=\"Delete: <?=(strlen($_POST['txtNote']) > 50 ? substr($_POST['txtNote'],0,50).'...' : $_POST['txtNote'])?>\"><img id='deleteButton<?=$newID?>' src='../../images/button_delete.png' alt='delete button' onmouseover='this.src=\"../../images/button_delete_on.png\"' onmouseout='this.src=\"../../images/button_delete.png\"' /></a></span></td> </tr> ";
	
//	window.opener.repaint('NSONotes');
	
	window.opener.location.reload(true);

	window.close();	
</script>

<?		} 
	}
?>