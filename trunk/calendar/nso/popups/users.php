<?	include('_controller.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>User Popup</title>
	
	<? include($BF .'calendar/components/list/sortlistjs.php')?>
	<script type='text/javascript' src='<?=$BF?>includes/overlaysnew.js'></script>
	<script type='text/javascript'>

	function associate(id,fname,lname,email) {
		window.opener.document.getElementById('NSOUserAssoc').innerHTML += "<tr id='NSOUserstr"+id+"' class='ListOdd' onmouseover='RowHighlight(\"NSOUserstr"+id+"\");' onmouseout='UnRowHighlight(\"NSOUserstr"+id+"\");'>	<td window.location.href=\"edit.php?id="+id+"\">"+lname+"</td>	<td window.location.href=\"edit.php?id="+id+"\">"+fname+"</td> <td window.location.href=\"edit.php?id="+id+"\">"+email+"</td> <td><span class='deleteImage'><a href=\"javascript:warning("+id+", '"+fname+" "+lname+"','','NSOUsers');\" title=\"Delete: "+name+"\"><img id='deleteButton"+id+"' src='../../images/button_delete.png' alt='delete button' onmouseover='this.src=\"../../images/button_delete_on.png\"' onmouseout='this.src=\"../../images/button_delete.png\"' /></a></span></td> </tr> ";

		window.opener.repaint('NSOUserAssoc');

		var poststr = "idCalendarEvent=<?=$_REQUEST['id']?>" +
			"&idNSOUser=" + id + 
        	"&postType=" + encodeURI( "users" );
        	
      	postInfo('ajax_associate.php', poststr);
      	
	}
	
	</script>

</head>
<body>


<?
	$results = db_query("SELECT Users.ID,Users.chrLast,Users.chrFirst,Users.chrEmail
				FROM CalendarAccess
				JOIN Users ON Users.ID=CalendarAccess.idUser
				WHERE !Users.bDeleted
					AND Users.ID NOT IN (SELECT idNSOUser FROM NSOUserAssoc WHERE idCalendarEvent='".$_REQUEST['id']."')
				ORDER BY chrLast,chrFirst
			","Getting users");
	if(mysqli_num_rows($results) > 0) { 
?>

	<table id='Tasks' class='List sortable' style='width: 100%' cellpadding="0" cellspacing="0">
		<thead><tr>
			<th class='ListHeadSortOn sorttable_sorted' >Last Name&nbsp;<img src='<?=$BF?>components/list/column_sorted_asc.gif' alt='sorted' style='vertical-align: bottom;' /><span id='sorttable_sortfwdind'></span></th>
			<th>First Name&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='unsorted' style='vertical-align: bottom;' /></th>
			<th>Email Address&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='unsorted' style='vertical-align: bottom;' /></th>
		</tr></thead>
<?		$count = 0;
		while($row = mysqli_fetch_assoc($results)) { 
?>
		<tr id='Taskstr<?=$row['ID']?>' class='<?=(($count++ % 2) == 0 ? 'ListEven' : 'ListOdd')?>' 
			onmouseover='RowHighlight("Taskstr<?=$row['ID']?>");' onmouseout='UnRowHighlight("Taskstr<?=$row['ID']?>");'>
			<td onclick="associate(<?=$row['ID']?>,'<?=$row['chrLast']?>','<?=$row['chrFirst']?>','<?=$row['chrEmail']?>')"><?=$row['chrLast']?></td>
			<td onclick="associate(<?=$row['ID']?>,'<?=$row['chrLast']?>','<?=$row['chrFirst']?>','<?=$row['chrEmail']?>')"><?=$row['chrFirst']?></td>
			<td onclick="associate(<?=$row['ID']?>,'<?=$row['chrLast']?>','<?=$row['chrFirst']?>','<?=$row['chrEmail']?>')"><?=$row['chrEmail']?></td>
		</tr>
<?		} ?>
	</table>
	
<?	} else { ?>
	No Records to add
<?	} ?>

<div align="center" style='margin: 10px auto;'><a href="javascript:window.close();">Close Window</a></div>
</body>
</html>
