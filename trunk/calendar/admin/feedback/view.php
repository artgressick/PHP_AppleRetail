<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
	
							<div class='header4' style='margin: 10px 0px;'>Feedback from: <?=$info['chrFirst']?> <?=$info['chrLast']?></div>
				
							<div>Information sent: <?=$info['dtCreated']?></div>
							<div><?=$info['txtFeedback']?></div>
							
							
							<?=form_button(array('type'=>'button','style'=>'margin-top: 10px;','value'=>'Go Back','extra'=>'onclick="location.href=\'index.php\'"'))?>
<?
	}
?>