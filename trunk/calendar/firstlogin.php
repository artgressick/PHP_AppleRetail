<?php
	include('_controller.php');
	
	function sitm() {
		global $BF;
		$q = "SELECT txtHTML FROM LandingPage WHERE ID=11";
		$landingpage = db_query($q,"Getting Landing Page",1);
?>
		<div class='index'>
			<?=decode($landingpage['txtHTML'])?>
		</div>
<?
	} 
?>