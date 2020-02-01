<?
	$BF = "../../";
	require($BF. 'iphone/_lib.php');
	require_once "Spreadsheet/Excel/Writer.php";

	$results = database_query($_SESSION['excelQuery']);

	// create workbook
	$workbook = new Spreadsheet_Excel_Writer();
	
	$workbook->send(date('M-d-Y')."_".str_replace(' ','_',$_SESSION['chrStoreName']).'_iPhone_Report.xls');	
	
	// create format for column headers
	$format_column_header =& $workbook->addFormat();
	$format_column_header->setBold();
	$format_column_header->setSize(10);
	$format_column_header->setAlign('left');
	
	// create data format
	$format_data =& $workbook->addFormat();
	$format_data->setSize(10);
	$format_data->setAlign('left');
	
	// Create worksheet
	$worksheet =& $workbook->addWorksheet('Participants');
	$worksheet->hideGridLines();

	$column_num = 0;
	$row_num = 0;

	$worksheet->setColumn($column_num, $column_num, 20);
	$worksheet->write($row_num, $column_num, 'First Name', $format_column_header);
	$column_num++;
	$worksheet->setColumn($column_num, $column_num, 20);
	$worksheet->write($row_num, $column_num, 'Last Name', $format_column_header);
	$column_num++;
	$worksheet->setColumn($column_num, $column_num, 12);
	$worksheet->write($row_num, $column_num, 'Employee ID', $format_column_header);
	$column_num++;
	$worksheet->setColumn($column_num, $column_num, 15);
	$worksheet->write($row_num, $column_num, 'Date Submitted', $format_column_header);
	$column_num++;
	$worksheet->setColumn($column_num, $column_num, 15);
	$worksheet->write($row_num, $column_num, 'Time Submitted', $format_column_header);
	$column_num++;
	
	$row_num++;

	$totalEmployees = 0;
	while($row = mysqli_fetch_assoc($results)) {
	
		++$totalEmployees;
		$column_num = 0;
		
		$worksheet->write($row_num, $column_num, $row['chrFirst'], $format_data);
		$column_num++;
		$worksheet->write($row_num, $column_num, $row['chrLast'], $format_data);
		$column_num++;
		$worksheet->write($row_num, $column_num, $row['chrEmpID'], $format_data);
		$column_num++;
		$worksheet->write($row_num, $column_num, $row['dtCreated2'], $format_data);
		$column_num++;
		$worksheet->write($row_num, $column_num, $row['dtTime'], $format_data);
		$column_num++;
	
		$row_num++;
	}
	
	$column_num = 0;
	$row_num += 2;

	$worksheet->setColumn($column_num, $column_num, 20);
	$worksheet->write($row_num, $column_num, 'Total Employees', $format_column_header);
	$column_num++;
	
	$worksheet->write($row_num, $column_num, $totalEmployees, $format_data);
	$column_num++;
	
	$workbook->close();