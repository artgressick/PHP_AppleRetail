document.write('<script type="text/javascript" src="'+ BF + 'storehours/includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	totalErrors = 0;
	
	var totalDays = document.getElementById('totalDays').value;
	
	var i=0;
	
	while(i <= totalDays) {
		if(document.getElementById('bClosed' + i).checked==false) {
			var dow = document.getElementById('chrDateText' + i).innerHTML;
	
			if(errEmpty('tOpening' + i, "You must enter a New Begin Time for "+dow+".")) { 
				totalErrors++;
			} else if(filter_check('tOpening' + i,'You must enter a proper New Begin Time for '+dow+'.')) {
				totalErrors++;
			}
			if(errEmpty('tClosing' + i, "You must enter an New End Time for "+dow+".")) { 
				totalErrors++; 
			} else if(filter_check('tClosing' + i,'You must enter a proper New End Time for '+dow+'.')) {
				totalErrors++;
			}
		} else { setColorDefault('tOpening' + i); setColorDefault('tClosing' + i); }
		i++;
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