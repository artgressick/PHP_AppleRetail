<?
	$BF = '../';
	$NON_HTML_PAGE=1;
	include($BF.'calendar/_lib.php');
	include($BF.'calendar/components/add_functions.php');

	function cal_escape($string) {
		return(str_replace(array("\\", ',', ';', "\n", "\r"), array("\\\\", "\\,", "\\;", '\n', ''), $string));
	}
	
	header('Content-type: text/plain');
	
?>
BEGIN:VCALENDAR
VERSION:2.0
X-WR-CALNAME:StoreOPS_NSO_Calendar

PRODID:-//Apple Computer (Internal)//StoreOPS//EN
X-WR-TIMEZONE:US/Pacific
CALSCALE:GREGORIAN
LAST-MODIFIED:<?=date('Ymd\Thms')?>Z

<?
	$q = db_query("SELECT chrCalendarQuery FROM CalendarQueries WHERE chrKEY='". $_REQUEST['k'] ."'","Getting Query",1);
		
	$results = db_query(decode($q['chrCalendarQuery']),"Getting Information");
	
	$series = "X";
	while($row = mysqli_fetch_assoc($results)) {
		if(!preg_match('/(^|,)'. $row['chrSeries'] .'(,|$)/',$series)) { 
			if($row['chrSeries'] != "") { $series = $row['chrSeries'].","; }
?>


BEGIN:VEVENT

DTSTAMP:<?=date('Ymd\Thms', strtotime('now'))?>Z

URL;VALUE=URI:<?=$PROJECT_ADDRESS?>calendar/

DTSTART;VALUE=DATE:<?=strftime('%Y%m%d', strtotime($row['dBegin']))?><? if($row['tBegin'] != "" && $row['tBegin'] != "00:00:00") { echo "T".str_replace(':','',$row['tBegin']); } ?>

<?		if(!$row['bAllDay']) { ?>

DTEND;VALUE=DATE:<?=strftime('%Y%m%d', strtotime($row['dBegin']))?><? if($row['tEnd'] != "" && $row['tEnd'] != "00:00:00") { echo "T".str_replace(':','',$row['tEnd']); } ?>

<?		} else { ?>

DTEND;VALUE=DATE:<?=strftime('%Y%m%d', strtotime($row['dMaxDate'].' + 1 day'))?>

<?		} ?>
SUMMARY:<?=$row['chrCalendarEvent']?>

<?		if(false) { ?>

DESCRIPTION:You scheduled to work at:\nRoom: <?=decode($row['chrRoomName'])?>\nDate: <?=strftime('%m/%d/%Y', strtotime($row['dDate']))?>\nTimes: <?=date('g:m a',strtotime($row['tBegin']))?> - <?=date('g:m a',strtotime($row['tEnd']))?>\nDescription: <?=decode($row['chrDescription'])?>

<? 		} ?>

UID:STOREOPSCAL<?=$row['ID']?>

<?		if(false) { ?>

BEGIN:VALARM

ACTION:DISPLAY

X-WR-ALARMUID:ALARM<?=$row['ID']?>

DESCRIPTION:You scheduled to work at:\nRoom: <?=decode($row['chrRoomName'])?>\nDate: <?=strftime('%m/%d/%Y', strtotime($row['dDateFormated']))?>\nTimes: <?=date('g:m a',strtotime($row['tBegin']))?> - <?=date('g:m a',strtotime($row['tEnd']))?>\nDescription: <?=decode($row['chrDescription'])?>

TRIGGER:-PT15M

END:VALARM
<?		} ?>

<?		if($row['chrReoccur'] != "") { 
			if($row['chrReoccur'] == 'day') { 
				$type = "DAILY";
			} else if($row['chrReoccur'] == 'week') { 
				$type = "WEEKLY";
			} else if($row['chrReoccur'] == 'month') { 
				$type = "MONTLY";
			} else if($row['chrReoccur'] == 'year') { 
				$type = "YEARLY";
			} else { 
				$type = "DAILY";
			}
?>

RRULE:FREQ=<?=$type?>;INTERVAL=1;UNTIL=<?=str_replace("-","",$row['dMaxDate'])?><? if($row['tEnd'] != "" && $row['tEnd'] != "00:00:00" && !$row['bAllDay']) { echo "T".str_replace(':','',$row['tEnd']); } ?>


<?		} ?>

END:VEVENT

<?		}
	} ?>

END:VCALENDAR
