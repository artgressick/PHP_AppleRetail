<?
	include_once($BF.'calendar/components/edit_functions.php');

	db_query("INSERT INTO SSs SET chrKEY='". makekey() ."',dtCreated=now(),idUser=". $_SESSION['idUser'],"Insert New SS");

	global $mysqli_connection;  // This is needed for mysqli to be able to get the "last insert id"
	$newID = mysqli_insert_id($mysqli_connection);
	
	$q = "";
	$i = 0;
	while($i++ <= $_POST['intCount']) {
		# First, make sure that the question is set AND that it wasn't set to be removed.
		if(isset($_POST['chrQuestion'.$i]) && $_POST['chrQuestion'.$i] != '' && $_POST['bDeleted'.$i] != 1) {
			
			# If they chose a text (1) or textarea (2) field, continue, else run a few more checks.
			if($_POST['idSSType'.$i] == 1 || $_POST['idSSType'.$i] == 2) {
				$q .= "('". makekey() ."','". $newID ."','". (isset($_POST['bRequired'.$i]) && $_POST['bRequired'.$i] == "on" ? 1 : 0) ."','". $_POST['idSSType'.$i] ."','". $_POST['dOrder'.$i] ."','". encode($_POST['chrQuestion'.$i]) ."','',".$_POST['idSSCat'.$i]."),";	
			} else {
				$optionVals = "";
				$j = 0;
				# Create a ||| seperated list of options.
				while($j++ <= $_POST['optionval'.$i]) {
					$optionVals .= ($_POST['optionval'.$i.'-'.$j] != "" ? encode($_POST['optionval'.$i.'-'.$j]).'|||' : '');
				}
				# Check to make sure at least ONE option was in fact added
				if($optionVals != "") {
					$q .= "('". makekey() ."','". $newID ."','". (isset($_POST['bRequired'.$i]) && $_POST['bRequired'.$i] == "on" ? 1 : 0) ."','". $_POST['idSSType'.$i] ."','". $_POST['dOrder'.$i] ."','". encode($_POST['chrQuestion'.$i]) ."','". substr($optionVals,0,-3) ."',".$_POST['idSSCat'.$i]."),";	
				}
			}
		}
	}
	
	if($q != "") {
		db_query("INSERT INTO SSQuestions (chrKEY,idSS,bRequired,idSSType,dOrder,chrQuestion,txtOptions,idSSCat) VALUES ".substr($q,0,-1),"Adding the questions");
	}
	
	header("Location: index.php");
	die();	
?>