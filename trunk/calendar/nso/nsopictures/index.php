<?
	include('_controller.php');
	
	function sitm() { 
		global $BF;
?>
									<form enctype="multipart/form-data" action="" method="post" id="idForm" onsubmit="return error_check()">
										<?=form_text(array('caption'=>'Picture Title','name'=>'chrFileTitle','size'=>'35','maxlength'=>'200'))?>
										<?=form_text(array('caption'=>'Choose a file','type'=>'file','required'=>'true','name'=>'chrAttachment'))?>

										<?=form_checkbox(array('name'=>'bPrimary','nodisplay'=>'true'))?> <label for='bPrimary'>Use this picture to represent the store.</label>

										<?	$results = db_query("SELECT ID,chrNSOPictureGroup as chrRecord FROM NSOPictureGroups WHERE !bDeleted ORDER BY chrNSOPictureGroup","getting NSOFileGroups"); ?>			
										<?=form_select($results,array('caption'=>'Picture Group','required'=>'true','name'=>'idNSOFileGroup'))?>

										<?=form_textarea(array('caption'=>'Picture Description','name'=>'txtFileDescription','cols'=>'100','rows'=>'30','extra'=>'wrap="virtual"'))?>

										<div class='FormButtons'>
											<?=form_button(array('type'=>'submit','value'=>'Add Another','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'index.php\';"'))?> &nbsp;&nbsp; <?=form_button(array('type'=>'submit','value'=>'Add and Continue','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'../view.php\';"'))?>
											<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'moveTo'))?>
											<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'key','value'=>$_REQUEST['key']))?>
										</div>
									</form>
<?
	}
?>