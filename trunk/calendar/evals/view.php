<?
	include('_controller.php');

	function sitm() { 
		global $BF,$info;
?>
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td width="50%"><?=form_text(array('caption'=>'Store Name/Store Number','value'=>$info['chrName'].' / '.$info['chrStoreNum'],'display'=>'true','style'=>'color:blue;'))?></td>
				<td width="50%"><?=form_text(array('caption'=>'Completed By','display'=>'true','value'=>$info['chrFirst'].' '.$info['chrLast'],'style'=>'color:blue;'))?></td>
			</tr>
			<tr>
				<td colspan='2'><?=form_text(array('caption'=>'Date','value'=>date('m/d/Y',strtotime($info['dtStamp'])),'display'=>'true','style'=>'color:blue;'))?></td>
			</tr>
		</table>
<?
			$results = db_query("SELECT idEvalQuestion, txtAnswer FROM EvalAnswers WHERE idNSOEval=".$info['ID'],"Getting Answers");
			$answers = array();
			while($row = mysqli_fetch_assoc($results)) {
				$answers[$row['idEvalQuestion']] = $row['txtAnswer'];
			}

			$results = db_query("SELECT Q.ID,Q.chrQuestion, EC.chrCat, Q.idEvalCat
				FROM EvalQuestions AS Q
				JOIN EvalCats AS EC ON Q.idEvalCat=EC.ID
				WHERE Q.idEval = ".$info['idEval']."
				ORDER BY EC.intOrder, EC.chrCat,Q.dOrder,Q.idEvalType,Q.chrQuestion
			","Getting Event info");
			$prevCat = '';
			while($row = mysqli_fetch_assoc($results)) {
			if($prevCat != $row['idEvalCat']) {
				$prevCat = $row['idEvalCat'];
?>
			<hr />		
			<div style="font-size:14px; font-weight:bold;"><?=$row['chrCat']?></div>
<?
			}
				
?>
			<div style='padding-bottom:10px;'><?=form_text(array('caption'=>$row['chrQuestion'],'display'=>'true','value'=>(isset($answers[$row['ID']]) && $answers[$row['ID']] != ''?nl2br($answers[$row['ID']]):'N/A'),'style'=>'color:blue;'))?></div>
<?
			}
		$files = db_query("SELECT * 
							FROM NSOFiles 
							WHERE idType=1 AND idReference=".$info['ID']." AND chrThumbnail != ''
							ORDER BY chrFile","Getting All Pictures for this Site Survey");
		if(mysqli_num_rows($files) > 0) {
?>
		<hr />
		<div style="font-size:14px; font-weight:bold;padding-bottom:5px;">Pictures</div>
			<table border="0" cellpadding="3" cellspacing="0" style='border:1px solid #CCC;'>
				<tr>
<?
			$columns = 1;
			$cnt = 0;
			while($row = mysqli_fetch_assoc($files)) {
				$cnt++;
				if ($columns++ > 5) {
					$columns = 1;
?>
					</tr>
					<tr>
<?
				}
?>
						<td><?=linkto(array('address'=>'calendar/files/'.$row['chrFile'],'img'=>'calendar/files/'.$row['chrThumbnail']))?></td>
<?
			}
?>

				</tr>
			</table>		
<?
		}
		$files = db_query("SELECT * 
							FROM NSOFiles 
							WHERE idType=1 AND idReference=".$info['ID']." AND chrThumbnail = ''
							ORDER BY chrFile","Getting All Other Files for this Site Survey");
		if(mysqli_num_rows($files) > 0) {
?>
		<hr />
		<div style="font-size:14px; font-weight:bold;padding-bottom:5px;">Other Files</div>
			<table border="0" cellpadding="10" cellspacing="0" style='border:1px solid #CCC;'>
				<tr>
		
<?
			$columns = 1;
			$cnt = 0;
			while($row = mysqli_fetch_assoc($files)) {
				$cnt++;
				if ($columns++ > 4) {
					$columns = 1;
?>
					</tr>
					<tr>
<?
				}
?>
						<td><?=linkto(array('address'=>'calendar/files/'.$row['chrFile'],'display'=>$row['chrFile']))?></td>
<?
			}
?>

				</tr>
			</table>		
<?
		}
		
	}
	
?>