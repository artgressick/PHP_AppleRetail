document.write('<script type="text/javascript" src="'+ BF + 'calendar/includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
	totalErrors = 0;
		if(errEmpty('chrCalendarEvent', "You must enter a Calendar Event Name.")) { totalErrors++; }
		if(errEmpty('dBegin', "You must enter a Date.")) { totalErrors++; }
		if(errEmpty('idCalendarType', "You must choose a Calendar Type.")) { totalErrors++; }
	return (totalErrors == 0 ? true : false);
}