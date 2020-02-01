<?
	$BF = "../";
	$auth_not_required = true;
	require($BF. 'iphone/_lib.php');

	if(!isset($_REQUEST['id'])) { $_REQUEST['id'] = ""; }

	$error_messages = array();
	if(count($_POST)) {
	
		if($_POST['chrFirst'] == '') { $error_messages[] = "You must enter your First Name."; }
		if($_POST['chrLast'] == '') { $error_messages[] = "You must enter your Last Name."; }
		if($_POST['chrEmail'] == '') { 
			$error_messages[] = "You must enter your Email Address Name."; 
		} else {
			# To check for other apple emails... use the script below which checks for apple.com, euro.apple.com and asia.apple.com
			# /^([a-zA-Z0-9_\.\-])+\@(apple.com|euro.apple.com|asia.apple.com)$/   
			if(!preg_match("/^([a-zA-Z0-9_\.\-])+\@apple.com$/",$_POST['chrEmail'],$matches)) {
				$error_messages[] = "You must enter a Valid Apple Email Address."; 
			}
		}
		if($_POST['chrSerial'] == '') { $error_messages[] = "You must enter your iPhone Serial Number."; }
		if($_POST['chrEmpID'] == '') { 
			$error_messages[] = "You must enter your Employee ID."; 
		} else {
			$chkEmpID = database_query("SELECT ID FROM iPhoneRequest WHERE bComplete AND chrEmpID='" . $_POST['chrEmpID'] . "'","emp id check");
			if(mysqli_num_rows($chkEmpID) && $_REQUEST['id'] == "") {
				$error_messages[] = "This employee ID has already registered."; 
			}
		}		
		if($_POST['chrDivision'] == '') { $error_messages[] = "You must choose your Store Name."; }
		
		if(count($error_messages) == 0) {
				
			if($_POST['id'] == "") {
				$q = "INSERT INTO iPhoneRequest SET
					chrFirst = '" . encode($_POST['chrFirst']) . "',
					chrLast = '" . encode($_POST['chrLast']) . "',
					chrEmail = '" . $_POST['chrEmail'] . "',
					chrSerial = '" . encode($_POST['chrSerial']) . "',
					chrEmpID = '" . $_POST['chrEmpID'] . "',
					chrDivision = '" . $_POST['chrDivision'] . "',
					dtCreated = NOW()
					";
				
				if(database_query($q, 'insert user info')) {
					global $mysqli_connection;
					$new_id = mysqli_insert_id($mysqli_connection);
					header("Location: confirm.php?id=" . $new_id);
					die();
				}
			} else { 

				$q = "UPDATE iPhoneRequest SET
					chrFirst = '" . encode($_POST['chrFirst']) . "',
					chrLast = '" . encode($_POST['chrLast']) . "',
					chrEmail = '" . $_POST['chrEmail'] . "',
					chrSerial = '" . encode($_POST['chrSerial']) . "',
					chrEmpID = '" . $_POST['chrEmpID'] . "',
					chrDivision = '" . $_POST['chrDivision'] . "'
					WHERE ID='" . $_POST['id'] . "'";
				
				if(database_query($q, 'update user info')) {
					header("Location: confirm.php?id=" . $_POST['id']);
					die();
				}
			}
		}
		$info = $_POST;		
	} else {
		if($_REQUEST['id'] != "") {
			$info = database_query("SELECT * FROM iPhoneRequest WHERE ID=". $_REQUEST['id'],"getting user data",1);
		} else {
			$info = 0;
		}
	}

	$stores = database_query("SELECT chrStoreNum,chrName FROM RetailStores WHERE !bDeleted AND chrCountry='US' ORDER BY chrName","getting stores");

	$title = "iPhone Registration";
	include($BF. "includes/meta.php");
	include($BF. "includes/top.php");
?>

<form action='' method="post">

	<table width="924" border="0" cellpadding="0" cellspacing="0" class="home_content">
		<tr>
			<td width="100%">
		
				<table cellspacing="0" cellpadding="0" style='width: 924px; '>
					<tr>
						<td style='vertical-align: top; width: 50%;'>

							<div class='header1'>Register your iPhone.</div>
							<div class='header' style='font-size: 12px; margin-top: 10px;'>Before receiving the iPhone all employees must register in the presence of store management.</div>
							<div class='header' style='font-size: 12px;'></div>
							
							<div class="Messages" style='margin-top: 10px;'>
<? 	foreach($error_messages as $error) { ?>
										<div class='ErrorMessage'><?=$error?></div>
<?	} ?>
							</div>
				
				<table cellspacing="0" cellpadding="0" style='width: 100%;'>
					<tr>
						<td>
							<div class='InputField'>
								<div class='InputLabel'>First Name <span class='FormRequired'>(Required)</span></div>
								<input name="chrFirst" type="text" size="25" maxlength="100" value='<?=$info['chrFirst']?>' />
							</div>
							
							<div class='InputField'>
								<div class='InputLabel'>Last Name <span class='FormRequired'>(Required)</span></div>
								<input name="chrLast" type="text" size="25" maxlength="100" value='<?=$info['chrLast']?>' />
							</div>

							<div class='InputField'>
								<div class='InputLabel'>Email Address <span class='FormRequired'>(Required)</span></div>
								<input name="chrEmail" type="text" size="25" maxlength="100" value='<?=$info['chrEmail']?>' />
							</div>
		
						</td>
						<td style='vertical-align: top; width: 50%;'>
		
							<div class='InputField'>
								<div class='InputLabel'>Phone Serial Number <span class='FormRequired'>(Required)</span></div>
								<input name="chrSerial" type="text" size="25" maxlength="30" value='<?=$info['chrSerial']?>' />
							</div>
		
							<div class='InputField'>
								<div class='InputLabel'>Employee ID <span class='FormRequired'>(Required)</span></div>
								<input name="chrEmpID" type="text" size="25" maxlength="100" value='<?=$info['chrEmpID']?>' />
							</div>

							<div class='InputField'>
								<div class='InputLabel'>Your Store <span class='FormRequired'>(Required)</span></div>
								<select name='chrDivision'>
									<option value=''>-Select Store-</option>
<?	while($row = mysqli_fetch_assoc($stores)) { ?>
									<option<?=($info['chrDivision'] == $row['chrStoreNum'] ? ' selected="selected"' : '')?> value='<?=$row['chrStoreNum']?>'><?=$row['chrName']?></option>
<?	} ?>
								</select>
							</div>
						</td>
					</tr>
				</table>
									
						</td>
						<td style='vertical-align: top; width: 1%;'><img src="images/iphones.jpg" alt="iphones"></td>
					</tr>
				</table>
				
				<div class='instructions'>NOTE: This product is not eligible for return or exchange.</div>
				<div style='margin-top: 20px;'>
					<input name='Submit' type='Submit' value='Submit Information' />
					<input name='id' type='hidden' value='<?=$_REQUEST['id']?>'  /> 
				</div>
			</td>
		</tr>
	</table>
</form>

<?
	include($BF. "includes/bottom.php");
?>

