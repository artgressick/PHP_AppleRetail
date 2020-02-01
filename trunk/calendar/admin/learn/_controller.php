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
			auth_check('litm',17,1);
			include($BF.'calendar/components/formfields.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
				?><script type='text/javascript' src='<?=$BF?>includes/overlaysnew.js'></script>
				<script type='text/javascript'>
					function sh_article(id) {

						var parentoff = '<?=$BF?>images/parentoff.png';
						var imgon = '<?=$BF?>images/on.png';
						var imgoff = '<?=$BF?>images/off.png';
						var titleparentoff = 'Parent is not visible.';
						var titleon = 'This article is visible.';
						var titleoff = 'This article is not visible.';
						var link = 'javascript:sh_article('+ id +');';
						
						var bParent = document.getElementById('parent'+id).value;
						var bShow = document.getElementById('show'+id).value;

						if (bShow == '1') {
							var newbShow = '0'; 
						} else {
							var newbShow = '1';
						}

						//do ajax here
						ajax = startAjax();
						var address = "<?=$BF?>calendar/includes/ajax_delete.php?postType=updatebshow&bParent=" + bParent + "&bShow=" + newbShow + "&id=" + id;
						var result = '0';
						if(ajax) {
							ajax.open("GET", address);
							ajax.onreadystatechange = function() {
								if(ajax.readyState == 4 && ajax.status == 200) {
									var result = 'fail';
									result = ajax.responseText;
										if(result == 'success') {
											if(bParent == '1') {
												var childarray = document.getElementById(id+'children').value.split(',');
												document.getElementById('show'+id).value = newbShow;
												if(newbShow == 0) {
													document.getElementById('showtd'+id).style.cursor = 'pointer';
													document.getElementById('showtd'+id).title = titleoff;
													document.getElementById('showtd'+id).attributes['onclick'].value = 'javascript:sh_article('+ id +');';
													document.getElementById('showimage'+id).src = imgoff;
													document.getElementById('showimage'+id).alt = titleoff;
					
													for (var i = 0; i < childarray.length; i++) {
														document.getElementById('showtd'+childarray[i]).style.cursor = 'default';
														document.getElementById('showtd'+childarray[i]).title = titleparentoff;
														document.getElementById('showtd'+childarray[i]).attributes['onclick'].value = '';
														document.getElementById('showimage'+childarray[i]).src = parentoff;
														document.getElementById('showimage'+childarray[i]).alt = titleparentoff;
													}
												} else {
													document.getElementById('showtd'+id).style.cursor = 'pointer';
													document.getElementById('showtd'+id).title = titleon;
													document.getElementById('showtd'+id).attributes['onclick'].value = 'javascript:sh_article('+ id +');';
													document.getElementById('showimage'+id).src = imgon;
													document.getElementById('showimage'+id).alt = titleon;
													
													for (var i = 0; i < childarray.length; i++) {
														if(document.getElementById('show'+childarray[i]).value == 1) {
															document.getElementById('showtd'+childarray[i]).style.cursor = 'pointer';
															document.getElementById('showtd'+childarray[i]).title = titleon;
															document.getElementById('showtd'+childarray[i]).attributes['onclick'].value = 'javascript:sh_article('+ childarray[i] +');';
															document.getElementById('showimage'+childarray[i]).src = imgon;
															document.getElementById('showimage'+childarray[i]).alt = titleon;
														} else {
															document.getElementById('showtd'+childarray[i]).style.cursor = 'pointer';
															document.getElementById('showtd'+childarray[i]).title = titleoff;
															document.getElementById('showtd'+childarray[i]).attributes['onclick'].value = 'javascript:sh_article('+ childarray[i] +');';
															document.getElementById('showimage'+childarray[i]).src = imgoff;
															document.getElementById('showimage'+childarray[i]).alt = titleoff;
														}														 
													}
												}
											} else {
												document.getElementById('show'+id).value = newbShow;
												if(newbShow == 0) {
													document.getElementById('showtd'+id).style.cursor = 'pointer';
													document.getElementById('showtd'+id).title = titleoff;
													document.getElementById('showtd'+id).attributes['onclick'].value = 'javascript:sh_article('+ id +');';
													document.getElementById('showimage'+id).src = imgoff;
													document.getElementById('showimage'+id).alt = titleoff;
												} else {
													document.getElementById('showtd'+id).style.cursor = 'pointer';
													document.getElementById('showtd'+id).title = titleon;
													document.getElementById('showtd'+id).attributes['onclick'].value = 'javascript:sh_article('+ id +');';
													document.getElementById('showimage'+id).src = imgon;
													document.getElementById('showimage'+id).alt = titleon;
												}
											}
										}
									}
								} 
							} 
							ajax.send(null); 
						}
				</script><?
				include($BF .'calendar/components/list/sortlistjs.php');
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
				$tableName = "NSOLearn";
				include($BF ."calendar/includes/overlay.php");
			}

			$q = "SELECT ID, chrKEY, chrTitle, bParent, bShow, bPShow, dtCreated, dtUpdated, idParent, dOrder,dOrderChild,
				  	(SELECT CONCAT(chrFirst,' ',chrLast) AS chrUser FROM Users AS U WHERE U.ID=L.idCreator) AS chrCUser,
				  	(SELECT CONCAT(chrFirst,' ',chrLast) AS chrUser FROM Users AS U WHERE U.ID=L.idUpdater) AS chrUpdater
				  FROM NSOLearn AS L
				  WHERE !bDeleted AND idType=1
			 	  ORDER BY dOrder,!bParent,dOrderChild,chrTitle";
			$results = db_query($q,"getting articles");
	
			if(isset($_POST['saveorder'])) { include($post_file); } 
			
			# The template to use (should be the last thing before the break)
			$title = "Site Tour Articles";						# Page Title
			$directions = 'Choose a article from the list below. Click on the column header to sort the list by that column.';
			$header_title = (access_check(17,2) ? linkto(array('address'=>'add.php','img'=>'/calendar/images/plus_add.png')):'').' Site Tour Articles <span class="resultsShown">(<span id="resultCount">'.mysqli_num_rows($results).'</span> results)</span>';
			include($BF ."calendar/models/admin.php");		

			break;
		

 		#################################################
		##	Add Page
		#################################################
		case 'add.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',17,2);
			include($BF.'calendar/components/formfields.php');

			if(isset($_POST['chrTitle'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
?>	<script type='text/javascript'>var page = 'add';</script>
	<script type='text/javascript' src='error_check.js'></script>
	<script type='text/javascript'>
		function typeselect() {
			if(document.getElementById('bParent1').checked==true) {
				document.getElementById('ParentSelect').style.display='none';
			} else {
				document.getElementById('ParentSelect').style.display='';
			}
		
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
<?

			}

			# The template to use (should be the last thing before the break)
			$title = "Add Site Tour Article";	# Page Title
			$directions = 'You are adding a Site Tour Article to the database. It is <strong>not</strong> suggested to use hard widths, but instead use percentages unless unavoidable.<br /><strong>NOTE: Please Use FireFox to add and edit Articles as some features may not be available or function properly.</strong>';
			$header_title = 'Add Site Tour Article';
			include($BF ."calendar/models/admin.php");	
			
			break;


		#################################################
		##	Edit Page
		#################################################
		case 'edit.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',17,3);
			include($BF.'calendar/components/formfields.php');
			
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Article'); } // Check Required Field for Query

			$info = db_query("SELECT *
								FROM NSOLearn
								WHERE chrKEY='". $_REQUEST['key'] ."'
			","getting info",1); // Get Info
				
			if($info['ID'] == "") { errorPage('Invalid Article'); } // Did we get a result?
			
			if(isset($_POST['chrTitle'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
			
?>	<script type='text/javascript'>var page = 'edit';</script>
	<script type='text/javascript' src='error_check.js'></script>
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
<?
			}

			# The template to use (should be the last thing before the break)
			$title = "Edit Site Tour Article";	# Page Title
			$directions = 'Please update the information below and press the "Update Information" when you are done making changes.<br />It is <strong>not</strong> suggested to use hard widths, but instead use percentages unless unavoidable.<br /><strong>NOTE: Please Use FireFox to add and edit Articles as some features may not be available or function properly.</strong>';
			$header_title = 'Edit Site Tour Article: '. $info['chrTitle'];
			include($BF ."calendar/models/admin.php");		
			
			break;
						
			
		#################################################
		##	Else show Error Page
		#################################################
		default:
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>