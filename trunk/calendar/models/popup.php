<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?=(isset($title) && $title != '' ? $title .' - ' : '')?>Retail Ops</title>
		<link href="<?=$BF?>includes/global.css" rel="stylesheet" type="text/css" />
		<link href="<?=$BF?>calendar/includes/calendar.css" rel="stylesheet" type="text/css" />
		<script type='text/javascript'>var BF = '<?=$BF?>';</script>
<?		# If the "Stuff in the Header" function exists, then call it
		if(function_exists('sith')) { sith(); } 
?>
	</head>
	<body onload='<?=(isset($bodyParams) ? $bodyParams : '')?>'>
<?
		// echo "<pre>"; print_r($_SESSION); echo "</pre>"; // This is to display the SESSION variables, unrem to use
?>
	<div class='header2' style='padding-left:10px;'><?=$header_title?></div>
	<div class='innerbody' style='padding:10px;'>

<?	if(!isset($custom_directions)) { ?>
					<div class='instructions'><?=$directions?></div>
<?	} ?>
					<?=messages()?>
					<!-- Begin code -->
<?
	# This is where we will put in the code for the page.
	(isset($sitm) && $sitm != '' && function_exists($sitm) ? $sitm() : sitm());
?>
					<!-- End code -->
<?
	# Any aditional things can go down here including javascript or hidden variables
	# "Stuff on the Bottom"
	if(function_exists('sotb')) { sotb(); } 
?>
	</body>
</html>
