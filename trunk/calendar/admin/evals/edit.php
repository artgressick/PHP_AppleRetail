<?
	include('_controller.php');
	
	function sitm() { 
		global $BF;
		
		$eval_type_results = db_query("SELECT ID,chrEvalType FROM EvalTypes WHERE !bDeleted","getting eval types");	
		
		$eval_types = '<option value="">-Select Option Types-</option>';
		while($row = mysqli_fetch_row($eval_type_results)) {
			$eval_types .= '<option value="'.$row[0] .'">'.$row[1].'</option>';
		}
		$eval_types .= "</select>";

		$eval_cat_results = db_query("SELECT ID,chrCat FROM EvalCats WHERE !bDeleted ORDER BY intOrder","getting eval cats");	
		
		$eval_cats = '<option value="">-Select Category-</option>';
		while($row = mysqli_fetch_row($eval_cat_results)) {
			$eval_cats .= '<option value="'.$row[0] .'">'.$row[1].'</option>';
		}
		$eval_cats .= "</select>";
		
		
		
		$q = "SELECT bRequired,idEvalType,dOrder,chrQuestion,txtOptions,idEvalCat
			FROM EvalQuestions
			WHERE idEval IN (SELECT ID FROM Evals WHERE chrKEY='". $_REQUEST['key'] ."')
			ORDER BY dOrder,idEvalType,chrQuestion";
		$results = db_query($q,"getting previous questions");
		
		# This is the messages to show for each of the eval types;
		$eval_messages[1] = "<em>A text box will appear for this question with a space limit of 255 characters.</em>";
		$eval_messages[2] = "<em>A text area will appear for this question.  This will be big enough to hold multiple paragraphs of information.</em>";
		$eval_messages[3] = "<em>A select box appear for this question.  Please fill in the names of the options you would like to use.</em>";
		$eval_messages[4] = "<em>A set of checkboxes will appear for this question.  Please fill in the names of the options you would like to appear for the checkboxes.</em>";
		$eval_messages[5] = "<em>A set of radio boxes will appear for this question.  Please fill in the names of the options you would like to appear for the radio boxes.</em>";			

?>
									<form action="" method="post" id="idForm" onsubmit="return error_check()">
									<div id='questions' style='margin-bottom: 10px;'>
<?
		$i = 1;
		while($row = mysqli_fetch_assoc($results)) {
?>
										
											<table cellspacing="0" cellpadding="0" class='questions' id='question<?=$i?>'>
												<tr>
													<td class='lheader'><strong>Question <?=$i?></strong></td>
													<td class='loption'><input type='text' name='chrQuestion<?=$i?>' id='chrQuestion<?=$i?>' style='width: 325px;' value='<?=$row['chrQuestion']?>' /></td>
													<td class='rheader'>Required Field</td>
													<td class='roption'><input type='checkbox' name='bRequired<?=$i?>' id='bRequired<?=$i?>'<?=($row['bRequired'] == 1 ? ' checked="checked"' : '')?> /></td>
												</tr>
												<tr>
													<td class='lheader'>Category:</td>
													<td class='loption'><select name='idEvalCat<?=$i?>' id='idEvalCat<?=$i?>'><?=str_replace('value="'.$row['idEvalCat'].'"','value="'.$row['idEvalCat'].'" selected="selected"',$eval_cats)?></td>
													<td class='rheader'>Display Order</td>
													<td class='roption'><input type='text' name='dOrder<?=$i?>' id='dOrder<?=$i?>' value='<?=$row['dOrder']?>' style='width: 25px;' /></td>
												</tr>
												<tr>
													<td class='lheader'>Answer Option Types:</td>
													<td class='loption'><select name='idEvalType<?=$i?>' id='idEvalType<?=$i?>' onchange='showOptions(this.value,<?=$i?>)'><?=str_replace('value="'.$row['idEvalType'].'"','value="'.$row['idEvalType'].'" selected="selected"',$eval_types)?></td>
													<td class='rheader'>&nbsp;</td>
													<td class='roption'>&nbsp;</td>
												</tr>
												<tr>
													<td colspan='4' id='options<?=$i?>' class='additional'>
														<div id='optionset<?=$i?>'>
														<?=$eval_messages[$row['idEvalType']]?>
<?			
				if($row['txtOptions'] != "") {
?>
														<table id='optionsetTbl<?=$i?>' cellpadding="0" cellspacing="0">
<?					$tmp_options = explode('|||',$row['txtOptions']);
					$len = count($tmp_options);
					$k = 1;
					while($k <= $len) {
?>
															<tr>
																<td class='optionlabel'>Option <?=$k?>:</td>
																<td class='optionBox' id='optionBox<?=$i?>-<?=$k?>'><input type='text' name='optionval<?=$i?>-<?=$k?>' id='optionval<?=$i?>-<?=$k?>' value='<?=$tmp_options[$k-1]?>' /></td>
																<td class='optionExtra'><em><a id='removeOption<?=$i?>-<?=$k?>' href='javascript:eraseOption("<?=$i?>-<?=$k?>")'>Remove Option</a></em></td>
															</tr>
<?						$k++;
					}
?>
														</table>
														</div><input type='hidden' name='optionval<?=$i?>' id='optionval<?=$i?>' value='<?=$len?>' /><div style='padding: 5px 10px;'><a href='javascript:newOption(<?=$i?>);'>Add Another Option</a></div> 
<?
				}
?>
														</div>
													</td>
												</tr>
											</table>
											<input type="hidden" name="bDeleted<?=$i?>" id="bDeleted<?=$i?>" value="0" />
											<div style="text-align: right;"><a href="javascript:eraseQuestion(<?=$i?>);" id="addremove1">Remove Question <?=$i?></a></div>
<?	
			$i++;
		} 
?>
										</div>
										<div><a href='javascript:addNew();'>Add New Question</a></div>

										<div class='FormButtons'>
											<?=form_button(array('type'=>'submit','value'=>'Update Information'))?>
											<input type='hidden' name='intCount' id='intCount' value='<?=--$i?>' />
										</div>



										<script type='text/javascript'>
											var eval_types = '<?=$eval_types?>';
											var eval_cats = '<?=$eval_cats?>';
											var eval_messages = new Array('','<?=implode("','",$eval_messages)?>');
										</script>
									</form>
<?
	}
?>