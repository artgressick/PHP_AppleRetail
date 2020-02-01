<?
	$BF = "../../";
	require($BF. 'iphone/_lib.php');
	
	//dtn: This is for the sorting of the rows and columns.  We must set the default order and name, this is not for sorting but for formatting
	include($BF. 'components/list/sortList.php'); 
	if(!isset($_REQUEST['sortCol'])) { $_REQUEST['sortCol'] = "chrLast,chrFirst"; } //dtn: This sets the default column order.  Asc by default.

	$q = "SELECT iPhoneRequest.ID,chrFirst,chrLast,chrEmpID,iPhoneRequest.chrDivision,RetailStores.chrName as chrStore,dtCreated,DATE_FORMAT(dtCreated,'%M %D, %Y') as dtCreated2,
			DATE_FORMAT(dtCreated,'%r') as dtTime
			FROM iPhoneRequest
			JOIN RetailStores ON RetailStores.chrStoreNum=iPhoneRequest.chrDivision
			WHERE bComplete AND RetailStores.ID='".$_REQUEST['id']."'";
			
	if(isset($_POST['chrSearch']) && $_POST['chrSearch'] != "") {
		
		$q .= " AND (LOWER(iPhoneRequest.chrFirst) LIKE LOWER('%".encode($_POST['chrSearch'])."%') 
						OR LOWER(iPhoneRequest.chrLast) LIKE LOWER('%".encode($_POST['chrSearch'])."%') 
						OR LOWER(iPhoneRequest.chrEmpID) LIKE LOWER('%".encode($_POST['chrSearch'])."%') 
						OR LOWER(iPhoneRequest.chrEmail) LIKE LOWER('%".encode($_POST['chrSearch'])."%')
						OR LOWER(iPhoneRequest.chrSerial) LIKE LOWER('%".encode($_POST['chrSearch'])."%'))";
	
	}
			
			
	$q .= " ORDER BY ". $_REQUEST['sortCol'] ." ". $_REQUEST['ordCol'];
	$results = database_query($q,"Getting Results");
	$_SESSION['excelQuery'] = $q;
	
	$store = database_query("SELECT chrName, chrStoreNum FROM RetailStores WHERE ID='".$_REQUEST['id']."'", "Getting Store Name",1);
	$_SESSION['chrStoreName'] = $store['chrName'] ." (".$store['chrStoreNum'].")";
	
	$title = "Administration Center";
	include($BF. "includes/meta.php");
	include($BF. "includes/top.php");

?>
		<form enctype="multipart/form-data" action="" method="post" id="idForm">
		<table width="924" border="0" cellpadding="0" cellspacing="0" class="home_content">
		<tr>
			<td width="100%">
		
				<table cellspacing="0" cellpadding="0" style='width: 924px; '>
					<tr>
						<td style='vertical-align: top; width: 50%;'>
							<table width="100%" cellspacing="0" cellpadding="0" border="0">
								<tr>
									<td class='header1' align="left">Administration Center - <?=$_SESSION['chrStoreName']?> List</td>
									<td class='header1' style='font-size: 12px; text-align:right;'>(<a href='excel-export.php'>Download Excel Report</a>)</td>
								</tr>
								<tr>
									<td class='greenFilter' align="left" style="border-right:none;">Sort the report as you would like to see it.  Download the excel document in the same format.</td>
									<td class='greenFilter' align="right" style="border-left:none;">Search 
										<input type="text" name="chrSearch" id="chrSearch" size="30" value="<?=(isset($_POST['chrSearch']) && $_POST['chrSearch'] != "" ? $_POST['chrSearch'] : "")?>" /> <input type="submit" value="Go" />
									</td>
								</tr>
							</table>							
							<table class='List' id='List' style='width: 100%;'  cellpadding="0" cellspacing="0">
								<tr>
									<?  sortList('First Name', 'chrFirst','','id='.$_REQUEST['id']);
										sortList('Last Name', 'chrLast','','id='.$_REQUEST['id']);
										sortList('Employee ID', 'chrEmpID','','id='.$_REQUEST['id']);
										sortList('Date Submitted', 'dtCreated','','id='.$_REQUEST['id']);
										sortList('Time Submitted', 'dtCreated','','id='.$_REQUEST['id']); ?>
								</tr>
<? $count=0;	
	while ($row = mysqli_fetch_assoc($results)) { 
		$link = "edit.php?id=".$row['ID'];
?>
									<tr id='tr<?=$row['ID']?>' class='<?=($count++%2?'ListLineOdd':'ListLineEven')?>' 
									onmouseover='RowHighlight("tr<?=$row['ID']?>");' onmouseout='UnRowHighlight("tr<?=$row['ID']?>");'>
										
										<td style='cursor: pointer;' onclick='location.href="<?=$link?>";'><?=$row['chrFirst']?></td>
										<td style='cursor: pointer;' onclick='location.href="<?=$link?>";'><?=$row['chrLast']?></td>
										<td style='cursor: pointer;' onclick='location.href="<?=$link?>";'><?=$row['chrEmpID']?></td>
										<td style='cursor: pointer;' onclick='location.href="<?=$link?>";'><?=$row['dtCreated2']?></td>
										<td style='cursor: pointer;' onclick='location.href="<?=$link?>";'><?=$row['dtTime']?></td>
									</tr>
<?	} 
if($count == 0) { ?>
									<tr>
										<td align="center" colspan='5' height="20">No one has registered.</td>
									</tr>
<?	} ?>
							</table>
															
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	</form>
	
	
	
<?
	include($BF. "includes/bottom.php");
?>
