<?
	# This is the BASE FOLDER pointing back to the root directory
	$BF = '../../';
	
	$file_name = basename($_SERVER['SCRIPT_NAME']);
    $post_file = '_'.$file_name;

 	switch($file_name) {
		#################################################
		##	Index Page, List Evaluation
		#################################################
		case 'index.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			auth_check('litm',5,1);
			include($BF.'calendar/components/formfields.php');

			if(isset($_REQUEST['key']) && strlen($_REQUEST['key']) == 40) {
				$info = db_query("SELECT NSOs.ID, Stores.chrName AS chrStore, Stores.chrStoreNum
									FROM NSOs
									JOIN RetailStores AS Stores ON NSOs.idStore=Stores.ID
									WHERE NSOs.chrKEY = '".$_REQUEST['key']."'
									","NSO and Store Info",1);

				if($info['ID'] == "") { errorPage('Invalid NSO/Remodel Event'); } // Did we get a result?
			}
			
			$results = db_query("SELECT E.ID,E.chrKEY,CONCAT('<span style=\"display:none;\">',E.dtStamp,\"</span>\",DATE_FORMAT(E.dtStamp,'%c/%e/%Y %l:%i %p')) AS dtStamp,CONCAT(Users.chrFirst,' ',Users.chrLast) AS chrCreator, Stores.chrName as chrStore, Stores.chrStoreNum
					FROM NSOEvals AS E
					JOIN Users ON E.idUser=Users.ID
					JOIN NSOs ON E.idNSO=NSOs.ID
					JOIN RetailStores AS Stores ON NSOs.idStore=Stores.ID
					WHERE ".($_SESSION['bGlobal'] ? "":"NSOs.bShow AND ")."!E.bDeleted AND !NSOs.bDeleted AND !Stores.bDeleted ".(isset($info['ID']) ? " AND NSOs.ID=".$info['ID']." ":"").(isset($_REQUEST['idStore']) && is_numeric($_REQUEST['idStore']) && !isset($info['ID']) ? " AND Stores.ID=".$_REQUEST['idStore']." ":"")." ".(!$_SESSION['bGlobal'] ? " AND Stores.ID IN (".$_SESSION['txtStoreAccess'].") " : "")." 
					ORDER BY E.dtStamp DESC,Stores.chrName
				","Getting Evaluations info");
			
			# Stuff In The Header
			function sith() { 
				global $BF,$results;
				?><script type='text/javascript' src='<?=$BF?>includes/overlaysnew.js'></script><?
				include($BF .'calendar/components/list/sortlistjs.php');
			}

			# Stuff On The Bottom
			function sotb() { 
				global $BF;
				$tableName = "NSOEvals";
				include($BF ."calendar/includes/overlay.php");
			}

			# The template to use (should be the last thing before the break)
			$title = "Evaluations".(isset($info['ID']) ? ": ".$info['chrStore']." / ".$info['chrStoreNum'] :"");	# Page Title
			$header_title = "Evaluations".(isset($info['ID']) ? ": ".$info['chrStore']." / ".$info['chrStoreNum'] :"");
			$directions = 'Please select an Evaluation from the list below to view the details.';
			$custom_directions = '';
								
			include($BF ."calendar/models/nso.php");		
			
			break;

		#################################################
		##	Add Page, Do Eval
		#################################################
		case 'add.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			auth_check('litm',5,2);
			include($BF.'calendar/components/formfields.php');
			
/*			if(!isset($_REQUEST['key']) && strlen($_REQUEST['key']) != 40) {
				$results = db_query("SELECT chrKEY FROM Evals ORDER BY ID DESC LIMIT 1","getting latest key",1);
				header('Location: add.php?key='.$results['chrKEY']);
				die();
			}
*/
 			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid NSO/Remodel Event'); } // Check Required Field for Query

 			$info = db_query("SELECT Stores.*, NSOs.ID as idNSO, (SELECT ID FROM Evals WHERE !bDeleted ORDER BY ID DESC LIMIT 1) AS idEval, NSOs.chrKEY AS chrNSOKey,T.chrNSOType
								FROM NSOs
								JOIN RetailStores AS Stores ON Stores.ID=NSOs.idStore
								JOIN NSOTypes AS T ON T.ID=NSOs.idNSOType
								WHERE NSOs.chrKEY='". $_REQUEST['key'] ."'
			","getting info",1); // Get Info
 			
 			if (isset($_POST['id']) && is_numeric($_POST['id'])) {
 				$info['idEval'] = $_POST['id'];
 			}
 			
			if($info['ID'] == "") { 
				errorPage('Invalid NSO/Remodel Event');
			} else if ($info['idEval'] == "") {
				errorPage('Sorry there are no evaluations available at this time.');
			}
			
			$results = db_query("SELECT EQ.ID,EQ.bRequired,EQ.idEvalType,EQ.dOrder,EQ.chrQuestion,EQ.txtOptions,EQ.idEval,EQ.idEvalCat,EC.chrCat
				FROM EvalQuestions AS EQ
				JOIN EvalCats AS EC ON EQ.idEvalCat = EC.ID
				WHERE EQ.idEval = ".$info['idEval']."
				ORDER BY EC.intOrder, EC.chrCat, EQ.dOrder,EQ.idEvalType,EQ.chrQuestion
			","Getting Event info");
			
 			if(count($_POST)) { include($post_file); }

			# Stuff In The Header
			function sith() { 
				global $BF,$results;
?>
<script type="text/javascript" src="<?=$BF?>calendar/includes/forms.js"></script>
<script type="text/javascript">
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
	totalErrors = 0;
<?	$i = 1;
	while($row = mysqli_fetch_assoc($results)) {
		if($row['bRequired']) {
			if($row['idEvalType'] == 1 || $row['idEvalType'] == 2 || $row['idEvalType'] == 3) {
?> 	if(errEmpty('<?=$row['ID']?>', "Question #<?=$i?> must be answered.")) { totalErrors++; }<?
			} else {
?> 	if(errEmpty('<?=$row['ID']?>[]', "Question #<?=$i?> must be answered.","array")) { totalErrors++; }<?
			}
		}
		$i++;
	}
	mysqli_data_seek($results,0);
?>

	return (totalErrors == 0 ? true : false);
}



function newOption(num,table) {
	var currentnum = parseInt(document.getElementById('int'+table).value) + 1;
	document.getElementById('int'+table).value = currentnum;
	
	var tr = document.createElement('tr');
	var td1 = document.createElement('td');
	var td2 = document.createElement('td');
	
	td1.innerHTML = "File "+ currentnum +":";
	td2.id = table+"file"+ currentnum;
	td2.innerHTML = "<input type='file' name='chr"+table+"File"+ currentnum +"' id='chr"+table+"File"+ currentnum +"' />";
	
	tr.appendChild(td1);
	tr.appendChild(td2);
	document.getElementById(table+"tbody").appendChild(tr);
}

</script>
<?
			}

			# The template to use (should be the last thing before the break)
			$title = "Evaluation";	# Page Title
			$header_title = "Evaluation";
			$directions = 'Please fill out the Evaluation below, and press the "Submit Information" button below to finish.';
								
			include($BF ."calendar/models/nso.php");		
			
			break;

		#################################################
		##	Thanks page
		#################################################
		case 'thanks.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			auth_check('litm',5,2);
			include($BF.'calendar/components/formfields.php');
			

			# The template to use (should be the last thing before the break)
			$title = "Evalution Complete";	# Page Title
			$header_title = "Evaluation Complete";
			$directions = 'To leave this page, please click on the button at the bottom of the page;';
								
			include($BF ."calendar/models/nso.php");		
			
			break;

		#################################################
		##	View Page
		#################################################
		case 'view.php':
			# Adding in the lib file
			include($BF .'calendar/_lib.php');
			auth_check('litm',5,1);
			include($BF.'calendar/components/formfields.php');
			
			# Stuff In The Header
			function sith() { 
				global $BF;
?>			
			<style type="text/css">
				.FormName { font-size: 12px; line-height: 14px; font-weight: bold; margin-left:2px; }
			</style>
<?
			}

 			if(!isset($_REQUEST['key']) || $_REQUEST['key'] == "") { errorPage('Invalid Evaluation'); } // Check Required Field for Query

 			$info = db_query("SELECT E.ID, E.idEval, E.idNSO, S.chrName, S.chrStoreNum, S.chrAddress, S.chrAddress2, S.chrAddress3, S.chrCity, S.chrState,
 							  S.chrPostalCode, S.chrCountry, U.chrFirst, U.chrLast, E.dtStamp 
 							  FROM NSOEvals AS E
 							  JOIN Users AS U ON E.idUser=U.ID
 							  JOIN NSOs ON E.idNSO = NSOs.ID
 							  JOIN RetailStores AS S ON S.ID=NSOs.idStore
 							  WHERE !E.bDeleted AND E.chrKEY='". $_REQUEST['key'] ."'","getting info",1); // Get Info
 			
 			if($info['ID'] == '') { errorPage('Invalid Evaluation'); }			
	
			# The template to use (should be the last thing before the break)
			$title = "View Evaluation";	# Page Title
			$header_title = "View Evaluation for ".$info['chrName']." / ".$info['chrStoreNum'];
			$directions = '';
								
			include($BF ."calendar/models/nso.php");		
			
			break;
		#################################################
		##	Else show Error Page
		#################################################
		default:
			errorPage('Page Incomplete.  Please notify an Administrator that you have received this error.');
	}
?>