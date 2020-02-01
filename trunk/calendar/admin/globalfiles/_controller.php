<?
	# This is the BASE FOLDER pointing back to the root directory
	$BF = '../../../';

	preg_match('/(\w)+\.php$/',$_SERVER['SCRIPT_NAME'],$file_name);
    $post_file = '_'.$file_name[0];

	switch($file_name[0]) {
		#################################################
		##	Index Page
		#################################################
		case 'index.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',25,2);
			include($BF.'calendar/components/formfields.php');

			
			if(isset($_POST['submit'])) { include($post_file); }
			
			$tmp = db_query("SELECT ID,chrNSOFileGroup FROM NSOFileGroups WHERE !bDeleted ORDER BY chrNSOFileGroup","Getting Groups");
			$groups = "";
			while($row = mysqli_fetch_assoc($tmp)) {
				$groups .= "<option value='".$row['ID']."'>".encode($row['chrNSOFileGroup'])."</option>";
			}

			$tmp = db_query("SELECT ID,chrNSOPictureGroup FROM NSOPictureGroups WHERE !bDeleted ORDER BY chrNSOPictureGroup","Getting Picture Groups");
			$picgroups = "";
			while($row = mysqli_fetch_assoc($tmp)) {
				$picsgroups .= "<option value='".$row['ID']."'>".encode($row['chrNSOPictureGroup'])."</option>";
			}
			
			
			# Stuff In The Header
			function sith() { 
				global $BF,$groups,$picsgroups;
?>	<script type='text/javascript'>
	var page = 'add';
	function newOption(num,table) {
		var groups = "<?=$groups?>";
		var currentnum = parseInt(document.getElementById('int'+table).value) + 1;
		document.getElementById('int'+table).value = currentnum;
		
		var tr = document.createElement('tr');
		var td1 = document.createElement('td');
		var td2 = document.createElement('td');
		var td3 = document.createElement('td');
		var td7 = document.createElement('td');
		var td4 = document.createElement('td');

		var tr2 = document.createElement('tr');
		var td5 = document.createElement('td');
		var td6 = document.createElement('td');
		
		td1.style.fontWeight = "bold";
		td1.style.verticalAlign = "top";
		td1.innerHTML = "File "+ currentnum +":";
		td1.style.borderLeft = "1px solid #CCC";
		td2.id = table+"file"+ currentnum;
		td2.style.verticalAlign = "top";
		td2.innerHTML = "<input type='file' name='chr"+table+"File"+ currentnum +"' id='chr"+table+"File"+ currentnum +"' />";
		td3.style.paddingLeft = "10px";
		td3.innerHTML = "Type:<br /><select name='chr"+table+"Type"+ currentnum +"' id='chr"+table+"Type"+ currentnum +"' onchange='shfilegroup("+currentnum+",this.value);'><option value='doc'>Document</option><option value='pic'>Picture</option></select>";
		td4.style.paddingLeft = "10px";
		td4.style.borderRight = "1px solid #CCC";
		td4.innerHTML = "Title:<br /><input type='text' name='chr"+table+"Title"+currentnum+"' id='chr"+table+"Title"+currentnum+"' />";
		td7.style.paddingLeft = "10px";
		td7.innerHTML = "<div id='group"+currentnum+"'>Group:<br /><select name='chr"+table+"Group"+currentnum+"' id='chr"+table+"Group"+currentnum+"'>"+groups+"</select></div>";
		
		td5.style.verticalAlign = "top";
		td5.style.textAlign = "right";
		td5.style.borderLeft = "1px solid #CCC";
		td5.style.borderBottom = "1px solid #CCC";
		td5.colSpan = "2";
		td5.innerHTML = "Description:";
		td6.style.paddingLeft = "10px";
		td6.colSpan = "3";
		td6.style.borderRight = "1px solid #CCC";
		td6.style.borderBottom = "1px solid #CCC";
		td6.innerHTML = "<textarea name='txt"+table+"Desc"+currentnum+"' id='txt"+table+"Desc"+currentnum+"' cols='30' rows='2' style='width:100%;'></textarea>";
		
		tr.appendChild(td1);
		tr.appendChild(td2);
		tr.appendChild(td3);
		tr.appendChild(td7);
		tr.appendChild(td4);
		tr2.appendChild(td5);
		tr2.appendChild(td6);
		
		document.getElementById(table+"tbody").appendChild(tr);
		document.getElementById(table+"tbody").appendChild(tr2);
	}
	function shfilegroup(num,value) {
		var groups = "<?=$groups?>";
		var picgroups = "<?=$picsgroups?>";
		if(value == 'pic') {
			document.getElementById('group'+num).innerHTML = "Group:<br /><select name='chrFilesGroup"+num+"' id='chrFilesGroup"+num+"'>"+picgroups+"</select>";
		} else {
			document.getElementById('group'+num).innerHTML = "Group:<br /><select name='chrFilesGroup"+num+"' id='chrFilesGroup"+num+"'>"+groups+"</select>";
		}
	}
	</script>
	<script type='text/javascript' src='error_check.js'></script>
<?
			}
	
			# The template to use (should be the last thing before the break)
			$title = "Global Files Upload";						# Page Title
			$directions = 'Upload Files to all or some NSO/Remodel Events. Select file(s), type of file, NSO(s)/Remodel(s) and Click Upload';
			$header_title = 'Upload Global Files';
			include($BF ."calendar/models/admin.php");		

			break;
		

		#################################################
		##	Else show Error Page
		#################################################
		default:
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>