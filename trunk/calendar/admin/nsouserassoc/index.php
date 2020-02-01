<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results;

		$tableHeaders = array(
			'chrField'			=> array('displayName' => 'Association With','default' => 'asc'),
		);
	
		sortList('NSOPeopleAssoc',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			(access_check(30,3) ? 'edit.php?key=':''),		# The linkto page when you click on the row
			'width: 100%; border-top: 0;', 			# Additional header CSS here
			''
		);
	}