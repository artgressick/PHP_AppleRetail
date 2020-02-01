<?
	global $auth_not_required;
	global $ProjectName;


		if (isset($_REQUEST['auth_form_name'])) {  // check to see if this is a submission of the login form
			$auth_form_name = strtolower($_REQUEST['auth_form_name']);

				$query = "
				SELECT *
				FROM Users 
				WHERE !bDeleted AND chrEmail='" . $auth_form_name . "' AND
				chrPassword=SHA1('" . $_REQUEST['auth_form_password'] . "')";

			$result = database_query($query, "auth_check: verifying Email and Password against db.");
			
			if ($result) {
				if (mysqli_num_rows($result)) {
					$row = mysqli_fetch_assoc($result);
					$_SESSION['chrEmail'] = $row["chrEmail"];
					$_SESSION['idUser'] = $row["ID"];
					$_SESSION['chrFirst'] = $row["chrFirst"];
					$_SESSION['chrLast'] = $row["chrLast"];
					$_SESSION['auto_logon'] = false;
					header('Location: ' . $_SERVER['REQUEST_URI']);
	
				} else {
					$auth_error = "Authentication failed<!--(1)-->.";
				}
			} else {
				echo(mysql_error());
				$auth_error = "Authentication failed<!--(2)-->.";
			}
		
		}

		if (isset($_SESSION['idUser'])) {  // if this variable is set, they are now authenticated
			header("Location: " . $_SERVER['REQUEST_URI']);
			die();
		}
	
	if (!isset($auth_not_required)) $auth_not_required = false;

	if (!$auth && $auth_not_required != true) {  // if not authenticated, present the form
		include($BF . "includes/login.php");
		die();
	}
?>
