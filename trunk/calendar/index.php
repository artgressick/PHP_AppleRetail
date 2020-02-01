<?php
	include('_controller.php');
	
	function sitm() {
		global $BF;
		$q = "SELECT txtHTML FROM LandingPage WHERE ID=10";
		$landingpage = db_query($q,"Getting Landing Page",1);
?>
		<div class='index'>
			<?=decode($landingpage['txtHTML'])?>
		</div>
<?
	$lastupdate = db_query("SELECT MAX(dtUpdated) AS dtUpdated FROM NSOs WHERE !bDeleted","Get Last Update",1);
?>
		<div style='padding-top:23px;font-weight:normal;'>Last Updated made on: <span style=''><?=($lastupdate['dtUpdated'] != '0000-00-00 00:00:00.0' ? date('l, F jS, Y - g:i a',strtotime($lastupdate['dtUpdated'])) : 'N/A')?></span></div>
<?	} ?>