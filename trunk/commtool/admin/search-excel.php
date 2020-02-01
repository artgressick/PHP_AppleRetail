<?
	$BF='../../';
	$NON_HTML_PAGE = true;
	require($BF. '_lib.php');

	$results = database_query($_SESSION['queryForExcel'],"Getting results");
	$name = mysqli_fetch_assoc($results);
	mysqli_data_seek($results,0);

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=". strtolower(str_replace(' ','_',$name['chrName'])) .".xls");
header("Pragma: no-cache");
header("Expires: 0"); 


	$data = "<table border='1'>";
	$data .= "<tr>";
	$data .= '<td>Store Number</td>';
	$data .= '<td>Store Name</td>';
	$data .= '<td>Email Type</td>';
	$data .= '<td>Geo</td>';
	$data .= '<td>Date Sent</td>';
	$data .= '<td>Status</td>';
	$data .= "</tr>";
	
	while($row = mysqli_fetch_assoc($results)) {
		$data .= '<tr><td>'. $row['chrStoreNum'] .'</td>';
		$data .= '<td>'. decode($row['chrStore']) .'</td>';
		$data .= '<td>'. decode($row['chrName']) .'</td>';
		$data .= '<td>'. decode($row['chrGeo']) .'</td>';
		$data .= '<td>'. date('n/j/Y g:i a',strtotime($row['dtStamp'])).'</td>';
		$data .= '<td>'. $row['chrStatus'].'</td>';
		
		$row['txtMessage'] = decode($row['txtMessage']);
		$row['txtMessage'] = str_replace("<br />"," ",$row['txtMessage']);
		$row['txtMessage'] = str_replace("<br/>"," ",$row['txtMessage']);
		$row['txtMessage'] = str_replace("<br>"," ",$row['txtMessage']);
		$tmp = explode(":::",str_replace("|||"," ",$row['txtMessage']));
		
		foreach($tmp as $v) {
			$data .= '<td>'.$v.'</td>';		
		}
		
		$data .= "</tr>";
	}
	$data .= "</table>";
	
	echo $data;
?>
