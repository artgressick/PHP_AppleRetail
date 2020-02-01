<?
	# This is the BASE FOLDER pointing back to the root directory
	$BF = '../../';
	
	preg_match('/(\w)+\.php$/',$_SERVER['SCRIPT_NAME'],$file_name);
    $post_file = '_'.$file_name[0];

 	switch($file_name[0]) {
		#################################################
		##	Index Page, View Event
		#################################################
		case 'index.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			auth_check('litm','1,2');
			include($BF.'calendar/components/formfields.php');
			
			$info = db_query("SELECT CalendarEvents.*, chrCalendarType
					FROM CalendarEvents 
					JOIN CalendarTypes ON CalendarTypes.ID=CalendarEvents.idCalendarType
					WHERE CalendarEvents.chrKey='". $_REQUEST['key'] ."'
				","Getting Event info",1);
			
			
			# Stuff In The Header
			function sith() { 
				global $BF;
			}

			# The template to use (should be the last thing before the break)
			$title = "View Calendar Event";	# Page Title
			$header_title = "View Event: ".$info['chrCalendarEvent'];
			$directions = '<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td>Unless you created this event, you only have view access to it.</td>';
			if($info['idUser'] == $_SESSION['idUser']) {
				$directions .=	'<td style="text-align: right; padding: 0; margin: 0;">
									<input type="button" value="Edit Event" onclick="window.location.href=\'edit.php?dBegin='.$_REQUEST['dBegin'].'&key='.$_REQUEST['key'].'&from='.$_REQUEST['from'].'\'" />
									<input type="button" value="Delete Event" onclick="window.location.href=\'delete.php?dBegin='.$_REQUEST['dBegin'].'&key='.$_REQUEST['key'].'&from='.$_REQUEST['from'].'\'" />
								</td>';
			}
				$directions .= '</tr>
								</table>';
								
			include($BF ."calendar/models/events.php");		
			
			break;

		#################################################
		##	Add Event
		#################################################
		case 'add.php':
			if(!isset($_REQUEST['dBegin']) || $_REQUEST['dBegin'] == '') { 
				header("Location: add.php?dBegin=".idate('Y').(idate('m') < 10 ? '0'.idate('m') : idate('m')).(idate('d') < 10 ? '0'.idate('d') : idate('d')));	
				die(); 
			}			
		
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			auth_check('litm','1,2');
			include($BF.'calendar/components/formfields.php');
			
 			if(isset($_POST['chrCalendarEvent'])) { include($post_file); }
 			
			# Stuff In The Header
			function sith() { 
				global $BF;
?>				<link href="<?=$BF?>calendar/includes/calendar.css" rel="stylesheet" type="text/css" />
				<script type='text/javascript'>var page = 'add';</script>
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
						
						filemanager_rootpath : "<?=realpath($BF . 'userfiles/'.$_SESSION['chrEmail'].'/')?>",
						filemanager_path : "<?=realpath($BF . 'userfiles/'.$_SESSION['chrEmail'].'/')?>",
						filemanager_extensions : "gif,jpg,htm,html,pdf,zip,txt,doc,xls",
						relative_urls : true,
						document_base_url : "<?=$PROJECT_ADDRESS?>"
					});
				</script>
				<script language="javascript" type="text/javascript">
					function colortest() {
						document.getElementById('colortest').style.backgroundColor = document.getElementById('chrColorBG').value;
						document.getElementById('colortest').style.color = document.getElementById('chrColorText').value;
					}
					/*
					// Adds a day to the script.... 
					function changedate() {
						var dates = document.getElementById('dBegin').value.split("/");
						var dt = new Date(dates[2],(dates[0] - 1),dates[1]);
						dt.setDate(dt.getDate() + 1)
						var m = (dt.getMonth() + 1);
						if(m < 10) { m = "0"+m; }
						var d = dt.getDate();
						if(d < 10) { d = "0"+d; }
						var y = (dt.getYear() + 1900);
						
						document.getElementById('dEnd').value = m+"/"+d+"/"+y
					}
					*/
					function changedate() {
						document.getElementById('dEnd').value = document.getElementById('dBegin').value;
					}
					
					function showreoccuring() {
						if(document.getElementById('bReoccur').checked) {
							document.getElementById('reoccur').style.display = "";
						} else {
							document.getElementById('reoccur').style.display = "none";
						}
					}
				</script>
<?
			}

			# The template to use (should be the last thing before the break)
			$title = "Add Event";	# Page Title
			$header_title = 'Add Event';
			$directions = 'Please remember to use FireFox for editing this section. This does not affect the user viewing the page with Safari.';		
			include($BF ."calendar/models/events.php");		
			
			break;			

		#################################################
		##	Edit Event
		#################################################
		case 'edit.php':
				# Adding in the lib file
			include($BF .'calendar/_lib.php');
			auth_check('litm','1,2');
			include($BF.'calendar/components/formfields.php');
			
			$info = db_query("SELECT CalendarEvents.*, chrCalendarType
					FROM CalendarEvents 
					JOIN CalendarTypes ON CalendarTypes.ID=CalendarEvents.idCalendarType
					WHERE CalendarEvents.chrKey='". $_REQUEST['key'] ."'
				","Getting Event info",1);
			
 			if(isset($_POST['chrCalendarEvent'])) { include($post_file); }
 			
			# Stuff In The Header
			function sith() { 
				global $BF;
?>				<link href="<?=$BF?>calendar/includes/calendar.css" rel="stylesheet" type="text/css" />
				<script type='text/javascript'>var page = 'add';</script>
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
						
						filemanager_rootpath : "<?=realpath($BF . 'userfiles/'.$_SESSION['chrEmail'].'/')?>",
						filemanager_path : "<?=realpath($BF . 'userfiles/'.$_SESSION['chrEmail'].'/')?>",
						filemanager_extensions : "gif,jpg,htm,html,pdf,zip,txt,doc,xls",
						relative_urls : true,
						document_base_url : "<?=$PROJECT_ADDRESS?>"
					});
					
					function error_check(addy) {
						if(total != 0) { reset_errors(); }  
				
						var total=0;
				
						total += ErrorCheck('chrCalendarEvent', "You must enter a Calendar Event Name.");
						total += ErrorCheck('dBegin', "You must enter a Date");
						if(total == 0) { document.getElementById('idForm').submit(); }
					}
				/*
				// Adds a day to the script.... 
				function changedate() {
					var dates = document.getElementById('dBegin').value.split("/");
					var dt = new Date(dates[2],(dates[0] - 1),dates[1]);
					dt.setDate(dt.getDate() + 1)
					var m = (dt.getMonth() + 1);
					if(m < 10) { m = "0"+m; }
					var d = dt.getDate();
					if(d < 10) { d = "0"+d; }
					var y = (dt.getYear() + 1900);
					
					document.getElementById('dEnd').value = m+"/"+d+"/"+y
				}
				*/
				function changedate() {
					document.getElementById('dEnd').value = document.getElementById('dBegin').value;
				}
				
				function showreoccuring() {
					if(document.getElementById('bReoccur').checked) {
						document.getElementById('reoccur').style.display = "";
					} else {
						document.getElementById('reoccur').style.display = "none";
					}
				}
				
				</script>
				<script language="javascript" type="text/javascript">
				function colortest() {
					document.getElementById('colortest').style.backgroundColor = document.getElementById('chrColorBG').value;
					document.getElementById('colortest').style.color = document.getElementById('chrColorText').value;
				}
				</script>
<?
			}

			# The template to use (should be the last thing before the break)
			$title = "Edit Event";	# Page Title
			$header_title = 'Edit Event: '.$info['chrCalendarEvent'];
			$directions = 'Please remember to use FireFox for editing this section. This does not affect the user viewing the page with Safari.';		
			include($BF ."calendar/models/events.php");		
			
			break;	

		#################################################
		##	Delete Event
		#################################################
		case 'delete.php':
				# Adding in the lib file
			include($BF .'calendar/_lib.php');
			auth_check('litm','1,2');
			include($BF.'calendar/components/formfields.php');
			
			$info = db_query("SELECT CalendarEvents.*, chrCalendarType
					FROM CalendarEvents 
					JOIN CalendarTypes ON CalendarTypes.ID=CalendarEvents.idCalendarType
					WHERE CalendarEvents.chrKey='". $_REQUEST['key'] ."'
				","Getting Event info",1);
			
 			if(count($_POST)) { include($post_file); }
 			
			# Stuff In The Header
			function sith() { 
				global $BF;
?>				<link href="<?=$BF?>calendar/includes/calendar.css" rel="stylesheet" type="text/css" /><?
			}

			# The template to use (should be the last thing before the break)
			$title = "Delete Event";	# Page Title
			$header_title = 'Delete Event: '.$info['chrCalendarEvent'];
			$directions = 'Deleting these events will cause them to permanently dissapear.  Please make sure this is what you want to do.';		
			include($BF ."calendar/models/events.php");		
			
			break;	
		
		#################################################
		##	Else show Error Page
		#################################################
		default:
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

	
	function get_dates($var) {
		$intDay = date('d',strtotime($var));
		$intMonth = date('m',strtotime($var));
		$intYear = date('Y',strtotime($var));
		
		return(array(
				($intDay < 10 ? '0'.$intDay : $intDay),
				$intMonth,
				$intYear,
				1-(idate('w', mktime(0, 0, 0, $intMonth, 1, $intYear))),
				idate('t', mktime(0, 0, 0, $intMonth, 1, $intYear)),
				idate('t', mktime(0, 0, 0, ($intMonth-1), 1, $intYear))
		));
	}
?>