<?	include('_controller.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>New Features Added</title>
	<script type='text/javascript'>var BF = '<?=$BF?>';</script>
</head>
<body>
	<div align="center" style='margin: 10px auto;'><a href="javascript:window.close();">Close Window</a></div>
<?
$popup = db_query("SELECT txtAnnoucement FROM NSONewFeatures WHERE bShow AND ID=1","Gettin Popup",1);
?>
	<div style='border: 1px solid gray; padding: 10px;'><?=decode($popup['txtAnnoucement'])?></div>
<div align="center" style='margin: 10px auto;'><a href="javascript:window.close();">Close Window</a></div>
</body>
</html>
