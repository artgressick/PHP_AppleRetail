<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;

		$tableHeaders = array(
			'dtCreated'				=> array('displayName' => 'Date Updated','default' => 'asc'),
			'chrName'				=> array('displayName' => 'Updated By')
		);
		if(access_check(28,4)) {
			$tableHeaders['opt_del'] = 'dtCreated';
		}
		
		sortList('SSs',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			(access_check(28,3) ? 'edit.php?key=':''),		# The linkto page when you click on the row
			'width: 100%', 			# Additional header CSS here
			''
		);
	}
?>