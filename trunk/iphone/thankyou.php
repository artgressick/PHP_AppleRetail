<?
	$BF = "../";
	$auth_not_required = true;
	require($BF. 'iphone/_lib.php');

	if(isset($_REQUEST['id'])) { 
		if(!is_numeric($_REQUEST['id']) || $_REQUEST['id'] == "") {
			header("Location: ". $BF ."iphone/"); 
			die();
		}
	} else {
		header("Location: ". $BF ."iphone/"); 
		die();
	}
	
	database_query("UPDATE iPhoneRequest SET bComplete=1 WHERE ID=". $_REQUEST['id'],"Complete this user");

	$user = database_query("SELECT * FROM iPhoneRequest WHERE ID='" . $_REQUEST['id'] . "'","getting user info",1);

	$to      = $user['chrEmail'];
	$subject = 'Apple Retail iPhone Registration Complete.';

	$msg = $user['chrFirst'] . " " . $user['chrLast'] . ",

Congratulations! Your iPhone registration has been successfully submitted.

Thank you.
";

	$headers = 'From: storeops@apple.com' . "\r\n";
	mail($to, $subject, $msg, $headers);

	$title = "iPhone Registration Completion";
	include($BF. "includes/meta.php");
	include($BF. "includes/top.php");

?>
		<table width="924" border="0" cellpadding="0" cellspacing="0" class="home_content">
		<tr>
			<td width="100%">
		
				<table cellspacing="0" cellpadding="0" style='width: 924px; '>
					<tr>
						<td style='vertical-align: top; width: 50%;'>

							<div class='header1'>Thank You!</div>
							<div class='header3'>A confirmation email has been sent to your apple.com email.</div>
									
						</td>
						<td style='vertical-align: top; width: 1%;'><img src="images/iphones.jpg" alt="iphones"></td>
					</tr>
				</table>

			</td>
		</tr>
	</table>

<?
	include($BF. "includes/bottom.php");
?>

