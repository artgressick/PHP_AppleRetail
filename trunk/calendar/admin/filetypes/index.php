<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;

		$tableHeaders = array(
			'chrNSOFileType'			=> array('displayName' => 'File Types','default' => 'asc'),
			'opt_del'		 			=> 'chrNSOFileType'
		);
		
		sortList('NSOFileTypes',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			'edit.php?key=',		# The linkto page when you click on the row
			'width: 100%', 			# Additional header CSS here
			''
		);
	}
?>