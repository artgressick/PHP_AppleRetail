function showOptions(type,num) {
	var box = document.getElementById('options'+num);
	box.className = "additional";

	if(type == 1) {
		// text box

		box.innerHTML = "<em>A text box will appear for this question with a space limit of 255 characters.</em>";

	} else if(type == 2) {
		// Text area (paragraph box)

		box.innerHTML = "<em>A text area will appear for this question.  This will be big enough to hold multiple paragraphs of information.</em>";

	} else if(type == 3) {
		// select box

		box.innerHTML = "<em>A select box appear for this question.  Please fill in the names of the options you would like to use.</em>" +
		fillOptions(num);
		
	} else if(type == 4) {
		// check box

		box.innerHTML = "<em>A set of checkboxes will appear for this question.  Please fill in the names of the options you would like to appear for the checkboxes.</em>" +
		fillOptions(num);

	} else if(type == 5) {
		// radio buttons
		
		box.innerHTML = "<em>A set of radio boxes will appear for this question.  Please fill in the names of the options you would like to appear for the radio boxes.</em>" +
		fillOptions(num);

	}
}

function fillOptions(num) {
	return "<div id='optionset"+ num +"'>" +
			"<table id='optionsetTbl"+ num +"' cellpadding='0' cellspacing='0'><tr><td class='optionlabel'>Option 1:</td><td class='optionBox' id='optionBox"+ num +"-1'><input type='text' name='optionval"+ num +"-1' id='optionval"+ num +"-1' />" +
			"<input type='hidden' name='optionval"+ num +"' id='optionval"+ num +"' value='1' /></td><td class='optionExtra'><em><a id='removeOption"+ num +"-1' href='javascript:eraseOption(\""+ num +"-1\")'>Remove Option</a></em></td></tr></table><div><a href='javascript:newOption("+ num +");'>Add Another Option</a></div>";
}

function addNew() {
	num = document.getElementById('intCount');
	num.value = parseInt(num.value) + 1;

	var div = document.createElement('div');
	
	div.innerHTML = '<table cellspacing="0" cellpadding="0" class="questions" id="question'+ num.value +'">' +
	'	<tr>' +
	'		<td class="lheader"><strong>Question '+ num.value +'</strong></td>' +
	'		<td class="loption"><input type="text"" name="chrQuestion'+ num.value +'" id="chrQuestion'+ num.value +'"" style="width: 325px;"" /></td>' +
	'		<td class="rheader">Required Field</td>' +
	'		<td class="roption"><input type="checkbox" name="bRequired'+ num.value +'" id="bRequired'+ num.value +'" /></td>' +
	'	</tr>' +
	'   <tr>' +
	'		<td class="lheader">Category:</td>' +
	'		<td class="loption"><select name="idSSCat'+ num.value +'" id="idSSCat"'+ num.value +'">'+ eval_cats +'</td>' +
	'		<td class="rheader">Display Order</td>' +
	'		<td class="roption"><input type="text" name="dOrder'+ num.value +'" id="dOrder'+ num.value +'" value="'+ num.value +'" style="width: 25px;" /></td>' +
	'	</tr>' +
	'	<tr>' +
	'		<td class="lheader">Answer Option Types:</td>' +
	'		<td class="loption"><select name="idSSType'+ num.value +'" id="idSSType'+ num.value +'" onchange="showOptions(this.value,'+ num.value +')">'+ eval_types +'</td>' +
	'		<td class="rheader">&nbsp;</td>' +
	'		<td class="roption">&nbsp;</td>' +
	'	</tr>' +
	'	<tr>' +
	'		<td colspan="4" id="options'+ num.value +'"></td>' +
	'	</tr>' +
	'</table>'+
	'<input type="hidden" name="bDeleted'+ num.value +'" id="bDeleted'+ num.value +'" value="0" />' +
	'<div style="text-align: right;"><a href="javascript:eraseQuestion('+ num.value +');" id="addremove'+ num.value +'">Remove Question '+ num.value +'</a></div>';
	
	document.getElementById('questions').appendChild(div);
}

function eraseQuestion(num) {
	var val = document.getElementById('question'+num);
	if(val.style.display == 'none') {
		val.style.display = '';
		document.getElementById('addremove'+num).innerHTML = 'Remove Question '+num;
		document.getElementById('bDeleted'+num).value = '0';
	} else {
		val.style.display = 'none';
		document.getElementById('addremove'+num).innerHTML = 'Re-Add Question '+num;
		document.getElementById('bDeleted'+num).value = '1';
	}
}

function eraseOption(num) {
	var val = document.getElementById('removeOption'+num);
	if(document.getElementById('removedVal'+num)) {
		var remVal = document.getElementById('removedVal'+num).innerHTML;
		document.getElementById('optionBox'+num).innerHTML = "<input type='text' name='optionval"+num+"' id='optionval"+num+"' value='"+ remVal +"' />";
		document.getElementById('removeOption'+num).innerHTML = "Remove Option";
	} else {
		var tmpVal = document.getElementById('optionval'+num).value;
		document.getElementById('optionBox'+num).innerHTML = "Option Removed<span id='removedVal"+num+"' style='display:none;'>"+tmpVal+"</span>";
		document.getElementById('removeOption'+num).innerHTML = "Re-Enable Option";
	}
}

function newOption(num) {
	var currentnum = document.getElementById('optionval'+num);
	currentnum.value = parseInt(currentnum.value) + 1;
	
	var tr = document.createElement('tr');
	var td1 = document.createElement('td');
	var td2 = document.createElement('td');
	var td3 = document.createElement('td');
	td1.className='optionlabel';
	td1.innerHTML = "<td>Option "+ currentnum.value +":</td>";
	
	td2.className='optionBox';
	td2.id='optionBox'+ num +'-'+ currentnum.value;
	td2.innerHTML="<input type='text' name='optionval"+ num +"-"+ currentnum.value +"' id='optionval"+ num +"-"+ currentnum.value +"' /></td>";

	td3.className='optionExtra';
	td3.innerHTML = "<em><a id='removeOption"+ num +"-"+ currentnum.value +"' href='javascript:eraseOption(\""+ num +"-"+ currentnum.value +"\")'>Remove Option</a></em></td>";
	
	tr.appendChild(td1);
	tr.appendChild(td2);
	tr.appendChild(td3);
	document.getElementById("optionsetTbl"+ num).appendChild(tr);
}
  
