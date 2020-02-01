<?
	include('_controller.php');
	
	function sitm() { 
		global $BF,$info,$results,$directions;
	
		if(!is_numeric($info['ID']) AND $_SESSION['bGlobal']) {
			$topborder = false;
			$_REQUEST['idNSOType'] = (!isset($_REQUEST['idNSOType']) ? '' : $_REQUEST['idNSOType']); 
			$filter_results = db_query("SELECT Stores.ID,CONCAT(Stores.chrName,' / ',Stores.chrStoreNum) AS chrRecord 
										FROM RetailStores AS Stores
										JOIN NSOs ON NSOs.idStore=Stores.ID
										JOIN NSOSS AS E ON E.idNSO=NSOs.ID
										WHERE ".($_SESSION['bGlobal'] ? "":"NSOs.bShow AND ")."!Stores.bDeleted AND !NSOs.bDeleted AND !E.bDeleted
										GROUP BY Stores.ID 
										ORDER BY chrRecord","Getting Stores with Site Surveys");
?>
			<table cellspacing="0" cellpadding="0" class='instructions' style='width: 100%;'>
				<tr>
					<td><?=$directions?></td>
					<td style='text-align: right;'>
						<?=form_select($filter_results,array('nocaption'=>'true',
									'name'=>'idStore',
									'caption'=>'- Stores -',
									'value'=>(isset($_REQUEST['idStore'])?$_REQUEST['idStore']:''),
									'extra'=>' onchange="window.location.href=\'?idStore=\'+this.value"'))?>
					</td>
				</tr>
			</table>

<?
		} else { $topborder = true; }
		messages();
		
		if(!is_numeric($info['ID'])) { 
			$tableHeaders = array(
				'chrStore'				=> array('displayName' => 'Store Name'),
				'chrStoreNum'			=> array('displayName' => 'Store Number'),
				'chrCreator'			=> array('displayName' => 'Created By'),
				'dtStamp'				=> array('displayName' => 'Submitted On','default'=>'desc')
			);
			if(access_check(5,4)) {
				$tableHeaders['opt_del'] = 'chrCreator';
			}
			
		} else {
			$tableHeaders = array(
				'chrCreator'				=> array('displayName' => 'Created By'),
				'dtStamp'					=> array('displayName' => 'Submitted On','default'=>'desc')
						);
			if(access_check(5,4)) {
				$tableHeaders['opt_del'] = 'chrCreator';
			}
		}
		
		sortList('NSOSS',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			'view.php?key=',		# The linkto page when you click on the row
			'width: 100%; '.(!$topborder ? 'border-top: 0;' : ''), 			# Additional header CSS here
			''
		);
		
	}
?>