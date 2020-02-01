document.write('<script type="text/javascript" src="'+ BF + 'calendar/includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
	totalErrors = 0;
		if(errEmpty('idStore', "You must choose a Store.")) { totalErrors++; }
		if(errEmpty('idNSOType', "You must choose a NSO Type.")) { totalErrors++; }

	if(document.getElementById('dBegin').value == '' && document.getElementById('idBeginStatus1').checked==false && document.getElementById('idBeginStatus2').checked==false) {
		errCustom('','You must either enter a value or select TBD or Cancelled for Project Start Date'); 
		totalErrors++;
	}
	if(document.getElementById('dEnd').value == '' && document.getElementById('idEndStatus1').checked==false && document.getElementById('idEndStatus2').checked==false) {
		errCustom('','You must either enter a value or select TBD or Cancelled for Store Opens'); 
		totalErrors++;
	}

	if(document.getElementById('dBegin').value != '' && document.getElementById('dEnd').value != '') {
		var beg = document.getElementById('dBegin').value.split('/');
		var end = document.getElementById('dEnd').value.split('/');
		var b = new Date(beg[2],beg[0],beg[1]);
		var e = new Date(end[2],end[0],end[1]);
		if(b >= e) { errCustom('','Project Start Date must be greater than the Store Opens Date'); totalErrors++; }
	}
		
	return (totalErrors == 0 ? true : false);
}