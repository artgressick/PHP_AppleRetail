<?
	include('_controller.php');
	
	function sitm() { 
		global $BF;
?>
		<form action="" method="post" id="idForm" onsubmit="return error_check()">
			<table width="100%" class="twoCol" id="twoCol" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tcleft">

						<div style='padding-bottom:5px;'><?=form_checkbox(array('type'=>'radio','caption'=>'Article Type','title'=>'Parent','required'=>'true','name'=>'bParent','value'=>'1','checked'=>(isset($_REQUEST['P']) && $_REQUEST['P']==1?'true':'false'),'extra'=>'onchange="javascript:typeselect();"'))?><?=form_checkbox(array('type'=>'radio','title'=>'Child','name'=>'bParent','value'=>'0','checked'=>(isset($_REQUEST['P']) && $_REQUEST['P']==0?'true':'false'),'extra'=>'onchange="javascript:typeselect();"'))?></div>

						<?=form_text(array('caption'=>'Title','required'=>'true','name'=>'chrTitle','maxlength'=>'200','size'=>'40'))?>
						
					</td>
					<td class="tcgutter"></td>
					<td class="tcright">

						<? $parents = db_query("SELECT ID, chrTitle AS chrRecord FROM NSOLearn WHERE !bDeleted AND bParent AND idType=2 ORDER BY dOrder,chrTitle","Getting List of Parents"); ?>
						<div id='ParentSelect' style='display:none;'><?=form_select($parents,array('caption'=>'Select Parent','required'=>'true','name'=>'idParent'))?></div>

						<div style='padding-bottom:5px;'><?=form_checkbox(array('type'=>'radio','caption'=>'Display this Article?','title'=>'Yes','required'=>'true','name'=>'bShow','value'=>'1'))?><?=form_checkbox(array('type'=>'radio','title'=>'No','name'=>'bShow','value'=>'0','checked'=>'true'))?></div>

					</td>
				</tr>
			</table>
			<?=form_textarea(array('caption'=>'Article','name'=>'txtContent','rows'=>'30','style'=>'width:100%;'))?>
			<div class='FormButtons' style='padding-top:20px;'>
				<?=form_button(array('type'=>'submit','value'=>'Add Another','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'add.php\';"'))?> &nbsp;&nbsp; <?=form_button(array('type'=>'submit','value'=>'Add and Continue','extra'=>'onclick="document.getElementById(\'moveTo\').value=\'index.php\';"'))?>
				<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'moveTo'))?>
			</div>
		</form>
<?
	}
?>