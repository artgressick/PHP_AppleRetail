<?
	$BF = "../";
	$auth_not_required = true;
	require($BF. 'iphone/_lib.php');

	$errmsg = "";
	if(isset($_POST['auth_form_name'])) {
		if($_POST['auth_form_name'] == "iphone" && $_POST['auth_form_password'] = "0hSoPr3tty!") {
			
			$_SESSION['idUser'] = 1;
			header("Location: admin/");
			die();
			
		} else {
			$errmsg = "Incorrect Login and Password combination";
		}
	}

	$title = "Login Page";
	include($BF. "includes/meta.php");
	include($BF. "includes/top.php");
?>
	
			<form method="post" action="">
			<table cellpadding="0" cellspacing="0" border="0" style='margin: 0 auto; width: 400px'>
				<tr>
					<td colspan='2'>
						<div class='header2' style='margin: 10px 0;'>
							Admin Login
						</div>
					</td>
				</tr>
<? if($errmsg != "") { ?>
				<tr>
					<td colspan='2'>
					<div class='Messages'>
						<div class='ErrorMessage'><?=$errmsg?></div>
					</div>
					</td>
				</tr>
<?  } ?>
				<tr>
					<td>Login:</td>
					<td><input size="17" type="text" name="auth_form_name"></td>
				</tr>
				<tr>		
					<td>Password:</td>
					<td><input size="17" type="password" name="auth_form_password"></td>
				</tr>
				<tr>		
					<td colspan='2'>
					<input type='hidden' name='bAdmin' value='1' />
						
						<div class='ClickButtons' style='margin-top: 10px;'>
							<input class='LoginButton' type='submit' value='Log In'>
						</div> 
					</form>
					</td>
				</tr>
			</table>	
			
<?
	include($BF. "includes/bottom.php");
?>