<?	
	$BF = '../';
	require($BF. '_lib.php');
	include($BF. 'includes/meta.php');	
		// bSuperAdmin must be set to true, else show noaccess.php page
		if ($Security['bSuperAdmin'] == false) {
				header("Location: " . $BF . "noaccess.php");
				die();
		}
	// Get info to populate fields. Also ... If the old information is the same as the current, why update it?  Get the old information to test this against.
	$info = database_query("SELECT * FROM CMSPages WHERE ID=1","getting Landing Page info",1);

	// If a post occured
	if(isset($_POST['txtContent'])) { // When doing isset, use a required field.  Faster than the php count funtion.

		// Set the basic values to be used.
		//   $table = the table that you will be connecting to to check / make the changes
		//   $mysqlStr = this is the "mysql string" that you are going to be using to update with.  This needs to be set to "" (empty string)
		//   $sudit = this is the "audit string" that you are going to be using to update with.  This needs to be set to "" (empty string)
		$table = 'CMSPages';
		$mysqlStr = '';
		$audit = '';

		// "List" is a way for php to split up an array that is coming back.  
		// "set_strs" is a function (bottom of the _lib) that is set up to look at the old information in the DB, and compare it with
		//    the new information in the form fields.  If the information is DIFFERENT, only then add it to the mysql string to update.
		//    This will ensure that only information that NEEDS to be updated, is updated.  This means smaller and faster DB calls.
		//    ...  This also will ONLY add changes to the audit table if the values are different.
		list($mysqlStr,$audit) = set_strs($mysqlStr,'txtContent',$info['txtContent'],$audit,$table,$_POST['id']);

		// if nothing has changed, don't do anything.  Otherwise update / audit.
		if($mysqlStr != '') { list($str,$aud) = update_record($mysqlStr, $audit, $table, $_POST['id']); }

		header("Location: ". $_POST['moveTo']);
		die();
	}


?>
<script language="JavaScript" src="<?=$BF?>includes/forms.js"></script>
<script language="javascript">
	function error_check(addy) {
		if(total != 0) { reset_errors(); }  

		var total=0;
//		total += ErrorCheck('chrEmailCategory', "You must enter an Email Category Name.");
		total += CustomError('You must fill in the Landing Page', 'txtContent', 'tinyMCE');

		if(total == 0) { notice('Landing Page', 'index.php'); }
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
		extended_valid_elements : "hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
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
<script language="JavaScript" type='text/javascript' src="<?=$BF?>includes/noticeedit.js"></script>
<?
	
	//This is needed for the nav_menu on top. We are setting the focus on the first text box of the page.
	$bodyParams = "document.getElementById('txtContent').focus()";

	include($BF. "includes/top.php");
	include($BF. "admin/includes/admin_nav.php");
	include($BF. 'includes/noticeedit.php');
?>

<form name='idForm' id='idForm' action='' method="post">
	<div class='listHeader'>Edit Landing Page</div>
	<div class='greenInstructions'>Edit the main Landing Page for the Site. WARNING!! Do no make tables larger than 924 pixels wide, its better to us a % value.</div>
	
		<div id='errors'></div>
		
		<div class='FormName'>Landing Page <span class='FormRequired'>(Required)</span></div>
		<div class='FormField'><textarea id="txtContent" name="txtContent" rows="50" style="width:100%" wrap="virtual"><?=$info['txtContent']?></textarea></div>
					

			<input class='adminformbutton' type='button' value='Update Information' onclick="error_check();" />
			<input type='hidden' name='id' value='<?=$info['ID']?>' >
			<input type='hidden' name='moveTo' id='moveTo' />
</form>


<?php
	include($BF. "includes/bottom.php");
?>