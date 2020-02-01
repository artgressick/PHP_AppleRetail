<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;
?>
	<form action="" method="post" id="idForm" onsubmit="return error_check()">
<?
		$tableHeaders = array(
			'chrPunchCat'					=> array('displayName' => 'Category Name','default' => 'asc'),
			'opt_other'					=> 'order',
			'opt_del'		 			=> 'chrPunchCat'
		);
		if(access_check(36,4)) {
			$tableHeaders['opt_del'] = 'chrPunchCat';
		}
		
		sortList('PunchCats',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			(access_check(36,3) ? 'edit.php?key=':''),		# The linkto page when you click on the row
			'width: 100%', 			# Additional header CSS here
			''
		);
?>
		<div class='FormButtons'>
			<?=form_button(array('type'=>'submit','value'=>'Save Order'))?>
		</div>
	</form>
<?
	}
?>