<?
	$title = "Login Page";
	include($BF. "includes/meta.php");
	include($BF. "includes/top.php");
?>
<form id="form1" name="form1" method="post" action="">
<table width="300" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td>
      <div style='padding: 10px;'>
<? if(isset($error_messages)) {
		foreach($error_messages as $er) { ?>
			<div class='ErrorMessage'><?=$er?></div>
<?		}
	}
?>
        <p><span class="FormName">Apple Email Address</span> <span class="FormRequired">(Required)</span> <br />
            <input name="auth_form_name" type="text" size="30" maxlength="35" value='<?=(isset($_REQUEST['auth_form_name']) ? $_REQUEST['auth_form_name'] : '')?>' />
		</p>
        <p><span class="FormName">Password</span> <span class="FormRequired">(Required)</span> <br />
            <input name="auth_form_password" type="password" size="30" maxlength="30" />
		</p>
        <p>
			<input type="submit" name="Submit" value="Submit" />
		</p>
        <p class="FormRequired">Problems? Contact The Administrator</p>
    	 </td>
	</tr>
</table>
<?
	include($BF. "includes/bottom.php");
?>