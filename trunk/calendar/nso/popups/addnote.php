<?	include('_controller.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title><?=$title?></title>

	<?	if(isset($_POST['txtNote'])) { include($post_file); } ?>
	<script type='text/javascript'>var BF = '<?=$BF?>';</script>

	
	
</head>
<body>
	<form method='post' action='' onsubmit=''>

	<div style='border: 1px solid gray; padding: 10px;'>

		<?=form_textarea(array('caption'=>'Note','required'=>'true','name'=>'txtNote','style'=>'width:100%;','rows'=>'15'));?>

		<p>
		<?=form_button(array('type'=>'submit','value'=>'Add Note'))?>
		<?=form_button(array('type'=>'hidden','name'=>'id','value'=>$_REQUEST['id']))?>
		</p>
		
	</div>
	</form>
<div align="center" style='margin: 10px auto;'><a href="javascript:window.close();">Close Window</a></div>
</body>
</html>
