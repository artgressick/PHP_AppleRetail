<?
	if (isset($_POST['auth_form_name'])) {  // check to see if this is a submission of the login form
		$auth_form_name = strtolower($_REQUEST['auth_form_name']);

		$q = "SELECT *
			FROM Users
			WHERE !bDeleted AND chrEmail='" . $auth_form_name . "'
		";
		$result = db_query($q, "auth_check: verifying Email.");
		
		if (mysqli_num_rows($result)) {
			$pass = sha1($_POST['auth_form_password']);
			$row = mysqli_fetch_assoc($result);
			
			if($pass == $row['chrPassword']) {
				
				# Set the session variables that will be used in the rest of the site
				$_SESSION['chrEmail'] = $row["chrEmail"];
				$_SESSION['idUser'] = $row["ID"];
				$_SESSION['chrFirst'] = $row["chrFirst"];
				$_SESSION['chrLast'] = $row["chrLast"];
				$_SESSION['auto_logon'] = false;
				$_SESSION['dtLogin'] = date('m/d/Y H:m:s');
				
				$q = "SELECT CA.idSecurity, CS.bGlobal, CA.txtStoreAccess, CA.bShowOrangeEvents
						FROM Users AS U
						JOIN CalendarAccess AS CA ON CA.idUser=U.ID AND !CA.bDeleted
						JOIN CalSecurity AS CS ON CA.idSecurity=CS.ID
						WHERE !CA.bDeleted AND !CS.bDeleted AND !U.bDeleted && U.ID='".$_SESSION['idUser']."'";
				$tmp = db_query($q,'Checking Access',1);				
				$_SESSION['bShowOrangeEvents'] = $tmp["bShowOrangeEvents"];
				if(isset($tmp['bGlobal']) && $tmp['bGlobal'] != 1) { // Not Global
					$_SESSION['bGlobal'] = 0;
					$_SESSION['txtStoreAccess'] = $tmp['txtStoreAccess'];
					$security = db_query("SELECT F.ID, chrLevels 
											FROM CalSecFiles AS F
											LEFT JOIN CalSecuritySelections AS S ON F.ID=S.idCalSecFile AND S.idCalSecurity=".$tmp['idSecurity']." 
											ORDER BY F.ID","Get Security Options");
					unset($_SESSION['Security']);
					while($row = mysqli_fetch_assoc($security)) {
						$_SESSION['Security'][$row['ID']] = $row['chrLevels'];
					}
				} else if(isset($tmp['bGlobal']) && $tmp['bGlobal'] == 1) { // Is Global
					$_SESSION['bGlobal'] = 1;
				} else { // No Record
					errorPage('You do not have authorization to access this page.');
				}
				
				$_SESSION['dtLastSecurityCheck'] = date('m/d/Y H:i:s');
				if($row['dtCalLogin'] == '' || $row['dtCalLogin'] == '0000-00-00 00:00:00.0') {
					db_query("UPDATE Users SET dtCalLogin=NOW() WHERE ID=".$_SESSION['idUser'],"Update Login");
					header('Location: '.$BF.'calendar/firstlogin.php');
					die();
				}
				# This sends the user to whatever page they were originally trying to get to before being stopped to login
				header('Location: ' . $PROJECT_ADDRESS.$_SERVER['REQUEST_URI']);
				die();
			} else {
				# If the aacount failed to log in, but is under 5 attempts, show them the generic message and log the attempt
				$_SESSION['errorMessages'][] = "Authentication failed<!--(1)-->.";
			}
		} else {
			# Nothing came back for this email address in the DB.  Generic message ensues.
			$_SESSION['errorMessages'][] = "Authentication failed<!--(2)-->.";
		}
	
	}

	# if they need to be log in for the current page and currently are not yet logged in, send them to the login page.
	include_once($BF.'calendar/components/formfields.php');
	include($BF . "calendar/login.php");
	include($BF ."calendar/models/template.php");		
	die();
?>
