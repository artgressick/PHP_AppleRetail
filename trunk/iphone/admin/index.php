<?
	$BF = "../../";
	require($BF. 'iphone/_lib.php');

	//dtn: This is for the sorting of the rows and columns.  We must set the default order and name
	include($BF. 'components/list/sortList.php'); 
	if(!isset($_REQUEST['sortCol'])) { $_REQUEST['sortCol'] = "chrName"; } //dtn: This sets the default column order.  Asc by default.
	
	//jms: Query for List View
	$q = "SELECT ID, chrStoreNum, chrName, 
				(SELECT COUNT(iPhoneRequest.ID) FROM iPhoneRequest WHERE bComplete AND RetailStores.chrStoreNum=iPhoneRequest.chrDivision) as intCount
	 		FROM RetailStores 
	 		WHERE !bDeleted 
	 		ORDER BY ". $_REQUEST['sortCol'] ." ". $_REQUEST['ordCol'];
	$stores = database_query($q, "Getting Stores List");
	
	$title = "Administration Center";
	include($BF. "includes/meta.php");
	include($BF. "includes/top.php");

	$intTotal=0;
?>

		<table width="924" border="0" cellpadding="0" cellspacing="0" class="home_content">
		<tr>
			<td width="100%">
		
				<table cellspacing="0" cellpadding="0" style='width: 924px; '>
					<tr>
						<td style='vertical-align: top; width: 50%;'>

							<div class='header1'>Administration Center <span style='font-size: 12px;'>(<a href='excel-all-export.php'>Download Excel Report For All Stores</a>)</span> </div>
							<div class='instructions'></div>
							
								<table class='List' id='List' style='width: 100%;'  cellpadding="0" cellspacing="0">
								<tr>
									<?  sortList('Store Name', 'chrStoreNum');
										sortList('Total Registrations', 'intCount'); ?>
								</tr>
<? $count=0;	
	while ($row = mysqli_fetch_assoc($stores)) { 
		$link = "list.php?id=".$row['ID'];
		$intTotal += $row['intCount'];
?>
									<tr id='tr<?=$row['ID']?>' class='<?=($count++%2?'ListLineOdd':'ListLineEven')?>' 
									onmouseover='RowHighlight("tr<?=$row['ID']?>");' onmouseout='UnRowHighlight("tr<?=$row['ID']?>");'>
										
										<td style='cursor: pointer;' onclick='location.href="<?=$link?>";'><?=$row['chrName']?> (<?=$row['chrStoreNum']?>)</td>
										<td style='cursor: pointer;' onclick='location.href="<?=$link?>";'><?=number_format($row['intCount'])?></td>
									</tr>
<?	} 
if($count == 0) { ?>
									<tr>
										<td align="center" colspan='2' height="20">No Stores to Display.</td>
									</tr>
<?	} ?>
							</table>
							<div class='header1' style="font-size:14px;">Total Registrations: <?=number_format($intTotal)?></div>
						</td>
					</tr>
				</table>

			</td>
		</tr>
	</table>
	
	
	
<?
	include($BF. "includes/bottom.php");
?>
