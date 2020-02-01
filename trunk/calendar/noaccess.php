<?php
	include('_controller.php');
	function sitm() { ?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="home_content">
			<tr>
				<td width="100%">
					<form id='idForm' name='idForm' method='post' action=''>
					<div style="text-align:center; font-size:14px;"><strong>No Access Allowed. You do not have access to this page.</strong></div>
					<div style="text-align:center; padding-top:20px;">
							<?=form_button(array('type'=>'button','name'=>'Back','value'=>'Back','extra'=>'onclick="javascript: history.go(-1);"'))?>&nbsp;&nbsp;&nbsp;
							<?=form_button(array('type'=>'button','name'=>'Home','value'=>'Home','extra'=>'onclick="javascript:location.href=\''.$BF.'index.php\'";'))?>
							</div>
					</form>
				</td>
			</tr>
		</table>
<?	} ?>