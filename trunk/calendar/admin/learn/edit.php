<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
?>
		<form action="" method="post" id="idForm" onsubmit="return error_check()">
			<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tcleft">

						<?=form_text(array('caption'=>'Title','required'=>'true','name'=>'chrTitle','maxlength'=>'200','size'=>'40','value'=>$info['chrTitle']))?>
						
					</td>
					<td class="tcgutter"></td>
					<td class="tcright">
<?
					if(!$info['bParent']) {
?>
						<? $parents = db_query("SELECT ID, chrTitle AS chrRecord FROM NSOLearn WHERE !bDeleted AND bParent AND idType=1 ORDER BY dOrder,chrTitle","Getting List of Parents"); ?>
						<?=form_select($parents,array('caption'=>'Select Parent','required'=>'true','name'=>'idParent','value'=>$info['idParent']))?>
<?
					}
?>

						<div style='padding-bottom:5px;'><?=form_checkbox(array('type'=>'radio','caption'=>'Display this Article?','title'=>'Yes','required'=>'true','name'=>'bShow','value'=>'1','checked'=>($info['bParent'] && $info['bPShow'] ? 'true' : (!$info['bParent'] && $info['bShow'] ? 'true' : 'false'))))?><?=form_checkbox(array('type'=>'radio','title'=>'No','name'=>'bShow','value'=>'0','checked'=>'true','checked'=>($info['bParent'] && !$info['bPShow'] ? 'true' : (!$info['bParent'] && !$info['bShow'] ? 'true' : 'false'))))?></div>

					</td>												
												
				</tr>
			</table>
			<?=form_textarea(array('caption'=>'Article','name'=>'txtContent','rows'=>'30','style'=>'width:100%;','value'=>decode($info['txtContent'])))?>
			<div class='FormButtons'>
				<?=form_button(array('type'=>'submit','value'=>'Update Information'))?>
				<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'key','value'=>$_REQUEST['key']))?>
			</div>
		</form>
<?
	}
?>