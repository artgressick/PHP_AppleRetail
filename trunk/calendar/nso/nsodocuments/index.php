<?
	include('_controller.php');
	
	function sitm() { 
		global $BF;
?>
									<form enctype="multipart/form-data" action="" method="post" id="idForm" onsubmit="return error_check()">
										<?=form_text(array('caption'=>'Document Title','required'=>'true','name'=>'chrFileTitle','size'=>'35','maxlength'=>'200'))?>

										<?	$results = db_query("SELECT ID,chrNSOFileGroup as chrRecord FROM NSOFileGroups WHERE !bDeleted ORDER BY chrNSOFileGroup","getting NSOFileGroups"); ?>
										<?=form_select($results,array('caption'=>'File Group','required'=>'true','name'=>'idNSOFileGroup'))?>

										<?=form_text(array('caption'=>'Choose a file','type'=>'file','required'=>'true','name'=>'chrAttachment'))?>
										<?=form_textarea(array('caption'=>'Document Description','name'=>'txtFileDescription','cols'=>'100','rows'=>'30','extra'=>'wrap="virtual"'))?>

										<div class='FormButtons'>
											<?=form_button(array('type'=>'submit','value'=>'Add Another','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'index.php\';"'))?> &nbsp;&nbsp; <?=form_button(array('type'=>'submit','value'=>'Add and Continue','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'../view.php\';"'))?>
											<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'moveTo'))?>
											<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'key','value'=>$_REQUEST['key']))?>
										</div>
									</form>
<?
	}
?>