<?
	# This is the BASE FOLDER pointing back to the root directory
	$BF = '../../';

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
			auth_check('litm',7,1);
			include($BF.'calendar/components/formfields.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
				?><script type='text/javascript' src='<?=$BF?>includes/overlaysnew.js'></script><?
				include($BF .'calendar/components/list/sortlistjs.php');
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
				$tableName = "NSOs";
				//$postType = 'permDeleteSeries';
				include($BF ."calendar/includes/overlay.php");
			}

			$archivedate = date('Y-m-d',strtotime('TODAY'));
			
			if(!$_SESSION['bGlobal']) { // If User

				$Access = db_query("SELECT txtStoreAccess FROM CalendarAccess WHERE !bDeleted AND idUser=".$_SESSION['idUser'],"Getting Users Stores",1);
				if($Access['txtStoreAccess'] == '') { // Not Assigned
					$_SESSION['errorMessages'][] = "You are not assigned to any current Events. Please contact an Administrator if you feel this is an Error.";
					header("Location: ".$BF."calendar/");
					die();
				} else { // Assigned
					if(!isset($_REQUEST['idShow']) || $_REQUEST['idShow'] != 2) { $_REQUEST['idShow'] = 1; }
					$q = "SELECT NSOs.ID,NSOs.chrKEY, ".($_SESSION['bShowOrangeEvents']?"IF(NSOs.bShow=1,'<span style=\'display:none\'>Active</span><img src=\'".$BF."calendar/images/greendot.png\' alt=\'active\' />','<span style=\'display:none\'>De-Active</span><img src=\'".$BF."calendar/images/yellowdot.png\' alt=\'active\' />') AS chrIcon, ":"")."RetailStores.chrName AS chrStoreName, RetailStores.chrStoreNum, chrNSOType, RetailStores.chrRegion,
							IF(dBegin != '0000-00-00',CONCAT('<span style=\"display:none\">',dBegin,'</span>',DATE_FORMAT(dBegin,'%m/%d/%Y')),IF(NSOs.idBeginStatus=1,'<span style=\"display:none\">2099-12-31</span>TBD','<span style=\"display:none\">2099-12-31</span>Cancelled')) as dBegin2,
							IF(dBegin = '0000-00-00','2099-12-31',dBegin) as dBeginOrder,
							IF(dEnd   != '0000-00-00',CONCAT('<span style=\"display:none\">',dEnd,'</span>',DATE_FORMAT(dEnd,'%m/%d/%Y')),IF(NSOs.idEndStatus=1,'<span style=\"display:none\">2099-12-31</span>TBD','<span style=\"display:none\">2099-12-31</span>Cancelled')) as dEnd2
							FROM NSOs
							JOIN NSOTypes ON NSOTypes.ID=NSOs.idNSOType
							JOIN RetailStores ON NSOs.idStore=RetailStores.ID
							
							WHERE ".(!$_SESSION['bShowOrangeEvents']? "NSOs.bShow AND ":"")."!NSOs.bDeleted AND ((NSOs.dEnd != '0000-00-00' AND NSOs.dEnd ".($_REQUEST['idShow']==1 ? ">= '".$archivedate."') OR (NSOs.idBeginStatus=1 OR NSOs.idEndStatus=1)" : "< '".$archivedate."') OR (NSOs.idBeginStatus=2 OR NSOs.idEndStatus=2)")." ) AND RetailStores.ID IN (".$Access['txtStoreAccess'].") 
							". (isset($_REQUEST['idNSOType']) && is_numeric($_REQUEST['idNSOType']) ? ' AND NSOTypes.ID='.$_REQUEST['idNSOType'] : '') ."
							ORDER BY dBeginOrder,NSOs.chrCalendarEvent";
		
					$results = db_query($q,"getting calendar events");
					
					if(mysqli_num_rows($results) == 0) {
						$_REQUEST['idShow'] = 2;
						$q = "SELECT NSOs.ID,NSOs.chrKEY, ".($_SESSION['bShowOrangeEvents']?"IF(NSOs.bShow=1,'<span style=\'display:none\'>Active</span><img src=\'".$BF."calendar/images/greendot.png\' alt=\'active\' />','<span style=\'display:none\'>De-Active</span><img src=\'".$BF."calendar/images/yellowdot.png\' alt=\'active\' />') AS chrIcon, ":"")."RetailStores.chrName AS chrStoreName, RetailStores.chrStoreNum, chrNSOType, RetailStores.chrRegion,
								IF(dBegin != '0000-00-00',CONCAT('<span style=\"display:none\">',dBegin,'</span>',DATE_FORMAT(dBegin,'%m/%d/%Y')),IF(NSOs.idBeginStatus=1,'<span style=\"display:none\">2099-12-31</span>TBD','<span style=\"display:none\">2099-12-31</span>Cancelled')) as dBegin2,
								IF(dBegin = '0000-00-00','2099-12-31',dBegin) as dBeginOrder,
								IF(dEnd   != '0000-00-00',CONCAT('<span style=\"display:none\">',dEnd,'</span>',DATE_FORMAT(dEnd,'%m/%d/%Y')),IF(NSOs.idEndStatus=1,'<span style=\"display:none\">2099-12-31</span>TBD','<span style=\"display:none\">2099-12-31</span>Cancelled')) as dEnd2
								FROM NSOs
								JOIN NSOTypes ON NSOTypes.ID=NSOs.idNSOType
								JOIN RetailStores ON NSOs.idStore=RetailStores.ID
								
								WHERE ".(!$_SESSION['bShowOrangeEvents']? "NSOs.bShow AND ":"")."!NSOs.bDeleted AND ((NSOs.dEnd != '0000-00-00' AND NSOs.dEnd ".($_REQUEST['idShow']==1 ? ">= '".$archivedate."') OR (NSOs.idBeginStatus=1 OR NSOs.idEndStatus=1)" : "< '".$archivedate."') OR (NSOs.idBeginStatus=2 OR NSOs.idEndStatus=2)")." ) AND RetailStores.ID IN (".$Access['txtStoreAccess'].") 
								". (isset($_REQUEST['idNSOType']) && is_numeric($_REQUEST['idNSOType']) ? ' AND NSOTypes.ID='.$_REQUEST['idNSOType'] : '') ."
								ORDER BY dBeginOrder,NSOs.chrCalendarEvent";
			
						$results = db_query($q,"getting calendar events");
					}
					
					if(mysqli_num_rows($results) == 0) { // No Current Events
						$_SESSION['errorMessages'][] = "You are not assigned to any current Events. Please contact an Administrator if you feel this is an Error.";
						header("Location: ".$BF."calendar/");
						die();
					} else if (mysqli_num_rows($results) == 1) { // One Event
						$info = mysqli_fetch_assoc($results);
						header("Location: ".$BF."calendar/nso/landing.php?key=".$info['chrKEY']);
						die();
					} // else Continue
				}
			} else {  // If Admin
				if(!isset($_REQUEST['idShow']) || $_REQUEST['idShow'] != 2) { $_REQUEST['idShow'] = 1; }
			
				$q = "SELECT NSOs.ID,NSOs.chrKEY, ".($_SESSION['bShowOrangeEvents']?"IF(NSOs.bShow=1,'<span style=\'display:none\'>Active</span><img src=\'".$BF."calendar/images/greendot.png\' alt=\'active\' />','<span style=\'display:none\'>De-Active</span><img src=\'".$BF."calendar/images/yellowdot.png\' alt=\'active\' />') AS chrIcon, ":"")."RetailStores.chrName AS chrStoreName, RetailStores.chrStoreNum, chrNSOType, RetailStores.chrRegion,NSOs.bShow,
						IF(dBegin != '0000-00-00',CONCAT('<span style=\"display:none\">',dBegin,'</span>',DATE_FORMAT(dBegin,'%m/%d/%Y')),IF(NSOs.idBeginStatus=1,'<span style=\"display:none\">2099-12-31</span>TBD','<span style=\"display:none\">2099-12-31</span>Cancelled')) as dBegin2,
						IF(dBegin = '0000-00-00','2099-12-31',dBegin) as dBeginOrder,
						IF(dEnd   != '0000-00-00',CONCAT('<span style=\"display:none\">',dEnd,'</span>',DATE_FORMAT(dEnd,'%m/%d/%Y'))  ,IF(NSOs.idEndStatus=1,'<span style=\"display:none\">2099-12-31</span>TBD','<span style=\"display:none\">2099-12-31</span>Cancelled')) as dEnd2
						FROM NSOs
						JOIN NSOTypes ON NSOTypes.ID=NSOs.idNSOType
						JOIN RetailStores ON NSOs.idStore=RetailStores.ID
						WHERE ".(!$_SESSION['bShowOrangeEvents']? "NSOs.bShow AND ":"")."!NSOs.bDeleted AND ((NSOs.dEnd != '0000-00-00' AND NSOs.dEnd ".($_REQUEST['idShow']==1 ? ">= '".$archivedate."') OR (NSOs.idBeginStatus=1 OR NSOs.idEndStatus=1)" : "< '".$archivedate."') OR (NSOs.idBeginStatus=2 OR NSOs.idEndStatus=2)")." ) 
						". (isset($_REQUEST['idNSOType']) && is_numeric($_REQUEST['idNSOType']) ? ' AND NSOTypes.ID='.$_REQUEST['idNSOType'] : '') ."
						ORDER BY dBeginOrder,NSOs.chrCalendarEvent";
	
				$results = db_query($q,"getting calendar events");
			}
			
			# The template to use (should be the last thing before the break)
			$custom_directions = true; 		# In the need to have filters, set this option and write out the custom filters on the page
			$title = "NSO/Remodel Events";						# Page Title
			$directions = 'Choose a Calendar Event from the list below. Click on the column header to sort the list by that column.';
			$link = '';
			if(access_check(7,2)) {
				$link = linkto(array('address'=>'add.php','img'=>'/calendar/images/plus_add.png')).' ';
			}
			$header_title = $link."NSO & Remodel Events <span class='resultsShown'>(<span id='resultCount'>".mysqli_num_rows($results)."</span> results)</span>";
			include($BF ."calendar/models/nso.php");		

			break;
		#################################################
		##	Landing Page
		#################################################
		case 'landing.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',8,1);
			include($BF.'calendar/components/formfields.php');
			# Stuff In The Header
			function sith() { 
				global $BF;
?>	<script type='text/javascript' src='<?=$BF?>includes/showHide.js'></script><?
				include($BF .'calendar/components/list/sortlistjs.php');
				include($BF .'calendar/components/miniPopup.php');
			}
			
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid NSO Event'); }
			
			$info = db_query("SELECT N.ID,N.chrKEY,N.txtAirport,N.txtHotel,N.chrStoreManager,N.chrICSMentor,N.chrMGMentor,N.chrInStoreTrainer,N.chrMentorManager,N.dtUpdated,
							S.chrStoreNum, S.chrName as chrStore, S.chrAddress, S.chrAddress2, S.chrAddress3, S.chrCity, S.chrState, S.chrPostalCode, S.chrCountry, S.chrPhone,T.chrNSOType,N.idNSOType,
							N.dBegin, N.dDate2, N.dDate3, N.dDate4, N.dEnd, N.idBeginStatus, N.idDate2Status, N.idDate3Status, N.idDate4Status, N.idEndStatus, N.txtScope, N.bScope
				FROM NSOs AS N
				JOIN RetailStores AS S ON S.ID = N.idStore
				JOIN NSOTypes as T ON N.idNSOType = T.ID
				WHERE N.chrKEY='". $_REQUEST['key'] ."'"
			,"getting info",1); // Get Info
			
			if($info['ID'] == "") { errorPage('Invalid NSO Event'); } // Did we get a result?
			
			# The template to use (should be the last thing before the break)
			$custom_directions = true; 				# In the need to have filters, set this option and write out the custom filters on the page
			
			$supplylink = '';
			if(access_check(40,1)) {
				$q = "SELECT COUNT(SA.ID) AS intCount FROM SupplyAssoc AS SA JOIN SupplyItems AS SI ON SA.idSupplyItem=SI.ID WHERE !SI.bDeleted AND !SA.bDeleted AND SA.idNSO='".$info['ID']."' AND SA.intQSent > 0";
				$supplycheck= db_query($q,"Checking for Supply Items",1);
				if($supplycheck['intCount'] > 0) {
					$supplylink = "<li>".linkto(array('address'=>'/calendar/supply/?key='.$_REQUEST['key'],'display'=>'View Supply Items'))."</li>";
				}
			}
			
			$tabs1 = "			
				<!--6st drop down menu -->                                                   
				<div id='dropmenu6' class='dropmenudiv'>
					<ul>
						".(access_check(8,1) && access_check(9,1) ? "<li>".linkto(array('address'=>'/calendar/nso/landing.php?key='.$_REQUEST['key'],'display'=>'Landing Page'))."</li>
						<li>".linkto(array('address'=>'/calendar/nso/view.php?key='.$_REQUEST['key'],'display'=>'PM Tool'))."</li>" : "")."
						".(access_check(35,1) && 1==2? "<li>".linkto(array('address'=>'/calendar/punchlist/?key='.$_REQUEST['key'],'display'=>'View Punchlist'))."</li>" : "")."
						".(access_check(35,2) && 1==2 ? "<li>".linkto(array('address'=>'/calendar/punchlist/add.php?key='.$_REQUEST['key'],'display'=>'Add Punchlist'))."</li>" : "")."
						".(access_check(4,2) && ($info['idNSOType'] == 1 || $info['idNSOType'] == 11) ? "<li>".linkto(array('address'=>'/calendar/sitesurveys/add.php?key='.$_REQUEST['key'],'display'=>'Add Site Survey'))."</li>" : "")."
						".(access_check(4,1) && ($info['idNSOType'] == 1 || $info['idNSOType'] == 11) ? "<li>".linkto(array('address'=>'/calendar/sitesurveys/?key='.$_REQUEST['key'],'display'=>'View Site Surveys'))."</li>" : "")."
						".(access_check(5,2) ? "<li>".linkto(array('address'=>'/calendar/evals/add.php?key='.$_REQUEST['key'],'display'=>'Add Evaluation'))."</li>" : "")."
						".(access_check(5,1) ? "<li>".linkto(array('address'=>'/calendar/evals/?key='.$_REQUEST['key'],'display'=>'View Evaluations'))."</li>" : "").$supplylink."
					</ul>
				</div>";
	
			if((access_check(8,1) && access_check(9,1)) || access_check(5,2) || access_check(5,1) || ((access_check(4,1) || access_check(4,2)) && ($info['idNSOType'] == 1 || $info['idNSOType'] == 11))) { 
				$tabs = " 
		<table width='924' border='0' cellspacing='0' cellpadding='0' style='white-space: nowrap;'>
			<tr>
				<td style='width:10px;background:url(".$BF."calendar/images/tab-left.png);'>".img(array('src'=>'calendar/images/tab-left.png'))."</td>
				<td bgcolor='#A2BF67' style='vertical-align: middle;'>
					<div class='navstyle' id='nav1' style='text-align:right;padding-right:4px;'>
						<ul style='margin-top:-5px;'>
							<li><a href='#' title='NSO/Remodel Navigation' id='id-dropmenu6' style='border-left: 0;' rel='dropmenu6'>NSO/Remodel Navigation</a></li>
						</ul>
					</div>
				</td>
				<td style='width:10px;background:url(".$BF."calendar/images/tab-right.png);'>".img(array('src'=>'calendar/images/tab-right.png'))."</td>
			</tr>
		</table>
				".$tabs1."
				";
			} else { $tabs = ''; }
			$title = "NSO/Remodel Landing Page";	# Page Title
			# $directions = 'Choose a Calendar Event from the list below. Click on the column header to sort the list by that column.';
			$header_title = $info['chrNSOType'];
			$bodyParams = 'dropdown.startnav("nav1");';
			include($BF ."calendar/models/nso.php");		

			break;
 		#################################################
		##	View Page
		#################################################
		case 'view.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',9,1);
			include($BF.'calendar/components/formfields.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
				include($BF .'calendar/components/list/sortlistjs.php');
				include($BF .'calendar/components/miniPopup.php');
				
			/*	<script type='text/javascript' src='<?=$BF?>includes/showHide.js'></script> */
?>				<script type='text/javascript' src='<?=$BF?>includes/overlaysnew.js'></script>
				
				<script type='text/javascript'>
					function changepercent(id,idNSO) {
						ajax = startAjax();
						if(document.getElementById('complete'+id).checked == true) {
							var value=100;
						} else {
							var value=0;
						}
						var address = "<?=$BF?>calendar/includes/ajax_delete.php?postType=percentchange&idNSO=" + idNSO + "&id=" + id + "&value="+ value;
						//alert(address);
						if(ajax) {
							ajax.open("GET", address);	
							ajax.send(null);
						}
					}

					function corpchangepercent(id,idNSO) {
						ajax = startAjax();
						if(document.getElementById('complete'+id).checked == true) {
							var value=100;
						} else {
							var value=0;
						}
						var address = "<?=$BF?>calendar/includes/ajax_delete.php?postType=corppercentchange&idNSO=" + idNSO + "&id=" + id + "&value="+ value;
						//alert(address);
						if(ajax) {
							ajax.open("GET", address);	
							ajax.send(null);
						}
					}

					function updatesupply(id, idNSO) {
						ajax = startAjax();
						var value = document.getElementById('intSupply'+id).value;
						
						var address = "<?=$BF?>calendar/includes/ajax_delete.php?postType=supplychange&idNSO=" + idNSO + "&id=" + id + "&value="+ value;
						//alert(address);
						if(ajax) {
							ajax.open("GET", address);	
							ajax.send(null);
						}
					}

					function showhide(boxname) {
						if(document.getElementById(boxname+'box').style.display == 'none') {
							document.getElementById(boxname+'box').style.display = '';
						} else {
							document.getElementById(boxname+'box').style.display = 'none';
						}
					}
				</script>
				<style type='text/css'>
					.headerBlock { background: #ccc; width: 50px; padding: 2px 4px; white-space:nowrap; }
					.infoBlock { width: 300px; padding: 2px 4px; white-space:nowrap; }
					.showHideTitle { font-size: 14px; background: #99C0DF; padding: 5px 10px; margin-top: 10px; cursor: pointer; }
					.showHideBody { padding: 5px; border: 1px solid #99C0DF; }
				</style>
<?
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
				$tableName = "";
				$postType = 'permDelete';
				include($BF ."calendar/includes/overlay.php");
			}


			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid NSO Calendar Event'); }

			$info = db_query("SELECT NSOs.ID,NSOs.chrKEY,NSOs.chrCalendarEvent,dBegin,dEnd,dDate2,dDate3,dDate4,dtUpdated,idBeginStatus,idDate2Status,idDate3Status,idDate4Status,idEndStatus,
					RetailStores.chrName as chrStore,RetailStores.chrStoreNum,NSOTypes.chrNSOType,idNSOType
				FROM NSOs
				JOIN RetailStores ON RetailStores.ID=NSOs.idStore
				JOIN NSOTypes ON NSOTypes.ID=NSOs.idNSOType
				WHERE NSOs.chrKEY='".$_REQUEST['key'] ."'"
			,'getting nso info',1);
				
			if($info['ID'] == "") { errorPage('Invalid NSO Calendar Event'); } // Did we get a result?

			
/*
 			$NSOTasks = db_query('SELECT NSOTaskAssoc.ID,NSOTaskAssoc.chrKEY,NSOTasks.chrNSOTask,NSOTaskAssoc.intDateOffset, NSOTaskAssoc.intNSOTaskStatus,NSOTaskAssoc.intDateOffset,dOrder,
									DATE_FORMAT(adddate("'.$info['dBegin'].'",NSOTaskAssoc.intDateOffset),"%W, %M %D, %Y") as dOffset 
								FROM NSOTaskAssoc 
								JOIN NSOTasks ON NSOTasks.ID = NSOTaskAssoc.idNSOTask
								WHERE !bDeleted AND NSOTaskAssoc.idNSO="'. $info['ID'] .'"
								ORDER BY dOrder,NSOTaskAssoc.intDateOffset'
							,'getting tasks');
 */			
			
			$NSOTasks = db_query('SELECT NSOTaskAssoc.ID,NSOTaskAssoc.chrKEY,NSOTasks.chrNSOTask,NSOTaskAssoc.intDateOffset, NSOTaskAssoc.intNSOTaskStatus,NSOTaskAssoc.intDateOffset,dOrder
								FROM NSOTaskAssoc 
								JOIN NSOTasks ON NSOTasks.ID = NSOTaskAssoc.idNSOTask
								WHERE !bDeleted AND NSOTaskAssoc.idNSO="'. $info['ID'] .'"
								ORDER BY dOrder,NSOTaskAssoc.intDateOffset'
							,'getting tasks');

			$NSOCorpTasks = db_query('SELECT NSOCorpTaskAssoc.ID,NSOCorpTaskAssoc.chrKEY,NSOCorpTasks.chrNSOCorpTask,NSOCorpTaskAssoc.intDateOffset, NSOCorpTaskAssoc.intNSOTaskStatus,NSOCorpTaskAssoc.intDateOffset,dOrder
								FROM NSOCorpTaskAssoc 
								JOIN NSOCorpTasks ON NSOCorpTasks.ID = NSOCorpTaskAssoc.idNSOCorpTask
								WHERE !bDeleted AND NSOCorpTaskAssoc.idNSO="'. $info['ID'] .'"
								ORDER BY dOrder,NSOCorpTaskAssoc.intDateOffset'
							,'getting Corp tasks');
							
			$Notifications = db_query('SELECT NSONotificationAssoc.ID,NSONotifications.chrKEY,NSONotifications.chrFirst,NSONotifications.chrLast,NSONotifications.chrEmail 
				FROM NSONotificationAssoc
				JOIN NSONotifications ON NSONotifications.ID=NSONotificationAssoc.idNSONotification
				WHERE !NSONotifications.bDeleted AND idNSO="'. $info['ID'] .'"
				ORDER BY NSONotifications.chrLast,NSONotifications.chrFirst'
			,'getting notifications');

	if(($info['idNSOType'] == 1 || $info['idNSOType'] == 11)) {
			$SiteSurveys = db_query('SELECT NSOSiteSurveyAssoc.ID,NSONotifications.chrKEY,NSONotifications.chrFirst,NSONotifications.chrLast,NSONotifications.chrEmail 
				FROM NSOSiteSurveyAssoc
				JOIN NSONotifications ON NSONotifications.ID=NSOSiteSurveyAssoc.idNSONotification
				WHERE !NSONotifications.bDeleted AND idNSO="'. $info['ID'] .'"
				ORDER BY NSONotifications.chrLast,NSONotifications.chrFirst'
			,'getting notifications');
	}		

			$Evaluations = db_query('SELECT NSOEvaluationsAssoc.ID,NSONotifications.chrKEY,NSONotifications.chrFirst,NSONotifications.chrLast,NSONotifications.chrEmail 
				FROM NSOEvaluationsAssoc
				JOIN NSONotifications ON NSONotifications.ID=NSOEvaluationsAssoc.idNSONotification
				WHERE !NSONotifications.bDeleted AND idNSO="'. $info['ID'] .'"
				ORDER BY NSONotifications.chrLast,NSONotifications.chrFirst'
			,'getting notifications');
			
			$SupplyItems = db_query('SELECT SA.ID,SA.chrKEY,SI.chrItem,SI.chrThumbnail, SA.dtUpdated, SA.dtCreated, SA.intQSent, SA.intQReceived
				FROM SupplyAssoc AS SA
				JOIN SupplyItems AS SI ON SI.ID=SA.idSupplyItem
				WHERE !SA.bDeleted AND !SI.bDeleted AND SA.idNSO="'. $info['ID'] .'"
				ORDER BY SI.chrItem'
			,'getting Supply Items');
	
			$Notes = db_query('SELECT NSONotes.ID,NSONotes.chrKEY,NSONotes.txtNote
				FROM NSONotes
				WHERE !NSONotes.bDeleted AND idNSO="'. $info['ID'] .'"
				ORDER BY NSONotes.txtNote'
			,'getting notes');
			
			$Pictures = db_query('SELECT CalendarFiles.ID,CalendarFiles.chrKEY, chrFileTitle, chrCalendarFile,chrThumbnail,dtCreated AS dtCreatedNF,chrNSOPictureGroup, DATE_FORMAT(dtCreated,"%c/%e/%Y - %l:%i %p") AS dtCreated 
				FROM CalendarFiles
				JOIN NSOPictureGroups ON NSOPictureGroups.ID=CalendarFiles.idNSOFileGroup 
				WHERE idCalendarFileType=1 AND idNSO='.$info['ID'].' 
				ORDER BY bPrimary DESC,chrFileTitle,chrCalendarFile
			' ,'getting Pictures');
			
			$Documents = db_query('SELECT CalendarFiles.ID,CalendarFiles.chrKEY, chrFileTitle, chrCalendarFile,chrNSOFileGroup,dtCreated AS dtCreatedNF,DATE_FORMAT(CalendarFiles.dtCreated,"%c/%e/%Y - %l:%i %p") AS dtCreated
				FROM CalendarFiles
				JOIN NSOFileGroups ON NSOFileGroups.ID=CalendarFiles.idNSOFileGroup
				WHERE idCalendarFileType=2 AND idNSO='.$info['ID'].'
				ORDER BY chrFileTitle,chrCalendarFile
			','getting Documents');
			
			$Datehistory = db_query("SELECT A.ID, A.dtDateTime, A.txtOldValue, A.txtNewValue, A.chrColumnName, U.chrFirst, U.chrLast, U.chrEmail
				FROM Audit AS A
				JOIN Users AS U ON A.idUser=U.ID
				WHERE A.chrTableName='NSOs' AND A.idType='2' AND A.chrColumnName IN ('dBegin','dEnd','dDate2','dDate3','dDate4') AND A.idRecord=".$info['ID']." 
				ORDER BY dtDateTime DESC
			","Getting all date changes for this NSO");
			
							
			# The template to use (should be the last thing before the break)
			$tabs1 = "			
				<!--6st drop down menu -->                                                   
				<div id='dropmenu6' class='dropmenudiv'>
					<ul>
						".(access_check(8,1) && access_check(9,1) ? "<li>".linkto(array('address'=>'/calendar/nso/landing.php?key='.$_REQUEST['key'],'display'=>'Landing Page'))."</li>
						<li>".linkto(array('address'=>'/calendar/nso/view.php?key='.$_REQUEST['key'],'display'=>'PM Tool'))."</li>" : "")."
						".(access_check(4,1) && ($info['idNSOType'] == 1 || $info['idNSOType'] == 11) ? "<li>".linkto(array('address'=>'/calendar/sitesurveys/add.php?key='.$_REQUEST['key'],'display'=>'Add Site Survey'))."</li>" : "")."
						".(access_check(4,1) && ($info['idNSOType'] == 1 || $info['idNSOType'] == 11) ? "<li>".linkto(array('address'=>'/calendar/sitesurveys/?key='.$_REQUEST['key'],'display'=>'View Site Surveys'))."</li>" : "")."
						".(access_check(5,2) ? "<li>".linkto(array('address'=>'/calendar/evals/add.php?key='.$_REQUEST['key'],'display'=>'Add Evaluation'))."</li>" : "")."
						".(access_check(5,1) ? "<li>".linkto(array('address'=>'/calendar/evals/?key='.$_REQUEST['key'],'display'=>'View Evaluations'))."</li>" : "")."
						</ul>
				</div>";
			
			if((access_check(8,1) && access_check(9,1)) || access_check(5,2) || access_check(5,1) || (access_check(4,1) && $info['idNSOType'] == 1)) { 
				$tabs = " 
		<table width='924' border='0' cellspacing='0' cellpadding='0' style='white-space: nowrap;'>
			<tr>
				<td style='width:10px;background:url(".$BF."calendar/images/tab-left.png);'>".img(array('src'=>'calendar/images/tab-left.png'))."</td>
				<td bgcolor='#A2BF67' style='vertical-align: middle;'>
					<div class='navstyle' id='nav1' style='text-align:right;padding-right:4px;'>
						<ul style='margin-top:-5px;'>
							<li><a href='#' title='NSO/Remodel Navigation' id='id-dropmenu6' style='border-left: 0;' rel='dropmenu6'>NSO/Remodel Navigation</a></li>
						</ul>
					</div>
				</td>
				<td style='width:10px;background:url(".$BF."calendar/images/tab-right.png);'>".img(array('src'=>'calendar/images/tab-right.png'))."</td>
			</tr>
		</table>
				".$tabs1;
			} else { $tabs = ''; }

			$title = "PM Tool: ". $info['chrStore']. " / ". $info['chrStoreNum'];	# Page Title
			$directions = 'You are looking at a NSO/Remodel event.';
			$header_title = 'PM Tool: '. $info['chrStore']. ' / '. $info['chrStoreNum'];
//--> Arthur
			$subnav = 0;		
			$bodyParams = 'dropdown.startnav("nav1");';	
			include($BF ."calendar/models/nso.php");	
			
			break;
			
		#################################################
		##	Survey Page
		#################################################
		case 'sitesurvey.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2');
			include($BF.'calendar/components/formfields.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
				include($BF .'calendar/components/list/sortlistjs.php');
				include($BF .'calendar/components/miniPopup.php');
?>	<script type='text/javascript' src='<?=$BF?>includes/overlaysnew.js'></script>
	<script type='text/javascript' src='<?=$BF?>includes/showHide.js'></script>
	<style type='text/css'>
		.headerBlock { background: #ccc; width: 150px; padding: 2px 4px; }
		.infoBlock { width: 300px; padding: 2px 4px; }
		.showHideTitle { font-size: 14px; background: #99C0DF; padding: 5px 10px; margin-top: 10px; cursor: pointer; }
		.showHideBody { padding: 5px; border: 1px solid #99C0DF; }
	</style>
<?
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
				$tableName = "";
				$postType = 'permDelete';
				include($BF ."calendar/includes/overlay.php");
			}


			# Check for KEY, if not Error, Get $info, Error if no results
/*			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid NSO Calendar Event'); }

			$info = db_query("SELECT CalendarEvents.ID,CalendarEvents.chrKEY,chrCalendarEvent,dBegin,
					RetailStores.chrName as chrStore,NSOTypes.chrNSOType,
					(SELECT DATE_FORMAT(MAX(CE.dEnd),'%m/%d/%Y') FROM CalendarEvents as CE WHERE CE.chrSeries=CalendarEvents.chrSeries) as dEnd
				FROM CalendarEvents
				JOIN RetailStores ON RetailStores.ID=CalendarEvents.idStore
				JOIN NSOTypes ON NSOTypes.ID=CalendarEvents.idNSOType
				WHERE CalendarEvents.chrKEY='".$_REQUEST['key'] ."'"
			,'getting calendar event',1);
				
			if($info['ID'] == "") { errorPage('Invalid NSO Calendar Event'); } // Did we get a result?
*/
			# The template to use (should be the last thing before the break)
			$title = "Site Survey: ". $info['chrStore']. " / ". $info['chrStoreNum'];	# Page Title
			$directions = 'This is the site survey page for '. $info['chrStore']. ' / '. $info['chrStoreNum'].'.';
			$header_title = 'Site Survey: '. $info['chrStore']. ' / '. $info['chrStoreNum'];
			include($BF ."calendar/models/nso.php");	
			
			break;

		#################################################
		##	NSO Evaluation Page
		#################################################
		case 'eval.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2');
			include($BF.'calendar/components/formfields.php');

			# Stuff In The Header
			function sith() { 
				global $BF;
				include($BF .'calendar/components/list/sortlistjs.php');
				include($BF .'calendar/components/miniPopup.php');
?>	<script type='text/javascript' src='<?=$BF?>includes/overlaysnew.js'></script>
	<script type='text/javascript' src='<?=$BF?>includes/showHide.js'></script>
	<style type='text/css'>
		.headerBlock { background: #ccc; width: 150px; padding: 2px 4px; }
		.infoBlock { width: 300px; padding: 2px 4px; }
		.showHideTitle { font-size: 14px; background: #99C0DF; padding: 5px 10px; margin-top: 10px; cursor: pointer; }
		.showHideBody { padding: 5px; border: 1px solid #99C0DF; }
	</style>
<?
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
				$tableName = "";
				$postType = 'permDelete';
				include($BF ."calendar/includes/overlay.php");
			}


			# Check for KEY, if not Error, Get $info, Error if no results
/*			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid NSO Calendar Event'); }

			$info = db_query("SELECT CalendarEvents.ID,CalendarEvents.chrKEY,chrCalendarEvent,dBegin,
					RetailStores.chrName as chrStore,NSOTypes.chrNSOType,
					(SELECT DATE_FORMAT(MAX(CE.dEnd),'%m/%d/%Y') FROM CalendarEvents as CE WHERE CE.chrSeries=CalendarEvents.chrSeries) as dEnd
				FROM CalendarEvents
				JOIN RetailStores ON RetailStores.ID=CalendarEvents.idStore
				JOIN NSOTypes ON NSOTypes.ID=CalendarEvents.idNSOType
				WHERE CalendarEvents.chrKEY='".$_REQUEST['key'] ."'"
			,'getting calendar event',1);
				
			if($info['ID'] == "") { errorPage('Invalid NSO Calendar Event'); } // Did we get a result?
*/
			# The template to use (should be the last thing before the break)
			$title = "Site Evaluation: ". $info['chrStore']. " / ". $info['chrStoreNum'];	# Page Title
			$directions = 'This is the site evaluation page for this Remodel/NSO.';
			$header_title = 'Site Evaluation: '. $info['chrStore']. ' / '. $info['chrStoreNum'];
			include($BF ."calendar/models/nso.php");	
			
			break;
 		
		
		#################################################
		##	Add Page
		#################################################
		case 'add.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',7,2);
			include($BF.'calendar/components/formfields.php');

			if(isset($_POST['idStore'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
?>	<script type='text/javascript'>var page = 'add';</script>
	<script type='text/javascript' src='error_check.js'></script>
	<script type='text/javascript'>
		function dateselect(field,value) {
			if(document.getElementById('id'+field+'Status'+value).checked == true) {
				if(value==1) {
					document.getElementById('id'+field+'Status2').checked = false;
					document.getElementById('d'+field).value = '';
				} else {
					document.getElementById('id'+field+'Status1').checked = false;
					document.getElementById('d'+field).value = '';
				}
			}
		}
		function dateentry(field) {
			if(document.getElementById('d'+field).value != '') {
				document.getElementById('id'+field+'Status2').checked = false;
				document.getElementById('id'+field+'Status1').checked = false;
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
		mode : "specific_textareas",
		editor_selector : "mceEditor",
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
			$title = "Add Event";	# Page Title
			$directions = 'You are adding a Event to the database.';
			$header_title = 'Add Event';
			include($BF ."calendar/models/nso.php");	
			
			break;


		#################################################
		##	Edit Page
		#################################################
		case 'edit.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',7,3);
			include($BF.'calendar/components/formfields.php');
			
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid NSO Calendar Event'); } // Check Required Field for Query

			$info = db_query("SELECT NSOs.*, RetailStores.chrName as chrStore, RetailStores.chrStoreNum,NSOs.chrKEY AS chrNSOKey,T.chrNSOType
								FROM NSOs
								JOIN RetailStores ON RetailStores.ID=NSOs.idStore
								JOIN NSOTypes AS T ON T.ID=NSOs.idNSOType
								WHERE NSOs.chrKEY='". $_REQUEST['key'] ."'
			","getting info",1); // Get Info
				
			if($info['ID'] == "") { errorPage('Invalid NSO Calendar Event'); } // Did we get a result?
			
			if($info['dBegin'] == '0000-00-00') { $info['dBegin'] = ''; }
			if($info['dEnd'] == '0000-00-00') { $info['dEnd'] = ''; }
			if($info['dDate2'] == '0000-00-00') { $info['dDate2'] = ''; }
			if($info['dDate3'] == '0000-00-00') { $info['dDate3'] = ''; }
			if($info['dDate4'] == '0000-00-00') { $info['dDate4'] = ''; }
			
			
			if(isset($_POST['idStore'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
			
?>	<script type='text/javascript'>var page = 'edit';</script>
	<script type='text/javascript' src='error_check.js'></script>
	<script type='text/javascript'>
		function dateselect(field,value) {
			if(document.getElementById('id'+field+'Status'+value).checked == true) {
				if(value==1) {
					document.getElementById('id'+field+'Status2').checked = false;
					document.getElementById('d'+field).value = '';
				} else {
					document.getElementById('id'+field+'Status1').checked = false;
					document.getElementById('d'+field).value = '';
				}
			}
		}
		function dateentry(field) {
			if(document.getElementById('d'+field).value != '') {
				document.getElementById('id'+field+'Status2').checked = false;
				document.getElementById('id'+field+'Status1').checked = false;
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
		mode : "specific_textareas",
		editor_selector : "mceEditor",
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
			$title = "Edit Event";	# Page Title
			$directions = 'Please update the information below and press the "Update Information" when you are done making changes.';
			$header_title = 'Edit Event: '. $info['chrStore']. ' / '. $info['chrStoreNum'];
			include($BF ."calendar/models/admin.php");		
			
			break;
						

		#################################################
		##	Tasks Page
		#################################################
		case 'tasks.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',0);
			include($BF.'calendar/components/formfields.php');
			
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid NSO Calendar Event'); } // Check Required Field for Query

			$info = db_query("SELECT NSOTaskAssoc.ID,NSOTaskAssoc.chrKEY,NSOTaskAssoc.intNSOTaskStatus,NSOTaskAssoc.intDateOffset,NSOTaskAssoc.idUser,NSOTaskAssoc.txtNote,NSOTasks.chrNSOTask, NSOTaskAssoc.idNSO
					FROM NSOTaskAssoc 
					JOIN NSOTasks ON NSOTasks.ID=NSOTaskAssoc.idNSOTask					
					WHERE NSOTaskAssoc.chrKEY='". $_REQUEST['key'] ."'"
				,"getting info",1); // Get Info
			//if($info['ID'] == "") { errorPage('Invalid NSO Task'); } // Did we get a result?
			
			if(isset($_POST['intNSOTaskStatus'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
			
?>	<script type='text/javascript'>var page = 'edit';</script>
	<script type='text/javascript' src='error_check.js'></script>
<?
			}

			# The template to use (should be the last thing before the break)
			$title = "NSO/Remodel Task";	# Page Title
			$directions = 'Please update the information below and press the "Update Information" when you are done making changes.';
			$header_title = 'Edit NSO/Remodel Task: '.$info['chrNSOTask'];
			include($BF ."calendar/models/nso.php");		
			
			break;
		#################################################
		##	Corp Tasks Page
		#################################################
		case 'corptasks.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm',0);
			include($BF.'calendar/components/formfields.php');
			
			
			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Corporate Partner Task'); } // Check Required Field for Query

			$info = db_query("SELECT NSOCorpTaskAssoc.ID,NSOCorpTaskAssoc.chrKEY,NSOCorpTaskAssoc.intNSOTaskStatus,NSOCorpTaskAssoc.intDateOffset,NSOCorpTaskAssoc.idUser,NSOCorpTaskAssoc.txtNote,NSOCorpTasks.chrNSOCorpTask, NSOCorpTaskAssoc.idNSO
					FROM NSOCorpTaskAssoc 
					JOIN NSOCorpTasks ON NSOCorpTasks.ID=NSOCorpTaskAssoc.idNSOCorpTask					
					WHERE NSOCorpTaskAssoc.chrKEY='". $_REQUEST['key'] ."'"
				,"getting info",1); // Get Info
			if($info['ID'] == "") { errorPage('Invalid Corporate Partner Task'); } // Did we get a result?
			
			if(isset($_POST['intNSOTaskStatus'])) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF;
			
?>	<script type='text/javascript'>var page = 'edit';</script>
	<script type='text/javascript' src='error_check.js'></script>
<?
			}

			# The template to use (should be the last thing before the break)
			$title = "Corporate Partner Task";	# Page Title
			$directions = 'Please update the information below and press the "Update Information" when you are done making changes.';
			$header_title = 'Edit Corporate Partner Task: '.$info['chrNSOCorpTask'];
			include($BF ."calendar/models/nso.php");		
			
			break;
			
		#################################################
		##	Download Page
		#################################################
		case 'download.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2');

			# Check for KEY, if not Error, Get $info, Error if no results
			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid File'); } // Check Required Field for Query

			$info = db_query("SELECT ID, chrCalendarFile, idCalendarFileType
								FROM CalendarFiles
								WHERE chrKEY='". $_REQUEST['key'] ."'
			","getting info",1); // Get Info
				
			if($info['ID'] == "") { errorPage('Invalid File'); } // Did we get a result?
			
			if($info['idCalendarFileType'] == 1) {
				header("Location: ".$BF."calendar/nsopictures/".$info['chrCalendarFile']);
				die();
			} else if ($info['idCalendarFileType'] == 2) {
				header("Location: ".$BF."calendar/nsodocuments/".$info['chrCalendarFile']);
				die();
			}
			
			break;
			
		#################################################
		##	Popup Page
		#################################################
		case 'popup.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			# Auth Check, enable this if the page requires you to be logged in
			auth_check('litm','1,2');

			# Stuff In The Header
			function sith() { 
				global $BF;
			}
	
			break;		
		#################################################
		##	Else show Error Page
		#################################################
		default:
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}

?>