<?
	$BF='../../';
	$NON_HTML_PAGE = true;
	require($BF. '_lib.php');

	$q = "SELECT idStatus,date_format(dtStamp,'%M %Y') as dFormated
		FROM EmailMessages
		JOIN RFLEmails ON EmailMessages.idType=RFLEmails.ID
		WHERE dtStamp > '". date('Y-m-01',strtotime('now - 6 months')) ."'
		". ($_REQUEST['filter'] != '' ? " AND RFLEmails.idGeo='". $_REQUEST['filter'] ."' " : "") . "
		ORDER BY dtStamp ASC";
		
	$results = database_query($q,"getting results");
	$values = array();
	while($row = mysqli_fetch_assoc($results)) {
		if(!isset($values[$row['dFormated']])) { 
			$values[$row['dFormated']] = array(); 
			$values[$row['dFormated']]['intTotal'] = 0;
			$values[$row['dFormated']]['intOpened'] = 0;
			$values[$row['dFormated']]['intPending'] = 0;
		}
		$values[$row['dFormated']]['intTotal'] += 1;
		$values[$row['dFormated']]['intOpened'] += ($row['idStatus'] == 1 ? 1 : 0);
		$values[$row['dFormated']]['intPending'] += ($row['idStatus'] == 2 ? 1 : 0);
	}

?>

<chart palette='3' caption='6 Month Graph View' YaxisName='# of Escalations' labelDisplay='Rotate' slantLabels='1' useRoundEdges='1' showValues='0' legendBorderAlpha='0'>  

<categories>
<?	foreach(array_keys($values) as $v) { ?>
      <category label='<?=$v?>' />
<?	} ?>
</categories>

<dataset seriesname='Pending' color='FF5904'>
<?	foreach(array_keys($values) as $v) { ?>
      <set value='<?=$values[$v]['intPending']?>' />
<?	} ?>
</dataset>


<dataset seriesname='Open' color='333333'>
<?	foreach(array_keys($values) as $v) { ?>
      <set value='<?=$values[$v]['intOpened']?>' />
<?	} ?>
</dataset>


<dataset seriesname='Total Escalations Left' color='999999'>
<?	foreach(array_keys($values) as $v) { ?>
      <set value='<?=($values[$v]['intTotal'] - ($values[$v]['intOpened'] + $values[$v]['intPending']))?>' />
<?	} ?>
</dataset>


</chart>