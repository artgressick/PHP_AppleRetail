<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;
	
		$tableHeaders = array(
			'chrNSOPictureType'			=> array('displayName' => 'Picture Types','default' => 'asc'),
			'opt_del'		 			=> 'chrNSOPictureType'
		);
		
		sortList('NSOPictureTypes',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			'edit.php?key=',		# The linkto page when you click on the row
			'width: 100%; border-top: 0;', 			# Additional header CSS here
			''
		);
	}