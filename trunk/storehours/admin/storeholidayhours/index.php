<?php
	include('_controller.php');
	
	function sitm() {
		global $BF,$results;
?>
	<form id="form1" name="form1" method="get" action="" style='margin: 0; padding: 0;'>
	<table class='greenFilter' width='100%' cellpadding='0' cellspacing='0' border='0'>
		<tr>
			<td width='50%'>
			Select Store: 
<?
			$stores = db_query("SELECT ID, chrName AS chrRecord FROM RetailStores WHERE !bDeleted ORDER BY chrName","Getting Stores");
?>
			<?=form_select($stores,array('caption'=>'- Select Store -','nocaption'=>'true','name'=>'idStore','value'=>$_REQUEST['idStore'],'extra'=>'onchange="document.getElementById(\'form1\').submit();"'))?>
			and/or Holiday: 
<?
			$holidays = db_query("SELECT ID, chrHoliday AS chrRecord FROM Holidays WHERE !bDeleted ORDER BY chrHoliday","Getting Stores");
?>
			<?=form_select($holidays,array('caption'=>'- Select Holiday -','nocaption'=>'true','name'=>'idHoliday','value'=>$_REQUEST['idHoliday'],'extra'=>'onchange="document.getElementById(\'form1\').submit();"'))?>
			<?=form_button(array('type'=>'submit','name'=>'filter','value'=>'Filter'))?>
			</td>
		</tr>
	</table>
	</form>
<?
		
		
		
		$tableHeaders = array(
			'chrStore'		=> array('displayName' => 'Store Name'),
			'chrHoliday'	=> array('displayName' => 'Holiday Name','default' => 'asc'),
		);
		
		sortList('StoreHoursSpecial',				# Table Name
			$tableHeaders,					# Table Name
			$results,						# Query results
			'view.php?key=',				# The linkto page when you click on the row
			'width: 100%; border-top: 0;', 	# Additional header CSS here
			''
		);

	}
?>