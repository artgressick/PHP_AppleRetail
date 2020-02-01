<?php
	include('_controller.php');
	
	function sitm() {
		global $BF,$results;
		
		$tableHeaders = array(
			'chrName'		=> array('displayName' => 'Store Name'),
			'chrComplete'	=> array('displayName' => 'Hours Filled Out','default' => 'asc')
		);
		
		sortList('StoreHours',				# Table Name
			$tableHeaders,					# Table Name
			$results,						# Query results
			'view.php?id=',				# The linkto page when you click on the row
			'width: 100%;', 	# Additional header CSS here
			''
		);

	}
?>