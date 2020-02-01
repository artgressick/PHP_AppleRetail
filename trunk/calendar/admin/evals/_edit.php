<?
	include_once($BF.'calendar/components/edit_functions.php');

	db_query("INSERT INTO Evals SET chrKEY='". makekey() ."',dtCreated=now(),idUser=". $_SESSION['idUser'],"Insert New Eval");

	global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
	$newID = mysqli_insert_id($mysqli_connection);
	
	$q = "";
	$i = 0;
	while($i++ <= $_POST['intCount']) {
		# First, make sure that the question is set AND that it wasn't set to be removed.
		if(isset($_POST['chrQuestion'.$i]) && $_POST['chrQuestion'.$i] != '' && $_POST['bDeleted'.$i] != 1) {
			
			# If they chose a text (1) or textarea (2) field, continue, else run a few more checks.
			if($_POST['idEvalType'.$i] == 1 || $_POST['idEvalType'.$i] == 2) {
				$q .= "('". makekey() ."','". $newID ."','". (isset($_POST['bRequired'.$i]) && $_POST['bRequired'.$i] == "on" ? 1 : 0) ."','". $_POST['idEvalType'.$i] ."','". $_POST['dOrder'.$i] ."','". encode($_POST['chrQuestion'.$i]) ."','',".$_POST['idEvalCat'.$i]."),";	
			} else {
				$optionVals = "";
				$j = 0;
				# Create a ||| seperated list of options.
				while($j++ <= $_POST['optionval'.$i]) {
					$optionVals .= ($_POST['optionval'.$i.'-'.$j] != "" ? encode($_POST['optionval'.$i.'-'.$j]).'|||' : '');
				}
				# Check to make sure at least ONE option was in fact added
				if($optionVals != "") {
					$q .= "('". makekey() ."','". $newID ."','". (isset($_POST['bRequired'.$i]) && $_POST['bRequired'.$i] == "on" ? 1 : 0) ."','". $_POST['idEvalType'.$i] ."','". $_POST['dOrder'.$i] ."','". encode($_POST['chrQuestion'.$i]) ."','". substr($optionVals,0,-3) ."',".$_POST['idEvalCat'.$i]."),";	
				}
			}
		}
	}
	
	if($q != "") {
		db_query("INSERT INTO EvalQuestions (chrKEY,idEval,bRequired,idEvalType,dOrder,chrQuestion,txtOptions,idEvalCat) VALUES ".substr($q,0,-1),"Adding the questions");
	}
	
	header("Location: index.php");
	die();	
?>