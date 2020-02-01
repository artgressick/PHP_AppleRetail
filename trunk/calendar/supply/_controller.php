<?
	# This is the BASE FOLDER pointing back to the root directory
	$BF = '../../';
	
	preg_match('/(\w)+\.php$/',$_SERVER['SCRIPT_NAME'],$file_name);
    $post_file = '_'.$file_name[0];

 	switch($file_name[0]) {
		#################################################
		##	Index Page, View Supply
		#################################################
		case 'index.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			auth_check('litm','1,2');
			include($BF.'calendar/components/formfields.php');
			
 			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid NSO Event'); }
			
			$info = db_query("SELECT N.ID,N.chrKEY,N.dtUpdated,T.chrNSOType,N.idNSOType,S.chrStoreNum, S.chrName as chrStore
				FROM NSOs AS N
				JOIN RetailStores AS S ON S.ID = N.idStore
				JOIN NSOTypes as T ON N.idNSOType = T.ID
				WHERE N.chrKEY='". $_REQUEST['key'] ."'"
			,"getting info",1); // Get Info
					
			if($info['ID'] == "") { errorPage('Invalid NSO Event'); } // Did we get a result?
			
			$q = "SELECT SA.ID,SA.chrKEY,SI.chrItem,SI.chrThumbnail, SA.dtUpdated, SA.dtCreated, SA.intQSent, SA.intQReceived
				FROM SupplyAssoc AS SA
				JOIN SupplyItems AS SI ON SI.ID=SA.idSupplyItem
				WHERE !SA.bDeleted AND !SI.bDeleted AND SA.idNSO='". $info['ID'] ."' AND SA.intQSent > 0
				ORDER BY SI.chrItem";
			
			$results = db_query($q, "Get Results");
			
			# Stuff In The Header
			function sith() { 
				global $BF;
				include($BF .'calendar/components/list/sortlistjs.php');
				include($BF .'calendar/components/miniPopup.php');
?>
				<script type='text/javascript' src='<?=$BF?>includes/overlaysnew.js'></script>
				<script type='text/javascript'>
					function updatesupply(id, idNSO) {
						ajax = startAjax();
						var value = document.getElementById('intSupply'+id).value;
						
						var address = "<?=$BF?>calendar/includes/ajax_delete.php?postType=supplyupdate&idNSO=" + idNSO + "&id=" + id + "&value="+ value;
						//alert(address);
						if(ajax) {
							ajax.open("GET", address);	
							ajax.send(null);
						}
					}
				</script>
<?
			}

			# The template to use (should be the last thing before the break)
			$title = "Supply Items";						# Page Title
			
			$directions = 'This is a list of Supplies that are being sent to you. Please update the quantity received when you receive the item(s).  Quantitys are saved as you enter them.';
			$header_title = $info['chrNSOType']." at ".$info['chrStore']." - Supply List <span class='resultsShown'>(<span id='resultCount'>".mysqli_num_rows($results)."</span> results)</span>";
			include($BF ."calendar/models/nso.php");		
			
			break;

		#################################################
		##	Else show Error Page
		#################################################
		default:
			include($BF .'calendar/_lib.php');
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}
?>