<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;

		$tableHeaders = array(
			'chrItem'					=> array('displayName' => 'Supply Item','default' => 'asc')
		);
		if(access_check(39,4)) {
			$tableHeaders['opt_del'] = 'chrItem';
		}
		
		sortList('SupplyItems',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			(access_check(39,3) ? 'edit.php?key=':''),		# The linkto page when you click on the row
			'width: 100%', 			# Additional header CSS here
			''
		);
	}
?>