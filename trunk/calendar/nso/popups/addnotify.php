<?
	include('_controller.php');

	function sitm() { 
		global $BF;
?>
	<form method='post' action='' onsubmit='return error_check()'>
		<table cellpadding='0' cellspacing='0' style='width:100%;'>
			<tr>
				<td style='style:50%;'>
					<?=form_text(array('caption'=>'First Name','required'=>'true','name'=>'chrFirst'));?>
					<?=form_text(array('caption'=>'Last Name','required'=>'true','name'=>'chrLast'));?>
				</td>
				<td style='style:50%;'>
					<?=form_text(array('caption'=>'Email Address','required'=>'true','name'=>'chrEmail'));?>
					<?=form_text(array('caption'=>'Company','required'=>'true','name'=>'chrCompany'));?>
				</td>
			</tr>
		</table>
		<?=form_button(array('type'=>'submit','value'=>'Add Person'))?>
		<?=form_button(array('type'=>'hidden','name'=>'key','value'=>$_REQUEST['key']))?>
	</form>
	<div align="center" style='margin: 10px auto;'><input type='button' onclick='javascript:window.close();' value='Close Window' /></div>
<?
	}
?>