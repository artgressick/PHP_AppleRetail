<?	include('_controller.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Task Popup</title>
	
	<? include($BF .'calendar/components/list/sortlistjs.php')?>
	<script type='text/javascript' src='<?=$BF?>includes/overlaysnew.js'></script>
	<script type='text/javascript'>

	function associate(id,key,name,date) {
//		window.opener.document.getElementById('NSOTaskAssoc').innerHTML += "<tr id='NSOTaskstr"+id+"' class='ListOdd' onmouseover='RowHighlight(\"NSOTaskstr"+id+"\");' onmouseout='UnRowHighlight(\"NSOTaskstr"+id+"\");'>	<td window.location.href=\"edit.php?id="+id+"\">"+name+"</td>	<td window.location.href=\"edit.php?id="+id+"\">xxx</td> <td window.location.href=\"edit.php?id="+id+"\">0%</td> <td><span class='deleteImage'><a href=\"javascript:warning("+id+", '"+name+"','"+key+"','NSOTasks');\" title=\"Delete: "+name+"\"><img id='deleteButton"+id+"' src='../../images/button_delete.png' alt='delete button' onmouseover='this.src=\"../../images/button_delete_on.png\"' onmouseout='this.src=\"../../images/button_delete.png\"' /></a></span></td> </tr> ";

//		window.opener.repaint('NSOTaskAssoc');

		var poststr = "idNSO=<?=$_REQUEST['id']?>" +
			"&idNSOTask=" + id + 
			"&intDateOffset=" + date + 
        	"&postType=" + encodeURI( "tasks" );
        	
      	postInfo('ajax_associate.php', poststr);
 
 		window.opener.location.reload(true);
      	
	}
	
	</script>

</head>
<body>

<?
	$results = db_query("SELECT NSOTasks.ID,NSOTasks.chrKEY,NSOTasks.chrNSOTask,NSOTasks.intDateOffset
				FROM NSOTasks
				WHERE !NSOTasks.bDeleted
					AND NSOTasks.ID NOT IN (SELECT ID FROM NSOTaskAssoc WHERE idNSO='".$_REQUEST['id']."') AND idNSOType=(SELECT idNSOType FROM NSOs WHERE ID='".$_REQUEST['id']."')
				ORDER BY dOrder, chrNSOTask
			","Getting tasks");
	if(mysqli_num_rows($results) > 0) { 
?>

	<table id='Tasks' class='List sortable' style='width: 100%' cellpadding="0" cellspacing="0">
		<thead><tr>
			<th class='ListHeadSortOn sorttable_sorted' >Event Name&nbsp;<img src='<?=$BF?>components/list/column_sorted_asc.gif' alt='sorted' style='vertical-align: bottom;' /><span id='sorttable_sortfwdind'></span></th>
		</tr></thead>
<?		$count = 0;
		while($row = mysqli_fetch_assoc($results)) { 
?>
		<tr id='Taskstr<?=$row['ID']?>' class='<?=(($count++ % 2) == 0 ? 'ListEven' : 'ListOdd')?>' 
			onmouseover='RowHighlight("Taskstr<?=$row['ID']?>");' onmouseout='UnRowHighlight("Taskstr<?=$row['ID']?>");'>
			<td onclick="associate(<?=$row['ID']?>,'<?=$row['chrKEY']?>','<?=$row['chrNSOTask']?>','<?=$row['intDateOffset']?>')"><?=$row['chrNSOTask']?></td>
		</tr>
<?		} ?>
	</table>
	
<?	} else { ?>
	No Records to add
<?	} ?>

<div align="center" style='margin: 10px auto;'><a href="javascript:window.close();">Close Window</a></div>
</body>
</html>
