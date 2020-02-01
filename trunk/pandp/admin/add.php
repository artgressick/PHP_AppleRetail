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


	$query = "SELECT PNPPages.ID, chrTitle, chrGeo
				FROM PNPPages
				JOIN Geos ON PNPPages.idGeo=Geos.ID
				WHERE bParent AND !bDeleted ";
				
	if(isset($_SESSION['idAdminGeo']) && is_numeric($_SESSION['idAdminGeo'])) {
		$query .= " AND Geos.ID=".$_SESSION['idAdminGeo'];
	}
	
	$results = database_query($query, 'Getting Parents');
	
	$q = "SELECT * FROM Geos ORDER BY ID";
	$geos = database_query($q, "Getting Geos");
	
	if (count($_POST))
	{
	
		

	
		$q = "INSERT INTO PNPPages (bParent, idParent, bDeleted, chrTitle, txtContent, idGeo, dtCreated, idUser, idCUser, bVisable) 
				VALUES (".$_REQUEST['id'].", 0, 0, '".encode($_POST['chrTitle'])."', '".encode($_POST['txtContent'])."','".$_POST['idGeo']."',now(),'". $_SESSION['idUser']."','". $_SESSION['idUser']."','".$_POST['bVisable']."')";
		database_query($q, 'Inserting page into database');
		
//		$val = mysqli_insert_id($mysqli_connection);

		// This is the code for inserting the Audit Page
		// Type 1 means ADD NEW RECORD, change the TABLE NAME also
		global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
		$newID = mysqli_insert_id($mysqli_connection);
				
		$q = "INSERT INTO Audit SET 
			idType=1, 
			idRecord='". $newID ."',
			txtNewValue='". audit_encode($_POST['chrTitle']) ."',
			dtDateTime=now(),
			chrTableName='PNPPages',
			idUser='". $_SESSION['idUser'] ."'
		";
		database_query($q,"Insert audit");
		//End the code for History Insert		
		
		
		
		if ($_REQUEST['id'] == 1)
		{

			$next_order = database_query("SELECT dOrder FROM PNPPages WHERE !bDeleted AND bParent=1 AND idGeo=".$_POST['idGeo']." ORDER BY dOrder DESC LIMIT 1", "Getting Highest Order Number",1);
			$q = "UPDATE PNPPages
				SET idParent = ".$newID.",
				dOrder = '". ($next_order['dOrder'] + 1) . "' 
				WHERE ID = ".$newID;
			database_query($q, 'Updating Parent for a Parent');
			
			if ($_POST['moveTo'] == "") { $_POST['moveTo'] = "orderparents.php?idGeo=".$_POST['idGeo']; }
		}
		else
		{
			$tmp = database_query("SELECT idGeo, dOrder FROM PNPPages WHERE ID=".$_POST['idParent'],"Getting Geo of Parent",1);
			$next_order = database_query("SELECT dOrderChild FROM PNPPages WHERE !bDeleted AND bParent=0 AND idGeo=".$tmp['idGeo']." AND idParent=".$_POST['idParent']." ORDER BY dOrderChild DESC LIMIT 1", "Getting Highest Order Number",1);

			$q = "UPDATE PNPPages
				SET idParent = ".$_POST['idParent'].",
				idGeo = ".$tmp['idGeo'].", 
				dOrder = ".$tmp['dOrder'].",
				dOrderChild = " . ($next_order['dOrderChild'] + 1) . " 
				WHERE ID = ".$newID;
			database_query($q, 'Updating Parent for a Child');
			
			if ($_POST['moveTo'] == "") { $_POST['moveTo'] = "orderchildren.php?idParent=".$_POST['idParent']; }
		}
		
		
	 header("Location: ". $_POST['moveTo']);
	 die();
	
	
	
	}
?>
<script language="JavaScript" type='text/javascript' src="<?=$BF?>includes/noticeadd.js"></script>
<script language="JavaScript" src="<?=$BF?>includes/listadd.js"></script>
<script language="JavaScript" src="<?=$BF?>includes/forms.js"></script>

<script language="javascript">
	function error_check(addy) {
		if(total != 0) { reset_errors(); }  

		var total=0;

		total += ErrorCheck('chrTitle', "You must fill in a title.");
		total += CustomError('You must fill in the page content', 'txtContent', 'tinyMCE');
		<?=($_REQUEST['id']==0?"total += ErrorCheck('idParent', 'You must Select a Parent');":'')?>
		<?=($_REQUEST['id']==1?"total += ErrorCheck('idGeo', 'You must Select a Geo');":'')?>
		
		if(total == 0) { notice(document.getElementById('chrTitle').value, 'add.php', ''); }
	
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
	include($BF. 'includes/noticeadd.php');
?>




<form name='idForm' id='idForm' action='' method="post">

	<div class='listHeader'>Add Page</div>
	<div class='greenInstructions'>Please remember to use Firefox when building these pages until Safari 3.0 is release. Also your tables should not be larger then 703 Pixels. It is best to use percentages on table sizes so that is does not expand larger then your area.</div>
	
		<div id='errors'></div>
		
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		
		<? if($_REQUEST['id']==0)
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
					<option value = "<?=$row['ID']?>"><?=$row['chrTitle']?> (<?=$row['chrGeo']?>)</option>
		<?
				}
		?>
				</select><br /><br /></td></tr>
				<input type = "hidden" name = "idGeo" id = "idGeo" value = "0" />
		<?
			} else {
		?>	
			<tr><td>
				<div style = "font-weight: bold;">Geo</div>
				<select id = "idGeo" name = "idGeo" style = "width: 218px;">
						<option value = ''>Please select a Geo...</option>
		<?
				while ($row= mysqli_fetch_assoc($geos))
				{
		?>
					<option value = "<?=$row['ID']?>" <?=($row['ID'] == $_SESSION['idAdminGeo'] ? "selected='selected'" : "")?>><?=$row['chrGeo']?></option>
		<?
				}
		?>
				</select><br /><br /></td></tr>
		<?
			}
		?>
		
		<tr>
			<td>
				<div style = "font-weight: bold;">Title</div>
				<div><input type = "text" name = "chrTitle" id = "chrTitle" size = "30" /></div><br />
			</td>
		</tr>
		<tr>
			<td>
				<div style = "font-weight: bold;">Visible</div>
				<div><input type="radio" name="bVisable" id="bVisable" value="0" checked="checked" />No &nbsp;&nbsp; <input type="radio" name="bVisable" id = "bVisable" value="1" />Yes</div><br />
			</td>
		</tr>
		<tr>
			<td>
				<div><textarea name = "txtContent" id = "txtContent" cols = "90" rows = "30"></textarea><br />
			</td>
		</tr>
		<tr>
			<td>
				<input class='adminformbutton' type='button' value='Add Page' onclick="error_check();" /> 
				<input type="hidden" name="moveTo" id="moveTo" /></form>
			</td>
		</tr>
	</table>
				
	</td></tr></table>
	<?
	include($BF."includes/bottom.php");
?>