<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;
?>
	<form action="" method="post" id="idForm" onsubmit="return error_check()">
<?
		$tableHeaders = array(
			'chrCat'					=> array('displayName' => 'Category Name','default' => 'asc'),
			'opt_other'					=> 'order'
		);
		if(access_check(29,4)) {
			$tableHeaders['opt_del'] = 'chrCat';
		}
		
		sortList('EvalCats',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			(access_check(29,3) ? 'edit.php?key=':''),		# The linkto page when you click on the row
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