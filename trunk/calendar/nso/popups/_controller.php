<?
	# This is the BASE FOLDER pointing back to the root directory
	$BF = '../../../';

	preg_match('/(\w)+\.php$/',$_SERVER['SCRIPT_NAME'],$file_name);
    $post_file = '_'.$file_name[0];

	switch($file_name[0]) {
		#################################################
		##	Task Popup Page
		#################################################
		case 'tasks.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',9,2);
	
			break;		
		#################################################
		##	Task Popup Page
		#################################################
		case 'corptasks.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',9,2);
	
			break;		
			
		#################################################
		##	User Popup Page
		#################################################
		case 'users.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',9,2);
	
			break;		

		#################################################
		##	Notification Popup Page
		#################################################
		case 'notifications.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',9,2);

			# Stuff In The Header
			function sith() { 
				global $BF;
?>
				<script type='text/javascript' src='<?=$BF?>includes/overlaysnew.js'></script>
				<script type='text/javascript'>
				
				function associate(id,key,fname,lname,email) {
					ajax = startAjax();
					var address = "ajax_associate.php?postType=notifications&key=<?=$_REQUEST['key']?>&idNSONotification=" + id;
				
					if(ajax) {
						ajax.open("GET", address);
					
						ajax.onreadystatechange = function() { 
							if(ajax.readyState == 4 && ajax.status == 200) { 
				//				alert(ajax.responseText);
								if(ajax.responseText != "FAIL") {
									var newID = ajax.responseText; 
									window.opener.document.getElementById('NSONotificationAssoc').innerHTML += "<tr id='NSONotificationAssoctr"+newID+"' class='ListOdd' onmouseover='RowHighlight(\"NSONotificationAssoctr"+newID+"\");' onmouseout='UnRowHighlight(\"NSONotificationAssoctr"+newID+"\");'>	<td>"+lname+"</td>	<td>"+fname+"</td> <td>"+email+"</td> <td><span class='deleteImage'><a href=\"javascript:warning("+newID+", '"+fname+" "+lname+"','"+key+"','NSONotificationAssoc');\" title=\"Delete: "+fname+" "+lname+"\"><img id='deleteButton"+newID+"' src='../../images/button_delete.png' alt='delete button' onmouseover='this.src=\"../../images/button_delete_on.png\"' onmouseout='this.src=\"../../images/button_delete.png\"' /></a></span></td> </tr> ";
									window.opener.repaint('NSONotificationAssoc');
								}
							} 
						} 
						ajax.send(null); 
					}
				}
				</script>
<?
				include($BF .'calendar/components/list/sortlistjs.php');
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
			}
			
			$results = db_query("SELECT NSONotifications.ID,NSONotifications.chrKEY,NSONotifications.chrLast,NSONotifications.chrFirst,NSONotifications.chrEmail
				FROM NSONotifications
				WHERE !NSONotifications.bDeleted
				ORDER BY chrLast,chrFirst
			","Getting users");
			
			
			$title = 'Notification Popup';
			$header_title = 'Event Notifications';
			$directions = '<table width="100%" cellpadding="0" cellspacing="0"><tr><td style="white-space:nowrap;">Select a person to be notified about this Event.</td><td style="width:10px; text-align:right;"><input type="button" onclick="window.location=\'addnotify.php?key='.$_REQUEST['key'].'\';" value="Add Person" /></td></tr></table>';
			
			include($BF ."calendar/models/popup.php");
			break;		

		#################################################
		##	SiteSurveys Popup Page
		#################################################
		case 'sitesurveys.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',9,2);

			# Stuff In The Header
			function sith() { 
				global $BF;
?>
				<script type='text/javascript' src='<?=$BF?>includes/overlaysnew.js'></script>
				<script type='text/javascript'>
				
				function associate(id,key,fname,lname,email) {
					ajax = startAjax();
					var address = "ajax_associate.php?postType=sitesurveys&key=<?=$_REQUEST['key']?>&idNSONotification=" + id;
				
					if(ajax) {
						ajax.open("GET", address);
					
						ajax.onreadystatechange = function() { 
							if(ajax.readyState == 4 && ajax.status == 200) { 
				//				alert(ajax.responseText);
								if(ajax.responseText != "FAIL") {
									var newID = ajax.responseText; 
									window.opener.document.getElementById('NSOSiteSurveyAssoc').innerHTML += "<tr id='NSOSiteSurveyAssoctr"+newID+"' class='ListOdd' onmouseover='RowHighlight(\"NSOSiteSurveyAssoctr"+newID+"\");' onmouseout='UnRowHighlight(\"NSOSiteSurveyAssoctr"+newID+"\");'>	<td>"+lname+"</td>	<td>"+fname+"</td> <td>"+email+"</td> <td><span class='deleteImage'><a href=\"javascript:warning("+newID+", '"+fname+" "+lname+"','"+key+"','NSOSiteSurveyAssoc');\" title=\"Delete: "+fname+" "+lname+"\"><img id='deleteButton"+newID+"' src='../../images/button_delete.png' alt='delete button' onmouseover='this.src=\"../../images/button_delete_on.png\"' onmouseout='this.src=\"../../images/button_delete.png\"' /></a></span></td> </tr> ";
									window.opener.repaint('NSOSiteSurveyAssoc');
								}
							} 
						} 
						ajax.send(null); 
					}
				}
				</script>
<?
				include($BF .'calendar/components/list/sortlistjs.php');
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
			}
			
			$results = db_query("SELECT NSONotifications.ID,NSONotifications.chrKEY,NSONotifications.chrLast,NSONotifications.chrFirst,NSONotifications.chrEmail
				FROM NSONotifications
				WHERE !NSONotifications.bDeleted
				ORDER BY chrLast,chrFirst
			","Getting users");
			
			
			$title = 'Site Survey Popup';
			$header_title = 'Event Site Survey Distribution';
			$directions = '<table width="100%" cellpadding="0" cellspacing="0"><tr><td style="white-space:nowrap;">Select a person to be notified about Site Surveys submitted to this Event.</td><td style="width:10px; text-align:right;"><input type="button" onclick="window.location=\'addnotify.php?key='.$_REQUEST['key'].'\';" value="Add Person" /></td></tr></table>';
			
			include($BF ."calendar/models/popup.php");
			break;		
		#################################################
		##	Evaluations Popup Page
		#################################################
		case 'evaluations.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',9,2);

			# Stuff In The Header
			function sith() { 
				global $BF;
?>
				<script type='text/javascript' src='<?=$BF?>includes/overlaysnew.js'></script>
				<script type='text/javascript'>
				
				function associate(id,key,fname,lname,email) {
					ajax = startAjax();
					var address = "ajax_associate.php?postType=evaluations&key=<?=$_REQUEST['key']?>&idNSONotification=" + id;
				
					if(ajax) {
						ajax.open("GET", address);
					
						ajax.onreadystatechange = function() { 
							if(ajax.readyState == 4 && ajax.status == 200) { 
				//				alert(ajax.responseText);
								if(ajax.responseText != "FAIL") {
									var newID = ajax.responseText; 
									window.opener.document.getElementById('NSOEvaluationsAssoc').innerHTML += "<tr id='NSOEvaluationsAssoctr"+newID+"' class='ListOdd' onmouseover='RowHighlight(\"NSOEvaluationsAssoctr"+newID+"\");' onmouseout='UnRowHighlight(\"NSOEvaluationsAssoctr"+newID+"\");'>	<td>"+lname+"</td>	<td>"+fname+"</td> <td>"+email+"</td> <td><span class='deleteImage'><a href=\"javascript:warning("+newID+", '"+fname+" "+lname+"','"+key+"','NSOEvaluationsAssoc');\" title=\"Delete: "+fname+" "+lname+"\"><img id='deleteButton"+newID+"' src='../../images/button_delete.png' alt='delete button' onmouseover='this.src=\"../../images/button_delete_on.png\"' onmouseout='this.src=\"../../images/button_delete.png\"' /></a></span></td> </tr> ";
									window.opener.repaint('NSOEvaluationsAssoc');
								}
							} 
						} 
						ajax.send(null); 
					}
				}
				</script>
<?
				include($BF .'calendar/components/list/sortlistjs.php');
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
			}
			
			$results = db_query("SELECT NSONotifications.ID,NSONotifications.chrKEY,NSONotifications.chrLast,NSONotifications.chrFirst,NSONotifications.chrEmail
				FROM NSONotifications
				WHERE !NSONotifications.bDeleted
				ORDER BY chrLast,chrFirst
			","Getting users");
			
			
			$title = 'Evaluations Popup';
			$header_title = 'Event Evaluations Distribution';
			$directions = '<table width="100%" cellpadding="0" cellspacing="0"><tr><td style="white-space:nowrap;">Select a person to be notified about Evaluations submitted to this Event.</td><td style="width:10px; text-align:right;"><input type="button" onclick="window.location=\'addnotify.php?key='.$_REQUEST['key'].'\';" value="Add Person" /></td></tr></table>';
			
			include($BF ."calendar/models/popup.php");
			break;		
			
		#################################################
		##	Add Notification Popup Page
		#################################################
		case 'addnotify.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',9,2);
			include($BF.'calendar/components/formfields.php');

			if(isset($_POST['chrEmail'])) { include($post_file); }
			
			# Stuff In The Header
			function sith() { 
				global $BF;
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
			?><script type='text/javascript' src='error_check.js'></script><?
			}
			
			
			$title = 'Add Notification Person';
			$header_title = 'Add Notification Person';
			$directions = '<table width="100%" cellpadding="0" cellspacing="0"><tr><td style="white-space:nowrap;">Enter all information and click "Add Person"</td><td style="width:10px; text-align:right;"><input type="button" onclick="window.location=\'notifications.php?key='.$_REQUEST['key'].'\';" value="Choose Notification Person" /></td></tr></table>';
			
			include($BF ."calendar/models/popup.php");
			break;	
		#################################################
		##	Add Notification Popup Page
		#################################################
		case 'addnote.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',9,2);
			include($BF.'calendar/components/formfields.php');

			# The template to use (should be the last thing before the break)
			$title = "Add Note";	# Page Title
			$directions = 'You are adding a NSO Note to the database.';
			$header_title = 'Add Note';
						
			break;

		#################################################
		##	Show Date Changes Popup Page
		#################################################
		case 'showdates.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',9,1);
			

			if(!isset($_REQUEST['key']) || strlen($_REQUEST['key']) != 40 || $_REQUEST['key'] == "") { errorPage('Invalid NSO/Remodel Event'); }
			
			$results = db_query("SELECT A.ID, A.dtDateTime, A.txtOldValue, A.txtNewValue, A.chrColumnName, U.chrFirst, U.chrLast, U.chrEmail
				FROM Audit AS A
				JOIN Users AS U ON A.idUser=U.ID
				JOIN NSOs AS N ON A.idRecord=N.ID
				WHERE A.chrTableName='NSOs' AND A.idType='2' AND A.chrColumnName IN ('dBegin','dEnd','dDate2','dDate3','dDate4') AND N.chrKEY='".$_REQUEST['key']."'
				ORDER BY A.dtDateTime DESC
			","Getting all date changes for this NSO");
			
			# Stuff In The Header
			function sith() { 
				global $BF;
				include($BF .'calendar/components/list/sortlistjs.php');
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
			}
			
			
			$title = 'View all Date Changes';
			$header_title = 'View all Date Changes';
			$custom_directions = '';
			
			include($BF ."calendar/models/popup.php");
			break;	
		#################################################
		##	Task Popup Page
		#################################################
		case 'supply.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',9,2);
	
			break;					
		#################################################
		##	Else show Error Page
		#################################################
		default:
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>