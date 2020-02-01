document.write('<script type="text/javascript" src="'+ BF + 'calendar/includes/forms.js"></script>');
var totalErrors = 0;
function error_check() {
	if(totalErrors != 0) { reset_errors(); }  
	
	totalErrors = 0;
		if(errEmpty('chrNSOTask', "You must enter a NSO Task Name.")) { totalErrors++; }
		if(errEmpty('idNSOType', "You must choose a NSO Type.")) { totalErrors++; }
		if(errEmpty('intDateOffset', "You must enter a Date Offset.")) { totalErrors++; }
	return (totalErrors == 0 ? true : false);
}