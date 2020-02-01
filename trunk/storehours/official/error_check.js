document.write('<script type="text/javascript" src="'+ BF + 'storehours/includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
	totalErrors = 0;
	
	if(document.getElementById('bClosed0').checked==false) {
		var dow = "Sunday";

		if(errEmpty('tOpening0', "You must enter a Begin Time for "+dow+".")) { 
			totalErrors++;
		} else if(filter_check('tOpening0','You must enter a proper Begin Time for '+dow+'.')) {
			totalErrors++;
		}
		if(errEmpty('tClosing0', "You must enter an End Time for "+dow+".")) { 
			totalErrors++; 
		} else if(filter_check('tClosing0','You must enter a proper End Time for '+dow+'.')) {
			totalErrors++;
		}
	}
	if(document.getElementById('bClosed1').checked==false) {
		var dow = "Monday";

		if(errEmpty('tOpening1', "You must enter a Begin Time for "+dow+".")) { 
			totalErrors++;
		} else if(filter_check('tOpening1','You must enter a proper Begin Time for '+dow+'.')) {
			totalErrors++;
		}
		if(errEmpty('tClosing1', "You must enter an End Time for "+dow+".")) { 
			totalErrors++; 
		} else if(filter_check('tClosing1','You must enter a proper End Time for '+dow+'.')) {
			totalErrors++;
		}
	}
	if(document.getElementById('bClosed2').checked==false) {
		var dow = "Tuesday";

		if(errEmpty('tOpening2', "You must enter a Begin Time for "+dow+".")) { 
			totalErrors++;
		} else if(filter_check('tOpening2','You must enter a proper Begin Time for '+dow+'.')) {
			totalErrors++;
		}
		if(errEmpty('tClosing2', "You must enter an End Time for "+dow+".")) { 
			totalErrors++; 
		} else if(filter_check('tClosing2','You must enter a proper End Time for '+dow+'.')) {
			totalErrors++;
		}
	}
	if(document.getElementById('bClosed3').checked==false) {
		var dow = "Wednesday";

		if(errEmpty('tOpening3', "You must enter a Begin Time for "+dow+".")) { 
			totalErrors++;
		} else if(filter_check('tOpening3','You must enter a proper Begin Time for '+dow+'.')) {
			totalErrors++;
		}
		if(errEmpty('tClosing3', "You must enter an End Time for "+dow+".")) { 
			totalErrors++; 
		} else if(filter_check('tClosing3','You must enter a proper End Time for '+dow+'.')) {
			totalErrors++;
		}
	}
	if(document.getElementById('bClosed4').checked==false) {
		var dow = "Thursday";

		if(errEmpty('tOpening4', "You must enter a Begin Time for "+dow+".")) { 
			totalErrors++;
		} else if(filter_check('tOpening4','You must enter a proper Begin Time for '+dow+'.')) {
			totalErrors++;
		}
		if(errEmpty('tClosing4', "You must enter an End Time for "+dow+".")) { 
			totalErrors++; 
		} else if(filter_check('tClosing4','You must enter a proper End Time for '+dow+'.')) {
			totalErrors++;
		}
	}
	if(document.getElementById('bClosed5').checked==false) {
		var dow = "Friday";

		if(errEmpty('tOpening5', "You must enter a Begin Time for "+dow+".")) { 
			totalErrors++;
		} else if(filter_check('tOpening5','You must enter a proper Begin Time for '+dow+'.')) {
			totalErrors++;
		}
		if(errEmpty('tClosing5', "You must enter an End Time for "+dow+".")) { 
			totalErrors++; 
		} else if(filter_check('tClosing5','You must enter a proper End Time for '+dow+'.')) {
			totalErrors++;
		}
	}
	if(document.getElementById('bClosed6').checked==false) {
		var dow = "Saturday";

		if(errEmpty('tOpening6', "You must enter a Begin Time for "+dow+".")) { 
			totalErrors++;
		} else if(filter_check('tOpening6','You must enter a proper Begin Time for '+dow+'.')) {
			totalErrors++;
		}
		if(errEmpty('tClosing6', "You must enter an End Time for "+dow+".")) { 
			totalErrors++; 
		} else if(filter_check('tClosing6','You must enter a proper End Time for '+dow+'.')) {
			totalErrors++;
		}
	}

	return (totalErrors == 0 ? true : false);
}

function filter_check(name,message) {
	var filter = /^(\s)*((([1-9]|1[0-2])(\:\d\d)?(\s)*(([aA]|[pP])(m|M)))|(0?[0-9]|1[0-9]|2[0-3])(\:\d\d))(\s)*$/;
	if(!filter.test(document.getElementById(name).value)) {
		errCustom(name,message);
		return true;
	}
	return false;
}