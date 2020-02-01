<?php
	$BF='../../';

	require($BF. '_lib.php');
	include($BF. "includes/meta.php");
		// bPandP must be set to true, else show noaccess.php page
		if ($Security['bPandP'] == false) {
				header("Location: " . $BF . "pandp/noaccess.php");
				die();
		}	


	(!isset($_REQUEST['id'])?$_REQUEST['id']=1:'');

	$q = "SELECT ID, chrTitle, idParent, txtContent, idGeo, dOrder, bVisable
			FROM PNPPages
			WHERE ID = ".$_REQUEST['id']." AND !bDeleted";
	$info = database_query($q, 'Getting the edit info',1);


	
	if (count($_POST))
	{
	
		// Set the basic values to be used.
		//   $table = the table that you will be connecting to to check / make the changes
		//   $mysqlStr = this is the "mysql string" that you are going to be using to update with.  This needs to be set to "" (empty string)
		//   $sudit = this is the "audit string" that you are going to be using to update with.  This needs to be set to "" (empty string)
		$table = 'PNPPages';
		$mysqlStr = '';
		$audit = '';

		if ($_POST['idGeo'] == 0) {
		
			$tmp = database_query("SELECT idGeo, dOrder FROM PNPPages WHERE ID=".$_POST['idParent'],"Getting Geo of Parent",1);
		
			$_POST['idGeo'] = $tmp['idGeo'];
			$_POST['dOrder'] = $tmp['dOrder'];
				
		}
		
		$_POST['idUser'] = $_SESSION['idUser'];


		// "List" is a way for php to split up an array that is coming back.  
		// "set_strs" is a function (bottom of the _lib) that is set up to look at the old information in the DB, and compare it with
		//    the new information in the form fields.  If the information is DIFFERENT, only then add it to the mysql string to update.
		//    This will ensure that only information that NEEDS to be updated, is updated.  This means smaller and faster DB calls.
		//    ...  This also will ONLY add changes to the audit table if the values are different.
		list($mysqlStr,$audit) = set_strs($mysqlStr,'idParent',$info['idParent'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'chrTitle',$info['chrTitle'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'txtContent',$info['txtContent'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'idGeo',$info['idGeo'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'idUser',$info['idUser'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'bVisable',$info['bVisable'],$audit,$table,$_POST['id']);
		list($mysqlStr,$audit) = set_strs($mysqlStr,'dOrder',$info['dOrder'],$audit,$table,$_POST['id']);
						
		// if nothing has changed, don't do anything.  Otherwise update / audit.
		if($mysqlStr != '') { list($str,$aud) = update_record($mysqlStr, $audit, $table, $_POST['id']); }
	
		
	 header("Location: ". $_POST['moveTo']);
	 die();
	
	
	
	}
		$query = "SELECT PNPPages.ID, chrTitle, chrGeo
				FROM PNPPages
				LEFT JOIN Geos ON PNPPages.idGeo=Geos.ID
				WHERE bParent AND !bDeleted";
	$results = database_query($query, 'Getting Parents');
	
	$q = "SELECT * FROM Geos ORDER BY ID";
	$geos = database_query($q, "Getting Geos");	
	
?>
<script language="JavaScript" type='text/javascript' src="<?=$BF?>includes/noticeedit.js"></script>
<script language="JavaScript" src="<?=$BF?>includes/listadd.js"></script>
<script language="JavaScript" src="<?=$BF?>includes/forms.js"></script>

<script language="javascript">
	function error_check(addy) {
		if(total != 0) { reset_errors(); }  

		var total=0;

		total += ErrorCheck('chrTitle', "You must fill in a title.");
		total += CustomError('You must fill in the page content', 'txtContent', 'tinyMCE');
		<?=($info['ID']==$info['idParent']?"total += ErrorCheck('idParent', 'You must Select a Parent');":'')?>
		<?=($info['ID']==$info['idParent']?"total += ErrorCheck('idGeo', 'You must Select a Geo');":'')?>
		
		
		if(total == 0) { notice(document.getElementById('chrTitle').value, 'index.php?idGeo=<?=$_SESSION['idAdminGeo']?>'); }
		
	}
</script>
<script type="text/javascript" src="<?=$BF?>components/tiny_mce/tiny_mce_gzip.js"></script>
<script type="text/javascript">
tinyMCE_GZ.init({
	plugins : 'style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras',
	themes : 'simple,advanced',
	languages : 'en',
	disk_cache : true,
	debug : false
});
</script>
<!-- Needs to be seperate script tags! -->
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		plugins : "style,layer,table,save,advhr,advimage,advlink,emotions,insertdatetime,preview,zoom,flash,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,filemanager",
		theme_advanced_buttons1_add : "fontselect,fontsizeselect",
		theme_advanced_buttons2_add : "separator,forecolor,backcolor",
		theme_advanced_buttons2_add_before: "cut,copy,paste,pastetext,pasteword,separator",
		theme_advanced_buttons3_add : "emotions,flash,advhr,separator,print,separator,ltr,rtl,separator,fullscreen",
		theme_advanced_toolbar_location : "top",
		theme_advanced_path_location : "bottom",
		content_css : "/example_data/example_full.css",
	    plugin_insertdate_dateFormat : "%Y-%m-%d",
	    plugin_insertdate_timeFormat : "%H:%M:%S",
		extended_valid_elements : "a[href|target=_blank],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
		external_link_list_url : "example_data/example_link_list.js",
		external_image_list_url : "example_data/example_image_list.js",
		flash_external_list_url : "example_data/example_flash_list.js",
		file_browser_callback : "mcFileManager.filebrowserCallBack",
		theme_advanced_resize_horizontal : false,
		theme_advanced_resizing : true,
		apply_source_formatting : true,
		
		filemanager_rootpath : "<?=realpath($BF . 'uploads')?>",
		filemanager_path : "<?=realpath($BF . 'uploads')?>",
		relative_urls : false,
		document_base_url : "http://storeops.apple.com/"		
	});
</script>
<!-- /tinyMCE -->

<?	
	include($BF. "includes/top.php");
	include($BF. "pandp/includes/admin_nav.php"); 
	$TableName = "PNPPages"; //dtn:  This is the Database table that you will be setting the bDeleted statuses on.
	include($BF. 'includes/noticeedit.php');
?>




<form name='idForm' id='idForm' action='' method="post">

	<div class='listHeader'>Edit Page</div>
	<div class='greenInstructions'>Please remember to use Firefox when building these pages until Safari 3.0 is release. Also your tables should not be larger then 703 Pixels. It is best to use percentages on table sizes so that is does not expand larger then your area.</div>
	
		<div id='errors'></div>
		
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		
		<? if($info['idParent']!=$info['ID'])
			{
		?>
				<tr><td>
				<div style = "font-weight: bold;">Parent</div>
				<select id = "idParent" name = "idParent" style = "width: 218px;">
						<option value = ''>Please select a parent...</option>
		<?
				while ($row= mysqli_fetch_assoc($results))
				{
		?>
					<option value = "<?=$row['ID']?>" <?=($row['ID']==$info['idParent']?'selected':'')?>><?=$row['chrTitle']?> (<?=$row['chrGeo']?>)</option>
		<?
				}
		?>
				</select><br /><br /></td></tr>
				<input type = "hidden" name = "idGeo" id = "idGeo" value = "0" />
		<?
			}
			else{
			
		?>
			<input type = "hidden" name = "idParent" id = "idParent" value = "<?=$info['ID']?>" />
			<tr><td>
				<div style = "font-weight: bold;">Geo</div>
				<select id = "idGeo" name = "idGeo" style = "width: 218px;">
						<option value = ''>Please select a Geo...</option>
		<?
				while ($row= mysqli_fetch_assoc($geos))
				{
		?>
					<option value = "<?=$row['ID']?>" <?=($info['idGeo'] == $row['ID'] ? "selected='selected'" : "" )?>><?=$row['chrGeo']?></option>
		<?
				}
		?>
				</select><br /><br /></td></tr>		
		<? } ?>				
		<input type = "hidden" name = "ID" id = "ID" value = "<?=$_REQUEST['id']?>" />
			<tr>
				<td>
					<div style = "font-weight: bold;">Title</div>
					<div><input type = "text" name = "chrTitle" id = "chrTitle" size = "30" value = "<?=$info['chrTitle']?>" /></div><br />
				</td>
			</tr>
			<tr>
				<td>
					<div style = "font-weight: bold;">Visible</div>
					<div><input type="radio" name="bVisable" id="bVisable" value="0" <?=($info['bVisable'] == 0 ? 'checked="checked"' : "")?> />No &nbsp;&nbsp; <input type="radio" name="bVisable" id = "bVisable" value="1" <?=($info['bVisable'] == 1 ? 'checked="checked"' : "")?> />Yes</div><br />
				</td>
			</tr>
			<tr>
				<td>
					<div><textarea name = "txtContent" id = "txtContent" cols = "90" rows = "30"><?=decode($info['txtContent'])?></textarea><br />
				</td>
			</tr>
			<tr>
				<td>
					<input class='adminformbutton' type='button' value='Update Information' onclick="error_check();" />
					<input type='hidden' name='id' value='<?=$_REQUEST['id']?>' >
					<input type='hidden' name='dOrder' value='<?=$info['dOrder']?>' />
					<input type='hidden' name='moveTo' id='moveTo' />
				</td>
			</tr>
		</table>
	
	</td>
	</tr>
	</table>
	<?
	include($BF."includes/bottom.php");
?>