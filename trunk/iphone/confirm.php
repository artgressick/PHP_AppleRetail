<?
	$BF = "../";
	$auth_not_required = true;
	require($BF. '_lib.php');
	
	if(count($_POST)) {
		header("Location: thankyou.php?id=" . $_POST['id']);
		die();
	}

	if(isset($_REQUEST['id'])) { 
		if(!is_numeric($_REQUEST['id']) || $_REQUEST['id'] == "") {
			header("Location: ". $BF ."iphone/"); 
			die();
		}
	} else {
		header("Location: ". $BF ."iphone/"); 
		die();
	}
	
	$user = database_query("SELECT iPhoneRequest.*, RetailStores.chrName as chrStore
		FROM iPhoneRequest 
		JOIN RetailStores ON RetailStores.chrStoreNum=iPhoneRequest.chrDivision 
		WHERE iPhoneRequest.ID=". $_REQUEST['id'],"get user info",1);

	$title = "iPhone Registration Confirmation";
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
								
						<div class='header1'>Registration Confirmation</div>
						<div class='instructions' style='margin-top: 10px;'>Please carefully verify your registration information shown below. If anything is incorrect, press the cancel button to go back and correct any errors.</div>
						
						<table cellspacing="0" cellpadding="0" style='width: 100%; margin-top: 10px;'>
							<tr>
								<td colspan='2'><div class='header3'>Personal Information</div></td>
							</tr>
							<tr>
								<td><strong>Name</strong></td>
								<td><?=$user['chrFirst']?> <?=$user['chrLast']?></td>			
							</tr>
							<tr>
								<td><strong>Email Addrss</strong></td>
								<td><?=$user['chrEmail']?></td>
							</tr>
							<tr>
								<td><strong>Phone Serial Number</strong></td>
								<td><?=$user['chrSerial']?></td>	
							</tr>
							<tr>
								<td><strong>Employee ID</strong></td>
								<td><?=$user['chrEmpID']?></td>	
							</tr>
							<tr>
								<td><strong>Your Store</strong></td>
								<td><?=$user['chrStore']?></td>	
							</tr>
						</table>
													
						</td>
						<td style='vertical-align: top; width: 1%;'><img src="images/iphones.jpg" alt="iphones"></td>
					</tr>
				</table>

				<div style='margin-top: 20px;'>
				<input name='id' type='hidden' value='<?=$_REQUEST['id']?>'  /> 
					<div class='instructions' style='font-size: 12px; margin-bottom: 10px; font-weight: bold;'>
						Please check your information carefully. Once you hit the "Submit Registration" button your registration will be completed and you will not be able to change it.
					</div>
					<input name='Cancel' type='button' value='Cancel' onClick="location.href='index.php?id=<?=$_REQUEST['id']?>'" />
					<input name='Confirmed' type='Submit' value='Submit Registration' />
				</div>


			</td>
		</tr>
	</table>
</form>

<?
	include($BF. "includes/bottom.php");
?>




