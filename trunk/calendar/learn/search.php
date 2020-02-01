<?php
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info;
		if(!isset($_POST['chrSearch'])) {
?>
	<form action="" method="post" id="idForm" onsubmit="return error_check()">
		<table class='OneColumn' cellspacing="0" cellpadding="0">
			<tr>
				<td style='padding: 5px;'>
					
					<?=form_text(array('caption'=>'Search For','required'=>'true','name'=>'chrSearch','size'=>'40','maxlength'=>'200'))?>

					<div class='FormButtons'>
						<?=form_button(array('type'=>'submit','name'=>'Search','value'=>'Search'))?>
					</div>
				</td>
			</tr>
		</table>
	</form>
<?
		} else {
		
			$q = "SELECT DISTINCT ID, chrKEY, chrTitle FROM NSOLearn WHERE !bDeleted AND idType=1 AND bShow AND bPShow AND (LCASE(txtContent)LIKE LCASE('%".encode($_POST['chrSearch'])."%') OR LCASE(chrTitle) LIKE LCASE('%".encode($_POST['chrSearch'])."%')) ORDER BY chrTitle";
	
			$results = db_query($q, "Getting any results");
		
			$tableHeaders = array(
				'chrTitle'				=> array('displayName' => 'Title'),
			);
			
			sortList('SearchResults',		# Table Name
				$tableHeaders,			# Table Name
				$results,				# Query results
				'index.php?key=',		# The linkto page when you click on the row
				'width: 100%; border-top: 0;', 			# Additional header CSS here
				''
			);
?>
		<div style='padding:20px; text-align:center;'><a href="search.php">Click here for New Search</a></div>
<?
		}
	}
?>