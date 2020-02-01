<?
	include('_controller.php');
	
	
	function sitm() { 
		global $BF,$info;

?>
						<form action="" method="post" id="idForm" onsubmit="return error_check()">

							<?=form_text(array('caption'=>'NSO Event Name','display'=>'true','value'=>$info['chrNSOCorpTask']));?>

							<?	$results = array('0'=>'New','100'=>'Complete'); ?>
							<?=form_select($results,array('caption'=>'Task Status','required'=>'true', 'name'=>'intNSOTaskStatus','value'=>$info['intNSOTaskStatus']));?>

							<?=form_text(array('caption'=>'Date Offset','name'=>'intDateOffset','required'=>'true','value'=>$info['intDateOffset']));?>
							
							<?=form_textarea(array('caption'=>'Notes','required'=>'true','name'=>'txtNote','rows'=>'10','cols'=>'100','value'=>$info['txtNote']))?>

							<div class='FormButtons'>
								<?=form_button(array('type'=>'submit','value'=>'Update Information'))?>
								<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'key','value'=>$_REQUEST['key']))?>
								<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'oldkey','value'=>$_REQUEST['oldkey']))?>
							</div>

						</form>
<?
	}
?>